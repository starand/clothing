<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/common/headers.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (!userHasPermission(PERM_SEE_QUESTIONS)) {
        show_error("Недостатньо прав для здійснення операції");
    }

    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        if ($id == 0) {
            show_error("Не коректний номер запитання!");
        }
        if (isset($_GET['done'])) {
            $res = update_question_state($id, QUESTION_REPLIED);
        } else if (isset($_GET['delete'])) {
            $res = delete_question($id);
        }

        if (isset($res) && !$res) {
            show_error("Помилка бази даних");
        }
    }

    $questions = get_questsios();

    echo "<center><h2>Список запитань</h2>";
    echo "<table class='price-list' cellspacing='1' cellpadding='1'>";
    echo "<tr><td class='price-list-header'>#</td>";
    echo "<td class='price-list-header'> &nbsp; Запитання &nbsp; </td>";
    echo "<td class='price-list-header'> &nbsp; ІР &nbsp; </td>";
    echo "<td class='price-list-header'> &nbsp; Статус &nbsp; </td>";
    echo "</tr>";

    foreach ($questions as $quest) {
        $text = str_replace("\r\n\r\n", "<BR>", $quest['q_text']);
        $text = str_replace("\n", "<BR>", $text);
        // {$quest['q_state']}

        echo "<tr class='price-list'>
                <td class='price-list' id='{$quest['q_id']}'> {$quest['q_id']} </td>
                <td class='price-list' id='{$quest['q_id']}' style='font-weight:normal;width:400px;'>
                    <b>{$quest['q_contact']}</b>: $text
                </td>
                <td class='price-list' id='{$quest['q_id']}' style='font-size:12px;'>
                    {$quest['q_date']} <BR> {$quest['q_ip']}
                </td>
                <td class='price-list' id='{$quest['q_id']}'>
                    <img class='action' id='d{$quest['q_id']}' src='/images/done.png'>
                    <img class='action' id='r{$quest['q_id']}' src='/images/delete.png'>
                </td>
            </tr>";
    }

    if (!count($questions)) {
        echo "<tr class='price-list'>
                <td colspan='4' class='price-list' style='width:500px;'>Немає запитань</td>
             </tr>";
    }

    echo "</table>";
?>

<script>
$(document).ready(function() {
    $(".action").click(function() {
        id = $(this).attr('id');

        if (id.substr(0, 1) == 'd') {
            $('#main').load("questions.php?done=1&id=" + id.substr(1));
        } else if (id.substr(0, 1) == 'r') {
            $('#main').load("questions.php?delete=1&id=" + id.substr(1));
        }
    });
});
</script>