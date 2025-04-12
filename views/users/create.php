<?php
require_once "../../models/RolesModel.php";

// auth check
session_start();
if (!isset($_SESSION['user'])) {
	header('location: ../../index.php');
	exit;
}

$roleModel = new RoleModel();
$roles = $roleModel->getAllRoles();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container mt-5">
        <h4 class="mb-4">Roles and Permissions</h4>
        <?php if (isset($userError)) : ?>
            <div class="alert alert-danger">
                <?= $userError ?>
            </div>
        <?php endif; ?>
        <form action="../../actions/createUser.php" method="post" enctype="multipart/form-data">
            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required />
            </div>

            <!-- Username -->
            <div class="mb-3">
                <label for="username" class="form-label">User Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required />
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Email" required />
            </div>

            <!-- Password & Confirm Password -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required />
                </div>
                <div class="col-md-6 mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required />
                </div>
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

            <a href="../dashboard.php" class="btn btn-secondary">Back</a>
            <!-- Submit -->
            <button type="submit" class="btn btn-primary">Create User</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
