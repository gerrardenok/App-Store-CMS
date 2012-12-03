<?
include('./config.php');
if ($_GET['login'] OR $_GET['id']) {
if ($_GET['login']) {$login = "login='".safe($_GET['login'])."'";} else {$login = "id='".safe($_GET['id'])."'";}
$user = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE ".$login));
} else {
$user = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE login='".$_SESSION['login']."'"));
}
include('./head.php');
if (!$user['id']) {echo '<div class="alert alert-error">Пользователь не найден</div>';} else {
$rate = explode(';',$user['rates']);$i =0;
while($rate[$i]) {
$ratex = explode(':',$rate[$i]);
$rates[$i] = $ratex[0];
$rateid[$i] = $ratex[1];
if ($i == 0) {$ids = 'id="'.$ratex[1].'"';} else {$ids .= ' OR id="'.$ratex[1].'"';}
$i++;}
if ($ids) {
$prs = mysql_query("SELECT * FROM catalog WHERE ".$ids);
while ($pr = mysql_fetch_assoc($prs)) {
$name[$pr['id']] = $pr['title'];
}}
?>
<div id="profile">
<table><tr>
<td class="lp" valign="top">
<a href="#" class="thumbnail"><img src="<?=avatar($user['id'])?>"></a>
<?if ($_SESSION['login'] == $user['login']) {?><a href="/edit.php"><div class="btn">Изменить</div></a><?}?></td>
<td class="rp" valign="top">
<div class="well"><?=$user['login']?></div>
<div class="alert alert-success">
<b>Дата рождения:</b> <?=$user['bday'].' '.$mnt[$user['bmonth']].' '.$user['byear']?> <span class="age"><?$age=date('Y')-$user['byear'];if($user['bmonth']>date('m') OR ($user['bmonth']==date('m') AND $user['bday']>date('d'))) {$age=$age-1;} echo $age;?> лет</span><br>
<b>Дата регистрации:</b> <?=date('d.m.Y',$user['regdate'])?><br>
<b>Последнее посещение:</b> <?=date('H:m, d.m.Y',$user['lasttime'])?></div>
<?if ($user['loc']) {?><b>Местонахождение: </b> <?=$user['loc']?><br><?}?>
<?if ($user['site']) {?><b>Личный сайт:</b> <a href="<?=$user['site']?>" target="_blank"><?=$user['site']?></a><br><?}?>
<?if ($user['blog']) {?><b>Блог:</b> <a href="<?=$user['blog']?>" target="_blank"><?=$user['blog']?></a><br><?}?><br>
<?if ($user['about']) {?><b>О себе:</b> <?=$user['about']?><br><?}?>
<?if ($user['ints']) {?><b>Интересы:</b> <?$int = explode(',',$user['ints']);$i=0;while ($int[$i]) {echo '<a href="/search.php?act=int&name='.trim($int[$i]).'">'.trim($int[$i]).'</a> ';$i++;}?><?}?><br>
<?if ($rates) {?>
<h4>Последние оценки</h4>
<?$i = 0;
while ($rates[$i]) {
?><span class="mark"><?=$rates[$i]?></span> <a href="/cat.php?id=<?=$rateid[$i]?>"><?=$name[$rateid[$i]]?></a><br><?
$i++;
}}?>
</td>

</tr></table>
</div>
<?}
include('./foot.php');?>