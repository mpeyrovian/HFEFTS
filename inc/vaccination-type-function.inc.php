<?php
require "inc/function.inc.php";

function fetchAllVaccinationTypes()
{
    global $db;
    $sql = "SELECT * FROM vaccination_type ORDER BY type ASC";
    return $db->query($sql);
}

function fetchVaccinationType($id)
{
// fetch user from db
    global $db;
    $id = $db->real_escape_string($id);
    $sql = "SELECT * FROM vaccination_type WHERE id = '$id' LIMIT 1";
    $result = $db->query($sql);
    if (!$result) {
        // query did not work redirect
        header("Location: vaccination-type.php?message=dbError");
        die();
    }
    if ($result->num_rows != 1) {
        header("Location: vaccination-type.php?message=notFound");
        die();
    }
    return $result->fetch_assoc();
}

function deleteVaccinationType($id)
{
    global $db;
    $id = $db->real_escape_string($id);
    $sql = "DELETE FROM vaccination_type WHERE id = '$id' LIMIT 1";
    return $db->query($sql); // TODO: do the same for all pages
}

function updateVaccinationType($type, $id)
{
    global $db;
    $id = $db->real_escape_string($id);
    $type = $db->real_escape_string($type);
    $sql = "UPDATE vaccination_type SET type='$type' WHERE id ='$id' LIMIT 1";
    $db->query($sql);
}

function createVaccinationType($type)
{
    global $db;
    $type = $db->real_escape_string($type);
    $sql = "INSERT INTO vaccination_type (type) VALUES ('$type')";
    $db->query($sql);
}

