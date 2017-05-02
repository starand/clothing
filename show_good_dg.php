<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (isset($_GET['goodId'])) {
        $goodId = (int)$_GET['goodId'];
    }

    if (!isset($goodId) || $goodId == 0) {
        die();
    }

    $first = true;
    $records = getDimGridByGood($goodId);
    echo "<center><b>Розмірна сітка</b>";
    echo "<table cellspacing='1' cellpadding='1' class='dg'>";
    foreach ($records as $rec) {
        echo "<tr>";

        $dg_cols = parseDGString($rec['dg_data']);
        $cellId = userHasPermission(PERM_EDIT_GOOD) ? "e{$rec['dg_id']}" : "";

        foreach ($dg_cols as $col) {
            echo "<td id='$cellId' class='dg-".($first ? "header" : "row").($rec['dg_state'] == DG_SOLD ? "-sold" : "")."'   >";
            echo " &nbsp; $col </td>";
        }

        if ($first) {
            echo "<td class='dg-header' id='$cellId'>Статус</td>";
        } else if (userHasPermission(PERM_ADD_SELL) && $rec['dg_state'] == DG_PRESENT) {
            echo "<td class='dg-row' id='dim{$rec['dg_id']}'><a> &nbsp; Продати &nbsp; </a></td>";
        } else if ($rec['dg_state'] == DG_SOLD) {
            if (userHasPermission(PERM_ADD_SELL)) {
                echo "<td class='dg-row-sold' id='$cellId'> &nbsp; ".getSellerByDim($rec['dg_id'])." &nbsp; </td>";
            } else {
                echo "<td class='dg-row-sold' id='$cellId'> &nbsp; ".getDBRowState($rec['dg_state'])." &nbsp; </td>";
            }
        } else {
            if (inBasket($goodId, $rec['dg_id'])) {
                echo "<td class='dg-row-basket' id=''> &nbsp; В корзині</span> </td>";
            } else {
                echo "<td class='dg-row'> &nbsp;";
                echo "<input type='button' id='$goodId&dg_id={$rec['dg_id']}' class='add-basket' value='Додати в корзину' />";
                echo " &nbsp; </td>";
            }
        }

        $first = false;
        echo "</td>";
    }
    echo "</table><div id='update-dim-grid'></div>";
?>

<script>
$(document).ready(function() {
    $(".dg-row").click(function(event) {
        //$("input").blur();
        id = $(this).attr('id');

        if (id.substr(0, 3) == 'dim') {
            $('#' + id).load("sell_good.php?goodId=<?=$goodId;?>&edit=1&dim=" + id.substr(3));
        } else if (id.substr(0, 1) == 'e') {
            $('#update-dim-grid').load("update_dim_grid.php?edit=1&id=" + id.substr(1));
        }
    });

    $(".dg-header").click(function(event) {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'e') {
            $('#update-dim-grid').load("update_dim_grid.php?edit=1&id=" + id.substr(1));
        }
    });
    $(".dg-row-sold").click(function(event) {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'e') {
            $('#update-dim-grid').load("update_dim_grid.php?edit=1&id=" + id.substr(1));
        }
    });

    $(".add-basket").on("click", function() {
        id = $(this).attr('id');
        $("#main").load("basket.php?id=" + id);
    });

    $(".dg-row-basket").on("click", function() {
        id = $(this).attr('id');
        $("#main").load("basket.php?id=" + id);
    });
    
});
</script>
