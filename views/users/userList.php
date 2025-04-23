<?php
require_once '../../models/UserModel.php';
require_once '../../models/RolePermModel.php';

// Session_start();
session_start();

$current_user = $_SESSION['user'];
if (! $current_user) {
    header('location: ../../index.php');
    exit();
}

// Get All users
$userModel = new UserModel();
$users = $userModel->getAllUsers();

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

// Check if current user has permission to view users
if (!hasPermission('Read', 'Users', $permissions)) {
    header('location: ../dashboard.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h4 class="mb-4">User List</h4>

        <?php if (hasPermission('Create', 'Users', $permissions)) : ?>
            <div class="mb-3 text-end">
                <a href="create.php" class="btn btn-primary">Create User</a>
            </div>
        <?php endif; ?>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['role_name']) ?></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Action
                                </button>
                                <ul class="dropdown-menu">
                                    <?php if (hasPermission('Update', 'Users', $permissions)) : ?>
                                        <li><a class="dropdown-item" href="edit.php?user_id=<?php echo $user['id']; ?>">Edit</a></li>
                                    <?php endif; ?>
                                    <?php if (hasPermission('Update', 'Users', $permissions)) : ?>
                                        <li><a class="dropdown-item text-danger" href="../../actions/deleteUser.php?user_id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a></li>
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
