<?php
// 1. Fehleranzeige aktivieren
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. Datenbank einbinden
require_once __DIR__ . '/../db_config.php'; 

// Passwort-Validierungsfunktion
function validatePassword($password) {
    $errors = [];
    
    if (strlen($password) < 9) {
        $errors[] = "mindestens 9 Zeichen";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "mindestens einen Großbuchstaben";
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "mindestens einen Kleinbuchstaben";
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "mindestens eine Zahl";
    }
    
    return $errors;
}

// TOTP 2FA Funktionen
function verifyTOTP($secret, $code, $timeSlice = null) {
    if ($timeSlice === null) {
        $timeSlice = floor(time() / 30);
    }
    for ($i = -1; $i <= 1; $i++) {
        if (getTOTPCode($secret, $timeSlice + $i) === $code) {
            return true;
        }
    }
    return false;
}

function getTOTPCode($secret, $timeSlice) {
    $secret = base32Decode($secret);
    $time = pack('N*', 0, $timeSlice);
    $hmac = hash_hmac('sha1', $time, $secret, true);
    $offset = ord(substr($hmac, -1)) & 0x0F;
    $code = (((ord($hmac[$offset]) & 0x7F) << 24) | ((ord($hmac[$offset + 1]) & 0xFF) << 16) | ((ord($hmac[$offset + 2]) & 0xFF) << 8) | (ord($hmac[$offset + 3]) & 0xFF)) % 1000000;
    return str_pad($code, 6, '0', STR_PAD_LEFT);
}

function base32Decode($input) {
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $input = strtoupper(str_replace('=', '', $input));
    $output = '';
    $buffer = 0;
    $bitsLeft = 0;
    for ($i = 0; $i < strlen($input); $i++) {
        $val = strpos($alphabet, $input[$i]);
        if ($val === false) continue;
        $buffer = ($buffer << 5) | $val;
        $bitsLeft += 5;
        if ($bitsLeft >= 8) {
            $bitsLeft -= 8;
            $output .= chr(($buffer >> $bitsLeft) & 0xFF);
        }
    }
    return $output;
}

$message = "";

// 3. Token aus GET oder POST holen
$token = $_GET['token'] ?? $_POST['token'] ?? '';

// 4. Prüfen ob 2FA-Spalte existiert
$has2FAColumn = false;
$checkColumn = $mysqli->query("SHOW COLUMNS FROM user LIKE 'two_factor_secret'");
if ($checkColumn && $checkColumn->num_rows > 0) {
    $has2FAColumn = true;
}

// 5. Token validieren und User-Daten laden
$user = null;
$requires2FA = false;

if (!empty($token)) {
    if ($has2FAColumn) {
        $stmt = $mysqli->prepare("SELECT id, two_factor_secret FROM user WHERE reset_token = ? AND reset_expiry > NOW()");
    } else {
        $stmt = $mysqli->prepare("SELECT id FROM user WHERE reset_token = ? AND reset_expiry > NOW()");
    }
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    if ($user && $has2FAColumn && !empty($user['two_factor_secret'])) {
        $requires2FA = true;
    }
}

// Wenn kein gültiger User gefunden wurde
if (!$user) {
    ?>
    <!DOCTYPE html>
    <html lang="de">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../style.css">
        <title>Link ungültig - Cockpit Corner</title>
    </head>
    <body>
        <header class="hero-section">
            <div class="hero-banner">
                <div class="hero-content">
                    <span class="greeting-text">Fehler</span>
                    <h1>Link <span class="hero-highlight">ungültig</span></h1>
                    <p>Der Link ist abgelaufen oder wurde bereits verwendet.</p>
                    <div style="margin-top: 2rem;">
                        <a href="passwort_vergessen.php" class="cta-button-primary">Neuen Link anfordern</a>
                    </div>
                    <p style="margin-top: 2rem;">
                        <a href="login/loginformular.php" style="color: #90cdf4;">← Zurück zum Login</a>
                    </p>
                </div>
            </div>
        </header>
    </body>
    </html>
    <?php
    exit;
}

