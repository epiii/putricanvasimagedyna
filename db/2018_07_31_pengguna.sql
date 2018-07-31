-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 31, 2018 at 09:49 AM
-- Server version: 5.7.21-0ubuntu0.16.04.1
-- PHP Version: 7.0.28-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sf_db019`
--

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_fb` text NOT NULL,
  `nama_dpn` text NOT NULL,
  `nama_blk` text NOT NULL,
  `nama_fb` text,
  `no_wa` text NOT NULL,
  `gender` text,
  `email` text,
  `tgl_exp` datetime DEFAULT NULL,
  `nominal` int(11) NOT NULL,
  `tgl_join` datetime DEFAULT NULL,
  `username` text NOT NULL,
  `paytren_id` text,
  `jaguar` text,
  `referal` text,
  `web_training` text NOT NULL,
  `marketing` text,
  `tgl_lunas` date DEFAULT NULL,
  `id` int(11) NOT NULL,
  `dna_id` text NOT NULL,
  `dna_seq` text NOT NULL,
  `dna_level` text NOT NULL,
  `mlm_type` text NOT NULL,
  `id_frame` int(11) DEFAULT NULL,
  `id_promote` int(11) DEFAULT NULL,
  `foto_profil` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_fb`, `nama_dpn`, `nama_blk`, `nama_fb`, `no_wa`, `gender`, `email`, `tgl_exp`, `nominal`, `tgl_join`, `username`, `paytren_id`, `jaguar`, `referal`, `web_training`, `marketing`, `tgl_lunas`, `id`, `dna_id`, `dna_seq`, `dna_level`, `mlm_type`, `id_frame`, `id_promote`, `foto_profil`) VALUES
('1475592029162362', 'Yusriati', 'Yusuf', 'Yusriati Yusuf', '085267531945', 'female', 'Nov', '2020-10-16 02:52:09', 193572, '2017-10-16 14:52:09', 'yusriati', 'VP0430174', 'bukan', 'iman', 'lunas', NULL, '2018-03-30', 69, 'AAA001AAK', 'Huruf', '2', 'pyt', 24, NULL, 'profile_frame_1475592029162362.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1557;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
