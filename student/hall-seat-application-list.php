<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;

include_once '../class-file/User.php';
include_once '../class-file/UserDetails.php';
include_once '../class-file/HallSeatAllocationEvent.php';
include_once '../class-file/HallSeatApplication.php';
include_once '../class-file/Auth.php';
auth('user');

$sUser = $session::getObject('userObj');
$user = new User();
$session::copyProperties($sUser, $user);
$user->load();
$userDetails = new UserDetails();
$userDetails->getUsers($user->user_id, null, 1);

$hallSeatAllocationEvent = new HallSeatAllocationEvent();
$hallSeatApplication = new HallSeatApplication();

// Fetch all applications for the user.
$allApplications = $hallSeatApplication->getApplicationsByUserIdEventStatus($user->user_id, null, null, 'created', 'DESC');

// Mapping status codes to text.
$statusMap = [
    1 => 'Applied, Waiting for Viva',
    -2 => 'Viva Completed. Wait for Result',
    2 => 'Pass Viva and Verified',
    -3 => 'Viva Completed. Wait for Result',
    3 => 'Failed Viva',
    -4 => 'Absent in Viva. Marked as Failed',
    4 => 'Passed Viva But Not Allotted For Seat',
    5 => 'Seat Allotted',
    6 => 'Seat Confirmed',
    7 => 'Unconfirmed Seat'
];
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hall Seat Application List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Justify accordion body text */
        .accordion-body {
            text-align: justify;
        }
    </style>
</head>

