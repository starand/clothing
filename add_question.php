<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/common/headers.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (isset($_POST['contact']) && isset($_POST['question']) && isset($_POST['goodId'])) {
        $contact = addslashes($_POST['contact']);
        $question = addslashes($_POST['question']);
        $goodId = (int)$_POST['goodId'];

        if (strlen($contact) < 3) {
            show_error("Введіть номер телефону, скайп або електронну адресу!");
        }

        if (strlen($question) < 10) {
            show_error("Текст запитання не може бути менше десяти символів!");
        }

        if (!checkNewQuestionTimeoutElapsed()) {
            show_error("Будь-ласка, зачекайте! Ви не можете задавати запитання частіше ніж раз в 5 хвилин!");
        }

        if (add_question($contact, $question, $goodId)) {
            show_message("<BR><BR><center><b style='color:blue;'>Дякуємо! Наш менеджер звяжеться з вами.</b>", 'main');
            show_message('');
        } else {
            show_error("Помилка бази даних");
        }

        die();
    }
?>
<center><h2>Яке у вас запитання?</h2>
<form action='add_question.php' method='post' target='submit_frame'>
<table cellspacing='0' cellpadding='1' style='width:700px;'>
    <tr><td>Контактні дані: &nbsp;</td><td><input type='text' name='contact' style='width:572px;'></td></tr>
    <tr><td  colspan='2'>Ваше запитання: &nbsp; </td></tr>
    <tr><td  colspan='2'><textarea name='question' class='add-good' style='width:100%;'></textarea></td></tr>
    <tr><td  colspan='2' style='text-align:right;'><input type='submit' value='Надіслати'></td></tr>
</table>
<input type='hidden' name='goodId' value='<?=(isset($_GET['goodId']) ? $_GET['goodId'] : 0 )?>'>
</form>