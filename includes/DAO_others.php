<?php

class Location
{

    function __construct(
        private ?int $idLocation = null,
        private ?string $coor = null,
        private ?array $location = null,
        private ?int $id_User = null,
        private ?int $time = null
    ) {
    }
    function getIdLocation()
    {
        return $this->idLocation;
    }
    function getCoor()
    {
        return $this->coor;
    }
    function getLocation()
    {
        return $this->location;
    }
    function getId_User()
    {
        return $this->id_User;
    }
    function getTime()
    {
        return $this->time;
    }
}

class DTO_chat implements JsonSerializable
{
    function __construct(
        private ?string $idChat = null,
        private ?int $idUser = null,
        private string|int|null $idDokter = null,
        private ?string $email = null,
        private ?string $namaDokter = null,
        private ?string $fotoDokter = null,
        private ?string $waktuSelesai = null,
        private ?string $status = null,
        private ?string $waktuMulai = null,
    ) {
    }
    function getIdChat()
    {
        return $this->idChat;
    }
    function getIdUser()
    {
        return $this->idUser;
    }
    function getIdDokter()
    {
        return $this->idDokter;
    }
    function getEmail()
    {
        return censorEmail($this->email);
    }
    function getNamaDokter()
    {
        return $this->namaDokter;
    }
    function getFotoDokter()
    {
        return $this->fotoDokter;
    }
    function getStatus()
    {
        return $this->status;
    }
    function getWaktuSelesai()
    {
        return $this->waktuSelesai;
    }
    function getWaktuMulai()
    {
        return $this->waktuMulai;
    }
    function jsonSerialize(): mixed
    {
        return [
            'idChat' => $this->idChat,
            'idUser' => $this->idUser,
            'idDokter' => $this->idDokter,
            'email' => censorEmail($this->email),
            'namaDokter' => $this->namaDokter,
            'fotoDokter' => $this->fotoDokter,
            'waktuSelesai' => $this->waktuSelesai,
            'status' => $this->status,
            'waktuMulai' => $this->waktuMulai
        ];
    }
}

include_once 'database.php';
require_once 'userService.php';
require_once 'DAO_dokter.php';

class DAO_location
{
    static function getAllLocation()
    {
        $conn = Database::getConnection();
        $sql = 'select * from log_location';
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $hasil = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (empty($hasil)) {
                return [];
            }
            $dto = [];
            foreach ($hasil as $dat) {
                $obj = new Location($dat['id'], $dat['koor'], [$dat['kabupaten'], $dat['provinsi']], $dat['id_user'], $dat['timestamp']);
                $dto[] = $obj;
            }
            return $dto;
        } catch (PDOException $e) {
            custom_log("[DAO_location::getAllLocation]: " . $e->getMessage(), LOG_TYPE::ERROR);
            return [];
        }
    }
    static function insertLocation(Location $loc)
    {
        $conn = Database::getConnection();
        $sql = "insert into log_location (koor, kabupaten, provinsi, id_user) values (?, ?, ?, ?)";
        $params = [$loc->getCoor(), $loc->getLocation()[0], $loc->getLocation()[1], $loc->getId_User()];
        $ada = !empty($loc->getIdLocation());

        if ($ada) {
            $sql = 'update log_location set id_user=? where id=?';
            $params = [$loc->getId_User(), $loc->getIdLocation()];
        }
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            return $ada ? true : $conn->lastInsertId();
        } catch (PDOException $e) {
            custom_log("[DAO_location::insertLocation]: " . $e->getMessage(), LOG_TYPE::ERROR);
            return false;
        }
    }
}

