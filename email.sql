-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 22 Oca 2024, 14:07:59
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `emailtr`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanicilar`
--

CREATE TABLE `kullanicilar` (
  `id` varchar(36) NOT NULL DEFAULT uuid(),
  `Eposta` varchar(50) NOT NULL,
  `Isim` varchar(50) NOT NULL,
  `KullaniciAdi` varchar(50) NOT NULL,
  `Sifre` varchar(150) NOT NULL,
  `SonGiris` datetime DEFAULT NULL,
  `2FA` int(11) DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `kullanicilar`
--

INSERT INTO `kullanicilar` (`id`, `Eposta`, `Isim`, `KullaniciAdi`, `Sifre`, `SonGiris`, `2FA`) VALUES
('defae573-d845-11ed-a28b-244bfe7ca436', 'info@fatihyuzuguldu.com', 'Fatih Yüzügüldü', '123', '262c90b2c7d9a25950f181caf133965ec1333a74bd75d483fa5ea2ef8f11f740', '2024-01-22 14:45:56', 1);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `kullanicilar`
--
ALTER TABLE `kullanicilar`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
