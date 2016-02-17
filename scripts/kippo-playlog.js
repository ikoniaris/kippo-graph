$(document).ready(function () {
    $("#Playlog-List")
        .tablesorter({sortList: [
            [3, 1]
        ], widthFixed: true, widgets: ['zebra']})
        .tablesorterPager({container: $("#pager1")});
});