<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (!isset($_GET['id'])) {
        show_error("Вибрано не вірний товар");
    }

    $goodId = (int)$_GET['id'];
    $good = get_good($goodId);
    if (!$good) {
        show_error("Вибрано не вірний товар");
    }


?>
