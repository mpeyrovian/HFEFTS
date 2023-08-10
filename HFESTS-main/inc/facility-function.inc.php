<?php
require "inc/function.inc.php";
connectDB(); // Connect to database

function fetchAllFacilitiesBySearch($name, $facilityType)
{
    global $db;

    $name = $db->real_escape_string($name);
    $facilityType = $db->real_escape_string($facilityType);

    $sql = "SELECT facility.id as id, facility.name, facility.phone, facility.webAddress, facility_type.id as facilityTypeId, facility_type.type as facilityType,
                facility.capacity, address.address as address, postal_code.postalCode as postalCode, city.city as city, 
                city.province as province,generalManagerId, concat(medicareCardNo, ' - ', firstName,' ',lastName) as generalManagerName
        FROM facility, facility_type, address, postal_code, city,employee
        WHERE facility.typeId = facility_type.id AND facility.addressId = address.id 
          AND address.postalCodeId = postal_code.id AND postal_code.cityId = city.id
          AND employee.id = facility.generalManagerId
          AND name like '%$name%'
          AND facility_type.type like '%$facilityType%'
        ORDER BY name";

    return $db->query($sql);
}

function fetchFacility($id)
{
// fetch user from db
    global $db;
    $id = $db->real_escape_string($id);
    $sql = "SELECT facility.id as id, facility.name, facility.phone, facility.webAddress, facility.typeId as facilityTypeId,
    facility.capacity, address, postal_code.postalCode, city, province, generalManagerId, concat(medicareCardNo, ' - ', firstName,' ',lastName) as generalManagerName
FROM facility, address, postal_code, city,employee
WHERE facility.addressId = address.id 
  AND address.postalCodeId = postal_code.id 
  AND postal_code.cityId = city.id
  AND employee.id = facility.generalManagerId
    AND facility.id = '$id'";

    $result = $db->query($sql);
    if (!$result) {
// query did not work redirect
        header("Location: facility.php?message=dbError");
        die();
    }
    if ($result->num_rows != 1) {
        header("Location: facility.php?message=notFound");
        die();
    }
    return $result->fetch_assoc();
}

/*
function deleteFacility($id)
{
    global $db;
    $id = $db->real_escape_string($id);
    $sql = "DELETE FROM facility WHERE id = '$id' LIMIT 1";
    $result = $db->query($sql);
    return $result;
}

function updateFacility($name,$phone , $webAddress, $facilityTypeId, $capacity, $address, $postalCode, $city,$province, $id)
{
    global $db;
    $id = $db->real_escape_string($id);
    $name = $db->real_escape_string($name);
    $phone = $db->real_escape_string($phone);
    $capacity = $db->real_escape_string($capacity);
    $webAddress = $db->real_escape_string($webAddress);
    $facilityTypeId = $db->real_escape_string($facilityTypeId);
    $capacity = $db->real_escape_string($capacity);
    $address = $db->real_escape_string($address);
    $postalCode = $db->real_escape_string($postalCode);
    $city = $db->real_escape_string($city);
    $province = $db->real_escape_string($province);


    $sql = "
INSERT INTO city(city,province) select '$city','$province'
WHERE NOT EXISTS ( SELECT * FROM city 
                   WHERE city = '$city'
                   or province= '$province');
INSERT INTO postal_code(postalCode, cityId) select '$postalCode',Id
From city
WHERE  city='$city' AND
NOT EXISTS ( SELECT * FROM postal_code 
                   WHERE postalCode = '$postalCode'
);
INSERT INTO address(address, postalCodeID) select '$address',Id
From postal_code
WHERE  postalCode='$postalCode' AND
NOT EXISTS ( SELECT * FROM address 
                   WHERE address = '$address'
);
UPDATE facility SET name='$name', phone=$phone, capacity=$capacity, webAddress='$webAddress',
typeId='$facilityTypeId', capacity='$capacity'
WHERE id ='$id';
 ";
    $db->query($sql);
}

function createFacility($name,$phone , $webAddress, $facilityTypeId, $capacity, $address, $postalCode, $city,$province)
{
    global $db;
    $name = $db->real_escape_string($name);
    $phone = $db->real_escape_string($phone);
    $capacity = $db->real_escape_string($capacity);
    $webAddress = $db->real_escape_string($webAddress);
    $facilityTypeId = $db->real_escape_string($facilityTypeId);
    $capacity = $db->real_escape_string($capacity);
    $address = $db->real_escape_string($address);
    $postalCode = $db->real_escape_string($postalCode);
    $city = $db->real_escape_string($city);
    $province = $db->real_escape_string($province);

    $sql = "
INSERT INTO city(city,province) select '$city','$province'
WHERE NOT EXISTS ( SELECT * FROM city 
                   WHERE city = '$city'
                   or province= '$province');
INSERT INTO postal_code(postalCode, cityId) select '$postalCode',Id
From city
WHERE  city='$city' AND
NOT EXISTS ( SELECT * FROM postal_code 
                   WHERE postalCode = '$postalCode'
);
INSERT INTO address(address, postalCodeID) select '$address',Id
From postal_code
WHERE  postalCode='$postalCode' AND
NOT EXISTS ( SELECT * FROM address 
                   WHERE address = '$address'
);
INSERT INTO facility (name,phone ,capacity, webAddress, typeId,addressId)
SELECT '$name','$phone', '$capacity', '$webAddress', '$facilityTypeId',
 (
select Id
From address
Where address='$address'
);
";
    $db->query($sql);
    return $db->insert_id;
}
function fetchAllFacilityTypes()
{
    global $db;
    $sql = "SELECT * FROM facility_type ORDER BY type ASC";
    return $db->query($sql);
}

function fetchFacilityType($id)
{
// fetch user from db
    global $db;
    $id = $db->real_escape_string($id);
    $sql = "SELECT * FROM facility_type WHERE id = '$id' LIMIT 1";
    $result = $db->query($sql);
    if (!$result) {
        // query did not work redirect
        header("Location: facility-type.php?message=dbError");
        die();
    }
    if ($result->num_rows != 1) {
        header("Location: facility-type.php?message=notFound");
        die();
    }
    return $result->fetch_assoc();
}

function deleteFacilityType($id)
{
    global $db;
    $id = $db->real_escape_string($id);
    $sql = "DELETE FROM Facility_type WHERE id = '$id' LIMIT 1";
    return $db->query($sql); // TODO: do the same for all pages
}

function updatefacilityType($type, $id)
{
    global $db;
    $id = $db->real_escape_string($id);
    $type = $db->real_escape_string($type);
    $sql = "UPDATE facility_type SET type='$type' WHERE id ='$id' LIMIT 1";
    $db->query($sql);
}

function createfacilityType($type)
{
    global $db;
    $type = $db->real_escape_string($type);
    $sql = "INSERT INTO facility_type (type) VALUES ('$type')";
    $db->query($sql);
}

*/

