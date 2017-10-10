<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/common/headers.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (!userHasPermission(PERM_ADD_SELL)) {
        show_error("Недостатньо прав для здійснення операції");
    }

    $condition = "s_earn > -1";
    if (isset($_GET['client'])) {
        $condition .= " AND s_client=".(int)$_GET['client'];
    }
    if (isset($_GET['good'])) {
        $condition .= " AND s_good=".(int)$_GET['good'];
    }
    if (isset($_GET['time'])) {
        $condition .= " AND DATE(s_date)=DATE('".addslashes($_GET['time'])."')";
    }

    $sales = get_sales($condition); // get all goods;

    $lastMonthCondition = "month(s_date)=month(now())";
    if (strlen($condition) != 0) {
        $lastMonthCondition .= " AND $condition";
    }

    echo "<center><h2>Список продаж</h2>";

    echo "<div style='font-size:12px;'>
        Дохід: <span style='color:green;'>".getSalesTotal($condition)."</span>. &nbsp;
        Кількість продаж: ".getSalesCountTotal($condition).".
        За продажу: ".getAvgEarnPerSale($condition)." &nbsp;
        За місяць: <span style='color:green;'>".getSalesTotal($lastMonthCondition)."</span>.
    </div>";

    echo "<table class='price-list' cellspacing='1' cellpadding='1'>
            <tr><td class='price-list-header'>#</td>
            <td class='price-list-header'> &nbsp; Клієнт &nbsp; </td>
            <td class='price-list-header'>  </td>
            <td class='price-list-header'> Вид </td>
            <td class='price-list-header'> &nbsp; Товар &nbsp; </td>
            <td class='price-list-header'>  </td>
            <td class='price-list-header'> &nbsp; Розмір &nbsp; </td>
            <td class='price-list-header'> &nbsp; Дата &nbsp; </td>
            <td class='price-list-header'> &nbsp; Сума &nbsp; </td></tr>";

    foreach ($sales as $sale) {
        $client = getClient($sale['s_client']);
        echo "<tr class='price-list'>";
            echo "<td class='price-list' id='{$sale['s_id']}'> &nbsp; {$sale['s_id']}</td>";
            echo "<td class='price-list' id='u{$sale['s_id']}'> &nbsp; ";
            if ($client) {
                echo "{$client['c_name']} &nbsp; <br><span style='font-size:10px;'>{$client['c_address']}</span>";
            } else {
                echo "UNKNOWN";
            }
            echo " </td>";
            echo "<td><img src='$shift/themes/light/filter.jpeg' style='width:20px;' id='{$sale['s_client']}' class='client-filter'></td>";
            echo "<td class='price-list' id='g{$sale['s_good']}'>
            <img src='".get_image_url($sale['g_image'])."' class='good-list'></td>";
            echo "<td class='price-list' id='g{$sale['s_good']}'>{$sale['g_title']}</td>";
            echo "<td><img src='$shift/themes/light/filter.jpeg' style='width:20px;' id='{$sale['s_good']}' class='good-filter'></td>";
            $text = parseDGString($sale['dg_data'])[0];
            echo "<td class='price-list' id='d{$sale['s_id']}'>{$text}</td>";
            echo "<td class='price-list' id='{$sale['s_id']}'><div id='{$sale['s_date']}' class='time-filter'>&nbsp; {$sale['s_date']}</div></td>";
            echo "<td class='price-list' id='{$sale['s_id']}'> &nbsp; ".floor($sale['s_earn'])." &nbsp;</td>";
        echo "</tr>";
    }

    echo "</table>";
?>

<script>
$(document).ready(function() {
    $(".price-list").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'g') {
            $('#main').load("show_good.php?id=" + id.substr(1));
        } else if (id.substr(0, 1) == 'u') {
            $('#' + id).load("update_sale.php?edit=&id=" + id.substr(1));
        } else if (id.substr(0, 1) == 'd') {
            $('#' + id).load("update_dim.php?edit=&id=" + id.substr(1));
        }
    });

    $(".client-filter").click(function() {
        id = $(this).attr('id');
        $('#main').load("show_sales.php?client=" + id);
    });

    $(".good-filter").click(function() {
        id = $(this).attr('id');
        $('#main').load("show_sales.php?good=" + id);
    });

    $(".time-filter").click(function() {
        id = encodeURIComponent($(this).attr('id').trim());
        $('#main').load("show_sales.php?time=" + id);
    });
});
</script>