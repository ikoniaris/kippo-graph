$(document).ready(function () {
    $("a[rel=gallery_group]").fancybox({
        'transitionIn': 'elastic',
        'transitionOut': 'elastic',
        'titlePosition': 'outside',
        'speedIn': 600,
        'speedOut': 600,
        'overlayShow': true,
        'overlayColor': '#000',
        'overlayOpacity': 0.9
    });
});