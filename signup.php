<?php

$logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'];

$alert_msg = "";
if (isset($_GET) && isset($_GET['status']) && $_GET['status'] == 400) {
    $alert_msg = "An error occured then inserting the user into the database\\n";

    if (isset($_GET['email']) && $_GET['email'] == "exists") {
        $alert_msg = $alert_msg . "The email provided already exists\\n";
    }

    if (isset($_GET['username']) && $_GET['username'] == "exists") {
        $alert_msg = $alert_msg . "The username provided already exists";
    }
}

//Checks for post variable
if (isset($_POST) && $_POST != []) {
    //Get the user validation functions
    require($root . 'Validation/user_validation.php');

    //Filter, validate and sanitize all the variables
    [$errors, $sanitized] = filter_sanitize_fname($errors, $sanitized);
    [$errors, $sanitized] = filter_sanitize_lname($errors, $sanitized);
    [$errors, $sanitized] = filter_sanitize_birthdate($errors, $sanitized);
    [$errors, $sanitized] = filter_sanitize_email($errors, $sanitized);
    [$errors, $sanitized] = filter_sanitize_username($errors, $sanitized);

    //Ensure passwords match
    $passwords_match = passwords_match($_POST);

    //If passwords match, validate them
    if ($passwords_match) {
        [$errors, $sanitized] = filter_sanitize_password($errors, $sanitized);
        [$errors, $sanitized] = filter_sanitize_passwordConfirmation($errors, $sanitized);
    }

    //Perform username and email checks
    $email_exists = isset($sanitized['email']) && email_exists($sanitized['email'], $db);
    $username_exists = isset($sanitized['username']) && username_exists($sanitized['username'], $db);

    //Checks if the email exists
    if ($email_exists) {
        array_push($errors, "Error " . (count($errors) + 1) . " - Email is already in use");
    }
    //Checks if the username exists
    if ($username_exists) {
        array_push($errors, "Error " . (count($errors) + 1) . " - Username is already in use");
    }

    //If there are no errors, proceed with creating the user
    if (count($errors) == 0) {

        //Salt and hash the password, then verify it
        $hashed_salted_pass = password_hash($sanitized['password'], PASSWORD_BCRYPT);
        $hashed_salted_pass_VERIFY = password_verify($sanitized['password'], $hashed_salted_pass);

        //If the verify succeeds, insert the user into the database
        if ($hashed_salted_pass_VERIFY) {

            //Build and prepare the query
            $query = "INSERT INTO owners (FirstName, LastName, BirthDate, Email, Username, Password) VALUES (:FirstName, :LastName, :BirthDate, :Email, :Username, :Password)";
            $statement = $db->prepare($query);
            //Bind values to the query
            $bind_values = [
                'FirstName' => $sanitized['fname'],
                'LastName' => $sanitized['lname'],
                'BirthDate' => $sanitized['birthdate'],
                'Email' => $sanitized['email'],
                'Username' => $sanitized['username'],
                'Password' => $hashed_salted_pass
            ];
            //Execute the statement, storing the result
            $insert_results = $statement->execute($bind_values);

            if ($insert_results) {
                header('Location: ' . $root . 'index.php');
                exit;
            } else {
                $alert_msg = "ERROR - User was not added to the database";
            }
        }
    } else {
        $alert_msg = "An error occured when inserting the user.\\n";
        if ($email_exists) {
            $alert_msg = $alert_msg . "The email provided already exists.\\n";
        }
        if ($username_exists) {
            $alert_msg = $alert_msg . "The username provided already exists.\\n";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>

    <!-- Personal -->
    <link rel="stylesheet" href="./Styles/styles.css">
    <script src="./js/signup.js"></script>

    <title>Login</title>
</head>

<body>
    <header>
        <div class="container">
            <div class="d-flex flex-row align-items-center justify-content-between mt-3">
                <a href="./index.php">
                    <img id="logo" src="./images/icons/logo.png" alt="Logo" class="cursor-pointer">
                </a>
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

                <div class="d-flex flex-row">
                    <!-- If not logged in, show the LOGIN link -->
                    <?php if (!$logged_in) : ?>
                    <a href="./login.php" id="login" class="d-flex flex-column align-items-center mr-3">
                        <img src="./images/icons/login.png" alt="Login">
                        <h6 class="">LOGIN</h6>
                    </a>
                    <!-- If already logged in, show the ACCOUNT link -->
                    <?php else : ?>
                    <div class="dropdown d-flex flex-column">
                        <button class="btn dropdown-toggle hover-gray:hover" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        </button>
                        <label for="dropdownMenuButton" class="cursor-pointer">ACCOUNT</label>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a href="#" class="dropdown-item">Profile</a>
                            <a href="#" class="dropdown-item">Collections</a>
                            <a href="./api/logout.php" class="dropdown-item">Logout</a>
                            <?php if (isset($_SESSION['username']) && $_SESSION['username'] == "admin_user") : ?>
                            <hr>
                            <a href="./admin/admin_dashboard.php" class="dropdown-item">Admin Dashboard</a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php endif; ?>
                    <!-- The cart -->
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
            <!-- Sign up form -->
            <form action="" method="post" class="form d-flex flex-column mx-auto p-3 mt-5 mb-1 border border-primary"
                id="signup-form">
                <h3 class="mx-auto mb-4">Create an Account</h3>
                <div id="account-error" class="text-danger">

                </div>
                <input type="text" placeholder="First Name" class="input-field mt-3" name="fname" id="fname">
                <div id="fname-error" class="text-danger" hidden>

                </div>
                <input type="text" placeholder="Last Name" class="input-field mt-3" name="lname" id="lname">
                <div id="lname-error" class="text-danger" hidden>

                </div>
                <input type="date" class="input-field mt-3" name="birthdate" id="birthdate" placeholder="Birthdate">
                <div id="birthdate-error" class="text-danger" hidden>

                </div>
                <input type="email" placeholder="Email" class="input-field mt-3" name="email" id="email">
                <div id="email-error" class="text-danger" hidden>

                </div>
                <input type="text" placeholder="Username" class="input-field mt-3" name="username" id="username">
                <div id="username-error" class="text-danger" hidden>

                </div>
                <input type="password" placeholder="Password" class="input-field mt-3" name="password" id="password">
                <div id="password-error" class="text-danger" hidden>

                </div>
                <input type="password" placeholder="Confirm your Password" class="input-field mt-3"
                    name="password-confirm" id="password-confirm">
                <div id="password-confirm-error" class="text-danger" hidden>

                </div>
                <button id="btn-signup" type="submit" class="btn btn-primary mt-3">Sign Up</button>
            </form>
            <!-- Link to create account -->
            <p>Already have an account? <a href="./login.php">Login here</a></p>
        </div>
    </main>
    <footer>

    </footer>
</body>

</html>