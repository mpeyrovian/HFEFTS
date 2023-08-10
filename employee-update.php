<?php


require "inc/employee-function.inc.php"; // configuration file
connectDB(); // Connect to database
global $db;
// set initial values
$data = array('firstName' => "", 'lastName' => "", 'dateOfBirth' => "", 'roleId' => "",'medicareCardNo' => "", 'phone' => "", 'address' => "", 'postalCode' => "", 'city' => "", 'province' => "", 'citizenship' => "", 'email' => "", 'id' => "");
$pageTitle = "Add an Employee"; // Dynamic page title

$roleArray = fetchAllEmployeeRoles();

if ($_SERVER['REQUEST_METHOD'] == "GET") { // if GET method used
    if (isset($_GET['id']) && is_numeric($_GET['id'])) { // check if an id was given
        $data = fetchEmployee($_GET['id']);
        //print_r($data);
        if ($_GET['mode'] == "delete") {
            // delete Employee
            $result = deleteEmployee($_GET['id']);
            if (!$result) {
                header("Location: employee.php?message=notremoved&id=" . htmlentities($data['id']));
                die();
            }
            header("Location: employee.php?message=removed&id=" . htmlentities($data['id']));
            die();
        } else if ($_GET['mode'] == "edit") {
            // display 'Edit Employee' form
            $pageTitle = "Edit an Employee";
        } else {
            header("Location: employee.php?message=invalidRequest");
            die();
        }
    } else { // no id provided
        // display 'Add Employee' form
        $pageTitle = "Add an Employee";
    }
} else if ($_SERVER['REQUEST_METHOD'] == "POST") { // if POST method used
    // set info for form in case of error
    $id = isset($_POST['id']) ? $_POST['id'] : "";
    $firstName = isset($_POST['firstName']) ? $_POST['firstName'] : "";
    $lastName = isset($_POST['lastName']) ? $_POST['lastName'] : "";
    $dateOfBirth = isset($_POST['dateOfBirth']) ? $_POST['dateOfBirth'] : "";
    $medicareCardNo = isset($_POST['medicareCardNo']) ? $_POST['medicareCardNo'] : "";
    $roleId = isset($_POST['roleId']) ? $_POST['roleId'] : "";
    $phone = isset($_POST['phone']) ? $_POST['phone'] : "";
    $address = isset($_POST['address']) ? $_POST['address'] : "";
    $postalCode = isset($_POST['postalCode']) ? $_POST['postalCode'] : "";
    $city = isset($_POST['city']) ? $_POST['city'] : "";
    $province = isset($_POST['province']) ? $_POST['province'] : "";
    $citizenship = isset($_POST['citizenship']) ? $_POST['citizenship'] : "";
    $email = isset($_POST['email']) ? $_POST['email'] : "";

    // data validation
    if (isFieldEmpty($firstName) || isFieldEmpty($lastName) || isFieldEmpty($dateOfBirth)
        || isFieldEmpty($medicareCardNo) || isFieldEmpty($phone) || isFieldEmpty($address)
        || isFieldEmpty($postalCode) || isFieldEmpty($city) || isFieldEmpty($province)
        || isFieldEmpty($citizenship) || isFieldEmpty($email) || isFieldEmpty($roleId)) {
        $errorMessage = "All fields are required";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Email is not valid";
    }

    if ($errorMessage == "") { // no validation errors
        // we check for an id, so we know if it's a creation or an update
        if (isset($_POST['id']) && is_numeric($_POST['id'])) { // update
            updateEmployee($firstName, $lastName, $dateOfBirth, $medicareCardNo, $phone, $address, $postalCode, $city, $province, $citizenship, $email, $roleId, $id);
            $action = "updated";
        } else { // create
            $id = createEmployee($firstName, $lastName, $dateOfBirth, $medicareCardNo, $phone, $address, $postalCode, $city, $province, $citizenship, $email, $roleId);
            $action = "created";
        }
        if ( $id == null) {
            header("Location: employee.php?&message=triggerError&errorMsg=" . urlencode($db->error));
            die();
        } else {
        // redirect based on whether employee was created or updated
        header("Location: employee.php?message=$action&id=" . htmlentities($id));
        die();}
    }

    // Validation errors found: display the form plus the previously entered data
    $data = $_POST;
}
include "inc/header.inc.php"; ?>
    <div class="row">
        <div class="col-12">
            <h2><?= $pageTitle ?></h2>
            <hr class="mb-4">
        </div>
    </div>
<?php displayErrors(); ?>
    <div class="row">
        <div class="col-md-12 p-3">
            <form action="" method="POST">
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" class="form-control" name="firstName" id="firstName"
                           value="<?= $data['firstName']; ?>">
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" class="form-control" name="lastName" id="lastName"
                           value="<?= $data['lastName']; ?>">
                </div>
                <div class="form-group">
                    <label for="dateOfBirth">Date of Birth</label>
                    <input type="date" class="form-control" name="dateOfBirth" id="dateOfBirth"
                           value="<?= $data['dateOfBirth']; ?>">
                </div>
                <div class="form-group">
                    <label for="medicareCardNo">Medicare Card Number</label>
                    <input type="text" class="form-control" name="medicareCardNo" id="medicareCardNo"
                           value="<?= $data['medicareCardNo']; ?>">
                </div>
                <div class="form-group">
                    <label for="roleId">Role</label>
                    <select class="form-control" name="roleId" id="roleId">
                        <?php foreach ($roleArray as $role) { ?>
                            <option value="<?= $role['id'] ?>" <?= ($role['id'] == $data['roleId']) ? "selected" : "" ?>><?= $role['role'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" name="phone" id="phone" value="<?= $data['phone']; ?>">
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" name="address" id="address"
                           value="<?= $data['address']; ?>">
                </div>
                <div class="form-group">
                    <label for="postalCode">Postal Code</label>
                    <input type="text" class="form-control" name="postalCode" id="postalCode"
                           value="<?= $data['postalCode']; ?>">
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" class="form-control" name="city" id="city"
                           value="<?= $data['city']; ?>">
                </div>
                <div class="form-group">
                    <label for="province">Province</label>
                    <input type="text" class="form-control" name="province" id="province"
                           value="<?= $data['province']; ?>">
                </div>
                <div class="form-group">
                    <label for="citizenship">Citizenship</label>
                    <input type="text" class="form-control" name="citizenship" id="citizenship"
                           value="<?= $data['citizenship']; ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" name="email" id="email" value="<?= $data['email']; ?>">
                </div>
                <input type="hidden" name="id" value="<?php echo $data['id']; ?>"/><br/>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
<?php include "inc/footer.inc.php"; ?>