<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cockpit Corner | Zwei-Faktor-Authentifizierung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f8fb;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            max-width: 500px;
            margin-top: 50px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .qr-img {
            margin: 20px 0;
        }

        .btn-shop {
            margin-top: 30px;
        }

        .secret-box {
            background: #f1f1f1;
            padding: 10px;
            font-family: monospace;
            border-radius: 5px;
            display: inline-block;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>2-Faktor-Authentifizierung</h1>
        <p>Scanne diesen QR-Code mit deiner <strong>Google Authenticator</strong> App:</p>
        <?php
        include 'include/vendorconnect.php';
        include 'include/connect.php';

        include 'include/loginpruef.php';
        $mail = $_SESSION["user"]['email'];

        //Secret abfragen
        $stmt = $conPDO->prepare("SELECT google_secret FROM user WHERE mail = ?");
        $stmt->execute([$mail]);
        $secret = $stmt->fetchColumn();

        //QR Code zum Secret erstellen
        $ga = new PHPGangsta_GoogleAuthenticator();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl('CockpitCorner', $secret);

        // QR-Code anzeigen
        echo "<div class='qr-img'>";
        echo "<img src='https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode("otpauth://totp/CockpitCorner:$username?secret=$secret") . "&size=200x200&ecc=M' alt='QR Code'>";
        echo "</div>";
        ?>

        <p>Oder gib diesen geheimen Schlüssel manuell ein:</p>
        <div class="secret-box"><?php echo $secret; ?> </div>
        <br>
        <a href='../php/passwortAendern.php' class='btn btn-primary btn-shop' style="background-color: #092291; color: white; border: 2px solid #092291; border-radius: 0;">Weiter zur Passwortänderung</a>

    </div>

</body>

</html>