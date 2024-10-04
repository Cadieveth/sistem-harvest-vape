-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 04, 2024 at 03:11 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `revisi2`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sync_inventory_with_purchase` ()   BEGIN
    -- Menghapus semua data dari 'inventory'
    DELETE FROM inventory;

    -- Menyalin data dari 'purchase' ke 'inventory'
    INSERT INTO inventory (kode_barang, nama_barang, jumlah_barang, harga_barang)
    SELECT kode_barang, nama_barang, jumlah_barang, harga_barang
    FROM purchase;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(25) NOT NULL,
  `kode_akun` int(25) NOT NULL,
  `nama_akun` varchar(255) NOT NULL,
  `category_id` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `kode_akun`, `nama_akun`, `category_id`) VALUES
(3, 101, 'Kas', 2),
(7, 102, 'Persediaan Barang Dagang', 2),
(9, 301, 'Modal Harvest Vape', 5),
(10, 401, 'Penjualan', 8),
(12, 501, 'Beban Angkut Pembelian', 6),
(13, 124, 'Peralatan', 1),
(14, 105, 'Perlengkapan', 2),
(15, 403, 'Potongan Penjualan', 8),
(16, 502, 'Beban Listrik, Air dan Telepon', 6),
(17, 503, 'Beban Gaji Karyawan', 6),
(18, 504, 'Beban Sewa', 6),
(19, 505, 'Beban Pajak', 7),
(20, 506, 'Beban Pemasaran', 6),
(21, 507, 'Beban Bunga', 7),
(22, 508, 'Beban Asuransi', 6),
(23, 509, 'Beban Lain-lain', 6),
(24, 402, 'Harga Pokok Penjualan', 8);

-- --------------------------------------------------------

--
-- Table structure for table `account_category`
--

CREATE TABLE `account_category` (
  `id` int(25) NOT NULL,
  `category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `account_category`
--

INSERT INTO `account_category` (`id`, `category`) VALUES
(1, 'Aktiva Tetap'),
(2, 'Aktiva Lancar'),
(3, 'Liabilitas'),
(5, 'Modal'),
(6, 'Beban Operasional'),
(7, 'Beban Non-Operasional'),
(8, 'Pendapatan');

-- --------------------------------------------------------

--
-- Table structure for table `detail`
--

