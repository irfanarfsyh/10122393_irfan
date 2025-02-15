<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $hashed_password = hash('sha256', $password);

  $sql = "SELECT u.user_id, u.username, u.password, u.role_id, r.role_name, u.department_id 
          FROM users u 
          JOIN roles r ON u.role_id = r.role_id 
          WHERE u.username = ?";
  
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 1) {
      $user = $result->fetch_assoc();
      if ($hashed_password === $user['password']) {
          $_SESSION['user_id'] = $user['user_id'];
          $_SESSION['username'] = $user['username'];
          $_SESSION['role_id'] = $user['role_id'];
          $_SESSION['role_name'] = $user['role_name'];
          $_SESSION['department_id'] = $user['department_id'];

          switch ($user['role_id']) {
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
                  header("Location: index.php");
          }
          exit();
      } else {
          $error = "Username atau password tidak valid";
      }
  } else {
      $error = "Username atau password tidak valid";
  }
}

$content = '
<div class="row justify-content-center">
  <div class="col-md-6">
      <h2 class="mb-4">Login</h2>
      ' . (isset($error) ? '<div class="alert alert-danger">' . $error . '</div>' : '') . '
      <form method="post">
          <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" name="username" required>
          </div>
          <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <button type="submit" class="btn btn-primary">Login</button>
      </form>
  </div>
</div>
';

include 'layout.php';
?>

