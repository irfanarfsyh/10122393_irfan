-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Feb 2025 pada 12.03
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `10122393_if-11_kepegawaian`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_add_employee` (IN `p_username` VARCHAR(50), IN `p_email` VARCHAR(100), IN `p_role_id` INT, IN `p_department_id` INT, IN `p_first_name` VARCHAR(50), IN `p_last_name` VARCHAR(50), IN `p_nik` VARCHAR(20), IN `p_birth_date` DATE, IN `p_gender` CHAR(1), IN `p_address` TEXT, IN `p_phone` VARCHAR(20), IN `p_position` VARCHAR(100), IN `p_salary` DECIMAL(15,2))   BEGIN
    DECLARE v_user_id INT;
    
    -- Insert into users table
    INSERT INTO users (username, password, email, role_id, department_id)
    VALUES (p_username, SHA2('default123', 256), p_email, p_role_id, p_department_id);
    
    SET v_user_id = LAST_INSERT_ID();
    
    -- Insert into employees table
    INSERT INTO employees (
        user_id, first_name, last_name, nik, birth_date, 
        gender, address, phone, hire_date, position, salary
    )
    VALUES (
        v_user_id, p_first_name, p_last_name, p_nik, p_birth_date,
        p_gender, p_address, p_phone, CURRENT_DATE, p_position, p_salary
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_department_employees` (IN `p_department_id` INT)   BEGIN
    SELECT 
        e.employee_id,
        e.first_name,
        e.last_name,
        e.position,
        u.email,
        r.role_name
    FROM employees e
    JOIN users u ON e.user_id = u.user_id
    JOIN roles r ON u.role_id = r.role_id
    WHERE u.department_id = p_department_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL,
  `director_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `director_id`, `created_at`, `updated_at`) VALUES
(1, 'Operations', NULL, '2025-02-14 07:21:55', '2025-02-14 07:21:55'),
(2, 'IT', NULL, '2025-02-14 07:21:55', '2025-02-14 07:21:55'),
(3, 'R&D', NULL, '2025-02-14 07:21:55', '2025-02-14 07:21:55'),
(4, 'Marketing', NULL, '2025-02-14 07:21:55', '2025-02-14 07:21:55'),
(5, 'Mencari cinta sejati', NULL, '2025-02-15 10:02:08', '2025-02-15 10:08:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` enum('M','F') DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `salary` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `employees`
--

