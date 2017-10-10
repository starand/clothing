<?
    include_once "common/session_check.php";
    include_once ROOT_PATH."/common/headers.php";
    include_once ROOT_PATH."/db/db.php";
    include_once ROOT_PATH."/common/functions.php";

    if (!userHasPermission(PERM_SEE_CLIENT)) {
        show_error("Недостатньо прав для здійснення операції");
    }

    if (userHasPermission(PERM_EDIT_USER)) {
        echo "<a id='add_client'>Додати клієнта</a>";
    }

    $clients = getClients(); // get all goods;

    echo "<center><h2>Список клієнтів</h2>";
    echo "<table class='price-list' cellspacing='1' cellpadding='1'>";
    echo "<tr><td class='price-list-header'>#</td>";
    echo "<td class='price-list-header'> &nbsp; Клієнт &nbsp; </td>";
    echo "<td class='price-list-header'> &nbsp; Телефон &nbsp; </td>";
    echo "<td class='price-list-header'> &nbsp; Адреса &nbsp; </td></tr>";

    foreach ($clients as $client) {
        echo "<tr class='price-list'>";
            echo "<td class='price-list' id='{$client['c_id']}'> &nbsp; {$client['c_id']}</td>";
            echo "<td class='price-list' id='c{$client['c_id']}'> &nbsp; {$client['c_name']}</td>";
            echo "<td class='price-list' id='{$client['c_id']}'> &nbsp; {$client['c_phone']}</td>";
            echo "<td class='price-list' id='{$client['c_id']}'> &nbsp; {$client['c_address']}</td>";
        echo "</tr>";
    }

    echo "</table>";
?>

<script>
$(document).ready(function() {
    $(".price-list").click(function() {
        id = $(this).attr('id');
        if (id.substr(0, 1) == 'c') {
            $('#main').load("show_sales.php?client=" + id.substr(1));
        }  
    });
    $("#add_client").on("click", function() {
        $("#main").load("add_client.php");
    });
});
</script>