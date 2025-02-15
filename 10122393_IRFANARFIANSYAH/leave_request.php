<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 4) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $leave_type = $_POST['leave_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['reason'];

    $sql = "INSERT INTO leave_requests (employee_id, leave_type, start_date, end_date, reason) 
            VALUES ((SELECT employee_id FROM employees WHERE user_id = ?), ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $user_id, $leave_type, $start_date, $end_date, $reason);
    
    if ($stmt->execute()) {
        $success = "Leave request submitted successfully.";
    } else {
        $error = "Error submitting leave request. Please try again.";
    }
}

$content = '
<h2>Submit Leave Request</h2>
' . (isset($success) ? '<div class="alert alert-success">' . $success . '</div>' : '') . '
' . (isset($error) ? '<div class="alert alert-danger">' . $error . '</div>' : '') . '
<form method="post">
    <div class="mb-3">
        <label for="leave_type" class="form-label">Leave Type</label>
        <select class="form-control" id="leave_type" name="leave_type" required>
            <option value="Annual">Annual</option>
            <option value="Sick">Sick</option>
            <option value="Personal">Personal</option>
            <option value="Other">Other</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="start_date" class="form-label">Start Date</label>
        <input type="date" class="form-control" id="start_date" name="start_date" required>
    </div>
    <div class="mb-3">
        <label for="end_date" class="form-label">End Date</label>
        <input type="date" class="form-control" id="end_date" name="end_date" required>
    </div>
    <div class="mb-3">
        <label for="reason" class="form-label">Reason</label>
        <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit Leave Request</button>
</form>
';

include 'layout.php';
?>