CREATE TABLE `detail` (
  `id` bigint(20) NOT NULL,
  `batch` int(25) NOT NULL,
  `kode_barang` varchar(255) NOT NULL,
  `kode_data` varchar(255) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `jumlah_barang` int(11) NOT NULL,
  `harga_barang` decimal(10,2) NOT NULL,
  `ket_barang` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `detail`
--

INSERT INTO `detail` (`id`, `batch`, `kode_barang`, `kode_data`, `nama_barang`, `jumlah_barang`, `harga_barang`, `ket_barang`) VALUES
(16, 1, 'HV/001/LQD3', '1621001', 'MY BUTTER : Butter Cream Cheese 3mg', 20, '148000.00', 'Persediaan'),
(17, 1, 'HV/001/LQD6', '1621002', 'MY BUTTER : Butter Cream Cheese 6mg', 17, '148000.00', 'Persediaan'),
(18, 1, 'HV/001/124', '1622001', 'Mesin 1', 1, '340000.00', 'Peralatan'),
(19, 1, 'HV/002/124', '1622002', 'Mesin 2', 1, '600000.00', 'Peralatan'),
(20, 1, 'HV/001/105', '1623001', 'Pensil 2B', 20, '5000.00', 'Perlengkapan'),
(21, 1, 'HV/002/105', '1623002', 'Ballpoint Standart', 10, '7000.00', 'Perlengkapan');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_purchase` varchar(255) NOT NULL,
  `batch` int(25) NOT NULL,
  `kode_barang` varchar(255) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `jumlah_barang` int(11) NOT NULL,
  `harga_barang` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `kode_purchase`, `batch`, `kode_barang`, `nama_barang`, `jumlah_barang`, `harga_barang`) VALUES
(141, '1621001', 1, 'HV/001/LQD3', 'MY BUTTER : Butter Cream Cheese 3mg', 20, '148000.00'),
(142, '1621002', 1, 'HV/001/LQD6', 'MY BUTTER : Butter Cream Cheese 6mg', 17, '148000.00'),
(143, '1622001', 1, 'HV/001/124', 'Mesin 1', 1, '340000.00'),
(144, '1622002', 1, 'HV/002/124', 'Mesin 2', 1, '600000.00'),
(145, '1623001', 1, 'HV/001/105', 'Pensil 2B', 20, '5000.00'),
(146, '1623002', 1, 'HV/002/105', 'Ballpoint Standart', 10, '7000.00'),
(147, '1624001', 2, 'HV/001/LQD3', 'MY BUTTER : Butter Cream Cheese 3mg', 5, '148000.00');

-- --------------------------------------------------------

--
-- Table structure for table `journal`
--

CREATE TABLE `journal` (
  `id` bigint(20) NOT NULL,
  `tanggal_jurnal` date NOT NULL,
  `debit_acc_id` int(25) NOT NULL,
  `kredit_acc_id` int(25) NOT NULL,
  `debit_jurnal` decimal(10,2) NOT NULL,
  `kredit_jurnal` decimal(10,2) NOT NULL,
  `ket_jurnal` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `journal`
--

INSERT INTO `journal` (`id`, `tanggal_jurnal`, `debit_acc_id`, `kredit_acc_id`, `debit_jurnal`, `kredit_jurnal`, `ket_jurnal`) VALUES
(97, '2024-08-01', 7, 3, '740000.00', '740000.00', 'Pembelian MY BUTTER : Butter Cream Cheese 3mg (HV/001/LQD3)');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(82, '2014_10_12_000000_create_users_table', 1),
(83, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(84, '2019_08_19_000000_create_failed_jobs_table', 1),
(85, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(86, '2023_08_16_062628_create_vendors_table', 1),
(96, '2023_09_02_165128_create_inventory_table', 3),
(100, '2023_09_01_154719_create_purchase_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `neraca_awal`
--

CREATE TABLE `neraca_awal` (
  `id` bigint(25) NOT NULL,
  `id_akun` int(25) NOT NULL,
  `nominal` decimal(10,2) NOT NULL,
  `modal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `neraca_awal`
--

INSERT INTO `neraca_awal` (`id`, `id_akun`, `nominal`, `modal`) VALUES
(23, 7, '5476000.00', '5476000.00'),
(24, 13, '940000.00', '940000.00'),
(25, 14, '170000.00', '170000.00');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('admin1@gmail.com', '$2y$10$evEtTbJ0xm7gJCB8qlnh6O52jDDJKMxV1MHyXDzDebM8q68TGKUbe', '2024-06-18 05:57:27');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_payment` varchar(255) NOT NULL,
  `tanggal_payment` date NOT NULL,
  `ket_payment` varchar(255) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `cost_payment` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `kode_payment`, `tanggal_payment`, `ket_payment`, `keterangan`, `cost_payment`) VALUES
(79, '5102001', '2024-08-01', 'Pengiriman', 'MY BUTTER : Butter Cream Cheese 3mg', '15000.00');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_purchase` varchar(255) NOT NULL,
  `kode_barang` varchar(255) NOT NULL,
  `batch` int(25) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `jumlah_barang` int(11) NOT NULL,
  `harga_barang` decimal(10,2) NOT NULL,
  `harga_pokok` decimal(10,2) NOT NULL,
  `total_purchase` decimal(10,2) NOT NULL,
  `ket_purchase` varchar(255) NOT NULL,
  `tanggal_pembelian` date NOT NULL,
  `biaya_kirim` decimal(10,2) NOT NULL,
  `vendor_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `purchase`
--

INSERT INTO `purchase` (`id`, `kode_purchase`, `kode_barang`, `batch`, `nama_barang`, `jumlah_barang`, `harga_barang`, `harga_pokok`, `total_purchase`, `ket_purchase`, `tanggal_pembelian`, `biaya_kirim`, `vendor_id`) VALUES
(142, '1624001', 'HV/001/LQD3', 2, 'MY BUTTER : Butter Cream Cheese 3mg', 5, '145000.00', '148000.00', '740000.00', 'Persediaan', '2024-08-01', '15000.00', 17);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` bigint(20) NOT NULL,
  `role` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `role`) VALUES
