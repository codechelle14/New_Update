<?php
// BACKEND CODE 
require_once '../backend/config/database.php';

session_start();

$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validation
    if (empty($email) || empty($password)) {
        $error = "Email and password are required!";
    } else {
        // Get database connection
        $conn = getDBConnection();
        
        // Check if user exists
        $stmt = $conn->prepare("SELECT user_id, full_name, email, password_hash FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $full_name, $db_email, $hashed_password);
            $stmt->fetch();
            
            // Verify password
            if (password_verify($password, $hashed_password)) {
                // Set session
                $_SESSION['user_id'] = $user_id;
                $_SESSION['full_name'] = $full_name;
                $_SESSION['email'] = $db_email;
                
                // Update last login
                $update_stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
                $update_stmt->bind_param("i", $user_id);
                $update_stmt->execute();
                $update_stmt->close();
                
                // Redirect to home page
                header("Location: home.php");
                exit();
            } else {
                $error = "Invalid password!";
            }
        } else {
            $error = "No account found with that email!";
        }
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SpotBro – Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="style.css"/>
</head>
<body class="login-body">

  <div class="login-container">
    <div class="login-card">

    <a href="index.php" class="logo-image login-logo">
        <img src="images/logo.png" alt="SpotBro Logo">
    </a>
      <h2 class="login-title">Welcome Back</h2>
      
      <!-- Error Message Display -->
      <?php if ($error): ?>
        <div style="background: #fee; color: #c33; padding: 12px; border-radius: 5px; margin-bottom: 15px;">
          <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
      <?php endif; ?>

      <!-- UPDATED FORM - Added method, action, and name attributes -->
      <form class="login-form" method="POST" action="">
        <div class="form-group">
          <label>Email</label>
          <input type="email" 
                 name="email" 
                 required 
                 placeholder="JonhDoe@gmail.com"
                 value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />
        </div>

        <div class="form-group">
          <label>Password</label>
          <input type="password" 
                 name="password" 
                 required 
                 placeholder="••••••••" 
                 autocomplete="current-password" />
        </div>

        <!-- UPDATED BUTTON - Removed onclick -->
        <button type="submit" class="btn login-btn">Log In & Start Training</button>
      </form>

      <p class="login-signup">
        Don't have an account yet?
        <a href="signup.php">Sign Up</a>
      </p>
    </div>
  </div>

</body>
</html>