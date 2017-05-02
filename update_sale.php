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

    if (isset($_GET['edit'])) {
        $clients = getClients();
        echo "<select class='sel-user' id='u$saleId' style='width:150px;'>";
        foreach ($clients as $client) {
            echo "<option value='{$client['c_id']}'>{$client['c_name']}</option>";
        }
        echo "</select>";
    } else if (isset($_GET['set'])) {
        $clientId = (float)$_GET['set'];
        $client = getClient($clientId);
        if (!$client) {
            show_error("Не вірно вказаний покупець");
        }

        if (updateClientIdForSale($saleId, $clientId)) {
            echo " {$client['c_name']}<br><span style='font-size:10px;'>{$client['c_address']}</span> &nbsp; ";
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
        clientId = $(this).find(":selected").val();
        $('#' + id).load("update_sale.php?set=" + clientId + "&id=" + id.substr(1));
    });
});
</script>
