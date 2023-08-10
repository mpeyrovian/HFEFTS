<?php

require "inc/facility-function.inc.php"; // configuration file

connectDB(); // Connect to database

// TODO: Can be transferred to Header
if (isset ($_GET['message'])) {
    $type = isset($_GET['type']) ? $_GET['type'] : "";

    switch ($_GET['message']) {
        case "removed" :
            $errorMessage = "facility Type '$type' has been removed";
            break;
        case "notremoved" :
            $errorMessage = "facility Type record '$type' has not been removed. There are some items that depends on this facility";
            break;
        case "updated" :
            $errorMessage = "facility Type '$type' has been updated";
            break;
        case "created" :
            $errorMessage = "facility Type '$type' has been created";
            break;
        case "notFound":
            $errorMessage = "No facility Type found";
            break;
        case "dbError":
            $errorMessage = "Error Connecting to the Database";
            break;
        case "invalidRequest":
            $errorMessage = "Invalid Request";
            break;
    }
}

$results = fetchAllfacilityTypes();
global $db;
if (!$results) die("Error fetching items from DB: " . $db->error);
$pageTitle = "facility Types"; // Dynamic page title
include "inc/header.inc.php"; // header
?>
    <div class="row mt-4">
        <div class="col-12">
            <h2><?= $pageTitle ?><a class="btn btn-sm btn-success pull-right" href="facility-type-update.php">Create</a></h2>
            <hr>
            <?php displayErrors(); ?>
        </div>

        <div class="table-responsive col-12">
            <table class="table table-hover table-striped table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $results->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['type']; ?></td>
                        <td>
                            <a href="facility-type-update.php?id=<?= $row['id']; ?>&mode=edit">Edit</a> |
                            <a href="facility-type-update.php?id=<?= $row['id']; ?>&mode=delete">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

<?php include "inc/footer.inc.php"; //footer ?>