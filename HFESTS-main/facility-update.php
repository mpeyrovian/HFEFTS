<?php

require "inc/facility-function.inc.php";
global $db;

/*
// set initial values
$data = array('name' => "", 'phone' => "", 'webAddress' => "",'facilityType' => "", 'facilityTypeId' => "", 'capacity' => "", 'address' => "", 'postalCode' => "", 'city' => "", 'province' => "",'id' => "");
$pageTitle = "Add a facility"; // Dynamic page title
$facilityTypesArray = fetchAllFacilityTypes();

if ($_SERVER['REQUEST_METHOD'] == "GET") { // if GET method used
    if (isset($_GET['id']) && is_numeric($_GET['id'])) { // check if an id was given
        $data = fetchfacility($_GET['id']);
        if ($_GET['mode'] == "delete") {
            // delete facility
            deletefacility($_GET['id']);
            header("Location: facility.php?message=removed&id=" . htmlentities($data['id']));
            die();
        } else if ($_GET['mode'] == "edit") {
            // display 'Edit facility' form
            $pageTitle = "Edit an facility";
        } else {
            header("Location: facility.php?message=invalidRequest");
            die();
        }
    } else { // no id provided
        // display 'Add facility' form
        $pageTitle = "Add a facility";
    }
} else if ($_SERVER['REQUEST_METHOD'] == "POST") { // if POST method used
    // set info for form in case of error
    $id = isset($_POST['id']) ? $_POST['id'] : "";
    $name = isset($_POST['name']) ? $_POST['name'] : "";
    $phone = isset($_POST['phone']) ? $_POST['phone'] : "";
    $webAddress= isset($_POST['webAddress']) ? $_POST['webAddress'] : "";
    $facilityType= isset($_POST['facilityType']) ? $_POST['facilityType'] : "";
    $facilityTypeId= isset($_POST['facilityTypeId']) ? $_POST['facilityTypeId'] : "";
    $capacity = isset($_POST['capacity']) ? $_POST['capacity'] : "";
    $address = isset($_POST['address']) ? $_POST['address'] : "";
    $postalCode = isset($_POST['postalCode']) ? $_POST['postalCode'] : "";
    $city = isset($_POST['city']) ? $_POST['city'] : "";
    $province = isset($_POST['province']) ? $_POST['province'] : "";


    // data validation
    if (isFieldEmpty($name) || isFieldEmpty($phone) || isFieldEmpty($webAddress)
        || isFieldEmpty($facilityTypeId) || isFieldEmpty($capacity) || isFieldEmpty($address)
        || isFieldEmpty($postalCode) || isFieldEmpty($city)|| isFieldEmpty($province)) {
        $errorMessage = "All fields are required";
    }

    if ($errorMessage == "") { // no validation errors
        // we check for an id, so we know if it's a creation or an update
        if (isset($_POST['id']) && is_numeric($_POST['id'])) { // update
            updatefacility($name,$phone , $webAddress, $facilityTypeId, $capacity, $address, $postalCode, $city,$province, $id);
            $action = "updated";
        } else { // create
            $id = createFacility($name,$phone , $webAddress, $facilityTypeId, $capacity, $address, $postalCode, $city,$province);
            $action = "created";
        }
        // redirect based on whether facility was created or updated
        header("Location: facility.php?message=$action&id=" . htmlentities($id));
        die();
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
                    <label for="name">Facility Name</label>
                    <input type="text" class="form-control" name="name" id="name"
                           value="<?= $data['name']; ?>">
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" name="phone" id="phone"
                           value="<?= $data['phone']; ?>">
                </div>
                <div class="form-group">
                    <label for="webAddress">Website</label>
                    <input type="text" class="form-control" name="webAddress" id="webAddress"
                           value="<?= $data['webAddress']; ?>">
                </div>
                <div class="form-group">
                    <label for="facilityType">Facility Type</label>
                    <select class="form-control" name="facilityTypeId" id="facilityTypeId">
                    <?php foreach ($facilityTypesArray as $facilityType1) { ?>
                        <option value="<?= $facilityType1['id'] ?>" <?= ($facilityType1['id'] == $data['facilityTypeId']) ? "selected" : "" ?>><?= $facilityType1['type'] ?></option>
                    <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="capacity">Capacity</label>
                    <input type="text" class="form-control" name="capacity" id="capacity" value="<?= $data['capacity']; ?>">
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
                    <input type="text" class="form-control" name="city" id="city" value="<?= $data['city']; ?>">
                </div>
                <div class="form-group">
                    <label for="province">Province</label>
                    <input type="text" class="form-control" name="province" id="province" value="<?= $data['province']; ?>">
                </div>
                <input type="hidden" name="id" value="<?php echo $data['id']; ?>"/><br/>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
<?php include "inc/footer.inc.php"; ?>
*/



// set initial values
$data = array('name' => "", 'phone' => "", 'webAddress' => "", 'facilityTypeId' => "", 'capacity' => "", 'address' => "", 'postalCode' => "", 'city' => "", 'province' => "",'generalManagerId'=>"",'generalManagerName'=>"",'id'=>"");
$pageTitle = "Add a Facility"; // Dynamic page title

