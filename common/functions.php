<?

#---------------------------------------------------------------------------------------------------
## show error
function show_error($msg, $target = 'main_error') {
    //echo $msg;
    $msg = addslashes("<span class='error-msg'>$msg</span>");
    echo "<script>parent.document.getElementById('$target').innerHTML = '$msg'</script>";
    die();
}

#---------------------------------------------------------------------------------------------------
## clear error
function clear_error($target = 'main_error') {
    echo "<script>parent.document.getElementById('$target').innerHTML = ' &nbsp; '</script>";
}

#---------------------------------------------------------------------------------------------------
## show message
function show_message($msg, $target = 'main_error') {
    $msg = addslashes("<span class='green-msg'>$msg</span>");
    echo "<script>parent.document.getElementById('$target').innerHTML = '$msg'</script>";
    //echo $msg;
}

#---------------------------------------------------------------------------------------------------
## load page
function load_page($page, $target='main') {
    echo "<script>parent.load_page('$page', '$target');</script>";
}

#---------------------------------------------------------------------------------------------------
## check whether site is loaded by mobile device
function isMobile() {
    global $_SERVER;
    $useragent=$_SERVER['HTTP_USER_AGENT'];

    return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent,0,4));
}

#---------------------------------------------------------------------------------------------------
## GOODS
#---------------------------------------------------------------------------------------------------
# show goods tale
function show_goods($goods, $cols) {
    $counter = 0;
    echo "<TABLE  id='goods' cellpadding='5'><tr>";
    
    foreach($goods as $good) {
        $class = "good-item";
        switch($good['g_state']) {
            case GOOD_STATE_SOLD: $class = "good-item-sold"; break;
            case GOOD_STATE_WAIT: $class = "good-item-wait"; break;
        }
        echo "<td class='$class' id='{$good['g_id']}'>";
            switch($good['g_state']) {
                case GOOD_STATE_SOLD: echo "<div class='sold'>Продано</div>"; break;
                case GOOD_STATE_WAIT: echo "<div class='sold'>Очікуємо доставку</div>"; break;
            }
            echo "<div style='position:relative;'>";
            if (userHasPermission(PERM_EDIT_GOOD)) {
                echo "<div style='text-align: right;'>";
                    if ($good['g_state'] == GOOD_STATE_WAIT) {
                        echo "<span align='right' style='width:100%;' id='{$good['g_id']}' class='arrive'> Прибув &nbsp; </span>";
                    }
                    echo "<span align='right' style='width:100%;' id='{$good['g_id']}' class='edit'> Змінити </span>";
                echo "</div>";
            }

            echo "<div class='good-title'>{$good['g_title']} - {$good['g_price']} грн.</div>";
            echo "<img src='{$good['g_image']}' style='width:250px;'><br>";

        echo "</div></td>";

        if(++$counter % $cols == 0) {
            echo "</tr><tr>";
        }
    }

    echo "</tr></TABLE>";
}

#---------------------------------------------------------------------------------------------------
## parses raw string and returns only description
function get_description($raw_desc) {
    $pos = strrpos($raw_desc, ".jpg");

    $desc = $pos ? substr($raw_desc, $pos + 4) : $raw_desc;
    return str_replace("\n", "<br>", trim($desc));
}

#---------------------------------------------------------------------------------------------------
## parses raw string and returns array of image URLs
function get_images($raw_desc, $main_image) {
    $result = Array($main_image => $main_image);

    while ($pos = strpos($raw_desc, ".jpg")) {
        $pos += 4;

        $image = trim(substr($raw_desc, 0, $pos));
        if (substr($image, 0, 4) === 'http') {
            $result[$image] = $image;
        }
        
        $raw_desc = substr($raw_desc, $pos);
    }

    return $result;
}

#---------------------------------------------------------------------------------------------------
## parse dg string
function parseDGString($data) {
    $result = Array();
    if ($data === "") {
        return $result;
    }

    while ($pos = strpos($data, '|')) {
        $el = substr($data, 0, $pos);
        $data = substr($data, $pos + 1);

        if ($el !== '') {
            $result[] = $el;
        }
    }

    return $result;
}

#---------------------------------------------------------------------------------------------------
# replace in string 
function replace_ex($input, $from, $to) {
    while ($pos = strpos($input, $from)) {
        $input = substr($input, 0, $pos).$to.substr($input, $pos + strlen($from));
    }
    return $input;
}

#---------------------------------------------------------------------------------------------------
# add dim grid row
function getDBRowState($state) {
    switch($state) {
        case "0": 
            return 'В наявності';
        case "1":
            return 'Продано';
    }
    return "Невідомий";
}

#---------------------------------------------------------------------------------------------------
# checks whether good is already in basket
function inBasket($good, $dim) {
    $good = (int)$good;
    $dim = (int)$dim;

    global $_SESSION;
    return isset($_SESSION['cl_basket'][$good][$dim]);
}

#---------------------------------------------------------------------------------------------------
# return string pay name
function get_pay_name($payMethos) {
    switch ($payMethos) {
    case 0: return "Карта ПБ";
    case 1: return "Наложений";
    case 2: return "Готівка";
    }

    return "Не вказано";
}

#---------------------------------------------------------------------------------------------------
# returns string - delivery type
function get_delivery_name($deliveryType) {
    switch ($deliveryType) {
        case 0: return "Нова пошта";
        case 1: return "Укрпошта";
        case 2: return "При зустрічі";
    }
    return "Не вказано";
}

#---------------------------------------------------------------------------------------------------
#
function parse_order_description($desc) {
    $res = Array();
    $lines = explode("\n", $desc);
}

#---------------------------------------------------------------------------------------------------

// clear error whenever new page is loaded
clear_error();

?>