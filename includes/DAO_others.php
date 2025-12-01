<?php
include_once 'database.php';
require_once 'userService.php';

class Location{

    function __construct(private ?int $idLocation=null, private ?string $coor=null,
    private ?array $location=null, private ?int $id_User=null, private ?int $time=null){}
    function getIdLocation(){return $this->idLocation;}
    function getCoor(){return $this->coor;}
    function getLocation(){return $this->location;}
    function getId_User(){return $this->id_User;}
    function getTime(){return $this->time;}
}

class DTO_chat implements JsonSerializable{
    function __construct(
        private ?string $idChat=null,
        private ?int $idUser=null,
        private ?int $idDokter=null,
        private ?string $email=null,
        private ?string $namaDokter=null,
        private ?string $waktuSelesai=null,
        private ?string $status=null,
        private ?string $waktuMulai=null,
        ){}
    function getIdChat(){return $this->idChat;}
    function getIdUser(){return $this->idUser;}
    function getIdDokter(){return $this->idDokter;}
    function getEmail(){return censorEmail($this->email);}
    function getNamaDokter(){return $this->namaDokter;}
    function getStatus(){return $this->status;}
    function getWaktuSelesai(){return $this->waktuSelesai;}
    function getWaktuMulai(){return $this->waktuMulai;}
    function jsonSerialize():mixed{
        return ['idChat'=>$this->idChat, 'idUser'=>$this->idUser,
        'idDokter'=>$this->idDokter, 'email'=>censorEmail($this->email), 
        'namaDokter'=>$this->namaDokter, 'waktuSelesai'=>$this->waktuSelesai,
        'status'=>$this->status, 'waktuMulai'=>$this->waktuMulai];
    }
}
class DTO_Tag implements JsonSerializable{
    private ?int $idTag=null;
    private ?string $tag=null;

    function __construct(int $idTag, string $tag){
        $this->idTag=$idTag; $this->tag=$tag;
    }
    function getIdTag(){return $this->idTag;}
    function getTag(){return $this->tag;}
    function jsonSerialize():mixed{
        return ['idTag'=>$this->idTag, 'tag'=>$this->tag];
    }
}

class DTO_tanyajawab{
    private ?int $idTanya=null;
    private ?int $idUser=null;
    private ?int $idDokter=null;
    private string|int|null $Tag=null;

    private string $user, $dokter, $judul, $pertanyaan,
    $jawaban, $status, $dibuat, $tglJawab;   

    function forPreview($idtanya, $user, $judul, $destanya, $dibuat, $status, $tag=null){
        $this->idTanya=$idtanya; $this->judul=$judul; $this->pertanyaan=$destanya;
        $this->dibuat=$dibuat; $this->status=$status; $this->tag=$tag;
        $this->user=$user;
    }
    function forShowAnswer($dokter, $idDokter, $jwaban, $publish, $destanya){
        $this->dokter=$dokter; $this->idDokter=$idDokter; $this->jawaban=$jwaban; $this->tglJawab=$publish;
        $this->pertanyaan=$destanya;
    }
    function forCreateAsk(DTO_pengguna $user, string $judul, string $deskripsi, int $tag){
        $this->idUser=$user->getIdUser(); $this->user=censorEmail($user->getEmail());
        $this->judul=$judul; $this->pertanyaan=$deskripsi; $this->Tag=$tag;
    }
    function forAnswering(DTO_dokter $dokter, DTO_tanyajawab $tanya, $isi){
        $this->idDokter=$dokter->getId(); $this->dokter=$dokter->getNama();
        $this->idTanya=$tanya->getIdTanya(); $this->jawaban=$isi;
    }

    function getIdTanya(){return $this->idTanya;}
    function getIdUser(){return $this->idUser;}
    function getIdDokter(){return $this->idDokter;}
    function getUser(){return $this->user;}
    function getDokter(){return $this->dokter;}
    function getJudul(){return $this->judul;}
    function getDeskripsi(){return $this->pertanyaan;}
    function getJawaban(){return $this->jawaban;}
    function getStatus(){return $this->status;}
    function getCreated(){return $this->dibuat;}
    function getTag(): mixed{return $this->Tag;}
    function getTglJawab(){return $this->tglJawab;}
}

