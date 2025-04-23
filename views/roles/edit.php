<?php
require_once '../../models/RolesModel.php';
require_once '../../models/RolePermModel.php';

// Session_start();
session_start();

$current_user = $_SESSION['user'];
if (! $current_user) {
    header('location: ../../index.php');
    exit();
}

$rolePermModel = new RolePermModel();
if (isset($_GET['role_id'])) {
    // Get role name and id
    $roleName = $_GET['role_name'];
    $roleId = $_GET['role_id'];
}
// Get permissions by current user Id
$permissions = $rolePermModel->getPerByRoleId($current_user['role_id']);

// Get permissions for update role
$rolePerms = $rolePermModel->getPerByRoleId($roleId);

// Helper function to check permissions
function hasPermission($permissionKey, $featureName, $permissions)
{
    foreach ($permissions as $permission) {
        if ($permission['feature_name'] === $featureName) {
            if ($permission['permission_name'] === $permissionKey) {
                return 1;
            }
        }
    }
}

// Check if current user has permission to update roles
if (!hasPermission('Update', 'Roles', $permissions)) {
    header('location: ../dashboard.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Roles</title>
</head>

<body>
    <div class="container py-5">
        <h2 class="mb-4">Edit Role</h2>
        <?php if (isset($_SESSION['error_message'])) : ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <?= $_SESSION['error_message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <form action="../../actions/updateRole.php" method="POST" class="card p-4 shadow-sm">
            <div class="mb-3">
                <label for="role_name" class="form-label">Role Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="role_name" name="role_name" value="<?php if (isset($roleName)) echo $roleName ?>" required>
                <input type="hidden" name="role_id" value="<?php if (isset($roleId)) echo $roleId ?>">
            </div>

            <h5 class="mb-3">Role Permissions</h5>

            <div class="table-responsive">
                <table class="table table-bordered permission-table">
                    <thead class="table-light">
                        <tr>
                            <th>Module</th>
                            <th>Create</th>
                            <th>View</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Users</td>
                            <td>
                                <input
                                    type="checkbox"
                                    name="permissions[Users][Create]"
                                    value="1"
                                    <?php if (hasPermission('Create', 'Users', $rolePerms)) echo 'checked' ?>>
                            </td>
                            <td>
                                <input
                                    type="checkbox"
                                    name="permissions[Users][Read]"
                                    value="2"
                                    <?php if (hasPermission('Read', 'Users', $rolePerms)) echo 'checked' ?>>
                            </td>
                            <td>
                                <input
                                    type="checkbox"
                                    name="permissions[Users][Update]"
                                    value="3"
                                    <?php if (hasPermission('Update', 'Users', $rolePerms)) echo 'checked' ?>>
                            </td>
                            <td>
                                <input
                                    type="checkbox"
                                    name="permissions[Users][Delete]"
                                    value="4"
                                    <?php if (hasPermission('Delete', 'Users', $rolePerms)) echo 'checked' ?>>
                            </td>
                        </tr>
                        <tr>
                            <td>Roles</td>
                            <td>
                                <input
                                    type="checkbox"
                                    name="permissions[Roles][Create]"
                                    value="5"
                                    <?php if (hasPermission('Create', 'Roles', $rolePerms)) echo 'checked' ?>>
                            </td>
                            <td>
                                <input
                                    type="checkbox"
                                    name="permissions[Roles][Read]"
                                    value="6"
                                    <?php if (hasPermission('Read', 'Roles', $rolePerms)) echo 'checked' ?>>
                            </td>
                            <td>
                                <input
                                    type="checkbox"
                                    name="permissions[Roles][Update]"
                                    value="7"
                                    <?php if (hasPermission('Update', 'Roles', $rolePerms)) echo 'checked' ?>>
                            </td>
                            <td>
                                <input
                                    type="checkbox"
                                    name="permissions[Roles][Delete]"
                                    value="8"
                                    <?php if (hasPermission('Delete', 'Roles', $rolePerms)) echo 'checked' ?>>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="text-start">
                <button type="submit" class="btn btn-primary">Edit Role</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
