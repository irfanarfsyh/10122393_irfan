<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_policy'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        
        $sql = "INSERT INTO hr_policies (title, content) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $title, $content);
        
        if ($stmt->execute()) {
            $success = "Policy added successfully.";
        } else {
            $error = "Error adding policy. Please try again.";
        }
    } elseif (isset($_POST['update_policy'])) {
        $policy_id = $_POST['policy_id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        
        $sql = "UPDATE hr_policies SET title = ?, content = ? WHERE policy_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $title, $content, $policy_id);
        
        if ($stmt->execute()) {
            $success = "Policy updated successfully.";
        } else {
            $error = "Error updating policy. Please try again.";
        }
    } elseif (isset($_POST['delete_policy'])) {
        $policy_id = $_POST['policy_id'];
        
        $sql = "DELETE FROM hr_policies WHERE policy_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $policy_id);
        
        if ($stmt->execute()) {
            $success = "Policy deleted successfully.";
        } else {
            $error = "Error deleting policy. Please try again.";
        }
    }
}

$sql = "SELECT * FROM hr_policies ORDER BY title";
$result = $conn->query($sql);

$content = '
<h2>Manage HR Policies</h2>
' . (isset($success) ? '<div class="alert alert-success">' . $success . '</div>' : '') . '
' . (isset($error) ? '<div class="alert alert-danger">' . $error . '</div>' : '') . '
<table class="table table-striped">
    <thead>
        <tr>
            <th>Title</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
';

while ($row = $result->fetch_assoc()) {
    $content .= '
        <tr>
            <td>' . $row['title'] . '</td>
            <td>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editPolicyModal' . $row['policy_id'] . '">Edit</button>
                <form method="post" style="display: inline;">
                    <input type="hidden" name="policy_id" value="' . $row['policy_id'] . '">
                    <button type="submit" name="delete_policy" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this policy?\')">Delete</button>
                </form>
            </td>
        </tr>
        
        <div class="modal fade" id="editPolicyModal' . $row['policy_id'] . '" tabindex="-1" aria-labelledby="editPolicyModalLabel' . $row['policy_id'] . '" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPolicyModalLabel' . $row['policy_id'] . '">Edit Policy</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post">
                        <div class="modal-body">
                            <input type="hidden" name="policy_id" value="' . $row['policy_id'] . '">
                            <div class="mb-3">
                                <label for="title' . $row['policy_id'] . '" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title' . $row['policy_id'] . '" name="title" value="' . $row['title'] . '" required>
                            </div>
                            <div class="mb-3">
                                <label for="content' . $row['policy_id'] . '" class="form-label">Content</label>
                                <textarea class="form-control" id="content' . $row['policy_id'] . '" name="content" rows="5" required>' . $row['content'] . '</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="update_policy" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    ';
}

$content .= '
    </tbody>
</table>

<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPolicyModal">
    Add New Policy
</button>

<div class="modal fade" id="addPolicyModal" tabindex="-1" aria-labelledby="addPolicyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPolicyModalLabel">Add New Policy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="newTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="newTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="newContent" class="form-label">Content</label>
                        <textarea class="form-control" id="newContent" name="content" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_policy" class="btn btn-primary">Add Policy</button>
                </div>
            </form>
        </div>
    </div>
</div>
';

include 'layout.php';
?>

