<?php

require "inc/facility-function.inc.php"; // configuration file

connectDB(); // Connect to database

if (isset ($_GET['message'])) {
    $id = isset($_GET['id']) ? $_GET['id'] : "";

    switch ($_GET['message']) {
        case "removed" :
            $errorMessage = "Facility record '$id' has been removed";
            break;
        case "notremoved" :
            $errorMessage = "Facility record '$id' was <u>not</u> removed. There are some items that depend on this facility";
            break;
        case "updated" :
            $errorMessage = "Facility record '$id' has been updated";
            break;
        case "created" :
            $errorMessage = "Facility record '$id' has been created";
            break;
        case "notFound":
            $errorMessage = "No facility record found";
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

if (isset($_GET['name']) || isset($_GET['facilityType'])) { // if GET method used
    $results = fetchAllFacilitiesBySearch($_GET['name'], $_GET['facilityType']);
} else {
    $results = fetchAllFacilitiesBySearch("", "");
}

global $db;
if (!$results) die("Error fetching items from DB: " . $db->error);
$pageTitle = "Facilities"; // Dynamic page title
include "inc/header.inc.php"; // header
?>

    <div class="row mt-4">
        <div class="col-12">
            <h2><?= $pageTitle ?><a class="btn btn-sm btn-success pull-right" href="facility-update.php">Create</a></h2>
            <hr>
            <?php displayErrors(); ?>
        </div>

        <div class="col-12 mb-3">
            <form action="" method="GET" class="form-inline border p-2">
                <div class="form-group">
                    <label for="name" class="pr-2">Name:</label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>
                <div class="form-group pl-2">
                    <label for="facilityType" class="pr-2">Facility Type:</label>
                    <input type="text" class="form-control" name="facilityType" id="facilityType">
                </div>
                <button type="submit" class="btn btn-primary ml-2">Filter</button>
            </form>
        </div>

        <div class="table-responsive col-12">
            <table class="table table-hover table-striped table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Facility Name</th>
                    <th>Phone</th>
                    <th>Web Address</th>
                    <th>Facility Type</th>
                    <th>Capacity</th>
                    <th>Address</th>
                    <th>Postal Code</th>
                    <th>City</th>
                    <th>Province</th>
                    <th>Facility General Manager</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $results->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['name']; ?> </td>
                        <td><?= $row['phone']; ?></td>
                        <td><?= $row['webAddress']; ?></td>
                        <td><?= $row['facilityType']; ?></td>
                        <td><?= $row['capacity']; ?></td>
                        <td><?= $row['address']; ?></td>
                        <td><?= $row['postalCode']; ?></td>
                        <td><?= $row['city']; ?></td>
                        <td><?= $row['province']; ?></td>
                        <td><?= $row['generalManagerName']; ?></td>
                        <td>
                            <a href="facility-update.php?id=<?= $row['id']; ?>&mode=edit">Edit</a> |
                            <a href="facility-update.php?id=<?= $row['id']; ?>&mode=delete">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

<?php include "inc/footer.inc.php"; //footer ?>