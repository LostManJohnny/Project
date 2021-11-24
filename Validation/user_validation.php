<?php

require('./../api/connection.php');

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
    //Checks max length of first name
    else if (strlen($_POST['fname']) > 45) {
        array_push($errors, "Error " . (count($errors) + 1) . " - First name cannot exceed 45 characters");
    }
    //Checks if first name has spaces
    else if (str_contains($_POST['fname'], " ")) {
        array_push($errors, "Error " . (count($errors) + 1) . " - First name cannot have spaces");
    }
    //Checks if first name has invalid characters
    else if (!filter_input(INPUT_POST, 'fname', FILTER_VALIDATE_REGEXP, array("options" => ["regexp" => '/^([a-zA-Z\'-]+)$/']))) {
        array_push($errors, "Error " . (count($errors) + 1) . " - First name has invalid characters");
    } else {
        $sanitized['fname'] = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING, [FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH, FILTER_FLAG_STRIP_BACKTICK]);
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
    }
    //Checks max length of last name
    else if (strlen($_POST['lname']) > 45) {
        array_push($errors, "Error " . (count($errors) + 1) . " - Last name cannot exceed 45 characters");
    }
    //Checks if last name has invalid characters
    else if (!filter_input(INPUT_POST, 'lname', FILTER_VALIDATE_REGEXP, array("options" => ["regexp" => '/^([a-zA-Z\'-]+)$/']))) {
        array_push($errors, "Error " . (count($errors) + 1) . " - Last name has invalid characters");
    } else {
        $sanitized['lname'] = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING, [FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH, FILTER_FLAG_STRIP_BACKTICK]);
    }

    return [$errors, $sanitized];
}

/**
 * Filters and sanitized the last name POST variable
 */
function filter_sanitize_username($errors, $sanitized)
{
    //Check the first name is set
    if (!isset($_POST['username'])) {
        array_push($errors, "Error " . (count($errors) + 1) . " - No username submitted");
    }
    //Check the first name is not empty
    else if (strlen($_POST['username']) < 1) {
        array_push($errors, "Error " . (count($errors) + 1) . " - Username cannot be empty");
    }
    //Checks if first name has spaces
    else if (str_contains($_POST['username'], " ")) {
        array_push($errors, "Error " . (count($errors) + 1) . " - Username cannot have spaces");
    }
    //Checks if username has invalid characters and is between 8 - 20 characters in length
    else if (!filter_input(INPUT_POST, 'username', FILTER_VALIDATE_REGEXP, array("options" => ["regexp" => '/^(?=[a-zA-Z0-9._]{8,20}$)(?!.*[_.]{2})[^_.].*[^_.]$/']))) {
        array_push($errors, "Error " . (count($errors) + 1) . " - Username has invalid characters or is outside the range 8 - 20");
    } else {
        $sanitized['username'] = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING, [FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH, FILTER_FLAG_STRIP_BACKTICK]);
    }

    return [$errors, $sanitized];
}

/**
 * Filters and sanitized the birthdate POST variable
 */
function filter_sanitize_birthdate($errors, $sanitized)
{
    //Checks that birthdate is set
    if (!isset($_POST['birthdate'])) {
        array_push($errors, "Error " . (count($errors) + 1) . " - No birthdate submitted");
    }
    //Ensure that birthdate is formatted correctly using regular expression
    else if (!filter_input(INPUT_POST, 'birthdate', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => '/([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/']])) {
        array_push($errors, "Error " . (count($errors) + 1) . " - Invalid birthdate format");
    } else {
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
    }
    //Checks that email is invalid
    else if (!filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Error " . (count($errors) + 1) . " - Not a valid format for email");
    }
    //Sanitizes the email field
    else {
        $sanitized['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    }

    return [$errors, $sanitized];
}

/**
 * Description - Confirms that poth password and the confirmation were submitted and
 *                 that they are equal.
 *               Note* - This does not check if they are empty or not.
 */
function passwords_match($sanitized)
{
    //Ensures both password and confirmation are passed...
    if (isset($sanitized['password']) && isset(($sanitized['password-confirm']))) {
        //Checks if they are equal
        return $sanitized['password'] == $sanitized['password-confirm'];
    }
    //... otherwise returns false
    return false;
}

/**
 * Filters and sanitized the password POST variable
 */
function filter_sanitize_password($errors, $sanitized)
{
    //Check the password is set
    if (!isset($_POST['password'])) {
        array_push($errors, "Error " . (count($errors) + 1) . " - No password submitted");
    }
    //Check the password is not empty
    else if (strlen($_POST['password']) < 1) {
        array_push($errors, "Error " . (count($errors) + 1) . " - Password cannot be empty");
    }
    //Checks max length of password
    else if (strlen($_POST['password']) > 45) {
        array_push($errors, "Error " . (count($errors) + 1) . " - Password cannot exceed 45 characters");
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
    if (!isset($_POST['password-confirm'])) {
        array_push($errors, "Error " . (count($errors) + 1) . " - No password-confirm submitted");
    }
    //Check the first name is not empty
    else if (strlen($_POST['password-confirm']) < 1) {
        array_push($errors, "Error " . (count($errors) + 1) . " - password-confirm cannot be empty");
    } else {
        $sanitized['password-confirm'] = $_POST['password-confirm'];
    }

    return [$errors, $sanitized];
}

/**
 * Username - Username to validate
 * Description - Checks if the username is already in use
 */
function username_exists($username, $db)
{
    //Builds query and executes it
    $query = "SELECT Username FROM owners WHERE Username = :Username;";
    $statement = $db->prepare($query);
    $statement->bindValue('Username', $username);
    $statement->execute();
    //Checks if the username exists in the database, returns true if the username exists...
    if ($statement->rowCount() > 0) {
        return true;
    }
    //... otherwise return false
    return false;
}

/**
 * Username - Username to validate
 * Description - Checks if the username is already in use
 */
function email_exists($email, $db)
{
    //Builds query and executes it
    $query = "SELECT Email FROM owners WHERE Email = :Email";
    $statement = $db->prepare($query);
    $statement->bindValue('Email', $email);
    $statement->execute();
    //Checks if the email exists in the database, returns true if the email exists...
    if ($statement->rowCount() > 0) {
        return true;
    }
    //... otherwise return false
    return false;
}