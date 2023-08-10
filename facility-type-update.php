<?php

require "inc/facility-function.inc.php"; // configuration file

connectDB(); // Connect to database

// set initial values
$data = array('type' => "", 'id' => "");
$pageTitle = "Add a facility Type"; // Dynamic page title

if ($_SERVER['REQUEST_METHOD'] == "GET") { // if GET method used
    if (isset($_GET['id']) && is_numeric($_GET['id'])) { // check if an id was given
        $data = fetchFacilityType($_GET['id']);
        if ($_GET['mode'] == "delete") {
            // delete Facility
            $result = deleteFacilityType($_GET['id']);
            if (!$result) {
                header("Location: facility-type.php?message=notremoved&id=" . htmlentities($data['type']));
                die();
            }
            header("Location: facility-type.php?message=removed&type=" . htmlentities($data['type']));
            die();
        } else if ($_GET['mode'] == "edit") {
            // display 'Edit Facility Type' form
            $pageTitle = "Edit a facility Type";
        } else {
            header("Location: facility-type.php?message=invalidRequest");
            die();
        }
    } else { // no id provided
        // display 'Add Facility Type' form
        $pageTitle = "Add a facility Type";
    }
} else if ($_SERVER['REQUEST_METHOD'] == "POST") { // if POST method used
    // set info for form in case of error
    $id = isset($_POST['id']) ? $_POST['id'] : "";
    $type = isset($_POST['type']) ? $_POST['type'] : "";

    // data validation
    if (isFieldEmpty($_POST['type'])) {
        $errorMessage = "All fields are required";
    }

    if ($errorMessage == "") { // no validation errors
        // we check for an id, so we know if it's a creation or an update
        if (isset($_POST['id']) && is_numeric($_POST['id'])) { // update
            updateFacilityType($type, $id);
            $action ="updated";
        } else { // create
            createFacilityType($type);
            $action = "created";
        }
        // redirect based on whether user was created or updated
        header("Location: Facility-type.php?message=$action&type=" . htmlentities($type));
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
                    <label for="type">Type</label>
                    <input type="text" class="form-control" name="type" id="type" value="<?= $data['type']; ?>">
                </div>
                <input type="hidden" name="id" value="<?php echo $data['id']; ?>"/><br/>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
<?php include "inc/footer.inc.php"; ?>