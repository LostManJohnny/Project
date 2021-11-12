<?php

//Response variable
$response;

//Check for GET request
if (isset($_GET)) {
    //Get databse connection
    require('./connection.php');


    //Checks if id is a GET variable
    if (isset($_GET['id']) && $_GET['id'] != "" && $id = validate_id($_GET['id'])) {
        $query = "SELECT OwnerID, FirstName, LastName, Birthdate, Email, Username, PrefCurrency FROM Owners WHERE ownerID=:id";
        $statement = $db->prepare($query);
        $statement->bindValue("id", $id);
        $statement->execute();

        if ($statement->rowCount() == 0) {
            $response = [
                "status" => 404,
                "results" => [
                    "error" => "No user with id (" . $id . ") was found",
                    "id" => $id
                ]
            ];
        } else if ($statement->rowCount() > 1) {
            $response = [
                "status" => 400,
                "results" => [
                    "error" => "Bad request, multiple users with id (" . $id . ") was found",
                    "id" => $id
                ]
            ];
        } else if ($statement->rowCount() == 1) {
            $row = $statement->fetch();
            $response = [
                "status" => 200,
                "results" => [
                    "error" => null,
                    "id" => $row['OwnerID'],
                    "First Name" => $row['FirstName'],
                    "Last Name" => $row['LastName'],
                    "Birthdate" => $row['Birthdate'],
                    "Email" => $row['Email'],
                    "Username" => $row['Username'],
                    "Currency" => $row['PrefCurrency']
                ]
            ];
        }
    } else if (isset($_GET['email']) && $_GET['email'] != "" && $email = validate_email($_GET['email'])) {
        $query = "SELECT OwnerID, FirstName, LastName, Birthdate, Email, Username, PrefCurrency FROM Owners WHERE email=:email";
        $statement = $db->prepare($query);
        $statement->bindValue("email", $email);
        $statement->execute();

        if ($statement->rowCount() == 0) {
            $response = [
                "status" => 404,
                "results" => [
                    "error" => "No user with email (" . $email . ") was found",
                    "email" => $email
                ]
            ];
        } else if ($statement->rowCount() > 1) {
            $response = [
                "status" => 400,
                "results" => [
                    "error" => "Bad request, multiple users with email (" . $email . ") was found",
                    "email" => $email
                ]
            ];
        } else if ($statement->rowCount() == 1) {
            $row = $statement->fetch();
            $response = [
                "status" => 200,
                "results" => [
                    "error" => null,
                    "id" => $row['OwnerID'],
                    "First Name" => $row['FirstName'],
                    "Last Name" => $row['LastName'],
                    "Birthdate" => $row['Birthdate'],
                    "Email" => $row['Email'],
                    "Username" => $row['Username'],
                    "Currency" => $row['PrefCurrency']
                ]
            ];
        }
    } else if (isset($_GET['username']) && $_GET['username'] != "" && $username = validate_username($_GET['username'])) {
        $query = "SELECT OwnerID, FirstName, LastName, Birthdate, Email, Username, PrefCurrency FROM Owners WHERE username=:username";
        $statement = $db->prepare($query);
        $statement->bindValue("username", $username);
        $statement->execute();

        if ($statement->rowCount() == 0) {
            $response = [
                "status" => 404,
                "results" => [
                    "error" => "No user with username (" . $username . ") was found",
                    "username" => $username
                ]
            ];
        } else if ($statement->rowCount() > 1) {
            $response = [
                "status" => 400,
                "results" => [
                    "error" => "Bad request, multiple users with username (" . $username . ") was found",
                    "username" => $username
                ]
            ];
        } else if ($statement->rowCount() == 1) {
            $row = $statement->fetch();
            $response = [
                "status" => 200,
                "results" => [
                    "error" => null,
                    "id" => $row['OwnerID'],
                    "First Name" => $row['FirstName'],
                    "Last Name" => $row['LastName'],
                    "Birthdate" => $row['Birthdate'],
                    "Email" => $row['Email'],
                    "Username" => $row['Username'],
                    "Currency" => $row['PrefCurrency']
                ]
            ];
        }
    } else {
        $response = [
            "status" => 400,
            "results" => [
                "error" => "Bad request, missing GET variable id, email or username"
            ]
        ];
    }
} else {
    $response = [
        "status" => 400,
        "results" => [
            "error" => "Bad request, missing GET variable id, email or username"
        ]
    ];
}

echo json_encode($response);


/**
 * id - id to be validated
 * Description: Ensures that the id is a number and is formatted as an id
 */
function validate_id($id)
{
    //Ensures that the id is not an empty string
    if ($id == "") {
        return false;
    }
    //Ensures that the id is a number
    if (is_int($id)) {
        return false;
    }
    // //Ensures that the id is 7 digits long
    // if (strlen($id) != 7) {
    //     return false;
    // }

    return $id;
}

/**
 * id - id to be validated
 * Description: Ensures that the id is a number and is formatted as an id
 */
function validate_email($email)
{
    //Ensures that the id is not an empty string
    if ($email == "") {
        return false;
    }
    //Filters as an email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    return $email;
}

/**
 * id - id to be validated
 * Description: Ensures that the id is a number and is formatted as an id
 */
function validate_username($email)
{
    //Ensures that the id is not an empty string
    if ($email == "") {
        return false;
    }

    return $email;
}