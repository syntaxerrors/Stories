(function($){
    $.AjaxLeftTabs = function(baseURL, startTab){
        var url   = location.href;
        var parts = url.split('#');

        if (parts[1] != null) {
            $('#'+ parts[1]).parent().addClass('active');
            $('#ajaxContent').html('loading');
            $('#ajaxContent').load(baseURL + parts[1]);
        } else {
            $('#' + startTab).parent().addClass('active');
            $('#ajaxContent').html('loading');
            $('#ajaxContent').load(baseURL + startTab);
        }
        $('.ajaxLink').click(function() {

            $('.ajaxLink').parent().removeClass('active');
            $(this).parent().addClass('active');

            var link = $(this).attr('id');
            $('#ajaxContent').html('loading');
            $('#ajaxContent').load(baseURL + link);
        });
    }
})(jQuery);