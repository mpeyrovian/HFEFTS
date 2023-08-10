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


$results = fetchFacilities();
global $db;
if (!$results) die("Error fetching items from DB: " . $db->error);
$pageTitle = "Report 6"; // Dynamic page title
include "inc/header.inc.php"; // header
?>
    <div class="row mt-4">
        <div class="col-12">
            <h2><?= $pageTitle ?></h2>
            <h6 class="text-justify">Get details of all the facilities in the system. Details include facility’s name,
                address, city, province, postal-code, phone number, web address, type,
                capacity, general manager’s name and number of employees currently
                working for the facility. Results should be displayed sorted in ascending order
                by province, then by city, then by type, then by number of employees
                currently working for the facility.</h6>
            <hr>
            <?php displayErrors(); ?>
        </div>



        <div class="table-responsive col-12">
            <table class="table table-hover table-striped table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th>name</th>
                    <th>address</th>
                    <th>city</th>
                    <th>province</th>
                    <th>postalCode</th>
                    <th> phone </th>
                    <th>webAddress</th>
                    <th>type</th>
                    <th>capacity</th>
                    <th>currentEmployeesNumber</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $results->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['name']; ?></td>
                        <td><?= $row['address']; ?></td>
                        <td><?= $row['city']; ?></td>
                        <td><?= $row['province']; ?></td>
                        <td><?= $row['postalCode']; ?></td>
                        <td><?= $row['phone']; ?></td>
                        <td><?= $row['webAddress']; ?></td>
                        <td><?= $row['type']; ?></td>
                        <td><?= $row['capacity']; ?></td>
                        <td><?= $row['currentEmployeesNumber']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

<?php include "inc/footer.inc.php"; //footer ?>