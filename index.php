<?php
require('./api/connection.php');

session_start();

$query = "SELECT * FROM Cards";
$statement = $db->prepare($query);
$statement->execute();

//Checks if they are logged in by checking if the loggin SESSION variable is set ..
//.. and if it is set to true
$logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'];

/**
 * 
 */
function DownloadImage($url, $cardID, $small)
{
    //Checks if the small image flag is set and checks if the image already exists...
    if ($small && !file_exists(__DIR__ . '\images\small\\' . $cardID . '.jpg')) {
        $img = __DIR__ . '\images\small\\' . $cardID . '.jpg';
    }
    //... otherwise checks if the small image flag is not set and checks if the iamge already exists...
    else if (!$small && !file_exists(__DIR__ . '\images\large\\' . $cardID . '.jpg')) {
        $img = __DIR__ . '\images\large\\' . $cardID . '.jpg';
    }
    //... otherwise returns false since there was an error
    else {
        return false;
    }

    //If successful, stores the downloaded image at the specified location
    $result = file_put_contents($img, file_get_contents($url));

    //Returns the result of the operation
    return $result != false;
}

/**
 * 
 */
function getImagePath($cardID, $small)
{
    if ($small && file_exists('.\\images\\small\\' . $cardID)) {
        return '.\\images\\small\\' . $cardID;
    } else if (file_exists('.\\images\\large\\' . $cardID)) {
        return '.\\images\\large\\' . $cardID;
    } else {
        return null;
    }
}

function console_log($output, $with_script_tags = true)
{
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
        ');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
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
    <title>Home Page</title>
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
        <h1>Main Start</h1>
        <div class="container">
            <div id="recently_added">
                <?php while ($row = $statement->fetch()) : ?>
                <li><?= $row['Name'] ?></li>
                <?php endwhile; ?>
                <span id="recently-added-vm" class="cursor-pointer">View All</span>
            </div>
        </div>
    </main>
    <footer>
        <h1>Footer Start</h1>
        <div class="container">
            <div id="attribution">
                <h5>Attributions</h5>
                <h6>Icons made by:</h6>
                <div>
                    <a href="https://www.freepik.com" title="Freepik">Freepik</a> from
                    <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a>
                </div>
                <div>
                    <a href="https://www.flaticon.com/authors/kiranshastry" title="Kiranshastry">Kiranshastry</a> from
                    <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a>
                </div>
                <div>
                    <a href="https://www.flaticon.com/authors/royyan-wijaya" title="Royyan Wijaya">Royyan Wijaya</a>
                    from
                    <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>