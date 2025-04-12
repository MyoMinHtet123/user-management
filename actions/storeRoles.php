<?php
session_start();

require_once __DIR__ . '/../models/PermissionsModel.php';
require_once __DIR__ . '/../models/FeaturesModel.php';
require_once __DIR__ . '/../models/RolesModel.php';
require_once __DIR__ . '/../models/RolePermModel.php';

$permModel = new PermissionsModel();
$featuresModel = new FeaturesModel();
$roleModel = new RoleModel();
$rolePermModel = new RolePermModel();

if (isset($_POST['role_name'])) {

    // Create new role
    $roleName = $_POST['role_name'];
    $roleId = $roleModel->createRole($roleName);

    // Add permissions
    if (isset($_POST['permissions'])) {
        $permissions = $_POST['permissions'];

        // Get features name from create form
        $features = array_keys($permissions);

        foreach ($permissions as $permission) {
            $i = 0;
            $featureName = $features[$i];

            // Get feature from features table
            $permFeature = $featuresModel->getFeatureByName($featureName);

            foreach ($permission as $perm) {
                // Add to permissions table with featureId
                $permId = $permModel->addPermissions($permFeature['name'], $permFeature['id']);

                // Relationship to role_permissions 
                $addRolePerm = $rolePermModel->assignPermissionsToRole($roleId, $permId);
            }

            $i++;
        }
    }

    // Redirect to Dashborad
    header('location: ../views/dashboard.php');
    exit;
}
