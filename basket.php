<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (isset($_GET['id']) && isset($_GET['dg_id'])) {
        $goodId = (int)$_GET['id'];
        $dg_id = (int)$_GET['dg_id'];

        $good = get_good($goodId);
        if (!$good) {
            show_error("Не правильно вказано товар");
        }

        $dg_rec = getDimRow($dg_id);
        if (!$dg_rec) {
            show_error("Не правильно вказано розмір");
        }

        if ($dg_rec['dg_state'] == DG_SOLD) {
            show_error("Даний розмір вже продано");
        }
        
        if (isset($_SESSION['cl_basket'][$goodId][$dg_id])) {
            show_message("Товар вже в корзині");
        }

        // add good to basket
        $_SESSION['cl_basket'][$goodId][$dg_id] = Array();
        load_page("top.php", "top");
    }

    if (isset($_GET['goodId']) && isset($_GET['removeId'])) {
        $goodId = (int)$_GET['goodId'];
        $dg_id = (int)$_GET['removeId'];

        $good = get_good($goodId);
        if (!$good) {
            show_error("Не правильно вказано товар");
        }

        $dg_rec = getDimRow($dg_id);
        if (!$dg_rec) {
            show_error("Не правильно вказано розмір");
        }

        $goodList = $_SESSION['cl_basket'][$goodId];
        unset($goodList[$dg_id]);
        if (!count($goodList)) {
            unset($_SESSION['cl_basket'][$goodId]);
        } else {
            $_SESSION['cl_basket'][$goodId] = $goodList;
        }
    }
