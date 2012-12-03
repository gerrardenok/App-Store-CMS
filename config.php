<?session_start();
$config = array( 'hostname' => 'localhost', 'username' => 'as', 'password' => '1', 'dbname' => 'as' );
if( !mysql_connect($config['hostname'], $config['username'], $config['password']) )
{
echo 'Ошибка подключения к базе. Попробуйте позже';
        exit();
}
if( !mysql_select_db($config['dbname']) )
{
echo 'Ошибка подключения к базе. Попробуйте позже';
        exit();
}
mysql_query("SET NAMES 'UTF8'");
include('./functions.php');
if ($_SESSION['login']) {mysql_query("UPDATE users SET lasttime='".time()."' WHERE login='".$_SESSION['login']."'");}?>