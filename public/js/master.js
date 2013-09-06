(function($){
    $.AjaxLeftTabs = function(baseURL, startTab){
        var url   = location.href;
        var parts = url.split('#');

        if (parts[1] != null) {
            $('#'+ parts[1]).parent().addClass('active');
            $('#ajaxContent').html('<i class="icon-spinner icon-spin"></i>');
            $('#ajaxContent').load(baseURL + parts[1]);
        } else {
            $('#' + startTab).parent().addClass('active');
            $('#ajaxContent').html('<i class="icon-spinner icon-spin"></i>');
            $('#ajaxContent').load(baseURL + startTab);
        }
        $('.ajaxLink').click(function() {

            $('.ajaxLink').parent().removeClass('active');
            $(this).parent().addClass('active');

            var link = $(this).attr('id');
            $('#ajaxContent').html('<i class="icon-spinner icon-spin"></i>');
            $('#ajaxContent').load(baseURL + link);
        });
    }
})(jQuery);

(function($){
    $.fn.AjaxSubmit = function(path, successMessage){
        $(this).submit(function(event) {
            event.preventDefault();

            var formId = this.id;

            $('#' + formId + ' .error').removeClass('error');
            $('#' + formId + ' #message').html('<i class="icon-spinner icon-spin"></i>');

            $.post(path, $(this).serialize(), function(response) {
                if (response.status == 'success') {
                    $('#' + formId + ' #message').html(successMessage);
                }

                if (response.status == 'error') {
                    $('#' + formId + ' #message').empty();
                    $.each(response.errors, function (key, value) {
                        $('#' + formId + ' #' + key).addClass('error');
                        $('#' + formId + ' #message').append('<span class="text-error">'+ value +'</span><br />');
                    });
                }
            })
            .fail(function (){
                $('#' + formId + ' #message').html('<span class="text-error">An error occurred, please try again.</span>');
            });
        });
    }
})(jQuery);