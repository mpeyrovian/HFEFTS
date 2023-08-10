<?php
require "inc/function.inc.php";

function fetchAllEmployeesBySearch($firstName, $lastName, $medicareCardNo, $role)
{
    global $db;

    $firstName = $db->real_escape_string($firstName);
    $lastName = $db->real_escape_string($lastName);
    $medicareCardNo = $db->real_escape_string($medicareCardNo);
    $role = $db->real_escape_string($role);

    $sql = "SELECT employee.id as id, firstName, lastName, dateOfBirth, medicareCardNo, phone, citizenship, email, role, address, postal_code.postalCode, city, province 
    FROM employee 
    JOIN employee_role ON employee.roleId = employee_role.id
    JOIN address ON employee.addressId = address.id
    JOIN postal_code ON address.postalCodeId = postal_code.id
    JOIN city ON postal_code.cityId = city.id
    WHERE firstName like '%$firstName%'
    AND lastName like '%$lastName%'
    AND medicareCardNo like '%$medicareCardNo%'
    AND role like '%$role%'
    ORDER BY employee.id ASC";

    return $db->query($sql);
}

function fetchAllEmployeesByFacility($facilityName)
{
    global $db;
    $facilityName = $db->real_escape_string($facilityName);

    $sql = "SELECT employee.id as id, firstName, lastName, dateOfBirth, medicareCardNo, employee.phone, citizenship, email,
       address, postal_code.postalCode, city, province, startDate, facility.name as facilityName, employee_role.role
    FROM employee 
    JOIN employee_role ON employee.roleId = employee_role.id
    JOIN address ON employee.addressId = address.id
    JOIN postal_code ON address.postalCodeId = postal_code.id
    JOIN city ON postal_code.cityId = city.id
    JOIN employment ON employee.id = employment.employeeId
    JOIN facility ON employment.facilityId = facility.id
    WHERE facility.name like '%$facilityName%'
    ORDER BY employee_role.role ASC, firstName ASC, lastName ASC";

    return $db->query($sql);
}

function fetchEmployee($id)
{
// fetch user from db
    global $db;
    $id = $db->real_escape_string($id);
    $sql = "SELECT employee.id as id, firstName, lastName, dateOfBirth, medicareCardNo, phone, citizenship, email, roleId, address, postal_code.postalCode, city, province 
        FROM employee 
        JOIN address ON employee.addressId = address.id
        JOIN postal_code ON address.postalCodeId = postal_code.id
        JOIN city ON postal_code.cityId = city.id
        WHERE employee.id = '$id' LIMIT 1";
    $result = $db->query($sql);
    if (!$result) {
// query did not work redirect
        header("Location: employee.php?message=dbError");
        die();
    }
    if ($result->num_rows != 1) {
        header("Location: employee.php?message=notFound");
        die();
    }
    return $result->fetch_assoc();
}

function deleteEmployee($id)
{
    global $db;
    $id = $db->real_escape_string($id);
    $sql = "DELETE FROM employee WHERE id = '$id' LIMIT 1";
    return $db->query($sql);
}

