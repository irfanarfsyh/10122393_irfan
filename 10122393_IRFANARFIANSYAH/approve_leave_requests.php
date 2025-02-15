<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    header("Location: login.php");
    exit();
}

$department_id = $_SESSION['department_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_id = $_POST['request_id'];
    $status = $_POST['status'];

    $sql = "UPDATE leave_requests SET status = ?, approved_by = ? WHERE request_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $status, $_SESSION['user_id'], $request_id);

    if ($stmt->execute()) {
        $success = "Leave request updated successfully.";
    } else {
        $error = "Error updating leave request. Please try again.";
    }
}

$sql = "SELECT lr.request_id, e.first_name, e.last_name, lr.leave_type, lr.start_date, lr.end_date, lr.reason, lr.status
        FROM leave_requests lr
        JOIN employees e ON lr.employee_id = e.employee_id
        JOIN users u ON e.user_id = u.user_id
        WHERE u.department_id = ? AND lr.status = 'Pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $department_id);
$stmt->execute();
$result = $stmt->get_result();

$content = '
<h2>Approve Leave Requests</h2>
' . (isset($success) ? '<div class="alert alert-success">' . $success . '</div>' : '') . '
' . (isset($error) ? '<div class="alert alert-danger">' . $error . '</div>' : '') . '
<table class="table table-striped">
    <thead>
        <tr>
            <th>Employee</th>
            <th>Leave Type</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Reason</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
';

while ($row = $result->fetch_assoc()) {
    $content .= '
        <tr>
            <td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>
            <td>' . $row['leave_type'] . '</td>
            <td>' . $row['start_date'] . '</td>
            <td>' . $row['end_date'] . '</td>
            <td>' . $row['reason'] . '</td>
            <td>
                <form method="post" style="display: inline;">
                    <input type="hidden" name="request_id" value="' . $row['request_id'] . '">
                    <button type="submit" name="status" value="Approved" class="btn btn-sm btn-success">Approve</button>
                    <button type="submit" name="status" value="Rejected" class="btn btn-sm btn-danger">Reject</button>
                </form>
            </td>
        </tr>
    ';
}

$content .= '
    </tbody>
</table>
';

include 'layout.php';
?>

