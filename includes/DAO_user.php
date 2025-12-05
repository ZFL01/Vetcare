<?php
class DTO_pengguna{
    function __construct(
        private ?int $idUser =null,
        private ?string $email=null,
        private ?string $pass = null,
        private ?string $role=null,
        private ?string $resetToken=null,
        ){$this->pass=$pass;}
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

include_once 'database.php';
class DAO_pengguna{
    static $pesan="Hubungi admin untuk masalah ini.";
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
            return [$hasil, (int) $conn->lastInsertId()];
        }catch(PDOException $e){
            custom_log("DAO_user::insertUser :".$e->getMessage(), LOG_TYPE::ERROR);
            return [false, "Gagal menyimpan pengguna! ".self::$pesan];
        }
    }

    static function getUserEmail($email): array{
        $conn = Database::getConnection();
        $sql = "select id_pengguna, email, pass, role from m_pengguna where email = ?";
        
        try{
            $stmt = $conn->prepare($sql);
            $stmt->execute([$email]);
            $hasil = $stmt->fetch(PDO::FETCH_ASSOC);

            if($hasil){return [true, $hasil];}else{
                return [false, "Tidak ada data pengguna yang cocok!"];
            }
        }catch(PDOException $e){
            custom_log("DAO_user::getUserEmail : ".$e->getMessage(), LOG_TYPE::ERROR);
            return [false, "Gagal mengambil email! ".self::$pesan];
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
            custom_log("DAO_user::updateResetToken : ".$e->getMessage(), LOG_TYPE::ERROR);
            return [false, "Gagal menyimpan token! ".self::$pesan];
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
            custom_log("DAO_user::verifyToken" . $e->getMessage(), LOG_TYPE::ERROR);
            return [false, 'Gagal memverifikasi token! '.self::$pesan];
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
            custom_log("DAO_user::resetPass : ".$e->getMessage(), LOG_TYPE::ERROR);
            return [false, 'Gagal menyimpan password! '.self::$pesan];
        }
    }

    static function deleteUser(int $idUser){
        $conn = Database::getConnection();
        $sql = "delete from m_pengguna where id_pengguna = ?";
        try {
            $stmt = $conn->prepare($sql);
            $hasil = $stmt->execute([$idUser]);
            if ($hasil && $stmt->rowCount() > 0) {
                return [true];
            }
            return [false, "Gagal menghapus pengguna!"];
        } catch (PDOException $e) {
            custom_log("DAO_user::deleteUser : " . $e->getMessage(), LOG_TYPE::ERROR);
            return [false, 'Gagal menghapus akun pengguna! ' . self::$pesan];
        }
    } 
}
?>