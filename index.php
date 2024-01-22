<?php
require_once ("functions.php");
error_reporting(0);
ini_set('display_errors', 0);
sessionstart();
$id = $_SESSION["id"];
$query = $conn->prepare("SELECT * FROM kullanicilar WHERE id=:id");
$query->execute(['id' => $id]);
$row = $query->fetch();
?>
  <!DOCTYPE html>
  <html
      lang="tr"
      class="light-style layout-menu-fixed layout-compact"
      dir="ltr"
      data-theme="theme-default"
      data-assets-path="assets/"
      data-template="horizontal-menu-template">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title> FAYU Yönetim Paneli</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/logo/favicon.png" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
  </head>

<body>
  <!-- Layout wrapper -->
<div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
  <div class="layout-container">
  <!-- Navbar -->

  <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="container-xxl">
      <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
        <a href="index.php" class="app-brand-link gap-2">
                <span class="app-brand-logo demo">
                  <img src="assets/img/logo/logo-icon.png" width="22"  >
                </span>
          <span class="app-brand-text demo menu-text fw-bold"> HOŞGELDİN, <?= $row["Isim"] ?></span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
          <i class="ti ti-x ti-sm align-middle"></i>
        </a>
      </div>

      <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
          <i class="ti ti-menu-2 ti-sm"></i>
        </a>
      </div>

      <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">


          <!-- User -->
          <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
              <div class="avatar avatar-online">
                <img src="assets/img/avatars/1.png" alt class="h-auto rounded-circle" />
              </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item">
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                      <div class="avatar avatar-online">
                        <img src="assets/img/avatars/1.png" alt class="h-auto rounded-circle" />
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <span class="fw-medium d-block"><?= $row["Isim"] ?></span>
                      <small class="text-muted">Admin</small>
                    </div>
                  </div>
                </a>
              </li>
              <li>
                <div class="dropdown-divider"></div>
              </li>
              <li>
                <a class="dropdown-item" >
                  <i class="ti ti-user-check me-2 ti-sm"></i>
                  <span class="align-middle">Profil</span>
                </a>
              </li>
              <li>
                <a class="dropdown-item" >
                  <i class="ti ti-settings me-2 ti-sm"></i>
                  <span class="align-middle">Ayarlar</span>
                </a>
              </li>
              <li>
                <a class="dropdown-item" >
                        <span class="d-flex align-items-center align-middle">
                          <i class="flex-shrink-0 ti ti-credit-card me-2 ti-sm"></i>
                          <span class="flex-grow-1 align-middle">Ödemeler</span>
                        </span>
                </a>
              </li>
              <li>
                <div class="dropdown-divider"></div>
              </li>
              <li>
                <a class="dropdown-item">
                  <i class="ti ti-help me-2 ti-sm"></i>
                  <span class="align-middle">SSS</span>
                </a>
              </li>
              <li>
                <a class="dropdown-item" >
                  <i class="ti ti-currency-dollar me-2 ti-sm"></i>
                  <span class="align-middle">Faturalar</span>
                </a>
              </li>
              <li>
                <div class="dropdown-divider"></div>
              </li>
              <li>
                <a class="dropdown-item" href="logout.php" >
                  <i class="ti ti-logout me-2 ti-sm"></i>
                  <span class="align-middle">Çıkış Yap</span>
                </a>
              </li>
            </ul>
          </li>
          <!--/ User -->
        </ul>
      </div>
    </div>
  </nav>

  <!-- / Navbar -->

  <!-- Layout container -->
  <div class="layout-page">
  <!-- Content wrapper -->
  <div class="content-wrapper">
  <!-- Menu -->
  <aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0">
    <div class="container-xxl d-flex h-100">
      <ul class="menu-inner">
        <!-- Dashboards -->
        <li class="menu-item active">
          <a href="index.php" class="menu-link">
            <i class="menu-icon tf-icons ti ti-smart-home"></i>
            <div data-i18n="Anasayfa">Anasayfa</div>
          </a>
        </li>
      </ul>
    </div>
  </aside>
  <!-- / Menu -->

  <!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col-xl-12 mb-4 col-lg-12 col-12">
      <div class="card">
        <div class="d-flex align-items-end row">
          <div class="col-7">
            <div class="card-body text-nowrap">
              <h3 class="card-title mb-0">Hoşgeldiniz <?= $row["Isim"] ?>   🎉</h3>
              <br>
              <h5>Kullanıcı Adı! : <?= $row["KullaniciAdi"] ?>  </h5>
              <h5>Emaili Doğrulandı!  : <?= $row["Eposta"] ?> </h5>
              <h5>Son Başarılı Giriş! : <?= $row["SonGiris"] ?> </h5>
            </div>
          </div>
          <div class="col-5 text-center text-sm-left">
            <div class="card-body pb-0 px-0 px-md-4">
              <img
                  src="assets/img/illustrations/card-advance-sale.png"
                  height="220"
                  alt="view sales" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--/ Content -->


    <!-- Footer -->
    <footer class="content-footer footer bg-footer-theme">
      <div class="container-xxl">
        <div
            class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
          <div>
            ©
            <script>
              document.write(new Date().getFullYear());
            </script>
            , made with ❤️ by <a href="https://fatihyuzuguldu.com" target="_blank" class="fw-medium">Fatih Yüzügüldü</a>
          </div>
        </div>
      </div>
    </footer>
    <!-- / Footer -->

    <div class="content-backdrop fade"></div>
  </div>
    <!--/ Content wrapper -->
  </div>

    <!--/ Layout container -->
  </div>
</div>
  <!-- Main JS -->

  <script src="assets/vendor/libs/jquery/jquery.js"></script>
  <script src="assets/vendor/js/bootstrap.js"></script>

  <script src="assets/vendor/js/menu.js"></script>

  <script src="assets/js/main.js"></script>
  <!-- Page JS -->
  <script src="assets/js/app-ecommerce-dashboard.js"></script>
</body>
</html>
