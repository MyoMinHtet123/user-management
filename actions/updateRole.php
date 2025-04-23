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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roleId = $_POST['role_id'];
    $roleName = trim($_POST['role_name']);
    $permissions = $_POST['permissions'] ?? [];
    print_r($permissions);

    if ($roleId && $roleName) {
        //Check if role name already exists 
        $role = $roleModel->getRoleByName($roleName);
        if ($role && $role['name'] !== $roleName) {
            $_SESSION['error_message'] = "Role name already exists";
            header("Location: ../views/roles/edit.php?role_id=" . $roleId . "&&role_name=" . $roleName);
            exit();
        }

        // Update role name
        $result = $roleModel->updateRole($roleId, $roleName);

        // Update role_permissions 
        if (isset($permissions)) {
            $rolePermModel->deleteRolePerms($roleId);
            foreach ($permissions as $permission) {
                foreach ($permission as $perm) {
                    $rolePermModel->assignPermissionsToRole($roleId, $perm);
                }
            }
        }
    }
}

header('location: ../views/roles/rolesList.php');
exit();
