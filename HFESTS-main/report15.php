<?php

require "inc/report-function.inc.php"; // configuration file
connectDB(); // Connect to database

if (isset ($_GET['message'])) {
    $id = isset($_GET['id']) ? $_GET['id'] : "";

    switch ($_GET['message']) {
        case "removed" :
            $errorMessage = "Employee '$id' has been removed";
            break;
        case "notremoved" :
            $errorMessage = "Employee '$id' was <u>not</u> removed. There are some items that depend on this employee";
            break;
        case "updated" :
            $errorMessage = "Employee '$id' has been updated";
            break;
        case "created" :
            $errorMessage = "Employee '$id' has been created";
            break;
        case "notFound":
            $errorMessage = "No Employee found";
            break;
        case "dbError":
            $errorMessage = "Error Connecting to the Database";
            break;
        case "invalidRequest":
            $errorMessage = "Invalid Request";
            break;
    }
}


$results = fetchNurseHoursSummary();
global $db;
if (!$results) die("Error fetching items from DB: " . $db->error);
$pageTitle = "Report 15"; // Dynamic page title
include "inc/header.inc.php"; // header
?>
    <div class="row mt-4">
        <div class="col-12">

            <h5>  Get details of the nurse(s) who is/are currently working and has the highest
                number of hours scheduled in the system since they started working as a
                nurse. Details include first-name, last-name, first day of work as a nurse, date
                of birth, email address, and total number of hours schedule</h5>
            <hr>
            <?php displayErrors(); ?>
        </div>



        <div class="table-responsive col-12">
            <table class="table table-hover table-striped table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th>firstName</th>
                    <th>lastName</th>
                    <th>firstDayOfWork</th>
                    <th>dateOfBirth</th>
                    <th>email</th>
                    <th>totalHoursScheduled</th>


                </tr>
                </thead>
                <tbody>
                <?php while ($row = $results->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['firstName']; ?></td>
                        <td><?= $row['lastName']; ?></td>
                        <td><?= $row['firstDayOfWork']; ?></td>
                        <td><?= $row['dateOfBirth']; ?></td>
                        <td><?= $row['email']; ?></td>
                        <td><?= $row['totalHoursScheduled']; ?></td>

                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

<?php include "inc/footer.inc.php"; //footer ?>s