$facilityTypes = fetchAllFacilityTypes();
$adminEmployees = fetchAdminEmployees();


if ($_SERVER['REQUEST_METHOD'] == "GET") { // if GET method used
    if (isset($_GET['id']) && is_numeric($_GET['id'])) { // check if an id was given
        $data = fetchFacility($_GET['id']);
        //print_r($data);
        if ($_GET['mode'] == "delete") {
            // delete Facility
            $result = deleteFacility($_GET['id']);
            if (!$result) {
                header("Location: facility.php?message=notremoved&id=" . htmlentities($data['id']));
                die();
            }
            header("Location: facility.php?message=removed&id=" . htmlentities($data['id']));
            die();
        } else if ($_GET['mode'] == "edit") {
            // display 'Edit Facility' form
            $pageTitle = "Edit a Facility";
        } else {
            header("Location: facility.php?message=invalidRequest");
            die();
        }
    } else { // no id provided
        // display 'Add Facility' form
        $pageTitle = "Add a Facility";
    }
} else if ($_SERVER['REQUEST_METHOD'] == "POST") { // if POST method used
    // set info for form in case of error
    $id = isset($_POST['id']) ? $_POST['id'] : "";
    $name = isset($_POST['name']) ? $_POST['name'] : "";
    $phone = isset($_POST['phone']) ? $_POST['phone'] : "";
    $webAddress = isset($_POST['webAddress']) ? $_POST['webAddress'] : "";
    $facilityTypeId = isset($_POST['facilityTypeId']) ? $_POST['facilityTypeId'] : "";
    $capacity = isset($_POST['capacity']) ? $_POST['capacity'] : "";
    $address = isset($_POST['address']) ? $_POST['address'] : "";
    $postalCode = isset($_POST['postalCode']) ? $_POST['postalCode'] : "";
    $city = isset($_POST['city']) ? $_POST['city'] : "";
    $province = isset($_POST['province']) ? $_POST['province'] : "";
    $generalManagerId = isset($_POST['generalManagerId']) ? $_POST['generalManagerId'] : "";


    // data validation
    if (isFieldEmpty($name) || isFieldEmpty($phone) || isFieldEmpty($webAddress)
        || isFieldEmpty($facilityTypeId) || isFieldEmpty($capacity) || isFieldEmpty($address)
        || isFieldEmpty($postalCode) || isFieldEmpty($city) || isFieldEmpty($province)|| isFieldEmpty($generalManagerId)) {
        $errorMessage = "All fields are required";
    } else if (!preg_match('/^(www\.)?[\w-]+(\.[\w-]+)+$/', $webAddress)) {
        $errorMessage = "Web Address is not valid";
    }

    if ($errorMessage == "") { // no validation errors
        // we check for an id, so we know if it's a creation or an update
        if (isset($_POST['id']) && is_numeric($_POST['id'])) { // update
            updateFacility($name, $phone, $webAddress, $facilityTypeId, $capacity, $address, $postalCode, $city, $province,$generalManagerId, $id);
            $action = "updated";
        } else { // create
            $id = createFacility($name, $phone, $webAddress, $facilityTypeId, $capacity, $address, $postalCode, $city, $province, $generalManagerId);
            $action = "created";
        }
        if ( $id == null) {
            header("Location: facility.php?&message=triggerError&errorMsg=" . urlencode($db->error));
            die();
        } else {
        // redirect based on whether facility was created or updated
        header("Location: facility.php?message=$action&id=" . htmlentities($id));
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
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name"
                           value="<?= $data['name']; ?>">
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" name="phone" id="phone"
                           value="<?= $data['phone']; ?>">
                </div>
                <div class="form-group">
                    <label for="webAddress">Web Address</label>
                    <input type="text" class="form-control" name="webAddress" id="webAddress"
                           value="<?= $data['webAddress']; ?>">
                </div>
                <div class="form-group">
                    <label for="facilityTypeId">Facility Type</label>
                    <select class="form-control" name="facilityTypeId" id="facilityTypeId">
                        <?php foreach ($facilityTypes as $facilityTypeItem) { ?>
                            <option value="<?= $facilityTypeItem['id'] ?>" <?= ($facilityTypeItem['id'] == $data['facilityTypeId']) ? "selected" : "" ?>><?= $facilityTypeItem['type'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="capacity">Capacity</label>
                    <input type="text" class="form-control" name="capacity" id="capacity"
                           value="<?= $data['capacity']; ?>">
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
                    <label for="generalManagerId">General Manager</label>
                    <select class="form-control" name="generalManagerId" id="generalManagerId">
                        <?php foreach ($adminEmployees as $adminEmployeesItem) { ?>
                            <option value="<?= $adminEmployeesItem['id'] ?>" <?= ($adminEmployeesItem['id'] == $data['generalManagerId']) ? "selected" : "" ?>><?= $adminEmployeesItem['fullName'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <input type="hidden" name="id" value="<?php echo $data['id']; ?>"/><br/>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
<?php include "inc/footer.inc.php"; ?>