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

    if (isset($_GET['set'])) {
        $state = (int)$_GET['set'];
        if ($state < 1 || $state > 3) {
            show_error("Не вірний статус замовлення");
        }

        if (update_order_state($orderId, $state)) {
            echo getOrderState($state);
        } else {
            show_error("Помилка бази даних");
        }
    } else {
        echo "<select class='sel-user' id='s$orderId' style='width:150px;'>";
        foreach (range(1, 2) as $state) {
            echo "<option value='$state'>".getOrderState($state)."</option>";
        }
        echo "</select>";
    }
?>
<script>
$(document).ready(function() {
    $(".sel-user")
    .click(function(event) {
        event.stopImmediatePropagation();
    }).change(function(e){
        id = $(this).attr('id');
        state = $(this).find(":selected").val();
        $('#' + id).load("update_order.php?set=" + state + "&id=" + id.substr(1));
    });
});
</script>