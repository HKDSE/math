<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>url down</title>
</head>
<body >
<form method="post">
<li>File: <input name="url" size="40" />
<input name="submit" type="submit" /></li>
<li>Pass: <input name="password" type="password" /></li>
</form> 
<?php

set_time_limit (0); //不限時 24 * 60 * 60
$password = ''; //管理密碼
$pass = $_POST['password'];
if ($pass == $password) {
class runtime {
var $StartTime = 0;
var $StopTime = 0;
function get_microtime(){list($usec, $sec) = explode(' ', microtime());
return ((float)$usec + (float)$sec);}
function start() {$this->StartTime = $this->get_microtime();}
function stop() {$this->StopTime = $this->get_microtime();}
function spent() { return round(($this->StopTime - $this->StartTime) * 1000, 1);}
}
$runtime= new runtime;
$runtime->start();
if (!isset($_POST['submit'])) die();
$destination_folder = './'; // 下載的檔保存目錄。必須以斜杠結尾
if(!is_dir($destination_folder)) //判斷目錄是否存在
mkdir($destination_folder,0777); //若無則創建，並給與777許可權 windows忽略
$url = $_POST['url'];
$headers = get_headers($url, 1); //得到檔大小
if ((!array_key_exists("Content-Length", $headers))) {$filesize=0; }
$newfname = $destination_folder . basename($url);
$file = fopen ($url, "rb");
if ($file) {
$newf = fopen ($newfname, "wb");
if ($newf)
while(!feof($file)) {fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );}
}
if ($file) {fclose($file);}
if ($newf) {fclose($newf);}
$runtime->stop();
echo '<br /><li>下載耗時:<font color="blue"> '.$runtime->spent().' </font>微秒,文件大小<font color="blue"> '.$headers["Content-Length"].' </font>位元組</li>';
echo '<br /><li><font color="red">下載成功！ '.$showtime=date("Y-m-d H:i:s").'</font></li>';
}elseif(isset($_POST['password'])){
echo '<br /><li><font color="red">密碼錯誤！請從新輸入密碼!</font></li>';
}
?>
</body>
</html>
