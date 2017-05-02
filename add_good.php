<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/common/headers.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (!userHasPermission(PERM_ADD_GOOD) && !userHasPermission(PERM_EDIT_GOOD)) {
        show_error("Недостатньо прав для здійснення операції");
    }

    if (isset($_POST['catId']) && isset($_POST['title']) && isset($_POST['desc']) && 
        isset($_POST['count']) && isset($_POST['price']) && isset($_POST['image']))
    {
        //var_dump($_POST);
        $cat_id = (int)$_POST['catId'];
        $cat = get_category($cat_id);
        if (!$cat) { show_error("Категорія товару не вірна"); }
        $title = addslashes($_POST['title']);
        if (strlen($title) < 10) { show_error("Назва товару повинна бути не коротше 10 символів"); }
        $desc = addslashes($_POST['desc']);
        if (strlen($desc) < 10) { show_error("Опис товару повинeн бути не коротше 30 символів"); }
        $count = (int)$_POST['count'];
        if ($count < 0 || $count > 20) { show_error("Кількість одинь повинна бути від 1 до 20"); }
        $price = (float)$_POST['price'];
        if ($price < 5 || $price > 2000) { show_error("Ціна повинна бути від 5 до 2000 грн."); }
        $image = addslashes($_POST['image']);
        if (strlen($image) < 10) { show_error("Не коректна адреса зображення"); }

        if (isset($_POST['editId'])) {
            $goodId = (int)$_POST['editId'];
            $good  = get_good($goodId);
            if (!$good) {
                show_error("Не правильно вказаний товар");
            }

            if (update_good($goodId, $cat_id, $title, $desc, $image, $count, $price)) {
                show_message("Товар успішно обновлено");
                load_page("show_good.php?id=$goodId");
                die();
            } else {
                show_error("Помилка бази даних");
            }
        } else {
            if (add_good($cat_id, $title, $desc, $image, $count, $price)) {
                show_message("Товар успішно доданий");
                load_page("show_category.php?id=$cat_id");
                die();
            } else {
                show_error("Помилка бази даних");
            }
        }

        die();
    }

    if (isset($_GET['editId'])) {
        $goodId = (int)$_GET['editId'];
        $good = get_good($goodId);
        if (!$good) {
            show_error("Товар вказано не вірно");
        }
        
        $cat = get_category($good['g_cat']);

    } else if(isset($_GET['catId'])) {
        $cat_id = (int)$_GET['catId'];
        $cat = get_category($cat_id);
        if (!$cat) {
            show_error("Категорія товару не вірна");
        }
    } else {
        show_error("Категорія товару не вказана");
    }

    $title = ''; $price = ''; $count = ''; $image = ''; $desc = '';
    if (isset($good)) {
        echo "<h2>Редагувати товар '{$good['g_title']}'</h2>";

        $title = $good['g_title'];
        $price = $good['g_price'];
        $count = $good['g_count'];
        $image = $good['g_image'];
        $desc = $good['g_desc'];
    } else {
        echo "<h2>Додати товар в категорію '{$cat['cat_desc']}'</h2>";
    }
?>

<form action='add_good.php' method='post' target='submit_frame'>
<?
    if (isset($good)) {
        echo "<input type='hidden' name='editId' value='{$good['g_id']}'>";
    }
?>
<input type='hidden' name='catId' value='<?=$cat['cat_id'];?>'>
<table cellspacing='0' cellpadding='1'>
    <tr>
        <td>Назва: </td>
        <td><input type='text' name='title' style='width:350px;' value='<?=$title;?>'></td>
        <td>&nbsp; Ціна: </td>
        <td><input type='text' name='price' style='width:50px;' value='<?=$price;?>'></td>
        <td>&nbsp; Кількість: </td>
        <td><input type='text' name='count' style='width:50px;' value='<?=$count;?>'></td>
    </tr>
    <tr>
    <tr>
        <td>Зображення: </td>
        <td colspan='3'><input type='text' name='image' style='width:100%;' value='<?=$image;?>'></td>
        <td colspan='2' align='right'>Опис товару: </td>
    </tr>
    <tr>
        <td colspan='6'><textarea name='desc' class='add-good' style='width:100%;'><?=$desc;?></textarea></td>
    </tr>
    <tr><td><input type='submit' value='<?=(isset($good) ? 'Змінити' : 'Додати');?>'></td></tr>
</table>
</form>

<div id='good_dg'>
<?
    include_once ROOT_PATH."add_good_dg.php";
?>
</div>

