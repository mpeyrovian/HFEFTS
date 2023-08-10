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
    }
}

if (isset($_GET['infectionType'])) { // if GET method used
    $results = fetchAllFacilitiesByNumberOfInfectedEmployees($_GET['infectionType']);
} else {
    $results = fetchAllFacilitiesByNumberOfInfectedEmployees("COVID-19");
}

global $db;
if (!$results) die("Error fetching items from DB: " . $db->error);
$pageTitle = "Report 13 - Number of Infections per Facility"; // Dynamic page title
include "inc/header.inc.php"; // header
?>

    <div class="row mt-4">
        <div class="col-12">
            <h2><?= $pageTitle ?></h2>
            <h6 class="text-justify">For every facility, provide the province where the facility is located, the facility name, the capacity
                of the facility, and the total number of employees in the facility who have been infected by COVID-19
                in the past two weeks. The results should be displayed in ascending order by province, then by the total
                number of employees infected.</h6>
            <hr>
            <?php displayErrors(); ?>
        </div>

        <div class="table-responsive col-12">
            <table class="table table-hover table-striped table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th>Facility Name</th>
                    <th>Capacity</th>
                    <th>Province</th>
                    <th>Number of employees infected by COVID-19 in the past two weeks</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $results->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['name']; ?> </td>
                        <td><?= $row['capacity']; ?></td>
                        <td><?= $row['province']; ?></td>
                        <td><?= $row['numberOfInfectedEmployees']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

<?php include "inc/footer.inc.php"; //footer ?>