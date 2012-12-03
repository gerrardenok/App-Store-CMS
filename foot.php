</td>
<td class="sidebar">
<div class="soc">
<a href="http://vk.com" target="blank_" class="vk"></a>
<a href="http://fb.com" target="blank_" class="fb"></a>
<a href="http://twitter.com/" target="blank_" class="tw"></a>
<a href="http://plus.google.com" target="blank_" class="gp"></a>
</div>

<div class="block well">
<div class="title">Топ программ</div>
<div class="cont">
<?$top = mysql_query("SELECT * FROM catalog ORDER BY rate/cnt LIMIT 0,10");
$i=0;
while ($tp = mysql_fetch_assoc($top)) {
$i++;
?>
<?=$i?>. <a href="/cat.php?id=<?=$tp['id']?>"><?=$tp['title']?></a> <span class="mark"><?=$tp['rate']/$tp['cnt']?></span> (<?=$tp['cnt']?> <?=number($tp['cnt'],array('оценка','оценки','оценок'))?>)<br>
<?
}?>
</div>
</div>

<div class="block well">
<div class="title">Последние отзывы</div>
<div class="cont">
<?$cmns = mysql_query("SELECT * FROM comments ORDER BY time DESC LIMIT 0,10");
$i=1;
while ($cm = mysql_fetch_assoc($cmns)) {
$cname[$i] = $cm['name'];
$ccid[$i] = $cm['content_id'];
$comm[$i] = $cm['comment'];
$ctype[$i] = $cm['ctype'];
if ($cm['ctype'] == 'catalog') {if (!$cids) {$cids = "id='".$ccid[$i]."'";} else {$cids .= " OR id='".$ccid[$i]."'";}}
if ($cm['ctype'] == 'news') {if (!$nids) {$nids = "id='".$ccid[$i]."'";} else {$nids .= " OR id='".$ccid[$i]."'";}}
$i++;
}
if ($cids) {
$prs = mysql_query("SELECT * FROM catalog WHERE ".$cids);
while ($pr = mysql_fetch_assoc($prs)) {
$pname[$pr['id']] = $pr['title'];
}}
if ($nids) {
$ns = mysql_query("SELECT * FROM news WHERE ".$nids) ;
while ($nw = mysql_fetch_assoc($ns)) {
$nname[$nw['id']] = $nw['title'];
}}
$i = 1;
while ($cname[$i]) {
if ($ctype[$i] == 'catalog') {$name = $pname[$ccid[$i]];$url = 'cat';} else {$name = $nname[$ccid[$i]];$url = 'index';}
$cmn .= '<a href="/user.php?login='.$cname[$i].'">'.$cname[$i].'</a> в <a title="'.$comm[$i].'" href="/'.$url.'.php?id='.$ccid[$i].'">'.$name.'</a> <br>';
$i++;
}
echo $cmn;?>
</div>
</div>

</td></tr></table></div>
<div id="foot">
<div class="cpr"><a href="">My-Group</a> © 2012</div>
<div class="cntrs"><div></div><div></div><div></div></div></div>
</body>
</html>