INSERT INTO `employees` (`employee_id`, `user_id`, `first_name`, `last_name`, `nik`, `birth_date`, `gender`, `address`, `phone`, `hire_date`, `position`, `salary`, `created_at`, `updated_at`) VALUES
(1, 1, 'Super', 'Admin', 'ADM001', '1990-01-01', 'M', 'Jakarta', '081234567890', '2024-01-01', 'Direktur Utama', 0.00, '2025-02-14 07:42:32', '2025-02-14 07:42:32'),
(2, 2, 'Budi', 'Santoso', 'DIR001', '1975-03-15', 'M', 'Jakarta Selatan', '081234567891', '2020-01-01', 'Direktur Operasional', 25000000.00, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(3, 3, 'Siti', 'Rahayu', 'MGR001', '1980-06-20', 'F', 'Jakarta Barat', '081234567892', '2020-02-01', 'HR & Administration Manager', 15000000.00, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(4, 4, 'Dewi', 'Lestari', 'EMP001', '1990-08-25', 'F', 'Jakarta Timur', '081234567893', '2021-01-15', 'HR Staff', 8000000.00, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(5, 5, 'Rudi', 'Hartono', 'EMP002', '1992-11-30', 'M', 'Jakarta Utara', '081234567894', '2021-02-01', 'Admin Staff', 7500000.00, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(6, 6, 'irfan', 'arfiansyah', 'DIR002', '1978-04-10', 'M', 'Jakarta Selatan', '081234567895', '2020-01-01', 'Direktur IT', 25000000.00, '2025-02-14 07:54:16', '2025-02-15 09:44:35'),
(7, 7, 'Anton', 'Susanto', 'MGR002', '1982-07-15', 'M', 'Jakarta Barat', '081234567896', '2020-02-01', 'IT Services Manager', 15000000.00, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(8, 8, 'Andi', 'Putra', 'EMP003', '1991-09-20', 'M', 'Jakarta Timur', '08123456000', '2021-03-01', 'System Administrator', 9999999.25, '2025-02-14 07:54:16', '2025-02-15 10:44:08'),
(9, 9, 'Nina', 'Sari', 'EMP004', '1993-12-05', 'F', 'Jakarta Utara', '081234567898', '2021-04-01', 'Web Developer', 9500000.00, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(10, 10, 'Bambang', 'Kusuma', 'DIR003', '1976-05-20', 'M', 'Jakarta Selatan', '081234567899', '2020-01-01', 'Direktur R&D', 25000000.00, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(11, 11, 'Maya', 'Putri', 'MGR003', '1983-08-25', 'F', 'Jakarta Barat', '081234567800', '2020-02-01', 'R&D Manager', 15000000.00, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(12, 12, 'Dimas', 'Prayoga', 'EMP005', '1992-10-15', 'M', 'Jakarta Timur', '081234567801', '2021-05-01', 'Research Analyst', 9000000.00, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(13, 13, 'Linda', 'Wati', 'EMP006', '1994-01-10', 'F', 'Jakarta Utara', '081234567802', '2021-06-01', 'Research Assistant', 8500000.00, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(14, 14, 'Agus', 'Wibowo', 'DIR004', '1977-06-25', 'M', 'Jakarta Selatan', '081234567803', '2020-01-01', 'Direktur Pemasaran', 25000000.00, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(15, 15, 'Sarah', 'Anggraini', 'MGR004', '1984-09-30', 'F', 'Jakarta Barat', '081234567804', '2020-02-01', 'Marketing Strategy Manager', 15000000.00, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(16, 16, 'Rizki', 'Pratama', 'EMP007', '1993-11-20', 'M', 'Jakarta Timur', '081234567805', '2021-07-01', 'Marketing Specialist', 8500000.00, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(17, 17, 'Anita', 'Dewi', 'EMP008', '1995-02-15', 'F', 'Jakarta Utara', '081234567806', '2021-08-01', 'Marketing Assistant', 8000000.00, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(18, 19, 'lukman', 'sah', NULL, NULL, NULL, NULL, NULL, '2025-02-15', 'HR Staff', 122.00, '2025-02-15 10:35:13', '2025-02-15 10:35:13'),
(19, 20, 'Putri', 'Handayani', 'EMP009', '1993-03-15', 'F', 'Bekasi', '081234567807', '2022-01-15', 'Recruitment Staff', 8500000.00, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(20, 21, 'Fajar', 'Ramadhan', 'EMP010', '1994-05-20', 'M', 'Depok', '081234567808', '2022-02-01', 'Training Staff', 8500000.00, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(21, 22, 'Eva', 'Marlina', 'EMP011', '1992-07-25', 'F', 'Tangerang', '081234567809', '2022-03-15', 'Payroll Staff', 8800000.00, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(22, 23, 'Dani', 'Setiawan', 'EMP012', '1991-09-30', 'M', 'Bogor', '081234567810', '2022-04-01', 'HR Administration', 8300000.00, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(23, 24, 'Raka', 'Hidayat', 'EMP013', '1994-11-05', 'M', 'Jakarta Selatan', '081234567811', '2022-05-15', 'Backend Developer', 12000000.00, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(24, 25, 'Diana', 'Puspita', 'EMP014', '1993-12-10', 'F', 'Jakarta Timur', '081234567812', '2022-06-01', 'Frontend Developer', 11000000.00, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(25, 26, 'Aditya', 'Nugroho', 'EMP015', '1995-01-15', 'M', 'Bekasi', '081234567813', '2022-07-15', 'Database Administrator', 12500000.00, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(26, 27, 'Sinta', 'Wijaya', 'EMP016', '1994-02-20', 'F', 'Depok', '081234567814', '2022-08-01', 'UI/UX Designer', 10500000.00, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(27, 28, 'Bayu', 'Prasetyo', 'EMP017', '1993-03-25', 'M', 'Jakarta Barat', '081234567815', '2022-09-15', 'Product Researcher', 9500000.00, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(28, 29, 'Rina', 'Fitriani', 'EMP018', '1994-04-30', 'F', 'Tangerang', '081234567816', '2022-10-01', 'Market Researcher', 9300000.00, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(29, 30, 'Galih', 'Pratama', 'EMP019', '1995-05-05', 'M', 'Jakarta Utara', '081234567817', '2022-11-15', 'Data Analyst', 10000000.00, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(30, 31, 'Citra', 'Permata', 'EMP020', '1994-06-10', 'F', 'Bogor', '081234567818', '2022-12-01', 'Research Coordinator', 9800000.00, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(31, 32, 'Arif', 'Gunawan', 'EMP021', '1993-07-15', 'M', 'Jakarta Selatan', '081234567819', '2023-01-15', 'Digital Marketing', 9500000.00, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(32, 33, 'Maya', 'Safitri', 'EMP022', '1994-08-20', 'F', 'Jakarta Timur', '081234567820', '2023-02-01', 'Content Creator', 9000000.00, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(33, 34, 'Yoga', 'Pratama', 'EMP023', '1995-09-25', 'M', 'Bekasi', '081234567821', '2023-03-15', 'Social Media Specialist', 8800000.00, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(34, 35, 'Dina', 'Maharani', 'EMP024', '1994-10-30', 'F', 'Depok', '081234567822', '2023-04-01', 'Brand Executive', 9200000.00, '2025-02-15 10:55:17', '2025-02-15 10:55:17');

--
-- Trigger `employees`
--
DELIMITER $$
CREATE TRIGGER `before_employee_update` BEFORE UPDATE ON `employees` FOR EACH ROW BEGIN
    INSERT INTO employee_audit_log (
        employee_id,
        field_name,
        old_value,
        new_value,
        changed_by
    )
    SELECT 
        OLD.employee_id,
        'salary',
        OLD.salary,
        NEW.salary,
        @user_id
    WHERE OLD.salary != NEW.salary;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `employee_audit_log`
--

CREATE TABLE `employee_audit_log` (
  `log_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `field_name` varchar(50) DEFAULT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `changed_by` int(11) DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `employee_audit_log`
--

INSERT INTO `employee_audit_log` (`log_id`, `employee_id`, `field_name`, `old_value`, `new_value`, `changed_by`, `changed_at`, `approved`) VALUES
(1, 8, 'salary', '10000000.00', '9999999.25', NULL, '2025-02-15 10:44:08', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `leave_requests`
--

CREATE TABLE `leave_requests` (
  `request_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `leave_type` enum('Annual','Sick','Personal','Other') DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `approved_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `leave_requests`
--

INSERT INTO `leave_requests` (`request_id`, `employee_id`, `leave_type`, `start_date`, `end_date`, `reason`, `status`, `approved_by`, `created_at`, `updated_at`) VALUES
(1, 8, 'Sick', '2025-02-13', '2025-02-14', 'panas tiris', 'Approved', 7, '2025-02-14 08:02:52', '2025-02-14 08:03:21'),
(2, 8, 'Personal', '2025-02-12', '2025-02-14', 'pusing', 'Pending', NULL, '2025-02-15 10:05:52', '2025-02-15 10:05:52'),
(3, 3, 'Annual', '2024-03-01', '2024-03-03', 'Family vacation', 'Approved', NULL, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(4, 5, 'Sick', '2024-02-15', '2024-02-16', 'Fever', 'Approved', NULL, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(5, 7, 'Personal', '2024-04-01', '2024-04-02', 'Personal matters', 'Pending', NULL, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(6, 9, 'Other', '2024-03-20', '2024-03-21', 'Wedding ceremony', 'Approved', NULL, '2025-02-15 10:55:17', '2025-02-15 10:55:17');

--
-- Trigger `leave_requests`
--
DELIMITER $$
CREATE TRIGGER `after_leave_approval` AFTER UPDATE ON `leave_requests` FOR EACH ROW BEGIN
    IF NEW.status = 'Approved' AND OLD.status = 'Pending' THEN
        INSERT INTO notifications (
            user_id,
            message,
            type
        )
        SELECT 
            e.user_id,
            CONCAT('Your leave request from ', NEW.start_date, ' to ', NEW.end_date, ' has been approved'),
            'leave_approval'
        FROM employees e
        WHERE e.employee_id = NEW.employee_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `message`, `type`, `is_read`, `created_at`) VALUES
(1, 8, 'Your leave request from 2025-02-13 to 2025-02-14 has been approved', 'leave_approval', 0, '2025-02-14 08:03:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', '2025-02-14 07:21:55', '2025-02-14 07:21:55'),
(2, 'Director', '2025-02-14 07:21:55', '2025-02-14 07:21:55'),
(3, 'Manager', '2025-02-14 07:21:55', '2025-02-14 07:21:55'),
(4, 'Employee', '2025-02-14 07:21:55', '2025-02-14 07:21:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `role_id`, `department_id`, `manager_id`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 'admin@company.com', 1, NULL, NULL, 1, '2025-02-14 14:47:36', '2025-02-14 07:42:32', '2025-02-14 07:47:36'),
(2, 'dir_ops', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'dir.ops@company.com', 2, 1, NULL, 1, NULL, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(3, 'mgr_hr', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'mgr.hr@company.com', 3, 1, 2, 1, NULL, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(4, 'staff_hr1', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'staff.hr1@company.com', 4, 1, 3, 1, NULL, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(5, 'staff_hr2', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'staff.hr2@company.com', 4, 1, 3, 1, NULL, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(6, 'dir_it', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'dir.it@company.com', 2, 2, NULL, 1, NULL, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(7, 'mgr_it', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'mgr.it@company.com', 3, 2, 6, 1, NULL, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(8, 'staff_it1', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'staff.it1@company.com', 4, 3, 7, 1, NULL, '2025-02-14 07:54:16', '2025-02-15 10:44:08'),
(9, 'staff_it2', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'staff.it2@company.com', 4, 2, 7, 1, NULL, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(10, 'dir_rnd', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'dir.rnd@company.com', 2, 3, NULL, 1, NULL, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(11, 'mgr_rnd', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'mgr.rnd@company.com', 3, 3, 10, 1, NULL, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(12, 'staff_rnd1', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'staff.rnd1@company.com', 4, 3, 11, 1, NULL, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(13, 'staff_rnd2', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'staff.rnd2@company.com', 4, 3, 11, 1, NULL, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(14, 'dir_mkt', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'dir.marketing@company.com', 2, 4, NULL, 1, NULL, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(15, 'mgr_mkt', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'mgr.marketing@company.com', 3, 4, 14, 1, NULL, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(16, 'staff_mkt1', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'staff.mkt1@company.com', 4, 4, 15, 1, NULL, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(17, 'staff_mkt2', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'staff.mkt2@company.com', 4, 4, 15, 1, NULL, '2025-02-14 07:54:16', '2025-02-14 07:54:16'),
(18, 'dir_mct', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'dirmct@company.com', 2, 5, NULL, 1, NULL, '2025-02-15 10:10:35', '2025-02-15 10:10:35'),
(19, 'staffsus', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'stafsus@company.com', 4, 5, NULL, 1, NULL, '2025-02-15 10:35:13', '2025-02-15 10:35:13'),
(20, 'staff_hr3', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'staff.hr3@company.com', 4, 1, NULL, 1, NULL, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(21, 'staff_hr4', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'staff.hr4@company.com', 4, 1, NULL, 1, NULL, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(22, 'staff_hr5', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'staff.hr5@company.com', 4, 1, NULL, 1, NULL, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(23, 'staff_hr6', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'staff.hr6@company.com', 4, 1, NULL, 1, NULL, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(24, 'staff_it3', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'staff.it3@company.com', 4, 2, NULL, 1, NULL, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(25, 'staff_it4', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'staff.it4@company.com', 4, 2, NULL, 1, NULL, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(26, 'staff_it5', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'staff.it5@company.com', 4, 2, NULL, 1, NULL, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(27, 'staff_it6', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'staff.it6@company.com', 4, 2, NULL, 1, NULL, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(28, 'staff_rnd3', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'staff.rnd3@company.com', 4, 3, NULL, 1, NULL, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(29, 'staff_rnd4', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'staff.rnd4@company.com', 4, 3, NULL, 1, NULL, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(30, 'staff_rnd5', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'staff.rnd5@company.com', 4, 3, NULL, 1, NULL, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(31, 'staff_rnd6', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'staff.rnd6@company.com', 4, 3, NULL, 1, NULL, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(32, 'staff_mkt3', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'staff.mkt3@company.com', 4, 4, NULL, 1, NULL, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(33, 'staff_mkt4', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'staff.mkt4@company.com', 4, 4, NULL, 1, NULL, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(34, 'staff_mkt5', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'staff.mkt5@company.com', 4, 4, NULL, 1, NULL, '2025-02-15 10:55:17', '2025-02-15 10:55:17'),
(35, 'staff_mkt6', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'staff.mkt6@company.com', 4, 4, NULL, 1, NULL, '2025-02-15 10:55:17', '2025-02-15 10:55:17');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indeks untuk tabel `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- Indeks untuk tabel `employee_audit_log`
--
ALTER TABLE `employee_audit_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `changed_by` (`changed_by`);

--
-- Indeks untuk tabel `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `approved_by` (`approved_by`);

--
-- Indeks untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `manager_id` (`manager_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT untuk tabel `employee_audit_log`
--
ALTER TABLE `employee_audit_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Ketidakleluasaan untuk tabel `employee_audit_log`
--
ALTER TABLE `employee_audit_log`
  ADD CONSTRAINT `employee_audit_log_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  ADD CONSTRAINT `employee_audit_log_ibfk_2` FOREIGN KEY (`changed_by`) REFERENCES `users` (`user_id`);

--
-- Ketidakleluasaan untuk tabel `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  ADD CONSTRAINT `leave_requests_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `users` (`user_id`);

--
-- Ketidakleluasaan untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`),
  ADD CONSTRAINT `users_ibfk_3` FOREIGN KEY (`manager_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
