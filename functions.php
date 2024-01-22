<?php
require_once ("vt.php");
function sessionstart()
{
    date_default_timezone_set('Europe/Istanbul');
    session_set_cookie_params(9999999999);
    ini_set('session.gc_maxlifetime', 999999999999);

    session_start();

    // Çerez kontrolü
    if (isset($_SESSION["oturum"])) {
        // "oturum" oturum değişkeni varsa, hiçbir şey yapma
    } else if (isset($_SESSION["verify"])) {
        header("Location: verify.php"); // "verify" oturum değişkeni varsa, verify.php'ye yönlendir
        ob_end_clean();
        exit();
    } else {
        header("Location: login.php"); // Hiçbiri yoksa, login.php'ye yönlendir
        ob_end_clean();
        exit();
    }
}


