<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;
include_once '../class-file/Auth.php';
auth('admin');

include_once '../class-file/Department.php';
include_once '../popup-1.php';

// Show any flash message
if ($session::get("msg1")) {
    showPopup($session::get("msg1"));
    $session::delete("msg1");
}

$department = new Department();

// Handle create new department
if (isset($_POST['create-department'])) {
    $department->department_name          = $_POST['new_department_name'];
    $department->department_short_form    = $_POST['new_department_short_form'];
    $department->department_total_student = intval($_POST['new_department_total_student']);
    $department->status                   = 1;
    $newId = $department->insert();
    if ($newId) {
        $session::set("msg1", "Created Department '{$_POST['new_department_name']}' (ID: $newId).");
    } else {
        $session::set("msg1", "Failed to create department.");
    }
    echo "<script>window.location.href='manage-departments.php';</script>";
    exit;
}

// Handle status toggle
if (isset($_POST['update-status'])) {
    $departmentId = intval($_POST['department_id']);
    $newStatus    = intval($_POST['update-status']);
    $result       = $department->updateStatusByDepartmentId($departmentId, $newStatus);
    $newStatusTxt = $newStatus ? "activated" : "deactivated";
    if ($result) {
        $session::set("msg1", "Department ID $departmentId $newStatusTxt.");
    } else {
        $session::set("msg1", "Failed to update status for Department ID $departmentId.");
    }
    echo "<script>window.location.href='manage-departments.php';</script>";
    exit;
}

// Handle full record update
if (isset($_POST['update-department'])) {
    $departmentId = intval($_POST['department_id']);
    $department->getById($departmentId);
    $department->department_name          = $_POST['department_name'];
    $department->department_short_form    = $_POST['department_short_form'];
    $department->department_total_student = intval($_POST['department_total_student']);
    $result = $department->update();
    if ($result) {
        $session::set("msg1", "Department ID $departmentId updated.");
    } else {
        $session::set("msg1", "Failed to update Department ID $departmentId.");
    }
    echo "<script>window.location.href='manage-departments.php';</script>";
    exit;
}

