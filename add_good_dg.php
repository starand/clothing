<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/common/headers.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (!userHasPermission(PERM_ADD_GOOD) && !userHasPermission(PERM_EDIT_GOOD)) {
        show_error("Недостатньо прав для здійснення операції");
    }

    if (!isset($good)) {
        die();
    }

    $goodId = $good['g_id'];
    $dg = getDimGridByGood($goodId);

    echo "<div id='good_add_dg'>";
    if (!$dg) {
        echo "Розмірну сітку ще не додано - <a id='add_dg_record'> Додати </a>";
    } else {
        echo "<div id='dim-grid'>";
        include_once ROOT_PATH."show_good_dg.php";
        echo "</div><br>";
        echo "<a id='add_dg_record'> Змінити розмірну сітку </a>";
    }
    
    echo "</div>";
?>

<script>
$(document).ready(function() {
    $("#add_dg_record").on("click", function() {
        goodId = $(this).attr('id');
        $("#good_add_dg").load("add_good_dg_record.php?goodId=" + <?=$goodId;?>);
    });
});
</script>
