<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/common/headers.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (!userHasPermission(PERM_ADD_SELL) || !userHasPermission(PERM_EDIT_GOOD)) {
        show_error("Недостатньо прав для здійснення операції");
    }

    if (!isset($_GET['goodId'])) {
        show_error("Не вірно вказаний товар");
    }
    if (!isset($_GET['dim'])) {
        show_error("Не вказаний розмір товару");
    }

    $goodId = (int)$_GET['goodId'];
    $good = get_good($goodId);
    if (!$good) {
        show_error("Не вірно вказаний товар");
    }

    $dim = (int)$_GET['dim'];
    $dimRow = getDimRow($dim);
    if (!$dimRow) {
        show_error("Вказаний невірний розмір товару");
    }

    if (isset($_GET['edit'])) {
        echo "<input type='text' value='{$good['g_price']}' class='sell-price' id='sellPrice'>";
    } else if(isset($_GET['set'])) {
        $price = (float)$_GET['set'];

        $earned = $price - $good['g_total_price']/$good['g_count'];
        add_sale($goodId, $dim, $earned);

        updateDGRecordState($dim, DG_SOLD);
        $dimRow = getDimRow($dim);
        echo getDBRowState($dimRow['dg_state']);

        soldGoodIfAllDimsAreSold($goodId);
    }
    else {
        echo getDBRowState($dimRow['dg_state']);
    }
?>

<script>
$(document).ready(function() {
    $("#sellPrice")
    .click(function(event) {
        event.stopImmediatePropagation();
    })
    .keyup(function(e){
        if(e.keyCode == 13) {
            id = <?=$dim;?>;
            $('#dim' + id).load("sell_good.php?set=" + $(this).val() + 
                "&dim=" + id + "&goodId=<?=$goodId;?>");
        }
    });
});
</script>