// Fetch fresh list
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
    <!-- bootstrap‑table CSS -->
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
                <!-- Toggle for small screens -->
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

                    <!-- Left‑aligned search toolbar with hint -->
                    <div id="toolbar" class="d-flex align-items-center mb-2 mx-3">
                        <small class="text-muted">Search by anything</small>
                    </div>

                    <!-- Collapsible “Create Another Dept.” form -->
                    <div class="accordion mb-3 mx-3" id="createDeptAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingCreate">
                                <button
                                    class="accordion-button collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapseCreate"
                                    aria-expanded="false"
                                    aria-controls="collapseCreate">
                                    Create another dept.
                                </button>
                            </h2>
                            <div
                                id="collapseCreate"
                                class="accordion-collapse collapse"
                                aria-labelledby="headingCreate"
                                data-bs-parent="#createDeptAccordion">
                                <div class="accordion-body">
                                    <form method="POST" action="" enctype="multipart/form-data">
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <input
                                                    type="text"
                                                    name="new_department_name"
                                                    class="form-control"
                                                    placeholder="Department Name"
                                                    required>
                                            </div>
                                            <div class="col-md-4">
                                                <input
                                                    type="text"
                                                    name="new_department_short_form"
                                                    class="form-control"
                                                    placeholder="Department Short Form"
                                                    required>
                                            </div>
                                            <div class="col-md-4">
                                                <input
                                                    type="number"
                                                    name="new_department_total_student"
                                                    class="form-control text-end"
                                                    placeholder="Total Students"
                                                    required>
                                            </div>
                                        </div>
                                        <button
                                            type="submit"
                                            name="create-department"
                                            class="btn btn-success btn-lg fw-semibold rounded-pill shadow-sm mt-3 px-4">
                                            Create Department
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Departments table -->
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
                                    <th data-field="id"
                                        data-sortable="true"
                                        data-sorter="numericSorter">ID</th>

                                    <th data-field="name" data-sortable="true">Name</th>
                                    <th data-field="short" data-sortable="true">Short&nbsp;Form</th>

                                    <th data-field="total"
                                        data-sortable="true"
                                        data-sorter="numericSorter">Total&nbsp;Students</th>

                                    <th data-field="status" data-sortable="true">Status</th>
                                    <th data-field="action" data-sortable="false">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($departmentList as $dept) {
                                    $id   = intval($dept['department_id']);
                                    $form = "deptForm{$id}";
                                ?>
                                    <tr>
                                        <!-- ID + hidden form -->
                                        <td>
                                            <?php echo $id; ?>
                                            <form id="<?php echo $form; ?>" method="POST" action="" class="d-none">
                                                <input type="hidden" name="department_id" value="<?php echo $id; ?>">
                                            </form>
                                        </td>

                                        <!-- Dept Name (sortable; add hidden span) -->
                                        <td>
                                            <span class="d-none"><?php echo htmlspecialchars($dept['department_name']); ?></span>
                                            <input
                                                form="<?php echo $form; ?>"
                                                type="text"
                                                name="department_name"
                                                class="form-control"
                                                value="<?php echo htmlspecialchars($dept['department_name']); ?>">
                                        </td>

                                        <!-- Short Form -->
                                        <td>
                                            <span class="d-none"><?php echo htmlspecialchars($dept['department_short_form']); ?></span>
                                            <input
                                                form="<?php echo $form; ?>"
                                                type="text"
                                                name="department_short_form"
                                                class="form-control"
                                                value="<?php echo htmlspecialchars($dept['department_short_form']); ?>">
                                        </td>

                                        <!-- Total Students (numeric sort works the same) -->
                                        <td>
                                            <span class="d-none"><?php echo (int)$dept['department_total_student']; ?></span>
                                            <input
                                                form="<?php echo $form; ?>"
                                                type="number"
                                                name="department_total_student"
                                                class="form-control text-end"
                                                value="<?php echo (int)$dept['department_total_student']; ?>">
                                        </td>

                                        <!-- Status badge -->
                                        <td>
                                            <span class="d-none"><?php echo $dept['status']; ?></span> <!-- 0 / 1 for sort -->
                                            <span class="badge <?php echo $dept['status'] ? 'bg-success' : 'bg-secondary'; ?>">
                                                <?php echo $dept['status'] ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>


                                        <!-- Action buttons -->
                                        <td>
                                            <button
                                                form="<?php echo $form; ?>"
                                                type="submit"
                                                name="update-status"
                                                value="<?php echo ($dept['status'] == 1 ? 0 : 1); ?>"
                                                class="btn <?php echo ($dept['status'] == 1 ? 'btn-warning' : 'btn-success'); ?> btn-sm">
                                                <?php echo ($dept['status'] == 1 ? 'Deactivate' : 'Activate'); ?>
                                            </button>
                                            <button
                                                form="<?php echo $form; ?>"
                                                type="submit"
                                                name="update-department"
                                                class="btn btn-primary btn-sm ms-1">
                                                Update
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </main>
        </div>
    </div>


    
    <!-- jQuery, Bootstrap, bootstrap‑table JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.21.2/dist/bootstrap-table.min.js"></script>

    <script>
        /** Remove HTML tags and convert to float (returns 0 if NaN) */
        function toNumber(cellHtml) {
            const cleaned = String(cellHtml).replace(/<[^>]*>/g, '').trim(); // strip tags
            const n = parseFloat(cleaned);
            return isNaN(n) ? 0 : n;
        }

        /** Custom numeric sorter for bootstrap‑table */
        function numericSorter(a, b) {
            return toNumber(a) - toNumber(b);
        }

        /* initialise table with the sorter available */
        $('#deptTable').bootstrapTable({
            sortStable: true // keep relative order for equal values
            // other options can go here …
        });
    </script>



</body>

</html>