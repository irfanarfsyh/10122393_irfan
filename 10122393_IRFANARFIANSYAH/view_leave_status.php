<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 4) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT lr.*, u.username as approved_by_name
        FROM leave_requests lr
        LEFT JOIN users u ON lr.approved_by = u.user_id
        WHERE lr.employee_id = (SELECT employee_id FROM employees WHERE user_id = ?)
        ORDER BY lr.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$content = '
<h2>Leave Request Status</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Leave Type</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Approved By</th>
        </tr>
    </thead>
    <tbody>
';

while ($row = $result->fetch_assoc()) {
    $content .= '
        <tr>
            <td>' . $row['leave_type'] . '</td>
            <td>' . $row['start_date'] . '</td>
            <td>' . $row['end_date'] . '</td>
            <td>' . $row['reason'] . '</td>
            <td>' . $row['status'] . '</td>
            <td>' . ($row['approved_by_name'] ?? 'N/A') . '</td>
        </tr>
    ';
}

$content .= '
    </tbody>
</table>
<a href="leave_request.php" class="btn btn-primary">Submit New Leave Request</a>
';

include 'layout.php';
?>

