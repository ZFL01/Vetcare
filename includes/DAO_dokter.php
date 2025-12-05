<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Hashids\Hashids;

function hashId($id, bool $encode = true)
{
    $hashId = new Hashids(SALT_HASH, HASH_LENGTH);
    return $encode ? $hashId->encode($id) : $hashId->decode($id)[0];
}

include_once 'database.php';

class DTO_kateg implements JsonSerializable
{
    function __construct(private ?int $id = null, private ?string $namaKateg = null)
    {
    }
    function getIdK()
    {
        return $this->id;
    }
    function getNamaKateg()
    {
        return $this->namaKateg;
    }
    function jsonSerialize(): mixed
    {
        return [
            'id_kategori' => $this->id,
            'nama_kateg' => $this->namaKateg,
        ];
    }
}

class DTO_jadwal
{
    function __construct(private ?string $hari = null, private ?string $buka = null, private ?string $tutup = null)
    {
    }
    function getHari()
    {
        return $this->hari;
    }
    function getBuka()
    {
        return $this->buka;
    }
    function getTutup()
    {
        return $this->tutup;
    }
}

class DTO_dokter implements JsonSerializable
{
    private ?string $ttl = null; //admin dan dokter
    private ?string $strv = null; //dokter dan user {single}
    private ?string $exp_strv = null; //admin dan dokter
    private ?string $sip = null; //dokter
    private ?string $exp_sip = null; //admin dan dokter
    private ?string $pathSIP = null, $pathSTRV = null;
    private ?string $namaKlinik = null; //user dan dokter {single}
    private ?string $kab = null; //user, dokter, admin {single}
    private ?string $prov = null; //user, dokter, admin {single}
    private ?array $koor = null; //user dan dokter {single}
    private ?string $status = null;//dokter

    function __construct(
        private null|int|string $id_dokter = null, //validasi
        private ?string $nama = null, //dokter, user, admin
        private ?string $foto = null, //user, dokter
        private ?int $pengalaman = null, //user, dokter
        private ?float $rate = null, //dokter, user
        private ?array $kategori = null, //dokter, user, admin
        private ?array $jadwal = null, //user, dokter
        private ?string $harga = null //dokter, user, admin
    ) {
    }


    function setInfoDokter(array $dat)
    { //pasien side : ajax
        if (isset($dat['strv'])) {
            $this->strv = $dat['strv'];
        }
        if (isset($dat['kabupaten']) && isset($dat['provinsi'])) {
            $this->kab = $dat['kabupaten'];
            $this->prov = $dat['provinsi'];
        }

        $this->namaKlinik = $dat['nama_klinik'];
        $this->koor = isset($dat['lat'], $dat['long'])
            ? [$dat['lat'], $dat['long']] : null;
    }

    function upsertDokter(
        $id,
        $nama,
        $ttl,
        $strv = null,
        $exp_strv = null,
        $sip = null,
        $exp_sip = null,
        $foto = null,
        $pengalaman = null,
        $kab = null,
        $prov = null,
        $harga = null
    ) {
        $this->id_dokter = $id;
        $this->nama = $nama;
        $this->ttl = $ttl;
        $this->strv = $strv;
        $this->exp_strv = $exp_strv;
        $this->sip = $sip;
        $this->exp_sip = $exp_sip;
        $this->foto = $foto;
        $this->pengalaman = $pengalaman;
        $this->kab = $kab;
        $this->prov = $prov;
        $this->harga = $harga;
    }

    function setDoc($sip = null, $expSIP = null, $strv = null, $expSTRV = null)
    {
        $this->sip = $sip;
        $this->exp_sip = $expSIP;
        $this->strv = $strv;
        $this->exp_strv = $expSTRV;
    }
    function setDocPath($pathSIP, $pathSTRV)
    {
        $this->pathSIP = $pathSIP;
        $this->pathSTRV = $pathSTRV;
    }
    function setTTL($ttl = null)
    {
        $this->ttl = $ttl;
    }
    function setAlamat($kab = null, $prov = null)
    {
        $this->kab = $kab;
        $this->prov = $prov;
    }
    function setStatus($status)
    {
        $this->status = $status;
    }

