<?php
include_once "class-file/SessionManager.php";
$session = SessionStatic::class;

include_once "popup-1.php";
if ($session::get("msg1")) {
  showPopup($session::get("msg1"));
  $session::delete("msg1");
}

include_once "class-file/NoticeManager.php";
$noticeManager = new NoticeManager();

$allNotices = $noticeManager->getByStatus();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>MM Hall</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts: Roboto for Material Design look -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>
  <!-- Parent container with flex and min-vh-100 -->
  <div class="d-flex flex-column min-vh-100">
    <!-- Main content area -->
    <div class="flex-grow-1">
      <!-- Navbar Section Start -->
      <?php
      if ($session::get('user') != null) {
        include_once 'student/navbar-student-1.php';
      } else {
        include_once 'student/navbar-student-2.php';
      }
      ?>
      <!-- Navbar Section End -->

      <!-- Notices List (static HTML for now) -->
      <div class="container py-4">
    <h2 class="mb-4">Notices</h2>

    <!-- SEARCH INPUT -->
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <input
          type="number"
          id="searchId"
          class="form-control"
          placeholder="Search by Notice ID…"
          min="1"
        >
      </div>
    </div>

    <!-- NOTICES CARDS -->
    <div id="noticesContainer">
      <?php if (!empty($allNotices) && is_array($allNotices)): ?>
        <?php foreach ($allNotices as $notice): ?>
          <div
            class="card mb-3 shadow-sm notice-card"
            data-notice-id="<?php echo  htmlspecialchars($notice['notice_id']) ?>"
          >
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="card-title mb-0"><?php echo '#'. $notice['notice_id'] . " - ". $notice['title'] ?></h5>
                <span class="badge <?php echo  $notice['status']==1 ? 'bg-primary' : 'bg-secondary' ?>">
                  <?php echo  date('M d, Y', strtotime($notice['created'])) ?>
                </span>
              </div>
              <p class="card-text"><?php echo nl2br(htmlspecialchars($notice['description'])) ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="alert alert-info">No notices found.</div>
      <?php endif; ?>
    </div>

    <!-- PAGINATION CONTROLS -->
    <nav aria-label="Page navigation">
      <ul class="pagination justify-content-center" id="pagination"></ul>
    </nav>
  </div>


      <!-- Notices List End -->
    </div>

    <!-- Footer Section -->
    <footer class="bg-dark text-white mt-auto">
      <div class="container py-4 text-center">
        <p class="mb-0">&copy; 2025 MM Hall. All rights reserved.</p>
      </div>
    </footer>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const searchInput = document.getElementById('searchId');
      const cards = Array.from(document.querySelectorAll('.notice-card'));
      const pagination = document.getElementById('pagination');
      const itemsPerPage = 5;
      let filteredCards = cards.slice();
      let currentPage = 1;

      function render() {
        const term = searchInput.value.trim();
        // Filter by Notice ID
        filteredCards = cards.filter(card => {
          return !term || card.dataset.noticeId.includes(term);
        });

        const totalPages = Math.max(1, Math.ceil(filteredCards.length / itemsPerPage));
        if (currentPage > totalPages) currentPage = totalPages;

        // Hide all, then show current page
        cards.forEach(c => c.style.display = 'none');
        const start = (currentPage - 1) * itemsPerPage;
        filteredCards.slice(start, start + itemsPerPage)
          .forEach(c => c.style.display = '');

        renderPagination(totalPages);
      }

      function renderPagination(totalPages) {
        pagination.innerHTML = '';
        // Prev
        const prev = document.createElement('li');
        prev.className = 'page-item' + (currentPage === 1 ? ' disabled' : '');
        prev.innerHTML = '<a class="page-link" href="#" aria-label="Previous">&laquo;</a>';
        prev.onclick = e => {
          e.preventDefault();
          if (currentPage > 1) {
            currentPage--;
            render();
          }
        };
        pagination.appendChild(prev);

        // Pages
        for (let i = 1; i <= totalPages; i++) {
          const li = document.createElement('li');
          li.className = 'page-item' + (i === currentPage ? ' active' : '');
          li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
          li.onclick = e => {
            e.preventDefault();
            currentPage = i;
            render();
          };
          pagination.appendChild(li);
        }

        // Next
        const next = document.createElement('li');
        next.className = 'page-item' + (currentPage === totalPages ? ' disabled' : '');
        next.innerHTML = '<a class="page-link" href="#" aria-label="Next">&raquo;</a>';
        next.onclick = e => {
          e.preventDefault();
          if (currentPage < totalPages) {
            currentPage++;
            render();
          }
        };
        pagination.appendChild(next);
      }

      // Listeners
      searchInput.addEventListener('input', () => {
        currentPage = 1;
        render();
      });

      // Initial render
      render();
    });
  </script>

</body>

</html>