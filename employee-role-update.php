<?php

require "inc/employee-role-function.inc.php"; // configuration file

connectDB(); // Connect to database

// set initial values
$data = array('role' => "", 'id' => "");
$pageTitle = "Add an Employee Role"; // Dynamic page title

if ($_SERVER['REQUEST_METHOD'] == "GET") { // if GET method used
    if (isset($_GET['id']) && is_numeric($_GET['id'])) { // check if an id was given
        $data = fetchEmployeeRole($_GET['id']);
        if ($_GET['mode'] == "delete") {
            // delete Employee Role
            $result = deleteEmployeeRole($_GET['id']);
            if (!$result) {
                header("Location: employee-role.php?message=notremoved&role=" . htmlentities($data['role']));
                die();
            }
            header("Location: employee-role.php?message=removed&role=" . htmlentities($data['role']));
            die();
        } else if ($_GET['mode'] == "edit") {
            // display 'Edit Employee Role' form
            $pageTitle = "Edit an Employee Role";
        } else {
            header("Location: employee-role.php?message=invalidRequest");
            die();
        }
    } else { // no id provided
        // display 'Add Employee Role' form
        $pageTitle = "Add an Employee Role";
    }
} else if ($_SERVER['REQUEST_METHOD'] == "POST") { // if POST method used
    // set info for form in case of error
    $id = isset($_POST['id']) ? $_POST['id'] : "";
    $role = isset($_POST['role']) ? $_POST['role'] : "";

    // data validation
    if (isFieldEmpty($_POST['role'])) {
        $errorMessage = "All fields are required";
    }

    if ($errorMessage == "") { // no validation errors
        // we check for an id, so we know if it's a creation or an update
        if (isset($_POST['id']) && is_numeric($_POST['id'])) { // update
            updateEmployeeRole($role, $id);
            $action ="updated";
        } else { // create
            createEmployeeRole($role);
            $action = "created";
        }
        // redirect based on whether user was created or updated
        header("Location: employee-role.php?message=$action&role=" . htmlentities($role));
        die();
    }

    // Validation errors found: display the form plus the previously entered data (except Password)
    $data = $_POST;
}
include "inc/header.inc.php"; ?>
    <div class="row">
        <div class="col-12">
            <h2><?= $pageTitle ?></h2>
            <hr class="mb-4">
        </div>
    </div>
<?php displayErrors(); ?>
    <div class="row">
        <div class="col-md-12 p-3">
            <form action="" method="POST">
                <div class="form-group">
                    <label for="role">Role</label>
                    <input type="text" class="form-control" name="role" id="role" value="<?= $data['role']; ?>">
                </div>
                <input type="hidden" name="id" value="<?php echo $data['id']; ?>"/><br/>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
<?php include "inc/footer.inc.php"; ?>