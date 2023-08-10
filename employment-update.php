<?php

require "inc/employment-function.inc.php";

connectDB();
global $db;
$employment = array('id' => "", 'employeeId' => "", 'startDate' => "", 'endDate' => "", 'facilityId' => "");
$pageTitle = ""; // Dynamic page title

$facilityArray = fetchAllFacilities();
$employeeArray = fetchAllEmployees();

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $employment = fetchEmployment($_GET['id']);
        if ($_GET['mode'] == "delete") {
            $result = deleteEmployment($_GET['id']);
            if (!$result) {
                header("Location: employment.php?message=notRemoved&id=" . htmlentities($employment['id']));
                die();
            }
            header("Location: employment.php?message=removed&id=" . htmlentities($employment['id']));
            die();
        } else if ($_GET['mode'] == "edit") {
            $pageTitle = "Edit an employment";
        } else {
            header("Location: employment.php?message=invalidRequest");
            die();
        }
    } else { // no id provided
        $pageTitle = "Add an employment";
    }
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // set info for form in case of error
    $id = isset($_POST['id']) ? $_POST['id'] : "";
    $employeeId = isset($_POST['employeeId']) ? $_POST['employeeId'] : "";
    $startDate = isset($_POST['startDate']) ? $_POST['startDate'] : "";
    $endDate = isset($_POST['endDate']) ? $_POST['endDate'] : "";
    $facilityId = isset($_POST['facilityId']) ? $_POST['facilityId'] : "";


    // data validation
    if (isFieldEmpty($_POST['employeeId']) || isFieldEmpty($_POST['startDate'])
        || isFieldEmpty($_POST['facilityId'])) {
        $errorMessage = "All fields, except 'End Date' are required";
    }

    if ($errorMessage == "") { // no validation errors
        // we check for an id, so we know if it's a creation or an update
        if (isset($_POST['id']) && is_numeric($_POST['id'])) { // update
            updateEmployment($id, $startDate, $endDate, $employeeId, $facilityId);
            $action ="updated";
        } else { // create
            $id = createEmployment($startDate, $endDate, $employeeId, $facilityId);
            if ($id == 0) {
                // display message
            }

            $action = "created";
        }
        if ( $id == null) {
            header("Location: employment.php?&message=triggerError&errorMsg=" . urlencode($db->error));
            die();
        } else {
        // redirect based on whether Infection was created or updated
        header("Location: employment.php?message=$action&id=" . htmlentities($id));
        die();}
    }

    // Validation errors found: display the form plus the previously entered data
    $employment = $_POST;
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
                        <?php foreach ($employeeArray as $employee) {
                            $isSelected = ($employee['id'] == $employment['employeeId']) ? "selected" : "";
                            ?>
                            <option value="<?= $employee['id'] ?>" <?= $isSelected ?>>
                                <?= $employee['medicareCardNo'] . " - " . $employee['firstName'] . $employee['lastName'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="startDate">Start Date</label>
                    <input type="date" class="form-control" name="startDate" id="startDate"
                           value="<?= $employment['startDate']; ?>">
                </div>

                <div class="form-group">
                    <label for="endDate">End Date</label>
                    <input type="date" class="form-control" name="endDate" id="endDate"
                           value="<?= $employment['endDate']; ?>">
                </div>

                <div class="form-group">
                    <label for="facilityId">Facility</label>
                    <select class="form-control" name="facilityId" id="facilityId">
                        <?php foreach ($facilityArray as $facility) {
                            $isSelected = ($facility['id'] == $employment['facilityId']) ? "selected" : "";
                            ?>
                            <option value="<?= $facility['id'] ?>" <?= $isSelected ?>>
                                <?= $facility['name'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <input type="hidden" name="id" value="<?= $employment['id'] ?>"/><br/>
                <button type="submit" class="btn btn-primary">Submit</button>

            </form>

        </div>
    </div>
<?php include "inc/footer.inc.php"; ?>