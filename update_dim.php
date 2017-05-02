<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/common/headers.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (!userHasPermission(PERM_ADD_SELL)) {
        show_error("Недостатньо прав для здійснення операції");
    }

    if (!isset($_GET['id'])) {
        show_error("Не вірно вказаний номер продажі");
    }

    $saleId = (int)$_GET['id'];
    $sale = get_sale($saleId);
    if (!$sale) {
        show_error("Не вірний номер продажі");
    }

    $goodId = $sale['s_good'];
    $dims = getNotSoldDimGridByGood($goodId);
    //array_shift($dims);

    if (isset($_GET['edit'])) {
        echo "<select class='sel-user' id='d$saleId' style='width:70px;'>";
        $first = TRUE;
        foreach ($dims as $dim) {
            $dimArray = parseDGString($dim['dg_data']);
            echo "<option value='{$dim['dg_id']}'>{$dimArray[0]}</option>";
        }
        echo "</select>";
    } else if (isset($_GET['set'])) {
        $dimId = (int)$_GET['set'];
        $dim = getDimRow($dimId);
        if (!$dim) {
            show_error("Не вірно вказаний розмір");
        }

        if (updateDimIdForSale($saleId, $dimId)) {
            $text = parseDGString($dim['dg_data'])[0];
            echo "&nbsp; {$text} &nbsp; ";
        } else {
            show_error("Помилка бази даних");
        }
    }
?>

<script>
$(document).ready(function() {
    $(".sel-user")
    .click(function(event) {
        event.stopImmediatePropagation();  
    }).change(function(e){
        id = $(this).attr('id');
        dimId = $(this).find(":selected").val();
        $('#' + id).load("update_dim.php?set=" + dimId + "&id=" + id.substr(1));
    });
});
</script>
