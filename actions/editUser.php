<?php
require_once __DIR__ . "/../models/UserModel.php";

// Session Start
session_start();
$current_user = $_SESSION['user'];
if (! $current_user) {
    header('location: ../index.php');
    exit();
}

$userModel = new UserModel();

if (isset($_POST)) {
    $id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $roleId = $_POST['role'];

    // Check if user already exists
    $user = $userModel->getUserByUsername($username);
    if ($user && $user['username'] !== $username) {
        $_SESSION['user_error'] = "User already exists";
        header('location: ../views/users/edit.php');
        exit;
    }

    // Update User
    $data = $userModel->updateUser($id, $name, $email,  $username,  $roleId);

    if ($data) {
        header('location: ../views/dashboard.php');
        exit;
    } else {
        $_SESSION['user_error'] = "User cannot create";
        header('location: ../views/users/create.php');
        exit;
    }
}
