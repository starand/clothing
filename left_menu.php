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
            echo "<a class='category' href='/?cat={$sub_cat['cat_id']}'>";
            echo "{$sub_cat['cat_desc']}</a>";
            echo "</td></tr>";
        }

        echo "</table></td></tr>";
    }
    echo "</table>";
?>

</td></tr><tr><td><BR><BR>
<a id='contacts'> &nbsp; Зв'яжіться з нами</a>
<?
    if (userHasPermission(PERM_ADD_GOOD)) {
        echo "<BR><BR><a id='add_category'>Додати категорію</a></td></tr><tr><td>";
    }

    if (userHasPermission(PERM_SEE_CLIENT)) {
        echo "<a id='show_clients'>Клієнти</a></td></tr><tr><td>";
    }

    if (userHasPermission(PERM_ADD_SELL)) {
        echo "<a id='show_sales'>Продажі</a></td></tr><tr><td>";
    }

    if (userHasPermission(PERM_EDIT_NETPRICE)) {
        echo "<a id='net_prices'>Ціни</a></td></tr><tr><td>";
    }

    if (userHasPermission(PERM_SEE_QUESTIONS)) {
        echo "<a id='questions'>Запитання</a></td></tr><tr><td>";
    }

    if (userHasPermission(PERM_MANAGE_FEEDBACKS)) {
        echo "<a id='feedbacks'>Відгуки</a></td></tr><tr><td>";
    }
?>


</td></tr></table>

<BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>

<script>
$(document).ready(function() {
    $("#add_category").on("click", function() {
        $("#main").load("add_category.php");
    });
    $("#net_prices").on("click", function() {
        $("#main").load("net_price_list.php");
    });
    $("#show_clients").on("click", function() {
        $("#main").load("client_list.php");
    });
    $("#show_sales").on("click", function() {
        $("#main").load("show_sales.php");
    });
    $("#contacts").click(function() {
        $("#main").load("contacts.php");
    });
    $("#questions").click(function() {
        $("#main").load("questions.php");
    });
    $("#feedbacks").click(function() {
        $("#main").load("feedbacks.php");
    });
});
</script>