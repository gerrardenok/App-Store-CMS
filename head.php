<?if ($_POST['login']) {
$user = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE login='".safe($_POST['login'])."'"));
if ($user['login']) {
if ($user['pass'] == md5($_POST['pass'])) {
$_SESSION['login'] = $user['login'];
$_SESSION['id'] = $user['id'];
$_SESSION['rank'] = $user['rank'];
}
}
} else
if ($_GET['act'] == 'logout') {
unset($_SESSION['login']);
unset($_SESSION['id']);
unset($_SESSION['rank']);
}?>
<!DOCTYPE html>
    <html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>App-Store</title>
    <link rel="stylesheet" type="text/css" media="all" href="/css/style.css" />

	<link rel="stylesheet" type="text/css" media="all" href="/css/bootstrap.css" />
	<link rel="shortcut icon" href="/img/favicon.ico" />
	<script type="text/javascript" src="/js/jquery-1.8.2.min.js"></script>
	<script type="text/javascript" src="/js/bootstrap.js"></script>
	<script type="text/javascript" src="/js/js.js"></script>
	<script src="/js/jquery.wysibb-1.3.0.js"></script>
<link rel="stylesheet" href="/theme/default/wbbtheme.css" type="text/css" />

    </head>
    <body>
	
<div class="bg"><div class="icon"></div></div>	
<div class="navbar navbar-inverse">
              <div class="navbar-inner" style="border-radius:0;position:fixed;width:100%;left:0;top:0;">
                <div class="container">
                  <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-inverse-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </a>
                  <a class="logo" href="/"></a>
                  <div class="nav-collapse collapse navbar-inverse-collapse">
                    <ul class="nav">
                      <li class="active"><a href="/">Главная</a></li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Новости <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                          <li><a href="/?cat=1">IT Live</a></li>
                          <li><a href="/?cat=2">Дайджест</a></li>
						  <li><a href="/?cat=3">Online</a></li>
                        </ul>
                      </li>
					  <li><a href="/cat.php">Каталог</a></li>
                    </ul>
                    <ul class="nav pull-right">
					  <?if (!$_SESSION['login']) {?><li><a href="/reg.php">Регистрация</a></li><?}?>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?if ($_SESSION['login']) echo $_SESSION['login']; else echo 'Вход';?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                          <?if ($_SESSION['login']) {?>
						  <?if ($_SESSION['rank'] >= 1) {?><li><a href="/admin.php">Центр управления</a></li><?}?>
						  <li><a href="/user.php">Профиль</a></li>
                          <li class="divider"></li>
                          <li><a href="?act=logout">Выйти</a></li><?} else {?>
						  <div class="login">
						  <form action="" method="POST">
						  <input type="text" name="login"><br>
						  <input type="password" name="pass"><br>
						  <button class="btn btn-primary btn-block" onclick="submit()" style="margin:0;">Войти</button>
						  </table></form></div>
						  <?}?>
                        </ul>
                      </li>
                    </ul>
                  </div></div></div></div>
	
	<div id="mblock">
	<table id="mtable" cellspacing=0 >
	<tr><td colspan=2 class="search"><input type="text" name="search"><button class="btn btn-primary" onclick="submit()" style="float:left;margin-left:10px;height:38px;margin-top:0;">Искать</button></td></tr>
	<tr><td class="content">
	<div class="str"></div>
