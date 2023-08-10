<?php

require "inc/vaccination-function.inc.php"; // configuration file
global $db;
connectDB(); // Connect to database

// set initial values
$data = array('employeeId' => "", 'date' => "", 'typeId' => "", 'dose' => "", 'id' => "");
$pageTitle = "Add a vaccination"; // Dynamic page title

$employeeArray = fetchAllEmployees();
$vaccinationTypeArray = fetchAllVaccinationTypes();
$facilityArray = fetchAllFacilities();

if ($_SERVER['REQUEST_METHOD'] == "GET") { // if GET method used
    if (isset($_GET['id']) && is_numeric($_GET['id'])) { // check if an id was given
        $data = fetchVaccination($_GET['id']);
        if ($_GET['mode'] == "delete") {
            // delete Vaccination
            deleteVaccination($_GET['id']);
            header("Location: vaccination.php?message=removed&id=" . htmlentities($data['id']));
            die();
        } else if ($_GET['mode'] == "edit") {
            // display 'Edit vaccination' form
            $pageTitle = "Edit a vaccination";
        } else {
            header("Location: vaccination.php?message=invalidRequest");
            die();
        }
    } else { // no id provided
        // display 'Add vaccination' form
        $pageTitle = "Add a vaccination";
    }
} else if ($_SERVER['REQUEST_METHOD'] == "POST") { // if POST method used
    // set info for form in case of error
    $id = isset($_POST['id']) ? $_POST['id'] : "";
    $employeeId = isset($_POST['employeeId']) ? $_POST['employeeId'] : "";
    $date = isset($_POST['date']) ? $_POST['date'] : "";
    $typeId = isset($_POST['typeId']) ? $_POST['typeId'] : "";
    $dose = isset($_POST['dose']) ? $_POST['dose'] : "";
    $facilityId = isset($_POST['facilityId']) ? $_POST['facilityId'] : "";

    // data validation
    if (isFieldEmpty($_POST['employeeId']) || isFieldEmpty($_POST['date']) || isFieldEmpty($_POST['typeId']) || isFieldEmpty($_POST['dose']) || isFieldEmpty($_POST['facilityId'])) {
        $errorMessage = "All fields are required";
    }
    if (!is_numeric($_POST['dose'])) {
        $errorMessage = "Does must be a number";
    }

    if ($errorMessage == "") { // no validation errors
        // we check for an id, so we know if it's a creation or an update
        if (isset($_POST['id']) && is_numeric($_POST['id'])) { // update
            updateVaccination($employeeId, $date, $typeId, $dose, $facilityId, $id);
            $action ="updated";
        } else { // create
            $id = createVaccination($employeeId, $date, $typeId, $dose, $facilityId);
            $action = "created";
        }
        if ( $id == null) {
            header("Location: vaccination.php?&message=triggerError&errorMsg=" . urlencode($db->error));
            die();
        } else {
            // redirect based on whether Vaccination was created or updated
            header("Location: vaccination.php?message=$action&id=" . htmlentities($id));
            die();
        }
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
                <label for="typeId">TypeId</label>
                <select class="form-control" name="typeId" id="typeId">
                    <?php foreach ($vaccinationTypeArray as $vaccinationType) { ?>
                        <option value="<?= $vaccinationType['id'] ?>" <?= ($vaccinationType['id'] == $data['typeId']) ? "selected" : "" ?>><?= $vaccinationType['type'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="dose">Dose #</label>
                <input type="text" class="form-control" name="dose" id="dose" value="<?= $data['dose']; ?>">
            </div>
            <div class="form-group">
                <label for="facilityId">Facility</label>
                <select class="form-control" name="facilityId" id="facilityId">
                    <?php foreach ($facilityArray as $facility) {
                        $isSelected = ($facility['id'] == $data['facilityId']) ? "selected" : "";
                        ?>
                        <option value="<?= $facility['id'] ?>" <?= $isSelected ?>>
                            <?= $facility['name'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>"/><br/>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
<?php include "inc/footer.inc.php"; ?>