//echo "<pre>"; var_dump($_SESSION['cl_basket']); echo "</pre>";

    if (isset($_POST['desc']) && isset($_POST['pay']) && isset($_POST['delivery']) &&
        isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['middlename']) &&
        isset($_POST['address']) && isset($_POST['phone']) && isset($_POST['email']) &&
        isset($_POST['comment']) && isset($_POST['price']))
    {
        //echo "<pre>"; var_dump($_POST); echo "</pre>";
        $desc = addslashes($_POST['desc']);
        if (strlen($desc) < 4) {
            show_error("Не вірно вказано замовлення");
        }

        $pay = (int)$_POST['pay'];
        if ($pay < 0 || $pay > 2) {
            show_error("Не вірно вказано тип олплати");
        }

        $delivery = (int)$_POST['delivery'];
        if ($delivery < 0 || $delivery > 2) {
            show_error("Не вірно вказано спосіб доставки");
        }

        $name = addslashes("{$_POST['surname']} {$_POST['name']} {$_POST['middlename']}");
        if (strlen($name) < 5) {
            show_error("П.І.Б. повинен бути не менше 5 символів");
        }

        $address = addslashes($_POST['address']);
        if (strlen($address) < 10) {
            show_error("Вкажіть адресу доставки (не менше 10 смиволів) або 'При зустрічі'");
        }

        $phone = addslashes($_POST['phone']);
        if (strlen($phone) < 10) {
            show_error("Номер телефону повинен містити не менше 10 цифр");
        }

        $email = addslashes($_POST['email']);
        $comment = addslashes($_POST['comment']);

        $totalPrice = (float)$_POST['price'];

        if (add_order($desc, $pay, $delivery, $name, $address, $phone, $email, $comment, $totalPrice)) {
            $_SESSION['cl_basket'] = Array();
            show_message("<center><h2>Ви успішно здійснили замовлення. Ми з вами зв'яжемось незабаром.<BR><BR>Дякуємо!<h2>", "main");
            load_page("top.php", "top");
            die();
        } else {
            show_error("Помилка бази даних");
        }

        die();
    }

    if (!count($_SESSION['cl_basket'])) {
        echo "<center><h2>Корзина пуста</h2>";
    } else {
        echo "
        <center><h2>Оформити замовлення</h2>

        <form action='basket.php' method='POST' target='submit_frame'>
        <table cellspacing='1' cellpadding='1'><tr><td>
            <table class='price-list' cellspacing='10' cellpadding='1' style='width: 350px;'>
                <tr><td>Спосіб оплати:<BR>
                    <select name='pay' style='width:100%;'>
                        <option value='0'>На карту ПриватБанку</option>
                        <option value='1'>Накладеним платежем</option>
                        <option value='2'>Готівкою</option>
                    </select>
                </td></tr>
                <tr><td>Спосіб доставки:<BR>
                    <select name='delivery' style='width:100%;'>
                        <option value='0'>Нова Пошта</option>
                        <option value='1'>Укрпошта</option>
                        <option value='2'>При зустрічі</option>
                    </select>
                </td></tr>
                <tr><td>Імя:<BR>
                    <input type='text' name='name' style='width:100%;' />
                </td></tr>
                <tr><td>Прізвище:<BR>
                    <input type='text' name='surname' style='width:100%;' />
                </td></tr>
                <tr><td>По батькові:<BR>
                    <input type='text' name='middlename' style='width:100%;' />
                </td></tr>
                <tr><td>Адреса доставки (відділення):<BR>
                    <textarea name='address' style='width:100%;height:65px;'></textarea>
                </td></tr>
                <tr><td>Номер телефону:<BR>
                    <input type='text' name='phone' style='width:100%;' />
                </td></tr>
                <tr><td>E-mail:<BR>
                    <input type='text' name='email' style='width:100%;' />
                </td></tr>
                <tr><td>Коментар:<BR>
                    <textarea name='comment' style='width:100%;height:65px;'></textarea>
                </td></tr>
            </table>
            
        </td><td> </td><td><center>
        
            <table class='price-list' cellspacing='1' cellpadding='1'>
                <tr><td class='price-list-header'> &nbsp; # &nbsp; </td><td class='price-list-header'> &nbsp; Назва  &nbsp; </td>
                <td class='price-list-header'> &nbsp; К-сть &nbsp; </td>
                <td class='price-list-header'> &nbsp; Ціна &nbsp; </td></tr>";
            
            $desc = "";
            $totalPrice = 0;
            foreach ($_SESSION['cl_basket'] as $goodId => $dims) {
                $good = get_good($goodId);
                $desc .= "$goodId:";

                echo "<tr>";
                echo "<td class='price-list'><img src='".get_image_url($good['g_image'])."' style='width:60px;height:80px;'></td>";
                echo "<td class='price-list' style='padding: 10px;'>{$good['g_title']}";

                $dimCount = count($dims);
                echo "<br><br>";
                
                echo "<table class='price-list' cellspacing='1' cellpadding='1' style='font-size:12px;'>";
                foreach ($dims as $dimId => $dimData) {
                    $dim = getDimRow($dimId);
                    $desc .= "$dimId,";
                    echo "<tr>";
                    $rowData = parseDGString($dim['dg_data']);
                    echo "<td> &nbsp; {$rowData[0]} &nbsp; </td>";
                    echo "<td id='$dimId&goodId=$goodId' class='remove-basket'> [Видалити] </td>";
                    echo "</tr>";
                }
                echo "</table>";

                $goodPrice = $good['g_price'] * $dimCount;
                echo "</td><td class='price-list'> $dimCount </td><td class='price-list'> $goodPrice </td></tr>";
                
                $totalPrice += $goodPrice;
                $desc .= "\r\n";
            }

            echo "</table>
                
            <BR><BR><div style='font-weight:bold;'>Сума до сплати: $totalPrice</div>
            <BR><input type='submit' value='Оформити замовлення' />
            
        </td></tr></table>
        <input type='hidden' name='desc' value='$desc' />
        <input type='hidden' name='price' value='$totalPrice' />
        </form>";
    }

?>

<script>
$(document).ready(function() {
    $(".remove-basket").on("click", function() {
        id = $(this).attr('id');
        $("#main").load("basket.php?removeId=" + id);
    });
    
});
</script>