<?php
require('connection.php');

$query = "SELECT * FROM Cards";
$statement = $db->prepare($query);
$statement->execute();

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
    <title>Home Page</title>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>

<body>
    <header>
        <div class="container">
            <div class="d-flex flex-row align-items-center justify-content-between mt-3">
                <img id="logo" src="./images/icons/logo.png" alt="Logo">
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
                    <a href="./login.php" id="login" class="d-flex flex-column align-items-center mr-3">
                        <img src="./images/icons/login.png" alt="Login">
                        <h6 class="">LOGIN</h6>
                    </a>
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