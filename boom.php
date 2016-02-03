<?php

/* ==================《BOOM網站寶貝 v2.0 繁體版》 基本配置 =============== */

	//--程式使用方式："0"為不用登入即可使用；"1"為要輸入管理員密碼才能使用。
	$set[mode]="1";

	//--預設管理密碼：admin 這裡設定的密碼是經過MD5加密的字符串，而不是明碼。
	$set[password]="21232f297a57a5a743894a0e4a801fc3";

/* ============================  配置結束 ================================ */


if($_GET[dir]!=""){	 $dir=$_GET[dir];}
elseif($_POST[dir]!=""){ $dir=$_POST[dir];}
else{	$dir="./";}

$style_head="
<HTML>
<HEAD>
<TITLE>→BOOM{title}←</TITLE>
<META content='text/html; charset=UTF-8' http-equiv=Content-Type>

<style type='text/css'>
  A:link    {color:000000; text-decoration: underline}
  A:active  {color:ff3333; text-decoration: underline}
  A:hover   {color:ffffff; text-decoration: underline; LEFT: 1px; POSITION: relative; TOP: 1px}
  A:visited {color:000000; text-decoration: underline}
  body  {FONT-FAMILY:新細明體; font-size=9pt; color:999999}
  TD  {FONT-SIZE: 9pt; color:000000; line-height: 150%}
  INPUT  {FONT-SIZE: 9pt; HEIGHT: 20px; PADDING-BOTTOM: 1px; PADDING-LEFT: 1px; PADDING-RIGHT: 1px; PADDING-TOP: 0px}
  textarea  {FONT-FAMILY:新細明體}
 .menu TD A {COLOR:ffffff; TEXT-DECORATION: none; WIDTH:100%; padding-top:2px}
 .menu TD A:hover {COLOR: 000000; TEXT-DECORATION: none; BACKGROUND-COLOR: bbbbbb; LEFT: 0px; TOP: 0px}
 .menu A:active {COLOR: ffffff; TEXT-DECORATION: none}
 .menu A:visited {COLOR: ffffff; TEXT-DECORATION: none}
</style>

</head>
<BODY BGCOLOR=000000 leftMargin=5 rightMargin=5 topMargin=0>
<DIV align=center>
<table width=750 border=0 bgcolor=666666 cellpadding=0 cellspacing=1 class=menu>
 <tr bgcolor=888888><td title='作者：刀鋒戰士　BOOM網絡帝國&#10;—————————+————&#10;歡迎交流討論PHP與WEB技術 :)'><a href=http://boom.cpgl.net>>>　　<font color=ffffff face='Tahoma'>BOOM網站寶貝 Ver2.0</font>　　<<</a></td>
<td width=80 align=center title='文件目錄列表及管理'><a href='?'>文件管理</a></td>
<td width=80 align=center title='生成大量的連續代碼'><a href='?m=code'>代碼生成</a></td>
<td width=80 align=center title='生成MD5加密後的字符串'><a href='?m=md5'>MD5加密</a></td>
<td width=80 align=center title='Unix時間換算成通用時間'><a href='?m=unixdate'>UNIX時間</a></td>
</tr>
</table>
<table width=750 border=0 bgcolor=666666 cellpadding=3 cellspacing=1>
<tr bgcolor=666666><td>管理員作業：[<a href='?login=1'><u>登入</u></a>|<a href='?login=3'><u>退出</u></a>]<br>當前打開目錄：{$dir}</td><td><font size=3 color=ffffff><b>當前作業：{title}</b></font></td></tr>
</table>
";


function getmicrotime()
{ //----執行時間
  list($usec, $sec) = explode(" ",microtime()); 
  return ($usec + $sec); 
}

function error_info($info,$url="javascript:history.back(1)")
{ //----錯誤提示
  echo"<meta http-equiv=refresh content=5;URL='$url'><center><br><br><font size=3 color=ff0000>$info</font></center></td></tr></table>";
  exit;
}

function skin_var($var1,$var2)
{ //----替換頁面變量
 global $style_head;
  $style_head=eregi_replace("\{$var1\}",$var2,$style_head);
}

