<?php
if (isset($_GET)) {

    require('./connection.php');

    $response;

    if (isset($_GET['username']) && $_GET['username'] != "") {
        header('Content-Type: application/json; charset=utf-8');
        $query = "SELECT username FROM owners WHERE username = :username";
        $statement = $db->prepare($query);
        $statement->bindValue("username", $_GET['username']);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            // $response->status = "200";
            // $response->username = $_GET['username'];
            // $response->results = "false";
            $response = [
                "status" => 200,
                "username" => $_GET['username'],
                "results" => false
            ];
        } else {
            // $response->status = "200";
            // $response->username = $_GET['username'];
            // $response->results = "true";
            $response = [
                "status" => 200,
                "username" => $_GET['username'],
                "results" => true
            ];
        }
    } else {
        // $response->status = "400";
        // $response->error = "Bad Request, username not set or empty";
        // $response->results = "false";
        $response = [
            "status" => 400,
            "error" => "Bad Request, username not set or empty",
            "results" => false
        ];
    }

    echo json_encode($response);
}