<body>
    <!-- Parent container with flex and min-vh-100 -->
    <div class="d-flex flex-column min-vh-100">
        <!-- Main content including navbar -->
        <div class="flex-grow-1">
            <?php
            if ($session::get('user') !== null) {
                include_once 'navbar-student-1.php';
            } else {
                include_once 'navbar-student-2.php';
            }
            ?>
            <div class="container mt-4 mb-4">
                <h2 class="text-center">Hall Seat Application List</h2>
                <!-- Search Form -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4 offset-md-4">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search by Application ID">
                    </div>
                </div>
                <!-- Accordion Container -->
                <div class="accordion" id="accordionExample">
                    <?php
                    if ($allApplications && count($allApplications) > 0) {
                        foreach ($allApplications as $application) {
                            // Set the properties for the current application.
                            $hallSeatApplication->setProperties($application);
                            // Optionally load event details if needed.
                            $hallSeatAllocationEvent->getByEventAndStatus($hallSeatApplication->event_id);

                            // Determine status text.
                            $statusValue = intval($hallSeatApplication->status);
                            $statusText = isset($statusMap[$statusValue]) ? $statusMap[$statusValue] : 'Unknown';

                            // Generate unique IDs for accordion collapse.
                            $accordionId = "accordionItem" . $hallSeatApplication->application_id;
                    ?>
                            <div class="accordion-item app-item" data-app-id="<?php echo $hallSeatApplication->application_id; ?>">
                                <h2 class="accordion-header" id="heading<?php echo $hallSeatApplication->application_id; ?>">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $accordionId; ?>" aria-expanded="false" aria-controls="<?php echo $accordionId; ?>">
                                        Application ID: <?php echo $hallSeatApplication->application_id; ?> | Status: <?php echo $statusText; ?>
                                    </button>
                                </h2>
                                <div id="<?php echo $accordionId; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $hallSeatApplication->application_id; ?>" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <p><strong>Event ID:</strong> <?php echo $hallSeatApplication->event_id ?: 'Not Available'; ?></p>
                                        <p><strong>User ID:</strong> <?php echo $hallSeatApplication->user_id ?: 'Not Available'; ?></p>
                                        <p><strong>User Details ID:</strong> <?php echo $hallSeatApplication->user_details_id ?: 'Not Available'; ?></p>
                                        <p>
                                        <form action="view-profile.php" method="post" enctype="multipart/form-data" target="_blank">
                                            <input type="hidden" name="viewEditRequest" value="<?php echo $hallSeatApplication->user_details_id; ?>">
                                            <button type="submit" class="btn btn-outline-info">View Submitted Profile</button>
                                        </form>
                                        </p>
                                        <p><strong>Viva Date:</strong> <?php echo $hallSeatApplication->viva_date ? $hallSeatApplication->viva_date : 'Not Assigned'; ?></p>
                                        <p><strong>Allotted Seat ID:</strong> <?php echo $hallSeatApplication->allotted_seat_id ?: 'Not Allotted'; ?></p>
                                        <p><strong>Seat Confirm Date:</strong> <?php echo $hallSeatApplication->seat_confirm_date ? $hallSeatApplication->seat_confirm_date : 'Not Confirmed'; ?></p>
                                        <p><strong>Created:</strong> <?php echo $hallSeatApplication->created ?: 'Unknown'; ?></p>
                                        <p><strong>Modified:</strong> <?php echo $hallSeatApplication->modified ?: 'Unknown'; ?></p>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo '<div class="alert alert-info text-center">No applications found.</div>';
                    }
                    ?>
                </div>
                <!-- Pagination Controls -->
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center" id="pagination"></ul>
                </nav>
            </div>
        </div>
        <!-- Footer -->
        <footer class="bg-dark text-white mt-auto">
            <div class="container py-4 text-center">
                <p class="mb-0">&copy; 2025 MM Hall. All rights reserved.</p>
            </div>
        </footer>
    </div>


    <!-- jQuery (from CDN) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS for Search & Pagination -->
    <script>
        $(document).ready(function() {
            var itemsPerPage = 5;
            var $appItems = $('.app-item');

            // Function to filter items based on search term.
            function filterItems() {
                var searchTerm = $('#searchInput').val().toLowerCase().trim();
                // Filter by comparing the data-app-id attribute.
                var $filtered = $appItems.filter(function() {
                    var appId = $(this).data('app-id').toString();
                    return appId.indexOf(searchTerm) > -1;
                });
                return $filtered;
            }

            // Function to render pagination controls.
            function renderPagination($filtered, currentPage) {
                var total = $filtered.length;
                var totalPages = Math.ceil(total / itemsPerPage);
                var $pagination = $('#pagination');
                $pagination.empty();

                // Only render if more than one page.
                if (totalPages <= 1) return;

                // Previous button.
                if (currentPage > 1) {
                    $pagination.append('<li class="page-item"><a class="page-link" href="#" data-page="' + (currentPage - 1) + '">Previous</a></li>');
                } else {
                    $pagination.append('<li class="page-item disabled"><span class="page-link">Previous</span></li>');
                }

                // Page numbers.
                for (var i = 1; i <= totalPages; i++) {
                    if (i === currentPage) {
                        $pagination.append('<li class="page-item active"><span class="page-link">' + i + '</span></li>');
                    } else {
                        $pagination.append('<li class="page-item"><a class="page-link" href="#" data-page="' + i + '">' + i + '</a></li>');
                    }
                }

                // Next button.
                if (currentPage < totalPages) {
                    $pagination.append('<li class="page-item"><a class="page-link" href="#" data-page="' + (currentPage + 1) + '">Next</a></li>');
                } else {
                    $pagination.append('<li class="page-item disabled"><span class="page-link">Next</span></li>');
                }
            }

            // Function to perform pagination.
            function paginate() {
                var $filtered = filterItems();
                var currentPage = parseInt($('#currentPage').val(), 10) || 1;
                var total = $filtered.length;
                var totalPages = Math.ceil(total / itemsPerPage);
                if (currentPage > totalPages) {
                    currentPage = 1;
                }
                $('#currentPage').val(currentPage);
                // Hide all items and then show only the current page's items.
                $appItems.hide();
                $filtered.slice((currentPage - 1) * itemsPerPage, currentPage * itemsPerPage).show();
                renderPagination($filtered, currentPage);
            }

            // When search input changes, reset to page 1 and paginate.
            $('#searchInput').on('keyup', function() {
                $('#currentPage').val(1);
                paginate();
            });

            // Handle pagination link clicks.
            $('#pagination').on('click', 'a.page-link', function(e) {
                e.preventDefault();
                var page = $(this).data('page');
                $('#currentPage').val(page);
                paginate();
            });

            // Hidden input to keep track of the current page.
            if ($('#currentPage').length === 0) {
                $('<input type="hidden" id="currentPage" value="1">').appendTo('body');
            }

            // Initial pagination.
            paginate();
        });
    </script>
</body>

</html>