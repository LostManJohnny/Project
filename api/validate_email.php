<?php
if (isset($_GET)) {

    require('./connection.php');

    $response;

    if (isset($_GET['email']) && $_GET['email'] != "") {
        header('Content-Type: application/json; charset=utf-8');

        $statement;

        if (isset($_GET['ownerID']) && $_GET['ownerID'] != "") {
            $query = "SELECT email, ownerID FROM owners WHERE email = :email AND OwnerID <> :ownerID";
            $statement = $db->prepare($query);
            $statement->bindValue(":ownerID", $_GET['ownerID']);
        } else {
            $query = "SELECT email, ownerID FROM owners WHERE email = :email";
            $statement = $db->prepare($query);
        }

        $statement->bindValue("email", $_GET['email']);
        $statement->execute();

        $results = $statement->fetchAll();

        if ($statement->rowCount() > 0) {
            $response = [
                "status" => 200,
                "email" => $_GET['email'],
                "ownerID" => isset($_GET['ownerID']) ? $_GET['ownerID'] : null,
                "results" => true,
                "count" => $statement->rowCount(),
                "users" => $results
            ];
        } else {
            $response = [
                "status" => 200,
                "email" => $_GET['email'],
                "ownerID" => isset($_GET['ownerID']) ? $_GET['ownerID'] : null,
                "results" => false,
                "count" => $statement->rowCount(),
                "users" => $results
            ];
        }
    } else {
        $response = [
            "status" => 400,
            "error" => "Bad Request, email not set or empty",
            "results" => true
        ];
    }

    echo json_encode($response);
}