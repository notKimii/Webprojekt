<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PHP Basics</title>
        <?php
        ?>

        <!--Bootstrap CSS-->
        <link href="extern/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="<?php echo "container"; ?>">
            <br><br>
            
            <form method="POST" action="php/login.php">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" aria-describeby="emailHelp" name="email">
                </div>
                <div class="mb-3">
                    <label for="password1" class="form-lable">Password:</label>
                    <input type="password" class="form-control" id="password1" name="password">
                </div>
                <div class="mb-3">
                    <label for="2facode" class="form-lable">2FA Code:</label>
                    <input type="2facode" class="form-control" id="2facode" name="2facode">
                </div>

                <button type="submit" class="btn btn-primary">Login</button>

            </form>
         

        </div>

        <!--JS Bootstrap-->
        <script src="extern/bootstrap/js/bootstrap.bundle.min.js"></script>
    </body>
</html>