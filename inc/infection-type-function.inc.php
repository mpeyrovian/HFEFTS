<?php
require "inc/function.inc.php";

function fetchAllInfectionTypes()
{
    global $db;
    $sql = "SELECT * FROM infection_type ORDER BY type ASC";
    return $db->query($sql);
}

function fetchInfectionType($id)
{
// fetch user from db
    global $db;
    $id = $db->real_escape_string($id);
    $sql = "SELECT * FROM infection_type WHERE id = '$id' LIMIT 1";
    $result = $db->query($sql);
    if (!$result) {
        // query did not work redirect
        header("Location: infection-type.php?message=dbError");
        die();
    }
    if ($result->num_rows != 1) {
        header("Location: infection-type.php?message=notFound");
        die();
    }
    return $result->fetch_assoc();
}

function deleteInfectionType($id)
{
    global $db;
    $id = $db->real_escape_string($id);
    $sql = "DELETE FROM infection_type WHERE id = '$id' LIMIT 1";
    return $db->query($sql); // TODO: do the same for all pages
}

function updateInfectionType($type, $id)
{
    global $db;
    $id = $db->real_escape_string($id);
    $type = $db->real_escape_string($type);
    $sql = "UPDATE infection_type SET type='$type' WHERE id ='$id' LIMIT 1";
    $db->query($sql);
}

function createInfectionType($type)
{
    global $db;
    $type = $db->real_escape_string($type);
    $sql = "INSERT INTO infection_type (type) VALUES ('$type')";
    $db->query($sql);
}

