<?php

/*
* This file should display the login form. Login and authentication process should use user credentials
* from the file users.txt
*/
error_reporting(E_ALL);
ini_set("display_errors", 1);
if (isset($_POST['loginBtn'])) {

    $uname = $_POST['username'];
    $pwd = $_POST['password'];
    $filename = "users.txt";
    $handle =  file_get_contents($filename); //read file and return as a string

    // echo $handle . "</br>"; //print as string
    $content = explode("\n", $handle); //when meet new line split into array

    // print_r($content); //print in array

    $data = array();
    foreach ($content as $values) {
        $userInfo = array_map('trim', explode("|", $values));

        // print_r ($userInfo) . "</br>";

        if (!isset($userInfo[1])) {
            $userInfo[1] = null;
        }
        $data[$userInfo[0]] = $userInfo[1];

        // to authenticate username and password
        if ($uname == $userInfo[0] && $pwd == $userInfo[1]) {
            // echo "OKAY </br>";
            session_start();
            $_SESSION['$username'] = $uname;
            header('Location: index.php');
            break;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>

    <div class="container">
        <h5>Login page</h5>
        <!-- form -->
        <div>
            <form id="loginForm" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" aria-describedby="usernameHelp" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>
                </br>
                <input type="submit" class="btn btn-primary" name="loginBtn" value="Sign in"></input>
            </form>
        </div>

    </div>


    <!-- Script -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>

</html>