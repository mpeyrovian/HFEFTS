<?php

require "inc/schedule-function.inc.php";

connectDB();
global $db;

$schedule = array('id' => "", 'date' => "", 'startTime' => "", 'endTime' => "",
    'employmentId' => "", 'isCancelled' => "");
$pageTitle = "";

$employeeId = isset($_GET['employeeId']) ? $_GET['employeeId'] : "";
$employmentsArray = fetchAllEmploymentsForEmployee($employeeId);
$employee = fetchEmployee($employeeId);
$employeeFirstName = $employee['firstName'];
$employeeLastName = $employee['lastName'];
$employeeMedCardNum = $employee['medicareCardNo'];

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['scheduleId']) && is_numeric($_GET['scheduleId'])) {
        $schedule = fetchSchedule($_GET['scheduleId']);
        if ($_GET['mode'] == "delete") {
            deleteSchedule($schedule['id']);
            header("Location: schedule.php?message=removed&employeeId=" . htmlentities($employeeId)
                            . "&scheduleId=" . $schedule['id']);
            die();
        } else if ($_GET['mode'] == "edit") {
            $pageTitle = "Edit a Schedule";
        } else {
            header("Location: schedule.php?message=invalidRequest&employeeId=" . htmlentities($employeeId)
                            . "&scheduleId=" . $schedule['id']);
            die();
        }
    } else { // no id provided
        $pageTitle = "Add a Schedule";
        if ($employmentsArray->num_rows == 0) {
            $errorMessage = "No Employment Found. Please first add an employment for this employee.";
        }
    }
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // set info for form in case of error
    $scheduleId = isset($_POST['scheduleId']) ? $_POST['scheduleId'] : "";
    $date = isset($_POST['date']) ? $_POST['date'] : "";
    $startTime = isset($_POST['startTime']) ? $_POST['startTime'] : "";
    $endTime = isset($_POST['endTime']) ? $_POST['endTime'] : "";
    $employmentId = isset($_POST['employmentId']) ? $_POST['employmentId'] : "";
    $isCancelled = isset($_POST['isCancelled']) ? 1 : 0;

    // data validation
    if (isFieldEmpty($date) || isFieldEmpty($startTime)
        || isFieldEmpty($endTime)  || isFieldEmpty($employmentId)) {
        $errorMessage = "All fields are required";
    }


    if ($errorMessage == "") { // no validation errors so far
        // Employee must have received at least one vaccine for COVID-19 in the past six months
        $errorMessage = validateSchedule($date, $employmentId) ? "" : "Employee must have received at least one vaccine for COVID-19 in the past six months prior to the schedule date";
    }

    if ($errorMessage == "") { // no validation errors
        // we check for an id, so we know if it's a creation or an update
        if (isset($_POST['scheduleId']) && is_numeric($_POST['scheduleId'])) { // update
            updateSchedule($scheduleId, $date, $startTime, $endTime, $employmentId, $isCancelled);
            $action ="updated";
        } else { // create
            $scheduleId = createSchedule($date, $startTime, $endTime, $employmentId, $isCancelled);
            $action = "created";
        }
        // redirect based on whether schedule was created or updated
        if ( $scheduleId == null) {
            header("Location: schedule.php?employeeId=" . $_GET['employeeId'] . "&message=triggerError&errorMsg=" . urlencode($db->error));
            die();
        } else{
            header("Location: schedule.php?message=$action&employeeId=" . htmlentities($employeeId)
                        . "&scheduleId=" . $scheduleId);
        die();}
    }

    // Validation errors found: display the form plus the previously entered data
    $schedule = $_POST;
}
if ( $db->errno == 1644) {
    header("Location: schedule-update.php?employeeId=" . $_GET['employeeId'] . "&message=triggerError&errorMsg=" . urlencode($db->error));
    exit;
}
include "inc/header.inc.php"; ?>
    <div class="row">
        <div class="col-12">
            <h2><?= $pageTitle ?></h2>
            <h4><?= $employeeMedCardNum . ' - ' . $employeeFirstName . ' ' . $employeeLastName ?></h4>
            <hr class="mb-4">
        </div>
    </div>
<?php displayErrors(); ?>
    <div class="row">
        <div class="col-md-12 p-3">

            <form action="" method="POST">

                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" class="form-control" name="date" id="date"
                           value="<?= $schedule['date']; ?>">
                </div>


                <div class="form-group">
                    <label for="startTime">Start Time</label>
                    <input type="Time" class="form-control" name="startTime" id="startTime"
                           value="<?= $schedule['startTime']; ?>">
                </div>

                <div class="form-group">
                    <label for="endTime">End Time</label>
                    <input type="Time" class="form-control" name="endTime" id="endTime"
                           value="<?= $schedule['endTime']; ?>">
                </div>

                <div class="form-group">
                    <label for="employmentId">Employment</label>
                    <select class="form-control" name="employmentId" id="employmentId">
                        <?php foreach ($employmentsArray as $employment) { ?>
                            <option value="<?= $employment['id'] ?>" <?= ($employment['id'] == $schedule['employmentId']) ? "selected" : "" ?>>
                                <?= $employment['facilityName'] . ' (' . $employment['startDate'] . ' to ' .
                                (isset($employment['endDate']) ? $employment['endDate'] : 'Present')
                                . ')' ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-check">
                    <?php $isChecked = (isset($schedule['isCancelled']) && $schedule['isCancelled'] == 1) ? "checked" : ""; ?>
                    <input class="form-check-input" type="checkbox" name="isCancelled" id="isCancelled"
                           value="1" <?= $isChecked ?>>
                    <label class="form-check-label" for="isCancelled">Select to Cancel Schedule</label>
                </div>

                <input type="hidden" name="scheduleId" value="<?= $schedule['id'] ?>"/><br/>
                <button type="submit" class="btn btn-primary">Submit</button>

            </form>

        </div>
    </div>
<?php include "inc/footer.inc.php"; ?>