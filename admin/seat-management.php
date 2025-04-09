<?php
include_once '../class-file/SessionManager.php';
$session = SessionStatic::class;
if ($session::get('msg1')) {
    include_once '../popup-1.php';
    showPopup($session::get('msg1'));
    $session::delete('msg1');
}

include_once '../class-file/HallSeatDetails.php';
$seatDetails = new HallSeatDetails();
$totalFloors = $seatDetails->getMaxFloorNo();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="../css/Dashboard/dashboard.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <title>Seat Management - Dashboard</title>

    <style>
        .table-container {
            margin-top: 20px;
        }
        .table th, .table td {
            text-align: center;
        }
        .table {
            width: 100%;
            margin: 0 auto;
        }
        .btn-view {
            padding: 5px 10px;
            font-size: 14px;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand dashboard-nav py-4">
        <a class="navbar-brand ps-3" href="../index.html">HMS</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
    </nav>
    <div id="layoutSidenav">
        <?php include '../admin/admin-sidebar.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <div class="card__wrapper">
                        <div class="card__title-wrap mb-20">
                            <h3 class="table__heading-title">Seat Management</h3>
                        </div>
                        <!-- Button to Proceed to Create Floor and Seat Page -->
                        <div class="mb-3">
                            <a href="create-floor-room-seat.php" class="btn btn-success btn-create">Create Floor, Room & Seat</a>
                        </div>

                        <!-- Table for Floor No and View Room & Seat button -->
                        <div class="table-container">
                            <table id="userTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Floor No</th>
                                        <th>View Room & Seat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Loop through each floor number and create a row
                                    for ($floor = 0; $floor <= $totalFloors; $floor++) {
                                        echo "<tr>";
                                        echo "<td>" . ($floor) . "</td>"; // Display floor number (0-indexed)
                                        echo "<td><a href='floorwise.php?floorNo=" . $floor . "' class='btn btn-primary btn-view'>View</a></td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </main>
            <footer class="py-4 dashboard-copyright-footer mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Just 2024</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms & Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#userTable').DataTable(); // Initialize DataTables on #userTable
        });
    </script>
</body>

</html>
