<?php
require "inc/report-function.inc.php"; // configuration file
connectDB(); // Connect to database

$facilityName = isset($_GET['firstName']) ? $_GET['firstName'] : "";
$results = fetchDoctorsInfected2Weeks();

global $db;
if (!$results) die("Error fetching items from DB: " . $db->error);
$pageTitle = "Report 9 - Covid in Last 2 Weeks"; // Dynamic page title
include "inc/header.inc.php"; // header
?>
    <div class="row mt-4">
        <div class="col-12">
            <h2><?= $pageTitle ?></h2>
            <h6 class="text-justify">Get details of all the doctors who have been infected by COVID-19 in the past two weeks. Details include doctor’s first-name, last-name, date of infection, and the name of the facility that the doctor is currently working for. Results should be displayed sorted in ascending order by the facility name, then by the first-name of the doctor.</h6>
            <hr>
        </div>

        <div class="table-responsive col-12">
            <table class="table table-hover table-striped table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Infection Date</th>
                    <th>Facility</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $results->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['firstName']; ?></td>
                        <td><?= $row['lastName']; ?></td>
                        <td><?= $row['date']; ?></td>
                        <td><?= $row['name']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

<?php include "inc/footer.inc.php"; //footer ?>