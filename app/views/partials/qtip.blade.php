@if(!isset($classes) || $classes == '')
    <?php $classes = "qtip-light qtip-shadow qtip-rounded qtip-custom"; ?>
@endif

<script type="text/javascript">
    // Grab all elements with the class "hasTooltip"
    $('.hasTooltip').each(function() { // Notice the .each() loop, discussed below
        $(this).qtip({
            content: {
                text: $(this).attr('title'),
                title: $(this).attr('custom'),
                button: true
            },
        style: {
            classes: "<?=$classes?>"
        },
        position: {
            viewport: $(window)
        }
        });
    });
</script>