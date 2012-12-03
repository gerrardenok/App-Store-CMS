<?php

$url = "upload/news";
if($_FILES['img']['type'] == 'image/gif' OR $_FILES['img']['type'] == 'image/png' OR $_FILES['img']['type'] == 'image/jpeg') {$f = $_FILES['img']['tmp_name'];
if ($_FILES['img']['type'] == 'image/gif') {$src = imagecreatefromgif($f);$format = 'gif';}
if ($_FILES['img']['type'] == 'image/png') {$src = imagecreatefrompng($f);$format = 'png';}
if ($_FILES['img']['type'] == 'image/jpeg') {$src = imagecreatefromjpeg($f);$format = 'jpeg';}
$w_src = imagesx($src);
$h_src = imagesy($src);
$imname = md5(mt_rand());
if($_FILES['img']['size'] != 0) {
if(is_uploaded_file($_FILES['img']['tmp_name'])) {
if(move_uploaded_file($_FILES['img']['tmp_name'], $url."/".$imname.".".$format)) {}
else {$msg = 'Произошла ошибка при перемещении файла в папку'.$url;}}
else {$msg = 'Прозошла ошибка при загрузке файла на сервер';}}
else {$msg = 'Ошибка при загрузке изображения';}}
else {$msg = 'Файл не является картинкой формата GIF,PNG,JPEG';}


$fileupload = $_FILES["img"]['tmp_name'];

$isIframe =($_POST["iframe"]) ? true:false;
$idarea = $_POST["idarea"];


if ($isIframe) {
	#use for iframe upload
	echo '<html><body>OK<script>window.parent.$("#'.$idarea.'").insertImage("'.$url."/".$imname.".".$format.'",false).closeModal().updateUI();</script></body></html>';
}else{
	// use for drag&drop
	header("Content-type: text/javascript");
	if (!$xml) {
		echo '{"status":0,"msg":"Ошибка при загрузке файла"}';
	}else if (isset($xml->error)) {
		echo '{"status":0,"msg":"'.$xml->error.'"}';
	}else{
		#OK
		echo '{"status":1,"msg":"OK","image_link":"'.$xml->links->image_link.'","thumb_link":"'.$xml->links->thumb_link.'"}';
	}
}
### IMAGESHACK



?> 
?> 