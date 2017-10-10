<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/db/db.php";
?>
<table class='top' cellspacing='0' cellpadding='0'>
<tr>
    <td class ='top-logo' id='top_logo'>
        <a class='title'>
        <div class='top-logo'>BoyClothing</div>
        </a>
    </td>
    <td class='top-title'>Одяг та взуття на хлопчика. Низькі ціни!</td>
<?
    if (userHasPermission(PERM_VIEW_ORDERS)) {
        $count = get_undone_orders_count();
        $style = $count ? "color: yellow;" : "";
        echo "<td class='top-order' style='$style'>Покупки ( $count )</td>";
    }
?>    
    <td class='top-basket'>Корзина ( <?=count($_SESSION['cl_basket']);?> )</td>
    <td class='top-profile'>
<?
    
    if ($user = checkUser()) {
        echo "<a class='top-profile'><div class='top-profile' id='logout'>Вихід</div></a>";
    } else {
        echo "<a class='top-profile'><div class='top-profile' id='login'>Вхід</div></a>";
    }
?>
    </td>
</tr>
</table>

<script>
$(document).ready(function() {
    $("#top_logo").on("click", function() {
        $("#main").load("main.php");
    });
    $("#login").on("click", function() {
        $("#main").load("login.php");
    });
    $("#logout").on("click", function() {
        $("#main").load("login.php?logout=");
    });

    $(".top-basket").on("click", function() {
        $("#main").load("basket.php");
    });

    $(".top-order").on("click", function() {
        $("#main").load("orders.php");
    });
});
</script>
