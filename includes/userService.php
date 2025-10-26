<?php
require_once 'DAO_user.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class userService{
    private static function hashPass(string $pass){
        return password_hash($pass, PASSWORD_ARGON2ID);
    }
    private static function generateToken(){
        return str_pad(rand(101010, 999999), 6);
    }

    static function register(DTO_pengguna $dat){
        if (strlen($dat->getPass())<8){
            return [false, "Kata sandi minimal terdiri dari 8 karakter!"];
        }
        $hashPass = self::hashPass($dat->getPass());
        return DAO_pengguna::insertUser($dat, $hashPass);
    }

    static function login(DTO_pengguna $dat){
        $hasil = DAO_pengguna::getUserEmail($dat->getEmail());

        if(!$hasil[0]){return $hasil;}

        $dataHasil = $hasil[1];
        $hashedpass = $dataHasil['pass'];
        $inputpass = $dat->getPass();

        if(password_verify($inputpass, $hashedpass)){
            $dat->setReturn($dataHasil);
            return [true];
        }else{return [false, "Password salah!"];}
    }

    static function forgetPass(DTO_pengguna $data){
        $email=$data->getEmail();
        $cek = DAO_pengguna::getUserEmail($email);
        if(!$cek[0]){return $cek;}

        $token = self::generateToken();
        $expTime = date('Y-m-d H:i:s', time() + (5 * 60 + 15));
        $update = DAO_pengguna::updateResetToken($email, $token,$expTime);

        if(!$update[0]){return $update;}
        return self::sendEmailverify($email);
    }

    private static function sendEmailverify(string $recipientEmail){
        $mail = new PHPMailer(true);
        try{
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'svenhikari@gmail.com';
            $mail->Password = 'aghu ecip kllk jmro';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('svenhikari@gmail.com', 'reset');
            $mail->addAddress($recipientEmail);
            $mail->Subject = 'OTP';
            $mail->Body = "Kode OTP mu 777";

            if($mail->send()){
                return [true];
            }else{ return [false, "Gagal"];}
        }catch(Exception $e){
            error_log("userService::verifyEmail".$e->getMessage());
            return [false, 'err'];
        }
    }

    static function verifyToken(DTO_pengguna $data){
        $success = DAO_pengguna::verifToken($data);
        if(!$success[0])return $success;
        
        $data->setReturn($success[1]);
        return [true];
    }

    static function changePass(DTO_pengguna $data){
        if (strlen($data->getPass())<8){
            return [false, "Kata sandi minimal terdiri dari 8 karakter!"];
        }
        $hashPass = self::hashPass($data->getPass());
        $success = DAO_pengguna::resetPass($data->getEmail(), $hashPass);
        if(!$success){return $success;}

        $data->setNewPass(null);
    }
}
?>