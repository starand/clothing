<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/common/headers.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (!userHasPermission(PERM_EDIT_GOOD)) {
        show_error("Недостатньо прав для здійснення операції");
    }

    if (!isset($_GET['arriveId'])) {
        show_error("Не правильний код товару");
    }

    $arriveId = (int)$_GET['arriveId'];
    $good = get_good($arriveId);
    if (!$good) {
        show_error("Не вірний код товару");
    }

    updateGoodState($arriveId, GOOD_STATE_PRESENT);

    $_GET['id'] = $arriveId;
    include ROOT_PATH."/show_good.php";
?>
