<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/common/headers.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (!userHasPermission(PERM_ADD_CATEGORY)) {
        show_error("Недостатньо прав для здійснення операції");
    }

    if (isset($_POST['parent']) && isset($_POST['desc'])) {
        $parent = (int)$_POST['parent'];
        $desc = addslashes($_POST['desc']);

        $parent_cat = get_category($parent);
        if (!$parent_cat || $parent_cat['cat_parent'] != 0) {
            show_error("Не правильно вказана батьківська категорія");
        }

        if (strlen($desc) < 10) {
            show_error("Назва категорії повинна бути не коротше 10 символів");
        }

        if (add_category($desc, $parent)) {
            show_message("Категорія успішно додана");
            load_page("main.php");
        } else {
            show_error("Помилка бази даних");
        }

        die();
    }

    $parent_cats = get_categories(0);
?>
<center><h2>Додати категорію товарів</h2>
<form action='add_category.php' method='post' target='submit_frame'>
<table cellspacing='0' cellpadding='1'>
    <tr><td>Назва категорії: &nbsp;</td><td><input type='text' name='desc'></td></tr>
    <tr><td>У категорії: &nbsp; </td><td>
        <select name='parent'>
        <?
            foreach ($parent_cats as $cat) {
                echo "<option value='{$cat['cat_id']}'>{$cat['cat_desc']}</option>";
            }
        ?>
        </select>
    </td></tr>
    <tr><td></td><td><input type='submit' value='Додати'></td></tr>
</table>
</form>