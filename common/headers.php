<?
    $shift = "";
    if (defined("ROOT_SHIFT")) {
        $shift = ROOT_SHIFT;
    }

    include_once "functions.php";
?>
<HEAD>
    <link rel='icon' href='http://www.boyclothing.lviv.ua/favicon.ico'>
    <LINK href='<?=("$shift/themes/light/".(isMobile() ? "main" : "main"));?>.css' rel=stylesheet type=text/css>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
    <title>Одяг та взуття на хлопчика</title>

    <script>parent.document.getElementById('main_error').innerHTML = ' &nbsp; '</script>
</HEAD>