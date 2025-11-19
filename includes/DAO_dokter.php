<?php
include_once 'database.php';

class DTO_kateg implements JsonSerializable{
    function __construct(private ?int $id = null, private ?string $namaKateg = null, private ?string $fotoKateg=null){}
    function getIdK(){return $this->id;}
    function getNamaKateg(){return $this->namaKateg;}
    function getFotoKateg(){return $this->fotoKateg;}
    function jsonSerialize(): mixed {
        return [
            'id_kategori' => $this->id,
            'nama_kateg' => $this->namaKateg,
            'foto' => $this->fotoKateg
        ];
    }
}

class DTO_jadwal{
    function __construct(private ?string $hari = null, private ?string $buka = null, private ?string $tutup = null){}
    function getHari(){return $this->hari;}
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
    private ?string $namaKlinik = null; //user dan dokter {single}
    private ?string $alamat = null; //user, dokter, admin {single}
    private ?array $koor = null; //user dan dokter {single}
    private ?string $status=null;//dokter

    function __construct(
        private ?int $id_dokter = null, //validasi
        private ?string $nama = null, //dokter, user, admin
        private ?string $foto = null, //user, dokter
        private ?int $pengalaman = null, //user, dokter
        private ?float $rate = null, //dokter, user
        private ?array $kategori = null, //dokter, user, admin
        private ?array $jadwal = null //user, dokter
    ) {}


    function setInfoDokter(array $dat){ //pasien side : ajax
        if (isset($dat['strv'])) { 
        $this->strv = $dat['strv']; 
        }
        $this->namaKlinik = $dat['nama_klinik'];
        $this->alamat = $dat['alamat'];
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
        $pengalaman = null
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
    }

    function setDoc($sip = null, $expSIP = null, $strv = null, $expSTRV = null)
    {
        $this->sip = $sip;
        $this->exp_sip = $expSIP;
        $this->strv = $strv;
        $this->exp_strv = $expSTRV;
    }
    function setTTL($ttl = null)
    {
        $this->ttl = $ttl;
    }
    function setAlamat($alamat = null)
    {
        $this->alamat = $alamat;
    }
    function setStatus($status) { $this->status = $status; }

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
    function getAlamat()
    {
        return $this->alamat;
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
            'id' => $this->id_dokter, 'nama' => $this->nama,
            'foto' => $this->foto, 'pengalaman' => $this->pengalaman,
            'rate' => $this->rate, 'kategori' => $this->kategori,
            'jadwal' => $this->jadwal, 'strv' => $this->strv,
            'klinik' => $this->namaKlinik, 'alamat' => $this->alamat,
            'koor' => $this->koor
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
                $kateg[] = new DTO_kateg($row['id_kategori'], $row['nama_kateg'], $row['foto']);
            }
            return $kateg;
        } catch (PDOException $e) {
            error_log("[DAO_dokter::getAllKategori] : " . $e->getMessage());
            return [];
        }
    }

    static function newKategori(DTO_kateg $dat)
    {
        $conn = Database::getConnection();
        $sql = 'insert into m_kategori (nama_kateg, foto) values (?, ?)';
        try {
            $stmt = $conn->prepare($sql);
            return $stmt->execute([$dat->getNamaKateg(), $dat->getFotoKateg()]);
        } catch (PDOException $e) {
            error_log("[DAO_dokter::newKategori] : " . $e->getMessage());
            return false;
        }
    }

    static function updateKateg(DTO_kateg $dat)
    {
        $conn = Database::getConnection();
        $sql = 'update m_kategori set nama_kateg=?, foto=? where id_kategori=?';
        try {
            $stmt = $conn->prepare($sql);
            return $stmt->execute([$dat->getNamaKateg(), $dat->getFotoKateg(), $dat->getIdK()]);
        } catch (PDOException $e) {
            error_log("[DAO_dokter::updateKateg] : " . $e->getMessage());
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
            error_log("[DAO_dokter::delKateg] : " . $e->getMessage());
            return false;
        }
    }

}

class DAO_dokter
{
    private static function mapArray(array $dat, ?array $kateg = null, ?array $jadwal = null): DTO_dokter
    {
        $obj = new DTO_dokter(
            $dat['id_dokter'] ?? null,
            $dat['nama_dokter'] ?? null,
            $dat['foto'] ?? null,
            $dat['pengalaman'] ?? null,
            $dat['rate'],
            $kateg,
            $jadwal
        );
        $obj->setDoc(
            $dat['sip'] ?? null,
            $dat['exp_sip'] ?? null,
            $dat['strv'] ?? null,
            $dat['exp_strv'] ?? null
        ); //self dokter dan tabel admin
        //visualisasi data
        $obj->setTTL($dat['ttl'] ?? null);
        $obj->setAlamat($dat['alamat'] ?? null);
        return $obj;
    }

