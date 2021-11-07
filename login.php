<?php
require('connection.php');

session_start();

$errors = [];
$sanitized = [];

$valid_credentials = true;

// if (isset($_SESSION)) {
//     header("location: index.php");
//     exit;
// }

//Checks that the form was submitted
if (isset($_POST)) {
    if ((isset($_POST['login']) && //Post was submitted through the form
            $_POST['login'] = "submit") &&
        (isset($_POST['email']) && //Email is set
            isset($_POST['password'])) //Password is set
    ) {
        $sanitized['email'] = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

        //Get the user's credentials if and only if 
        $valid_credentials = valid_credentials($sanitized['email'], $_POST['password'], $db);

        if ($valid_credentials != false) {
            [$db_id, $db_username, $db_email, $db_password_hashed] = $valid_credentials;
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $db_id;
            $_SESSION['username'] = $db_username;
            header("location: index.php");
            exit;
        } else {
            echo "INVALID CREDENTIALS" . '<br/>';
            $valid_credentials = false;
        }
    }
}

/**
 * Email - The email being used to login
 * Password - The provided password to login with
 * db - Database PDO object
 * Description: Validates the credentials, and returns true if login is successful
 */
function valid_credentials($email, $password, $db)
{
    echo "VALID_CREDENTIALS - EMAIL EXISTS -> ";
    echo email_exists($email, $db) . '<br/>';
    //Check that the email is associated with an account and exists ...
    if (email_exists($email, $db)) {
        //Get the hashed password from the database
        [$db_id, $db_username, $db_email, $db_password_hashed] = get_user_details($email, $db);
        $password_provided = $password;

        //If the login password verifies successfully, return the user data ... 
        echo "VALID_CREDENTIALS - PASSWORD PROVIDED -> " . $password_provided . '<br/>';
        echo "VALID_CREDENTIALS - DB PASSWORD -> " . $db_password_hashed . '<br/>';
        echo "VALID_CREDENTIALS - PASSWORD_VERIFY -> " . password_verify($password_provided, $db_password_hashed) . '<br/>';
        if (password_verify($password_provided, $db_password_hashed)) {
            return [$db_id, $db_username, $db_email, $db_password_hashed];
        }
        //... otherwise return false
        else {
            return false;
        }
    }
    //... otherwise return false
    else {
        return false;
    }
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
 * Email - Email associated with the account you want the password for
 * db - Database PDO object
 * Description: Returns the password of an account you want
 *      Note*: Email is a unique field in the database so should only return one result
 */
function get_user_details($email, $db)
{
    //Build, prepare and bind the query
    $query = "SELECT OwnerID, Username, Email, Password FROM owners WHERE email = :Email";
    $statement = $db->prepare($query);
    $statement->bindValue('Email', $email);

    //Execute the query
    $statement->execute();

    //If no results were returned, the account doesn't exist
    if ($statement->rowCount() == 0) {
        return -1;
    }
    //If more than one result was returned, something bad happened a while ago
    else if ($statement->rowCount() > 1) {
        return -2;
    }
    //This is good, very good. 
    else if ($statement->rowCount() == 1) {
        $row = $statement->fetch();
        return [$row['OwnerID'], $row['Username'], $row['Email'], $row['Password']];
    }
    //This would only happen if rowCount was < 0 and I don't even want to 
    //think about what that could mean
    //So we'll just return a number and call it a day.
    else {
        return -3;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./Styles/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <title>Login</title>
</head>

<body>
    <header>
        <div class="container">
            <!-- Page Header -->
            <div class="d-flex flex-row align-items-center justify-content-between mt-3">

                <!-- Logo -->
                <img id="logo" src="./images/icons/logo.png" alt="Logo" class="cursor-pointer">

                <!-- Search Bar -->
                <form action="">
                    <div class="input-group">
                        <div class="form-outline">
                            <input type="search" id="cardSearch" class="form-control" placeholder="Search card name" />
                        </div>
                        <button type="button" class="btn btn-primary">
                            <ion-icon name="search-outline"></ion-icon>
                        </button>
                    </div>
                </form>

                <!-- Account Buttons -->
                <div class="d-flex flex-row">
                    <!-- Login -->
                    <a href="./login.php" id="login" class="d-flex flex-column align-items-center mr-3">
                        <img src="./images/icons/login.png" alt="Login">
                        <h6 class="">LOGIN</h6>
                    </a>
                    <!-- Cart -->
                    <a href="http://" id="cart" class="d-flex flex-column align-items-center ml-3">
                        <img src="./images/icons/shopping-cart.png" alt="Cart">
                        <h6>CART</h6>
                    </a>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="container w-25">
            <!-- Sign in form -->
            <form action="" method="post" class="form d-flex flex-column mx-auto p-3 mt-5 mb-1 border border-primary">
                <h3 class="mx-auto mb-4">Sign In</h3>

                <!-- On failed login attempt -->
                <?php if (!$valid_credentials) : ?>
                <h6 class="text-danger">Email or Password is incorrect</h4>

                    <input type="email" placeholder="Email" class="input-field mb-3" name="email"
                        value="<?= $_POST['email'] ?>">

                    <!-- On first login attempt -->
                    <?php else : ?>
                    <input type="email" placeholder="Email" class="input-field mb-3" name="email">
                    <?php endif; ?>

                    <input type="password" placeholder="Password" class="input-field mb-3" name="password">
                    <button type="submit" name="login" value="submit" class="btn btn-primary">Sign In</button>
            </form>
            <!-- Link to create account -->
            <p>Don't have an account? <a href="./signup.php">Sign up here</a></p>
        </div>
    </main>
    <footer>

    </footer>
</body>

</html>