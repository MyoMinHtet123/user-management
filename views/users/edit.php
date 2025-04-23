<?php
require_once '../../models/UserModel.php';
require_once "../../models/RolesModel.php";

// auth check
session_start();
if (!isset($_SESSION['user'])) {
    header('location: ../../index.php');
    exit;
}

if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    $userModel = new UserModel();
    $user = $userModel->getUserById($userId);;
}

$roleModel = new RoleModel();
$roles = $roleModel->getAllRoles();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container mt-5">
        <h4 class="mb-4">Edit User</h4>
        <?php if (isset($userError)) : ?>
            <div class="alert alert-danger">
                <?= $userError ?>
            </div>
        <?php endif; ?>
        <form action="../../actions/editUser.php" method="post" class="card p-4 shadow-sm" enctype="multipart/form-data">
            <!-- Hidden user_id -->
            <input type="hidden" name="user_id" value="<?php echo $userId; ?> ">

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" value="<?php echo $user['name'] ?>" class="form-control" id="name" name="name" required />
            </div>

            <!-- Username -->
            <div class="mb-3">
                <label for="username" class="form-label">User Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username'] ?>" required />
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="email" name="email" value="<?php echo $user['email'] ?>" required />
            </div>

            <!-- Roles Dropdown -->
            <div class="mb-4">
                <label for="role" class="form-label">Roles <span class="text-danger">*</span></label>
                <select class="form-select" id="role" name="role" required>
                    <option selected disabled>Select a Roles...</option>
                    <?php foreach ($roles as $role) : ?>
                        <option value="<?= $role[0] ?>"><?= $role[1] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <!-- Submit -->
                <button type="submit" class="btn btn-primary">Edit User</button>
                <a href="../dashboard.php" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