// 6. Passwort-Änderung verarbeiten
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pw1 = $_POST['pw1'] ?? '';
    $pw2 = $_POST['pw2'] ?? '';
    $twoFactorCode = $_POST['two_factor_code'] ?? '';

    if (empty($pw1) || empty($pw2)) {
        $message = "<div class='greeting' style='border-color: #ef4444;'><span class='greeting-text' style='color: #ef4444;'>✗ Bitte beide Passwortfelder ausfüllen.</span></div>";
    } elseif ($pw1 !== $pw2) {
        $message = "<div class='greeting' style='border-color: #ef4444;'><span class='greeting-text' style='color: #ef4444;'>✗ Die Passwörter stimmen nicht überein.</span></div>";
    } else {
        $passwordErrors = validatePassword($pw1);
        
        if (!empty($passwordErrors)) {
            $errorText = "Das Passwort benötigt " . implode(", ", $passwordErrors) . ".";
            $message = "<div class='greeting' style='border-color: #ef4444;'><span class='greeting-text' style='color: #ef4444;'>✗ " . $errorText . "</span></div>";
        } 
        elseif ($requires2FA && empty($twoFactorCode)) {
            $message = "<div class='greeting' style='border-color: #ef4444;'><span class='greeting-text' style='color: #ef4444;'>✗ Bitte gib deinen 2FA-Code ein.</span></div>";
        }
        elseif ($requires2FA && !verifyTOTP($user['two_factor_secret'], preg_replace('/\s+/', '', $twoFactorCode))) {
            $message = "<div class='greeting' style='border-color: #ef4444;'><span class='greeting-text' style='color: #ef4444;'>✗ Ungültiger 2FA-Code.</span></div>";
        }
        else {
            // Alles OK - Passwort speichern
            // SHA-512 Hash verwenden (wie bei Registrierung und Login)
            $hashedPassword = hash('sha512', $pw1);
            
            $update = $mysqli->prepare("UPDATE user SET passwort = ?, reset_token = NULL, reset_expiry = NULL WHERE id = ?");
            $update->bind_param("si", $hashedPassword, $user['id']);
            
            if ($update->execute()) {
                if ($update->affected_rows > 0) {
                    $message = "<div class='greeting' style='border-color: #10b981;'><span class='greeting-text' style='color: #10b981;'>✓ Passwort erfolgreich geändert! Du wirst zum Login weitergeleitet...</span></div>";
                    header("refresh:3;url=login/loginformular.php");
                } else {
                    $message = "<div class='greeting' style='border-color: #ef4444;'><span class='greeting-text' style='color: #ef4444;'>✗ Keine Änderung vorgenommen. User-ID: " . $user['id'] . "</span></div>";
                }
            } else {
                $message = "<div class='greeting' style='border-color: #ef4444;'><span class='greeting-text' style='color: #ef4444;'>✗ Datenbankfehler: " . htmlspecialchars($mysqli->error) . "</span></div>";
            }
            $update->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style.css">
    <title>Neues Passwort - Cockpit Corner</title>
    <style>
        .password-requirements {
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            text-align: left;
        }
        .password-requirements p {
            margin: 0 0 0.5rem 0;
            font-size: 0.9rem;
            color: #a0aec0;
        }
        .requirement {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: #718096;
            margin: 0.3rem 0;
        }
        .requirement.valid { color: #10b981; }
        .requirement.invalid { color: #ef4444; }
        .requirement-icon { width: 16px; text-align: center; }
        .two-factor-section {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
        }
        .two-factor-section label {
            display: block;
            color: #90cdf4;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <header class="hero-section">
        <div class="hero-banner">
            <div class="hero-content">
                <span class="greeting-text">Sicherheit</span>
                <h1>Neues <span class="hero-highlight">Passwort</span></h1>
                <p>Gib dein neues Passwort für deinen Cockpit Corner Account ein.</p>

                <?php echo $message; ?>

                <form method="POST" style="width: 100%; max-width: 400px; margin-top: 2rem;" id="passwordForm">
                    <!-- Token als hidden field mitgeben -->
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    
                    <div class="password-requirements">
                        <p><strong>Passwort-Anforderungen:</strong></p>
                        <div class="requirement" id="req-length">
                            <span class="requirement-icon">○</span>
                            <span>Mindestens 9 Zeichen</span>
                        </div>
                        <div class="requirement" id="req-upper">
                            <span class="requirement-icon">○</span>
                            <span>Mindestens ein Großbuchstabe (A-Z)</span>
                        </div>
                        <div class="requirement" id="req-lower">
                            <span class="requirement-icon">○</span>
                            <span>Mindestens ein Kleinbuchstabe (a-z)</span>
                        </div>
                        <div class="requirement" id="req-number">
                            <span class="requirement-icon">○</span>
                            <span>Mindestens eine Zahl (0-9)</span>
                        </div>
                    </div>
                    
                    <input type="password" name="pw1" id="pw1" placeholder="Neues Passwort" required minlength="9"
                           style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.1); color: white; margin-bottom: 1rem;">
                    
                    <input type="password" name="pw2" id="pw2" placeholder="Passwort wiederholen" required minlength="9"
                           style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.1); color: white; margin-bottom: 1rem;">
                    
                    <?php if ($requires2FA): ?>
                    <div class="two-factor-section">
                        <label for="two_factor_code">🔐 2-Faktor-Authentifizierung</label>
                        <input type="text" name="two_factor_code" id="two_factor_code" 
                               placeholder="6-stelliger Code aus deiner App" 
                               required maxlength="6" pattern="[0-9]{6}" inputmode="numeric" autocomplete="one-time-code"
                               style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid rgba(59, 130, 246, 0.3); background: rgba(255,255,255,0.1); color: white; text-align: center; font-size: 1.5rem; letter-spacing: 0.5rem;">
                    </div>
                    <?php endif; ?>
                    
                    <button type="submit" class="cta-button-primary" style="width: 100%;">Passwort speichern</button>
                </form>
                
                <p style="margin-top: 2rem;">
                    <a href="login/loginformular.php" style="color: #90cdf4;">← Zurück zum Login</a>
                </p>
            </div>
        </div>
    </header>

    <script>
        const pw1 = document.getElementById('pw1');
        const requirements = {
            length: { element: document.getElementById('req-length'), test: (pw) => pw.length >= 9 },
            upper: { element: document.getElementById('req-upper'), test: (pw) => /[A-Z]/.test(pw) },
            lower: { element: document.getElementById('req-lower'), test: (pw) => /[a-z]/.test(pw) },
            number: { element: document.getElementById('req-number'), test: (pw) => /[0-9]/.test(pw) }
        };
        
        function updateRequirements() {
            const password = pw1.value;
            for (const [key, req] of Object.entries(requirements)) {
                const isValid = req.test(password);
                req.element.classList.toggle('valid', isValid);
                req.element.classList.toggle('invalid', !isValid && password.length > 0);
                req.element.querySelector('.requirement-icon').textContent = isValid ? '✓' : '○';
            }
        }
        
        pw1.addEventListener('input', updateRequirements);
        
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            const password = pw1.value;
            const password2 = document.getElementById('pw2').value;
            const allValid = Object.values(requirements).every(req => req.test(password));
            
            if (!allValid) {
                e.preventDefault();
                alert('Bitte erfülle alle Passwort-Anforderungen.');
                return false;
            }
            if (password !== password2) {
                e.preventDefault();
                alert('Die Passwörter stimmen nicht überein.');
                return false;
            }
        });
    </script>
</body>
</html>