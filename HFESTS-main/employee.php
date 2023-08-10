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
        case "triggerError":
            $errorMessage = isset($_GET['errorMsg']) ? $_GET['errorMsg'] : "Unknown trigger error";
            break;
    }
}

if (isset($_GET['firstName']) || isset($_GET['lastName']) || isset($_GET['medicareCardNo']) || isset($_GET['role'])) { // if GET method used
    $results = fetchAllEmployeesBySearch($_GET['firstName'], $_GET['lastName'], $_GET['medicareCardNo'], $_GET['role']);
} else {
    $results = fetchAllEmployeesBySearch("", "", "", "");
}

global $db;
if (!$results) die("Error fetching items from DB: " . $db->error);
$pageTitle = "Employees"; // Dynamic page title
include "inc/header.inc.php"; // header
?>
    <div class="row mt-4">
        <div class="col-12">
            <h2><?= $pageTitle ?><a class="btn btn-sm btn-success pull-right" href="employee-update.php">Create</a></h2>
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
                    <label for="roleId" class="pr-2">Role:</label>
                    <input type="text" class="form-control" name="role" id="role">
                </div>
                <button type="submit" class="btn btn-primary ml-2">Filter</button>
            </form>
        </div>

        <div class="table-responsive col-12">
            <table class="table table-hover table-striped table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>DOB</th>
                    <th>Med.Card</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Postal Code</th>
                    <th>City / Province</th>
                    <th>Citizenship</th>
                    <th>Email</th>
                    <th>Schedule</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $results->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['firstName']; ?></td>
                        <td><?= $row['lastName']; ?></td>
                        <td><?= $row['dateOfBirth']; ?></td>
                        <td><?= $row['medicareCardNo']; ?></td>
                        <td><?= $row['role']; ?></td>
                        <td><?= $row['phone']; ?></td>
                        <td><?= $row['address']; ?></td>
                        <td><?= $row['postalCode']; ?></td>
                        <td><?= $row['city']; ?>, <?= $row['province']; ?></td>
                        <td><?= $row['citizenship']; ?></td>
                        <td><?= $row['email']; ?></td>
                        <td><a href="schedule.php?employeeId=<?= $row['id'] ?>">Schedule</a></td>
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