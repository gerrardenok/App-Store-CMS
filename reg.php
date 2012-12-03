<?
include('./config.php');
if ($_POST['login']) {
if ($_POST['pass'] AND $_POST['mail'] AND $_POST['sex'] AND $_POST['bday'] AND $_POST['bmonth'] AND $_POST['byear']) {
if ($_POST['login'] == safe($_POST['login'])) {
if (strlen($_POST['login'])>2 AND strlen($_POST['login'])<13) { 
if ($_POST['pass'] == safe($_POST['pass'])) {
if ($_POST['sex']<=3) {
if (checkdate($_POST['bday'],$_POST['bmonth'],$_POST['byear']) AND $_POST['byear']<=date('Y')) { 
if ($_POST['captcha'] == $_SESSION['captcha']) {
$user = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE login='".safe($_POST['login'])."';"));
if (!$user['login']) {
mysql_query("INSERT INTO users (login,pass,mail,sex,bday,bmonth,byear,regdate,lasttime) VALUES ('".safe($_POST['login'])."','".md5(safe($_POST['pass']))."','".safe($_POST['mail'])."','".$_POST['sex']."','".$_POST['bday']."','".$_POST['bmonth']."','".$_POST['byear']."','".time()."','".time()."');");
$msgsucc = 'Вы успешно зарегистрированы';
$user = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE login='".safe($_POST['login'])."'"));
$_SESSION['login'] = $user['login'];
$_SESSION['id'] = $user['id'];}
else $msg ='Данный логин уже используется';
} else $msg ='Неправильнно введен проверочный код';
} else $msg ='Неправильная дата';
} else $msg ='Неправильно выбран пол';
} else $msg ='Пароль содержит запрещенные символы';
} else $msg ='Логин должен состоять из 3-12 символов';
} else $msg ='Логин содержит запрещенные символы';
} else $msg ='Заполнены не все поля';
} elseif ($_POST['pass'] OR $_POST['mail'] OR $_POST['sex'] OR $_POST['bday']) $msg = 'Заполнены не все поля';
include('./head.php');
?>
<?if ($msgsucc) {?><div class="alert alert-success"><?=$msgsucc?></div><?}?>
<?if ($msg) {?><div class="alert alert-error"><?=$msg?></div><?}?>
<h3>Регистрация</h3>
<div id="reg">
<form action="" method="POST"><table>
<tr><td>Логин:</td><td><input name="login" type="text"></td></tr>
<tr><td>Пароль:</td><td><input name="pass" type="password"></td></tr>
<tr><td>Email:</td><td><input name="mail" type="text"></td></tr>
<tr><td>Пол:</td><td><select name="sex">
<option value="0">Не выбран</option>
<option value="1">Мужской</option>
<option value="2">Женский</option>
<option value="3">Другое</option></select></td></tr>
<tr><td>Дата рождения:</td><td>
<select name="bday" class="span1">
<?for ($i=1;$i<=31;$i++) {?>
<option value="<?=$i?>"><?=$i?></option><?}?>
</select>
<select name="bmonth" class="span1">
<?for ($i=1;$i<=12;$i++) {?>
<option value="<?=$i?>"><?=$i?></option><?}?>
</select>
<select name="byear" class="span2">
<?for ($i=2012;$i>=1900;$i++) {?>
<option value="<?=$i?>"><?=$i?></option><?
$i=$i-2;}?>
</select>
</td></tr>
<tr><td valign=top>Проверочный код:</td><td><img src="/captcha.php"><br><input name="captcha" type="text"></td></tr>
<tr><td></td><td><input type="submit" class="btn" value="Отправить"></td></tr>
</table>
</form></div>
<?include('./foot.php');?>