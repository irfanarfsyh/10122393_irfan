<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: login.php");
    exit();
}

$department_id = $_SESSION['department_id'];

$sql = "SELECT e.employee_id, e.first_name, e.last_name, e.position, u.email, r.role_name 
        FROM employees e 
        JOIN users u ON e.user_id = u.user_id 
        JOIN roles r ON u.role_id = r.role_id 
        WHERE u.department_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $department_id);
$stmt->execute();
$result = $stmt->get_result();

$content = '
<h2>Department Employees</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Position</th>
            <th>Email</th>
            <th>Role</th>
        </tr>
    </thead>
    <tbody>
';

while ($row = $result->fetch_assoc()) {
    $content .= '
        <tr>
            <td>' . $row['employee_id'] . '</td>
            <td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>
            <td>' . $row['position'] . '</td>
            <td>' . $row['email'] . '</td>
            <td>' . $row['role_name'] . '</td>
        </tr>
    ';
}

$content .= '
    </tbody>
</table>
';

include 'layout.php';
?>

