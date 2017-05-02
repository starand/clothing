<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/common/headers.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (!userHasPermission(PERM_EDIT_USER)) {
        show_error("Недостатньо прав для здійснення операції");
    }

    if (isset($_POST['name']) && isset($_POST['phone']) && isset($_POST['loc']))
    {
        $name = addslashes($_POST['name']);
        if (strlen($name) < 3) {
            show_error("Імя користувача повинне складися мінімум з 3 символів");
        }

        if (getClientByName($name)) {
            show_error("Користувач з таким іменем уже існує");
        }

        $phone = addslashes($_POST['phone']);
        $loc = addslashes($_POST['loc']);

        if (addClient($name, $phone, $loc)) {
            show_message("Користувач '$name' успішно доданий");
            load_page("client_list.php");
        } else {
            show_error("Помилка бази даних");
        }
        die();
    }

    $name = ""; $phone = ""; $location = "";

?>
<center><h2>Додати клієнта</h2>
<form action='add_client.php' method='post' target='submit_frame'>
<table cellspacing='1' cellpadding='2'>
    <tr>
        <td>Імя: </td>
        <td><input type='text' name='name' style='width:250px;' value='<?=$name;?>'></td>
    </tr><tr>
        <td>Телефон: </td>
        <td><input type='text' name='phone' style='width:250px;' value='<?=$phone;?>'></td>
    </tr><tr>
        <td>Адреса: </td>
        <td><input type='text' name='loc' style='width:250px;' value='<?=$location;?>'></td>
    </tr><tr>
        <td colspan='2'><center><input type='submit' value='Додати'></td>
    </tr>
</table>
</form>
