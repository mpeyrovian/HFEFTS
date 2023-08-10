<?php
require "inc/function.inc.php";

function fetchAllEmployments()
{
    global $db;
    $sql = "SELECT employment.id as id, medicareCardNo, firstName , lastName, startDate, endDate, facility.name as facilityName
        FROM employment, employee, facility
        WHERE employee.id = employment.employeeId AND facility.id = employment.facilityId
        ORDER BY employee.firstName ASC";
    return $db->query($sql);
}

function fetchEmployment($id)
{
    global $db;
    $id = $db->real_escape_string($id);
    $sql = "SELECT * FROM employment WHERE id = '$id' LIMIT 1";
    $result = $db->query($sql);
    if (!$result) {
        header("Location: employment.php?message=dbError");
        die();
    }
    if ($result->num_rows != 1) {
        header("Location: employment.php?message=notFound");
        die();
    }
    return $result->fetch_assoc();
}


function deleteEmployment($id)
{
    global $db;
    $id = $db->real_escape_string($id);
    $sql = "DELETE FROM employment WHERE id = '$id' LIMIT 1";
    return $db->query($sql);
}


function updateEmployment($id, $startDate, $endDate, $employeeId, $facilityId)
{
    global $db;
    $id = $db->real_escape_string($id);
    $startDate = $db->real_escape_string($startDate);
    $endDate = $db->real_escape_string($endDate);
    $employeeId = $db->real_escape_string($employeeId);
    $facilityId = $db->real_escape_string($facilityId);

    if (trim($endDate) == "") {
        $endDate = "null";
    } else {
        $endDate = "'" . $endDate . "'";
    }

    $sql = "UPDATE employment 
            SET startDate='$startDate', endDate=$endDate, employeeId='$employeeId',
            facilityId='$facilityId'
            WHERE employment.id ='$id'";

    $db->query($sql);
}

function createEmployment($startDate, $endDate, $employeeId, $facilityId)
{
    global $db;
    $startDate = $db->real_escape_string($startDate);
    $endDate = $db->real_escape_string($endDate);
    $employeeId = $db->real_escape_string($employeeId);
    $facilityId = $db->real_escape_string($facilityId);

    if (trim($endDate) == "") {
        $endDate = "null";
    } else {
        $endDate = "'" . $endDate . "'";
    }

    $sql = "INSERT INTO employment (startDate, endDate, employeeId, facilityId)
            VALUES ('$startDate', $endDate, '$employeeId', '$facilityId')";
    $db->query($sql);
    return $db->insert_id;
}

function fetchAllEmployees()
{
    global $db;
    $sql = "SELECT * FROM employee ORDER BY id ASC";
    return $db->query($sql);
}

function fetchAllFacilities() {
    global $db;
    $sql = "SELECT facility.id as id, facility.name as name, phone, webAddress, facility_type.type as facilityType,
                capacity, address.address as address, postal_code.postalCode as postalCode, city.city as city, 
                city.province as province
        FROM facility, facility_type, address, postal_code, city
        WHERE facility.typeId = facility_type.id AND facility.addressId = address.id AND
              address.postalCodeId = postal_code.id AND postal_code.cityId = city.id
        ORDER BY name";
    return $db->query($sql);
}
