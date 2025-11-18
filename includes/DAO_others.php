<?php
include_once 'database.php';
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

    function forPreview($idtanya, $user, $judul, $destanya, $dibuat, $status, $tag){
        $this->idTanya=$idtanya; $this->user=$user;
        $this->judul=$judul; $this->pertanyaan=$destanya;
        $this->dibuat=$dibuat; $this->status=$status; $this->tag=$tag;
    }
    function forShowAnswer($dokter, $jwaban, $publish, $destanya){
        $this->dokter=$dokter; $this->jawaban=$jwaban; $this->tglJawab=$publish;
        $this->pertanyaan=$destanya;
    }
    function forCreateAsk(DTO_pengguna $user, string $judul, string $deskripsi, int $tag){
        $this->idUser=$user->getIdUser(); $this->user=$user->getEmail();
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

    static function showAnswer(DTO_tanyajawab $dat){
        $conn=Database::getConnection();
        $sql="select j.nama_dokter, j.isi, j.publish, t.pertanyaan
        from jwb_dokter as j join tr_tanya as t on j.id_tanya=t.id_tanya
        where j.id_tanya=?";
        try{
            $stmt=$conn->prepare($sql);
            $stmt->execute([$dat->getIdTanya()]);
            $hasil=$stmt->fetch(PDO::FETCH_ASSOC);
            if(empty($hasil)){return false;}
            $dat->forShowAnswer($hasil['nama_dokter'], $hasil['isi'],
            $hasil['publish'], $hasil['pertanyaan']);
            return true;
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
            error_log("[DAO_chat::getLogChat]: ".$e->getMessage());
            return [];
        }
    }
    static function insertLogMessage($idChat, DTO_pengguna $user, DTO_dokter $dokter, bool $liked=true){
        $conn=Database::getConnection();
        $sql="insert into log_rating (idChat, id_pengguna, id_dokter, `liked?`) values (?,?,?,?)";
        try{
            $stmt=$conn->prepare($sql);
            return $stmt->execute([$idChat, $user->getIdUser(), $dokter->getId(), $liked]);
        }catch(PDOException $e){
            error_log("[DAO_chat::updateMessage]: ".$e->getMessage());
            return false;
        }
    }
}

?>