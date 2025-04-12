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
							<th>View</th>
							<th>Create</th>
							<th>Update</th>
							<th>Delete</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$permissions = ['view', 'create', 'update', 'delete'];

						foreach ($features as $feature): ?>
							<tr>
								<td><strong><?= $feature['name'] ?></strong></td>
								<?php foreach ($permissions as $perm): ?>
									<td>
										<input type="checkbox"
											name="permissions[<?= $feature['name'] ?>][]"
											value="<?= $perm ?>">
									</td>
									</td>
								<?php endforeach; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<div class="mt-4 d-flex justify-content-between">
				<button type="submit" class="btn btn-primary">Create Role</button>
				<a href="../dashboard.php" class="btn btn-secondary">Cancel</a>
			</div>
		</form>
	</div>

</body>

</html>
