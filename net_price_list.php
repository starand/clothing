<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/common/headers.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (!userHasPermission(PERM_EDIT_NETPRICE) || !userHasPermission(PERM_EDIT_GOOD)) {
        show_error("Недостатньо прав для здійснення операції");
    }

    $goods = get_goods(100); // get all goods;

    echo "<center><h2>Список товарів</h2>";

    $investment = getTotalInvestment();
    $incomes = getTotalIncomes();
    $diff = $investment - $incomes;
    $earn = getSalesTotal();

    echo "<div style='font-size:12px;'>
        Вкладено: <span style='color: magenta;'>$investment</span>. 
        Вернулося: <span style='color: green;'>$incomes</span>. 
        Різниця: <span style='color: red;'>$diff</span>. 
        Зароблено: <span style='color: blue;'>$earn</span>. 
    </div>";
    
    echo "<table class='price-list' cellspacing='1' cellpadding='1'>
        <tr>
            <td class='price-list-header'>#</td>
            <td class='price-list-header'>Вид</td><td class='price-list-header'>Товар</td>
            <td class='price-list-header'> &nbsp; Разом &nbsp; </td>
            <td class='price-list-header'> &nbsp; № &nbsp; </td>
            <td class='price-list-header'> &nbsp; Чиста &nbsp; </td>
            <td class='price-list-header'> &nbsp; Різниця &nbsp; </td>
            <td class='price-list-header'> &nbsp; Ціна &nbsp; </td>
        </tr>";

    foreach ($goods as $good) {
        $class = $good['g_state'] != GOOD_STATE_SOLD ? "price-list" : "price-list-sold";

        $netPrice = ceil($good['g_total_price']/$good['g_count']);
        $diff = $good['g_price'] - $netPrice;

        echo "<tr class='$class'>
                <td class='$class' id='{$good['g_id']}'>{$good['g_id']}</td>
                <td class='$class' id='{$good['g_id']}'>
                <img src='".get_image_url($good['g_image'])."' style='width:30px;height:40px;'/></td>
                <td class='$class' id='good{$good['g_id']}'>{$good['g_title']}</td>
                <td class='$class' id='price{$good['g_id']}'>{$good['g_total_price']}</td>
                <td class='$class' id='cost{$good['g_id']}'>{$good['g_count']}</td>
                <td class='$class' id='price{$good['g_id']}'>$netPrice</td>
                <td class='$class' id='{$good['g_id']}' style='color: green;'>$diff</td>
                <td class='$class' id='cost{$good['g_id']}'>{$good['g_price']}</td>
            </tr>";
    }

    echo "</table>";
?>

<script>
$(document).ready(function() {
    $(".price-list").click(function() {
        $("input").blur();
        id = $(this).attr('id');
        if (id.substr(0, 4) == 'cost') {
            $('#main').load("add_good.php?editId=" + id.substr(4));
        } else if (id.substr(0, 4) == 'good') {
            $('#main').load("show_good.php?id=" + id.substr(4));
        } else if (id.substr(0, 5) == 'price') {
            $('#' + id).load("load_price.php?edit=1&goodId=" + id.substr(5));
        } else {
            $('#main').load("show_good.php?id=" + id);
        }
    });
    $(".price-list-sold").click(function() {
        $("input").blur();
        id = $(this).attr('id');
        if (id.substr(0, 4) == 'cost') {
            $('#main').load("add_good.php?editId=" + id.substr(4));
        } else if (id.substr(0, 4) == 'good') {
            $('#main').load("show_good.php?id=" + id.substr(4));
        } else if (id.substr(0, 5) == 'price') {
            $('#' + id).load("load_price.php?edit=1&goodId=" + id.substr(5));
        } else {
            $('#main').load("show_good.php?id=" + id);
        }
    });
});
</script>