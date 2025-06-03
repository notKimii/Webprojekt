<?php
    include 'include/vendorconnect.php';

    use PHPMailer\PHPMailer\PHPMailer;
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
?>