class DAO_location{
    static function getAllLocation(){
        $conn = Database::getConnection();
        $sql = 'select * from log_location';
        try{
            $stmt=$conn->prepare($sql);
            $stmt->execute();
            $hasil=$stmt->fetchAll(PDO::FETCH_ASSOC);
            if(empty($hasil)){return [];}
            $dto = [];
            foreach($hasil as $dat){
                $obj=new Location($dat['id'], $dat['koor'], [$dat['kabupaten'], $dat['provinsi']], $dat['id_user'], $dat['timestamp']);
                $dto[]=$obj;
            }
            return $dto;
        }catch(PDOException $e){
            error_log("[DAO_location::getAllLocation]: ".$e->getMessage());
            return [];
        }
    }
    static function insertLocation(Location $loc){
        $conn=Database::getConnection();
        $sql="insert into log_location (koor, kabupaten, provinsi, id_user) values (?, ?, ?, ?)";
        $params = [$loc->getCoor(), $loc->getLocation()[0], $loc->getLocation()[1], $loc->getId_User()];
        $ada = !empty($loc->getIdLocation());

        if($ada){
            $sql = 'update log_location set id_user=? where id=?';
            $params = [$loc->getId_User(), $loc->getIdLocation()];
        }
        try{
            $stmt=$conn->prepare($sql);
            $stmt->execute($params);
            return $ada ? true : $conn->lastInsertId();
        }catch(PDOException $e){
            error_log("[DAO_location::insertLocation]: ".$e->getMessage());
            return false;
        }
    }
}

class DAO_Tag{
    static function getAllTags(){
        $conn=Database::getConnection();
        $sql="select idTag, tag from m_tag";
        try{
            $stmt=$conn->prepare($sql);
            $stmt->execute();
            $hasil=$stmt->fetchAll(PDO::FETCH_ASSOC);
            if(empty($hasil)){return [];}
            $dto = [];
            foreach($hasil as $dat){
                $obj=new DTO_Tag($dat['idTag'], $dat['tag']);
                $dto[]=$obj;
            }
            return $dto;
        }catch(PDOException $e){
            error_log("[DAO_others::getAllTags]: ".$e->getMessage());
            return [];
        }
    }
    static function insertTag(string $tag){
        $conn=Database::getConnection();
        $sql="insert into m_tag (tag) values (?)";
        try{
            $stmt=$conn->prepare($sql);
            $stmt->execute([$tag]);
            return $conn->lastInsertId();
        }catch(PDOException $e){
            error_log("[DAO_others::insertTag]: ".$e->getMessage());
            return false;
        }
    }
}

class DAO_Tanya{
    static function previewAll(?int $idTag=null, ?DTO_dokter $dokter = null, ?DTO_pengguna $user=null){
        $conn=Database::getConnection();
        $sql="select t.id_tanya, t.penanya, t.judul, substring_index(t.pertanyaan, ' ', 20)
        as deskripsi, t.dibuat, t.status, g.tag from tr_tanya as t
        join m_tag as g on t.idTag=g.idTag";
        $param=[];
        if($user !==null){
            $sql .=' where id_penanya=?';
            $param = [$user->getIdUser()];
        }elseif($dokter !==null){
            $sql .= ' join jwb_dokter as d on t.id_tanya=d.id_tanya where d.id_dokter=?';
            $param=[$dokter->getId()];
        }elseif($idTag !==null){
            $sql .= ' where t.idTag=?';
            $param=[$idTag];
        }

        try{
            $stmt=$conn->prepare($sql);
            $stmt->execute($param);
            $hasil=$stmt->fetchAll(PDO::FETCH_ASSOC);
            if(empty($hasil)){return [];}
            $dto = [];
            foreach($hasil as $dat){
                $obj=new DTO_tanyajawab();
                $obj->forPreview($dat['id_tanya'], $dat['penanya'], $dat['judul'],
                $dat['deskripsi'], $dat['dibuat'], $dat['status'], $dat['tag']);
                $dto[]=$obj;
            }
            return $dto;
        }catch(PDOException $e){
            error_log("[DAO_others::previewAll]: ".$e->getMessage());
            return [];
        }
    }

    static function insertAsk(DTO_tanyajawab $dat){
        $conn=Database::getConnection();
        $sql="insert into tr_tanya (id_penanya, penanya, judul, pertanyaan, idTag) values (?,?,?,?,?)";
        try{
            $stmt=$conn->prepare($sql);
            return $stmt->execute([$dat->getIdUser(), $dat->getUser(), $dat->getJudul(),$dat->getDeskripsi(), $dat->getTag()]);
        }catch(PDOException $e){
            error_log('[DAO_others::insertAsk]: '.$e->getMessage());
            return false;
        }
    }
    static function delete(DTO_tanyajawab $dat){
        $conn=Database::getConnection();

        $sql=['jwb_dokter'=>'delete from jwb_dokter where id_tanya=?',
            'tr_tanya'=>'delete from tr_tanya where id_tanya = ?'];
        try{
            $conn->beginTransaction();
            foreach($sql as $x=>$y){
                $conn->prepare($y)->execute([$dat->getIdTanya()]); 
            }
            return $conn->commit();
        }catch(PDOException $e){
            if ($conn->inTransaction()) { $conn->rollBack();}
            error_log('[DAO_others::delAsk]: '.$e->getMessage());
            return false;
        }
    }

