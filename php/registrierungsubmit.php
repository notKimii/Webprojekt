<?php
	include 'include/debug.php';

	session_start();

	include 'include/vendorconnect.php';
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	// Formulardaten
	$vorname = $_POST["vorname"];
	$nachname = $_POST["nachname"];
	$mail = $_POST["mail"];
	$adresse = $_POST["adresse"];
	$plz = $_POST["plz"];
	$ort = $_POST["ort"];

	//Passwort generieren
	function generatePassword($length = 10) {
    $lower = 'abcdefghijklmnopqrstuvwxyz';
    $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';
    $allChars = $lower . $upper . $numbers;

    $password = '';
    $password .= $lower[random_int(0, strlen($lower) - 1)];
    $password .= $upper[random_int(0, strlen($upper) - 1)];
    $password .= $numbers[random_int(0, strlen($numbers) - 1)];

    for ($i = strlen($password); $i < $length; $i++) {
        $password .= $allChars[random_int(0, strlen($allChars) - 1)];
    }

    // Passwort mischen
    $password = str_shuffle($password);

    return $password;
	}

	$plainPassword = generatePassword(10);
	// SHA512
	$password='';
	$password = hash('sha512', $plainPassword);

	// Google Authenticator vorbereiten
	// $gAuth = new PHPGangsta_GoogleAuthenticator();
	// $secret = $gAuth->createSecret();

	try {
		// Prüfung auf doppelten Eintrag
		include 'include/connect.php';
		$stmt = $con->prepare("SELECT COUNT(*) FROM user WHERE mail = ?");
		$stmt->execute([$mail]);
		$anzahl = $stmt->fetchColumn();

		if ($anzahl > 0) {
			$_SESSION['form_data'] = $_POST;
			$_SESSION['mail_error'] = "Diese E-Mail-Adresse ist bereits registriert.";
			header("Location: registrierung.php");
			exit;
		}
		$stmt = $con->prepare("INSERT INTO user (vorname, nachname, mail, adresse, plz, ort, passwort, google_secret) 
		VALUES (?, ?, ?, ?, ?, ?, ?,?)");
		$stmt->execute([$vorname, $nachname, $mail, $adresse, $plz, $ort, $password, NULL]);

		// E-Mail vorbereiten
		$mailer = new PHPMailer(true);
		$mailer->isSMTP();
		$mailer->Host = 'smtp.mailbox.org';
		$mailer->SMTPAuth = true;
		$mailer->Username = 'cockpitcorner@mailbox.org';
		$mailer->Password = 'Mailbox.123';
		$mailer->SMTPSecure = 'tls';
		$mailer->Port = 587;

		$mailer->setFrom('cockpitcorner@mailbox.org', 'Cockpit Corner');
		$mailer->addAddress($mail);
		$mailer->Subject = 'Willkommen bei Cockpit Corner';
		$mailer->isHTML(true);
		$body = "<p>Herzlich Willkommen $vorname $nachname! 
			<br><br>
			Sie haben sich erfolgreich bei <i>'Cockpit Corner' </i>registriert.
			<br><br><br> 
			Ihre <strong>Anmeldedaten</strong> lauten wie folgt: <br>
				Vorname: $vorname <br>
				Nachname: $nachname <br>
				E-Mail Adresse: $mail <br>
				Vorl&auml;iges Passwort: $plainPassword <br><br>
			Als <strong>Lieferadresse </strong> haben sie folgende Adresse angeben: <br> $adresse <br> $plz $ort
			<br><br>
			Sie k&ouml;nnen diese Adresse auf unserer Webseite unter der Katergorie Kundenkonto ab&auml;ndern! 
			<br>Viel Spa&szlig; beim Shoppen!<br><br>Happy Landings<br>Ihr CockpitCorner-Team</p>";
		$mailer->Body = $body;
		// E-Mail senden
		$mailer->send();

		// Session starten und weiter zur 2FA-Seite
		// session_start();
		// $_SESSION["username"] = $vorname;
		// $_SESSION["mail"] = $mail;
		// $_SESSION["google_secret"] = $secret;

		// header("Location: qr2fa.php");
		// exit;

	} 
	catch (Exception $e) {
		$_SESSION['form_data'] = $_POST;
		$_SESSION['mail_error'] = "Die E-Mail konnte nicht gesendet werden.";
		header("Location: registrierung.php");
		exit;
	}
// Google Authenticator Secret generieren
// $gAuth = new PHPGangsta_GoogleAuthenticator();
// $secret = $gAuth->createSecret();

// // QR-Code URL für Google Authenticator
// $websiteName = 'Pilotenshop'; // Oder dein Projektname
// $qrCodeUrl = $gAuth->getQRCodeGoogleUrl($websiteName, $secret);


//prüfung ob secret code schon vorhanden in db
?>

