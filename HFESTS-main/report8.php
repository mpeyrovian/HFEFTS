<?php
require "inc/report-function.inc.php"; // configuration file
connectDB(); // Connect to database

// Todo: Transfer to report-functions
function fetchEmployeeScheduleForReport8($employeeId, $startDate, $endDate)
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
        facilityName, date, startTime;
";
    return $db->query($sql);
}
// TODO: END


$results = array('date' => "", 'facilityName' => "", 'startTime' => "", 'endTime' => "");

if (isset($_GET['employeeId']) && isset($_GET['startDate']) && isset($_GET['endDate'])) {
    $results = fetchEmployeeScheduleForReport8($_GET['employeeId'], $_GET['startDate'], $_GET['endDate']);
} else {
   $results = null;
}

$employeeArray = fetchAllEmployees();
global $db;
//if (!$results) die("Error fetching items from DB: " . $db->error);
$pageTitle = "Report 8 - Employee Schedule"; // Dynamic page title
include "inc/header.inc.php"; // header
?>
    <div class="row mt-4">
        <div class="col-12">
            <h2><?= $pageTitle ?></h2>
            <h6 class="text-justify">For a given employee, get the details of all the schedules they have been
                scheduled during a specific period of time. Details include facility name, day
                of the year, start time and end time. Results should be displayed sorted in
                ascending order by facility name, then by day of the year, the by start time.
            </h6>
            <hr>
            <?php displayErrors(); ?>
        </div>
        <div class="col-12 mb-3">
            <form action="" method="GET" class="form-inline border p-2">
                <div class="form-group">
                    <label for="employeeId">Employee: </label>
                    <select class="form-control" name="employeeId" id="employeeId" required>
                        <option value="" selected disabled>Select</option>
                        <?php foreach ($employeeArray as $employee) { ?>
                            <option value="<?= $employee['id'] ?>">
                                <?= $employee['medicareCardNo'] . " - " . $employee['firstName'] . ' ' . $employee['lastName'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="startDate">Start Date: </label>
                    <input type="date" class="form-control" name="startDate" id="startDate" required>
                </div>

                <div class="form-group">
                    <label for="endDate">End Date: </label>
                    <input type="date" class="form-control" name="endDate" id="endDate" required>
                </div>
                <button type="submit" class="btn btn-primary ml-2">Search</button>
            </form>
        </div>

        <div class="table-responsive col-12">
            <table class="table table-hover table-striped table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th>Facility Name</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if ($results) {
                while ($row = $results->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['facilityName']; ?></td>
                        <td><?= $row['date']; ?></td>
                        <td><?= $row['startTime']; ?></td>
                        <td><?= $row['endTime']; ?></td>
                    </tr>
                <?php }
                }?>
                </tbody>
            </table>
        </div>
    </div>

<?php include "inc/footer.inc.php"; //footer ?>