<?php
require_once '../models/UserModel.php';
require_once '../models/RolePermModel.php';

// Session_start();
session_start();

$current_user = $_SESSION['user'];
if (! $current_user) {
    header('location: ../index.php');
    exit();
}

$userModel     = new UserModel();
$recentUsers   = $userModel->getRecentUsers();
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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: 100vh;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            margin: 2px 0;
            border-radius: 5px;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar .nav-link.active {
            background: #3498db;
            color: white;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-2 d-md-block sidebar bg-dark">
                <div class="position-sticky pt-3">
                    <h4 class="text-white px-3 mb-4">
                        <i class="bi bi-shield-lock"></i> Admin Panel
                    </h4>

                    <ul class="nav flex-column px-2">
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard.php">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>

                        <!-- Users Menu -->
                        <?php if (hasPermission('Create', 'Users', $permissions)): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="./users/create.php">
                                    <i class="bi bi-people"></i> Users
                                    <span class="badge bg-success float-end">
                                        <i class="bi bi-plus"></i>
                                    </span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <!-- Roles Menu -->
                        <?php if (hasPermission('Create', 'Roles', $permissions)): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="./roles/create.php">
                                    <i class="bi bi-shield-check"></i> Roles
                                    <span class="badge bg-success float-end">
                                        <i class="bi bi-plus"></i>
                                    </span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item mt-4">
                            <a class="nav-link" href="profile.php">
                                <i class="bi bi-person-circle"></i> My Profile
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="../actions/logout.php">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </h2>
                </div>

                <!-- Welcome Alert -->
                <div class="alert alert-primary">
                    <i class="bi bi-info-circle"></i> Welcome
                    <strong><?php echo htmlspecialchars($current_user['name']); ?></strong>!
                </div>

                <!-- Quick Actions -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-people"></i> User Management
                                </h5>
                                <p class="card-text">Manage all system users</p>
                                <?php if (hasPermission('Read', 'Users', $permissions)): ?>
                                    <a href="./users/userList.php" class="btn btn-primary">
                                        <i class="bi bi-list"></i> View Users
                                    </a>
                                <?php endif; ?>
                                <?php if (hasPermission('Create', 'Users', $permissions)): ?>
                                    <a href="./users/create.php" class="btn btn-success">
                                        <i class="bi bi-plus"></i> Add New
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-shield-check"></i> Role Management
                                </h5>
                                <p class="card-text">Manage user roles and permissions</p>
                                <?php if (hasPermission('Read', 'Roles', $permissions)): ?>
                                    <a href="./roles/rolesList.php" class="btn btn-primary">
                                        <i class="bi bi-list"></i> View Roles
                                    </a>
                                <?php endif; ?>
                                <?php if (hasPermission('Create', 'Roles', $permissions)): ?>
                                    <a href="./roles/create.php" class="btn btn-success">
                                        <i class="bi bi-plus"></i> Add New
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Users Table -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bi bi-clock-history"></i> Recent Users
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($recentUsers as $user):
                                    ?>
                                        <tr>
                                            <td><?php echo $user['id']; ?></td>
                                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                                            <td><?php echo $user['role_name']; ?></td>
                                            <td>
                                                <?php if (hasPermission('Update', 'Users', $permissions)): ?>
                                                    <a href="users/edit.php?user_id=<?php echo $user['id']; ?>"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (hasPermission('Delete', 'Users', $permissions)): ?>
                                                    <a href="../actions/deleteUser.php?user_id=<?php echo $user['id']; ?>"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Are you sure?')">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
