<?php
// BACKEND CODE 
require_once '../backend/config/database.php';

session_start();

$error = "";
$success = "";

// Password validation function - CHANGED TO 8 CHARACTERS
function validatePassword($password, $confirm_password) {
    // Check if passwords match
    if ($password !== $confirm_password) {
        return "Passwords do not match!";
    }
    
    // CHANGED: Minimum 8 characters (was 12)
    if (strlen($password) < 8) {
        return "Password must be at least 8 characters!";
    }
    
    // Check for uppercase letters
    if (!preg_match('/[A-Z]/', $password)) {
        return "Password must contain at least one uppercase letter!";
    }
    
    // Check for lowercase letters
    if (!preg_match('/[a-z]/', $password)) {
        return "Password must contain at least one lowercase letter!";
    }
    
    // Check for numbers
    if (!preg_match('/[0-9]/', $password)) {
        return "Password must contain at least one number!";
    }
    
    return ""; // No error
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Basic validation
    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required!";
    } else {
        // Validate password strength
        $password_error = validatePassword($password, $confirm_password);
        if ($password_error) {
            $error = $password_error;
        } else {
            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Invalid email format!";
            } else {
                // Get database connection
                $conn = getDBConnection();
                
                // Check if email exists
                $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();
                
                if ($stmt->num_rows > 0) {
                    $error = "Email already registered!";
                } else {
                    // Hash password with strong algorithm
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    
                    $insert_stmt = $conn->prepare("INSERT INTO users (full_name, email, password_hash, created_at) VALUES (?, ?, ?, NOW())");
                    $insert_stmt->bind_param("sss", $full_name, $email, $hashed_password);
                    
                    if ($insert_stmt->execute()) {
                        $success = "Account created successfully! Redirecting...";
                        
                        // Set session
                        $_SESSION['user_id'] = $insert_stmt->insert_id;
                        $_SESSION['full_name'] = $full_name;
                        $_SESSION['email'] = $email;
                        
                        // Redirect after 2 seconds
                        header("refresh:2;url=home.php");
                    } else {
                        $error = "Registration failed: " . $conn->error;
                    }
                    $insert_stmt->close();
                }
                $stmt->close();
                $conn->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SpotBro – Sign Up</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="style.css"/>
</head>
<body class="login-body">

  <div class="login-container">
    <div class="login-card">

      <div class="logo-image login-logo">
        <img src="images/logo.png" alt="SpotBro Logo">
      </div>
      
      <h2 class="login-title">Create Account</h2>
      
      <!-- Error/Success Messages -->
      <?php if ($error): ?>
        <div style="background: #fee; color: #c33; padding: 12px; border-radius: 5px; margin-bottom: 15px;">
          <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>
      
      <?php if ($success): ?>
        <div style="background: #dfd; color: #3a3; padding: 12px; border-radius: 5px; margin-bottom: 15px;">
          <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
        </div>
      <?php endif; ?>

      <form class="login-form" method="POST" action="" autocomplete="off">
        <div class="form-group">
          <label>Full Name</label>
          <input type="text" 
                 name="full_name" 
                 required 
                 placeholder="John Doe" 
                 value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>" />
        </div>

        <div class="form-group">
          <label>Email</label>
          <input type="email" 
                 name="email" 
                 required 
                 placeholder="JohnDoe@gmail.com"
                 value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />
        </div>

        <div class="form-group">
          <label>Password</label>
          <input type="text" 
                 name="password" 
                 required 
                 placeholder="••••••••" 
                 onfocus="this.type='password'"
                 autocomplete="new-password"
                 style="font-family: monospace; letter-spacing: 1px;"
                 oninput="this.value=this.value.replace(/\s/g,'')" />
        </div>

        <div class="form-group">
          <label>Confirm Password</label>
          <input type="text" 
                 name="confirm_password" 
                 required 
                 placeholder="••••••••" 
                 onfocus="this.type='password'"
                 autocomplete="new-password"
                 style="font-family: monospace; letter-spacing: 1px;"
                 oninput="this.value=this.value.replace(/\s/g,'')" />
        </div>

        <button type="submit" class="btn login-btn">Create Account</button>
      </form>

      <p class="login-signup">
        Already have an account?
        <a href="login.php">Log In</a>
      </p>
    </div>
  </div>

  
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    // Disable autocomplete on entire form
    const form = document.querySelector('.login-form');
    if (form) {
      form.setAttribute('autocomplete', 'off');
      form.setAttribute('autocorrect', 'off');
      form.setAttribute('spellcheck', 'false');
    }
    
    // Handle password fields
    const passwordFields = document.querySelectorAll('input[name="password"], input[name="confirm_password"]');
    passwordFields.forEach(function(field) {
      // Set initial type to text (trick browser)
      field.type = 'text';
      
      // Change to password when user starts typing
      field.addEventListener('input', function() {
        if (this.type === 'text') {
          this.type = 'password';
        }
      });
      
      // Prevent paste with browser suggestions
      field.addEventListener('paste', function(e) {
        e.preventDefault();
        const pastedText = (e.clipboardData || window.clipboardData).getData('text');
        this.value = pastedText.replace(/\s/g, '');
        if (this.type === 'text') {
          this.type = 'password';
        }
      });
      
      // Remove any browser-added attributes
      field.removeAttribute('list');
      field.removeAttribute('aria-autocomplete');
    });
  });
  </script>

</body>
</html>