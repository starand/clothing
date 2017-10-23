<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    clear_error();
?>
<div style='font-size:20px;font-weight:bold;'>Одяг та взуття на хлопчика</div><BR>
<?
    $goods = get_latest_not_sold_goods(21);
    $sold_goods = get_latest_goods(21, 1);

    foreach ($sold_goods as $good) {
        array_push($goods, $good);
    }

    show_goods($goods, 3);

    include_once ROOT_PATH."/common/good_scripts.inc";

    echo "<div style='font-size:10px;'>Відвідувачі: ".get_visits_per_day().". Унікальні відвідувачі: ".count(get_uniq_visits_per_day())."</div>";
?>