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

    $(".copy-good").click(function(event) {
        goodId = $(this).attr('id');
        $("#main").load("show_good.php?copyId=" + goodId);
        event.stopImmediatePropagation();
    });
});
</script>