    static function showAnswer(int $dat){
        $conn=Database::getConnection();
        $sql="select t.penanya, t.judul, t.dibuat, t.status, j.id_dokter, j.nama_dokter, j.isi, j.publish, t.pertanyaan
        from jwb_dokter as j join tr_tanya as t on j.id_tanya=t.id_tanya
        where j.id_tanya=?";
        try{
            $stmt=$conn->prepare($sql);
            $stmt->execute([$dat]);
            $hasil=$stmt->fetch(PDO::FETCH_ASSOC);
            if(empty($hasil)){return false;}
            
            $obj = new DTO_tanyajawab();
            $obj->forPreview($dat, $hasil['penanya'], $hasil['judul'], 
            $hasil['pertanyaan'], $hasil['dibuat'], $hasil['status']);

            $obj->forShowAnswer($hasil['nama_dokter'], $hasil['id_dokter'], $hasil['isi'],
            $hasil['publish'], $hasil['pertanyaan']);
            return $obj;
        }catch(PDOException $e){
            error_log("[DAO_others::showAnswer]: ".$e->getMessage());
            return false;
        }
    }

    static function insertAnswer(DTO_tanyajawab $dat){
        $conn=Database::getConnection();
        $sql="insert into jwb_dokter (id_tanya, id_dokter, nama_dokter, isi) values (?,?,?,?)";
        try{
            $stmt=$conn->prepare($sql);
            return $stmt->execute([$dat->getIdTanya(), $dat->getIdDokter(), $dat->getDokter(), $dat->getJawaban()]);
        }catch(PDOException $e){
            error_log("[DAO_others::insertAnswer]: ".$e->getMessage());
            return false;
        }
    }

    static function updateAnswer(DTO_tanyajawab $dat){
        $conn=Database::getConnection();
        $sql="update jwb_dokter set isi=? where id_tanya=?";
        try{
            $stmt=$conn->prepare($sql);
            return $stmt->execute([$dat->getJawaban(),$dat->getIdTanya()]);
        }catch(PDOException $e){
            error_log("[DAO_others::updateAnswer]: ".$e->getMessage());
            return false;
        }
    }
}

class DAO_chat{
    private const sesi_durasi = '+12 hours';
    static function getAllChats(?int $idUser=null, ?int $idDokter=null){
        $conn=Database::getConnection();
        $sql = "";
        $param = [];
        if($idUser){
            $sql="SELECT T1.id_chat, T1.id_dokter, D.nama AS nama_dokter, L.end AS waktu_selesai, T1.paid_at AS waktu_mulai_terbaru
            FROM tr_transaksi T1
            JOIN (
                SELECT dokter_id, MAX(paid_at) AS waktu_terbaru 
                FROM tr_transaksi 
                WHERE user_id = ? GROUP BY dokter_id
            ) T2 ON T1.dokter_id = T2.dokter_id AND T1.paid_at = T2.waktu_terbaru
            LEFT JOIN log_rating L ON T1.id_chat = L.id_chat
            JOIN m_dokter D ON T1.id_dokter = D.id_dokter
            WHERE T1.user_id = ?
            ORDER BY T1.paid_at DESC;";
            $param = [$idUser, $idUser];
        }elseif($idDokter){
            $sql="SELECT 
                T1.id_chat, 
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
            LEFT JOIN log_rating L ON T1.id_chat = L.id_chat
            JOIN m_pengguna U ON T1.user_id = U.id_pengguna
            WHERE T1.dokter_id = ?
            ORDER BY T1.paid_at DESC;";
            $param = [$idDokter, $idDokter];
        }else{
            return [];
        }
        try{
            $stmt=$conn->prepare($sql);
            $stmt->execute($param);
            $hasil=$stmt->fetchAll(PDO::FETCH_ASSOC);
            if(empty($hasil)){return [];}
            $dto = [];
            foreach($hasil as $dat){
                if($idUser){
                    $obj=new DTO_chat(
                        $dat['idChat'],
                        namaDokter: $dat['nama_dokter'],
                        waktuSelesai: $dat['waktu_selesai'],
                        waktuMulai: $dat['waktu_mulai_terbaru'],
                    );
                }else{
                    $obj=new DTO_chat(
                        $dat['idChat'],
                        email: $dat['email'],
                        waktuSelesai: $dat['waktu_selesai'],
                        waktuMulai: $dat['waktu_mulai_terbaru'],
                    );
                }
                $dto[]=$obj;
            }
            return $dto;
        }catch(PDOException $e){
            error_log("[DAO_others::getAllChats]: ".$e->getMessage());
            return [];
        }
    }

