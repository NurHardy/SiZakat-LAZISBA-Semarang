-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 29, 2013 at 02:38 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `lazizba`
--

-- --------------------------------------------------------

--
-- Table structure for table `akun`
--

CREATE TABLE IF NOT EXISTS `akun` (
  `idakun` int(11) NOT NULL AUTO_INCREMENT,
  `jenis` int(11) NOT NULL,
  `kode` varchar(18) NOT NULL,
  `namaakun` varchar(30) NOT NULL,
  `keterangan` text NOT NULL,
  `idParent` int(11) NOT NULL,
  PRIMARY KEY (`idakun`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=73 ;

--
-- Dumping data for table `akun`
--

INSERT INTO `akun` (`idakun`, `jenis`, `kode`, `namaakun`, `keterangan`, `idParent`) VALUES
(1, 1, '1.', 'Aktiva', '-', 0),
(2, 2, '2.', 'Penerimaan', '', 0),
(3, 3, '3.', 'Penyaluran', '', 0),
(4, 2, '2.1.', 'Zakat', '-', 2),
(5, 2, '2.1.1.', 'Zakat Individu', '-', 4),
(6, 2, '2.1.2.', 'Zakat Entitas', '-', 4),
(7, 2, '2.2.', 'Infaq / shodaqoh', '-', 2),
(8, 2, '2.2.1.', 'Infaq / shodaqoh Tidak Terikat', '-', 7),
(9, 2, '2.2.2.', 'Infaq / shodaqoh Terikat', '-', 7),
(10, 2, '2.2.2.1.', 'Pendidikan', '-', 9),
(11, 2, '2.2.2.2.', 'Kesehatan', '-', 9),
(12, 2, '2.2.2.3.', 'Ekonomi', '-', 9),
(13, 2, '2.2.2.4.', 'Sosial Kemanusiaan', '-', 9),
(14, 2, '2.3.', 'Wakaf', '-', 2),
(15, 2, '2.3.1.', 'Penerimaan Wakaf Mesjid', '-', 14),
(16, 2, '2.3.2.', 'Penerimaan Wakaf Ambulance', '-', 14),
(17, 2, '2.4.', 'Penerimaan Non Halal', '-', 2),
(18, 2, '2.4.1.', 'Penerimaan Jasa Giro/Bunga Ban', '-', 17),
(19, 2, '2.5.', 'Penerimaan Amil', '-', 2),
(20, 2, '2.5.1.', 'Bagian Amil dari Dana Zakat', '-', 19),
(21, 2, '2.5.2.', 'Bagian Amil dari Dana  Infaq /', '-', 19),
(22, 2, '2.5.3.', 'Bagian Amil dari Dana  Infaq /', '-', 19),
(23, 2, '2.5.4.', 'penerimaan Infaq Operasional', '-', 19),
(24, 2, '2.5.5.', 'Penerimaan Ujroh', '-', 19),
(25, 2, '2.5.6.', 'Penerimaan Bagi Hasil', '-', 19),
(26, 3, '3.1.', 'Zakat', '--', 3),
(27, 3, '3.1.1.', 'Fakir Miskin', '--', 26),
(28, 3, '3.1.2.', 'Amil', '--', 26),
(29, 3, '3.1.3.', 'Ibnu Sabil', '--', 26),
(30, 3, '3.1.4.', 'Mualaf', '--', 26),
(31, 3, '3.1.5.', 'Riqab', '--', 26),
(32, 3, '3.1.6.', 'Gharimin', '--', 26),
(33, 3, '3.1.7.', 'Fil Sabilillah', '---', 26),
(34, 3, '3.1.8.', 'Alokasi Pemanfaatan Aset Kelol', '--', 26),
(35, 3, '3.2.', 'Infaq / shodaqoh', '--', 3),
(36, 3, '3.2.1.', 'Infaq / shodaqoh Tidak Terikat', '--', 35),
(37, 3, '3.2.1.1.', 'pendidikan', '-', 36),
(38, 3, '3.2.1.2.', 'Kesehatan', '-', 36),
(39, 3, '3.2.1.3.', 'Ekonomi', '-', 36),
(40, 3, '3.2.1.4.', 'Yatim', '-', 36),
(41, 3, '3.2.1.5.', 'Dakwah', '-', 36),
(42, 3, '3.2.1.6.', 'Sosial Kemanusiaan', '-', 36),
(43, 3, '3.2.1.7.', 'Bagian Amil', '-', 36),
(44, 3, '3.2.2.', 'Infaq / shodaqoh Terikat', '-', 35),
(45, 3, '3.2.2.1.', 'Pendidikan', '-', 44),
(46, 3, '3.2.2.2.', 'Kesehatan', '-', 44),
(47, 3, '3.2.2.3.', 'Ekonomi', '-', 44),
(48, 3, '3.2.2.4.', 'Sosial Kemanusiaan', '-', 44),
(49, 3, '3.2.2.5.', 'Bagian Amil', '-', 44),
(50, 3, '3.2.3.', 'Alokasi Pemanfaatan Aset Kelol', '-', 35),
(51, 3, '3.3.', 'Waqaf', '-', 3),
(52, 3, '3.3.1.', 'Masjid', '-', 51),
(53, 3, '3.3.2.', 'Fasilitas Umum / Sosial', '-', 51),
(54, 3, '3.4.', 'Non Halal', '-', 3),
(55, 3, '3.4.1.', 'Fasilitas Umum / Sosial', '-', 54),
(56, 3, '3.5.', 'Dana Amil', '-', 3),
(57, 3, '3.5.1.', 'Beban Pegawai', '-', 56),
(58, 3, '3.5.2.', 'Bebang Administrasi / Umum', '-', 56),
(59, 3, '3.5.2.1.', 'Sewa Kantor', '-', 58),
(60, 3, '3.5.2.2.', 'Listrik', '-', 58),
(61, 3, '3.5.2.3.', 'Telepon', '-', 58),
(62, 3, '3.5.2.4.', 'Air', '-', 58),
(63, 3, '3.5.2.5.', 'ATK', '-', 58),
(64, 3, '3.5.2.6.', 'Adm. Bank', '-', 58),
(65, 3, '3.5.3.', 'Beban Penyusutan Amil (AT)', '-', 56),
(66, 3, '3.5.4.', 'Beban Pemeliharaan AT(AMIL)', '-', 56),
(67, 3, '3.5.5.', 'Beban Sosialisasi ZIS', '-', 56),
(69, 1, '1.2.', 'Bank', '', 1),
(72, 1, '1.1.', 'Kas', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `amilin`
--

CREATE TABLE IF NOT EXISTS `amilin` (
  `IdAmilin` int(7) NOT NULL AUTO_INCREMENT,
  `Nama` varchar(50) NOT NULL,
  `Jabatan` varchar(20) NOT NULL,
  `Tmp_Lahir` varchar(20) NOT NULL,
  `Tgl_Lahir` char(10) NOT NULL,
  `Alamat` varchar(100) NOT NULL,
  `Kota` varchar(20) NOT NULL,
  `Telepon` varchar(14) NOT NULL,
  `Hp` varchar(14) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Status` int(11) NOT NULL COMMENT '1 = KAWIN, 2=BELUM KAWIN, 3=JANDA, 4=DUDA',
  `Gol_Darah` varchar(2) NOT NULL,
  `Pend_Akhir` int(11) NOT NULL COMMENT '1=SD/MI, 2=SMP/MTs, 3=SMA/SMK/MA, 4=SARJANA, 5=MAGISTER, 6=DOKTOR',
  PRIMARY KEY (`IdAmilin`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `amilin`
--

INSERT INTO `amilin` (`IdAmilin`, `Nama`, `Jabatan`, `Tmp_Lahir`, `Tgl_Lahir`, `Alamat`, `Kota`, `Telepon`, `Hp`, `Email`, `Status`, `Gol_Darah`, `Pend_Akhir`) VALUES
(7, 'Barokah', 'Amil Zakat', 'Semarang', '12-02-2012', 'Jl. Pandanaran', 'Semarang', '08985602226', '08985602226', 'barokah.fitra@yahoo.com', 1, 'A', 4);

-- --------------------------------------------------------

--
-- Table structure for table `informasi`
--

CREATE TABLE IF NOT EXISTS `informasi` (
  `IdInformasi` int(11) NOT NULL AUTO_INCREMENT,
  `IdAdmin` int(11) NOT NULL,
  `Judul` varchar(50) NOT NULL,
  `Tanggal` char(10) NOT NULL,
  `Isi` text NOT NULL,
  `Status` int(11) NOT NULL,
  PRIMARY KEY (`IdInformasi`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `informasi`
--

INSERT INTO `informasi` (`IdInformasi`, `IdAdmin`, `Judul`, `Tanggal`, `Isi`, `Status`) VALUES
(10, 0, 'Artikel Pertama', '29-09-2013', '<div id="content">\r\n<div class="container-fluid">\r\n<div class="row">\r\n<div class="col-12">\r\n<p style="text-align: justify;">Takmir dan Lazisba Online merupakan sebuah sistem yang dapat membantu dalam melakukan rekapitulasi penerimaan maupun penyaluran dari zakat yang telah diterima. Dengan adanya Takmir dan Lazisba Online ini, masyarakat lebih mudah dalam memperoleh informasi mengenai transaksi zakat, sehingga dapat meningkatkan kepercayaan terhadap pengelolaan zakat yang ada di Masjid Baiturrahman Semarang.</p>\r\n</div>\r\n</div>\r\n</div>\r\n</div>', 2),
(11, 0, 'Event Pertama', '29-09-2013', '<p>Selamat datang di Kegiatan Pertama dari Masjid Baiturrahman Semarang</p>', 3),
(12, 0, 'Tetang Lazisba', '29-09-2013', '<p>Masjid Baiturrahman Semarang pada gambar 1 merupakan sebuah masjid yang terletak di Semarang, Indonesia. Masjid ini dibangun pada tahun 1968 dan selesai pada tahun 1974. Pembangunan Masjid Raya Baiturahman dimulai pada 10 Agustus 1968 dengan ditandai pemasangan tiang pancang untuk pondasi masjid sebanyak 137 buah. Masjid diresmikan oleh Presiden Soeharto pada 15 Desember 1974. Keberadaan masjid ini hingga sekarang menjadi kebanggaan warga Semarang, apalagi lokasinya berada di Simpang Lima yang merupakan pusat kota Semarang. Saat ini Masjid Raya Baiturrahman tidak hanya berfungsi sebagai tempat ibadah dan wadah berkumpulnya umat, melainkan juga pusat dakwah Islam. Jamaah yang hadir ke masjid Raya Baiturrahman Semarang tidak hanya dari kota semarang tetapi juga dating dari luar kota semarang terutama para touris yang sedang berkunjung ke simpang lima semarang.</p>', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mustahik`
--

CREATE TABLE IF NOT EXISTS `mustahik` (
  `IdMustahik` int(7) NOT NULL AUTO_INCREMENT,
  `Nama` varchar(50) NOT NULL,
  `Tmp_Lahir` varchar(20) NOT NULL,
  `Tgl_Lahir` char(10) NOT NULL,
  `Alamat` varchar(100) NOT NULL,
  `Kota` varchar(20) NOT NULL,
  `Telepon` varchar(14) NOT NULL,
  `Hp` varchar(14) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Pekerjaan` varchar(30) NOT NULL,
  `Penghasilan` double NOT NULL,
  PRIMARY KEY (`IdMustahik`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `mustahik`
--

INSERT INTO `mustahik` (`IdMustahik`, `Nama`, `Tmp_Lahir`, `Tgl_Lahir`, `Alamat`, `Kota`, `Telepon`, `Hp`, `Email`, `Pekerjaan`, `Penghasilan`) VALUES
(1, 'Samian', 'Semarang', '24-09-1986', 'Jl Pandanaran', 'Semarang', '024-232332', '08985602226', '-', 'Petani', 100000);

-- --------------------------------------------------------

--
-- Table structure for table `muzakki`
--

CREATE TABLE IF NOT EXISTS `muzakki` (
  `IdMuzakki` int(7) NOT NULL AUTO_INCREMENT,
  `Nama` varchar(50) NOT NULL,
  `Tmp_Lahir` varchar(20) NOT NULL,
  `Tgl_Lahir` char(10) NOT NULL,
  `Alamat` varchar(100) NOT NULL,
  `Kota` varchar(20) NOT NULL,
  `Telepon` varchar(14) NOT NULL,
  `Hp` varchar(14) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Pekerjaan` varchar(30) NOT NULL,
  `Penghasilan` double NOT NULL,
  `Perusahaan` varchar(30) NOT NULL,
  `Alamat_Perusahaan` varchar(100) NOT NULL,
  PRIMARY KEY (`IdMuzakki`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `muzakki`
--

INSERT INTO `muzakki` (`IdMuzakki`, `Nama`, `Tmp_Lahir`, `Tgl_Lahir`, `Alamat`, `Kota`, `Telepon`, `Hp`, `Email`, `Pekerjaan`, `Penghasilan`, `Perusahaan`, `Alamat_Perusahaan`) VALUES
(8, 'Muzakki', 'Semarang', '12-02-2012', 'Jl Sudarto', 'Semarang', '08985602226', '08985602226', 'aliyyil.m@gmail.com', 'Profesor', 10000, 'Becak Jaya', '<p>Khayangan</p>');

-- --------------------------------------------------------

--
-- Table structure for table `penerimaan`
--

CREATE TABLE IF NOT EXISTS `penerimaan` (
  `idpenerimaan` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` char(10) NOT NULL,
  `rekening` varchar(20) NOT NULL,
  `idmuzakki` int(11) NOT NULL,
  `idamilin` int(11) NOT NULL,
  `dutazakat` int(11) NOT NULL COMMENT '1=DZ, 2=NON-DZ',
  `jumlah` int(11) NOT NULL,
  `keterangan` text NOT NULL,
  `idakun` varchar(18) NOT NULL,
  `notransaksi` int(11) NOT NULL,
  PRIMARY KEY (`idpenerimaan`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `penerimaan`
--

INSERT INTO `penerimaan` (`idpenerimaan`, `tanggal`, `rekening`, `idmuzakki`, `idamilin`, `dutazakat`, `jumlah`, `keterangan`, `idakun`, `notransaksi`) VALUES
(1, '04-09-2013', '1.3.', 8, 7, 1, 1000000, '<p>-</p>', '2.1.1.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `penyaluran`
--

CREATE TABLE IF NOT EXISTS `penyaluran` (
  `idpenyaluran` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` char(11) NOT NULL,
  `rekening` int(11) NOT NULL,
  `idmustahik` int(11) NOT NULL,
  `idamilin` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `keterangan` text NOT NULL,
  `idakun` varchar(18) NOT NULL,
  `no_transaksi` int(11) NOT NULL,
  PRIMARY KEY (`idpenyaluran`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `penyaluran`
--

INSERT INTO `penyaluran` (`idpenyaluran`, `tanggal`, `rekening`, `idmustahik`, `idamilin`, `jumlah`, `keterangan`, `idakun`, `no_transaksi`) VALUES
(1, '12-02-2012', 72, 1, 7, 100000, '-', '3.1.1.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `level` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `idmuzakki` int(11) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `level`, `username`, `password`, `idmuzakki`) VALUES
(1, 99, 'admin', '0a61738283f733f2d79da5081c44591712edf195', 0),
(3, 1, 'muzakki', '5a54de74be98cc0585519da9d27a8a0845158312', 8);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
