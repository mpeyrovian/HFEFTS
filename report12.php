<?php

require "inc/report-function.inc.php"; // configuration file
connectDB(); // Connect to database

// TODO: Transfer to report-functions:
function fetchAllFacilitiesForReport12X() {
    global $db;
    $sql = "SELECT id, name FROM facility";
    return $db->query($sql);
}

function fetchFacilityHoursForReport12X($facilityName, $startDate, $endDate)
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
        and   s.date >= '$startDate'
        and   s.date <= '$endDate'
        and   f.name like '%$facilityName%'
        GROUP BY r.role;
        ";
    return $db->query($sql);
}


$facilityName = isset($_GET['facilityName']) ? $_GET['facilityName'] : "";
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : "1900-01-01";
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : "2100-01-01";

$results = fetchFacilityHoursForReport12X($facilityName, $startDate, $endDate);


global $db;
if (!$results) die("Error fetching items from DB: " . $db->error);
$pageTitle = "Report 12 - Facility Scheduled Hours"; // Dynamic page title
include "inc/header.inc.php"; // header
?>
    <div class="row mt-4">
        <div class="col-12">
            <h2><?= $pageTitle ?></h2>
            <h6 class="text-justify">For a given facility, give the total hours scheduled for every role during a specific period. Results should be displayed in ascending order by role.</h6>
            <hr>
            <?php displayErrors(); ?>
        </div>

        <div class="col-12 mb-3">
            <form action="" method="GET" class="form-inline border p-2">

                <div class="form-group pl-2">
                    <label for="facilityName" class="pr-2">Facility Name:</label>
                    <input type="text" class="form-control" name="facilityName" id="facilityName" required>
                </div>

                <div class="form-group">
                    <label for="startDate">Start Date:</label>
                    <input type="date" class="form-control" name="startDate" id="startDate" required
                           value="">
                </div>

                <div class="form-group">
                    <label for="endDate">End Date:</label>
                    <input type="date" class="form-control" name="endDate" id="endDate" required
                           value="">
                </div>

                <button type="submit" class="btn btn-primary ml-2">Search</button>
            </form>
        </div>

        <div class="table-responsive col-12">
            <table class="table table-hover table-striped table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th>Role</th>
                    <th>Scheduled Hours</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $results->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['role']; ?></td>
                        <td><?= $row['duration']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

<?php include "inc/footer.inc.php"; //footer ?>