    static function findChatRoom($idDokter, $idUser): DTO_chat|null{
        $conn=Database::getConnection();
        $sql = 'select t.id_tr, t.user_id, t.dokter_id, l.end from tr_transaksi t inner join
        log_rating l on l.idChat=t.id_tr where t.user_id=? and t.dokter_id=? and
        (t.status !="expired" OR t.status !="failed")
        order by t.id_tr desc';
        try{
            $stmt=$conn->prepare($sql);
            $stmt->execute([$idUser, $idDokter]);
            $hasil=$stmt->fetch(PDO::FETCH_ASSOC);
            if(empty($hasil)){
                return null;}else{
                $obj=new DTO_chat(
                    $hasil['id_tr'],
                    $hasil['user_id'],
                    $hasil['dokter_id'],
                    waktuSelesai:$hasil['end']
                );
            }
            return $obj;
        }catch(PDOException $e){
            error_log("[DAO_others::findChatRoom]: ".$e->getMessage());
            return null;
        }
    }

    static function thisChatRoom($idChat, $idUser, $idDokter=null){
        $conn=Database::getConnection();
        $sql='select d.nama_dokter, u.email, t.created, t.status, t.user_id, t.dokter_id from tr_transaksi as t
        inner join m_dokter as d on t.dokter_id=d.id_dokter
        inner join m_pengguna as u on t.user_id=u.id_pengguna where t.id_tr=?';
        try{
            $stmt=$conn->prepare($sql);
            $stmt->execute([$idChat]);
            $hasil=$stmt->fetch(PDO::FETCH_ASSOC);
            if(empty($hasil)){return null;}else{

                if($hasil['user_id'] != $idUser){
                    return null;
                }
                if($idDokter !==null && $hasil['dokter_id'] != $idDokter){
                    return null;
                }

                $created = $hasil['created'];
                $selesai = strtotime($created . self::sesi_durasi);
                $end = date('Y-m-d H:i:s', $selesai);

                $obj=new DTO_chat(
                    $idChat,
                    $hasil['user_id'], $hasil['dokter_id'],
                    email: $hasil['email'],
                    namaDokter: $hasil['nama_dokter'],
                    status: $hasil['status'],
                    waktuSelesai: $end, waktuMulai:$created
                );
            }
            return $obj;
        }catch(PDOException $e){
            error_log("[DAO_others::thisChatRoom]: ".$e->getMessage());
            return null;
        }
    }

    static function getRating(int $idDokter){
        $conn=Database::getConnection();
        $sql="select count(*) as total, sum(`liked?`) as suka from log_rating
        where id_dokter=?";
        try{
            $stmt=$conn->prepare($sql);
            $stmt->execute([$idDokter]);
            $hasil=$stmt->fetchAll(PDO::FETCH_ASSOC);
            return $hasil;
        }catch(PDOException $e){
            error_log("[DAO_others::getRating]: ".$e->getMessage());
            return [];
        }
    }
    static function registChatRoom($idChat, $user, $dokter, $created){
        $start = $created;
        $end = date('Y-m-d H:i:s', strtotime($created . self::sesi_durasi));

        $conn=Database::getConnection();
        $conn->beginTransaction();
        $sql="insert into tr_transaksi (id_tr, user_id, dokter_id, created) values (?,?,?,?)";
        $sql2="insert into log_rating (idChat, end) values (?,?)";
        try{
            $stmt=$conn->prepare($sql);
            $hasil= $stmt->execute([$idChat, $user, $dokter, $start]);
            if($hasil){
                $stmt2=$conn->prepare($sql2);
                $hasil2=$stmt2->execute([$idChat, $end]);
            }
            if($hasil && $hasil2){
                $conn->commit();
                return true;
            }
        }catch(PDOException $e){
            error_log("[DAO_others::registChatRoom]: ".$e->getMessage());
            return false;
        }
    }

    static function updateLogMessage($idChat, bool $liked=true){
        $conn=Database::getConnection();
        $sql="update log_rating set `end`=now(), `liked?`=? where idChat=?";
        try{
            $stmt=$conn->prepare($sql);
            return $stmt->execute([$liked, $idChat]);
        }catch(PDOException $e){
            error_log("[DAO_others::updateLogMessage]: ".$e->getMessage());
            return false;
        }
    }
}

?>