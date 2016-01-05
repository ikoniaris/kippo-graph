$(document).ready(function () {
    $("#Overall-IP-Activity")
        .tablesorter({sortList: [
            [3, 1]
        ], widthFixed: true, widgets: ['zebra']})
        .tablesorterPager({container: $("#pager1")});
});
function getIPinfo(ip) {
    $.ajax({
        type: "POST",
        url: 'include/kippo-ip.ajax.php',
        data: 'ip=' + ip,
        complete: function (response) {
            $('#extended-ip-info').html(response.responseText);

            $("#IP-attemps")
                .tablesorter({widthFixed: true, widgets: ['zebra']})
                .tablesorterPager({container: $("#pager2")});

            $("#IP-commands")
                .tablesorter({widthFixed: true, widgets: ['zebra']})
                .tablesorterPager({container: $("#pager3")});

        },
        error: function () {
            $('#output').html('Bummer: there was an error!');
        }
    });
    return false;
}
