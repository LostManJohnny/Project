<?php
if (isset($_GET)) {

    require('./connection.php');

    $response;

    if (isset($_GET['username']) && $_GET['username'] != "") {
        header('Content-Type: application/json; charset=utf-8');

        $statement;

        if (isset($_GET['ownerID']) && $_GET['ownerID'] != "") {
            $query = "SELECT username, ownerID FROM Owners WHERE username = :username AND OwnerID != :ownerID";
            $statement = $db->prepare($query);
            $statement->bindValue(":ownerID", $_GET['ownerID']);
        } else {
            $query = "SELECT username, ownerID FROM Owners WHERE username = :username";
            $statement = $db->prepare($query);
        }
        $statement->bindValue("username", $_GET['username']);
        $statement->execute();

        $results = $statement->fetchAll();

        if ($statement->rowCount() > 0) {
            $response = [
                "status" => 200,
                "username" => $_GET['username'],
                "ownerID" => isset($_GET['ownerID']) ? $_GET['ownerID'] : null,
                "results" => true,
                "count" => $statement->rowCount(),
                "users" => $results
            ];
        } else {
            $response = [
                "status" => 200,
                "username" => $_GET['username'],
                "ownerID" => isset($_GET['ownerID']) ? $_GET['ownerID'] : null,
                "results" => false,
                "count" => $statement->rowCount(),
                "users" => $results
            ];
        }
    } else {
        $response = [
            "status" => 400,
            "error" => "Bad Request, username not set or empty",
            "results" => true
        ];
    }

    echo json_encode($response);
}