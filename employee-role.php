<?php

require "inc/employee-role-function.inc.php"; // configuration file

connectDB(); // Connect to database

// TODO: Can be transferred to Header
if (isset ($_GET['message'])) {
    $role = isset($_GET['role']) ? $_GET['role'] : "";

    switch ($_GET['message']) {
        case "removed" :
            $errorMessage = "Employee Role '$role' has been removed";
            break;
        case "notremoved" :
            $errorMessage = "Employee Role '$role' was <u>not</u> removed. There are some items that depend on this Employee Role";
            break;
        case "updated" :
            $errorMessage = "Employee Role '$role' has been updated";
            break;
        case "created" :
            $errorMessage = "Employee Role '$role' has been created";
            break;
        case "notFound":
            $errorMessage = "No Employee Role found";
            break;
        case "dbError":
            $errorMessage = "Error Connecting to the Database";
            break;
        case "invalidRequest":
            $errorMessage = "Invalid Request";
            break;
    }
}

$results = fetchAllEmployeeRoles();
global $db;
if (!$results) die("Error fetching items from DB: " . $db->error);
$pageTitle = "Employee Roles"; // Dynamic page title
include "inc/header.inc.php"; // header
?>
    <div class="row mt-4">
        <div class="col-12">
            <h2><?= $pageTitle ?><a class="btn btn-sm btn-success pull-right" href="employee-role-update.php">Create</a></h2>
            <hr>
            <?php displayErrors(); ?>
        </div>

        <div class="table-responsive col-12">
            <table class="table table-hover table-striped table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Role</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $results->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['role']; ?></td>
                        <td>
                            <a href="employee-role-update.php?id=<?= $row['id']; ?>&mode=edit">Edit</a> |
                            <a href="employee-role-update.php?id=<?= $row['id']; ?>&mode=delete">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

<?php include "inc/footer.inc.php"; //footer ?>