function updateEmployee($firstName, $lastName, $dateOfBirth, $medicareCardNo, $phone, $address, $postalCode, $city, $province, $citizenship, $email, $roleId, $id)
{
    global $db;
    $id = $db->real_escape_string($id);
    $firstName = $db->real_escape_string($firstName);
    $lastName = $db->real_escape_string($lastName);
    $dateOfBirth = $db->real_escape_string($dateOfBirth);
    $medicareCardNo = $db->real_escape_string($medicareCardNo);
    $roleId = $db->real_escape_string($roleId);
    $phone = $db->real_escape_string($phone);
    $address = $db->real_escape_string($address);
    $postalCode = $db->real_escape_string($postalCode);
    $city = $db->real_escape_string($city);
    $province = $db->real_escape_string($province);
    $citizenship = $db->real_escape_string($citizenship);
    $email = $db->real_escape_string($email);

    $addressId = fetchOrCreateAddress($city, $province, $postalCode, $address);

    $sql = "UPDATE employee SET 
                    firstName='$firstName', 
                    lastName='$lastName', 
                    dateOfBirth='$dateOfBirth',
                    medicareCardNo='$medicareCardNo', 
                    roleId=$roleId,
                    phone='$phone', 
                    addressId='$addressId',
                    citizenship='$citizenship', 
                    email='$email'
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

function createEmployee($firstName, $lastName, $dateOfBirth, $medicareCardNo, $phone, $address, $postalCode, $city, $province, $citizenship, $email, $roleId)
{
    global $db;
    //$id = $db->real_escape_string($id);
    $firstName = $db->real_escape_string($firstName);
    $lastName = $db->real_escape_string($lastName);
    $dateOfBirth = $db->real_escape_string($dateOfBirth);
    $medicareCardNo = $db->real_escape_string($medicareCardNo);
    $roleId = $db->real_escape_string($roleId);
    $phone = $db->real_escape_string($phone);
    $address = $db->real_escape_string($address);
    $postalCode = $db->real_escape_string($postalCode);
    $city = $db->real_escape_string($city);
    $province = $db->real_escape_string($province);
    $citizenship = $db->real_escape_string($citizenship);
    $email = $db->real_escape_string($email);

    $addressId = fetchOrCreateAddress($city, $province, $postalCode, $address);

    $sql = "INSERT INTO employee (firstName, lastName, dateOfBirth, medicareCardNo, roleId, phone, addressId, citizenship, email)
            VALUES ('$firstName', '$lastName', '$dateOfBirth', '$medicareCardNo', '$roleId', '$phone', '$addressId', '$citizenship', '$email')";
    $db->query($sql);
    return $db->insert_id;
}

function fetchAllEmployeeRoles() {
    global $db;

    $sql = "SELECT * FROM employee_role ORDER BY id ASC";

    return $db->query($sql);
}

function fetchDoctorsAndNursesByFacilityInLastTwoWeeks($facilityName) {
    global $db;
    $facilityName = $db->real_escape_string($facilityName);

    $sql = "SELECT employee.id as id, firstName, lastName, employee_role.role, facility.name as facilityName,
            schedule.date, schedule.isCancelled
            FROM employee
                JOIN employment ON employee.id = employment.employeeId
                JOIN schedule ON schedule.employmentId = employment.id
                JOIN employee_role ON employee.roleId = employee_role.id
                JOIN facility ON employment.facilityId = facility.id
            WHERE facility.name like '%$facilityName%'
                AND (employee_role.role = 'Doctor' OR employee_role.role = 'Nurse')
                AND schedule.isCancelled = 0
                AND schedule.date >= CURDATE() - 14
            ORDER BY employee_role.role ASC, firstName ASC";

    return $db->query($sql);
}

function fetchAllEmployeesByRoleAndProvince($role, $province)
{
    global $db;
    $role = $db->real_escape_string($role);
    $province = $db->real_escape_string($province);

    $sql = "SELECT employee.id as id, firstName, lastName, city, province, 
            employee_role.role as role, COUNT(DISTINCT facility.id) as numberOfFacilities
    FROM employee 
        JOIN employee_role ON employee.roleId = employee_role.id
        JOIN address ON employee.addressId = address.id
        JOIN postal_code ON address.postalCodeId = postal_code.id
        JOIN city ON postal_code.cityId = city.id
        JOIN employment ON employee.id = employment.employeeId
        JOIN facility ON employment.facilityId = facility.id
    WHERE employee_role.role like '%$role%'
        AND province like '%$province%'
    GROUP BY employee.id, city
    ORDER BY city ASC, numberOfFacilities DESC ";

    return $db->query($sql);
}


function fetchByLackOfInfection($infectionType)
{
    global $db;
    $infectionType = $db->real_escape_string($infectionType);

    $sql = "SELECT employee.id as id, firstName, lastName, employment.startDate as startDate, employee_role.role as role, 
		dateOfBirth, email, SUM(TIMESTAMPDIFF(HOUR, startTime, endTime)) as TotalHours
    FROM employee 
        JOIN employee_role ON employee.roleId = employee_role.id
        JOIN employment ON employee.id = employment.employeeId
        JOIN infection ON infection.employeeId = employee.id
        JOIN infection_type ON infection_type.id = infection.infectionTypeID
        JOIN schedule ON schedule.employmentID = employment.id
    WHERE (employee_role.role = 'Doctor' or employee_role.role = 'Nurse')
        AND employment.endDate IS NULL
        AND infection_type.type NOT LIKE '%$infectionType%'
	GROUP BY employee.id, employment.startDate, role, firstName, lastName
    ORDER BY role ASC, firstName ASC, lastName ASC";

    return $db->query($sql);
}

function fetchByTimesInfected($infectionType, $timesInfected)
{
    global $db;
    $infectionType = $db->real_escape_string($infectionType);

    $sql = "SELECT employee.id as id, firstName, lastName, employment.startDate as startDate, employee_role.role as role, 
		dateOfBirth, email, SUM(TIMESTAMPDIFF(HOUR, startTime, endTime)) as TotalHours, 
		COUNT(infection_type.type) as timesInfected
    FROM employee 
        JOIN employee_role ON employee.roleId = employee_role.id
        JOIN employment ON employee.id = employment.employeeId
        JOIN infection ON infection.employeeId = employee.id
        JOIN infection_type ON infection_type.id = infection.infectionTypeID
        JOIN schedule ON schedule.employmentID = employment.id
    WHERE (employee_role.role = 'Doctor' or employee_role.role = 'Nurse')
        AND employment.endDate IS NULL
        AND infection_type.type LIKE '%$infectionType%'
	GROUP BY employee.id, infection_type.type, employment.startDate, role, firstName, lastName
	HAVING COUNT(infection_type.type) >= '$timesInfected'
    ORDER BY role ASC, firstName ASC, lastName ASC";

    return $db->query($sql);
}