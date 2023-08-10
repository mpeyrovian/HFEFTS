<?php
require "inc/function.inc.php";

function fetchAllVaccinationsBySearch($firstName, $lastName, $medicareCardNo, $type)
{
    global $db;

    $firstName = $db->real_escape_string($firstName);
    $lastName = $db->real_escape_string($lastName);
    $medicareCardNo = $db->real_escape_string($medicareCardNo);
    $type = $db->real_escape_string($type);

    $sql = "SELECT vaccination.id as id, employeeId, firstName, lastName, date, type, medicareCardNo, dose, name as facilityName
            FROM vaccination, employee, vaccination_type, facility
            WHERE employee.id = vaccination.employeeId
            AND vaccination_type.id = vaccination.typeId
            AND facility.id = vaccination.facilityId
            AND firstName like '%$firstName%'
            AND lastName like '%$lastName%'
            AND medicareCardNo like '%$medicareCardNo%'
            AND type like '%$type%'
            ORDER BY vaccination.id ASC";
    return $db->query($sql);
}

function fetchVaccination($id)
{
// fetch user from db
    global $db;
    $id = $db->real_escape_string($id);
    $sql = "SELECT vaccination.id as id, employeeId, date, vaccination.typeId, dose, facility.id as facilityId
         FROM vaccination, employee, vaccination_type, facility
         WHERE employee.id = vaccination.employeeId
         AND vaccination_type.id = vaccination.typeId
         AND facility.id = vaccination.facilityId
         AND vaccination.id = '$id'";
    $result = $db->query($sql);
    if (!$result) {
        // query did not work redirect
        header("Location: vaccination.php?message=dbError");
        die();
    }
    if ($result->num_rows != 1) {
        header("Location: vaccination.php?message=notFound");
        die();
    }
    return $result->fetch_assoc();
}

function deleteVaccination($id)
{
    global $db;
    $id = $db->real_escape_string($id);
    $sql = "DELETE FROM vaccination WHERE id = '$id'";
    $db->query($sql);
}

function updateVaccination($employeeId, $date, $typeId, $dose, $facilityId, $id)
{
    global $db;
    $id = $db->real_escape_string($id);
    $employeeId = $db->real_escape_string($employeeId);
    $date = $db->real_escape_string($date);
    $typeId = $db->real_escape_string($typeId);
    $dose = $db->real_escape_string($dose);
    $facilityId = $db->real_escape_string($facilityId);
    $sql = "UPDATE vaccination SET employeeId='$employeeId', date='$date', typeId='$typeId', dose='$dose', facilityId='$facilityId' WHERE id ='$id'";
    $db->query($sql);
}

function createVaccination($employeeId, $date, $typeId, $dose, $facilityId)
{
    global $db;
    $employeeId = $db->real_escape_string($employeeId);
    $date = $db->real_escape_string($date);
    $typeId = $db->real_escape_string($typeId);
    $dose = $db->real_escape_string($dose);
    $facilityId = $db->real_escape_string($facilityId);
    $sql = "INSERT INTO vaccination (employeeId, date, typeId, dose, facilityId) VALUES ('$employeeId', '$date', '$typeId', '$dose', '$facilityId')";

    $db->query($sql);

    return $db->insert_id;
}

function fetchAllEmployees()
{
    global $db;
    $sql = "SELECT * FROM employee ORDER BY id ASC";
    return $db->query($sql);
}

function fetchAllVaccinationTypes()
{
    global $db;
    $sql = "SELECT * FROM vaccination_type ORDER BY type ASC";
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
