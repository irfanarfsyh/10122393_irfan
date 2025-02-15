<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT e.*, u.email, u.username, d.department_name
        FROM employees e
        JOIN users u ON e.user_id = u.user_id
        LEFT JOIN departments d ON u.department_id = d.department_id
        WHERE e.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

$content = '
<h2>Employee Profile</h2>
<div class="card">
    <div class="card-body">
        <h5 class="card-title">' . $employee['first_name'] . ' ' . $employee['last_name'] . '</h5>
        <p><strong>Username:</strong> ' . $employee['username'] . '</p>
        <p><strong>Email:</strong> ' . $employee['email'] . '</p>
        <p><strong>Department:</strong> ' . ($employee['department_name'] ?? 'N/A') . '</p>
        <p><strong>Position:</strong> ' . $employee['position'] . '</p>
        <p><strong>NIK:</strong> ' . $employee['nik'] . '</p>
        <p><strong>Birth Date:</strong> ' . $employee['birth_date'] . '</p>
        <p><strong>Gender:</strong> ' . ($employee['gender'] == 'M' ? 'Male' : 'Female') . '</p>
        <p><strong>Address:</strong> ' . $employee['address'] . '</p>
        <p><strong>Phone:</strong> ' . $employee['phone'] . '</p>
        <p><strong>Hire Date:</strong> ' . $employee['hire_date'] . '</p>
    </div>
</div>
<a href="edit_profile.php" class="btn btn-primary mt-3">Edit Profile</a>
';

include 'layout.php';
?>