class DAO_chat
{
    private const sesi_durasi = '+12 hours';
    static function getAllChats(?int $idUser = null, ?int $idDokter = null, ?bool $now = false)
    {
        $conn = Database::getConnection();
        $sql = "";
        $param = [];
        if ($idUser) {
            $sql = "SELECT T1.id_tr, T1.dokter_id, D.nama_dokter, L.end AS waktu_selesai, T1.paid_at AS waktu_mulai_terbaru
            FROM tr_transaksi T1
            JOIN (
                SELECT dokter_id, MAX(paid_at) AS waktu_terbaru 
                FROM tr_transaksi 
                WHERE user_id = ? GROUP BY dokter_id
            ) T2 ON T1.dokter_id = T2.dokter_id AND T1.paid_at = T2.waktu_terbaru
            LEFT JOIN log_rating L ON T1.id_tr = L.idChat
            JOIN m_dokter D ON T1.dokter_id = D.id_dokter
            WHERE T1.user_id = ?
            ORDER BY waktu_mulai_terbaru DESC;";
            $param = [$idUser, $idUser];
        } elseif ($idDokter) {
            if ($now) {
                $sql = "SELECT 
                    T1.id_tr, 
                    T1.user_id, 
                    U.email AS user_email,
                    L.end AS waktu_selesai, 
                    T1.paid_at AS waktu_mulai_terbaru
                FROM tr_transaksi T1
                JOIN (
                    SELECT user_id, MAX(paid_at) AS waktu_terbaru 
                    FROM tr_transaksi 
                    WHERE dokter_id = ? GROUP BY user_id
                ) T2 ON T1.user_id = T2.user_id AND T1.paid_at = T2.waktu_terbaru
                LEFT JOIN log_rating L ON T1.id_tr = L.idChat
                JOIN m_pengguna U ON T1.user_id = U.id_pengguna
                WHERE T1.dokter_id = ? 
                ORDER BY waktu_mulai_terbaru DESC;";
                $param = [$idDokter, $idDokter];
            } else {
                $sql = "SELECT 
                    T1.id_tr, 
                    T1.user_id, 
                    U.email AS user_email,
                    L.end AS waktu_selesai, 
                    T1.paid_at AS waktu_mulai_terbaru
                FROM tr_transaksi T1
                JOIN (
                    SELECT user_id, MAX(paid_at) AS waktu_terbaru 
                    FROM tr_transaksi 
                    WHERE dokter_id = ? GROUP BY user_id
                ) T2 ON T1.user_id = T2.user_id AND T1.paid_at = T2.waktu_terbaru
                LEFT JOIN log_rating L ON T1.id_tr = L.idChat
                JOIN m_pengguna U ON T1.user_id = U.id_pengguna
                WHERE T1.dokter_id = ?
                ORDER BY waktu_mulai_terbaru DESC;";
                $param = [$idDokter, $idDokter];
            }
        } else {
            return [];
        }
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute($param);
            $hasil = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (empty($hasil)) {
                return [];
            }
            $idChats = array_column($hasil, 'id_tr');
            $dto = [];
            foreach ($hasil as $dat) {
                if ($idUser) {
                    $obj = new DTO_chat(
                        $dat['id_tr'],
                        namaDokter: $dat['nama_dokter'],
                        waktuSelesai: $dat['waktu_selesai'],
                        waktuMulai: $dat['waktu_mulai_terbaru'],
                    );
                } else {
                    $obj = new DTO_chat(
                        $dat['id_tr'],
                        email: $dat['user_email'],
                        waktuSelesai: $dat['waktu_selesai'],
                        waktuMulai: $dat['waktu_mulai_terbaru'],
                    );
                }
                $dto[] = $obj;
            }
            return [$dto, $idChats];
        } catch (PDOException $e) {
            custom_log("[DAO_others::getAllChats] {$idUser} or {$idDokter}: " . $e->getMessage(), LOG_TYPE::ERROR);
            return [];
        }
    }

    static function findChatRoom($idDokter, $idUser): DTO_chat|null
    {
        $conn = Database::getConnection();
        $sql = 'select t.id_tr, t.user_id, t.dokter_id, l.end from tr_transaksi t inner join
        log_rating l on l.idChat=t.id_tr where t.user_id=? and t.dokter_id=? and
        (t.status !="expired" OR t.status !="failed")
        order by t.id_tr desc';
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute([$idUser, $idDokter]);
            $hasil = $stmt->fetch(PDO::FETCH_ASSOC);
            if (empty($hasil)) {
                return null;
            } else {
                $obj = new DTO_chat(
                    $hasil['id_tr'],
                    $hasil['user_id'],
                    $hasil['dokter_id'],
                    waktuSelesai: $hasil['end']
                );
            }
            return $obj;
        } catch (PDOException $e) {
            custom_log("[DAO_others::findChatRoom]: " . $e->getMessage(), LOG_TYPE::ERROR);
            return null;
        }
    }

    static function thisChatRoom($idChat, $idUser, $idDokter = null)
    {
        if($idDokter !== null) {
            $idUser = null;
        }
        $conn = Database::getConnection();
        $sql = 'select d.nama_dokter, d.foto, u.email, t.created, t.status, t.user_id, t.dokter_id from tr_transaksi as t
        inner join m_dokter as d on t.dokter_id=d.id_dokter
        inner join m_pengguna as u on t.user_id=u.id_pengguna where t.id_tr=?';
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute([$idChat]);
            $hasil = $stmt->fetch(PDO::FETCH_ASSOC);
            if (empty($hasil)) {
                return null;
            } else {

                if ($idDokter !== null && $hasil['dokter_id'] != $idDokter) {
                    return null;
                }elseif ($idUser !== null && $hasil['user_id'] != $idUser) {
                    return null;
                }

                $created = $hasil['created'];
                $selesai = strtotime($created . self::sesi_durasi);
                $end = date('Y-m-d H:i:s', $selesai);

                $obj = new DTO_chat(
                    $idChat,
                    $hasil['user_id'],
                    hashId($hasil['dokter_id'], true),
                    email: $hasil['email'],
                    namaDokter: $hasil['nama_dokter'],
                    fotoDokter: $hasil['foto'],
                    status: $hasil['status'],
                    waktuSelesai: $end,
                    waktuMulai: $created
                );
            }
            return $obj;
        } catch (PDOException $e) {
            custom_log("[DAO_others::thisChatRoom]: " . $e->getMessage(), LOG_TYPE::ERROR);
            return null;
        }
    }

    static function getParticipantsByChatId(string $idChat)
    {
        $conn = Database::getConnection();
        $sql = 'select user_id, dokter_id from tr_transaksi where id_tr = ?';
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute([$idChat]);
            $hasil = $stmt->fetch(PDO::FETCH_ASSOC);
            if (empty($hasil)) {
                return null;
            }
            return $hasil;
        } catch (PDOException $e) {
            custom_log("[DAO_others::getParticipantsByChatId]: " . $e->getMessage(), LOG_TYPE::ERROR);
            return null;
        }
    }

    static function getRating(int $idDokter)
    {
        $conn = Database::getConnection();
        $sql = "select count(*) as total, sum(l.`liked?`) as suka from log_rating l
        join tr_transaksi t on l.idChat = t.id_tr
        where t.dokter_id=?";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute([$idDokter]);
            $hasil = $stmt->fetch(PDO::FETCH_ASSOC);
            return $hasil;
        } catch (PDOException $e) {
            custom_log("[DAO_others::getRating]: " . $e->getMessage(), LOG_TYPE::ERROR);
            return [];
        }
    }
    static function registChatRoom($idChat, $user = null, $dokter=null, $created=null, $biaya=null, array $liked = ['isLiked' => false, 'rating' => true])
    {
        $start = $created;
        $end = date('Y-m-d H:i:s', strtotime($created . self::sesi_durasi));
        $hasil = true;

        $conn = Database::getConnection();
        $sql = "insert into tr_transaksi (id_tr, user_id, dokter_id, created, paid_at, biaya) values (?,?,?,?,?,?)";
        $sql2 = "insert into log_rating (idChat, `liked?`, `end`) values (?, ?, now())";
        try {
            if ($liked['isLiked']) {
                $stmt = $conn->prepare($sql2);
                $hasil = $stmt->execute([$idChat, $liked['rating']]);
            } else {
                $stmt = $conn->prepare($sql);
                $hasil = $stmt->execute([$idChat, $user, $dokter, $start, $start, $biaya]);
            }

            return $hasil;
        } catch (PDOException $e) {
            custom_log("[DAO_others::registChatRoom]: " . $e->getMessage(), LOG_TYPE::ERROR);
            return false;
        }
    }

    static function updateLogMessage($idChat, bool $liked = true)
    {
        $conn = Database::getConnection();
        $sql = "update log_rating set `end`=now(), `liked?`=? where idChat=?";
        try {
            $stmt = $conn->prepare($sql);
            return $stmt->execute([$liked, $idChat]);
        } catch (PDOException $e) {
            custom_log("[DAO_others::updateLogMessage]: " . $e->getMessage(), LOG_TYPE::ERROR);
            return false;
        }
    }
}

