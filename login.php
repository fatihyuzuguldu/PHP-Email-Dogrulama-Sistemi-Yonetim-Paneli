<?php
require_once("functions.php");
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
} elseif (isset($_COOKIE["cerez"])) {
  $query = $conn->prepare("SELECT * FROM kullanicilar");
  $query->execute();
  while ($result = $query->fetch()) {
    if ($_COOKIE["cerez"] == hash("sha256", "aa" . $result["Eposta"] . "bb")) {
      $_SESSION["verify"] = "4567";
      $_SESSION["Eposta"] = $result["Eposta"];
    }
  }
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
  <title>Giriş Yap FAYU App</title>
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
        <h3 class="mb-1">Hoşgeldin 👋</h3>
        <p class="mb-4">Lütfen giriş yaparak kullanıcı paneline erişin.</p>

        <?php
        if ($_POST) {
          $KullaniciAdi = htmlspecialchars($_POST["txtKullaniciAdi"]);
          $Sifre = hash("sha256", "56" . $_POST["txtSifre"] . "23");
          $date = date('Y-m-d H:i:s');
          if (empty($KullaniciAdi) || empty($Sifre)) {

            echo '<script type="text/javascript" src="assets/js/sweet-alert/sweetalert2.all.min.js"></script>';
            echo "<script> Swal.fire({title:'Hata!', text:'Kullanıcı Adı veua şifre alanını doldurun.', icon:'error', confirmButtonText:'Kapat'});</script>";
          }
          else {
            $query = $conn->prepare("SELECT * FROM kullanicilar WHERE KullaniciAdi=:KullaniciAdi");
            $query->execute(['KullaniciAdi' => $KullaniciAdi]);
            $row = $query->fetch();

            if (!$row) {
              // Kullanıcı adı bulunamadıysa hata ver

              echo '<script type="text/javascript" src="assets/js/sweet-alert/sweetalert2.all.min.js"></script>';
              echo "<script> Swal.fire({title:'Hata!', text:'Kullanıcı Adı Bulunamadı', icon:'error', confirmButtonText:'Kapat'});</script>";

            } else {
              $Eposta = $row["Eposta"];
              $facheck = $row["2FA"];

              if ($Sifre == $row["Sifre"]) {
                if ($facheck == 1) {

                  $_SESSION["id"] = $row["id"];

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
                  <h3>Merhaba: ' . $row["Isim"] . '.</h3>
                </td>
              </tr>
                <tr>
                  <td><img src="https://malimusaviraliozturk.com/admin/assets/images/forms/email.png" width="144" alt="" style=";margin-bottom: 30px;"></td>
                </tr>
                <tr>
                  <td><img src="https://malimusaviraliozturk.com/admin/assets/images/email-template/success.png" alt=""></td>
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

                  $_SESSION["verify"] = "4567";
                  header("Location: verify.php");
                  exit();
                }
                else {
                  $updateQuery = "UPDATE kullanicilar SET SonGiris=:SonGiris WHERE id=:id";
                  $stmt = $conn->prepare($updateQuery);
                  $stmt->execute([
                      'SonGiris' => $date,
                      'id' => $row["id"]
                  ]);
                  $_SESSION["oturum"] = "6789";
                  $_SESSION["id"] = $row["id"];
                  header("Location: index.php");
                  exit();
                }
              }
              else {
                echo '<script type="text/javascript" src="assets/js/sweet-alert/sweetalert2.all.min.js"></script>';
                echo "<script> Swal.fire({title:'Hata!', text:'Şifre Yanlış', icon:'error', confirmButtonText:'Kapat'});</script>";
              }
            }
          }
        }
        ?>
        <form id="formAuthentication" class="mb-3" method="post" action="login.php">
          <div class="mb-3">
            <label for="kullaniciadi" class="form-label">Kullanıcı Adı</label>
            <input
                type="text"
                class="form-control"
                id="kullaniciadi"
                value="<?= @$KullaniciAdi ?>"
                name="txtKullaniciAdi"
                placeholder="Kullanıcı Adı"
                autofocus />
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
                  name="txtSifre"
                  placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                  aria-describedby="Sifre" />
              <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
            </div>
          </div>
          <div class="mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="remember-me" checked/>
              <label class="form-check-label" for="remember-me"> Beni Hatırla </label>
            </div>
          </div>
          <button class="btn btn-primary d-grid w-100" type="submit">Giriş Yap</button>
        </form>
        <div class="mb-3">
          <div style="text-align: center;" class="form-check">
            <a href="register.php"> Hesabın yok mu ? Kayıt Ol</a>
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
