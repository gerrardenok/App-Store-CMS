<?function safe($code) {
$code = htmlspecialchars(stripslashes($code));
return $code;
}

$mnt = array('1'=>'января', '2'=>'февраля', '3'=>'марта', '4'=>'апреля','5'=>'мая', '6'=>'июня', '7'=>'июля', '8'=>'августа','9'=>'сентября', '10'=>'октября', '11'=>'ноября', '12'=>'декабря');

function number($n, $titles) {
  $cases = array(2, 0, 1, 1, 1, 2);
  return $titles[($n % 100 > 4 && $n % 100 < 20) ? 2 : $cases[min($n % 10, 5)]];
}

function avatar($id) {
if (file_exists("upload/avatars/".$id.".gif")) {echo '/upload/avatars/'.$id.'.gif';}  elseif (file_exists("upload/avatars/".$id.".png")) {echo '/upload/avatars/'.$id.'.png';} elseif (file_exists("upload/avatars/".$id.".jpeg")) {echo '/upload/avatars/'.$id.'.jpeg';} else {echo '/img/noavatar.png';}
}?>