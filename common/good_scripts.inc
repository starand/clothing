<script>
$(document).ready(function() {
    $(".edit").click(function(event) {
        goodId = $(this).attr('id');
        $("#main").load("add_good.php?editId=" + goodId);
        event.stopImmediatePropagation();
    });

    $(".arrive").click(function(event) {
        goodId = $(this).attr('id');
        $("#main").load("update_good.php?arriveId=" + goodId);
        event.stopImmediatePropagation();
    });

    $("#goods td").click(function() {
        goodId = $(this).attr('id');
        $("#main").load("show_good.php?id=" + goodId);
    });
});
</script>