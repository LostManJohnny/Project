<?php
//Start session
session_start();
//Ensure only admin access
require('./api/authenticate.php');

$logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'];

if (!isset($_SESSION['username']) || $_SESSION['username'] != "admin_user") {
    echo "<script>" .
        "alert(\"Unauthorized access to admin dashboard.\\nClick ok to return to the home page.\");" .
        "window.location.href='./index.php'" .
        "</script type=\'text/javascript\'>";
} else {
    //Database connection
    require('./api/connection.php');

    //Get all the users from the database
    $query = "SELECT OwnerID as ID, CONCAT(FirstName, CONCAT(' ', LastName)) as Fullname, Joindate, Email, Username FROM Owners";
    $statement = $db->prepare($query);
    $statement->execute();
    $users = $statement->fetchAll();

    //Get all the cards from the database
    $query = "SELECT CardID as ID, Name, Foil, Promo, setCode FROM Cards";
    $statement = $db->prepare($query);
    $statement->execute();
    $cards = $statement->fetchAll();

    //Get all the cards from the database
    $query = "SELECT CollectionID as ID, OwnerID as Owner, CollectionName as Name FROM Collections";
    $statement = $db->prepare($query);
    $statement->execute();
    $collections = $statement->fetchAll();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="./Styles/styles.css">

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

    <title>Admin Dashboard</title>
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
                            <a href="./admin_dashboard.php" class="dropdown-item">Admin Dashboard</a>
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
        <div id="sections" class="d-flex">
            <div id="sections" class="container mt-4">
                <section id="actions" class="mb-3">
                    <form action="./api/add_user.php" method="post">
                        <button id="btn-add_user" name="add" class="btn btn-success">Add User</button>
                    </form>
                </section>
                <!-- $query = "SELECT CONCAT(FirstName, CONCAT(' ', LastName)) as Fullname, Joindate, Email, Username FROM Owners"; -->
                <section id="users" class="overflow-auto" style="height:400px;">
                    <h1>Site Users</h1>
                    <ul id="users-list">
                        <?php foreach ($users as $user) : ?>
                        <li>
                            <h3><?= $user['ID'] ?></h3>
                            <h3><?= $user['Fullname'] ?></h3>
                            <h5><?= $user['Username'] ?></h5>
                            <p><?= $user['Email'] ?></p>
                            <p><?= $user['Joindate'] ?></p>
                        </li>
                        <form action="./api/edit_user.php" method="post">
                            <button id="btn-update_user" name="edit" class="btn btn-secondary"
                                value="<?= $user['ID'] ?>">Edit User</button>
                        </form>
                        <?php endforeach; ?>

                    </ul>
                </section>
                <!-- $query = "SELECT CardID as ID, Name, Foil, Promo, setCode FROM Cards"; -->
                <section id="cards" class="overflow-auto" style="height:400px;">
                    <h1>Site Cards</h1>
                    <ul>
                        <?php foreach ($cards as $card) : ?>
                        <li>
                            <h3><?= $card['ID'] ?></h3>
                            <h3><?= $card['Name'] ?></h3>
                            <h5><?= $card['Foil'] ?></h5>
                            <p><?= $card['Promo'] ?></p>
                            <p><?= $card['setCode'] ?></p>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </section>
                <!-- $query = "SELECT CollectionID as ID, OwnerID as Owner, CollectionName as Name FROM Cards"; -->
                <section id="collections" class="overflow-auto" style="height:400px;">
                    <h1>Site Collections</h1>
                    <ul>
                        <?php foreach ($cards as $card) : ?>
                        <li>
                            <h3><?= $card['ID'] ?></h3>
                            <h3><?= $card['Owner'] ?></h3>
                            <h5><?= $card['Name'] ?></h5>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </section>
            </div>
        </div>
    </main>
    <footer>

    </footer>
</body>

</html>