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

if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    if ($userModel->deleteUser($userId)) {
        $_SESSION['success'] = "Deleted User Successfully";
        header('location: ../views/dashboard.php');
        exit;
    } else {
        $_SESSION['error'] = "Can not delet User";
        header('location: ../views/dashboard.php');
        exit;
    }
}
