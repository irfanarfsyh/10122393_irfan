<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role_id'] != 2 && $_SESSION['role_id'] != 3)) {
    header("Location: login.php");
    exit();
}

$department_id = $_SESSION['department_id'];

// Total employees
$sql = "SELECT COUNT(*) as total_employees FROM employees e JOIN users u ON e.user_id = u.user_id WHERE u.department_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $department_id);
$stmt->execute();
$result = $stmt->get_result();
$total_employees = $result->fetch_assoc()['total_employees'];

// Average salary
$sql = "SELECT AVG(salary) as avg_salary FROM employees e JOIN users u ON e.user_id = u.user_id WHERE u.department_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $department_id);
$stmt->execute();
$result = $stmt->get_result();
$avg_salary = $result->fetch_assoc()['avg_salary'];

// Leave requests this month
$sql = "SELECT COUNT(*) as leave_requests FROM leave_requests lr 
        JOIN employees e ON lr.employee_id = e.employee_id 
        JOIN users u ON e.user_id = u.user_id 
        WHERE u.department_id = ? AND MONTH(lr.start_date) = MONTH(CURRENT_DATE()) AND YEAR(lr.start_date) = YEAR(CURRENT_DATE())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $department_id);
$stmt->execute();
$result = $stmt->get_result();
$leave_requests = $result->fetch_assoc()['leave_requests'];

$content = '
<h2>Department Reports</h2>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Employees</h5>
                <p class="card-text">' . $total_employees . '</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Average Salary</h5>
                <p class="card-text">$' . number_format($avg_salary, 2) . '</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Leave Requests This Month</h5>
                <p class="card-text">' . $leave_requests . '</p>
            </div>
        </div>
    </div>
</div>

<h3 class="mt-4">Employee List</h3>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Position</th>
            <th>Hire Date</th>
            <th>Salary</th>
        </tr>
    </thead>
    <tbody>
';

$sql = "SELECT e.first_name, e.last_name, e.position, e.hire_date, e.salary 
        FROM employees e 
        JOIN users u ON e.user_id = u.user_id 
        WHERE u.department_id = ?
        ORDER BY e.salary DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $department_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $content .= '
        <tr>
            <td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>
            <td>' . $row['position'] . '</td>
            <td>' . $row['hire_date'] . '</td>
            <td>$' . number_format($row['salary'], 2) . '</td>
        </tr>
    ';
}

$content .= '
    </tbody>
</table>
';

include 'layout.php';
?>

