<?php
require_once ("functions.php");
date_default_timezone_set('Europe/Istanbul');
session_start();
if (isset($_SESSION["oturum"]) && $_SESSION["oturum"] == "6789") {
  header("location:index.php");
}
if (!(isset($_SESSION["verify"]) && $_SESSION["verify"] == "4567")) {
  header("location:login.php");
}


?>
<!DOCTYPE html>

<html
  lang="en"
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
  <title>2FA Doğrulama FAYU App</title>
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
<?php
if ($_POST) {
  $code1 = $_POST['code1'];
  $code2 = $_POST['code2'];
  $code3 = $_POST['code3'];
  $code4 = $_POST['code4'];
  $code5 = $_POST['code5'];
  $code6 = $_POST['code6'];
  $vcode = $code1 . $code2 . $code3 . $code4 . $code5 . $code6;

  if ($_SESSION["kod"] == $vcode) {
    $_SESSION["oturum"] = "6789";
    unset($_SESSION["verify"]);
    $date = date('Y-m-d H:i:s');
    $updateQuery = "UPDATE kullanicilar SET SonGiris=:SonGiris WHERE id=:id";
    $stmt = $conn->prepare($updateQuery);
    $stmt->execute([
        'SonGiris' => $date,
        'id' => $_SESSION["id"]
    ]);

    header("Location: index.php");
  } else {
    echo '<script type="text/javascript" src="assets/js/sweet-alert/sweetalert2.all.min.js"></script>';
    echo "<script> Swal.fire({title:'Hata     !', text:'Doğrulama kodu hatalı!', icon:'error', confirmButtonText:'Close'});</script>";
  }
}
?>
    <div class="authentication-wrapper authentication-cover authentication-bg">
      <div class="authentication-inner row">
        <!-- /Left Text -->
        <div class="d-none d-lg-flex col-lg-7 p-0">
          <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
            <img
              src="assets/img/illustrations/auth-two-step-illustration-light.png"
              alt="auth-two-steps-cover"
              class="img-fluid my-5 auth-illustration"
              data-app-light-img="illustrations/auth-two-step-illustration-light.png"
              data-app-dark-img="illustrations/auth-two-step-illustration-dark.png" />

            <img
              src="assets/img/illustrations/bg-shape-image-light.png"
              alt="auth-two-steps-cover"
              class="platform-bg"
              data-app-light-img="illustrations/bg-shape-image-light.png"
              data-app-dark-img="illustrations/bg-shape-image-dark.png" />
          </div>
        </div>
        <!-- /Left Text -->

        <!-- Two Steps Verification -->
        <div class="d-flex col-12 col-lg-5 align-items-center p-4 p-sm-5">
          <div class="w-px-400 mx-auto">
            <!-- Logo -->
            <div class="app-brand mb-4">
              <a href="index.php" class="app-brand-link gap-2">
                <img src="assets/img/logo/logo.png">
              </a>
            </div>
            <!-- /Logo -->

            <h4 class="mb-1">2FA Doğrulama 💬</h4>
            <p class="text-start mb-4">
              Lütfen Mail kutunuza gönderilen doğrulama kodunu girin .
              <span class="fw-medium d-block mt-2"><?= $_SESSION["Eposta"] ?></span>
            </p>
            <p class="mb-0 fw-medium">6 Haneli kodu girin</p>
            <form id="twoStepsForm" method="POST">
              <div class="mb-3">
                <div
                  class="auth-input-wrapper d-flex align-items-center justify-content-sm-between numeral-mask-wrapper">

                  <input class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2" name="code1" type="tel" maxlength="1" inputmode="numeric" pattern="[0-9]" required oninput="goToNextInput(event, 'code2')" onkeydown="goToPreviousInput(event, 'code1', 'code1')" autocomplete="off" autofocus />
                  <input class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2" name="code2" type="tel" maxlength="1" inputmode="numeric" pattern="[0-9]" required oninput="goToNextInput(event, 'code3')" onkeydown="goToPreviousInput(event, 'code1', 'code2')" autocomplete="off">
                  <input class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2" name="code3" type="tel" maxlength="1" inputmode="numeric" pattern="[0-9]" required oninput="goToNextInput(event, 'code4')" onkeydown="goToPreviousInput(event, 'code2', 'code3')" autocomplete="off">
                  <input class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2" name="code4" type="tel" maxlength="1" inputmode="numeric" pattern="[0-9]" required oninput="goToNextInput(event, 'code5')" onkeydown="goToPreviousInput(event, 'code3', 'code4')" autocomplete="off">
                  <input class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2" name="code5" type="tel" maxlength="1" inputmode="numeric" pattern="[0-9]" required oninput="goToNextInput(event, 'code6')" onkeydown="goToPreviousInput(event, 'code4', 'code5')" autocomplete="off">
                  <input class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2" name="code6" type="tel" maxlength="1" inputmode="numeric" pattern="[0-9]" required onkeydown="goToPreviousInput(event, 'code5', 'code6')" autocomplete="off">
                </div>
                <!-- Create a hidden field which is combined by 3 fields above -->
                <input type="hidden" name="otp" />
              </div>
              <button class="btn btn-primary d-grid w-100 mb-3">Doğrula</button>
              <div class="text-center">
                Kod Gelmedimi
                <a href="logout.php"> Geri Dön </a>
              </div>
            </form>
          </div>
        </div>
        <!-- /Two Steps Verification -->
      </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/libs/hammer/hammer.js"></script>
    <script src="assets/vendor/libs/i18n/i18n.js"></script>
    <script src="assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="assets/vendor/libs/cleavejs/cleave.js"></script>
    <script src="assets/vendor/libs/@form-validation/umd/bundle/popular.min.js"></script>
    <script src="assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js"></script>
    <script src="assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js"></script>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="assets/js/pages-auth.js"></script>
    <script src="assets/js/pages-auth-two-steps.js"></script>
    <script>
      function goToNextInput(event, nextInputName) {
        var currentInput = event.target;
        if (currentInput.value.length === currentInput.maxLength) {
          var nextInput = document.getElementsByName(nextInputName)[0];
          if (nextInput) {
            nextInput.focus();
          }
        }
      }

      function goToPreviousInput(event, previousInputName, currentInputName) {
        if (event.key === "Backspace" && event.target.value === "") {
          var previousInput = document.getElementsByName(previousInputName)[0];
          if (previousInput) {
            previousInput.focus();
          }
        } else if (event.key === "ArrowLeft" && event.target.selectionStart === 0) {
          var currentInput = document.getElementsByName(currentInputName)[0];
          if (currentInput) {
            currentInput.focus();
          }
        }
      }
    </script>
  </body>
</html>
