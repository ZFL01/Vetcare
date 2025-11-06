<?php
include_once 'database.php';
class DTO_pengguna{
    protected function __construct(
        private ?int $idUser =null,
        private ?string $email=null,
        private ?string $pass = null,
        private ?string $role=null,
        private ?string $resetToken=null,
        ){}

    static function forRegist($email, $pass, $role){
        return new self(null, $email, $pass, $role);
    }
    static function forLogin($email, $pass){
        return new self(null, $email, $pass);
    }
    static function forReset($email){
        return new self(null, $email, null,
    null);
    }
    function setReturn(array $data){
        $this->idUser=$data['id_pengguna']; $this->pass=null;
        $this->role=$data['role'];
    }

    function setNewPass(?string $newPass=null){$this->pass=$newPass;}
    function setToken($token){$this->resetToken=$token;}
    function getIdUser(){return $this->idUser;}
    function getEmail(){return $this->email;}
    function getPass(){return $this->pass;}
    function getToken(){return $this->resetToken;}
    function getRole(){return $this->role;}
}

class DAO_pengguna{
    static function insertUser(DTO_pengguna $data, string $hashpass){
        $conn = Database::getConnection();

        $ada= self::getUserEmail($data->getEmail());
        if($ada[0]){
            return [false, "Email sudah terdaftar"];}

        $sql = "insert into m_pengguna (email, pass, role) values (?,?,?)";
        $params = [$data->getEmail(), $hashpass, $data->getRole()];

        try{
            $stmt = $conn->prepare($sql);
            $hasil = $stmt->execute($params);
            return [$hasil];
        }catch(PDOException $e){
            error_log("DAO_user::insertUser :".$e->getMessage());
            return [false, "err"];
        }
    }

    static function getUserEmail($email){
        $conn = Database::getConnection();
        $sql = "select id_pengguna, email, pass, role from m_pengguna where email = ?";
        
        try{
            $stmt = $conn->prepare($sql);
            $stmt->execute([$email]);
            $hasil = $stmt->fetch(PDO::FETCH_ASSOC);

            if($hasil){return [true, $hasil];}else{
                return [false, "Email not found!"];
            }
        }catch(PDOException $e){
            error_log("DAO_user::getUserEmail : ".$e->getMessage());
            return [false, "err"];
        }
    }

    static function updateResetToken(string $email, string $token, string $expTime){
        $conn = Database::getConnection();
        $sql = "update m_pengguna set reset_token = ?, exp_token = ? where email=?";

        try{
            $stmt = $conn->prepare($sql);
            $hasil = $stmt->execute([$token, $expTime, $email]);

            if($hasil && $stmt->rowCount()>0) {
                return [true];
            } return[false, "Gagal menyimpan token, coba kirim ulang!"];
        }catch(PDOException $e){
            error_log("DAO_user::updateResetToken : ".$e->getMessage());
            return [false, "err"];
        }
    }

    static function verifToken(DTO_pengguna $dat){
        $conn = Database::getConnection();
        $sql="select id_pengguna, role, reset_token from m_pengguna
        where email =? and reset_token =? and exp_token >= now()";

        try{
            $stmt = $conn->prepare($sql);
            $stmt->execute([$dat->getEmail(), $dat->getToken()]);
            $hasil = $stmt->fetch(PDO::FETCH_ASSOC);
            if($hasil){return [true, $hasil];}
            else{return [false, "Token salah"];}
        }catch(PDOException $e){
            error_log("DAO_user::verifyToken" . $e->getMessage());
            return [false, 'err'];
        }
    }

    static function resetPass(string $email, string $hashedpass){
        $conn = Database::getConnection();
        $sql = "update m_pengguna set pass=?, reset_token=null where email=?";
        
        try{
            $stmt = $conn->prepare($sql);
            $hasil =$stmt->execute([$hashedpass, $email]);
            if($hasil&& $stmt->rowCount()>0){
                return [true];
            }return [false, "Gagal menyimpan password!"];
        }catch(PDOException $e){
            error_log("DAO_user::resetPass : ".$e->getMessage());
            return [false, 'err'];
        }
    }

}

?>