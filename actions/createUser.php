<?php
require_once __DIR__ . "/../models/UserModel.php";

// Session Start
session_start();

$userModel = new UserModel();

if (isset($_POST)) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $roleId = $_POST['role'];

    // Check if user already exists
    $user = $userModel->getUserByUsername($username);
    if ($user) {
        $_SESSION['user_error'] = "User already exists";
        header('location: ../views/users/create.php');
        exit;
    }

    // Check for password
    if ($password !== $confirmPassword) {
        $_SESSION['user_error'] = "Password does not match";
        header('location: ../views/users/create.php');
        exit;
    }

    // Create a new User
    $data = $userModel->createUser($name, $email,  $username, $password, $roleId);

    if ($data) {
        header('location: ../views/dashboard.php');
        exit;
    } else {
        $_SESSION['user_error'] = "User cannot create";
        header('location: ../views/users/create.php');
        exit;
    }
}
