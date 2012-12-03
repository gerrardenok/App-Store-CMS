<?
include('./config.php');

include('./head.php');
if ($_GET['id']) {
$n = mysql_fetch_array(mysql_query("SELECT * FROM news WHERE id='".safe($_GET['id'])."'"));
if ($n['id']) {
$text = $n['text'];
$text = preg_replace('/\n/si','<br>',$text);
$text = preg_replace('/\[end\]\[\/end\]/i', '', $text);
$text = preg_replace('/\[img\](.*?)\[\/img\]/i', '<img src="$1">', $text);
$text = preg_replace('/\[url=(.*?)\](.*?)\[\/url\]/i', '<a href="$1">$2</a>', $text);
$text = preg_replace('/\[b\](.*?)\[\/b\]/i', '<b>$1</b>', $text);
$text = preg_replace('/\[i\](.*?)\[\/i\]/i', '<em>$1</em>', $text);
$text = preg_replace('/\[u\](.*?)\[\/u\]/i', '<u>$1</u>', $text);
?>
<div class="full">
<div class="well"><?=$n['title']?></div>
<center><img class="scr" src="/upload/news/<?=$n['scr']?>"></center>
<?=$text?>
</div>
<?
$time = time();
$uname=$_SESSION['login'];
$content_id = $n['id'];
if(!empty($_POST['message']) AND $uname) {
  $comment = mysql_real_escape_string(strip_tags($_POST['message'], "<p><font><img>"));
  $parent_id = intval($_POST['parent']);
  mysql_query("INSERT INTO comments (`id`, `name`, `comment`, `ctype`, `content_id`, `parent_id`, `time`) VALUES (NULL, '$uname', '$comment', 'news', '$content_id', '$parent_id', '$time')");
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
$result = mysql_query("SELECT * FROM comments WHERE content_id='$content_id' AND ctype='news'");
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
<?
} else echo '<div class="msg">Новость не найдена</div>';
} else {
if ($_GET['cat']) {$catsql = " AND cat='".safe($_GET['cat'])."' ";}
$news = mysql_query("SELECT * FROM news WHERE status='1'".$catsql." ORDER BY date DESC");
if (mysql_num_rows($news)!=0) {
while ($n = mysql_fetch_assoc($news)) {
$text = explode('[end][/end]',$n['text']);
$text = $text[0];
$text = preg_replace('/\n/si','<br>',$text);
$text = preg_replace('/\[end\]\[\/end\]/i', '', $text);
$text = preg_replace('/\[img\](.*?)\[\/img\]/i', '<img src="$1">', $text);
$text = preg_replace('/\[url=(.*?)\](.*?)\[\/url\]/i', '<a href="$1">$2</a>', $text);
$text = preg_replace('/\[b\](.*?)\[\/b\]/i', '<b>$1</b>', $text);
$text = preg_replace('/\[i\](.*?)\[\/i\]/i', '<em>$1</em>', $text);
$text = preg_replace('/\[u\](.*?)\[\/u\]/i', '<u>$1</u>', $text);

?>
<div class="news">
<a href="#img<?=$n['id']?>" data-toggle="modal" role="button" class="thumbnail"><img src="/upload/news/<?=$n['scr']?>"></a>
<div class="text">
<div class="title"><a href="?id=<?=$n['id']?>"><h5><?=$n['title']?></h5></a></div>
<span class="date"><?=date('d.m.Y в H:m',$n['date'])?></span><br>
<?=$text?>
</div>
</div>
<div id="img<?=$n['id']?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="img<?=$n['id']?>Label" aria-hidden="true">
<a href="#" data-dismiss="modal" aria-hidden="true" class="thumbnail"><img src="/upload/news/<?=$n['scr']?>"></a>
</div>
<?}} else echo '<div class="msg">Нет новостей</div>';}
include('./foot.php');?>