    function getId()
    {
        return $this->id_dokter;
    }
    function getNama()
    {
        return $this->nama;
    }
    function getTTL()
    {
        return $this->ttl;
    }
    function getSTRV()
    {
        return $this->strv;
    }
    function getExp_STRV()
    {
        return $this->exp_strv;
    }
    function getSIP()
    {
        return $this->sip;
    }
    function getExp_SIP()
    {
        return $this->exp_sip;
    }
    function getPathSIP()
    {
        return $this->pathSIP;
    }
    function getPathSTRV()
    {
        return $this->pathSTRV;
    }
    function getFoto()
    {
        return $this->foto;
    }
    function getPengalaman()
    {
        return $this->pengalaman;
    }
    function getRate()
    {
        return $this->rate;
    }
    function getKategori()
    {
        return $this->kategori;
    }
    function getJadwal()
    {
        return $this->jadwal;
    }
    function getNamaKlinik()
    {
        return $this->namaKlinik;
    }
    function getKab()
    {
        return $this->kab;
    }
    function getProv()
    {
        return $this->prov;
    }
    function getHarga()
    {
        return $this->harga;
    }
    function getKoor()
    {
        return $this->koor;
    }
    function getStatus()
    {
        return $this->status;
    }

    function jsonSerialize(): mixed
    {
        return [
            'id' => hashId($this->id_dokter, true),
            'nama' => $this->nama,
            'foto' => $this->foto,
            'pengalaman' => $this->pengalaman,
            'rate' => $this->rate,
            'kategori' => $this->kategori,
            'jadwal' => $this->jadwal,
            'strv' => $this->strv,
            'klinik' => $this->namaKlinik,
            'kabupaten' => $this->kab,
            'provinsi' => $this->prov,
            'koor' => $this->koor,
            'harga' => $this->harga
        ];
    }
}

class DAO_kategori
{
    static function getAllKategori()
    {
        $conn = Database::getConnection();
        $sql = "select * from m_kategori";
        $kateg = [];
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $hasil = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (empty($hasil)) {
                return [];
            }

            foreach ($hasil as $row) {
                $kateg[] = new DTO_kateg($row['id_kategori'], $row['nama_kateg']);
            }
            return $kateg;
        } catch (PDOException $e) {
            error_log("[DAO_dokter::getAllKategori] : " . $e->getMessage(), 3, ERROR_LOG_FILE);
            return [];
        }
    }

    static function newKategori(DTO_kateg $dat)
    {
        $conn = Database::getConnection();
        $sql = 'insert into m_kategori (nama_kateg) values (?)';
        try {
            $stmt = $conn->prepare($sql);
            return $stmt->execute([$dat->getNamaKateg()]);
        } catch (PDOException $e) {
            error_log("[DAO_dokter::newKategori] : " . $e->getMessage(), 3, ERROR_LOG_FILE);
            return false;
        }
    }

    static function updateKateg(DTO_kateg $dat)
    {
        $conn = Database::getConnection();
        $sql = 'update m_kategori set nama_kateg=? where id_kategori=?';
        try {
            $stmt = $conn->prepare($sql);
            return $stmt->execute([$dat->getNamaKateg(), $dat->getIdK()]);
        } catch (PDOException $e) {
            error_log("[DAO_dokter::updateKateg] : " . $e->getMessage(), 3, ERROR_LOG_FILE);
            return false;
        }
    }

    static function delKateg(DTO_kateg $dat)
    {
        $conn = Database::getConnection();
        $sql = 'delete from m_kategori where id_kategori=?';
        try {
            $stmt = $conn->prepare($sql);
            return $stmt->execute([$dat->getIdK()]);
        } catch (PDOException $e) {
            error_log("[DAO_dokter::delKateg] : " . $e->getMessage(), 3, ERROR_LOG_FILE);
            return false;
        }
    }

}

class DAO_dokter
{
    private static function mapArray(array $dat, ?array $kateg = null, ?array $jadwal = null): DTO_dokter
    {
        $id = $dat['id_dokter'] ?? null;
        $obj = new DTO_dokter(
            $id,
            $dat['nama_dokter'] ?? null,
            $dat['foto'] ?? null,
            $dat['pengalaman'] ?? null,
            $dat['rate'],
            $kateg,
            $jadwal,
            $dat['harga'] ?? null
        );
        $obj->setDoc(
            $dat['sip'] ?? null,
            $dat['exp_sip'] ?? null,
            $dat['strv'] ?? null,
            $dat['exp_strv'] ?? null
        ); //self dokter dan tabel admin
        //visualisasi data
        $obj->setTTL($dat['ttl'] ?? null);
        $obj->setAlamat($dat['kabupaten'] ?? null, $dat['provinsi'] ?? null);
        $obj->setStatus($dat['status'] ?? null);
        return $obj;
    }

