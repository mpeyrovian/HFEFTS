<?php

require "inc/vaccination-function.inc.php"; // configuration file

connectDB(); // Connect to database

if (isset ($_GET['message'])) {
    $id = isset($_GET['id']) ? $_GET['id'] : "";

    switch ($_GET['message']) {
        case "removed" :
            $errorMessage = "Vaccination record '$id' has been removed";
            break;
        case "updated" :
            $errorMessage = "Vaccination record '$id' has been updated";
            break;
        case "created" :
            $errorMessage = "Vaccination record '$id' has been created";
            break;
        case "notFound":
            $errorMessage = "No Vaccination record found";
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

if (isset($_GET['firstName']) || isset($_GET['lastName']) || isset($_GET['medicareCardNo']) || isset($_GET['type'])) { // if GET method used
    $results = fetchAllVaccinationsBySearch($_GET['firstName'], $_GET['lastName'], $_GET['medicareCardNo'], $_GET['type']);
} else {
    $results = fetchAllVaccinationsBySearch("", "", "", "");
}

global $db;
if (!$results) die("Error fetching items from DB: " . $db->error);
$pageTitle = "Vaccinations"; // Dynamic page title
include "inc/header.inc.php"; // header
?>
<div class="row mt-4">
    <div class="col-12">
        <h2><?= $pageTitle ?><a class="btn btn-sm btn-success pull-right" href="vaccination-update.php">Create</a></h2>
        <hr>
        <?php displayErrors(); ?>
    </div>

    <div class="col-12 mb-3">
        <form action="" method="GET" class="form-inline border p-2">
            <div class="form-group pl-2">
                <label for="firstName" class="pr-2">First Name:</label>
                <input type="text" class="form-control" name="firstName" id="firstName">
            </div>
            <div class="form-group pl-2">
                <label for="lastName" class="pr-2">Last Name:</label>
                <input type="text" class="form-control" name="lastName" id="lastName">
            </div>
            <div class="form-group pl-2">
                <label for="medicareCardNo" class="pr-2">Med.Card#:</label>
                <input type="text" class="form-control" name="medicareCardNo" id="medicareCardNo">
            </div>
            <div class="form-group pl-2">
                <label for="type" class="pr-2">Vaccination Type:</label>
                <input type="text" class="form-control" name="type" id="type">
            </div>
            <button type="submit" class="btn btn-primary ml-2">Filter</button>
        </form>
    </div>

    <div class="table-responsive col-12">
        <table class="table table-hover table-striped table-bordered">
            <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Employee</th>
                <th>Date</th>
                <th>Vaccination Type</th>
                <th>Dose</th>
                <th>Facility</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $results->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['medicareCardNo']; ?> - <?= $row['firstName'] . ' ' . $row['lastName']; ?></td>
                    <td><?= $row['date']; ?></td>
                    <td><?= $row['type']; ?></td>
                    <td><?= $row['dose']; ?></td>
                    <td><?= $row['facilityName']; ?></td>
                    <td>
                        <a href="vaccination-update.php?id=<?= $row['id']; ?>&mode=edit">Edit</a> |
                        <a href="vaccination-update.php?id=<?= $row['id']; ?>&mode=delete">Delete</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "inc/footer.inc.php"; //footer ?>
