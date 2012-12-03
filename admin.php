<?
include('./config.php');
include('./head.php');
if ($_SESSION['rank']>=1) {
if ($_GET['md'] == 'nvers') {
// ------------------------------ Обновление программы ------------------------------
if ($_GET['id']) {
$pr = mysql_fetch_array(mysql_query("SELECT * FROM catalog WHERE id='".safe($_GET['id'])."'"));
if ($pr['id']) {
if ($_POST) {
if ($_POST['vers'] AND $_POST['trel'] AND $_POST['dlof']) {
if ($_SESSION['rank'] == 1) {$st = 0;} else {$st = 1;}
mysql_query("INSERT INTO vers (pid,vers,dl,tadd,trel,status) VALUES ('".$pr['id']."','".safe($_POST['vers'])."','".safe($_POST['dlof']).";".safe($_POST['dlmir'])."','".time()."','".safe($_POST['trel'])."','".$st."')");
if ($st = 1) {$msgsucc = 'Обновление успешно добавлено';} else {$msgsucc = 'Обновление будет добавлено после подтверждения модераторами';}
} else {$msg = 'Не заполнены обязательные поля';}
}?>
<?if ($msgsucc) {?><div class="alert alert-success"><?=$msgsucc?></div><?}?>
<?if ($msg) {?><div class="alert alert-error"><?=$msg?></div><?}?>
<form action="" method="POST"><table><tr><td>Новая версия</td><td><h4><a href="/cat.php?id=<?=$pr['id']?>"><?=$pr['title']?></a></h4></td></tr>
<tr><td>Версия*:</td><td><input type="text" name="vers"></td></tr>
<tr><td>Дата релиза*:</td><td><input type="text" name="trel"> DD.MM.YYYY</td></tr>
<tr><td>Ссылка с оф. сайта*:</td><td><input type="text" name="dlof"></td></tr>
<tr><td>Зеркала:</td><td><input type="text" name="dlmir"> через точку с запятой</td></tr>
<tr><td></td><td><input type="submit" value="Отправить" class="btn"></td></tr></table></form>
<?} else echo '<div class="alert alert-error">Программа не найдена</div>';} else {
?>
<h4>Выберите программу</h4>
<table width=100% class="table table-condensed table-striped">
<tr><td>ID</td><td>Название</td><td>Последняя версия</td></tr>
<?$prs = mysql_query("SELECT * FROM catalog LIMIT 0,20");
$i = 0;
while ($pr = mysql_fetch_assoc($prs)) {
$prg[$pr['id']] .= '<tr><td>'.$pr['id'].'</td><td><a href="?md=nvers&id='.$pr['id'].'">'.$pr['title'].'</a></td>';
$ids .= $pr['id'].';';
if (!$mids) {$mids = "WHERE pid='".$pr['id']."'";} else {$mids .= " OR pid='".$pr['id']."'";}
}
$vers = mysql_query("SELECT * FROM vers ".$mids);
while ($vs = mysql_fetch_assoc($vers)) {
if (!$t[$vs['pid']]) {$prg[$vs['pid']] .= '<td>'.$vs['vers'].'</td></tr>'; $t[$vs['pid']] = 1;}
}
$ids = explode(';',$ids);
while ($ids[$i]) {
echo $prg[$ids[$i]];
$i++;
}?>
</table>
<?}}
elseif ($_GET['md'] == 'nnews') { 
// ------------------------------ Добавление новости ------------------------------
$cats = mysql_query("SELECT * FROM cats WHERE cat='news'");
if ($_POST['title'] or $_POST['cat'] or $_POST['text'] or $_FILES['FILE']['name']) {
if ($_POST['title'] AND ($_POST['cat']>0 AND $_POST['cat']<mysql_num_rows($cats)) AND $_POST['text'] AND $_FILES['FILE']['name']) {
$url = "upload/news";
if($_FILES['FILE']['type'] == 'image/gif' OR $_FILES['FILE']['type'] == 'image/png' OR $_FILES['FILE']['type'] == 'image/jpeg') {$f = $_FILES['FILE']['tmp_name'];
if ($_FILES['FILE']['type'] == 'image/gif') {$src = imagecreatefromgif($f);$format = 'gif';}
if ($_FILES['FILE']['type'] == 'image/png') {$src = imagecreatefrompng($f);$format = 'png';}
if ($_FILES['FILE']['type'] == 'image/jpeg') {$src = imagecreatefromjpeg($f);$format = 'jpeg';}
$w_src = imagesx($src);
$h_src = imagesy($src);
$imname = md5(mt_rand());
if($_FILES['FILE']['size'] != 0) {
if(is_uploaded_file($_FILES['FILE']['tmp_name'])) {
if(move_uploaded_file($_FILES['FILE']['tmp_name'], $url."/".$imname.".".$format)) {
if ($_SESSION['rank'] == 1) {$st = 0;} else {$st = 1;}
mysql_query("INSERT INTO news (title,text,scr,date,cat,status,author) VALUES ('".safe($_POST['title'])."','".safe($_POST['text'])."','".$imname.".".$format."','".time()."','".safe($_POST['cat'])."','".$st."','".$_SESSION['id']."')");
if ($_SESSION['rank'] == 1) {$msgsucc = 'Новость успешно отправлена на модерацию';} else {$msgsucc = 'Новость успешно добавлена';}
}
else {$msg = 'Произошла ошибка при перемещении файла в папку'.$url;}}
else {$msg = 'Прозошла ошибка при загрузке файла на сервер';}}
else {$msg = 'Ошибка при загрузке изображения';}}
else {$msg = 'Файл не является картинкой формата GIF,PNG,JPEG';}
} else $msg = 'Одно из полей не заполнено';
}
?>
<?if ($msg) {?><div class="alert alert-error"><?=$msg?></div><?}?>
<?if ($msgsucc) {?><div class="alert alert-success"><?=$msgsucc?></div><?}?>
<form action="" method="POST" enctype='multipart/form-data'>
<h4>Добавление новости</h4>
<input type="text" name="title" value="<?=$_POST['title']?>"> Заголовок <br>
<select name="cat">
<?while ($cat=mysql_fetch_assoc($cats)) {?><option value="<?=$cat['id']?>"<?if ($_POST['cat'] == $cat['id']) echo ' selected';?>><?=$cat['title']?></option><?}?>
</select> Категория <br>
<input type="file" name="FILE" value="<?=$_FILE['FILE']?>"> Изображение<br>
<h5>Содержание</h5><textarea id="editor" name="text"><?=$_POST['text']?></textarea><br>
<input type="submit" value="Отправить" class="btn"> <input type="button" class="btn" value="Просмотр"> 
</form>
<?} else 
if ($_GET['md'] == 'nedit') { 
// ------------------------------ Редактирование новости ------------------------------
if ($_GET['id']) {
if ($_SESSION['rank'] == 1) {$st = 0;} else {$st = 1;}
$news = mysql_fetch_array(mysql_query("SELECT * FROM news WHERE id='".safe($_GET['id'])."'"));
if ($news['id']) {
if ($_SESSION['id'] == $news['author'] OR $_SESSION['rank']>1)  {
$cats = mysql_query("SELECT * FROM cats WHERE cat='news'");
if ($_POST['title'] or $_POST['cat'] or $_POST['text'] or $_FILES['FILE']['name']) {
if ($_POST['title'] AND $_POST['cat']>0 AND $_POST['text']) {
if ($_FILES['FILE']['name']) {
$url = "upload/news";
if($_FILES['FILE']['type'] == 'image/gif' OR $_FILES['FILE']['type'] == 'image/png' OR $_FILES['FILE']['type'] == 'image/jpeg') {$f = $_FILES['FILE']['tmp_name'];
if ($_FILES['FILE']['type'] == 'image/gif') {$src = imagecreatefromgif($f);$format = 'gif';}
if ($_FILES['FILE']['type'] == 'image/png') {$src = imagecreatefrompng($f);$format = 'png';}
if ($_FILES['FILE']['type'] == 'image/jpeg') {$src = imagecreatefromjpeg($f);$format = 'jpeg';}
$w_src = imagesx($src);
$h_src = imagesy($src);
$imname = md5(mt_rand());
if($_FILES['FILE']['size'] != 0) {
if(is_uploaded_file($_FILES['FILE']['tmp_name'])) {
if(move_uploaded_file($_FILES['FILE']['tmp_name'], $url."/".$imname.".".$format)) {
mysql_query("UPDATE news SET title='".safe($_POST['title'])."',text='".safe($_POST['text'])."',scr='".$imname.".".$format."',cat='".safe($_POST['cat'])."',status='".$st."' WHERE id='".$news['id']."'");
$msgsucc = 'Новость успешно отредактирована';
}
else {$msg = 'Произошла ошибка при перемещении файла в папку'.$url;}}
else {$msg = 'Прозошла ошибка при загрузке файла на сервер';}}
else {$msg = 'Ошибка при загрузке изображения';}}
else {$msg = 'Файл не является картинкой формата GIF,PNG,JPEG';}} else {
mysql_query("UPDATE news SET title='".safe($_POST['title'])."',text='".safe($_POST['text'])."',cat='".safe($_POST['cat'])."',status='".$st."' WHERE id='".$news['id']."'");
$msgsucc = 'Новость успешно отредактирована';
}
} else $msg = 'Одно из полей не заполнено';
}
?>
<?if ($msg) {?><div class="alert alert-error"><?=$msg?></div><?}?>
<?if ($msgsucc) {?><div class="alert alert-success"><?=$msgsucc?></div><?}?>
<form action="" method="POST" enctype='multipart/form-data'>
<h4>Добавление новости</h4>
<input type="text" name="title" value="<?=$news['title']?>"> Заголовок <br>
<select name="cat">
<?while ($cat=mysql_fetch_assoc($cats)) {?><option value="<?=$cat['id']?>"<?if ($news['cat'] == $cat['id']) echo ' selected';?>><?=$cat['title']?></option><?}?>
</select> Категория <br>
<input type="file" name="FILE" value="<?=$_FILE['FILE']?>"> Изображение<br>
<h5>Содержание</h5><textarea id="editor" name="text"><?=$news['text']?></textarea><br>
<input type="submit" value="Отправить" class="btn"> <input type="button" class="btn" value="Просмотр"> 
</form>
<?
} else {echo '<div class="alert alert-error">У вас недостаточно прав для редактирования этой новости</div>';}
} else {echo '<div class="alert alert-error">Новость не найдена</div>';}} else {
?>
<h4>Выберите новость</h4>
<table width=100% class="table table-condensed table-striped">
<tr><td>ID</td><td>Название</td><td>Дата</td></tr>
<?$news = mysql_query("SELECT * FROM news ORDER BY date DESC LIMIT 0,20");
while ($nw = mysql_fetch_assoc($news)) {
echo '<tr><td>'.$nw['id'].'</td><td><a href="?md=nedit&id='.$nw['id'].'">'.$nw['title'].'</a></td><td>'.date('d.m.Y',$nw['date']).'</td></tr>';
}?>
</table>
<?}
} else
if ($_SESSION['rank']>=2 AND $_GET['md']) {
if ($_GET['md'] == 'npr') { 
// ------------------------------ Добавление программы ------------------------------
if ($_POST) {
}
$cats = mysql_query("SELECT * FROM cats WHERE cat='catalog'");
$devs = mysql_query("SELECT * FROM devs");
?>
<h4>Добавление программы</h4>
<form action="" method="POST">
<input name="title" type="text" value="<?=$_POST['title']?>"> Название<br>
<select name="cat">
<?while ($cat=mysql_fetch_assoc($cats)) {?><option value="<?=$cat['id']?>"<?if ($_POST['cat'] == $cat['id']) echo ' selected';?>><?=$cat['title']?></option><?}?>
</select> Категория<br>
<select name="dev">
<?while ($dev=mysql_fetch_assoc($devs)) {?><option value="<?=$dev['id']?>"<?if ($_POST['dev'] == $dev['id']) echo ' selected';?>><?=$dev['name']?></option><?}?>
</select> Разработчик<br>
<input name="lic" type="text" value="<?=$_POST['lic']?>"> Лицензия<br>
<input name="cost" type="text" value="<?=$_POST['cost']?>"> Цена<br>
<h4>Описание</h4>
<textarea id="editor" name="desc" type="text"><?=$_POST['desc']?></textarea><br>
<input type="submit" value="Отправить" class="btn">
</form>
<?
}
} else { 
// ------------------------------ Главная панель ------------------------------
?>
<table width=100% id="admin"><tr><td colspan=2><h4>Панель журналиста</h4></td></tr>
<tr>
<td><a href="?md=nvers">
<h5>Обновить программу</h5>
 <span class="help-block">Добавление новой версии существующей программы</span>
</a></td>
<td><a href="?md=nnews">
<h5>Добавить новость</h5>
<span class="help-block">Отправить новость на модерацию (после проверки будет показана на главной)</span>
</a></td></tr>
<tr><td><a href="?md=nedit">
<h5>Редактировать новости</h5>
<span class="help-block">Изменение прежде добавленной новости</span>
</a></td></tr>
<?if ($_SESSION['rank']>=2) {?>
<tr><td colspan=2><h4>Панель модератора</h4></td></tr>
<tr><td><a href="?md=npr">
<h5>Добавить программу</h5>
<span class="help-block">Добавление новой программы в каталог</span>
</a></td>
<td><a href="?md=mnews">
<h5>Новости на модерации</h5>
<span class="help-block">Подтверждение или удаление заявок на добавление новости</span>
</a></td></tr>
<tr><td><a href="?md=mcom">
<h5>Редактировать отзывы</h5>
<span class="help-block">Чтение и изменение отзывов, добавленных пользователями в каталоге и новостях</span>
</a></td>
<td><a href="?md=npr">
<h5>Редактировать программу</h5>
<span class="help-block">Изменение информации о прежде добавленной программе</span>
</a></td></tr>
<tr><td><a href="?md=ban">
<h5>Бан пользователя</h5>
<span class="help-block">Раздача бананов, налетай народ ;)</span>
</a></td></tr><?}?>
<?if ($_SESSION['rank'] == 3) {?>
<tr><td colspan=2><h4>Панель всемогущего</h4></td></tr>
<tr><td><a href="?md=uedit">
<h5>Изменение пользователя</h5>
<span class="help-block">Редактирование информации о пользователях</span>
</a></td></tr><?}?>
</table>
<?}} else {
echo '<div class="alert alert-error">Чего это мы тут забыли, а?</div>';
}
include('./foot.php');?>