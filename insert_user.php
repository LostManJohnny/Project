<?php

$errors = [];
$sanitized = [];

if (isset($_POST)) {
    [$errors, $sanitized] = filter_sanitize_fname($errors, $sanitized);
    [$errors, $sanitized] = filter_sanitize_lname($errors, $sanitized);
    [$errors, $sanitized] = filter_sanitize_birthdate($errors, $sanitized);
    [$errors, $sanitized] = filter_sanitize_email($errors, $sanitized);

    if (passwords_match()) {
        [$errors, $sanitized] = filter_sanitize_password($errors, $sanitized);
        [$errors, $sanitized] = filter_sanitize_passwordConfirmation($errors, $sanitized);
    }
}

/**
 * Filters and sanitized the first name POST variable
 */
function filter_sanitize_fname($errors, $sanitized)
{
    //Check the first name is set
    if (!isset($_POST['fname'])) {
        array_push($errors, "Error " . (count($errors) + 1) . " - No first name submitted");
    }
    //Check the first name is not empty
    else if (strlen($_POST['fname']) < 1) {
        array_push($errors, "Error " . (count($errors) + 1) . " - First name cannot be empty");
    }
    //Checks if first name has spaces
    else if (str_contains($_POST['fname'], " ")) {
        array_push($errors, "Error " . (count($errors) + 1) . " - First name cannot have spaces");
    } else {
        $sanitized['fname'] = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
    }

    return [$errors, $sanitized];
}

/**
 * Filters and sanitized the last name POST variable
 */
function filter_sanitize_lname($errors, $sanitized)
{
    //Check the first name is set
    if (!isset($_POST['lname'])) {
        array_push($errors, "Error " . (count($errors) + 1) . " - No last name submitted");
    }
    //Check the first name is not empty
    else if (strlen($_POST['lname']) < 1) {
        array_push($errors, "Error " . (count($errors) + 1) . " - Last name cannot be empty");
    }
    //Checks if first name has spaces
    else if (str_contains($_POST['lname'], " ")) {
        array_push($errors, "Error " . (count($errors) + 1) . " - Last name cannot have spaces");
    } else {
        $sanitized['lname'] = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
    }

    return [$errors, $sanitized];
}

/**
 * Filters and sanitized the birthdate POST variable
 */
function filter_sanitize_birthdate($errors, $sanitized)
{
    if (!isset($_POST['birthdate'])) {
        array_push($errors, "Error " . (count($errors) + 1) . " - No birthdate submitted");
    } else if (strlen($_POST['birthdate']) != 10) {
        array_push($errors, "Error " . (count($errors) + 1) . " - Birthday is incorrectly formatted");
    } else if (str_contains($_POST['birthdate'], " ")) {
        array_push($errors, "Error " . (count($errors) + 1) . " - Birthdate cannot have spaces");
    } else if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['birthdate'])) {
        $sanitized['birthdate'] = date($_POST['birthdate']);
    }

    return [$errors, $sanitized];
}

/**
 * Filters and sanitized the email POST variable
 */
function filter_sanitize_email($errors, $sanitized)
{
    //Check the first name is set
    if (!isset($_POST['email'])) {
        array_push($errors, "Error " . (count($errors) + 1) . " - No email submitted");
    }
    //Check the first name is not empty
    else if (strlen($_POST['email']) < 1) {
        array_push($errors, "Error " . (count($errors) + 1) . " - Email cannot be empty");
    }
    //Checks if first name has spaces
    else if (str_contains($_POST['email'], " ")) {
        array_push($errors, "Error " . (count($errors) + 1) . " - Email cannot have spaces");
    } else {
        $sanitized['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    }

    return [$errors, $sanitized];
}

/**
 * Description - Confirms that poth password and the confirmation were submitted and
 *                 that they are equal.
 *               Note* - This does not check if they are empty or not.
 */
function passwords_match()
{
    //Ensures both password and confirmation are passed...
    if (isset($_POST['password']) && isset(($_POST['password-confirm']))) {
        //Checks if they are equal
        return $_POST['password'] == $_POST['password-confirm'];
    }
    //... otherwise returns false
    return false;
}

/**
 * Filters and sanitized the password POST variable
 */
function filter_sanitize_password($errors, $sanitized)
{
    //Check the first name is set
    if (!isset($_POST['password'])) {
        array_push($errors, "Error " . (count($errors) + 1) . " - No password submitted");
    }
    //Check the first name is not empty
    else if (strlen($_POST['password']) < 1) {
        array_push($errors, "Error " . (count($errors) + 1) . " - Password cannot be empty");
    } else {
        $sanitized['password'] = $_POST['password'];
    }

    return [$errors, $sanitized];
}

/**
 * Filters and sanitized the password confirmation POST variable
 */
function filter_sanitize_passwordConfirmation($errors, $sanitized)
{
    //Check the first name is set
    if (!isset($_POST['password-confirmation'])) {
        array_push($errors, "Error " . (count($errors) + 1) . " - No password-confirmation submitted");
    }
    //Check the first name is not empty
    else if (strlen($_POST['password-confirmation']) < 1) {
        array_push($errors, "Error " . (count($errors) + 1) . " - Password-confirmation cannot be empty");
    } else {
        $sanitized['password-confirmation'] = $_POST['password-confirmation'];
    }

    return [$errors, $sanitized];
}

function hash_passwords($errors, $sanitized)
{
    //Ensures that the passwords were filtered and sanitized
    if (!isset($sanitized['password'])) {
        [$errors, $sanitized] = filter_sanitize_password($errors, $sanitized);
    }
    if (!isset($sanitized['password-confirmation'])) {
        [$errors, $sanitized] = filter_sanitize_passwordConfirmation($errors, $sanitized);
    }
    //Ensures that there are no newly created errors
    if (count($errors) == 0) {
    }

    return [$errors, $sanitized];
}

/**
 * Username - Username to validate
 * Description - Checks if the username is already in use
 */
function username_exists($username)
{
    //Database connection
    require('connection.php');

    //Builds query and executes it
    $query = `SELECT Username FROM owners WHERE Username = ${username};`;
    $statement = $db->prepare($query);
    $statement->execute();
    //Checks if the username exists in the database, returns true if the username exists...
    if ($statement->rowCount > 0) {
        return true;
    }
    //... otherwise return false
    return false;
}

/**
 * Username - Username to validate
 * Description - Checks if the username is already in use
 */
function email_exists($email)
{
    //Database connection
    require('connection.php');

    //Builds query and executes it
    $query = `SELECT Email FROM email WHERE Email = ${email};`;
    $statement = $db->prepare($query);
    $statement->execute();
    //Checks if the email exists in the database, returns true if the email exists...
    if ($statement->rowCount > 0) {
        return true;
    }
    //... otherwise return false
    return false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

</body>

</html>