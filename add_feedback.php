<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/common/headers.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (isset($_POST['contact']) && isset($_POST['feedback']) && isset($_POST['goodId'])) {
        $contact = addslashes($_POST['contact']);
        $feedback = addslashes($_POST['feedback']);
        $goodId = (int)$_POST['goodId'];

        if (strlen($contact) < 3) {
            show_error("Введіть ваше ім'я!");
        }

        if (strlen($feedback) < 10) {
            show_error("Текст відгуку не може бути менше десяти символів!");
        }

        if (!checkNewFeedbackTimeoutElapsed()) {
            show_error("Будь-ласка, зачекайте! Ви не можете додавати відгуки частіше ніж раз в 5 хвилин!");
        }

        if (add_feedback($contact, $feedback, $goodId)) {
            show_message("<BR><BR><center><b style='color:blue;'>Дякуємо за ваш відгук!</b>", 'main');
            show_message('');
        } else {
            show_error("Помилка бази даних");
        }

        die();
    }
?>
<center><h2>Додати відгук</h2>
<form action='add_feedback.php' method='post' target='submit_frame'>
<table cellspacing='0' cellpadding='1' style='width:700px;'>
    <tr><td>Ваше ім'я: &nbsp;</td><td><input type='text' name='contact' style='width:572px;'></td></tr>
    <tr><td  colspan='2'>Текст відгуку: &nbsp; </td></tr>
    <tr><td  colspan='2'><textarea name='feedback' class='add-good' style='width:100%;'></textarea></td></tr>
    <tr><td  colspan='2' style='text-align:right;'><input type='submit' value='Надіслати'></td></tr>
</table>
<input type='hidden' name='goodId' value='<?=(isset($_GET['goodId']) ? $_GET['goodId'] : 0 )?>'>
</form>