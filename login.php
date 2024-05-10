<?php
ob_start();
session_start();
include("db_config.php");
ini_set('display_errors', 1);

?>

<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Simple Login Page with SQL Injection</title>
    <link href="css/htmlstyles.css" rel="stylesheet">
</head>
<body>
<div class="container-narrow">
    <div class="jumbotron">
        <p class="lead" style="color:white">
            Simple Login Page with SQL Injection
        </p>
    </div>
    <div class="response">
        <form method="POST" autocomplete="off">
            <p style="color:white">
                Username: <input type="text" id="uid" name="uid"><br/><br/>
                Password: <input type="password" id="password" name="password">
            </p>
            <br/>
            <p>
                <input type="submit" value="Submit"/>
                <input type="reset" value="Reset"/>
            </p>
        </form>
    </div>
    <br/>
    <div class="row marketing">
        <div class="col-lg-6">
            <?php

            if (!empty($_GET['msg'])) {
                echo "<font style=\"color:#FF0000\">Please login to continue.<br/></font>";
            }

            if (!empty($_POST['uid']) && !empty($_POST['password'])) {
                $username = mysqli_real_escape_string($con, $_POST['uid']);
                $password = mysqli_real_escape_string($con, $_POST['password']);

                // Using parameterized query to prevent SQL injection
                $stmt = mysqli_prepare($con, "SELECT * FROM users WHERE username=? AND password=?");
                mysqli_stmt_bind_param($stmt, "ss", $username, md5($password));
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (!$result) {
                    die('Error: ' . mysqli_error($con));
                }

                $row_cnt = mysqli_num_rows($result);

                if ($row_cnt > 0) {
                    $row = mysqli_fetch_array($result);
                    if ($row) {
                        $_SESSION["username"] = $row['username'];
                        $_SESSION["name"] = $row['name'];
                        header('Location: home.php');
                    }
                } else {
                    echo "<font style=\"color:#FF0000\"><br/>Invalid password!</font>";
                }
            }
            ?>
        </div>
    </div>
    <div class="footer">
        <p>Anmol Chadha || Lochan Jain</p>
    </div>
</div> <!-- /container -->
</body>
</html>