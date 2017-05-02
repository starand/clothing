<?
    $shift = "/clothing";
    if (defined("ROOT_SHIFT")) {
        $shift = ROOT_SHIFT;
    }

    include_once "functions.php";

    echo "<HEAD><LINK href='$shift/themes/light/".(isMobile() ? "main" : "main").".css' rel=stylesheet type=text/css>";
    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'></HEAD>";
?>