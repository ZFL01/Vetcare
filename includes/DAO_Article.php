<?php
include_once 'database.php';
class DTO_artikel{
    private string $isi;
    private string $referensi;
    private string $author;
    private string $published;
    private int $views;
    private string $updated;
    function __construct( //get All articles
        private int $idArtikel,
        private string $judul,
        private string $preview,
        private string $tag
    ){}
    function forThisArticle($isi, $ref, $authName,
    $published, $updated){
        $this->isi=$isi; $this->referensi=$ref;
        $this->author=$authName; $this->published=$published;
        $this->updated=$updated;
    }
    function createArticle($judul, $preview, $isi, $referensi){
        $this->judul=$judul; $this->preview=$preview;
        $this->isi=$isi; $this->referensi=$referensi;
    }
    function set_views(int $views){$this->views=$views;}

    function get_idArticle(){return $this->idArtikel;}
    function get_judul(){return $this->judul;}
    function get_preview(){return $this->preview;}
    function get_isi(){return $this->isi;}
    function get_reference(){return $this->referensi;}
    function get_author(){return $this->author;}
    function get_created(){return $this->published;}
    function get_views(){return $this->views;}
    function get_update(){return $this->updated;}
    function get_tag(){return $this->tag;}
}

class DAO_Artikel{
    static function getAllArticles(?int $idDokter = null,?string $tag=null, bool $popular=false){
        $conn = Database::getConnection();
        $sql="select a.id_artikel, a.judul, a.preview, a.views, t.tag from m_artikel as a
        join m_tag as t on a.tag=t.idTag";
        $param = [];
        if($idDokter !==null){
            $sql .=' where a.author_id=?';
            $param[] = $idDokter; 
        }elseif($tag !==null){
            $sql .= ' where t.tag=?';
            $param[] = $tag;
        }
        if($popular){
            $sql .=' order by a.views desc';
        }else{
            $sql .= ' order by a.updated desc';
        }
        try{
            $stmt = $conn->prepare($sql);
            $stmt->execute($param);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $DTO_artikel = [];
            foreach($data as $dat){
                $obj = new DTO_artikel(
                    $dat['id_artikel'], $dat['judul'],
                    $dat['preview'], $dat['tag']
                );
                if($idDokter !==null){
                    $obj->set_views($dat['views']);
                }
                $DTO_artikel[] = $obj;
            }
            return $DTO_artikel;
        }catch(PDOException $e){
            error_log("DAO_Artikel::getAllArticles : ". $e->getMessage());
            return [];
        }
    }

    static function getArtikel(DTO_artikel $dat){
        $conn = Database::getConnection();
        $sql = "select isi, referensi, author, published, updated 
        from m_artikel where id_artikel=?";
        try{
            $stmt = $conn->prepare($sql);
            $stmt->execute([$dat->get_idArticle()]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            $dat->forThisArticle($data['isi'], $data['referensi'], $data['author'],
            $data['published'], $data['updated']);
            return true;
        }catch(PDOException $e){
            error_log("DAO_Artikel::getArtikel : ".$e->getMessage());
            return false;
        }
    }

    static function makeArticle(DTO_dokter $Dokter, DTO_artikel $Artikel, int $idTag){
        $conn = Database::getConnection();
        $sql = "insert into m_artikel (judul, preview, isi, referensi, author, author_id, tag)
        values (?,?,?,?,?,?,?)";
        $params = [$Artikel->get_judul(), $Artikel->get_preview(), $Artikel->get_isi(),
        $Artikel->get_reference(), $Dokter->getNama(), $Dokter->getId(), $idTag];
        try{
            $stmt = $conn->prepare($sql);
            return $stmt->execute($params);
        }catch(PDOException $e){
            error_log("[DAO_Article::makeArticle]".$e->getMessage());
            return false;
        }
    }
}

?>