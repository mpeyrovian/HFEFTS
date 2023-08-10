<?php
// require "inc/function.inc.php"; // configuration file
require "inc/employment-function.inc.php";
connectDB(); // Connect to database

if (isset ($_GET['message'])) {
    $id = isset($_GET['id']) ? $_GET['id'] : "";

    switch ($_GET['message']) {
        case "removed" :
            $errorMessage = "employment record '$id' has been removed";
            break;
        case "notRemoved" :
            $errorMessage = "Employment record '$id' was <u>not</u> removed. 
                            There are some schedules that depend on this record";
            break;
        case "updated" :
            $errorMessage = "employment record '$id' has been updated";
            break;
        case "created" :
            $errorMessage = "employment record '$id' has been created";
            break;
        case "notFound":
            $errorMessage = "No employment record found";
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

$employments = fetchAllEmployments();
global $db;
if (!$employments) die("Error fetching items from DB: " . $db->error);
$pageTitle = "Employment"; // Dynamic page title
include "inc/header.inc.php"; // header
?>
    <div class="row mt-4">
        <div class="col-12">
            <h2><?= $pageTitle ?><a class="btn btn-sm btn-success pull-right" href="employment-update.php">Create</a></h2>
            <hr>
            <?php displayErrors(); ?>
        </div>

        <div class="table-responsive col-12">
            <table class="table table-hover table-striped table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th>Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Facility</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $employments->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['medicareCardNo'] . ' - ' . $row['firstName'] . ' ' . $row['lastName']; ?></td>
                        <td><?= $row['startDate']; ?></td>
                        <td><?= $row['endDate']; ?></td>
                        <td><?= $row['facilityName']; ?></td>
                        <td>
                            <a href="employment-update.php?id=<?= $row['id']; ?>&mode=edit">Edit</a> |
                            <a href="employment-update.php?id=<?= $row['id']; ?>&mode=delete">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

<?php include "inc/footer.inc.php"; //footer ?>