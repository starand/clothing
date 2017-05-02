<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/db/db.php";
?>

<table cellpadding='3' cellspacing='2'><tr><td>

<?
    $cats = get_categories(0);
    echo "<table class='left-menu'>";
    foreach($cats as $cat) {
        echo "<tr><td class='cat-item'>{$cat['cat_desc']}</td></tr>";

        $sub_cats = get_categories($cat['cat_id']);
        echo "<tr><td><table class='left-sub-menu'>";

        foreach($sub_cats as $sub_cat) {
            echo "<tr><td id='catItem' class='cat-item'> &nbsp; ";
            echo "<a id='{$sub_cat['cat_id']}'>";
            echo "{$sub_cat['cat_desc']}</a>";
            echo "</td></tr>";
        }

        echo "</table></td></tr>";
    }
    echo "</table>";
?>

</td></tr><tr><td><BR>
<?
    if (userHasPermission(PERM_ADD_GOOD)) {
        echo "<a id='add_category'>Додати категорію</a>";
    }
?>
</td></tr><tr><td>
<?
    if (userHasPermission(PERM_SEE_CLIENT)) {
        echo "<BR><a id='show_clients'>Список клієнтів</a>";
    }
?>
</td></tr><tr><td>
<?
    if (userHasPermission(PERM_EDIT_USER)) {
        echo "<a id='add_client'>Додати клієнта</a>";
    }
?>
</td></tr><tr><td>
<?
    if (userHasPermission(PERM_ADD_SELL)) {
        echo "<BR><a id='show_sales'>Продажі</a>";
    }
?>
</td></tr><tr><td>
<?
    if (userHasPermission(PERM_EDIT_NETPRICE)) {
        echo "<a id='net_prices'>Ціни</a>";
    }
?>


</td></tr></table>

<BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>

<script>
$(document).ready(function() {
    $("#catItem a").click(function() {
        catId = $(this).attr('id');
        $("#main").load("show_category.php?id=" + catId);
    });
    $("#add_category").on("click", function() {
        $("#main").load("add_category.php");
    });
    $("#net_prices").on("click", function() {
        $("#main").load("net_price_list.php");
    });
    $("#add_client").on("click", function() {
        $("#main").load("add_client.php");
    });
    $("#show_clients").on("click", function() {
        $("#main").load("client_list.php");
    });
    $("#show_sales").on("click", function() {
        $("#main").load("show_sales.php");
    });
});
</script>