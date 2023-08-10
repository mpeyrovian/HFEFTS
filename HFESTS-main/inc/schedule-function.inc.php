<?php
require "inc/function.inc.php";

function fetchAllSchedulesByEmployeeId($id)
{
    global $db;
    $sql = "SELECT schedule.id as id, date, startTime, endTime, isCancelled, employment.startDate, employment.endDate, facility.name as facilityName
        FROM schedule, employment, facility
        WHERE schedule.employmentId = employment.id AND employment.facilityId = facility.id
          AND employment.employeeId = '$id'
        ORDER BY date";
    return $db->query($sql);
}

function fetchSchedule($id)
{
    global $db;
    $id = $db->real_escape_string($id);
    $sql = "SELECT id, date, startTime, endTime, employmentId, isCancelled
            FROM schedule 
            WHERE id = '$id'";
    $result = $db->query($sql);
    if (!$result) {
        header("Location: schedule.php?message=dbError");
        die();
    }
    if ($result->num_rows != 1) {
        header("Location: schedule.php?message=notFound");
        die();
    }
    return $result->fetch_assoc();
}


function deleteSchedule($id)
{
    global $db;
    $id = $db->real_escape_string($id);
    $sql = "DELETE FROM schedule WHERE id = '$id'";
    return $db->query($sql);
}

function updateSchedule($id, $date, $startTime, $endTime, $employmentId, $isCancelled)
{
    global $db;
    $id = $db->real_escape_string($id);
    $date = $db->real_escape_string($date);
    $startTime = $db->real_escape_string($startTime);
    $endTime = $db->real_escape_string($endTime);
    $employmentId = $db->real_escape_string($employmentId);
    $isCancelled = $db->real_escape_string($isCancelled);

    $sql = "UPDATE schedule 
            SET date='$date', startTime='$startTime', endTime='$endTime',
            employmentId='$employmentId', isCancelled = '$isCancelled'
            WHERE schedule.id ='$id'";

    $db->query($sql);
}

function createSchedule($date, $startTime, $endTime, $employmentId, $isCancelled)
{
    global $db;
    $date = $db->real_escape_string($date);
    $startTime = $db->real_escape_string($startTime);
    $endTime = $db->real_escape_string($endTime);
    $employmentId = $db->real_escape_string($employmentId);
    $isCancelled = $db->real_escape_string($isCancelled);

    $sql = "INSERT INTO schedule (date, startTime, endTime, employmentId, isCancelled)
            VALUES ('$date', '$startTime', '$endTime', '$employmentId', '$isCancelled')";
    $db->query($sql);
    return $db->insert_id;
}

function validateSchedule($date, $employmentId)
{
    global $db;
    $date = $db->real_escape_string($date);
    $employmentId = $db->real_escape_string($employmentId);

    $sql = "SELECT id
        FROM vaccination 
         WHERE employeeId = (SELECT employeeId FROM employment WHERE id = '$employmentId')
           AND date > DATE_SUB('$date', INTERVAL 6 MONTH);";

    $result = $db->query($sql);

    return $result->num_rows > 0;
}


function fetchAllEmploymentsForEmployee($employeeId)
{
    global $db;
    $sql = "SELECT employment.id as id, firstName , lastName, startDate, endDate, facility.name as facilityName
        FROM employment, employee, facility
        WHERE employee.id = employment.employeeId 
          AND facility.id = employment.facilityId
          AND employee.id = $employeeId
        ORDER BY employee.id";
    return $db->query($sql);
}

function fetchEmployee($id)
{
// fetch user from db
    global $db;
    $id = $db->real_escape_string($id);
    $sql = "SELECT * FROM employee WHERE id = '$id' LIMIT 1";
    $result = $db->query($sql);
    if (!$result) {
// query did not work redirect
        header("Location: schedule.php?message=dbError");
        die();
    }
    if ($result->num_rows != 1) {
        header("Location: schedule.php?message=notFound");
        die();
    }
    return $result->fetch_assoc();
}