function fetchAdminEmployees()
{
    global $db;

    $sql = "SELECT employee.id , concat(medicareCardNo,' - ', firstName, ' ', lastName) as fullName 
    FROM employee 
    Where employee.roleId =6
    ORDER BY employee.id ASC";

    return $db->query($sql);
}

function deleteFacility($id)
{
    global $db;
    $id = $db->real_escape_string($id);
    $sql = "DELETE FROM facility WHERE id = '$id'";
    return $db->query($sql);
}

function updateFacility($name, $phone, $webAddress, $facilityTypeId, $capacity, $address, $postalCode, $city, $province, $generalManagerId, $id)
{
    global $db;
    $id = $db->real_escape_string($id);
    $name = $db->real_escape_string($name);
    $phone = $db->real_escape_string($phone);
    $capacity = $db->real_escape_string($capacity);
    $webAddress = $db->real_escape_string($webAddress);
    $facilityTypeId = $db->real_escape_string($facilityTypeId);
    $capacity = $db->real_escape_string($capacity);
    $address = $db->real_escape_string($address);
    $postalCode = $db->real_escape_string($postalCode);
    $city = $db->real_escape_string($city);
    $province = $db->real_escape_string($province);
    $generalManagerId = $db->real_escape_string($generalManagerId);

    $addressId = fetchOrCreateAddress($city, $province, $postalCode, $address);

    $sql = "UPDATE facility SET 
                    name='$name', 
                    phone=$phone, 
                    capacity=$capacity, 
                    webAddress='$webAddress',
                    typeId='$facilityTypeId', 
                    generalManagerId=$generalManagerId,
                    addressId=$addressId
            WHERE id ='$id'";

    $db->query($sql);
}

/**
 * @param $city
 * @param $province
 * @param $db
 * @param $postalCode
 * @param $address
 * @return array
 */
