<?php


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
                <img id="logo" src="./#" alt="Logo" class="cursor-pointer">

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
            <form action="./insert_user.php" method="post"
                class="form d-flex flex-column mx-auto p-3 mt-5 mb-1 border border-primary">
                <h3 class="mx-auto mb-4">Create an Account</h3>
                <input type="text" placeholder="First Name" class="input-field mb-3" name="fname" id="fname" required>
                <input type="text" placeholder="Last Name" class="input-field mb-3" name="lname" id="lname" required>
                <input type="date" class="input-field mb-3" name="birthdate" id="birthdate" required>
                <input type="email" placeholder="Email" class="input-field mb-3" name="email" required>
                <input type="text" placeholder="Username" class="input-field mb-3" name="username" id="username"
                    required>
                <input type="password" placeholder="Password" class="input-field mb-3" name="password" required>
                <input type="password" placeholder="Confirm your Password" class="input-field mb-3"
                    name="password-confirm" id="password-confirm" required>
                <button type="submit" class="btn btn-primary">Sign In</button>
            </form>
            <!-- Link to create account -->
            <p>Already have an account? <a href="./login.php">Login here</a></p>
        </div>
    </main>
    <footer>

    </footer>
</body>

</html>