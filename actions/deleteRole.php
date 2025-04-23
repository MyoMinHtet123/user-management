<?php
require_once '../models/RolesModel.php';
require_once '../models/RolePermModel.php';

session_start();
$current_user = $_SESSION['user'];
if (! $current_user) {
    header('location: ../index.php');
    exit();
}

$roleModel = new RoleModel();
$rolePermModel = new RolePermModel();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $roleId = $_GET['role_id'];

    // Delete Role
    $result = $roleModel->deleteRole($roleId);
    // Delet role permissions
    if ($result) {
        $rolePermModel->deleteRolePerms($roleId);
    }
}

header('location: ../views/roles/rolesList.php');
exit();
