<?php
    //echo"Login";
    //var_dump($_POST);
    //exit;
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";
    $twofa = $_POST["2facode"] ?? "";
    


    $checkresult = false;
    require_once "../extern/google_auth/PHPGangsta/GoogleAuthenticator.php";

    //secret holt man sich aus der datenbank
    $secret= "CSOA6OJIMOEUEXXD";



    echo "email: ".$email."     pass: ".$password;
    /*
    if($password==="testpassword"){

        //2FA
        $ga = new PHPGangsta_googleAuthenticator();
        $checkresult = $ga->veryfyCode($secret, $twofa, 2); //2 = 2*30sec clock tolerance

        if($checkresult){
            echo "Login succsefull";
        }
        else{
            echo "invalid 2FAcode";
        }
    }

    
    if($checkresult)
    {
        header("location: ../portal.php");
    }
    else{
        header("location: ../index.php");
    }
    */
    
?>