class ringkasanTransaksiDoker
{
    static function getRingkasan(DTO_dokter $dokter, int $tahun)
    {
        $conn = Database::getConnection();
        $sql = "select month(paid_at) as bulan, count(*) as total, sum(biaya) as total_pendapatan from tr_transaksi
        where dokter_id=? and year(paid_at)=? group by paid_at";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute([$dokter->getId(), $tahun]);
            $hasil = $stmt->fetch(PDO::FETCH_ASSOC);
            return $hasil;
        } catch (PDOException $e) {
            custom_log("[DAO_others::getRingkasan]: " . $e->getMessage(), LOG_TYPE::ERROR);
            return [];
        }
    }
    static function ringkasanMingguIni(int $dokter)
    {
        $conn = Database::getConnection();
        $sql = "select month(paid_at) as bulan, count(*) as total, sum(biaya) as total_pendapatan from tr_transaksi
        where dokter_id=? and year(paid_at)=? group by paid_at";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute([$dokter]);
            $hasil = $stmt->fetch(PDO::FETCH_ASSOC);
            return $hasil;
        } catch (PDOException $e) {
            custom_log("[DAO_others::getRingkasan]: " . $e->getMessage(), LOG_TYPE::ERROR);
            return [];
        }
    }

    static function getTotalPendapatan(int $dokter)
    {
        $conn = Database::getConnection();
        $sql = "select sum(biaya) as total_pendapatan from tr_transaksi where dokter_id=?";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute([$dokter]);
            $hasil = $stmt->fetch(PDO::FETCH_ASSOC);
            return $hasil['total_pendapatan'] ?? 0;
        } catch (PDOException $e) {
            custom_log("[DAO_others::getTotalPendapatan]: " . $e->getMessage(), LOG_TYPE::ERROR);
            return 0;
        }
    }

    static function getTotalKonsultasi(int $dokter)
    {
        $conn = Database::getConnection();
        $sql = "select count(*) as total_konsultasi from tr_transaksi where dokter_id=?";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute([$dokter]);
            $hasil = $stmt->fetch(PDO::FETCH_ASSOC);
            return $hasil['total_konsultasi'] ?? 0;
        } catch (PDOException $e) {
            custom_log("[DAO_others::getTotalKonsultasi]: " . $e->getMessage(), LOG_TYPE::ERROR);
            return 0;
        }
    }

    static function getPasienKembali(int $dokter)
    {
        $conn = Database::getConnection();
        $sql = "SELECT COUNT(*) as returning_users FROM (
            SELECT user_id FROM tr_transaksi
            WHERE dokter_id = ?
            GROUP BY user_id
            HAVING COUNT(*) > 1
        ) as subquery";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute([$dokter]);
            return $stmt->fetchColumn() ?: 0;
        } catch (PDOException $e) {
            custom_log("[DAO_others::getPasienKembali]: " . $e->getMessage(), LOG_TYPE::ERROR);
            return 0;
        }
    }

    static function getTotalPasien(int $dokter)
    {
        $conn = Database::getConnection();
        $sql = "SELECT COUNT(DISTINCT user_id) FROM tr_transaksi WHERE dokter_id = ?";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute([$dokter]);
            return $stmt->fetchColumn() ?: 0;
        } catch (PDOException $e) {
            custom_log("[DAO_others::getTotalPasien]: " . $e->getMessage(), LOG_TYPE::ERROR);
            return 0;
        }
    }

    static function getStatistikMingguan(int $dokter)
    {
        $conn = Database::getConnection();
        // 0 = Monday, 6 = Sunday
        $sql = "SELECT WEEKDAY(created) as hari, COUNT(*) as total 
                FROM tr_transaksi 
                WHERE dokter_id = ? 
                AND YEARWEEK(created, 1) = YEARWEEK(NOW(), 1) 
                GROUP BY hari";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute([$dokter]);
            return $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];
        } catch (PDOException $e) {
            custom_log("[DAO_others::getStatistikMingguan]: " . $e->getMessage(), LOG_TYPE::ERROR);
            return [];
        }
    }

    static function getStatistikBulanan(int $dokter, int $tahun)
    {
        $conn = Database::getConnection();
        // 1 = January, 12 = December
        $sql = "SELECT MONTH(created) as bulan, COUNT(*) as total 
                FROM tr_transaksi 
                WHERE dokter_id = ? 
                AND YEAR(created) = ? 
                GROUP BY bulan";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute([$dokter, $tahun]);
            return $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];
        } catch (PDOException $e) {
            custom_log("[DAO_others::getStatistikBulanan]: " . $e->getMessage(), LOG_TYPE::ERROR);
            return [];
        }
    }
}

?>