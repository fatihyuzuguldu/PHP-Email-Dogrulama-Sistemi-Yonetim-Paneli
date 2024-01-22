Login.php ve Register.php dosyalarındaki Eposta stmp ayarlarını yapınız. <br>
Vt.php bölümündeki veritabanı ayarlarını yapınız. <br>

<br>

SQL Oluşturma komutları aşağıda verilmiştir.

<br><br><br>
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;



CREATE TABLE `kullanicilar` (
  `id` varchar(36) NOT NULL DEFAULT uuid(),
  `Eposta` varchar(50) NOT NULL,
  `Isim` varchar(50) NOT NULL,
  `KullaniciAdi` varchar(50) NOT NULL,
  `Sifre` varchar(150) NOT NULL,
  `SonGiris` datetime DEFAULT NULL,
  `2FA` int(11) DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `kullanicilar`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
