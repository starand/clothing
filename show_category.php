<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (!isset($_GET['id'])) {
        show_error("Категорія вказана не правильно");
    }

    $cat_id = (int)$_GET['id'];

    $cat = get_category($cat_id);
    if (!$cat) {
        show_error("Категорія вказана не правильно");
    }

    echo "<h2>{$cat['cat_desc']}</h2>";

    $goods = get_goods_by_cat($cat['cat_id']);
    show_goods($goods, 3);
   
    if (userHasPermission(PERM_ADD_GOOD)) {
        echo "<a id='add_good'>Додати товар</a>";
    }

    include_once ROOT_PATH."/common/good_scripts.inc";
?>

<script>
$(document).ready(function() {
    $("#add_good").on("click", function() {
        $("#main").load("add_good.php?catId=<?=$cat_id;?>");
    });
});
</script>