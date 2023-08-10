<?php
// start session
session_start();

// set php to show error messages
ini_set('display_errors', 1);

$errorMessage = ""; // variable to monitor errors
$successMessage = ""; // variable to monitor success
$db = null;    // variable for built-in database connection

/**
 * Connect to mysqli database (old style)
 */
function connectDB()
{
    global $db;

    // connect to database
    $db = new mysqli("127.0.0.1", "root", "", "mydb");

    // check connection successful
    if ($db->connect_errno > 0) {
        die("Connection failed: " . $db->connect_error);
    }
}

/**
 * Pretty printout of given variable
 *
 * @param [*] $data
 */
function pr($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

/**
 * Validate if a given variable is empty
 *
 * @param [string] $field
 * @return boolean
 */
function isFieldEmpty($field)
{
    return (!isset($field) || trim($field) == "");
}

/**
 * Output error messages
 *
 * @return void
 */
function displayErrors()
{
    global $errorMessage; // use the variable that was created OUTSIDE the function.

    if ($errorMessage != "") { ?>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="alert alert-info">
                    <h4 class="alert-heading">Important Information!</h4>
                    <p class="mb-0"><?= $errorMessage; ?></p>
                </div>
            </div>
        </div>
    <?php }
}

/**
 * Display Error / Success message
 */
function displayMessage()
{
    global $errorMessage;
    global $successMessage;

    echo "
    <div class=\"row\">
        <div class=\"col-md-12\">
    ";

    if (trim($errorMessage) != "") {
        echo "
            <div class=\"alert alert-danger\">
                <h4 class=\"alert-heading\">Warning!</h4>
                <p class=\"mb-0\">$errorMessage</p>
            </div>
    ";
    } else if (trim($successMessage) != "") {
        echo "
            <div class=\"alert alert-success\">
                <h4 class=\"alert-heading\">Success!</h4>
                <p class=\"mb-0\">$successMessage</p>
            </div>
    ";
    }

    echo "</div>
    </div>";
}

