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
              $updatequery = $conn->prepare("INSERT INTO kullanicilar (KullaniciAdi, Sifre, 2FA, Isim, Eposta) VALUES (:KullaniciAdi, :Sifre, :2FA, :Isim, :Eposta)");
              $update = $updatequery->execute([
                  'KullaniciAdi' => $KullaniciAdi,
                  'Sifre' => $Sifre,
                  '2FA' => $Verify,
                  'Isim' => $Isim,
                  'Eposta' => $Eposta,
              ]);
              $_SESSION["oturum"] = "6789";
              header("Location: logout.php");
              exit();
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
