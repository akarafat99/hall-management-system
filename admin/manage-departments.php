<?php
include_once '../class-file/Department.php';

$department = new Department();
$departmentList = $department->getDepartments();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Departments</title>

    <!-- Bootstrap 5 CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css"
        rel="stylesheet" />
    <!-- bootstrap‑table CSS (for sorting & search) -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.21.2/dist/bootstrap-table.min.css"
        rel="stylesheet" />
    <!-- Sidebar styles -->
    <link href="../css2/sidebar-admin.css" rel="stylesheet" />
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include 'sidebar-admin.php'; ?>

            <!-- Main Content -->
            <main id="mainContent" class="col">
                <button
                    class="btn btn-dark d-lg-none mt-3 mb-3"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#sidebarMenu"
                    aria-controls="sidebarMenu"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                    ☰ Menu
                </button>

                <div class="p-3" style="height:100vh; overflow-y:auto">
                    <h1 class="mb-4">Manage Departments</h1>

                    <!-- 1) toolbar container for left‐aligned search -->
                    <div id="toolbar" class="mb-2 mx-1"></div>

                    <div class="table-responsive">
                        <table
                            id="deptTable"
                            class="table table-striped table-hover align-middle"
                            data-toggle="table"
                            data-toolbar="#toolbar"
                            data-search="true"
                            data-search-align="left"
                            data-pagination="false">
                            <thead class="table-light">
                                <tr>
                                    <th data-field="id" data-sortable="true">ID</th>
                                    <th data-field="name" data-sortable="true">Name</th>
                                    <th data-field="short" data-sortable="true">Short Form</th>
                                    <th data-field="total" data-sortable="true">Total Students</th>
                                    <th data-field="status" data-sortable="true">Status</th>
                                    <th data-field="action" data-sortable="false">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($departmentList as $dept) { ?>
                                    <tr>
                                        <form method="POST" action="update-department.php" class="update-department-form">
                                            <input
                                                type="hidden"
                                                name="department_id"
                                                value="<?php echo intval($dept['department_id']); ?>">

                                            <!-- ID -->
                                            <td><?php echo intval($dept['department_id']); ?></td>

                                            <!-- Dept Name (editable) -->
                                            <td>
                                                <input
                                                    type="text"
                                                    name="department_name"
                                                    class="form-control"
                                                    value="<?php echo htmlspecialchars($dept['department_name']); ?>">
                                            </td>

                                            <!-- Short Form (editable) -->
                                            <td>
                                                <input
                                                    type="text"
                                                    name="department_short_form"
                                                    class="form-control"
                                                    value="<?php echo htmlspecialchars($dept['department_short_form']); ?>">
                                            </td>

                                            <!-- Total Students (editable) -->
                                            <td>
                                                <input
                                                    type="number"
                                                    name="department_total_student"
                                                    class="form-control text-end"
                                                    value="<?php echo intval($dept['department_total_student']); ?>">
                                            </td>

                                            <!-- Status badge -->
                                            <td>
                                                <span class="badge <?php echo ($dept['status'] == 1 ? 'bg-success' : 'bg-secondary'); ?>">
                                                    <?php echo ($dept['status'] == 1 ? 'Active' : 'Inactive'); ?>
                                                </span>
                                            </td>

                                            <!-- Action buttons -->
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button
                                                        type="submit"
                                                        name="update-status"
                                                        value="<?php echo ($dept['status'] == 1 ? 0 : 1); ?>"
                                                        class="btn <?php echo ($dept['status'] == 1 ? 'btn-warning' : 'btn-success'); ?>"
                                                        data-id="<?php echo intval($dept['department_id']); ?>">
                                                        <?php echo ($dept['status'] == 1 ? 'Deactivate' : 'Activate'); ?>
                                                    </button>
                                                    <button
                                                        type="submit"
                                                        name="update-department"
                                                        class="btn btn-primary"
                                                        data-id="<?php echo intval($dept['department_id']); ?>">
                                                        <?php echo 'Update'; ?>
                                                    </button>
                                                </div>
                                            </td>
                                        </form>
                                    </tr>
                                <?php } ?>
                            </tbody>

                        </table>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <!-- jQuery (required by bootstrap‑table) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5 JS bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- bootstrap‑table JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.21.2/dist/bootstrap-table.min.js"></script>
    <!-- Initialize -->
    <script>
        $(function() {
            $('#deptTable').bootstrapTable();
        });
    </script>
</body>

</html>