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

if (isset($_GET['facilityName'])) {
    $results = fetchAllEmployeesByFacility($_GET['facilityName']);
} else {
    $results = fetchAllEmployeesByFacility("");
}

global $db;
if (!$results) die("Error fetching items from DB: " . $db->error);
$pageTitle = "Report 7 - Employee By Facility"; // Dynamic page title
include "inc/header.inc.php"; // header
?>
    <div class="row mt-4">
        <div class="col-12">
            <h2><?= $pageTitle ?></h2>
            <h6 class="text-justify">Get details of all the employees currently working in a specific facility. Details include employee’s
                first-name, last-name, start date of work, date of birth, Medicare card number, telephone-number,
                address, city, province, postal-code, citizenship, and email address. Results should be displayed
                sorted in ascending order by role, then by first name, then by last name.</h6>
            <hr>
            <?php displayErrors(); ?>
        </div>

        <div class="col-12 mb-3">
            <form action="" method="GET" class="form-inline border p-2">
                <div class="form-group pl-2">
                    <label for="facilityName" class="pr-2">Facility:</label>
                    <input type="text" class="form-control" name="facilityName" id="facilityName">
                </div>
                <button type="submit" class="btn btn-primary ml-2">Filter</button>
            </form>
        </div>

        <div class="table-responsive col-12">
            <table class="table table-hover table-striped table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th>Role - Facility</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>start date of work</th>
                    <th>DOB</th>
                    <th>Med.Card</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>City / Province</th>
                    <th>Postal Code</th>
                    <th>Citizenship</th>
                    <th>Email</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $results->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['role']; ?> - <?= $row['facilityName']; ?></td>
                        <td><?= $row['firstName']; ?></td>
                        <td><?= $row['lastName']; ?></td>
                        <td><?= $row['startDate']; ?></td>
                        <td><?= $row['dateOfBirth']; ?></td>
                        <td><?= $row['medicareCardNo']; ?></td>
                        <td><?= $row['phone']; ?></td>
                        <td><?= $row['address']; ?></td>
                        <td><?= $row['city']; ?>, <?= $row['province']; ?></td>
                        <td><?= $row['postalCode']; ?></td>
                        <td><?= $row['citizenship']; ?></td>
                        <td><?= $row['email']; ?></td>
                        <td>
                            <a href="employee-update.php?id=<?= $row['id']; ?>&mode=edit">Edit</a> |
                            <a href="employee-update.php?id=<?= $row['id']; ?>&mode=delete">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

<?php include "inc/footer.inc.php"; //footer ?>