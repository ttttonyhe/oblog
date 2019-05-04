<button @click="new_page" style="opacity:0" id="bottom"></button>
<script>
    $(window).scroll(function() {
        var scrollTop = $(window).scrollTop();
        var scrollHeight = $('#bottom').offset().top - 742;
        if (scrollTop >= scrollHeight) {
            setTimeout('$("#bottom").click()',1000);
        }
    });
</script>
<script type="text/javascript">
        $(document).ready(function () {
            $.goup({
                trigger: 100,
                bottomOffset: 30,       //距底部偏移量 
                locationOffset: 30,     //距右部偏移量
            });
        });
</script>