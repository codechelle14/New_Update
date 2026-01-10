<?php
echo '<!DOCTYPE html>
<html>
<head>
    <title>SpotBro Setup</title>
    <style>
        body { font-family: Arial; padding: 20px; background:#f0f0f0; }
        .box { background: white; padding: 30px; border-radius: 10px; max-width: 600px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; border: 1px solid #f5c6cb; }
        .info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; border: 1px solid #bee5eb; }
        input { width: 100%; padding: 10px; margin: 5px 0 15px 0; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #4CAF50; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background: #45a049; }
        .step { background: #e9ecef; padding: 10px; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="box">
        <h2>SpotBro Stock Website Setup</h2>
        <div class="step">
            <strong>Step 1:</strong> Make sure XAMPP is running (Apache + MySQL)<br>
            <strong>Step 2:</strong> Fill in your MySQL password below<br>
            <strong>Step 3:</strong> Click "Setup Everything"<br>
        </div>';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = $_POST['host'] ?? 'localhost';
    $user = $_POST['user'] ?? 'root';
    $pass = $_POST['pass'] ?? '';
    $dbname = $_POST['dbname'] ?? 'spotbro_db';
    
    // STEP 1: Test MySQL connection
    echo '<div class="info">Testing MySQL connection...</div>';
    $conn = new mysqli($host, $user, $pass);
    
    if ($conn->connect_error) {
        echo '<div class="error">Connection Failed: ' . $conn->connect_error . '</div>';
        echo '<p><strong>Try:</strong><br>1. Make sure MySQL is running in XAMPP<br>2. Try password: (empty) or "root"<br>3. Check if port 3306 is free</p>';
    } else {
        echo '<div class="success">Connected to MySQL Server</div>';
        
        // STEP 2: Create database
        $conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
        $conn->select_db($dbname);
        echo '<div class="success">Database "' . $dbname . '" created/selected</div>';
        
        // STEP 3: Create ALL tables for stock website
        $tables_created = createStockTables($conn);
        
        if ($tables_created) {
            echo '<div class="success">All database tables created (' . $tables_created . ' tables)</div>';
            
            // STEP 4: Create database.php from template
            if (createDatabaseFile($host, $user, $pass, $dbname)) {
                echo '<div class="success">Configuration file created (database.php)</div>';
                
                // STEP 5: Insert sample data
                insertSampleData($conn);
                
                // FINAL MESSAGE
                echo '<div class="info"><strong>SETUP COMPLETE!</strong></div>';
                echo '<p>Your stock website is ready to use!</p>';
                echo '<p><a href="../frontend/index.php" style="display:inline-block; background:#007bff; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;">🚀 Go to Website</a></p>';
                echo '<p><strong>Admin Login:</strong><br>Username: admin<br>Password: admin123</p>';
            } else {
                echo '<div class="error">Could not create config file. Check folder permissions.</div>';
            }
        }
        $conn->close();
    }
}

// Show setup form
echo '<form method="POST">
    <p><strong>Database Configuration:</strong></p>
    
    <label>MySQL Host:</label>
    <input type="text" name="host" value="localhost" required>
    
    <label>MySQL Username:</label>
    <input type="text" name="user" value="root" required>
    
    <label>MySQL Password:</label>
    <input type="password" name="pass" placeholder="Leave empty for XAMPP default">
    
    <label>Database Name:</label>
    <input type="text" name="dbname" value="spotbro_db" required>
    
    <button type="submit">Setup Everything</button>
</form>

<div class="info">
    <strong>Quick Guide:</strong><br>
    1. Start XAMPP (Apache + MySQL)<br>
    2. Leave password empty for XAMPP default<br>
    3. Click "Setup Everything"<br>
    4. Wait for all checks to complete<br>
    5. Visit your website!
</div>
</div>
</body>
</html>';

// ================= FUNCTIONS =================

function createStockTables($conn) {
    $tables = 0;
    
    // Users table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        user_id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        full_name VARCHAR(100),
        role ENUM('admin', 'user') DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    if ($conn->query($sql)) $tables++;
    
    // Stocks table
    $sql = "CREATE TABLE IF NOT EXISTS stocks (
        stock_id INT PRIMARY KEY AUTO_INCREMENT,
        symbol VARCHAR(10) UNIQUE NOT NULL,
        company_name VARCHAR(100) NOT NULL,
        current_price DECIMAL(10,2) NOT NULL,
        change_percent DECIMAL(5,2),
        volume INT,
        market_cap DECIMAL(15,2),
        last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    if ($conn->query($sql)) $tables++;
    
    // Watchlist table
    $sql = "CREATE TABLE IF NOT EXISTS watchlist (
        watch_id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT,
        stock_id INT,
        added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id),
        FOREIGN KEY (stock_id) REFERENCES stocks(stock_id)
    )";
    if ($conn->query($sql)) $tables++;
    
    // Transactions table
    $sql = "CREATE TABLE IF NOT EXISTS transactions (
        transaction_id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT,
        stock_id INT,
        transaction_type ENUM('BUY', 'SELL') NOT NULL,
        quantity INT NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        total_amount DECIMAL(12,2) NOT NULL,
        transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id),
        FOREIGN KEY (stock_id) REFERENCES stocks(stock_id)
    )";
    if ($conn->query($sql)) $tables++;
    
    // Portfolio table
    $sql = "CREATE TABLE IF NOT EXISTS portfolio (
        portfolio_id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT,
        stock_id INT,
        quantity INT DEFAULT 0,
        average_price DECIMAL(10,2),
        total_investment DECIMAL(12,2),
        FOREIGN KEY (user_id) REFERENCES users(user_id),
        FOREIGN KEY (stock_id) REFERENCES stocks(stock_id)
    )";
    if ($conn->query($sql)) $tables++;
    
    return $tables;
}

