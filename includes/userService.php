<?php
require_once 'DAO_user.php';
require_once 'database.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

enum index_email
{
    case FORGOT;
    case CHANGE_PASS;
    case VERIFY;
    case ACC_DOCTOR_REGIST;
    case REJECT_DOCTOR_REGIST;
    case COMPLAINT;
    function getData($reason = '')
    {
        return match ($this) {
            self::FORGOT => [
                'subject' => 'Forgot Password',
                'body' =>
                    "<h3 style='text-align: center'>Kode OTP mu adalah : <h3> <br><br>" .
                    "<br><div style='text-align: center'><h1><b>{{token}} </b></h1></div><br><br>" .
                    "<br>Kode ini hanya berlaku selama 15 menit. <br>Jika Anda tidak merasa melakukan ini, silahkan hubungi Admin"

            ],
            self::CHANGE_PASS => [
                'subject' => 'Change Password',
                'body' =>
                    "<h3 style='text-align: center'>Kami mendeteksi perubahan password akun Anda<h3> <br><br>" .
                    "<br><br>Jika Anda tidak merasa melakukan ini, silahkan hubungi Admin"

            ],
            self::VERIFY => [
                'subject' => 'Verify Account',
                'body' =>
                    "<h3 style='text-align: center'>Kami mendeteksi pendaftaran akun Anda<h3> <br><br>" .
                    "<br><br>Jika Anda tidak merasa melakukan ini, silahkan hubungi Admin"

            ],
            self::ACC_DOCTOR_REGIST => [
                'subject' => 'Accept Doctor Registration',
                'body' =>
                    "<h3 style='text-align: center'>Selamat bergabung dengan kami, sebagai mitra dokter<h3> <br><br>" .
                    "<br><br>Anda dapat melakukan login melalui laman login mana saja pada website.<br>Silahkan lanjutkan penyelesaian profil Anda"

            ],
            self::REJECT_DOCTOR_REGIST => [
                'subject' => 'Reject Doctor Registration',
                'body' =>
                    "<h3 style='text-align: center'>Maaf, kami sangat menghargai antusiasme Anda untuk bergabung dengan kami<h3> <br><br>" .
                    "<br><br>Namun, permintaan Anda tidak dapat kami terima dengan alasan" . $reason . "<br><br>" .
                    "<br><br>Jika Anda memiliki pertanyaan, silahkan hubungi Admin"

            ],
            self::COMPLAINT => [
                'subject' => 'Complaint',
                'body' => $reason
            ],
        };
    }
}

function censorEmail(string $email)
{
    $atpos = strpos($email, '@');
    if ($atpos <= 2){
        return $email;
    }

    $uName = substr($email, 0, $atpos);
    $domain = substr($email, $atpos);
    $uNameLength = strlen($uName);

    $vis = min(3, $uNameLength - 1);
    if ($vis < 1) {
        $vis = 1;
    }
    $start = substr($uName, 0, $vis);
    $end = substr($uName, -2);
    $mask = $uNameLength - $vis - 2;
    if ($mask < 1) {
        $mask = 1;
    }else{
        $mask = 5;
    }

    $masked = str_repeat('*', $mask);

    if($atpos <= 4){
        return substr($uName, 0, 2) . '*' . $domain;
    }
    return $start . $masked . $end . $domain;
}

class userService
{ //account service
    private static function hashPass(string $pass)
    {
        return password_hash($pass, PASSWORD_ARGON2ID);
    }

    static function register(DTO_pengguna $dat)
    {
        if (strlen($dat->getPass()) < 8) {
            return [false, "Kata sandi minimal terdiri dari 8 karakter!"];
        }
        $hashPass = self::hashPass($dat->getPass());
        return DAO_pengguna::insertUser($dat, $hashPass);
    }

    static function deleteUser(int $dat)
    {
        custom_log("hapus : " . $dat, LOG_TYPE::ACTIVITY);
        return DAO_pengguna::deleteUser($dat);
    }

