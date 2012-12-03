<?
include('./config.php');
if ($_SESSION['login']) {
if ($_POST['bday'] OR $_POST['bmonth'] OR $_POST['byear'] OR $_POST['sex'] OR $_POST['loc'] OR $_POST['site'] OR $_POST['blog'] OR $_POST['about'] OR $_POST['int']) {
if ($_POST['sex']<=3 AND $_POST['sex']>0) {
if (checkdate($_POST['bday'],$_POST['bmonth'],$_POST['byear']) AND $_POST['byear']<=date('Y')) {
mysql_query("UPDATE users SET bday='".$_POST['bday']."',bmonth='".$_POST['bmonth']."',byear='".$_POST['byear']."',sex='".$_POST['sex']."',loc='".safe($_POST['loc'])."',site='".safe($_POST['site'])."',blog='".safe($_POST['blog'])."',about='".safe($_POST['about'])."',`ints`='".safe($_POST['ints'])."' WHERE `login`='".$_SESSION['login']."'");
$msgsucc = 'Данные сохранены';
} else $msg = 'Неправильная дата рождения';
} else $msg = 'Неправильно выбран пол';
}
$url = "upload/avatars";
$user = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE login='".$_SESSION['login']."'"));
if($_FILES['FILE']['name']) /*Проверяем, существует ли имя.*/ {if($_FILES['FILE']['type'] == 'image/gif' OR $_FILES['FILE']['type'] == 'image/png' OR $_FILES['FILE']['type'] == 'image/jpeg') {$f = $_FILES['FILE']['tmp_name'];
if ($_FILES['FILE']['type'] == 'image/gif') {$src = imagecreatefromgif($f);$format = 'gif';$format1 = 'png';$format2 = 'jpeg';}
if ($_FILES['FILE']['type'] == 'image/png') {$src = imagecreatefrompng($f);$format = 'png';$format1 = 'gif';$format2 = 'jpeg';}
if ($_FILES['FILE']['type'] == 'image/jpeg') {$src = imagecreatefromjpeg($f);$format = 'jpeg';$format1 = 'png';$format2 = 'gif';}
$w_src = imagesx($src);
$h_src = imagesy($src);
if($w_src==100 && $h_src==100) {
if($_FILES['FILE']['size'] != 0 AND $_FILES['FILE']['size']<=1024000) /*Проверяем размер файла*/ {
if(is_uploaded_file($_FILES['FILE']['tmp_name'])) /*Проверяем загрузился ли файл на сервер*/ {if (file_exists($url."/".$user['id'].".".$format1)) {unlink($url."/".$user['id'].".".$format1);} if (file_exists($url."/".$user['id'].".".$format2)) {unlink($url."/".$user['id'].".".$format2);}
if(move_uploaded_file($_FILES['FILE']['tmp_name'], $url."/".$user['id'].".".$format)) /*Перемещаем загруженный файл в необходимую папку $url*/ {}
else {$msg = 'Произошла ошибка при перемещении файла в папку'.$url;}}
else {$msg = 'Прозошла ошибка при загрузке файла на сервер';}}
else {$msg = 'Размер файла не должен превышать 100Кб';}}
else {$msg = 'Размер не соответствует указанному';}}
else {$msg = 'Файл не является картинкой формата GIF,PNG,JPEG';}}
include('./head.php');
?>
<div id="profile">
<?if ($msg) {?><div class="alert alert-error"><?=$msg?></div><?}?>
<?if ($msgsucc) {?><div class="alert alert-success"><?=$msgsucc?></div><?}?>
<table><tr>
<form action="" method="POST" enctype='multipart/form-data'>
<td class="lp" valign="top"><a href="#" class="thumbnail"><img src="<?=avatar($user['id'])?>"></a>

    <div class="btn" style="height:20px;">
    Обзор...
    <input type="file" name="FILE" size="1" onchange="submit();">
    </div>
</td>
<td class="rp" valign="top">
<div class="well"><?=$user['login']?></div>
<table>
<tr><td><b>Пол:</b></td><td><select name="sex">
<option value="1"<?if($user['sex']==1) echo ' selected';?>>Мужской</option>
<option value="2"<?if($user['sex']==2) echo ' selected';?>>Женский</option>
<option value="3"<?if($user['sex']==3) echo ' selected';?>>Другое</option></select></td></tr>
<tr><td><b>Дата рождения:</b></td><td>
<select name="bday" class="span1">
<?for ($i=1;$i<=31;$i++) {?>
<option value="<?=$i?>"<?if ($user['bday']==$i) {echo ' selected';}?>><?=$i?></option><?}?>
</select>
<select name="bmonth" class="span1">
<?for ($i=1;$i<=12;$i++) {?>
<option value="<?=$i?>"<?if ($user['bmonth']==$i) {echo ' selected';}?>><?=$i?></option><?}?>
</select>
<select name="byear" class="span2">
<?for ($i=2012;$i>=1900;$i++) {?>
<option value="<?=$i?>"<?if ($user['byear']==$i) {echo ' selected';}?>><?=$i?></option><?
$i=$i-2;}?>
</select></td></tr>
<tr><td><b>Местонахождение: </b></td><td> <input type="text" name="loc" value="<?=$user['loc']?>"></td></tr>
<tr><td><b>Личный сайт:</b></td> <td><input type="text" name="site" value="<?=$user['site']?>"></td></tr>
<tr><td><b>Блог:</b> </td> <td><input type="text" name="blog" value="<?=$user['blog']?>"></td></tr>
<tr><td><b>О себе:</b></td><td><textarea name="about"><?=$user['about']?></textarea></td></tr>
<tr><td><b>Интересы:</b></td><td><textarea name="ints"><?=$user['ints']?></textarea><br><span class="hint">через запятую</span></td></tr>
</table>
<input type="submit" value="Сохранить" class="btn"></form>
</td>
</tr></table><br>
</div>
<?} else {echo '<div class="msg">Вы не вошли на сайт</div>';}
include('./foot.php');?>