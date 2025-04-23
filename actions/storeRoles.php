<?php
require_once __DIR__ . '/../models/PermissionsModel.php';
require_once __DIR__ . '/../models/FeaturesModel.php';
require_once __DIR__ . '/../models/RolesModel.php';
require_once __DIR__ . '/../models/RolePermModel.php';

session_start();
$current_user = $_SESSION['user'];
if (! $current_user) {
    header('location: ../index.php');
    exit();
}

$permModel = new PermissionsModel();
$featuresModel = new FeaturesModel();
$roleModel = new RoleModel();
$rolePermModel = new RolePermModel();

if (isset($_POST['role_name'])) {
    $roleName = $_POST['role_name'];

    // Check if role name is already exists
    $role = $roleModel->getRoleByName($roleName);
    if ($role) {
        $_SESSION['error_message'] = "Role name already exists";
        header('location: ../views/roles/create.php');
        exit();
    }

    // Create new role
    $roleId = $roleModel->createRole($roleName);

    // Add permissions
    if (isset($_POST['permissions'])) {
        $permissions = $_POST['permissions'];

        foreach ($permissions as $key => $permission) {
            $feature = $featuresModel->getFeatureByName($key);
            foreach ($permission as $perm) {
                $permId = $permModel->getPermByNameAndId($perm, $feature['id']);
                // Relationship to role_permissions 
                $addRolePerm = $rolePermModel->assignPermissionsToRole($roleId, $permId['id']);
            }
        }
    }

    // Redirect to Dashborad
    header('location: ../views/dashboard.php');
    exit;
}
