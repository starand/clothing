<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/common/headers.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (!userHasPermission(PERM_ADD_GOOD) && !userHasPermission(PERM_EDIT_GOOD)) {
        show_error("Недостатньо прав для здійснення операції");
    }

    if (isset($_POST['goodId']) && isset($_POST['field1']) && isset($_POST['field2']) && 
        isset($_POST['field3']) && isset($_POST['field4']) && isset($_POST['field5']) && 
        isset($_POST['field6']) && isset($_POST['field7']) && isset($_POST['field8']))
    {
        $goodId = (int)$_POST['goodId'];
        $good = get_good($goodId);
        if (!$good) {
            show_error("Такий товар не існує");
        }

        $f1 = addslashes($_POST['field1']); $f2 = addslashes($_POST['field2']);
        $f3 = addslashes($_POST['field3']); $f4 = addslashes($_POST['field4']);
        $f5 = addslashes($_POST['field5']); $f6 = addslashes($_POST['field6']);
        $f7 = addslashes($_POST['field7']); $f8 = addslashes($_POST['field8']);
        if ($f1 === "") {
            show_error("Жодне поле не заповнене");
        }

        $data = replace_ex("$f1|$f2|$f3|$f4|$f5|$f6|$f7|$f8", "||", "|");

        if (addDimGridRow($goodId, $data)) {
            load_page("add_good_dg_record.php?goodId=$goodId", "good_add_dg");
        } else {
            show_error("Помилка бази даних");
        }
    }

    if (!isset($_GET['goodId'])) {
        die();
    }

    $goodId = (int)$_GET['goodId'];
    $records = getDimGridByGood($goodId);
    
    echo "<div class='add-dg-record'>";

    if (!$records) {
        ?>
        &nbsp; Введіть назви колонок розмірної таблиці:<br><br>

        <form action='add_good_dg_record.php' method='post' target='submit_frame'>
        <input type='hidden' name='goodId' value='<?=$goodId;?>'>
        <table cellspacing='0' cellpadding='1'><tr>
        <?
            for ($i = 1; $i <= 8; ++$i) {
                echo "<td> &nbsp; #$i &nbsp; </td><td><input type='text' name='field$i'></td>";
                if ($i % 3 == 0) {
                    echo "</tr><tr>";
                }
            }
        ?>
        </tr>
        <tr><td colspan='4'><center><input type='submit' value='Додати'></td></tr>
        </table>
        </form>

        <div id='dim-grid'><?
    } else {
        include_once ROOT_PATH."show_good_dg.php";
        ?></div>
        <br>&nbsp; Введіть дані розміру:<br>
        <form action='add_good_dg_record.php' method='post' target='submit_frame'>
        <input type='hidden' name='goodId' value='<?=$goodId;?>'>
        <table cellspacing='0' cellpadding='1'>
        <?
            echo "<tr>";
            $first = true;
            $dg_cols = parseDGString($records[0]['dg_data']);
            foreach ($dg_cols as $col) {
                echo "<td class='dg-".($first ? "header" : "row")."'>$col</td>";
            }
            $first = false;
            echo "</tr>";

            $i = 1;
            echo "<tr>";
            foreach ($dg_cols as $col) {
                echo "<td><input type='text' name='field$i' class='dg-rec'></td>";
                $i++;
            }
            echo "</tr>";
        ?>
        </tr>
        <tr><td colspan='<?=($i-1);?>'><center><input type='submit' value='Додати'></td></tr>
        </table>
        <?
            while($i != 9) {
                echo "<input type='hidden' name='field$i'>";
                $i++;
            }
        ?>
        </form>

        <?
    }
?>

<div>