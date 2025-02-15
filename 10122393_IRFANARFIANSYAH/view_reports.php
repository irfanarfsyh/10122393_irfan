<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: login.php");
    exit();
}

// Total employees
$sql = "SELECT COUNT(*) as total_employees FROM employees";
$result = $conn->query($sql);
$total_employees = $result->fetch_assoc()['total_employees'];

// Total departments
$sql = "SELECT COUNT(*) as total_departments FROM departments";
$result = $conn->query($sql);
$total_departments = $result->fetch_assoc()['total_departments'];

// Average salary
$sql = "SELECT AVG(salary) as avg_salary FROM employees";
$result = $conn->query($sql);
$avg_salary = $result->fetch_assoc()['avg_salary'];

// Leave requests this month
$sql = "SELECT COUNT(*) as leave_requests FROM leave_requests 
        WHERE MONTH(start_date) = MONTH(CURRENT_DATE()) AND YEAR(start_date) = YEAR(CURRENT_DATE())";
$result = $conn->query($sql);
$leave_requests = $result->fetch_assoc()['leave_requests'];

$content = '
<h2>HR Reports</h2>
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Employees</h5>
                <p class="card-text">' . $total_employees . '</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Departments</h5>
                <p class="card-text">' . $total_departments . '</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Average Salary</h5>
                <p class="card-text">$' . number_format($avg_salary, 2) . '</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Leave Requests This Month</h5>
                <p class="card-text">' . $leave_requests . '</p>
            </div>
        </div>
    </div>
</div>

<h3 class="mt-4">Department Overview</h3>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Department</th>
            <th>Total Employees</th>
            <th>Average Salary</th>
        </tr>
    </thead>
    <tbody>
';

$sql = "SELECT d.department_name, 
               COUNT(e.employee_id) as total_employees, 
               AVG(e.salary) as avg_salary
        FROM departments d
        LEFT JOIN users u ON d.department_id = u.department_id
        LEFT JOIN employees e ON u.user_id = e.user_id
        GROUP BY d.department_id
        ORDER BY total_employees DESC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $content .= '
        <tr>
            <td>' . $row['department_name'] . '</td>
            <td>' . $row['total_employees'] . '</td>
            <td>$' . number_format($row['avg_salary'], 2) . '</td>
        </tr>
    ';
}

$content .= '
    </tbody>
</table>
';

include 'layout.php';
?>

