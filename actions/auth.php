<?php
require_once __DIR__ . "/../models/UserModel.php";

session_start();

$userModel = new UserModel();

// authenticate user
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user = $userModel->getUserByUsername($username);
    if (!$user) {
        $_SESSION['error'] = "Incorrect Username or password";
        header("Location: ../index.php");
        exit;
    }

    // Check password
    if (password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header("Location: ../views/dashboard.php");
        exit;
    }
}
