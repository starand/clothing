<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (isset($_GET['copyId'])) {
        $copyFromId = (int)$_GET['copyId'];
        $copyFromGood = get_good($copyFromId);
        if (!$copyFromGood) {
            show_error("Вибрано не вірний товар для копіювання");
            die();
        }

        $newGoodId = copyGood($copyFromId);
        if (!$newGoodId) {
            die();
        }

        $_GET['id'] = $newGoodId;
    }

    if (!isset($_GET['id'])) {
        show_error("Вибрано не вірний товар");
    }

    $goodId = (int)$_GET['id'];

    $good = get_good($goodId);
    if (!$good) {
        show_error("Вибрано не вірний товар");
    }

    if (!userHasPermission(PERM_EDIT_GOOD)) {
        incrementGoodViewCounter($goodId);
    }

    echo "<div style='text-align: right; font-size: 12px;'>";
        $prev = getPrevGood($goodId, $good['g_cat']);
        if ($prev) {
            echo "<a class='prev-good' id='{$prev['g_id']}'> &lt;&lt; Попередній &nbsp;</a>";
        } else {
            echo "&lt; Попередній &nbsp;";
        }
        $next = getNextGood($goodId, $good['g_cat']);
        if ($next) {
            echo "<a class='next-good' id='{$next['g_id']}'> Наступний &gt;&gt; </a>";
        } else {
            echo " Наступний &gt;   ";
        }
    echo "</div>";

    $good_desc = stripslashes(get_description($good['g_desc']));
    // make links
    $good_desc = preg_replace("/(?<!http:\/\/)www\./","http://www.", $good_desc);
    $good_desc = preg_replace( "/((http|ftp)+(s)?:\/\/[^<>\s]+)/i",
                            "<a href=\"\\0\" target=\"_blank\">\\0</a>", $good_desc);

    $images = get_images($good['g_desc'], $good['g_image']);

    echo "<div class='good-desc'>";
        echo "<div align='right' style='width:100%;'>";
            if (userHasPermission(PERM_EDIT_GOOD)) {
                echo good_control_panel($good);
            }
        echo "</div>";

        echo "<div class='good-desc-title'>{$good['g_title']}</div>";

        if ($good['g_state'] == GOOD_STATE_WAIT) {
            echo "<B style='color: green;'>Очікуємо на доставку!</B><br><br>";
        }
        if ($good['g_state'] == GOOD_STATE_SOLD) {
            echo "<B style='color: red;'>Товар продано!</B><br><br>";
        }

        // price & question
        echo "<BR><table style='width:100%;font-size:12px;'>
                <tr>
                    <td>Ціна: {$good['g_price']}</td>
                    <td style='text-align:right;'>
                        <input id='question' type='button' value='У вас є запитання?'>
                        <input id='feedback' type='button' value='Написати відгук'>
                    </td>
                </tr>
              </table>
              <p style='text-align: justify;'>$good_desc</p>";

        echo "<div id='dim-grid'><center>";
        include_once ROOT_PATH."show_good_dg.php";
        echo "</div>";
    echo "</div>";

    $feedbacks = get_feedbacks(FEEDBACK_APPROVED, $goodId);
    if (count($feedbacks)) {
        echo "<div style='margin:20px;font-size:14px;'>Відкуги:
            <table class='price-list' cellspacing='1' cellpadding='1'>";
        foreach ($feedbacks as $fb) {
            echo "<tr>
                    <td style='text-align:right;font-size:14px; font-weight: bold'>
                        &nbsp; {$fb['f_contact']}: &nbsp; </td>
                    <td style='font-size:14px; font-style:italic; width: 700px;'>
                        &nbsp; {$fb['f_text']} &nbsp; </td>
                </tr>";
        }
        echo "</table></div>";
    }
    
    // show images
    // echo "<pre>"; var_dump($images); echo "</pre>";
    echo "<center>";
    foreach($images as $img) {
        echo "<div class='good-image'><img src='".get_image_url($img)."' style='width:680px;'></div>";
    }

    include_once ROOT_PATH."/common/good_scripts.inc";   
?>

<script>
$(document).ready(function() {
    $(".prev-good").on("click", function() {
        id = $(this).attr('id');
        $("#main").load("show_good.php?id=" + id);
    });

    $(".next-good").on("click", function() {
        id = $(this).attr('id');
        $("#main").load("show_good.php?id=" + id);
    });

    $("#question").on("click", function() {
        $("#main").load("add_question.php?goodId=<?=$goodId;?>");
    });
    $("#feedback").on("click", function() {
        $("#main").load("add_feedback.php?goodId=<?=$goodId;?>");
    });
});
</script>