/* ========================== 函數結束，開始程式 ========================= */


if($_GET[login]=="2"){
/*------------------------ 檢測密碼，並生成Cookie ----------------------*/
	$password=md5($_POST[password]);
	if ( $password != $set[password] ) {
		error_info("密碼錯誤！登入失敗</font>");
	}
	$time=time();

	if ( $_POST[yxtime] ==3600)     {$cookie_time=$time+3600;}
	elseif ( $_POST[yxtime] ==10800) {$cookie_time=$time+10800;}
	elseif ( $_POST[yxtime] ==86400)  {$cookie_time=$time+86400;}
	elseif ( $_POST[yxtime] ==2592000) {$cookie_time=$time+2592000;}
	else { $cookie_time=0; }

	setcookie ("boom_baby","$password","$cookie_time","$_SERVER[PHP_SELF]"); 

	echo"<meta http-equiv=refresh content=5;URL='?'><center><br><br>輸入密碼正確 | 登入成功</center>";
	exit;
}

elseif($_GET[login]=="3"){
/*------------------------------ 退出登入狀態 --------------------------*/
	setcookie ("boom_baby","00","-9999","$_SERVER[PHP_SELF]"); 
	error_info("已經退出登入，並清空Cookie");
}

elseif($_GET[login]=="1"){
/*-------------------------------- 登入界面 ----------------------------*/
	echo"<body bgcolor=000000><center><br><br><br><br><br>
<table width=400 border=0 bgcolor=666666 cellpadding=3 cellspacing=1>
 <tr bgcolor=666666><td align=center><font size=3 color=ffffff><b>登　入　管　理</b></font></td></tr>
 <tr bgcolor=eeeeee>
 <form action='?login=2' method=post>
  <td align=center height=80>管理員密碼：<input type='password' name='password' size=19 maxlength=20>
<br><font style='font-size:9pt'>Cookies設定：</font><select name='yxtime' size=1>
<option value='0'>不保存</option>
<option value='3600'> 1小時</option>
<option value='10800'>3小時</option>
<option value='86400'>1天</option>
<option value='2592000'>1個月</option>
</select><br><input type='submit' value='登入管理'></td>
 </form>
 </tr>
</table></center></body>";
	exit;
}

$time_start = getmicrotime();

if (($set[mode]=="1") and ($_COOKIE[boom_baby] != $set[password])) {
	echo"<center><br><br><br><font size=3 color=ff0000>抱歉，您沒有登入。無法使用本程式！</font><hr size=1>
	     <a href='?login=1'>>>輸入管理員密碼登入<<</a></center>";
	exit;
}


 chdir($dir);
 $open=opendir("./");


