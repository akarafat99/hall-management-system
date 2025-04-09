<?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h5>Submitted Data</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Field</th>
                                                <th>Value</th>
                                            </tr>
                                            <tr>
                                                <td>Event Title</td>
                                                <td><?php echo $hallSeatAllocationEvent->title; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Event Details</td>
                                                <td><?php echo $hallSeatAllocationEvent->details; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Application Start Date</td>
                                                <td><?php echo $hallSeatAllocationEvent->application_start_date; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Application End Date</td>
                                                <td><?php echo $hallSeatAllocationEvent->application_end_date; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Viva Notice Date</td>
                                                <td><?php echo $hallSeatAllocationEvent->viva_notice_date; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Priority List</td>
                                                <td><?php echo $hallSeatAllocationEvent->priority_list; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Seat Distribution Quota</td>
                                                <td><?php echo $hallSeatAllocationEvent->seat_distribution_quota; ?></td>
                                            </tr>
                                            <tr>
                                                <td>BSc11</td>
                                                <td><?php echo htmlspecialchars($_POST['bsc11']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>BSc12</td>
                                                <td><?php echo htmlspecialchars($_POST['bsc12']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>BSc21</td>
                                                <td><?php echo htmlspecialchars($_POST['bsc21']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>BSc22</td>
                                                <td><?php echo htmlspecialchars($_POST['bsc22']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>BSc31</td>
                                                <td><?php echo htmlspecialchars($_POST['bsc31']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>BSc32</td>
                                                <td><?php echo htmlspecialchars($_POST['bsc32']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>BSc41</td>
                                                <td><?php echo htmlspecialchars($_POST['bsc41']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>BSc42</td>
                                                <td><?php echo htmlspecialchars($_POST['bsc42']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>MSc11</td>
                                                <td><?php echo htmlspecialchars($_POST['msc11']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>MSc12</td>
                                                <td><?php echo htmlspecialchars($_POST['msc12']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>MSc21</td>
                                                <td><?php echo htmlspecialchars($_POST['msc21']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>MSc22</td>
                                                <td><?php echo htmlspecialchars($_POST['msc22']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Total Available Seats</td>
                                                <td><?php echo $totalAvailableSeats; ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            <?php endif; ?>








                            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">&copy; Just 2024</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>