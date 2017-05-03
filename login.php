<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/common/headers.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    clear_error();
    if (isset($_POST['login']) && isset($_POST['pswd'])) {
        $login = addslashes($_POST['login']);
        $pswd = $_POST['pswd'];



        $user = getUserByLogin($login);
        if (!$user || $user['u_pswd'] != md5($pswd)) {
            show_error("Не вірний логін або пароль!");
        }

        $_SESSION['cl_user'] = $user;
        clear_error();

        load_page('top.php', 'top');
        load_page('main.php', 'main');
        load_page('left_menu.php', 'left_menu');

        die();
    }

    if (isset($_GET['logout'])) {
        clearSession();

        load_page('top.php', 'top');
        load_page('login.php', 'main');
        load_page('left_menu.php', 'left_menu');
    }
?>

<center>
<table style='width:100%; height:300px;'>
<tr><td><center>

<form action='login.php' method='post' target='submit_frame'>
    <table cellspacing='0' cellpadding='1' class='login'>
        <tr><td colspan='2'><center><h2>Вхід на сайт</h2></td></tr>
        <tr><td>Логін: </td><td><input type='text' name='login'></td></tr>
        <tr><td>Пароль: </td><td><input type='password' name='pswd'></td></tr>
        <tr><td></td><td><input type='submit' value='Ввійти'></td></tr>
    </table>
 </form>

</td></tr>
</table>
