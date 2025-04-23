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

// Get permissions for current role
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

// Check if current user has permission to view roles
if (!hasPermission('Read', 'Roles', $permissions)) {
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
	<title>View Roles</title>
</head>

<body>
	<div class="container py-5">
		<h2 class="mb-4">Detail Role</h2>

		<div class="card p-4 shadow-sm">
			<div class="mb-3">
				<h4>Role Name</h4>
				<p class="ms-4"><?= $roleName ?></p>
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
									disabled
									<?php if (hasPermission('Create', 'Users', $rolePerms)) echo 'checked' ?>>
							</td>
							<td>
								<input
									type="checkbox"
									disabled
									<?php if (hasPermission('Read', 'Users', $rolePerms)) echo 'checked' ?>>
							</td>
							<td>
								<input
									type="checkbox"
									disabled
									<?php if (hasPermission('Update', 'Users', $rolePerms)) echo 'checked' ?>>
							</td>
							<td>
								<input
									type="checkbox"
									disabled
									<?php if (hasPermission('Delete', 'Users', $rolePerms)) echo 'checked' ?>>
							</td>
						</tr>
						<tr>
							<td>Roles</td>
							<td>
								<input
									type="checkbox"
									disabled
									<?php if (hasPermission('Create', 'Roles', $rolePerms)) echo 'checked' ?>>
							</td>
							<td>
								<input
									type="checkbox"
									disabled
									<?php if (hasPermission('Read', 'Roles', $rolePerms)) echo 'checked' ?>>
							</td>
							<td>
								<input
									type="checkbox"
									disabled
									<?php if (hasPermission('Update', 'Roles', $rolePerms)) echo 'checked' ?>>
							</td>
							<td>
								<input
									type="checkbox"
									disabled
									<?php if (hasPermission('Delete', 'Roles', $rolePerms)) echo 'checked' ?>>

							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