if($_GET[m]=="show"){
//-------------------------------- 查看內容 --------------------------------
	if($_GET[id] != ""){
		if(file_exists("$_GET[id]")){
			$fp=fopen($_GET[id],r);
			$data=fread($fp,"9999999");
			fclose($fp);

			$data=str_replace("</textarea>","[/textarea]",$data);
			$data=str_replace("</TEXTAREA>","[/textarea]",$data);
			$data=str_replace("<textarea","[textarea",$data);
			$data=str_replace("<TEXTAREA","[textarea",$data);

		}
	}

	skin_var(title,"查看編輯文件");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee align=center>
<form method=post action='?m=write&dir={$dir}'>
  <td height=100>
文件名：<input type=text name=id value='{$_GET[id]}' size=30 maxlength=30><br>
<textarea name='data' cols=100 rows=20>{$data}</textarea>
<input type=hidden name='dir' value='{$dir}'><input type='submit' value='確定修改保存'>
</td></tr></form>
<tr bgcolor=888888 align=center><td>
<table width=700 border=0  style='border: solid 1; border-color: 666666'><tr><td>
<center>可編輯txt/html/css/js/php/cgi/asp/jsp等所有文本類文件</center>
<font color=ff0000>注意：</font>　所編輯文件 < 9MB
<br>由於程式使用“<font color=0000ff>&lt;textarea&gt;&lt;/textarea&gt;</font>”標籤來顯示編輯文件內容，為了避免因衝突產生錯誤，
<br>如果所顯示編輯的文件中有“<font color=0000ff>&lt;textarea&gt;&lt;/textarea&gt;</font>”標籤，
<br>程式會自動將“<font color=0000ff>&lt;textarea</font>”轉換成“<font color=00ff00>[textarea</font>”、“<font color=0000ff>&lt;/textarea&gt;</font>”轉換成“<font color=00ff00>[/textarea]</font>”顯示出來。
<br>當文件保存時程式會自動再將“<font color=00ff00>[textarea</font>”還原回“<font color=0000ff>&lt;textarea</font>”、“<font color=00ff00>[/textarea]</font>”還原回“<font color=0000ff>&lt;/textarea&gt;</font>”。
<br>-----特此提醒使用者！！！
</td></tr></table>
</td></tr></table>";
}



elseif($_GET[m]=="write"){
//-------------------------------- 寫文件 --------------------------------
	$data=stripslashes($_POST[data]);
	$data=str_replace("[/textarea]","</textarea>",$data);
	$data=str_replace("[textarea","<textarea",$data);

	skin_var(title,"寫入文件");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee align=center>
  <td height=100>";
	if($data != ""){
		$fp=fopen($_POST[id],"w");
		flock($fp,LOCK_EX);
		$data=str_replace("\r","",$data);
		fputs($fp,$data);
		fclose($fp);
	
		echo"<meta http-equiv=refresh content=5;URL='?dir={$dir}'><p><b>文件：<font size=3 color=ff0000>{$_POST[id]}</font>保存完畢！</b>";
	}
	else{echo"<meta http-equiv=refresh content=5;URL='javascript:history.back(1);'><font size=3 color=ff0000>請輸入需要修改的文件名稱</font>";}
	echo"</td></tr></table>";
}




elseif($_GET[m]=="mkdir"){
//------------------------------ 創建新目錄 -------------------------------
	skin_var(title,"創建新目錄");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee align=center>
  <td height=100>";

	if($_GET[id] != ""){
		if(!file_exists($_GET[id])){mkdir($_GET[id],0755);echo"<meta http-equiv=refresh content=5;URL='?dir={$dir}'>目錄“<font size=3 color=ff0000>{$_GET[id]}</font>”創建成功<br><br>程式5秒鐘後自動返回查看";}
		else{echo"<meta http-equiv=refresh content=5;URL='javascript:history.back(1);'>目錄“<font size=3 color=ff0000>{$_GET[id]}</font>”已經存在";}
	}
	else{echo"<meta http-equiv=refresh content=5;URL='javascript:history.back(1);'><font size=3 color=ff0000>請輸入需要新創建的目錄名稱</font>";}
	echo"</td></tr></table>";
}




elseif($_GET[m]=="md5"){
//-------------------------- 輸入需MD5加密的字符 ---------------------------
	skin_var(title,"輸入需加密的字符");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
<form method='post' action='?m=showmd5'>
 <tr bgcolor=eeeeee align=center>
  <td height=100>
需要加密的字符：<input type=text name=word size=30 maxlength=30>
<input type='submit' value='確定'>
  </td></tr></form></table>";
}




elseif($_GET[m]=="showmd5"){
//------------------------------ 顯示MD5加密後 -----------------------------
	$word=md5($_POST[word]);
	skin_var(title,"顯示MD5加密後的字符串");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee align=center>
  <td height=100>
<font color=ff0000>經過MD5加密後生成的字符串：</font><input type=text name='word' value='$word' size=40 maxlength=50 readonly>
  </td></tr></table>";
}





elseif($_GET[m]=="code"){
//-------------------------------- 代碼生成 --------------------------------
	skin_var(title,"輸入代碼");
	echo"{$style_head}
<table width=750 border=0 bgcolor=eeeeee cellpadding=2 cellspacing=1>
<tr><td align=center height=150>
	<table width=500 border=0 bgcolor=bbbbbb cellpadding=3 cellspacing=1>
	<tr><td bgcolor=eeeeee>
這個功能的用處在於生成大量相近的連續代碼。
<br>舉個例子來說吧：
<br>如果要在網站資料中加上“http://boom.cpgl.net/”地址下從“001.gif”到“100.gif”的圖片，
我難道需要手工一個個插入或是編寫代碼？
<br>曾經手工編寫代碼的我做了這個功能。讓程式自己產生，而不是我們來編寫修改。
<br><br>我們所需要做的只是設定好需要的字符串/要變化部分的最小值/最大值/。
<br>等程式生成後我們再拷貝代碼就OK啦！
	</td></tr></table>
<hr size=1 color=cccccc>
 <form method='post' action='?m=showcode'>
前部字符：<input type=text name='string_q' size=50 maxlength=80 value='http://boom.cpgl.net/'>
<br>
初始數：<input type=text name='minimum' size=3 maxlength=3 value='1'>
最大數：<input type=text name='max' size=3 maxlength=3 value='100'>
<br>
後部字符：<input type=text name='string_h' size=50 maxlength=80 value='.gif'>
<br>
<input type='submit' value='開始生成'>
</form>
</td></tr></table>";
}




elseif($_GET[m]=="showcode"){
//-------------------------------- 顯示所生成代碼 --------------------------------
	$all=$_POST[max]-$_POST[minimum]+1;
	skin_var(title,"顯示代碼");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee align=center>
  <td height=100><b>{$_POST[minimum]}</b> 到 <b>{$_POST[max]}</b> 共 <b>{$all}</b> 項<br>
<textarea name='data' cols=80 rows=19>";

	$len=strlen($_POST[minimum]);
	for ($i=$_POST[minimum]; $i<=$_POST[max]; $i++) {
		$num=$i;
		$x=$len-strlen($i);
		for($x; $x>0; $x--) {$num="0".$num;}
		echo(stripslashes("{$_POST[string_q]}{$num}{$_POST[string_h]}\n"));
	} 
  	echo"</textarea></td></tr></table>";
}




elseif($_GET[m]=="unixdate"){
//------------------------------- unix時間換算 --------------------------------
	skin_var(title,"UNIX時間換算");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee align=center>
  <td height=100>
<br>將輸入的UNIX時間戳記轉換為通用公元年月日時分秒
<br>比如：1067762599 計算為 2003年11月02日 16時11分19秒
<hr size=1>
<form method='post' action='?m=showdate'>輸入UNIX時間戳記：<input type=text name=data size=20 maxlength=20><input type='submit' value='開始計算'></form>
<hr size=1>注：UNIX時間是從 1970年1月1日8時1分0秒 為起始的以秒為單位的10進制數值。
</td></tr>
</table>";
}



elseif($_GET[m]=="showdate"){
//------------------------------ unix時間轉換通常時間 -----------------------------
	$date=date("Y年m月d日 H時m分s秒",$_POST[data]);

	skin_var(title,"UNIX時間轉換通常時間");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee align=center>
  <td height=100>時間：<font size=3>$date</font>
  </td></tr></table>";
}



elseif($_POST[m]=="屬性"){
//-------------------------------- 輸入屬性 --------------------------------
	skin_var(title,"輸入屬性值");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
    <tr bgcolor=eeeeee><td align=center height=100>";

	if(!$_POST[id][0]){error_info("沒有選擇要修改屬性的項目");}

	while ( list($key, $val) = each($_POST[id]) ) {
		if($key=="0"){$items=$_POST[id][$key];}
		else{$items=$items."|".$_POST[id][$key];}
	}

	echo"
    <form action='?m=chmod&dir={$dir}' method=post><br>屬性值：
	<input type='text' name='val' value='0755' size=4 maxlength=4>
	<input type='hidden' name='items' value='{$items}'>
	<input type=submit value='確定修改'>
    </td>
    </tr></form>
</table>";
}



elseif($_GET[m]=="chmod"){
//-------------------------------- 修改屬性 --------------------------------

#	$val=(integer)$_POST[val];
#	echo"{$_POST[val]}|".gettype($_POST[val])."<br>{$val}|".gettype($val)."<br>";

	skin_var(title,"修改屬性");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee align=center>
  <td height=100>";
	if(!$_POST[items]){error_info("!沒有選擇需要修改屬性的目標!");}

	$id = explode("|",$_POST[items]);
	$val=base_convert($_POST[val],8,10);
#	$val=base_convert($val,10,8);
	for($i=0; $i<count($id); $i++){
		if(chmod($id[$i],$val)){echo"修改『<font color=ff0000>{$id[$i]}</font>』屬性為[<font color=ff0000>{$_POST[val]}</font>]成功<br>";}else{;echo"“<font color=ff0000>{$id[$i]}</font>”修改屬性失敗<br>";}
	}
	echo"</td></tr></table>";
}



elseif($_POST[m]=="改名"){
//-------------------------------- 改名確認 --------------------------------
	if(!$_POST[id][0]){error_info("!沒有選擇需要改名的目標!");}

	skin_var(title,"改名確認");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee align=center>
  <td height=100>
    <form action='?m=rename&dir={$dir}' method=post><br>
文件/目錄：<input type='text' name='id' value='{$_POST[id][0]}' size=20 readonly><br>
　 改名為：<input type='text' name='newname' size=20 maxlength=20><br>
	<input type=submit value='確定改名'>
    </td>
    </tr></form></td></tr></table>";
}



elseif($_GET[m]=="rename"){
//-------------------------------- 修改名稱 --------------------------------
	if((!$_POST[id]) or (!$_POST[newname])){error_info("!請選擇需要改名的目標，並輸入新名稱!");}

	skin_var(title,"修改名稱");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee align=center>
  <td height=100>";

	if(rename ($_POST[id],$_POST[newname])){echo"<font size=3>改名成功</font>";}
	else{echo"<font size=3 color=ff0000>改名作業失敗</font>";}
	echo"</td></tr></table>";
}



elseif($_POST[m]=="刪除"){
//-------------------------------- 刪除確認 --------------------------------
	skin_var(title,"刪除作業確認");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee align=center>
  <td height=100>";

	if(!$_POST[id][0]){error_info("沒有選擇要刪除的項目<br>");}
	$id_all=count($_POST[id]);

	echo"<table width=300 border=0 bgcolor=cccccc cellpadding=3 cellspacing=1>";

	while ( list($key, $val) = each($_POST[id]) ) {
		if($key=="0"){$items=$_POST[id][$key];}
		else{$items=$items."|".$_POST[id][$key];}

		if(is_dir($_POST[id][$key])){$info1="目錄";}else{$info1="文件";}
		if((is_writeable($_POST[id][$key]))==1){$info2="可刪";}else{$info2="<font color=ff0000>不可刪</font>";}
		echo"<tr bgcolor=eeeeee><td>{$_POST[id][$key]}</td><td align=center>$info1</td><td align=center>$info2</td></tr>";
	}

	echo"</td></tr></table>
<hr size=1>
<font color=ff0000>再次提醒您看清楚路徑！誤作業將帶來不必要的損失！</font><br><font size=3><b>確定刪除以上全部 <font color=ff0000>{$id_all}</font> 項？</b></font>
    <form action='?m=del&dir={$dir}' method=post>
	<input type='hidden' name='items' value='$items'>
	<input type=submit value='確定刪除'>
</form>
<hr size=1>若刪除目錄，程式會自動刪除目錄下一級的文件和空目錄（不包括更深層的目錄和文件）
</td></tr></table>";
}



elseif($_GET[m]=="del"){
//-------------------------------- 開始刪除 --------------------------------
	skin_var(title,"刪除作業");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee align=center>
  <td height=100>
    <table border=0><tr><td>";

	if(!$_POST[items]){error_info("沒有選擇要刪除的項目");}
	$id = explode("|",$_POST[items]); 
	$i_all=count($id);
	echo"刪除項目總數:$i_all<hr>";

	for($i=0; $i < $i_all; $i++){
		if(is_dir($id[$i])){

			chdir($id[$i]);
			$open=opendir("./");
			for($ii=0; $filename=readdir($open); $ii++){
				if(is_dir($filename)){
				if(($filename!=".") and ($filename!="..")){ rmdir($filename);}
				}
				else{ unlink($filename);}
			}
			chdir("../");
			$open=opendir("./");

			if(@rmdir($id[$i])){echo"刪除目錄：<b>{$id[$i]}</b><br>";}
			else{echo"<font color=ff0000>刪除目錄<b>{$id[$i]}失敗</b></font><br>";}
			
		}
		else{
			if(@unlink($id[$i])){echo"刪除文件：<b>{$id[$i]}</b><br>";}
			else{echo"<font color=ff0000>刪除文件<b>{$id[$i]}</b>失敗！</font><br>";}
		}
	}

 echo"
    </td></tr></table></td></tr></table>";
}




elseif($_GET[m]=="help"){
//-------------------------------- 程式說明 --------------------------------

 $phpver=phpversion();
 $os=PHP_OS;
 $df=round(diskfreespace("/")/1048576);
 if (get_cfg_var("safe_mode")){$safe_mode="開啟";}else{$safe_mode="關閉";}
 $upfile_max = get_cfg_var("upload_max_filesize");
 $scriptouttime = get_cfg_var("max_execution_time");
 if (get_cfg_var("register_globals")){$register_globals ="On";}else{$register_globals ="Off";}
 $post_max_size = get_cfg_var("post_max_size");
 $memory_limit= get_cfg_var("memory_limit");

	skin_var(title,"信息說明");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee>
  <td height=100><font size=3><center>
我的IP地址：{$_SERVER[REMOTE_ADDR]}</center></font><br>
   <table border=0 bgcolor=aaaaaa cellpadding=1 cellspacing=1 align=center>
<tr bgcolor=cccccc><td colspan=2 align=center>主信息：</td></tr>
<tr bgcolor=eeeeee><td colspan=2>{$_SERVER[SERVER_SIGNATURE]}</td></tr>
<tr bgcolor=eeeeee><td>作業系統</td><td>{$os}</td></tr>
<tr bgcolor=eeeeee><td>PHP 版本</td><td>{$phpver}</td></tr>
<tr bgcolor=eeeeee><td>服務器程式</td><td>{$_SERVER[SERVER_SOFTWARE]}</td></tr>
<tr bgcolor=eeeeee><td>磁盤剩餘空間</td><td>{$df} MB</td></tr>
<tr bgcolor=eeeeee><td>WWW服務默認路徑</td><td>{$_SERVER[DOCUMENT_ROOT]}</td></tr>
<tr bgcolor=eeeeee><td>當前程式所在路徑</td><td>{$_SERVER[SCRIPT_FILENAME]}</td></tr>
<tr bgcolor=eeeeee><td>當前程式所在路徑</td><td>{$_SERVER[PATH_TRANSLATED]}</td></tr>
<tr bgcolor=cccccc><td colspan=2 align=center>PHP.ini配置信息：</td></tr>
<tr bgcolor=eeeeee><td>安全模式</td><td>{$safe_mode}</td></tr>
<tr bgcolor=eeeeee><td>自動全局變量</td><td>{$register_globals}</td></tr>
<tr bgcolor=eeeeee><td>最大上傳文件</td><td>{$upfile_max}</td></tr>
<tr bgcolor=eeeeee><td>最大POST上限</td><td>{$post_max_size}</td></tr>
<tr bgcolor=eeeeee><td>最大使用內存</td><td>{$memory_limit}</td></tr>
<tr bgcolor=eeeeee><td>腳本超時時間</td><td>{$scriptouttime} sec</td></tr>
<tr bgcolor=cccccc><td colspan=2 align=center>[<a href='?m=phpinfo'><font color=ff0000>Phpinfo 詳細信息！</font></a>]</td></tr>
   </table>
<br>
<br><br><b>功能介紹：</b>
<li>遍歷服務器上有足夠權限的目錄，並列出目錄下的文件和子目錄信息。
<li>測試文件是否可以讀寫。1為可，0為否。
<li>在可讀的情況下，能查看文件的內容。包括該文件裡的敏感信息。
<li>在可寫的情況下，能夠【上傳文件】、【修改屬性】、【文件改名】、【編輯文件】、【新建文件】、【新建目錄】。
<li>【批量刪除文件和空目錄】、【批量修改文件和目錄屬性】。
<li>[MD5加密字符]、[批量代碼生成]、[UNIX時間戳換算]。
<li>另外還可返回系統環境信息。
<li>管理員登入功能。
<li>以後會增加更多所能想到的實用功能。
<br><br><b>注意事項：</b>
<li><font color=ff0000>本程式可無用設定直接使用。但由於功能強大而存在危險性，所以請改名並放在只有你自己知道的地方。最好配置密碼使用！</font>
<li><font color=ff0000>使用本程式也許會獲得某些服務器中的敏感信息，但請勿做非法用途！否則後果自負！ </font>
<li>由於服務器配置各不相同，無法保證您使用時本程式的全部功能都有效。程式某些功能無法正常執行，返回錯誤信息也不奇怪。 
<li>若要刪除文件和目錄，請先把要刪除目標所在的目錄屬性改為777，以確保成功。

<hr size=1>
<p align=right>程式作者：刀鋒戰士　　2004-06-01
</td></tr></table>";
}



elseif($_GET[m]=="phpinfo"){
	phpinfo();
	exit;
}


elseif($_GET[m]=="upfile"){
//-------------------------------- 文件上傳 --------------------------------
	if ($_FILES[upfile][name]==""){error_info("!請選擇要上傳的文件!<br>不然我怎麼知道你要上傳哪一個？昏！");}
	if (file_exists($_FILES[upfile][name])) {error_info("該目錄中已有同名文件，請改名！");}

	move_uploaded_file($_FILES[upfile][tmp_name],$_FILES[upfile][name]);

	skin_var(title,"文件上傳");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee>
  <td height=200 align=center>
<b><font size=3>文件“<font color=ff0000>{$_FILES[upfile][name]}</font>”上傳完畢！</font></b>
<br>
文件大小：{$_FILES[upfile][size]} Byte
<hr size=1 width=400>
備註：某些情況下可能需要上傳2次才能成功。
</td></tr></table>
<meta http-equiv=refresh content=7;URL='?dir={$dir}'>";
}



else{
//-------------------------------- 目錄列表 --------------------------------
	if($_GET[showtype]==""){ $showname="所有文件與目錄";}
	elseif($_GET[showtype]=="directory"){ $showname="所有目錄";}
	else{ $showname="<b><font face='Tahoma'>*.{$_GET[showtype]}</font></b> 文件";}

	skin_var(title,"目錄列表");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=888888 align=center><td></td>
  <form method='get'>
  <td><table width=100% border=0 cellpadding=0 cellspacing=0><tr><td><font color=ffffff>　{$showname}</font></td><td align=right>
	<select name='showtype' size=1 onchange=\"window.location=('?dir={$dir}&showtype='+this.options[this.selectedIndex].value+'');\">
	 <option style='BACKGROUND-COLOR: aaaaaa; color=ffffff'>顯示條件</option>
	 <option value=''>全部顯示</option>
	 <option value='directory'>< 目錄 ></option>
	 <option value='html'>*.html</option>
	 <option value='htm'>*.htm</option>
	 <option value='txt'>*.txt</option>
	 <option value='cgi'>*.cgi</option>
	 <option value='php'>*.php</option>
	 <option value='asp'>*.asp</option>
	 <option value='jsp'>*.jsp</option>
	 <option value='dat'>*.dat</option>
	 <option value='swf'>*.swf</option>
	 <option value='gif'>*.gif</option>
	 <option value='jpg'>*.jpg</option>
	 <option value='png'>*.png</option>
	 <option value='zip'>*.zip</option>
	 <option value='rar'>*.rar</option>
	</select></td></tr></table>
  </td></form>
  <td><font color=ffffff>文件大小</font></td>
  <td><font color=ffffff>創建時間</font></td>
  <td><font color=ffffff>修改時間</font></td>
  <td><font color=ffffff>屬 性</font></td>
  <td><font color=ffffff>可讀</font></td>
  <td><font color=ffffff>可寫</font></td>
  <td><font color=ffffff>所有者</font></td>
 </tr><form method='post'>\n";

 for($i=0; $filename=readdir($open); $i++){
	if(is_dir($filename)){
		if(($_GET[showtype]!="") and ($_GET[showtype]!="directory")){continue;}

		if(($filename==".") or ($filename=="..")){echo"<tr bgcolor=dddddd align=center><td></td><td align=left><font color=ff9900>[<a href='?dir={$dir}$filename/'>$filename</a>]</font></td>";}
		else{echo"<tr bgcolor=dddddd align=center><td><input type='checkbox' name='id[]' value='$filename'></td><td align=left><font color=ff9900>[<a href='?dir={$dir}$filename/'>$filename</a>]</font></td>";}
		$fileinfo[2]="<td>< 目錄 >";
		$dir_i++;
	}
	else{
		if($_GET[showtype]=="directory"){continue;}
		elseif($_GET[showtype]!=""){
			if(strtolower($_GET[showtype]) != strtolower(substr(strrchr($filename,"."),1))){continue;}
		}

		echo"<tr bgcolor=eeeeee align=center><td><input type='checkbox' name='id[]' value='$filename'></td><td align=left><table width=100% border=0 cellpadding=0 cellspacing=0><tr><td><a href='{$dir}".urlencode($filename)."'>$filename</a></td><td align=right><a href='?m=show&id={$filename}&dir={$dir}'>查看</a></td></tr></table></td>";
		$fileinfo[2]="<td align=right>".filesize("{$filename}");
		$file_i++;
	}

	echo"{$fileinfo[2]}</td><td>".date("y-m-d H:i",filectime("$filename"))."</td><td>".date("y-m-d H:i",filemtime("$filename"))."</td><td>".substr(decoct(fileperms("$filename")),-3)."</td><td>".is_readable($filename)."</td><td>".is_writeable($filename)."</td><td>".fileowner("{$filename}")."</td></tr>\n";
 }
 echo"<tr bgcolor=888888><td colspan=3>
<input type=hidden name='dir' value='{$dir}'>
<input type='submit' name='m' value='刪除'>
<input type='submit' name='m' value='屬性'>
<input type='submit' name='m' value='改名'>
</td>
</form>
<td colspan=6 align=center>總共：{$i}個文件和目錄　　目錄數：{$dir_i}　　文件數：{$file_i}</td></tr>
</table>";
}


/* ================================ 程式尾部樣式 ========================= */
$time_end = getmicrotime();
$alltime=$time_end-$time_start;
echo"
<table width=750 border=0 bgcolor=666666 cellpadding=3 cellspacing=0>
 <tr bgcolor=666666>
<form action='?m=upfile&dir={$dir}' method='post' enctype='multipart/form-data'>
  <td><input type='file' name='upfile' size=18><input type='submit' value='上傳文件'></td>
</form>
  <td> <a href='?dir=c:/'>[C:]</a> <a href='?dir=d:/'>[D:]</a> <a href='?dir=e:/'>[E:]</a> |<a href='?m=help'>說明</a>|</td>
<form method='get'>
  <td align=right><select name='m' size=1>
			<option value='mkdir'>新建一個目錄</option>
			<option value='show'>新建一個文件</option>
</select><input type=hidden name='dir' value='{$dir}'><input type=text name=id size=15 maxlength=15><input type=submit value='確定創建'></td>
 </tr>
</form>
</table>
<table width=750 border=0 cellpadding=3 cellspacing=1>
 <tr align=center>
  <td align=left>
<font color=FFFFFF>程式執行時間：{$alltime} s</font>
  </td><td align=right>
<font color=FFFFFF face='Tahoma'>...:::::MADE IN BOOM</font><br><font color=FFFFFF>繁體製作：</font><a target=_blank href=http://twcode.idv.tw/><font color=FFFFFF>童言無忌資訊網</font></a></td>
 </tr>
</table>
</DIV>
</BODY>
</HTML>";

?>
