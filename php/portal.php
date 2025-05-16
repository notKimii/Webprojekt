<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo "Titel"; ?></title>
    </head>
    <body>
        <h1>Welcome to our portal</h1>
        <?php

            require_once __DIR__.'/google_auth/PHPGangsta/GoogleAuthenticator.php';
            $ga = new PHPGangsta_GoogleAuthenticator();
            $fixedSecret="CSOA6OJIMOEUEXXD";
            $qrCodeUrlBlob2 = $ga->getQRCodeGoogleUrl('Blog', $fixedSecret);

            echo "<img src='https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode("otpauth://totp/Blog?secret=$fixedSecret") . "&size=200x200&ecc=M'>";


            echo "<br>";
            echo "Neuer Code:".$ga->createSecret();
        ?>
        <a href='../php/basis.php'>Weiter zum Shop</a>
    </body>
</html>