function createDatabaseFile($host, $user, $pass, $dbname) {
    $template = "<?php
/**
 * SpotBro Database Configuration
 * AUTO-GENERATED by install.php
 */

define('DB_HOST', '$host');
define('DB_USER', '$user');
define('DB_PASS', '$pass');
define('DB_NAME', '$dbname');

function getDBConnection() {
    \$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if (\$conn->connect_error) {
        die(\"Database connection failed: \" . \$conn->connect_error);
    }
    
    return \$conn;
}

// Auto-create connection for quick use
\$db = getDBConnection();

?>";
    
    // Save to database.php (in same folder)
    return file_put_contents('database.php', $template);
}

function insertSampleData($conn) {
    // Insert admin user
    $hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
    $conn->query("INSERT IGNORE INTO users (username, email, password_hash, full_name, role) 
                  VALUES ('admin', 'admin@spotbro.com', '$hashed_password', 'Administrator', 'admin')");
    
    // Insert sample stocks
    $stocks = [
        ['AAPL', 'Apple Inc.', 175.25, 1.25, 50000000, 2750000000000],
        ['GOOGL', 'Alphabet Inc.', 135.75, -0.50, 25000000, 1750000000000],
        ['TSLA', 'Tesla Inc.', 210.50, 3.25, 75000000, 650000000000],
        ['MSFT', 'Microsoft Corp.', 330.40, 0.75, 35000000, 2450000000000],
        ['AMZN', 'Amazon.com Inc.', 145.80, -1.20, 40000000, 1500000000000]
    ];
    
    foreach ($stocks as $stock) {
        $conn->query("INSERT IGNORE INTO stocks (symbol, company_name, current_price, change_percent, volume, market_cap) 
                      VALUES ('$stock[0]', '$stock[1]', $stock[2], $stock[3], $stock[4], $stock[5])");
    }
    
    echo '<div class="success">✅ Sample data inserted (admin user + 5 stocks)</div>';
}
?>
