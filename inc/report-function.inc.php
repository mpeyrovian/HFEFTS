<?php
require "inc/function.inc.php";

function fetchEmails($facilityName)
{
    global $db;
    $facilityName = $db->real_escape_string($facilityName);

    $sql = "SELECT * FROM email_sent 
    JOIN facility ON email_sent.facility_id = facility.id
    WHERE facility.name like '%$facilityName%'
    ORDER BY date";

    return $db->query($sql);
}

function fetchDoctorsInfected2Weeks()
{
    global $db;

    $sql = "SELECT e.id, e.firstName, e.lastName, infection.date, facility.name
    FROM employee e
    JOIN employee_role on e.roleId = employee_role.id
    JOIN infection on e.id = infection.employeeId
    JOIN infection_type on infection.infectionTypeId = infection_type.id
    LEFT JOIN employment on e.id = employment.employeeId
    JOIN facility on employment.facilityId = facility.id
    WHERE role='Doctor'
      AND infection_type.type='COVID-19'
      AND infection.date > DATE_SUB(CURDATE(), INTERVAL 2 WEEK)
      AND employment.endDate is null
    ORDER BY facility.name, e.firstName";

    return $db->query($sql);
}
function fetchFacilities()
{
    global $db;

    $sql = "
SELECT a.name, b.address, d.city, d.province, c.postalCode, a.phone, a.webAddress, g.type, a.capacity, COUNT(f.id) as currentEmployeesNumber
FROM facility a
LEFT JOIN facility_type g ON a.typeId = g.id
LEFT JOIN address b ON a.addressId = b.id
LEFT JOIN postal_code c ON c.id = b.postalCodeId
LEFT JOIN city d ON c.cityId = d.id
INNER JOIN employee e ON a.generalManagerId = e.id
LEFT JOIN employment f ON a.id = f.facilityId AND  f.endDate is null
GROUP BY a.name, b.address, d.city, d.province, c.postalCode, a.phone, a.webAddress, g.type, a.capacity
ORDER BY d.province, d.city, g.type, currentEmployeesNumber
";

    return $db->query($sql);
}

function fetchFacilitiesForReport12($facilityId, $startDate, $endDate)
{
    global $db;

    $sql = "
        SELECT r.role, SUM(TIMESTAMPDIFF(HOUR, s.startTime, s.endTime)) as duration
        FROM facility f, schedule s, employee e, employment et, employee_role r
        Where f.id = et.facilityId
        and	  et.employeeId = e.id
        and	  e.roleId = r.id
        and   et.id = s.employmentId
        and	  s.isCancelled = '0'
        and s.date >= $startDate
        and s.date <= $endDate

        GROUP BY r.role
        ";
    print_r($sql);
//        die();
    return $db->query($sql);
}

function fetchAllFacilitiesForReport12() {
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

function fetchAllEmployees()
{
    global $db;
    $sql = "SELECT * FROM employee ORDER BY id ASC";
    return $db->query($sql);
}
function fetchEmployeeSchedule($employeeId,$startDate,$endDate)
{
    global $db;
    $employeeId = $db->real_escape_string($employeeId);
    $startDate = $db->real_escape_string($startDate);
    $endDate = $db->real_escape_string($endDate);


    $sql = "SELECT
    E.Id as employeeId,
    F.name as facilityName,
    S.date as date,
    S.startTime as startTime,
    S.endTime as endTime
 
FROM
    employee E,
    employment EM,
    facility F,
    schedule S
WHERE
    E.id = $employeeId 
    AND E.id = EM.employeeId
    AND EM.id = S.employmentId
    AND 
    (Case when null is null and null is null then TRUE
     When $startDate is null then  S.date <= $endDate
	 	when $endDate is null then S.date >= $startDate        
        else S.date BETWEEN $startDate AND $endDate
	End)
    AND EM.facilityId = F.id
ORDER BY
    facilityName ASC,
    date ASC,
    startTime ASC;
";

    return $db->query($sql);
}


function fetchNurseHoursSummary()
{
    global $db;



    $sql = "SELECT 
    e.firstName,
    e.lastName,
    MIN(emp.startDate) AS firstDayOfWork,
    e.dateOfBirth,
    e.email,
    SUM(TIMESTAMPDIFF(HOUR, s.startTime, s.endTime)) AS totalHoursScheduled
FROM 
    mydb.employee AS e,
    mydb.employment AS emp,
    mydb.schedule AS s
WHERE
    e.id = emp.employeeId
    AND emp.id = s.employmentId
    AND e.roleId = 1
    AND emp.endDate IS NULL
    AND s.isCancelled = 0
GROUP BY
    e.id
HAVING
    totalHoursScheduled = (
        SELECT 
            MAX(total_hours)
        FROM (
            SELECT 
                employeeId,
                SUM(TIMESTAMPDIFF(HOUR, startTime, endTime)) AS total_hours
            FROM 
                mydb.employment AS emp2,
                mydb.schedule AS s2,
                mydb.employee AS e2
            WHERE
                e2.id = emp2.employeeId
                AND emp2.id = s2.employmentId
                AND e2.roleId = 1
                AND emp2.endDate IS NULL
                AND s2.isCancelled = 0
            GROUP BY
                employeeId
        ) AS max_hours
    )
";

    return $db->query($sql);
}

