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
// Get permissions by role Id
$permissions = $rolePermModel->getPerByRoleId($current_user['role_id']);

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

// Check if current user has permission to view roles
if (!hasPermission('Read', 'Roles', $permissions)) {
    header('location: ../dashboard.php');
    exit();
}

// Get All roles
$roleModel = new RoleModel();
$roles = $roleModel->getAllRoles();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Roles List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h4 class="mb-4">Roles List</h4>

        <?php if (hasPermission('Create', 'Roles', $permissions)) : ?>
            <div class="mb-3 text-end">
                <a href="create.php" class="btn btn-primary">Create Role</a>
            </div>
        <?php endif; ?>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Role Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($roles as $role): ?>
                    <tr>
                        <td><?= $role[0] ?></td>
                        <td><?= htmlspecialchars($role[1]) ?></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Action
                                </button>
                                <ul class="dropdown-menu">
                                    <?php if (hasPermission('Update', 'Roles', $permissions)) : ?>
                                        <li><a class="dropdown-item" href="edit.php?role_name=<?php echo $role[1]; ?>&&role_id=<?php echo $role[0]; ?>">Edit</a></li>
                                    <?php endif; ?>
                                    <?php if (hasPermission('Read', 'Roles', $permissions)) : ?>
                                        <li><a class="dropdown-item" href="view.php?role_name=<?php echo $role[1]; ?>&&role_id=<?php echo $role[0]; ?>">View Detail</a></li>
                                    <?php endif; ?>
                                    <?php if (hasPermission('Delete', 'Roles', $permissions)) : ?>
                                        <li><a class="dropdown-item text-danger" href="../../actions/deleteRole.php?role_id=<?php echo $role[0]; ?>" onclick="return confirm('Are you sure?')">Delete</a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
