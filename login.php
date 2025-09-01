<?php
session_start();
include 'db.php';

// Handle Login
if (isset($_POST['login'])) {
    $username = $_POST['login_username'];
    $password = $_POST['login_password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php"); // go to main site
        exit;
    } else {
        $error = "Wrong username or password!";
    }
}

// Handle Register
if (isset($_POST['register'])) {
    $username = $_POST['register_username'];
    $password = password_hash($_POST['register_password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Username already exists!";
    } else {
        mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('$username', '$password')");
        $success = "Account created! Please log in.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>BASRENG - Login</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

  <div class="login-container">
    <!-- Show messages -->
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>

    <!-- Login Form -->
    <form method="POST" class="login-box">
      <h2>Login</h2>
      <input type="text" name="login_username" placeholder="Username" required>
      <input type="password" name="login_password" placeholder="Password" required>
      <button type="submit" name="login">Login</button>
      <p>Don't have an account? <a href="#" onclick="toggleForms()">Create one</a></p>
    </form>

    <!-- Register Form -->
    <form method="POST" class="login-box hidden" id="register-box">
      <h2>Create Account</h2>
      <input type="text" name="register_username" placeholder="Username" required>
      <input type="password" name="register_password" placeholder="Password" required>
      <button type="submit" name="register">Register</button>
      <p>Already have an account? <a href="#" onclick="toggleForms()">Login here</a></p>
    </form>
  </div>

  <script>
    function toggleForms() {
      document.querySelector(".login-box").classList.toggle("hidden");
      document.getElementById("register-box").classList.toggle("hidden");
    }
  </script>
</body>
</html>
