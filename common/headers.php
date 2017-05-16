<?
    $shift = "";
    if (defined("ROOT_SHIFT")) {
        $shift = ROOT_SHIFT;
    }

    include_once "functions.php";

    echo "<HEAD>
    <LINK href='$shift/themes/light/".(isMobile() ? "main" : "main").".css' rel=stylesheet type=text/css>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
    <title>Одяг та взуття на хлопчика</title>
    </HEAD>";
?>