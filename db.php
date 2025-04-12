<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "sample";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select database
$conn->select_db($dbname);

// Create tables
$tables = [
    "CREATE TABLE IF NOT EXISTS features (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",

    "CREATE TABLE IF NOT EXISTS permissions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        feature_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (feature_id) REFERENCES features(id) ON DELETE CASCADE
    )",

    "CREATE TABLE IF NOT EXISTS roles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",

    "CREATE TABLE IF NOT EXISTS role_permissions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        role_id INT NOT NULL,
        permissions_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
        FOREIGN KEY (permissions_id) REFERENCES permissions(id) ON DELETE CASCADE,
        UNIQUE KEY (role_id, permissions_id)
    )",

    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        username VARCHAR(255) NOT NULL UNIQUE,
        role_id INT NOT NULL,
        phone VARCHAR(20),
        email VARCHAR(255) NOT NULL UNIQUE,
        address TEXT,
        password VARCHAR(255) NOT NULL,
        gender BOOLEAN DEFAULT 1,
        is_active BOOLEAN DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT
    )"
];

foreach ($tables as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Table created successfully :" . explode(" ", $sql)[5] . "<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
}

// Seed data
function seedData($conn)
{
    // Seed features
    $features = ['Users', "Roles"];
    foreach ($features as $feature) {
        $conn->query("INSERT INTO features (name) VALUES ('$feature')");
    }

    // Seed permissions
    $permissions = [
        ['Create', 1],
        ['Read', 1],
        ['Update', 1],
        ['Delete', 1],
        ['Create', 2],
        ['Read', 2],
        ['Update', 2],
        ['Delete', 2],
    ];

    foreach ($permissions as $perm) {
        $name = $conn->real_escape_string($perm[0]);
        $feature_id = $perm[1];
        $conn->query("INSERT INTO permissions (name, feature_id) VALUES ('$name', $feature_id)");
    }

    // Seed roles
    $role = 'admin';
    $conn->query("INSERT INTO roles (name) VALUES ('$role')");

    // Assign all permissions to Admin
    $result = $conn->query("SELECT id FROM roles WHERE name = 'admin'");
    if ($result->num_rows > 0) {
        $role_id = $result->fetch_assoc()['id'];
        $permissions = $conn->query("SELECT * FROM permissions");

        while ($perm = $permissions->fetch_assoc()) {
            $perm_id = $perm['id'];
            $conn->query("INSERT INTO role_permissions (role_id, permissions_id) VALUES ($role_id, $perm_id)");
        }
    }

    // Create admin user
    $adminData = [
        'name' => 'Admin',
        'username' => 'superadmin',
        'role_id' => 1,
        'phone' => '09123456789',
        'email' => 'admin@example.com',
        'address' => 'Yangon, Myanmar',
        'password' => password_hash('admin123', PASSWORD_DEFAULT),
        'gender' => 1,
        'is_active' => 1
    ];

    $stmt = $conn->prepare("INSERT INTO users (name, username, role_id, phone, email, address, password, gender, is_active) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "ssissssii",
        $adminData['name'],
        $adminData['username'],
        $adminData['role_id'],
        $adminData['phone'],
        $adminData['email'],
        $adminData['address'],
        $adminData['password'],
        $adminData['gender'],
        $adminData['is_active']
    );

    try {
        $stmt->execute();
        echo "Admin user created successfully<br>";
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            echo "Admin user already exists<br>";
        } else {
            echo "Error creating admin user: " . $e->getMessage() . "<br>";
        }
    }
}

// Execute seed function
seedData($conn);

// Close connection
$conn->close();
