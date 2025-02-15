<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

switch ($_SESSION['role_id']) {
  case 1:
      header("Location: super_admin_dashboard.php");
      break;
  case 2:
      header("Location: director_dashboard.php");
      break;
  case 3:
      header("Location: manager_dashboard.php");
      break;
  case 4:
      header("Location: employee_dashboard.php");
      break;
  default:
      $content = '<h1>Selamat datang di Sistem Manajemen Karyawan</h1>';
      include 'layout.php';
}
?>