(1, 'Admin'),
(2, 'Staff');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_sales` varchar(255) NOT NULL,
  `date_sales` date NOT NULL,
  `kode_barang` varchar(255) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `harga_barang` float(10,2) NOT NULL,
  `jumlah_sales` int(11) NOT NULL,
  `total_sales` float(10,2) NOT NULL,
  `harga_potongan` float(10,2) NOT NULL,
  `penjualan_bersih` float(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
(1, 'Demo', 'demo', 'demo@gmail.com', '2023-09-02 09:38:07', '$2a$10$tnNLWN1452PjD/9uhQKAB.InRViVBKsG2khw.H.gKlpCxQcsKyocO', NULL, '2023-09-02 09:37:38', '2024-06-24 05:45:42', 'Staff'),
(2, 'Administrator', 'admin', 'adechris211@gmail.com', '2024-03-27 07:44:40', '$2y$10$cFq3ZmKha.NaeFSMKXY2meNcR88x9ahnnBTqoXDviUOlIACP1mibe', '47qMNboj9H7z4mJlcISTa6WcoWrB4DRCOJjIMI3L95PAFQfUpZbz8ZBRAiOW', '2024-03-27 07:44:27', '2024-03-27 07:44:40', 'Admin'),
(4, 'ade1', 'ade1', 'ade1@gmail.com', '2024-06-19 08:34:30', '$2y$10$cgy3tEzR/fNhkM710lrruOb5nVsaVL/led9BvsOU7sX34hbB9SavK', '8qS3agCwM7eZAJ2rs1h3XXLb14NRNv7U8lo4sY0YH5f9LDVoCnF8wzPHKpgc', '2024-06-19 08:11:16', '2024-06-24 22:29:18', 'Staff');

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

CREATE TABLE `vendor` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_vendor` varchar(255) NOT NULL,
  `nama_vendor` varchar(255) NOT NULL,
  `kontak_vendor` varchar(255) NOT NULL,
  `alamat_vendor` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vendor`
--

INSERT INTO `vendor` (`id`, `kode_vendor`, `nama_vendor`, `kontak_vendor`, `alamat_vendor`) VALUES
(17, '9422001', 'Juicenation Co.', '81111111718', 'Jl. Cirangrang Barat No.10, RT.002/RW.001, Margahayu Utara, Kec. Babakan Ciparay, Kota Bandung, Jawa Barat 40224'),
(18, '9422002', 'Vape ZOO', '82184474714', 'Jl. Panjang No.71, RT.6/RW.1, Kedoya Sel., Kec. Kb. Jeruk, Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta 11520'),
(19, '9422003', 'Indonesia Dream Juice', '81259954400', 'Jl. Medokan Keputih Jl. Medokan Semampir No.3, Keputih, Kec. Sukolilo, Kota SBY, Jawa Timur 60111'),
(20, '9422004', 'Blackjack Distribution', '8111251166', 'Jl. K S Tubun No.138, RT.01/RW.03, Cibuluh, Kec. Bogor Utara, Kota Bogor, Jawa Barat 16151'),
(21, '9422005', 'HAFSA ATEKA Print Scan Fotocopy Alat Tulis', '8977468445', 'Jl. Sarjono, Rengas, Tambakboyo, Kec. Ambarawa, Kab. Semarang, Jawa Tengah 50614'),
(22, '9422006', 'Dragonclouds.id', '87749962842', 'https://www.instagram.com/dragoncloudz.id/?hl=en'),
(23, '9422007', 'Geek Vape Indonesia', '83145371401', 'Jl. Barito II No.1a No.1a, RT.9/RW.7, Pulo, Kec. Kebayoran Baru, Jakarta Selatan'),
(24, '9422008', 'tigac', '82112970023', 'Jalan Panjang, No.12 Kav 12-22, Jakarta Barat.'),
(25, '9422009', 'Tokopedia', '87824458288', 'https://www.tokopedia.com/'),
(26, '9422010', 'Shopee', '87824458288', 'https://shopee.co.id/');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_akun` (`kode_akun`);

--
-- Indexes for table `account_category`
--
ALTER TABLE `account_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detail`
--
ALTER TABLE `detail`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_barang` (`kode_barang`),
  ADD UNIQUE KEY `kode_data` (`kode_data`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `journal`
--
ALTER TABLE `journal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `neraca_awal`
--
ALTER TABLE `neraca_awal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_akun` (`id_akun`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_kode_purchase_unique` (`kode_purchase`) USING BTREE,
  ADD KEY `purchase_ibfk_1` (`vendor_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vendor`
--
ALTER TABLE `vendor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vendor_kode_vendor_unique` (`kode_vendor`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `account_category`
--
ALTER TABLE `account_category`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `detail`
--
ALTER TABLE `detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT for table `journal`
--
ALTER TABLE `journal`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `neraca_awal`
--
ALTER TABLE `neraca_awal`
  MODIFY `id` bigint(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vendor`
--
ALTER TABLE `vendor`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `purchase_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendor` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
