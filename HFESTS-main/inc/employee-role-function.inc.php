<?php
require "inc/function.inc.php";

function fetchAllEmployeeRoles() {
    global $db;

    $sql = "SELECT * FROM employee_role ORDER BY id ASC";

    return $db->query($sql);
}

function fetchEmployeeRole($id)
{
// fetch user from db
    global $db;
    $id = $db->real_escape_string($id);
    $sql = "SELECT * FROM employee_role WHERE id = '$id'";
    $result = $db->query($sql);
    if (!$result) {
        // query did not work redirect
        header("Location: employee-role.php?message=dbError");
        die();
    }
    if ($result->num_rows != 1) {
        header("Location: employee-role.php?message=notFound");
        die();
    }
    return $result->fetch_assoc();
}

function deleteEmployeeRole($id)
{
    global $db;
    $id = $db->real_escape_string($id);
    $sql = "DELETE FROM employee_role WHERE id = '$id'";
    return $db->query($sql);
}

function updateEmployeeRole($role, $id)
{
    global $db;
    $id = $db->real_escape_string($id);
    $role = $db->real_escape_string($role);
    $sql = "UPDATE employee_role SET role='$role' WHERE id ='$id'";
    $db->query($sql);
}

function createEmployeeRole($role)
{
    global $db;
    $role = $db->real_escape_string($role);
    $sql = "INSERT INTO employee_role (role) VALUES ('$role')";
    $db->query($sql);
}

