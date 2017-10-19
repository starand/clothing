<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/common/headers.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (!userHasPermission(PERM_ADD_SELL) || !userHasPermission(PERM_EDIT_GOOD)) {
        show_error("Недостатньо прав для здійснення операції");
    }

    if (!isset($_GET['saleId'])) {
        show_error("Не вірно вказано номер продажі");
    }

    $saleId = (int)$_GET['saleId'];
    $sale = get_sale($saleId);
    if (!$sale) {
        show_error("Не вірно вказано номер продажі");
    }

    if (updateDGRecordState($sale['s_dim'], DG_PRESENT) &&
        unsoldGood($sale['s_good']) && remove_sale($saleId)) {
            echo "<b style='color:green;'> X </b>";
    } else {
        echo "<b style='color:red;'>ERROR!</b>";
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