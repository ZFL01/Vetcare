<?php
require_once 'DAO_user.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

enum index_email:string{
    case Forgot = 'Forgot Password';
    case ChangePass = 'Change Password';
    case Verify = 'Verify Account';
}

function censorEmail(string $email){
    $atpos = strpos($email, '@');
    if($atpos <= 2)return $email;
    
    $uName = substr($email, 0, $atpos);
    $domain = substr($email, $atpos);
    $uNameLength = strlen($uName);
    
    $vis = min(3, $uNameLength-1);
    if ($vis < 1) {
        $vis = 1; 
    }
    $start = substr($uName, 0, $vis);
    $end = substr($uName, -2);
    $mask = $uNameLength - $vis - 2;
    if($mask < 1){
        $mask = 1;
    }
    $masked = str_repeat('*', $mask);

    return $start . $masked . $end . $domain;
}

class userService{ //account service
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

    static function deleteUser(int $dat){
        error_log("hapus : ".$dat);
        return DAO_pengguna::deleteUser($dat);
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
        }else{return [false, "Email atau password salah!"];}
    }

    static function sendEmail(DTO_pengguna $data, index_email $index){
        $email=$data->getEmail();
        $cek = DAO_pengguna::getUserEmail($email);
        if(!$cek[0]){return $cek;}

        $token = self::generateToken();
        $expTime = date('Y-m-d H:i:s', time() + (5 * 60 + 15));
        $update = DAO_pengguna::updateResetToken($email, $token,$expTime);

        if(!$update[0]){return $update;}
        return self::sendEmailverify($email, $token, $index->value);
    }

    private static function sendEmailverify(string $recipientEmail, string $token, string $judul){
        $mail = new PHPMailer(true);
        try{
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'svenhikari@gmail.com';
            $mail->Password = 'adgr jymt rqmf qkdv';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('svenhikari@gmail.com', $judul);
            $mail->addAddress($recipientEmail);
            $mail->Subject = $judul;
            $mail->isHTML(true);
            $mail->Body = "<h3 style='text-align: center'>Kode OTP mu adalah : <h3> <br><br>";
            $mail->Body .=" <br><div style='text-align: center'><h1><b>".$token . "</b></h1></div><br><br>";
            $mail->Body .=" <br>Kode ini hanya berlaku selama 15 menit. <br>Jika Anda tidak merasa melakukan ini, silahkan hubungi Admin";

            if($mail->send()){
                return [true];
            }else{ return [false, "Gagal"];}
        }catch(Exception $e){
            error_log("userService::verifyEmail".$e->getMessage());
            return [false, 'Gagal mengirim email token! '. DAO_pengguna::$pesan];
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
        if(!$success[0]){return $success;}

        $data->setNewPass(null);
        return [true];
    }
}
//pk.f36f3d13ab6674ab62602323da859b26
class apiControl{
    static function getCityProvince($lat, $lng){
        $key = LOCATIONIQ_API;
        $url = "https://us1.locationiq.com/v1/reverse?key={$key}&lat={$lat}&lon={$lng}&format=json&zoom=10";
        $options = [
            'http' => ['timeout' => 5]
        ];
        $context = stream_context_create($options);
        $json = @file_get_contents($url, false, $context);

        if ($json === FALSE) {
        // Gagal mengambil data (timeout, jaringan, atau API key salah)
            error_log("LocationIQ Request Failed.");
            return [false, 'Tidak Diketahui'];
        }
        $dat = json_decode($json, true);
        if(isset($dat['address'])){
            $address = $dat['address'];
            $city = $address['city'] ?? $address['town'] ?? $address['village'] ?? $address['county'] ?? 'Tidak Diketahui';
            $province = $address['state'] ?? 'Tidak Diketahui';
        
            return [$city, $province];
        }
        return [false, 'tidak diketahui'];
    }
}
?>