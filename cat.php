<?
include('./config.php');
include('./head.php');
if ($_GET['id']) {
$pr = mysql_fetch_array(mysql_query("SELECT * FROM catalog WHERE id='".safe($_GET['id'])."'"));
if (!$pr['id']) {echo '<div class="msg">Программа не найдена</div>';} else {
if ($_SESSION['login']) {
$user = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE login='".$_SESSION['login']."'"));
$cat = mysql_fetch_array(mysql_query("SELECT * FROM cats WHERE id='".$pr['cat']."'"));
$rates = explode(';',$user['rates']);$i =0;
while($rates[$i]) {$ratex = explode(':',$rates[$i]);if ($ratex[1] == $pr['id']) {$urate = $ratex[0];} $i++;}}
if ($_GET['rate']>0 AND $_GET['rate']<=10 AND $_SESSION['login']) {
if (!$urate) {
$urate = $_GET['rate'];
mysql_query("UPDATE users SET rates='".safe($_GET['rate']).":".$pr['id'].";' WHERE login='".$_SESSION['login']."'");
$nrate = $pr['rate']+$_GET['rate'];
mysql_query("UPDATE catalog SET rate='".$nrate."',cnt=cnt+1 WHERE id='".safe($_GET['id'])."'");
$pr['rate']=$pr['rate']+$_GET['rate'];
$pr['cnt']++;
}
}
if ($pr['cnt']) {$rate=$pr['rate']/$pr['cnt'];}
$vers = mysql_query("SELECT * FROM vers WHERE pid='".$pr['id']."' AND status=1 ORDER BY tadd DESC");
while($vs = mysql_fetch_assoc($vers)) {
$dl = explode(';',$vs['dl']);if (!$dll) {$dll = $dl[0];$dlv = $vs['vers'];}
$ver .= '		  <b>v.'.$vs['vers'].'</b><br>
				  Релиз: '.date('d.m.Y',$vs['tadd']).'<br>
				  <a href="javascript://" onClick=spoiler("sp'.$vs['id'].'");><b>Скачать</b></a> <span id="sp'.$vs['id'].'" class="spoiler">: <a target="_blank" href="'.$dl[0].'">Оф. сайт</a>';
				  $i=1;while($dl[$i]) {$ver .= ' | <a target="_blank" href="'.$dl[$i].'">Зеркало '.$i.'</a>';}
				  $ver .= '</span><br><br>';
				  }
$dev = mysql_fetch_array(mysql_query("SELECT * FROM devs WHERE id='".$pr['dev']."'"));
if ($dev['url']) {$dev = '<a href="'.$dev['url'].'" target="blank_">'.$dev['name'].'</a>';}
else {$dev = $dev['name'];}
?>
<div id="pr">
<table><tr>
<td class="lp">
<img src="/img/thumb_cat.png" class="scr" style="background-image:url(../upload/catalog/<?=$pr['scr']?>);"></div>
<a href="<?=$dll?>" target="blank_" class="dllast btn btn-success"><div><span class="dl">Скачать</span><br><?=$dlv?></div></a>
</td>
<td class="rp">
<span class="title"><?=$pr['title']?></span><br><br>
<b>Категория:</b> <a href="/search.php?act=news&cat=<?=$pr['cat']?>"><?=$cat['title']?></a><br>
<b>Разработчик:</b> <?=$dev?><br>
<b>Лицензия:</b> <?=$pr['lic']?><br>
<b>Цена:</b> <?=$pr['price']?>$<br><br>
<b>Рейтинг</b> <?if ($urate or !$_SESSION['login']) {?><?=round($rate,1)?> (<?=$pr['cnt']?> <?=number($pr['cnt'],array('оценка', 'оценки', 'оценок'))?>)<?}?>
<?if ($rate*100/10>=75) {$type = 'bar-success';} elseif ($rate*100/10>=50) {$type = 'bar-warning';} else {$type = 'bar-danger';}?>
<div class="progress">
<?if ($urate or !$_SESSION['login']) {?>
<div class="bar <?=$type?>" style="width:<?=$rate*100/10?>%;"></div>
<?} else {?>
<a href="?id=<?=$pr['id']?>&rate=1"><div class="bar bar-danger" style="width: 10%;">1</div></a>
<a href="?id=<?=$pr['id']?>&rate=2"><div class="bar bar-danger" style="width: 10%;">2</div></a>
<a href="?id=<?=$pr['id']?>&rate=3"><div class="bar bar-danger" style="width: 10%;">3</div></a>
<a href="?id=<?=$pr['id']?>&rate=4"><div class="bar bar-danger" style="width: 10%;">4</div></a>
<a href="?id=<?=$pr['id']?>&rate=5"><div class="bar bar-warning" style="width: 10%;">5</div></a>
<a href="?id=<?=$pr['id']?>&rate=6"><div class="bar bar-warning" style="width: 10%;">6</div></a>
<a href="?id=<?=$pr['id']?>&rate=7"><div class="bar bar-warning" style="width: 10%;">7</div></a>
<a href="?id=<?=$pr['id']?>&rate=8"><div class="bar bar-success" style="width: 10%;">8</div></a>
<a href="?id=<?=$pr['id']?>&rate=9"><div class="bar bar-success" style="width: 10%;">9</div></a>
<a href="?id=<?=$pr['id']?>&rate=10"><div class="bar bar-success" style="width: 10%;">10</div></a>
<?}?>
</div>

<?if ($urate) {?>
Ваша оценка: <?=$urate?> 
<?}?>

</td>
</tr>
<tr><td>
			<div class="tabbable tabs-left">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#lA" data-toggle="tab">Описание</a></li>
				<li><a href="#lB" data-toggle="tab">Версии</a></li>
                <li><a href="#lC" data-toggle="tab">Арт</a></li>
                <li><a href="#lD" data-toggle="tab">Видео</a></li>
				<li><a href="#2A" data-toggle="tab">Награды</a></li>
				
              </ul></div></td>
			  <td class="rp">
              <div class="tab-content">
                <div class="tab-pane active" id="lA">
                  <?=$pr['text']?>
                </div>
                <div class="tab-pane" id="lB">
                  <?=$ver?>
                </div>
                <div class="tab-pane" id="lC">
                  Арт-контент
                </div>
				<div class="tab-pane" id="lD">
                  Видео-контент
                </div>
				<div class="tab-pane" id="2A">
                  Награды-контент
                </div>
              </div>
            </td></tr>
</table>
<?
$time = time();
$uname=$_SESSION['login'];
$content_id = $pr['id'];
if(!empty($_POST['message']) AND $uname) {
  $comment = mysql_real_escape_string(strip_tags($_POST['message'], "<p><font><img>"));
  $parent_id = intval($_POST['parent']);
  mysql_query("INSERT INTO comments (`id`, `name`, `comment`, `ctype`, `content_id`, `parent_id`, `time`) VALUES (NULL, '$uname', '$comment', 'catalog', '$content_id', '$parent_id', '$time')");
}

/*
*/

function crazysort(&$comments, $parentComment = 0, $level = 0, $count = null){
  if (is_array($comments) && count($comments)){
    $return = array();
    if (is_null($count)){
      $c = count($comments);
    }else{
      $c = $count;
    }
    for($i=0;$i<$c;$i++){
      if (!isset($comments[$i])) continue;
      $comment = $comments[$i];
      $parentId = $comment['parent_id'];
      if ($parentId == $parentComment){
        $comment['level'] = $level;
        $commentId = $comment['id'];
        $return[] = $comment;
        unset($comments[$i]);
        while ($nextReturn = crazysort($comments, $commentId, $level+1, $c)){
          $return = array_merge($return, $nextReturn);
        }
      }
    }
    return $return;
  }
  return false;
}

$msg = array();
$result = mysql_query("SELECT * FROM comments WHERE content_id='$content_id' AND ctype='catalog'") or die(mysql_error());
while($row = mysql_fetch_assoc($result)){
  $msg[] = $row;
}
$count = count($msg);

$parent = 0;
$form = "<div class='editor'>
<form id='comment-form' autocomplete='off' method='post'>
<input type='hidden' name='parent' value='{$parent}'>
<textarea name='message' class='message'></textarea><br><input id='submit' name='signup' type='submit' class='btn' value='Добавить' /></div>
</form>";

$i = 0;

  $comments = "<div class='well'><span style='float:left'>";
  if($count){$comments .= "Отзывы ({$count})";} else {$comments .= "Еще никто не добавил отзыв";}
  $comments .= "</span><span class='add-comment'>Добавить отзыв</span></div><div id='addcomment'>
<form id='comment-form' autocomplete='off' method='post'>
<input type='hidden' name='parent' value='{$parent}'>
<textarea name='message' class='message'></textarea><br><input id='submit' name='signup' type='submit' class='btn' value='Добавить' /></div>
</form>".$form;
if($count){ 
  $msg = crazysort($msg);
  while($i<$count){
    $margin = $msg[$i]['level'] * 20;
    $date = date("d.m.Y в H:i",$msg[$i]['time']);
    $comments .= "<div id='msg{$msg[$i]['id']}' style='margin-left: {$margin}px'><div class='comment-title'><span style='float:left'><b>{$msg[$i]['name']}</b> <small>({$date})</small></span><span class='comment-ans' id={$msg[$i]['id']}>ответить</span></div><div class='comment-message'>{$msg[$i]['comment']}</div></div>";
    $i++;
  }  
}
?>


<div id="comments">
<?
echo $comments;
?>
</div>
<script>
$(function () {

  $('.add-comment').click(function(){
  spoiler('addcomment');
  });
  $('.comment-ans').click(function(){
    var $editor = $('.editor');
    $editor.hide();
    var clone = $editor.clone();
    $editor.remove();
	if (mid == $(this).attr("id")) {
	setTimeout(function(){
      $("div#msg"+mid).slideDown();
    }, 200);
	} else {
	var mid = $(this).attr("id");
    setTimeout(function(){
      $(clone).css("margin", "5px 0 5px 20px");
      $(clone).insertAfter("div#msg"+mid).slideDown();
      $("input[name=parent]").val(mid);
    }, 200);}
  });
    
});
</script>
</div>
<?}} else {?>
<h4>Новые поступления</h4>
<table class="table table-striped">
<?
$prog = mysql_query("SELECT * FROM catalog ORDER BY tadd DESC");
$i=1;
while ($pr = mysql_fetch_assoc($prog)) {
$prg[$pr['id']]['title'] = $pr['title'];
$prg[$pr['id']]['scr'] = $pr['scr'];
$pids .= $pr['id'].';';
if ($i<=5) {
echo '<tr><td><a href="/cat.php?id='.$pr['id'].'">'.$pr['title'].'</a></td></tr>';}
}
$vers = mysql_query("SELECT * FROM vers WHERE status=1 ORDER BY tadd DESC LIMIT 0,10");
?>
</table>
<h4>Обновления</h4>
<table class="table table-striped">
<?
while ($vs = mysql_fetch_assoc($vers)) {
if (!preg_match('/\b'.$vs['pid'].'\b/i', $ids, $match)) {
$ids .= ' '.$vs['pid'].' ';
echo '<tr><td><a href="/cat.php?id='.$vs['pid'].'">'.$prg[$vs['pid']]['title'].' v.'.$vs['vers'].'</a></td></tr>';
}}
?>
</table>
<h4>Программы</h4>
<ul class="thumbnails prs">
<? 
$ids = '';
$pids = explode(';',$pids);
$pidn = count($pids)-1;
if ($pidn>15) {$pn = 15;} else {$pn = $pidn;}
for ($i=1;$i<=$pn;$i++){
$pd = '';
while (!$pd) {
$pid = mt_rand(0,$pidn-1);
if (!preg_match('/\b'.$pids[$pid].'\b/i', $ids, $match)) {
$ids .= ' '.$pids[$pid].' ';
$pd = $pids[$pid];}}
?>
<li><a href="?id=<?=$pids[$pid]?>">
    <div class="thumbnail">
    <img src="/upload/catalog/<?=$prg[$pids[$pid]]['scr']?>" width="150" height="200" alt="">
	<div class="caption">
	<h5><?=$prg[$pids[$pid]]['title']?></h5>
	</div>
    </div>
    </a></li>
<?
}
?>	
</ul>
<?
}
include('./foot.php');
?>