function fetchOrCreateAddress($city, $province, $postalCode, $address)
{

    global $db;
    $sql = "SELECT id FROM city WHERE city='$city' AND province='$province'";
    $cityId = $db->query($sql);
    if ($cityId->num_rows == 0) {
        $sql = "INSERT INTO city (`city`, `province`) VALUES('$city', '$province')";
        $db->query($sql);
        $sql = "SELECT LAST_INSERT_ID() as id";
        $cityId = $db->query($sql);
    }
    $cityId = $cityId->fetch_assoc()["id"];

    $sql = "SELECT id FROM postal_code WHERE postalCode='$postalCode' AND cityId='$cityId'";
    $postalCodeId = $db->query($sql);
    if ($postalCodeId->num_rows == 0) {
        $sql = "INSERT INTO postal_code (`postalCode`, `cityId`) VALUES('$postalCode', '$cityId')";
        $db->query($sql);
        $sql = "SELECT LAST_INSERT_ID() as id";
        $postalCodeId = $db->query($sql);
    }
    $postalCodeId = ($postalCodeId->fetch_assoc())["id"];

    $sql = "SELECT id FROM address WHERE address='$address' AND postalCodeId='$postalCodeId'";
    $addressId = $db->query($sql);
    if ($addressId->num_rows == 0) {
        $sql = "INSERT INTO address (`address`, `postalCodeId`) VALUES('$address', '$postalCodeId')";
        $db->query($sql);
        $sql = "SELECT LAST_INSERT_ID() as id";
        $addressId = $db->query($sql);
    }
    $addressId = ($addressId->fetch_assoc())["id"];

    return $addressId;
}

function createFacility($name, $phone, $webAddress, $facilityTypeId, $capacity, $address, $postalCode, $city, $province, $generalManagerId)
{
    global $db;
    $name = $db->real_escape_string($name);
    $phone = $db->real_escape_string($phone);
    $capacity = $db->real_escape_string($capacity);
    $webAddress = $db->real_escape_string($webAddress);
    $facilityTypeId = $db->real_escape_string($facilityTypeId);
    $capacity = $db->real_escape_string($capacity);
    $address = $db->real_escape_string($address);
    $postalCode = $db->real_escape_string($postalCode);
    $city = $db->real_escape_string($city);
    $province = $db->real_escape_string($province);
    $generalManagerId = $db->real_escape_string($generalManagerId);

    $addressId = fetchOrCreateAddress($city, $province, $postalCode, $address);

    $sql = "INSERT INTO facility (name, phone , webAddress, typeId,generalManagerId, capacity,addressId)
            VALUES ('$name','$phone' , '$webAddress', '$facilityTypeId','$generalManagerId' ,'$capacity', '$addressId')";
    $db->query($sql);
    return $db->insert_id;
}

function fetchAllFacilityTypes()
{
    global $db;

    $sql = "SELECT * FROM facility_type ORDER BY id ASC";

    return $db->query($sql);
}

function fetchFacilityType($id)
{
// fetch user from db
    global $db;
    $id = $db->real_escape_string($id);
    $sql = "SELECT * FROM facility_type WHERE id = '$id' LIMIT 1";
    $result = $db->query($sql);
    if (!$result) {
        // query did not work redirect
        header("Location: facility-type.php?message=dbError");
        die();
    }
    if ($result->num_rows != 1) {
        header("Location: facility-type.php?message=notFound");
        die();
    }
    return $result->fetch_assoc();
}

function deleteFacilityType($id)
{
    global $db;
    $id = $db->real_escape_string($id);
    $sql = "DELETE FROM facility_type WHERE id = '$id' LIMIT 1";
    return $db->query($sql); // TODO: do the same for all pages
}

function updateFacilityType($type, $id)
{
    global $db;
    $id = $db->real_escape_string($id);
    $type = $db->real_escape_string($type);
    $sql = "UPDATE facility_type SET type='$type' WHERE id ='$id' LIMIT 1";
    $db->query($sql);
}

function createFacilityType($type)
{
    global $db;
    $type = $db->real_escape_string($type);
    $sql = "INSERT INTO facility_type (type) VALUES ('$type')";
    $db->query($sql);
}

function fetchAllFacilitiesByNumberOfInfectedEmployees($infectionType)
{
    global $db;

    $infectionType = $db->real_escape_string($infectionType);

    $sql = "SELECT COUNT(employee.id) as numberOfInfectedEmployees, facility.name, city.province, facility.capacity
            FROM employee
                JOIN employment ON employment.employeeId = employee.id
                JOIN facility ON facility.id = employment.facilityId
                JOIN infection ON employee.id = infection.employeeId
                JOIN infection_type ON infection_type.id = infection.infectionTypeId
                JOIN address ON facility.addressId = address.id
                JOIN postal_code ON address.postalCodeId = postal_code.id
                JOIN city ON city.id = postal_code.cityId
            WHERE infection_type.type LIKE '%$infectionType%'
                AND infection.date >= CURDATE() - 14
            GROUP BY facility.id
            ORDER BY province ASC, numberOfInfectedEmployees DESC";

    return $db->query($sql);
}