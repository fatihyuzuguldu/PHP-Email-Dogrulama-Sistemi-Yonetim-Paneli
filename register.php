<?php
require_once("functions.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'inc/PHPMailer/PHPMailer.php';
require 'inc/PHPMailer/SMTP.php';
require 'inc/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
session_start();
if (isset($_SESSION["oturum"]) && $_SESSION["oturum"] == "6789") {
  header("Location: index.php");
}
if (isset($_SESSION["verify"]) && $_SESSION["verify"] == "4567") {
  header("Location: verify.php");
}


?>
<!DOCTYPE html>
<html
    lang="tr"
    class="light-style layout-wide customizer-hide"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="assets/"
    data-template="horizontal-menu-template">
<head>
  <meta charset="utf-8" />
  <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>Kayıt Ol FAYU App</title>
  <meta name="description" content="Yönetim Paneli" />
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="assets/img/logo/favicon.png" />
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap" rel="stylesheet" />
  <!-- Icons -->

  <link rel="stylesheet" href="assets/vendor/fonts/tabler-icons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />

</head>

<body>
<!-- Content -->

<div class="authentication-wrapper authentication-cover authentication-bg">
  <div class="authentication-inner row">
    <!-- /Left Text -->
    <div class="d-none d-lg-flex col-lg-7 p-0">
      <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
        <img
            src="assets/img/illustrations/auth-login-illustration-light.png"
            alt="auth-login-cover"
            class="img-fluid my-5 auth-illustration"
            data-app-light-img="illustrations/auth-login-illustration-light.png"
            data-app-dark-img="illustrations/auth-login-illustration-dark.png" />

        <img
            src="assets/img/illustrations/bg-shape-image-light.png"
            alt="auth-login-cover"
            class="platform-bg"
            data-app-light-img="illustrations/bg-shape-image-light.png"
            data-app-dark-img="illustrations/bg-shape-image-dark.png" />
      </div>
    </div>
    <!-- /Left Text -->
    <?php
    if ($_POST) {
      $KullaniciAdi = htmlspecialchars($_POST["KullaniciAdi"]);
      $Sifre = hash("sha256", "56" . $_POST["Sifre"] . "23");
      $ReSifre = hash("sha256", "56" . $_POST["SifreTekrar"] . "23");
      $Verify = htmlspecialchars($_POST["2FA"]);
      $Isim = htmlspecialchars($_POST["Isim"]);
      $Eposta = htmlspecialchars($_POST["Eposta"]);
      $date = date('Y-m-d H:i:s');
      $query = $conn->prepare("SELECT Eposta,KullaniciAdi FROM kullanicilar");
      $query->execute();
      $row = $query->fetch();

      if (empty($KullaniciAdi) || empty($Sifre) || empty($Verify) || empty($Isim) || empty($Eposta) || empty($ReSifre)) {
        echo '<script type="text/javascript" src="assets/js/sweet-alert/sweetalert2.all.min.js"></script>';
        echo "<script> Swal.fire({title:'Hata!', text:'Tüm alanları eksiksiz doldurunuz.', icon:'error', confirmButtonText:'Kapat'})</script>";
      }
      else {
        if($Eposta == $row["Eposta"] || $KullaniciAdi == $row["KullaniciAdi"]){
          echo '<script type="text/javascript" src="assets/js/sweet-alert/sweetalert2.all.min.js"></script>';
          echo "<script> Swal.fire({title:'Hata!', text:'Kullanıcı adı ve şifre sistemde kayıtlı.', icon:'error', confirmButtonText:'Kapat'});</script>";
        }
        else{
          if ($Sifre != $ReSifre)
          {
            echo '<script type="text/javascript" src="assets/js/sweet-alert/sweetalert2.all.min.js"></script>';
            echo "<script> Swal.fire({title:'Hata!', text:'Şifreler Uyuşmuyor', icon:'error', confirmButtonText:'Kapat'});</script>";
          }
          else{
            if (!filter_var($Eposta, FILTER_VALIDATE_EMAIL)) {
              echo '<script type="text/javascript" src="assets/js/sweet-alert/sweetalert2.all.min.js"></script>';
              echo "<script> Swal.fire({title:'Hata!', text:'Eposta Hatalı ', icon:'error', confirmButtonText:'Kapat'});</script>";
            } else {
              if ($Verify == 1) {
                if (!isset($_SESSION['kod']) || !isset($_POST['kod'])) {
                  $_SESSION['kod'] = rand(111111, 999999);
                } elseif (isset($_POST['kod'])) {
                  //validate code
                  if ($_POST['kod'] == $_SESSION['kod']) {
                    unset($_SESSION['kod']);
                  }
                }
                $mail = new PHPMailer(true);
                  $mail->CharSet = 'UTF-8';
                  $mail->Encoding = 'base64';
                  //Server settings
                  $mail->isSMTP();                                            //Send using SMTP
                  $mail->Host       = 'mail.xxxxxxxxxxxx.com';                     //Set the SMTP server to send through
                  $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                  $mail->Username   = 'xxxxxxx@xxxxx.com';                     //SMTP username
                  $mail->Password   = 'xxxxxxxx';                               //SMTP password
                  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                  $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                  //Recipients
                  $mail->setFrom('xxxxx@xxxxxxx.com', 'Fayu Verify Code');
                  $mail->addAddress($row["Eposta"]); // Use the "Email" cookie value
                  //Content
                  $mail->isHTML(true);                                  //Set email format to HTML
                  $mail->Subject = "Doğrulama Kodunuz: " . $_SESSION['kod'];
                  $mail->Body = '
<!DOCTYPE html>
<html lang="tr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="FAYU Yönetim Paneli ile harekete geç">
    <meta name="keywords" content="fayu,fatih,yüzügüldü,yönetim,paneli">
    <meta name="author" content="Fatih Yüzügüldü">
    <link rel="icon" href="https://malimusaviraliozturk.com/admin/assets/images/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="https://malimusaviraliozturk.com/admin/assets/images/favicon.png" type="image/x-icon">
    <title>Verify - FAYU Yönetim Paneli</title>
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <style type="text/css">
      body{
      text-align: center;
      margin: 0 auto;
      width: 650px;
      font-family: work-Sans, sans-serif;
      background-color: #f6f7fb;
      display: block;
      }
      ul{
      margin:0;
      padding: 0;
      }
      li{
      display: inline-block;
      text-decoration: unset;
      }
      a{
      text-decoration: none;
      }
      p{
      margin: 15px 0;
      }
      h5{
      color:#444;
      text-align:left;
      font-weight:400;
      }
      .text-center{
      text-align: center
      }
      .main-bg-light{
      background-color: #fafafa;
      box-shadow: 0px 0px 14px -4px rgba(0, 0, 0, 0.2705882353);
      }
      .title{
      color: #444444;
      font-size: 22px;
      font-weight: bold;
      margin-top: 10px;
      margin-bottom: 10px;
      padding-bottom: 0;
      text-transform: uppercase;
      display: inline-block;
      line-height: 1;
      }
      table{
      margin-top:30px
      }
      table.top-0{
      margin-top:0;
      }
      table.order-detail , .order-detail th , .order-detail td {
      border: 1px solid #ddd;
      border-collapse: collapse;
      }
      .order-detail th{
      font-size:16px;
      padding:15px;
      text-align:center;
      }
      .footer-social-icon tr td img{
      margin-left:5px;
      margin-right:5px;
      }
    </style>
  </head>
  <body style="margin: 20px auto;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" style="padding: 0 30px;background-color: #fff; -webkit-box-shadow: 0px 0px 14px -4px rgba(0, 0, 0, 0.2705882353);box-shadow: 0px 0px 14px -4px rgba(0, 0, 0, 0.2705882353);width: 100%;">
      <tbody>
        <tr>
          <td>
            <table align="center" border="0" cellpadding="0" cellspacing="0">
              <tbody>
              <tr>
                <td>
                  <h3>Merhaba: ' . $Isim . '.</h3>
                </td>
              </tr>
                <tr>
                  <td><img src="https://fatihyuzuguldu.com/admin/assets/images/forms/email.png" width="144" alt="" style=";margin-bottom: 30px;"></td>
                </tr>
                <tr>
                  <td><img src="https://fatihyuzuguldu.com/admin/assets/images/email-template/success.png" alt=""></td>
                </tr>
                <tr>
                  <td>
                    <h3>2FA Kodunuz: ' . $_SESSION['kod'] . '.</h3>
                  </td>
                </tr>
                <tr>
                  <td>
                    <p>Lütfen sayfaya dönüp 2FA Doğrulamasını geçiniz.</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
    <table class="main-bg-light text-center top-0" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
      <tbody>
        <tr>
          <td style="padding: 30px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 20px auto 0;">
              <tbody>
                <tr>
                  <td>
                    <p style="font-size:13px; margin:0;">2023 Copyright by Fatih Yüzügüldü</p>
                  </td>
                </tr>
                <tr>
                  <td><a href="https://fatihyuzuguldu.com" style="font-size:13px; margin:0;text-decoration: underline;">Website</a></td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>';
                $mail->AltBody = strip_tags($_SESSION['kod']);

                $mail->send();
                $mail->ClearAddresses();
                $mail->ClearAttachments();
                $updatequery = $conn->prepare("INSERT INTO kullanicilar (KullaniciAdi, Sifre, 2FA, Isim, Eposta) VALUES (:KullaniciAdi, :Sifre, :2FA, :Isim, :Eposta)");
                $update = $updatequery->execute([
                    'KullaniciAdi' => $KullaniciAdi,
                    'Sifre' => $Sifre,
                    '2FA' => $Verify,
                    'Isim' => $Isim,
                    'Eposta' => $Eposta,
                ]);
                $_SESSION["Eposta"] = $Eposta;
                $_SESSION["KullaniciAdi"] = $KullaniciAdi;
                $_SESSION["Isim"] = $Isim;
                $_SESSION["verify"] = "4567";
                header("Location: verify.php");
                exit();
              }
              else {
                $updatequery = $conn->prepare("INSERT INTO kullanicilar (KullaniciAdi, Sifre, 2FA, Isim, Eposta,SonGiris) VALUES (:KullaniciAdi, :Sifre, :2FA, :Isim, :Eposta,:SonGiris)");
                $update = $updatequery->execute([
                    'KullaniciAdi' => $KullaniciAdi,
                    'Sifre' => $Sifre,
                    '2FA' => $Verify,
                    'Isim' => $Isim,
                    'Eposta' => $Eposta,
                    'SonGiris' => $date,
                ]);

                $_SESSION["oturum"] = "6789";
                $_SESSION["Eposta"] = $Eposta;
                $_SESSION["KullaniciAdi"] = $KullaniciAdi;
                $_SESSION["Isim"] = $Isim;
                $_SESSION["SonGiris"] = $date;
                header("Location: index.php");
                exit();
              }
            }
          }
        }
      }
    }
    ?>
    <!-- Login -->
    <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
      <div class="w-px-400 mx-auto">
        <!-- Logo -->
        <div class="app-brand mb-4">
          <a href="index.php" class="app-brand-link gap-2">
            <img src="assets/img/logo/logo.png">

          </a>
        </div>
        &nbsp;
        <!-- /Logo -->
        <h3 class="mb-1">Kayıt Ol👋</h3>
        <p class="mb-4">Lütfen bilgilerinizi eksiksiz doldurarak kullanıcı paneline erişin.</p>
        <form id="formAuthentication" class="mb-3" method="post"  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

          <div class="mb-3">
            <label for="Isim" class="form-label">Isim</label>
            <input
                type="text"
                class="form-control"
                id="Isim"
                value="<?= @$Isim ?>"
                name="Isim"
                placeholder="Isim"
                autofocus required/>
          </div>
          <div class="mb-3">
            <label for="kullaniciadi" class="form-label">Kullanıcı Adı</label>
            <input
                type="text"
                class="form-control"
                id="kullaniciadi"
                value="<?= @$KullaniciAdi ?>"
                name="KullaniciAdi"
                placeholder="Kullanıcı Adı"
                required/>
          </div>
          <div class="mb-3">
            <label for="Eposta" class="form-label">Eposta</label>
            <input
                type="email"
                class="form-control"
                id="basic-default-email"
                value="<?= @$Eposta ?>"
                name="Eposta"
                placeholder="Eposta" required />
          </div>
          <div class="mb-3">
            <label for="2FA" class="form-label">2FA Doğrulama</label>
            <select name="2FA" id="2FA" class="form-select" required>
              <option value="1">Aktif</option>
              <option value="0">Pasif</option>
            </select>
          </div>
          <div class="mb-3 form-password-toggle">
            <div class="d-flex justify-content-between">
              <label class="form-label" for="password" >Şifre</label>
            </div>
            <div class="input-group input-group-merge">
              <input
                  type="password"
                  id="Sifre"
                  class="form-control"
                  name="Sifre"
                  placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                  aria-describedby="Sifre" required/>
              <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
            </div>
          </div>
          <div class="mb-3 form-password-toggle">
            <div class="d-flex justify-content-between">
              <label class="form-label" for="password">Şifre Tekrar</label>
            </div>
            <div class="input-group input-group-merge">
              <input
                  type="password"
                  id="SifreTekrar"
                  class="form-control"
                  name="SifreTekrar"
                  placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                  aria-describedby="Sifre Tekrar" required/>
              <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
            </div>
          </div>

          <button class="btn btn-primary d-grid w-100" type="submit">Kayıt Ol</button>
        </form>
        <div class="mb-3">
          <div style="text-align: center;" class="form-check">
            <a href="logout.php"> Hesabın var mı ? Giriş Yap</a>
          </div>
        </div>
      </div>
    </div>
    <!-- /Login -->
  </div>
</div>

<!-- Main JS -->
<script src="assets/js/main.js"></script>

</body>
</html>
