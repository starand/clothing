<?
    include_once "common/session_check.php";
    include_once "common/headers.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";
?>
<div style='font-size:20px;font-weight:bold;'>Одяг та взуття на хлопчика. Низькі ціни!</div><BR>
<?
    $goods = get_latest_goods(20);
    show_goods($goods, 1);

    include_once ROOT_PATH."/common/good_scripts.inc";
?>