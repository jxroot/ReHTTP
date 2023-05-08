<?php
session_start();
require_once "../config/db.php";
global $connection;
if (isset($_SESSION['login'])) {
    header("location:index.php");
    die();

}
if (isset($_POST['btn'])) {

    if (isset($_POST['username']) and !empty($_POST['username']) and isset($_POST['password']) and !empty($_POST['password'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $sql = $connection->prepare("select * from admin WHERE username=? ");
        $sql->bindValue(1, $username);
        $sql->execute();
        if ($sql->rowCount() >= 1) {
            $data = $sql->fetch(PDO::FETCH_ASSOC);
            $username = $data['username'];
            $hash = $data['password'];
            if (password_verify($password, $hash)) {
                $_SESSION['login'] = "ok";
                header("location:index.php");


            }else {
                header("location:?error=Password incurrect ):");
    
            }

        } else {
            header("location:?error=Username NotFound ):");

        }
    }else {
        header("location:?error=Some Input Not Set ):");


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
    <style>
        @font-face {
            font-family: 'Source Sans Pro';
            font-style: normal;
            font-weight: 200;
            src: url(https://fonts.gstatic.com/s/sourcesanspro/v21/6xKydSBYKcSV-LCoeQqfX1RYOo3i94_wlxdr.ttf) format('truetype');
        }

        @font-face {
            font-family: 'Source Sans Pro';
            font-style: normal;
            font-weight: 300;
            src: url(https://fonts.gstatic.com/s/sourcesanspro/v21/6xKydSBYKcSV-LCoeQqfX1RYOo3ik4zwlxdr.ttf) format('truetype');
        }

        * {
            margin: 0;
            padding: 0;
            font-weight: 300;
        }

        body {
            font-family: 'Source Sans Pro', sans-serif;
            color: white;
            font-weight: 300;
        }

        body ::-webkit-input-placeholder {
            /* WebKit browsers */
            font-family: 'Source Sans Pro', sans-serif;
            color: white;
            font-weight: 300;
        }

        body :-moz-placeholder {
            /* Mozilla Firefox 4 to 18 */
            font-family: 'Source Sans Pro', sans-serif;
            color: white;
            opacity: 1;
            font-weight: 300;
        }

        body ::-moz-placeholder {
            /* Mozilla Firefox 19+ */
            font-family: 'Source Sans Pro', sans-serif;
            color: white;
            opacity: 1;
            font-weight: 300;
        }

        body :-ms-input-placeholder {
            /* Internet Explorer 10+ */
            font-family: 'Source Sans Pro', sans-serif;
            color: white;
            font-weight: 300;
        }

        .wrapper {
            background: #50a3a2;
            background: linear-gradient(to bottom right, #50a3a2 0%, #53e3a6 100%);
            position: absolute;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .wrapper.form-success .container h1 {
            transform: translateY(85px);
        }

        .container {
            max-width: 600px;
            margin: 9% auto;
            padding: 80px 0;
            height: 400px;
            text-align: center;
        }

        .container h1 {
            font-size: 40px;
            transition-duration: 1s;
            transition-timing-function: ease-in-put;
            font-weight: 200;
        }

        form {
            padding: 20px 0;
            position: relative;
            z-index: 2;
        }

        form input {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            outline: 0;
            border: 1px solid rgba(255, 255, 255, 0.4);
            background-color: rgba(255, 255, 255, 0.2);
            width: 250px;
            border-radius: 3px;
            padding: 10px 15px;
            margin: 0 auto 10px auto;
            display: block;
            text-align: center;
            font-size: 18px;
            color: white;
            transition-duration: 0.25s;
            font-weight: 300;
        }

        form input:hover {
            background-color: rgba(255, 255, 255, 0.4);
        }

        form input:focus {
            background-color: white;
            width: 300px;
            color: #53e3a6;
        }

        form button {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            outline: 0;
            background-color: white;
            border: 0;
            padding: 10px 15px;
            color: #53e3a6;
            border-radius: 3px;
            width: 250px;
            cursor: pointer;
            font-size: 18px;
            transition-duration: 0.25s;
        }

        form button:hover {
            background-color: #f5f7f9;
        }

        .bg-bubbles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .bg-bubbles li {
            position: absolute;
            list-style: none;
            display: block;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.15);
            bottom: -160px;
            -webkit-animation: square 25s infinite;
            animation: square 25s infinite;
            transition-timing-function: linear;
        }

        .bg-bubbles li:nth-child(1) {
            left: 10%;
        }

        .bg-bubbles li:nth-child(2) {
            left: 20%;
            width: 80px;
            height: 80px;
            -webkit-animation-delay: 1s;
            animation-delay: 1s;
            -webkit-animation-duration: 17s;
            animation-duration: 17s;
        }

        .bg-bubbles li:nth-child(3) {
            left: 25%;
            -webkit-animation-delay: 4s;
            animation-delay: 4s;
        }

        .bg-bubbles li:nth-child(4) {
            left: 40%;
            width: 60px;
            height: 60px;
            -webkit-animation-duration: 21s;
            animation-duration: 21s;
            background-color: rgba(255, 255, 255, 0.25);
        }

        .bg-bubbles li:nth-child(5) {
            left: 70%;
        }

        .bg-bubbles li:nth-child(6) {
            left: 80%;
            width: 120px;
            height: 120px;
            -webkit-animation-delay: 3s;
            animation-delay: 3s;
            background-color: rgba(255, 255, 255, 0.2);
        }

        .bg-bubbles li:nth-child(7) {
            left: 32%;
            width: 160px;
            height: 160px;
            -webkit-animation-delay: 7s;
            animation-delay: 7s;
        }

        .bg-bubbles li:nth-child(8) {
            left: 55%;
            width: 20px;
            height: 20px;
            -webkit-animation-delay: 15s;
            animation-delay: 15s;
            -webkit-animation-duration: 40s;
            animation-duration: 40s;
        }

        .bg-bubbles li:nth-child(9) {
            left: 25%;
            width: 10px;
            height: 10px;
            -webkit-animation-delay: 1s;
            animation-delay: 1s;
            -webkit-animation-duration: 40s;
            animation-duration: 40s;
            background-color: rgba(255, 255, 255, 0.3);
        }

        .bg-bubbles li:nth-child(10) {
            left: 90%;
            width: 160px;
            height: 160px;
            -webkit-animation-delay: 11s;
            animation-delay: 11s;
        }

        @-webkit-keyframes square {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(-700px) rotate(600deg);
            }
        }

        @keyframes square {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(-700px) rotate(600deg);
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <h1>Login</h1>

            <form class="form" method="POST">
                <input type="text" placeholder="Username" name="username" autocomplete="off">
                <input type="password" placeholder="Password" name="password" autocomplete="off">
                <button type="submit" name="btn">Connect</button>
            </form>

            <?php
            if (isset($_GET['error'])) {
                echo '<b style="color:red">' . trim(htmlspecialchars($_GET['error'])) . '</b>';
            }

            ?>
        </div>

        <ul class="bg-bubbles">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>

        </ul>
    </div>
</body>

</html>