<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/common/headers.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (!userHasPermission(PERM_MANAGE_FEEDBACKS)) {
        show_error("Недостатньо прав для здійснення операції");
    }

    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        if ($id == 0) {
            show_error("Не коректний номер відгука!");
        }
        if (isset($_GET['done'])) {
            $res = update_feedback_state($id, FEEDBACK_APPROVED);
        } else if (isset($_GET['delete'])) {
            $res = delete_feedback($id);
        }

        if (isset($res) && !$res) {
            show_error("Помилка бази даних");
        }
    }

    $feedbacks = get_feedbacks(FEEDBACK_NEW);

    echo "<center><h2>Список відгуків</h2>";
    echo "<table class='price-list' cellspacing='1' cellpadding='1'>";
    echo "<tr><td class='price-list-header'>#</td>";
    echo "<td class='price-list-header'> &nbsp; Текст відгуку &nbsp; </td>";
    echo "<td class='price-list-header'> &nbsp; ІР &nbsp; </td>";
    echo "<td class='price-list-header'> &nbsp; Статус &nbsp; </td>";
    echo "</tr>";

    foreach ($feedbacks as $fb) {
        $text = str_replace("\r\n\r\n", "<BR>", $fb['f_text']);
        $text = str_replace("\n", "<BR>", $text);
        // {$fb['f_state']}

        echo "<tr class='price-list'>
                <td class='price-list' id='{$fb['f_id']}'> {$fb['f_id']} </td>
                <td class='price-list' id='{$fb['f_id']}' style='font-weight:normal;width:400px;'>
                    <b>{$fb['f_contact']}</b>: $text
                </td>
                <td class='price-list' id='{$fb['f_id']}' style='font-size:12px;'>
                    {$fb['f_date']} <BR> {$fb['f_ip']}
                </td>
                <td class='price-list' id='{$fb['f_id']}'>
                    <img class='action' id='d{$fb['f_id']}' src='/images/done.png'>
                    <img class='action' id='r{$fb['f_id']}' src='/images/delete.png'>
                </td>
            </tr>";
    }

    if (!count($feedbacks)) {
        echo "<tr class='price-list'>
                <td colspan='4' class='price-list' style='width:500px;'>Немає незатверджених відгуків</td>
             </tr>";
    }

    echo "</table>";
?>

<script>
$(document).ready(function() {
    $(".action").click(function() {
        id = $(this).attr('id');

        if (id.substr(0, 1) == 'd') {
            $('#main').load("feedbacks.php?done=1&id=" + id.substr(1));
        } else if (id.substr(0, 1) == 'r') {
            $('#main').load("feedbacks.php?delete=1&id=" + id.substr(1));
        }
    });
});
</script>