<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/common/headers.php";
    include_once ROOT_PATH."/db/db.php";

    if (!userHasPermission(PERM_UPDATE_ORDERS)) {
        show_error("Недостатньо прав для здійснення операції");
    }

    if (!isset($_GET['id'])) {
        show_error("Не правильний код замовлення");
    }

    $orderId = (int)$_GET['id'];
    $order = get_order($orderId);
    if (!$order) {
        show_error("Не вірний номер замовлення");
    }

    if (isset($_GET['send'])) {
        if (update_order_state($orderId, ORDER_SENT)) {
           echo "<img class='action' id='$orderId' src='/images/done.png'>";
        } else {
            show_error("DB Error");
        }
    } else if (isset($_GET['delete'])) {
        if (delete_order($orderId)) {
            load_page("orders.php");
            die();
        } else {
            show_error("DB Error");
        }
    }
?>
