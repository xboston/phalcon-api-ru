$(document).ready(function () {
    $('ul#class-tree ul').each(function (index) {
        $(this).prev().addClass('collapsible').click(function () {
            if ($(this).next().css('display') == 'none') {
                $(this).next().slideDown(100, function () {
                    $(this).prev().removeClass('collapsed').addClass('expanded');
                });
            } else {
                $(this).next().slideUp(100, function () {
                    $(this).prev().removeClass('expanded').addClass('collapsed');
                    $(this).find('ul').each(function () {
                        $(this).hide().prev().removeClass('expanded').addClass('collapsed');
                    });
                });
            }
            return false;
        });
    });
});