    static function getAllDokter()
    {
        $conn = Database::getConnection();
        try {
            $queryDokter = "select id_dokter, nama_dokter, foto, pengalaman, rate, harga, kabupaten, provinsi
            from m_dokter where status='aktif'";

            $stmt = $conn->prepare($queryDokter);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($results)) {
                return [];
            }

            $groupId = [];
            $listIdValid = [];
            foreach ($results as $row) {
                $id = $row['id_dokter'];
                $groupId[$id] = $row;
                $groupId[$id]['jadwal'] = [];
                $groupId[$id]['kateg'] = [];
                $listIdValid[] = $id;
            }

            $idValid = implode(',', $listIdValid);

            $queryJadwal = "select id_dokter, hari, buka, tutup from m_hpraktik where id_dokter in (" . $idValid . ")";
            $queryKategori = "select dd.id_dokter, k.nama_kateg from m_kategori as k
            inner join detail_dokter as dd on k.id_kategori=dd.id_kategori where dd.id_dokter in (" . $idValid . ")";

            $stmt = $conn->query($queryJadwal);
            $hasilJadwal = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = $conn->query($queryKategori);
            $hasilKateg = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($hasilJadwal as $row) {
                $id = $row['id_dokter'];
                $hari = $row['hari'];

                if (isset($groupId[$id])) {
                    $groupId[$id]['jadwal'][$hari][] = [
                        'buka' => $row['buka'],
                        'tutup' => $row['tutup']
                    ];
                }
            }

            foreach ($hasilKateg as $row) {
                $id = $row['id_dokter'];
                if (isset($groupId[$id])) {
                    $groupId[$id]['kateg'][] = $row['nama_kateg'];
                }
            }

            $DTO_dokter = [];
            foreach ($groupId as $id => $data) {
                $obj = self::mapArray($data, $data['kateg'], $data['jadwal']);
                $DTO_dokter[] = $obj;
            }
            return $DTO_dokter;

        } catch (PDOException $e) {
            error_log("DAO_dokter::getAllDokter :" . $e->getMessage(), 3, ERROR_LOG_FILE);
            return [];
        }
    }

    static function getTop3Dokter()
    {
        $conn = Database::getConnection();
        $sql = 'select id_dokter, nama_dokter, foto, pengalaman, rate, harga from m_dokter
                where status="aktif" order by rate desc limit 3';
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $hasil = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $dokterIds = array_column($hasil, 'id_dokter');
            $placeholders = implode(',', array_fill(0, count($dokterIds), '?'));

            $sql2 = "SELECT 
                            dd.id_dokter, 
                            k.nama_kateg 
                        FROM detail_dokter dd 
                        JOIN m_kategori k ON k.id_kategori = dd.id_kategori 
                        WHERE dd.id_dokter IN ({$placeholders})";

            $stmt = $conn->prepare($sql2);
            $stmt->execute($dokterIds);
            $hasilKateg = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $map = [];
            foreach ($hasilKateg as $row) {
                $id = $row['id_dokter'];
                if (!isset($map[$id])) {
                    $map[$id] = [];
                }
                $map[$id][] = $row['nama_kateg'];
            }

            $obj = [];
            foreach ($hasil as $row) {
                $id = $row['id_dokter'];
                $kateg = $map[$id] ?? [];
                $obj[] = self::mapArray($row, $kateg);
            }
            return $obj;
        } catch (PDOException $e) {
            error_log("DAO_dokter::getTop3Dokter :" . $e->getMessage(), 3, ERROR_LOG_FILE);
            return [];
        }
    }

    static function allDoktersLocations()
    {
        $conn = Database::getConnection();
        $sql = 'select 
                l.*, 
                d.nama_dokter,
                d.kabupaten,
                d.provinsi 
           from m_lokasipraktik as l 
           inner join m_dokter as d on d.id_dokter=l.dokter
           where d.status="aktif"';
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $hasil = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $hasil;
        } catch (PDOException $e) {
            error_log('[DAO_dokter::allDoktersLocations]: ' . $e->getMessage(), 3, ERROR_LOG_FILE);
            return false;
        }
    }

    static function manageDokter(DTO_dokter $data)
    {
        $conn = Database::getConnection();
        try {
            $sql = 'select * from m_doc_dokter where id_dokter = ?';
            $stmt = $conn->prepare($sql);
            $stmt->execute([$data->getId()]);
            $hasil = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($hasil == null) {
                return null;
            }
            $data->setDocPath($hasil['path_sip'], $hasil['path_strv']);
            return true;
        } catch (PDOException $e) {
            error_log("[DAO_dokter::manageDokter] : " . $e->getMessage(), 3, ERROR_LOG_FILE);
            return false;
        }
    }

    static function tabelAdmin()
    {
        $conn = Database::getConnection();
        try {
            $queryDokter = "select d.id_dokter, d.nama_dokter, d.ttl, d.exp_strv,
            d.exp_sip, d.kabupaten, d.provinsi, d.rate, d.status from m_dokter as d order by d.id_dokter desc";

            $stmt = $conn->prepare($queryDokter);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($results)) {
                return [];
            }

            $groupId = [];
            foreach ($results as $row) {
                $id = $row['id_dokter'];
                $groupId[$id] = $row;
                $groupId[$id]['kateg'] = [];
            }

            $queryKategori = "select dd.id_dokter, k.nama_kateg from m_kategori as k
            inner join detail_dokter as dd on k.id_kategori=dd.id_kategori order by dd.id_dokter desc";

            $stmt = $conn->query($queryKategori);
            $hasilKateg = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($hasilKateg as $row) {
                $id = $row['id_dokter'];
                if (isset($groupId[$id])) {
                    $groupId[$id]['kateg'][] = $row['nama_kateg'];
                }
            }

            $DTO_dokter = [];
            foreach ($groupId as $id => $data) {
                $DTO_dokter[] = self::mapArray($data, $data['kateg']);
            }
            return $DTO_dokter;

        } catch (PDOException $e) {
            error_log("DAO_dokter::getAllDokter :" . $e->getMessage(), 3, ERROR_LOG_FILE);
            return [];
        }
    }

    static function getProfilDokter(?DTO_pengguna $data = null, bool $initiate = true, int $idDokter = 0)
    {//dokter profil
        $conn = Database::getConnection();
        $id = $idDokter > 0 ? $idDokter : $data->getIdUser();
        try {
            $sql = "select * from m_dokter where id_dokter=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id]);
            $profil = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($profil == null) {
                return false;
            }

            $dokter = new DTO_dokter($profil['id_dokter'], $profil['nama_dokter'], $profil['foto'], rate: $profil['rate']);
            if ($initiate) {
                return $dokter;
            }


            $sql = "select k.id_kategori as idK, k.nama_kateg as namaK from m_kategori as k join detail_dokter as dd
            on dd.id_kategori=k.id_kategori where dd.id_dokter=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$data->getIdUser()]);
            $kateg = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $dokter = new DTO_dokter(
                $profil['id_dokter'],
                $profil['nama_dokter'],
                $profil['foto'],
                $profil['pengalaman'],
                $profil['rate'],
                $kateg,
                harga: $profil['harga']
            );
            if ($idDokter > 0 && $initiate === false) {
                return $dokter;
            }
            $dokter->setAlamat($profil['kabupaten'], $profil['provinsi']);
            $dokter->setDoc($profil['sip'], $profil['exp_sip'], $profil['strv'], $profil['exp_strv']);
            $dokter->setTTL($profil['ttl']);
            $dokter->setStatus($profil['status']);
            return $dokter;
        } catch (PDOException $e) {
            error_log('[DAO_Dokter::getProfilDokter]: ' . $e->getMessage(), 3, ERROR_LOG_FILE);
            return false;
        }
    }

    static function getAlamat(DTO_dokter $dat)
    {
        $conn = Database::getConnection();
        try {
            $sql = "select * from m_lokasipraktik where dokter=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$dat->getId()]);
            $hasil = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$hasil) {
                return false;
            }
            $dat->setInfoDokter($hasil);
            return true;
        } catch (PDOException $e) {
            error_log('[DAO_dokter::getAlamat]: ' . $e->getMessage(), 3, ERROR_LOG_FILE);
            return false;
        }
    }

    static function getJadwal(DTO_dokter $dat)
    {
        $conn = Database::getConnection();
        $jadwal = [];
        try {
            $sql = "select * from m_hpraktik where id_dokter=? order by hari asc, buka asc";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$dat->getId()]);
            $hasil = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (empty($hasil)) {
                return [];
            }

            foreach ($hasil as $row) {
                $hari = $row['hari'];
                $obj = new DTO_jadwal($hari, $row['buka'], $row['tutup']);

                $jadwal[$hari][] = $obj;
            }
            return $jadwal;
        } catch (PDOException $e) {
            error_log('[DAO_dokter::getJadwal]: ' . $e->getMessage(), 3, ERROR_LOG_FILE);
            return [];
        }
    }

    static function getInfoDokter(DTO_dokter $dat)
    { //single user
        $conn = Database::getConnection();
        try {
            $query = "select d.strv, loc.lat, loc.long, loc.nama_klinik
            from m_dokter as d inner join m_lokasipraktik as loc
            on d.id_dokter = loc.dokter where d.id_dokter = ?";

            $stmt = $conn->prepare($query);
            $stmt->execute([$dat->getId()]);
            $detail = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$detail) {
                return null;
            }
            $dat->setInfoDokter($detail);
            return true;
        } catch (PDOException $e) {
            error_log("DAO_dokter::getInfoDokter :" . $e->getMessage(), 3, ERROR_LOG_FILE);
            return false;
        }
    }

    static function insertDokter(DTO_dokter $data, array $datKateg)
    { //register kedua dokter
        $conn = Database::getConnection();

        $query = "INSERT INTO m_dokter (
                    id_dokter, 
                    nama_dokter, 
                    ttl, 
                    foto, 
                    pengalaman,
                    kabupaten,
                    provinsi 
                  ) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $sqlDocuments = "insert into m_doc_dokter (id_dokter, path_sip, path_strv) values (?,?,?)";
        $paramDoc = [
            $data->getId(),
            $data->getSIP(),
            $data->getSTRV()
        ];

        // Pastikan jumlah parameter di sini ada 10, sama dengan jumlah tanda tanya (?) di atas
        $params = [
            $data->getId(),          // 1. id_dokter
            $data->getNama(),        // 2. nama_dokter
            date('Y-m-d', strtotime($data->getTTL())),         // 3. ttl
            $data->getFoto(),        // 4. foto
            $data->getPengalaman(),  // 5. pengalaman
            $data->getKab(), // 6. kabupaten
            $data->getProv()   // 7. provinsi
        ];

        try {
            $conn->beginTransaction();

            $stmt = $conn->prepare($query);
            $mDokter = $stmt->execute($params);
            $stmtDoc = $conn->prepare($sqlDocuments);
            $stmtDoc->execute($paramDoc);

            // Panggil fungsi set kategori
            $detDok = self::setKategDokter(false, $data->getId(), $datKateg);

            $conn->commit();
            return $detDok && $mDokter && $stmtDoc;
        } catch (PDOException $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            error_log("DAO_dokter::insertDokter :" . $e->getMessage(), 3, ERROR_LOG_FILE);
            return false;
        }
    }

    static function setKategDokter(bool $update, $idDokter, array $datKateg)
    {

        $conn = Database::getConnection();
        $sql = "insert into detail_dokter values (?,?)";
        $sqlDel = "delete from detail_dokter where id_dokter =?";
        try {
            if ($update) {
                $conn->beginTransaction();
            }
            if ($update) {
                $stmt = $conn->prepare($sqlDel);
                $stmt->execute([$idDokter]);
            }
            foreach ($datKateg as $pk) {
                $params = [$idDokter, $pk->getIDK()];
                $stmt = $conn->prepare($sql);
                $stmt->execute($params);
            }
            if ($update) {
                $conn->commit();
            }
            return true;
        } catch (PDOException $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            error_log("DAO_dokter::setkategDokter {$update} : " . $e->getMessage(), 3, ERROR_LOG_FILE);
            return false;
        }
    }

    static function setJadwal(int $idDokter, array $jdwlterstruktur)
    {
        $conn = Database::getConnection();
        $queryInsert = "INSERT INTO m_hpraktik (id_dokter, hari, buka, tutup) VALUES (?, ?, ?, ?)";
        $queryDelete = "DELETE FROM m_hpraktik WHERE id_dokter = ?";

        try {
            $conn->beginTransaction();

            // 1. Hapus semua jadwal lama dokter ini
            $stmtDel = $conn->prepare($queryDelete);
            if (!$stmtDel->execute([$idDokter])) {
                $conn->rollBack();
                return false;
            }

            // 2. Loop dan Sisipkan Sesi Baru
            $stmtInsert = $conn->prepare($queryInsert);
            foreach ($jdwlterstruktur as $dayName => $sessions) {
                foreach ($sessions as $session) {
                    $buka = $session['buka'];
                    $tutup = $session['tutup'];

                    if (!$stmtInsert->execute([$idDokter, $dayName, $buka, $tutup])) {
                        $conn->rollBack();
                        return false;
                    }
                }
            }

            $conn->commit();
            return true;
        } catch (PDOException $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            error_log("DAO_dokter::setJadwalStructured Error: " . $e->getMessage(), 3, ERROR_LOG_FILE);
            return false;
        }
    }

    static function setLokasi(bool $update, DTO_dokter $data, $nKlinik, $lat, $long)
    {
        $conn = Database::getConnection();
        $sql = "insert into m_lokasipraktik values (" . $data->getId() . ",?,?,?)";
        if ($update) {
            $sql = "update m_lokasipraktik set nama_klinik=?,
            lat=?, `long`=? where dokter= " . $data->getId();
        }
        try {
            if ($nKlinik === null) {
                $stmt = $conn->prepare("delete from m_lokasipraktik where dokter=?");
                $hasil = $stmt->execute([$data->getId()]);
                return [$hasil, 'hapus'];
            }
            $stmt = $conn->prepare($sql);
            $hasil = $stmt->execute([$nKlinik, $lat, $long]);
            return [$hasil, 'update/insert'];
        } catch (PDOException $e) {
            error_log("[DAO_dokter::setLokasi] {$update} : " . $e->getMessage(), 3, ERROR_LOG_FILE);
            return [false, "error saat {$update} : " . $e->getMessage(), 3, ERROR_LOG_FILE];
        }
    }

    //update

    static function updateDocument(DTO_dokter $data)
    {
        $conn = Database::getConnection();
        $sql = "update m_doc_dokter set path_sip=?, path_strv=? where id_dokter=?";
        try {
            $stmt = $conn->prepare($sql);
            return $stmt->execute([
                $data->getSIP(),
                $data->getSTRV(),
                $data->getId()
            ]);
        } catch (PDOException $e) {
            error_log("DAO_dokter::updateDocument: " . $e->getMessage(), 3, ERROR_LOG_FILE);
            return [false, $e->getMessage(), 3, ERROR_LOG_FILE];
        }
    }

    static function updateDokter(int $idDokter, DTO_dokter $data, $status, bool $admin = false)
    {
        $conn = Database::getConnection();
        $sqlDokter = "update m_dokter set nama_dokter =?, ttl=?, pengalaman=?, kabupaten=?, provinsi=?, harga=? where id_dokter=?";
        $sqlAdmin = "update m_dokter set strv=?, exp_strv=?, sip=?, exp_sip=?, status=? where id_dokter=?";

        try {
            if ($admin) {
                $stmt = $conn->prepare($sqlAdmin);
                return $stmt->execute([
                    $data->getSTRV(),
                    $data->getExp_STRV(),
                    $data->getSIP(),
                    $data->getExp_SIP(),
                    $status,
                    $idDokter
                ]);
            } else {
                $stmt = $conn->prepare($sqlDokter);
                return $stmt->execute([
                    $data->getNama(),
                    date('Y-m-d', strtotime($data->getTTL())),
                    $data->getPengalaman(),
                    $data->getKab(),
                    $data->getProv(),
                    $data->getHarga(),
                    $idDokter
                ]);
            }
        } catch (PDOException $e) {
            error_log("DAO_dokter::updateDokter {admin = $admin}: " . $e->getMessage(), 3, ERROR_LOG_FILE);
            return [false, $e->getMessage(), 3, ERROR_LOG_FILE];
        }
    }

    static function deleteDokter($idDokter)
    {
        $conn = Database::getConnection();
        $sql = [
            'sql1' => "delete from detail_dokter where id_dokter =?",
            'sql2' => "delete from m_hpraktik where id_dokter =?",
            'sql3' => "delete from m_lokasipraktik where dokter =?",
            'sql4' => "delete from m_dokter where id_dokter = ?",
            'sql5' => "delete from m_pengguna where id_pengguna =?"
        ];
        try {
            $conn->beginTransaction();
            foreach ($sql as $x => $n) {
                $stmt = $conn->prepare($n);
                $stmt->execute([$idDokter]);
            }
            return $conn->commit();
        } catch (PDOException $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            error_log("DAO_dokter::deleteDokter" . $e->getMessage(), 3, ERROR_LOG_FILE);
            return false;
        }
    }
}
?>