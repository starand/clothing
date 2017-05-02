<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/common/headers.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (!userHasPermission(PERM_EDIT_NETPRICE) || !userHasPermission(PERM_EDIT_GOOD)) {
        show_error("Недостатньо прав для здійснення операції");
    }

    if (!isset($_GET['goodId'])) {
        show_error("Не вірно вказаний товар");
    }

    $goodId = (int)$_GET['goodId'];
    $good = get_good($goodId);
    if (!$good) {
        show_error("Товар не знайдено");
    }

    if (isset($_GET['edit'])) {
        echo "<input type='text' value='{$good['g_total_price']}' class='edit-price' id='editPrice'>";
    } else if (isset($_GET['set'])) {
        $price = (float)$_GET['set'];
        updateGoodTotalPrice($goodId, $price);
        $good = get_good($goodId);
        echo $good['g_total_price'];
    } else {
        echo $good['g_total_price'];
    } 
?>

<script>
$(document).ready(function() {
    $("#editPrice")
    .click(function(event) {
        event.stopImmediatePropagation();
    })
    .keyup(function(e){
        if(e.keyCode == 13) {
            id = <?=$goodId;?>;
            $('#price' + id).load("load_price.php?set=" + $(this).val() + "&goodId=" + id);
        }
    });
});
</script>