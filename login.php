<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();
require 'inc/connect.php';
$msg = "";
// Initialize the session


// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}

if (isset($_POST['submit'])) {


    $email = $_POST['email'];
    //$password = $con->real_escape_string($_POST['password']);
    $password = $_POST['password'];

    if ($email == "" || $password == "")
        $msg = "Please check your inputs!";
    else {
        $sql = $con->prepare("SELECT id, password, isEmailConfirmed FROM users WHERE email= ?");
        $sql->bind_param('s', $email);
        $sql->execute();
        $result = $sql->get_result();


        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            if (password_verify($password, $data['password'])) {
                if ($sql = $con->prepare("SELECT id, isEmailConfirmed FROM users WHERE email = ?"))
                {
                    $sql->bind_param('s', $email);
                    $sql->execute();
                    $result = $sql->get_result();
                    $sql->bind_result($id, $isemailconfirmed);

                    while($row = $result->fetch_assoc()) {
                        if($row['isEmailConfirmed'] == 1) {

                            // Store data in session variables
                    $_SESSION["loggedin"] = true;

                    $_SESSION['id'] = $row['id'];

                    $_SESSION["username"] = $email;

                    // Redirect user to welcome page
                   // header("location: index.php");
                    exit;
                        } else {
                            echo "Your account is not verified, please check your email!";
                        }
                    }
                }


                    

            } else
                $msg = "Please check your inputs!";
                

exit;
        } else {
            $msg = "Please check your inputs!";
        }
    }
}
?>



<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login | Sky Cargo Virtual</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#" style="font-size:30px;"><span style="color: #660099;">Fed</span><span style="color: #ff6600;">Ex</span></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/downloads">Downloads</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Contact</a>
            </li>
            <?php
            if ($_SESSION['loggedin']) {
                echo '<li class="nav-item">
                    <a class="nav-link" href="logout.php">Log Out</a>
                </li>';
            } else {
                echo '<li class="nav-item">
                    <a class="nav-link" href="login.php">Log In</a>
                </li>';
                echo '<li class="nav-item">
                    <a class="nav-link" href="register.php">Sign Up</a>
                </li>';
            }
            ?>
        </ul>

    </div>
</nav>
	<div class="container" style="margin-top: 100px;">
		<div class="row justify-content-center">
			<div class="col-md-6 col-md-offset-3" align="center">

				<img src="https://logodix.com/logo/2087366.jpg"><br><br>

				<?php if ($msg != "") echo $msg . "<br><br>" ?>

				<form method="post" action="login.php">
					<input class="form-control" name="email" type="email" placeholder="Email..."><br>
					<input class="form-control" name="password" type="password" placeholder="Password..."><br>
					<input class="btn btn-primary" type="submit" name="submit" value="Log In">
				</form>

			</div>
		</div>
	</div>
</body>
</html>