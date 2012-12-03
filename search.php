<?
include('./config.php');
include('./head.php');
if ($_GET['act'] == 'int') {
if ($_GET['name']) {
$name = safe($_GET['name']);
$ints = mysql_query("SELECT * FROM users WHERE `ints` LIKE '%$name%'");
if (mysql_num_rows($ints)!=0) {
while($int=mysql_fetch_assoc($ints)) {
?>
<div class="user">
<div class="avatar"></div>
<div class="desc"><b><?=$int['login']?></b><br>
<b>Интересы:</b> <?=$int['ints']?></div>
</div>
<?}} else {echo '<div class="msg">Пользователи с таким интересом не найдены</div>';}
} else {echo '<div class="msg">Не выбран интерес</div>';}
}
include('./foot.php');
?>