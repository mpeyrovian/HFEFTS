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

if (isset($_GET['role']) || isset($_GET['province'])) {
    $results = fetchAllEmployeesByRoleAndProvince($_GET['role'], $_GET['province']);
} else {
    $results = fetchAllEmployeesByRoleAndProvince("", "");
}

global $db;
if (!$results) die("Error fetching items from DB: " . $db->error);
$pageTitle = "Report 14 - Number of Facilities"; // Dynamic page title
include "inc/header.inc.php"; // header
?>
    <div class="row mt-4">
        <div class="col-12">
            <h2><?= $pageTitle ?></h2>
            <h6 class="text-justify">For every doctor who is currently working in the province of “Québec”, provide the doctor’s first-name,
                last-name, the city of residence of the doctor, and the total number of facilities the doctor is
                currently working for. Results should be displayed in ascending order by city, then in descending
                order by total number of facilities.</h6>
            <hr>
            <?php displayErrors(); ?>
        </div>

        <div class="col-12 mb-3">
            <form action="" method="GET" class="form-inline border p-2">
                <div class="form-group pl-2">
                    <label for="role" class="pr-2">Role:</label>
                    <input type="text" class="form-control" name="role" id="role">
                </div>
                <div class="form-group pl-2">
                    <label for="province" class="pr-2">Province:</label>
                    <input type="text" class="form-control" name="province" id="province">
                </div>
                <button type="submit" class="btn btn-primary ml-2">Filter</button>
            </form>
        </div>

        <div class="table-responsive col-12">
            <table class="table table-hover table-striped table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Role</th>
                    <th>City</th>
                    <th>Province</th>
                    <th>Number of Facilities</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $results->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['firstName']; ?></td>
                        <td><?= $row['lastName']; ?></td>
                        <td><?= $row['role']; ?></td>
                        <td><?= $row['city']; ?></td>
                        <td><?= $row['province']; ?></td>
                        <td><?= $row['numberOfFacilities']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

<?php include "inc/footer.inc.php"; //footer ?>