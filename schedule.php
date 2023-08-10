<?php
// require "inc/function.inc.php"; // configuration file
require "inc/schedule-function.inc.php";
connectDB(); // Connect to database

if (isset ($_GET['message'])) {
    $scheduleId = isset($_GET['scheduleId']) ? $_GET['scheduleId'] : "";

    switch ($_GET['message']) {
        case "removed" :
            $errorMessage = "schedule record '$scheduleId' has been removed";
            break;
        case "updated" :
            $errorMessage = "schedule record '$scheduleId' has been updated";
            break;
        case "created" :
            $errorMessage = "schedule record '$scheduleId' has been created";
            break;
        case "notFound":
            $errorMessage = "No schedule record found";
            break;
        case "dbError":
            $errorMessage = "Error Connecting to the Database";
            break;
        case "invalidRequest":
            $errorMessage = "Invalid Request";
            break;
        case "triggerError":
            $errorMessage = isset($_GET['errorMsg']) ? $_GET['errorMsg'] : "Unknown trigger error";
            break;
    }
}

$schedules = fetchAllSchedulesByEmployeeId($_GET['employeeId']);
$employee = fetchEmployee($_GET['employeeId']);
$employeeFirstName = $employee['firstName'];
$employeeLastName = $employee['lastName'];
$employeeMedCardNum = $employee['medicareCardNo'];

global $db;
if (!$schedules) die("Error fetching items from DB: " . $db->error);

$pageTitle = "Schedule"; // Dynamic page title

include "inc/header.inc.php"; // header
?>

    <div class="row mt-4">
        <div class="col-12">
            <h2><?= $pageTitle ?><a class="btn btn-sm btn-success pull-right"
                                    href="schedule-update.php?employeeId=<?= $employee['id']?>">Create</a></h2>
            <h4><?= $employeeMedCardNum . ' - ' . $employeeFirstName . ' ' . $employeeLastName ?></h4>
            <hr>
            <?php displayErrors(); ?>
        </div>

        <div class="table-responsive col-12">
            <table class="table table-hover table-striped table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th>id</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Cancellation</th>
                    <th>Employment</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $schedules->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['date']; ?></td>
                        <td><?= $row['startTime']; ?></td>
                        <td><?= $row['endTime']; ?></td>
                        <td><?= ($row['isCancelled'] == 1) ? "cancelled": ""; ?></td>
                        <td><?= $row['facilityName'] . ' (' . $row['startDate'] . ' to ' .
                            (isset($row['endDate']) ? $row['endDate'] : 'Present')
                            . ')' ?></td>
                        <td>
                            <a href="schedule-update.php?scheduleId=<?= $row['id']; ?>&employeeId=<?= $_GET['employeeId']; ?>&mode=edit">Edit</a> |
                            <a href="schedule-update.php?scheduleId=<?= $row['id']; ?>&employeeId=<?= $_GET['employeeId']; ?>&mode=delete">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

<?php include "inc/footer.inc.php"; //footer ?>