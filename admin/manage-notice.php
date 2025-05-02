<?php
include_once "../class-file/SessionManager.php";
$session = SessionStatic::class;
include_once "../class-file/Auth.php";
auth('admin');

include_once "../popup-1.php";
if ($session::get("msg1")) {
    showPopup($session::get("msg1"));
    $session::delete("msg1");
}

include_once "../class-file/NoticeManager.php";
$noticeManager = new NoticeManager();

if (isset($_POST['submit'])) {
    $noticeManager->title = $_POST['title'];
    $noticeManager->description = $_POST['description'];
    $notice_id = $noticeManager->insert();
    if ($notice_id) {
        $session::set("msg1", "Notice created successfully. Notice ID: $notice_id");
    } else {
        $session::set("msg1", "Failed to create notice.");
    }
    echo "<script>window.location.href='manage-notice.php';</script>";
    exit;
}


if (isset($_POST['update'])) {
    $noticeManager->notice_id = $_POST['notice_id'];
    $noticeManager->title = $_POST['title'];
    $noticeManager->description = $_POST['description'];
    if ($noticeManager->update()) {
        $session::set("msg1", "Notice updated successfully.");
    } else {
        $session::set("msg1", "Failed to update notice.");
    }
    echo "<script>window.location.href='manage-notice.php';</script>";
    exit;
}

if (isset($_POST['delete']) || isset($_POST['restore'])) {
    $noticeManager->notice_id = $_POST['notice_id'];
    $noticeManager->loadByNoticeId();
    $noticeManager->status = isset($_POST['delete']) ? -1 : 1; // -1 for delete, 1 for restore
    if ($noticeManager->update()) {
        $session::set("msg1", "Notice deleted successfully.");
    } else {
        $session::set("msg1", "Failed to delete notice.");
    }
    echo "<script>window.location.href='manage-notice.php';</script>";
    exit;
}



$allNotices = $noticeManager->getByStatus();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MM Hall</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css"
        rel="stylesheet" />
    <!-- for sidebar -->
    <link href="../css2/sidebar-admin.css" rel="stylesheet" />

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Menu -->
            <?php include 'sidebar-admin.php'; ?>

            <!-- Main Content Area -->
            <main id="mainContent" class="col">
                <!-- Toggle button for sidebar on small screens -->
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

                <div class="p-3">
                    <h1>Welcome to Notice Management</h1>
                </div>

                <!-- New Notice Form -->
                <div class="accordion" id="noticeAccordion">
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingNotice">
      <button
        class="accordion-button collapsed"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#collapseNotice"
        aria-expanded="false"
        aria-controls="collapseNotice">
        Create New Notice
      </button>
    </h2>
    <div
      id="collapseNotice"
      class="accordion-collapse collapse"
      aria-labelledby="headingNotice"
      data-bs-parent="#noticeAccordion">
      <div class="accordion-body p-3">
        <form action="" method="post">
          <input type="hidden" name="action" value="create">
          <div class="mb-3">
            <label for="newTitle" class="form-label">Notice Title</label>
            <input
              type="text"
              id="newTitle"
              name="title"
              class="form-control"
              placeholder="Enter title"
              required>
          </div>
          <div class="mb-3">
            <label for="newDescription" class="form-label">Notice Description</label>
            <textarea
              id="newDescription"
              name="description"
              class="form-control"
              rows="3"
              placeholder="Enter description"
              required></textarea>
          </div>
          <button
            type="submit"
            name="submit"
            class="btn btn-success">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>


                <hr>

                <!-- 1) Search inputs -->
                <div class="row g-3 p-3">
                    <div class="col-md-4">
                        <input
                            type="text"
                            id="searchId"
                            class="form-control"
                            placeholder="Search by ID…">
                    </div>
                    <div class="col-md-4">
                        <input
                            type="text"
                            id="searchAny"
                            class="form-control"
                            placeholder="Search title or description…">
                    </div>
                </div>

                <!-- 2) Notices table with an ID -->
                <div class="p-3">
                    <h2>All Notices</h2>
                    <table id="noticesTable" class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Status</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Created</th>
                                <th>Modified</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allNotices as $notice): ?>
                                <form action="" method="post">
                                    <tr>
                                        <td><?php echo  htmlspecialchars($notice['notice_id']) ?></td>
                                        <td>
                                            <?php if ($notice['status'] == 1): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Deleted</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <input
                                                type="text"
                                                name="title"
                                                value="<?php echo  htmlspecialchars($notice['title']) ?>"
                                                class="form-control form-control-sm">
                                        </td>
                                        <td>
                                            <textarea
                                                name="description"
                                                class="form-control form-control-sm"
                                                rows="1"><?php echo  htmlspecialchars($notice['description']) ?></textarea>
                                        </td>
                                        <td><?php echo  htmlspecialchars($notice['created']) ?></td>
                                        <td><?php echo  htmlspecialchars($notice['modified']) ?></td>
                                        <td class="text-nowrap">
                                            <input type="hidden" name="notice_id" value="<?php echo  htmlspecialchars($notice['notice_id']) ?>">
                                            <button type="submit" name="update" class="btn btn-sm btn-outline-primary me-1">
                                                <i class="fas fa-edit"></i> Update
                                            </button>
                                            <?php if ($notice['status'] == 1): ?>
                                                <button type="submit" name="delete" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </button>
                                            <?php else: ?>
                                                <button type="submit" name="restore" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-undo"></i> Restore
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </form>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </main>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchId = document.getElementById('searchId');
            const searchAny = document.getElementById('searchAny');
            const rows = document.querySelectorAll('#noticesTable tbody tr');

            function filterRows() {
                const idFilter = searchId.value.trim();
                const termFilter = searchAny.value.trim().toLowerCase();

                rows.forEach(row => {
                    const id = row.cells[0].textContent.trim();
                    const title = row.querySelector('input[name="title"]').value.trim().toLowerCase();
                    const desc = row.querySelector('textarea[name="description"]').value.trim().toLowerCase();

                    const matchId = !idFilter || id.includes(idFilter);
                    const matchTerm = !termFilter || title.includes(termFilter) || desc.includes(termFilter);

                    row.style.display = (matchId && matchTerm) ? '' : 'none';
                });
            }

            searchId.addEventListener('input', filterRows);
            searchAny.addEventListener('input', filterRows);
        });
    </script>

</body>

</html>