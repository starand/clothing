<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/common/headers.php";
    include_once ROOT_PATH."/common/functions.php";
    include_once ROOT_PATH."/db/db.php";

    add_visit($_SERVER['REMOTE_ADDR']);
?>
<script src="common/jquery.js"></script>

<script>
function load_page(page, target) {
    $("#" + target).load(page);
}
</script>

<?
    if (isMobile()) {
        //include_once "mobile_index.php";
        //die();
    }
?>

<center>

<!-- Top table -->
<table cellspacing='0' cellpadding='0' width='100%'>
<tr><td class='top-title'><center>
    <div id='top' class='top'><? include_once "top.php"; ?></div>
</td></tr>
</table>

<!-- Body -->
<table cellspacing='0' cellpadding='0' class='index'>
<tr>
    <td class='left-menu'><div id='left_menu'><? include_once "left_menu.php"; ?></div></td>
    <td class='main'>
        <div style='padding:6px; background:white;'>
            Наша група - <a href='https://vk.com/boys.clothing' target='_blank'>https://vk.com/boys.clothing</a>
        </div>
        <table cellspacing='0' cellpadding='0' style='width:100%;'>
            <tr><td><div id='main_error' class='main-error'> &nbsp; </div></td></tr>
            <tr><td><div id='main' class='main'>
            <?
                if (isset($_GET['id'])) {
                    include_once "show_good.php";
                } else {
                    include_once "main.php";
                }
            ?>
            </div></td></tr>
        </table>
    </td>
</tr>
</table>

<iframe name='submit_frame' src='' style='width:0%;height:0%;'></iframe>
