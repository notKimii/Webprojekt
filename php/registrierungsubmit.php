<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$mailFehler = '';
if (isset($_SESSION['mail_error'])) {
    $mailFehler = $_SESSION['mail_error'];
    unset($_SESSION['mail_error']);
}
?>

<?php
	require_once __DIR__ . '/../vendor/autoload.php';
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	// Formulardaten
	$vorname = $_POST["vorname"];
	$nachname = $_POST["nachname"];
	$mail = $_POST["mail"];
	$adresse = $_POST["adresse"];
	$plz = $_POST["plz"];
	$ort = $_POST["ort"];
	$passwort = password_hash($_POST["password"], PASSWORD_DEFAULT); // besser als SHA512

	// Google Authenticator vorbereiten
	$gAuth = new PHPGangsta_GoogleAuthenticator();
	$secret = $gAuth->createSecret();

	// E-Mail vorbereiten
	$mailer = new PHPMailer(true);

	try {
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
				E-Mail Adresse: $mail <br><br>
			Als <strong>Lieferadresse </strong> haben sie folgende Adresse angeben: <br> $adresse <br> $plz $ort
			<br><br>
			Sie k&ouml;nnen diese Adresse auf unserer Webseite unter der Katergorie Kundenkonto ab&auml;ndern! 
			<br>Viel Spa&szlig; beim Shoppen!<br><br>Happy Landings<br>Ihr CockpitCorner-Team</p>";
		$mailer->Body = $body;
		// E-Mail senden
		$mailer->send();

		//  Wenn Mail erfolgreich → in DB einfügen
		$pdo = new PDO("mysql:host=localhost;dbname=dbPilotenshop", "root", "");
		$stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE mail = ?");
		$stmt->execute([$mail]);
		$anzahl = $stmt->fetchColumn();

		if ($anzahl > 0) {
			session_start();
			$_SESSION['form_data'] = $_POST;
			$_SESSION['mail_error'] = "Diese E-Mail-Adresse ist bereits registriert.";
			header("Location: registrierung.php");
			exit;
		}

		$stmt = $pdo->prepare("INSERT INTO user (vorname, nachname, mail, adresse, plz, ort, passwort, google_secret) 
							VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->execute([$vorname, $nachname, $mail, $adresse, $plz, $ort, $passwort, $secret]);

		// Session starten und weiter zur 2FA-Seite
		session_start();
		$_SESSION["username"] = $vorname;
		$_SESSION["mail"] = $mail;
		$_SESSION["google_secret"] = $secret;

		header("Location: qr2fa.php");
		exit;

	} 
	catch (Exception $e) {
		// ❌ Wenn Mail-Versand fehlschlägt
		session_start();
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

// // User speichern
// $stmt = $pdo->prepare("
//     INSERT INTO user (vorname, nachname, adresse, plz, ort, mail, passwort, google_secret)
//     VALUES (:vorname, :nachname, :adresse, :plz, :ort, :mail, :passwort, :google_secret)
// ");
// $stmt->execute([
//     'vorname' => $_POST['vorname'],
//     'nachname' => $_POST['nachname'],
//     'adresse' => $_POST['adresse'],
//     'plz' => $_POST['plz'],
//     'ort' => $_POST['ort'],
//     'mail' => $_POST['mail'],
//     'passwort' => $hashedPassword,
//     'google_secret' => $secret
// ]);


// echo "<h2>Registrierung erfolgreich!</h2>";
// echo "<p>Bitte scanne diesen QR-Code mit deiner Google Authenticator App:</p>";
// echo "<img src='$qrCodeUrl' alt='QR-Code'>";
// echo "<p>Oder gib diesen Schlüssel manuell ein: <strong>$secret</strong></p>";
// echo "<a href='login.html'>Jetzt einloggen</a>";
?>
