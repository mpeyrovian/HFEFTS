<?php

require "inc/infection-function.inc.php"; // configuration file

connectDB(); // Connect to database
global $db;

// set initial values
$data = array('employeeId' => "", 'date' => "", 'infectionTypeId' => "", 'id' => "");
$pageTitle = "Add an Infection"; // Dynamic page title

$employeeArray = fetchAllEmployees();
$infectionTypeArray = fetchAllInfectionTypes();

if ($_SERVER['REQUEST_METHOD'] == "GET") { // if GET method used
    if (isset($_GET['id']) && is_numeric($_GET['id'])) { // check if an id was given
        $data = fetchInfection($_GET['id']);
        if ($_GET['mode'] == "delete") {
            // delete Infection
            deleteInfection($_GET['id']);
            header("Location: infection.php?message=removed&id=" . htmlentities($data['id']));
            die();
        } else if ($_GET['mode'] == "edit") {
            // display 'Edit infection' form
            $pageTitle = "Edit an infection";
        } else {
            header("Location: infection.php?message=invalidRequest");
            die();
        }
    } else { // no id provided
        // display 'Add infection' form
        $pageTitle = "Add an infection";
    }
} else if ($_SERVER['REQUEST_METHOD'] == "POST") { // if POST method used
    // set info for form in case of error
    $id = isset($_POST['id']) ? $_POST['id'] : "";
    $employeeId = isset($_POST['employeeId']) ? $_POST['employeeId'] : "";
    $date = isset($_POST['date']) ? $_POST['date'] : "";
    $infectionTypeId = isset($_POST['infectionTypeId']) ? $_POST['infectionTypeId'] : "";

    // data validation
    if (isFieldEmpty($_POST['employeeId']) || isFieldEmpty($_POST['date']) || isFieldEmpty($_POST['infectionTypeId'])) {
        $errorMessage = "All fields are required";
    }

    if ($errorMessage == "") { // no validation errors
        // we check for an id, so we know if it's a creation or an update
        if (isset($_POST['id']) && is_numeric($_POST['id'])) { // update
            updateInfection($employeeId, $date, $infectionTypeId, $id);
            $action ="updated";
        } else { // create
            $id = createInfection($employeeId, $date, $infectionTypeId);
            $action = "created";
        }
        if ( $id == null) {
            header("Location: infection.php?&message=triggerError&errorMsg=" . urlencode($db->error));
            die();
        } else {
        // redirect based on whether Infection was created or updated
        header("Location: infection.php?message=$action&id=" . htmlentities($id));
        die();}
    }

    // Validation errors found: display the form plus the previously entered data
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
                    <label for="employeeId">Employee</label>
                    <select class="form-control" name="employeeId" id="employeeId">
                        <?php foreach ($employeeArray as $employee) { ?>
                            <option value="<?= $employee['id'] ?>" <?= ($employee['id'] == $data['employeeId']) ? "selected" : "" ?>><?= $employee['medicareCardNo'] . " - " . $employee['firstName'] . " " . $employee['lastName'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" class="form-control" name="date" id="date" value="<?= $data['date']; ?>">
                </div>
                <div class="form-group">
                    <label for="infectionTypeId">Infection Type</label>
                    <select class="form-control" name="infectionTypeId" id="infectionTypeId">
                        <?php foreach ($infectionTypeArray as $infectionType) { ?>
                            <option value="<?= $infectionType['id'] ?>" <?= ($infectionType['id'] == $data['infectionTypeId']) ? "selected" : "" ?>><?= $infectionType['type'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <input type="hidden" name="id" value="<?php echo $data['id']; ?>"/><br/>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
<?php include "inc/footer.inc.php"; ?>