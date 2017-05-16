<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (!isset($_GET['id'])) {
        show_error("Вибрано не вірний товар");
    }

    $goodId = (int)$_GET['id'];

    $good = get_good($goodId);
    if (!$good) {
        show_error("Вибрано не вірний товар");
    }

    incrementGoodViewCounter($goodId);

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
                echo good_control_panel();
            }
        echo "</div>";

        echo "<div class='good-desc-title'>{$good['g_title']}</div>";

        if ($good['g_state'] == GOOD_STATE_WAIT) {
            echo "<B style='color: green;'>Очікуємо на доставку!</B><br><br>";
        }
        if ($good['g_state'] == GOOD_STATE_SOLD) {
            echo "<B style='color: red;'>Товар продано!</B><br><br>";
        }

        // price
        echo "Ціна: {$good['g_price']}<br>";
        // show description
        echo "<p style='text-align: justify;'>$good_desc</p>";

        echo "<div id='dim-grid'><center>";
        include_once ROOT_PATH."show_good_dg.php";
        echo "</div>";
    echo "</div>";
    
    // show images
    // echo "<pre>"; var_dump($images); echo "</pre>";
    echo "<center>";
    foreach($images as $img) {
        echo "<div class='good-image'><img src='{$img}' style='width:680px;'></div>"; // 
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
});
</script>
