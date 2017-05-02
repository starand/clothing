<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/common/headers.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (!userHasPermission(PERM_EDIT_GOOD)) {
        show_error("Недостатньо прав для здійснення операції");
    }

    if (!isset($_GET['id'])) {
        show_error("Не вірно вказаний номер розміру");
    }

    $dimId = (int)$_GET['id'];
    $dimRow = getDimRow($dimId);
    if (!$dimRow) {
        show_error("Розмір не існує");
    }

    if (isset($_GET['edit'])) {
        echo "Змінити розмірний ряд: <br><input type='text' value='{$dimRow['dg_data']}' class='update-dg' id='$dimId' style='width: 350px;'>";
    } else if (isset($_GET['set'])) {
        $data = addslashes($_GET['set']);
        if(strlen($data) < 3) {
            show_error("Не вірно вказано дані розміру");
        }

        if (updateDGRecord($dimId, $data)) {
            $goodId = $dimRow['dg_good'];
            load_page("show_good_dg.php?goodId=$goodId", "dim-grid");
        } else {
            show_error("Помилка бази даних");
        }

        die();
    }
?>

<script>
$(document).ready(function() {
    $(".update-dg")
    .click(function(event) {
        event.stopImmediatePropagation();
    })
    .keyup(function(e){
        if(e.keyCode == 13) {
            id = <?=$dimId;?>;
            val = encodeURIComponent($(this).val().trim());
            //alert("update_dim_grid.php?set=" + $(this).val() + "&id=" + id);
            $('#update-dim-grid').load("update_dim_grid.php?set=" + val + "&id=" + id);
            $(this).val("");
        }
    });
});
</script>
