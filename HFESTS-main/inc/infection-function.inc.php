<?php
require "inc/function.inc.php";

function fetchAllInfectionsBySearch($firstName, $lastName, $medicareCardNo, $infectionType)
{
    global $db;
    $firstName = $db->real_escape_string($firstName);
    $lastName = $db->real_escape_string($lastName);
    $medicareCardNo = $db->real_escape_string($medicareCardNo);
    $infectionType = $db->real_escape_string($infectionType);

    $sql = "SELECT infection.id as id, medicareCardNo, firstName, lastName, date, type 
            FROM infection, employee, infection_type
            WHERE employee.id = infection.employeeId
            AND infection_type.id = infection.infectionTypeId
            AND firstName like '%$firstName%'
            AND lastName like '%$lastName%'
            AND medicareCardNo like '%$medicareCardNo%'
            AND type like '%$infectionType%'
            ORDER BY infection.id ASC";
    return $db->query($sql);
}

function fetchInfection($id)
{
// fetch user from db
    global $db;
    $id = $db->real_escape_string($id);
    $sql = "SELECT infection.id as id, employeeId, date, infectionTypeId
         FROM infection, employee, infection_type
         WHERE employee.id = infection.employeeId
         AND infection_type.id = infection.infectionTypeId
         AND infection.id = '$id'";
    $result = $db->query($sql);
    if (!$result) {
        // query did not work redirect
        header("Location: infection.php?message=dbError");
        die();
    }
    if ($result->num_rows != 1) {
        header("Location: infection.php?message=notFound");
        die();
    }
    return $result->fetch_assoc();
}

function deleteInfection($id)
{
    global $db;
    $id = $db->real_escape_string($id);
    $sql = "DELETE FROM infection WHERE id = '$id'";
    $db->query($sql);
}

function updateInfection($employeeId, $date, $infectionTypeId, $id)
{
    global $db;
    $id = $db->real_escape_string($id);
    $employeeId = $db->real_escape_string($employeeId);
    $date = $db->real_escape_string($date);
    $infectionTypeId = $db->real_escape_string($infectionTypeId);
    $sql = "UPDATE infection SET employeeId='$employeeId', date='$date', infectionTypeId='$infectionTypeId' WHERE id ='$id'";
    $db->query($sql);
}

function createInfection($employeeId, $date, $infectionTypeId)
{
    global $db;
    $employeeId = $db->real_escape_string($employeeId);
    $date = $db->real_escape_string($date);
    $infectionTypeId = $db->real_escape_string($infectionTypeId);
    $sql = "INSERT INTO infection (employeeId, date, infectionTypeId) VALUES ('$employeeId', '$date', '$infectionTypeId')";
    $db->query($sql);
    return $db->insert_id;
}

function fetchAllEmployees()
{
    global $db;
    $sql = "SELECT * FROM employee ORDER BY id ASC";
    return $db->query($sql);
}

function fetchAllInfectionTypes()
{
    global $db;
    $sql = "SELECT * FROM infection_type ORDER BY type ASC";
    return $db->query($sql);
}
