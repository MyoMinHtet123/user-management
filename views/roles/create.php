<?php
require_once '../../models/FeaturesModel.php';

// auth check
session_start();
if (!isset($_SESSION['user'])) {
	header('location: ../../index.php');
	exit;
}

// Initialize new feature model
$featureModel = new FeaturesModel();
$features = $featureModel->getAllFeatures();
$permissions = ['Create', 'Read', 'Update', 'Delete'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Create Role</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
		.permission-table th,
		.permission-table td {
			vertical-align: middle;
			text-align: center;
		}
	</style>
</head>

<body class="bg-light">
	<div class="container py-5">
		<h2 class="mb-4">Create Role</h2>
		<?php if (isset($_SESSION['error_message'])) : ?>
			<div class="alert alert-warning alert-dismissible fade show" role="alert">
				<?= $_SESSION['error_message'] ?>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		<?php endif; ?>

		<form action="../../actions/storeRoles.php" method="POST" class="card p-4 shadow-sm">
			<div class="mb-3">
				<label for="role_name" class="form-label">Role Name <span class="text-danger">*</span></label>
				<input type="text" class="form-control" id="role_name" name="role_name" required>
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
							<td><strong>Users</strong></td>
							<?php foreach ($permissions as $perm): ?>
								<td>
									<input type="checkbox"
										name="permissions[Users][]"
										value="<?= $perm ?>">
								</td>
								</td>
							<?php endforeach; ?>
						</tr>
						<tr>
							<td><strong>Roles</strong></td>
							<?php foreach ($permissions as $perm): ?>
								<td>
									<input type="checkbox"
										name="permissions[Roles][]"
										value="<?= $perm ?>">
								</td>
								</td>
							<?php endforeach; ?>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="mt-4 d-flex justify-content-between">
				<button type="submit" class="btn btn-primary">Create Role</button>
				<a href="../dashboard.php" class="btn btn-secondary">Cancel</a>
			</div>
		</form>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
