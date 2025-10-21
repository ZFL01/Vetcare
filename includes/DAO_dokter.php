<?php
include_once 'database.php';

class DTO_dokter{
    function __construct(
        private ?int $id_dokter = null, 
        private ?string $nama = null,
        private ?string $ttl = null,
        private ?string $strv = null,
        private ?string $exp_strv = null,
        private ?string $sip = null,
        private ?string $exp_sip = null,
        private ?string $foto = null,
        private ?int $pengalaman = null
    ) {}

    function getId(){return $this->id_dokter;}
    function getNama(){return $this->nama;}
    function getTTL(){return $this->ttl;}
    function getSTRV(){return $this->strv;}
    function getExp_STRV(){return $this->exp_strv;}
    function getSIP(){return $this->sip;}
    function getExp_SIP(){return $this->exp_sip;}
    function getFoto(){return $this->foto;}
    function getPengalaman(){return $this->pengalaman;}
}

class DAO_dokter{
    private $conn = Database::getConnection();
    static function getAllDokter(){
        try{
            $query = "select d.id_dokter, d.nama_dokter, d.foto, d.pengalaman, k.nama_kateg
            from m_dokter as d
            inner join detail_dokter as dd on d.id_dokter=dd.dokter
            inner join m_kategori as k on dd.id_kategori=k.id_kategori";
        }
    }
}

?>