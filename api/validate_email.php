<?php
if (isset($_GET)) {

    require('./connection.php');

    $response;

    if (isset($_GET['email']) && $_GET['email'] != "") {
        header('Content-Type: application/json; charset=utf-8');
        $query = "SELECT email FROM owners WHERE email = :email";
        $statement = $db->prepare($query);
        $statement->bindValue("email", $_GET['email']);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            $response = [
                "status" => 200,
                "email" => $_GET['email'],
                "results" => true
            ];
        } else {
            $response = [
                "status" => 200,
                "email" => $_GET['email'],
                "results" => false
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