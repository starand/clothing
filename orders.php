<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/common/headers.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (!userHasPermission(PERM_VIEW_ORDERS)) {
        show_error("Недостатньо прав для здійснення операції");
    }

    $orders = get_orders();

    echo "<center><h2>Нові замовлення</h2>";
    echo "<table class='price-list' cellspacing='1' cellpadding='3' style=''>";
    echo "<tr><td class='price-list-header'>#</td>";
    echo "<td class='price-list-header'> &nbsp; Клієнт &nbsp; </td>";
    echo "<td class='price-list-header'> &nbsp; Контакти &nbsp; </td>";
    echo "<td class='price-list-header'> &nbsp; Оплата &nbsp; </td>";
    echo "<td class='price-list-header'> &nbsp; Доставка &nbsp; </td>";
    echo "<td class='price-list-header'> &nbsp; Ціна &nbsp; </td>";
    echo "<td class='price-list-header'> &nbsp; Статус &nbsp; </td></tr>";

    foreach ($orders as $order) {
        $idx = 1;
        $class = "price-list";
        echo "<tr class='$class'>
                <td class='$class' id='{$order['o_id']}'>{$order['o_id']}</td>
                <td class='$class' id='{$order['o_id']}'>{$order['o_name']}</td>
                <td class='$class' id='{$order['o_id']}'>{$order['o_phone']} {$order['o_mail']}</td>
                <td class='$class' id='{$order['o_id']}'>".get_pay_name($order['o_pay'])."</td>
                <td class='$class' id='{$order['o_id']}'>".get_delivery_name($order['o_delivery'])."</td>
                <td class='$class' id='{$order['o_id']}' style='color: green;'>{$order['o_total_price']}</td>
                <td class='$class' id='s{$order['o_id']}' style='color:green;'>".getOrderState($order['o_state'])."</td>
            </tr>";
        
        echo "<tr><td></td><td colspan='4'>
            <table>
                <tr>
                    <td class='price-list-header'> &nbsp; # &nbsp; </td>
                    <td class='price-list-header'> &nbsp; Вид &nbsp; </td>
                    <td class='price-list-header'> &nbsp; Назва м</td>
                    <td class='price-list-header'> &nbsp; Розмір &nbsp; </td>
                    <td class='price-list-header'> &nbsp; Ціна &nbsp; </td>
                </tr>";

        $items = explode("\n", trim($order['o_desc']));
        foreach($items as $item) {
            $orderItems = explode(":", $item);
            $orderItems[1] = substr(trim($orderItems[1]), 0, -1);

            $goodId = $orderItems[0];
            $good = get_good($goodId);
            $dims = explode(",", $orderItems[1]);

            foreach ($dims as $dimId) {
                $dimData = getDimRow($dimId);
                $dimArray = parseDGString($dimData['dg_data']);
                $dimension = count($dimArray) ? $dimArray[0] : $orderItems[1];
                echo "<tr>
                        <td class='$class'>{$idx}</td>
                        <td class='$class'><img src='".get_image_url($good['g_image'])."' style='width:30px;'></td>
                        <td class='$class' id='g$goodId'>{$good['g_title']}</td>
                        <td class='$class'>$dimension</td>
                        <td class='$class'>{$good['g_price']}</td>
                    </tr>";
                //$dim_str .= parseDGString($dimData['dg_data'])[0];
                ++$idx;
            }
        }
        echo "</table>
            </td></tr>";
    }

    echo "</table>";
?>


<script>
$(document).ready(function() {
    $(".price-list").click(function(event) {
        event.stopImmediatePropagation();
        //$("input").blur();
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'g') {
            $('#main').load("show_good.php?id=" + id.substr(1));
        } else if (id.substr(0, 1) == 's') {
            $("#" + id).load("update_order.php?id=" + id.substr(1));
        }
    });
});
</script>