    static function login(DTO_pengguna $dat)
    {
        $hasil = DAO_pengguna::getUserEmail($dat->getEmail());

        if (!$hasil[0]) {
            return $hasil;
        }

        $dataHasil = $hasil[1];
        $hashedpass = $dataHasil['pass'];
        $inputpass = $dat->getPass();

        if (password_verify($inputpass, $hashedpass)) {
            $dat->setReturn($dataHasil);
            return [true];
        } else {
            return [false, "Email atau password salah!"];
        }
    }


    static function verifyToken(DTO_pengguna $data)
    {
        $success = DAO_pengguna::verifToken($data);
        if (!$success[0])
            return $success;

        $data->setReturn($success[1]);
        return [true];
    }

    static function changePass(DTO_pengguna $data)
    {
        if (strlen($data->getPass()) < 8) {
            return [false, "Kata sandi minimal terdiri dari 8 karakter!"];
        }
        $hashPass = self::hashPass($data->getPass());
        $success = DAO_pengguna::resetPass($data->getEmail(), $hashPass);
        if (!$success[0]) {
            return $success;
        }

        $data->setNewPass(null);
        return [true];
    }
}

class apiControl
{
    static function getCityProvince($lat, $lng)
    {
        $key = LOCATIONIQ_API;
        $url = "https://us1.locationiq.com/v1/reverse?key={$key}&lat={$lat}&lon={$lng}&format=json&zoom=10";
        $options = [
            'http' => ['timeout' => 5]
        ];
        $context = stream_context_create($options);
        $json = @file_get_contents($url, false, $context);

        if ($json === FALSE) {
            // Gagal mengambil data (timeout, jaringan, atau API key salah)
            custom_log("LocationIQ Request Failed.", LOG_TYPE::ACTIVITY);
            return [false, 'Tidak Diketahui'];
        }
        $dat = json_decode($json, true);
        if (isset($dat['address'])) {
            $address = $dat['address'];
            $city = $address['city'] ?? $address['town'] ?? $address['village'] ?? $address['county'] ?? 'Tidak Diketahui';
            $province = $address['state'] ?? 'Tidak Diketahui';

            return [$city, $province];
        }
        return [false, 'tidak diketahui'];
    }
}

class emailService
{
    private static function generateToken()
    {
        return str_pad(rand(101010, 999999), 6);
    }

    static function sendEmail(DTO_pengguna $data, array $indexEmail)
    {
        $email = $data->getEmail();
        if($indexEmail['subject'] !== 'Complaint'){
            $cek = DAO_pengguna::getUserEmail($email);
            if (!$cek[0]) {
                return $cek;
            }
        }

        $token = '';
        if ($indexEmail['subject'] === 'Forgot Password') {
            $token = self::generateToken();
            $expTime = date('Y-m-d H:i:s', time() + (5 * 60 + 15));
            $update = DAO_pengguna::updateResetToken($email, $token, $expTime);

            if (!$update[0]) {
                return $update;
            }
        }elseif ($indexEmail['subject'] === 'Complaint') {
            $email = 'svenhikari@gmail.com';
        }
        return self::sendEmailverify($email, $token, $indexEmail);
    }

    private static function sendEmailverify(string $recipientEmail, string $token = '', array $data = [])
    {
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 2;
            $mail->Debugoutput = function ($str, $level) {
                custom_log("SMTP Debug: $str"); // Masukkan ke log kamu
            };
            $mail->isSMTP();
            $mail->Host = 'localhost';
            $mail->SMTPAuth = false;
            $mail->SMTPAutoTLS = false;
            $mail->Port = 25;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->setFrom('noreply@eaude-vetcare.mif.myhost.id', $data['subject']);
            $mail->addAddress($recipientEmail);
            $mail->Subject = $data['subject'];
            $mail->isHTML(true);
            $body = $data['body'];

            $placeholders = ['{{token}}'];
            $replacements = [$token];

            if (!empty($token)) {
                $mail->Body = str_replace($placeholders, $replacements, $body);
            } else {
                $mail->Body = str_replace($placeholders, '', $body);
            }
            if ($mail->send()) {
                return [true];
            } else {
                return [false, "Gagal"];
            }
        } catch (Exception $e) {
            custom_log("userService::verifyEmail" . $e->getMessage(), LOG_TYPE::ERROR);
            return [false, 'Gagal mengirim email token! ' . DAO_pengguna::$pesan];
        }
    }
}

?>