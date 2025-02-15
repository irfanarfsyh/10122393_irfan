<?php
require_once 'db_connection.php';

$sql = "ALTER TABLE employee_audit_log ADD COLUMN approved TINYINT(1) DEFAULT NULL";

if ($conn->query($sql) === TRUE) {
    echo "Table employee_audit_log updated successfully";
} else {
    echo "Error updating table: " . $conn->error;
}

$conn->close();
?>

