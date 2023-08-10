<?php

require "inc/infection-function.inc.php"; // configuration file

connectDB(); // Connect to database

if (isset ($_GET['message'])) {
    $id = isset($_GET['id']) ? $_GET['id'] : "";

    switch ($_GET['message']) {
        case "removed" :
            $errorMessage = "Infection record '$id' has been removed";
            break;
        case "updated" :
            $errorMessage = "Infection record '$id' has been updated";
            break;
        case "created" :
            $errorMessage = "Infection record '$id' has been created";
            break;
        case "notFound":
            $errorMessage = "No Infection record found";
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

if (isset($_GET['firstName']) || isset($_GET['lastName']) || isset($_GET['medicareCardNo']) || isset($_GET['infectionType'])) { // if GET method used
    $results = fetchAllInfectionsBySearch($_GET['firstName'], $_GET['lastName'], $_GET['medicareCardNo'], $_GET['infectionType']);
} else {
    $results = fetchAllInfectionsBySearch("", "", "", "");
}


global $db;
if (!$results) die("Error fetching items from DB: " . $db->error);
$pageTitle = "Infections"; // Dynamic page title
include "inc/header.inc.php"; // header
?>
    <div class="row mt-4">
        <div class="col-12">
            <h2><?= $pageTitle ?><a class="btn btn-sm btn-success pull-right" href="infection-update.php">Create</a></h2>
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
                    <label for="infectionType" class="pr-2">Infection Type:</label>
                    <input type="text" class="form-control" name="infectionType" id="infectionType">
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
                    <th>Infection Type</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $results->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['medicareCardNo']; ?> - <?= $row['firstName']; ?> <?= $row['lastName']; ?></td>
                        <td><?= $row['date']; ?></td>
                        <td><?= $row['type']; ?></td>
                        <td>
                            <a href="infection-update.php?id=<?= $row['id']; ?>&mode=edit">Edit</a> |
                            <a href="infection-update.php?id=<?= $row['id']; ?>&mode=delete">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

<?php include "inc/footer.inc.php"; //footer ?>