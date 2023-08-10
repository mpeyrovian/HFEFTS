<?php

require "inc/employee-function.inc.php"; // configuration file
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

if (isset($_GET['infectionType']) || isset($_GET['timesInfected'])) {
    $results = fetchByTimesInfected($_GET['infectionType'], $_GET['timesInfected']);
} else {
    $results = fetchByTimesInfected("covid", "3");
}

global $db;
if (!$results) die("Error fetching items from DB: " . $db->error);
$pageTitle = "Report 16 - Times Infected"; // Dynamic page title
include "inc/header.inc.php"; // header
?>
    <div class="row mt-4">
        <div class="col-12">
            <h2><?= $pageTitle ?></h2>
            <h6 class="text-justify">Get details of the nurse(s) or the doctor(s) who are currently working and have been infected by COVID-19
                at least three times. Details include first-name, last-name, first day of work as a nurse or as a doctor,
                role (nurse/doctor), date of birth, email address, and total number of hours scheduled. Results should
                be displayed sorted in ascending order by role, then by first name, then by last name.</h6>
            <hr>
            <?php displayErrors(); ?>
        </div>

        <div class="col-12 mb-3">
            <form action="" method="GET" class="form-inline border p-2">
                <div class="form-group pl-2">
                    <label for="infectionType" class="pr-2">Infection Type:</label>
                    <input type="text" class="form-control" name="infectionType" id="infectionType">
                </div>
                <div class="form-group pl-2">
                    <label for="timesInfected" class="pr-2">Minimum Number of Times Infected:</label>
                    <input type="number" class="form-control" name="timesInfected" id="timesInfected">
                </div>
                <button type="submit" class="btn btn-primary ml-2">Filter</button>
            </form>
        </div>

        <div class="table-responsive col-12">
            <table class="table table-hover table-striped table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>First Day of Work</th>
                    <th>Role</th>
                    <th>Number of Times Infected</th>
                    <th>Date Of Birth</th>
                    <th>email</th>
                    <th>Total Hours Scheduled</th>
                    <th>Schedule</th>

                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $results->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['firstName']; ?></td>
                        <td><?= $row['lastName']; ?></td>
                        <td><?= $row['startDate']; ?></td>
                        <td><?= $row['role']; ?></td>
                        <td><?= $row['timesInfected']; ?></td>
                        <td><?= $row['dateOfBirth']; ?></td>
                        <td><?= $row['email']; ?></td>
                        <td><?= $row['TotalHours']; ?></td>
                        <td><a href="schedule.php?employeeId=<?= $row['id'] ?>">Schedule</a></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

<?php include "inc/footer.inc.php"; //footer ?>