    static function getAllDokter()
    {
        $conn = Database::getConnection();
        try {
            $queryDokter = "select id_dokter, nama_dokter, foto, pengalaman, rate
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
                $DTO_dokter[] = self::mapArray($data, $data['kateg'], $data['jadwal']);
            }
            return $DTO_dokter;

        } catch (PDOException $e) {
            error_log("DAO_dokter::getAllDokter :" . $e->getMessage());
            return [];
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
            if ($hasil == null) {return null;}
            $data->setDoc($hasil['path_sip'], $data->getExp_SIP(), $hasil['path_strv'], $data->getExp_STRV());
            return true;
        }catch (PDOException $e) {
            error_log("[DAO_dokter::manageDokter] : " . $e->getMessage());
            return false;
        }
    }

    static function tabelAdmin()
    {
        $conn = Database::getConnection();
        try {
            $queryDokter = "select d.id_dokter, d.nama_dokter, d.ttl, d.exp_strv,
            d.exp_sip, l.alamat from m_dokter as d inner join m_lokasipraktik as l
            on d.id_dokter=l.dokter";

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
                $groupId[$id]['kateg'] = [];
                $listIdValid[] = $id;
            }

            $idValid = implode(',', $listIdValid);

            $queryKategori = "select dd.id_dokter, k.nama_kateg from m_kategori as k
            inner join detail_dokter as dd on k.id_kategori=dd.id_kategori where dd.id_dokter in (" . $idValid . ")";

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
            error_log("DAO_dokter::getAllDokter :" . $e->getMessage());
            return [];
        }
    }

    static function getProfilDokter(DTO_pengguna $data, bool $initiate){//dokter profil
        $conn=Database::getConnection();
        try{
            $sql="select * from m_dokter where id_dokter=?";
            $stmt=$conn->prepare($sql);$stmt->execute([$data->getIdUser()]);
            $profil=$stmt->fetch(PDO::FETCH_ASSOC);
            if($profil==null){return null;}
            
            $dokter = new DTO_dokter($profil['id_dokter'], $profil['nama_dokter'], $profil['foto'], rate:$profil['rate']);
            if($initiate){return $dokter;}
            

            $sql = "select k.id_kategori as idK, k.nama_kategori as namaK from m_kategori join detail_dokter as dd
            on dd.id_kategori=k.id_kategori where dd.id_dokter=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$data->getIdUser()]);
            $kateg = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $dokter = new DTO_dokter($profil['id_dokter'], $profil['nama_dokter'],
                $profil['foto'], $profil['pengalaman'], $profil['rate'], $kateg);
            $dokter->setDoc($profil['sip'], $profil['exp_sip'], $profil['strv'], $profil['exp_strv']);
            $dokter->setTTL($profil['ttl']);
            $dokter->setStatus($profil['status']);
            return $dokter;
        } catch (PDOException $e) {
            error_log('[DAO_Dokter::getProfilDokter]: ' . $e->getMessage());
            return false;
        }
    }

    static function getAlamat(DTO_dokter $dat) {
        $conn = Database::getConnection();
        try {
            $sql = "select * from m_lokasipraktik where dokter=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$dat->getId()]);
            $hasil = $stmt->fetch(PDO::FETCH_ASSOC);
            $dat->setInfoDokter($hasil);
            return true;
        } catch (PDOException $e) {
            error_log('[DAO_dokter::getAlamat]: ' . $e->getMessage());
            return false;
        }
    }

    static function getJadwal(DTO_dokter $dat) {
        $conn = Database::getConnection();
        try {
            $sql = "select * from m_hpraktik where id_dokter=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$dat->getId()]);
            $hasil = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $hasil;
        } catch (PDOException $e) {
            error_log('[DAO_dokter::getJadwal]: ' . $e->getMessage());
            return [false];
        }
    }

    static function getInfoDokter(DTO_dokter $dat) { //single user
        $conn = Database::getConnection();
        try {
            $query = "select d.strv, loc.alamat, loc.lat, loc.long, loc.nama_klinik
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
            error_log("DAO_dokter::getInfoDokter :" . $e->getMessage());
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
                    pengalaman 
                  ) VALUES (?, ?, ?, ?, ?)";
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
            $data->getTTL(),         // 3. ttl
            $data->getFoto(),        // 4. foto
            $data->getPengalaman(),  // 5. pengalaman
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
            error_log("DAO_dokter::insertDokter :".$e->getMessage());
            return false;
        }
    }

    static function setKategDokter(bool $update, $idDokter, array $datKateg)
    {

        $conn = Database::getConnection();
        $sql = "insert into detail_dokter values (?,?)";
        $sqlDel = "delete from detail_dokter where id_dokter =?";
        try {
            $conn->beginTransaction();
            if ($update) {
                $stmt = $conn->prepare($sqlDel);
                $stmt->execute([$idDokter]);
            }
            foreach ($datKateg as $pk) {
                $params = [$idDokter, $pk->getIDK()];
                $stmt = $conn->prepare($sql);
                $stmt->execute($params);
            }
            $conn->commit();
            return true;
        } catch (PDOException $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            error_log("DAO_dokter::setkategDokter {$update} : " . $e->getMessage());
            return false;
        }
    }

    static function setJadwal(bool $update, int $idDokter, array $hari, array $buka, array $tutup)
    {
        $conn = Database::getConnection();
        $query = "insert into m_hpraktik values (?,?,?,?)";
        $queryDel = "delete from m_hpraktik where id_dokter=?";
        $status = null;
        try {
            $conn->beginTransaction();
            if ($update) {
                $stmt = $conn->prepare($queryDel);
                $status = $stmt->execute([$idDokter]);
                if (!$status) {
                    $conn->rollBack();
                    return false;
                }
            }

            foreach ($hari as $key => $day) {
                $stmt = $conn->prepare($query);
                $status = $stmt->execute([$idDokter, $day, $buka[$key], $tutup[$key]]);
                if (!$status) {
                    $conn->rollBack();
                    return false;
                }
            }
            $conn->commit();
            return true;
        } catch (PDOException $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            error_log("DAO_dokter::setJadwal {$update} :" . $e->getMessage());
            return false;
        }
    }

    static function setLokasi(bool $update, DTO_dokter $data)
    {
        $koor = $data->getKoor();
        $lat = $koor[0];
        $long = $koor[1];
        $conn = Database::getConnection();
        $sql = "insert into m_lokasipraktik values (" . $data->getId() . ",?,?,?,?)";
        if ($update) {
            $sql = "update m_lokasipraktik set nama_klinik=?,
            alamat=?, lat=?, long=? where dokter= " . $data->getId();
        }
        try {
            if ($data->getNamaKlinik() === null && $data->getAlamat() === null) {
                $stmt = $conn->prepare("delete from m_lokasipraktik where dokter=?");
                return $stmt->execute([$data->getId()]);
            }
            $stmt = $conn->prepare($sql);
            return $stmt->execute([$data->getNamaKlinik(), $data->getAlamat(), $lat, $long]);
        } catch (PDOException $e) {
            error_log("[DAO_dokter::setLokasi] {$update} : " . $e->getMessage());
            return false;
        }
    }

    //update

    static function updateDocument(DTO_dokter $data){
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
            error_log("DAO_dokter::updateDocument: " . $e->getMessage());
            return false;
        }
    }
    static function updateDokter(int $idDokter, DTO_dokter $data, $status, bool $admin=false){
        $conn = Database::getConnection();
        $sqlDokter = "update m_dokter set nama_dokter =?, ttl=?, pengalaman=? where id_dokter=?";
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
                    $data->getTTL(),
                    $data->getPengalaman(),
                    $idDokter
                ]);
            }
        } catch (PDOException $e) {
            error_log("DAO_dokter::updateDokter {admin = $admin}: " . $e->getMessage());
            return false;
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
            error_log("DAO_dokter::deleteDokter" . $e->getMessage());
            return false;
        }
    }

    /**
     * Simple search/filter helper for doctors.
     * Falls back to in-memory filtering of `getAllDokter()` results to avoid
     * complex SQL changes. Accepts a search string and a category name/id.
     *
     * @param string $searchTerm
     * @param string $kategori
     * @return DTO_dokter[]
     */
    static function searchDokter(string $searchTerm = '', string $kategori = ''): array
    {
        $all = self::getAllDokter();
        if (empty($searchTerm) && empty($kategori)) {
            return $all;
        }

        $searchTerm = trim($searchTerm);
        $kategori = trim($kategori);

        $filtered = [];
        foreach ($all as $dok) {
            // filter by search term (name)
            $matchSearch = true;
            if ($searchTerm !== '') {
                $name = $dok->getNama() ?? '';
                $matchSearch = (stripos($name, $searchTerm) !== false);
            }

            // filter by kategori (category name or id)
            $matchKateg = true;
            if ($kategori !== '') {
                $kategs = $dok->getKategori() ?? [];
                $matchKateg = false;
                foreach ($kategs as $k) {
                    // allow matching by name (case-insensitive) or numeric id
                    if (is_numeric($kategori)) {
                        if ((string)$k === (string)$kategori) {
                            $matchKateg = true;
                            break;
                        }
                    }
                    if (stripos((string)$k, $kategori) !== false) {
                        $matchKateg = true;
                        break;
                    }
                }
            }

            if ($matchSearch && $matchKateg) {
                $filtered[] = $dok;
            }
        }

        return $filtered;
    }
}

?>