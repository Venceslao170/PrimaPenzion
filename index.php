<?php
require_once "./vendor/autoload.php";
require_once "./data.php";

$idStranky = array_keys($poleStranek)[0];

if (array_key_exists("id-stranky", $_GET)) {
  
    $idStranky = $_GET["id-stranky"];
}


?>
<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $poleStranek[$idStranky]->getTitulek(); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>

    <header>
        <div class="container">

            <div class="headerTop">
                <a class="tel" href="tel:+420606123456">+420 / 729 394 550</a>
                <div class="soc">
                    <a href="https://twitter.com" target="_blank">
                        <i class="fa-brands fa-x-twitter" target></i>
                    </a>
                    <a href="https://instagram.com" target="_blank">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="https://facebook.com" target="_blank">
                        <i class="fa-brands fa-square-facebook"></i>
                    </a>
                </div>
            </div>

            <a href="domu" class="logo">Prima<br>Penzion</a>

            <?php
            require "./menu.php";
            ?>

        </div>
        <img src="img/<?php echo $poleStranek[$idStranky]->getObrazek(); ?>" alt="Prima Penzion">
    </header>

    <section>

        <?php               

        $rawObsah = $poleStranek[$idStranky]->getObsah();
        echo
        primakurzy\Shortcode\Processor::process('./moje-pluginy', $rawObsah);

        ?>

    </section>

    <footer>

        <?php
        require "./menu.php";
        ?>

        <a href="domu" class="logo">prima<br>penzion</a>

        <div class="footerInfo">
            <p>
                <i class="fa-solid fa-location-dot"></i>
                <a class="tel" href="https://goo.gl/maps/mX2SCHxf59Vn4afF8" target="_blank"><strong>PrimaPenzion</strong>, Jablonského 2, Praha 7</a>
            </p>
            <p>
                <i class="fa-solid fa-phone"></i>
                <a class="tel" href="tel:+420606123456">+420 / 729 394 550</a>
            </p>
            <p>
                <i class="fa-regular fa-envelope"></i>
                <b>info@primapenzion.cz</b>
            </p>
        </div>

        <div class="soc">
            <a href="https://twitter.com" target="_blank">
                <i class="fa-brands fa-x-twitter" target></i>
            </a>
            <a href="https://instagram.com" target="_blank">
                <i class="fa-brands fa-instagram"></i>
            </a>
            <a href="https://facebook.com" target="_blank">
                <i class="fa-brands fa-square-facebook"></i>
            </a>
        </div>

        <div class="copy">&copy; <b>Prima Penzion</b> 2023
        </div>
    </footer>

</body>

</html>
