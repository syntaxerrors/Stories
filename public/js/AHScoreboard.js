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

(function($){
    $.AjaxSubmit = function(path, successMessage){
        $('#jsonSubmit').click(function(event) {
            event.preventDefault();

            $('.error').removeClass('error');
            $('#message').empty().append('<i class="icon-spinner icon-spin"></i>');

            var data = $('#submitForm').serialize();

            $.post(path, data, function(response) {

                if (response.status == 'success') {
                    $('#message').empty().append(successMessage);
                }
                if (response.status == 'error') {
                    $('#message').empty();
                    $.each(response.errors, function (key, value) {
                        $('#' + key).addClass('error');
                        $('#message').append('<span class="text-error">'+ value +'</span><br />');
                    });
                }
            });
        });
    }
})(jQuery);

function Tree(settings) {
   this.userId = settings.userId;
   this.folders = settings.folders;
   this.errors = "";
   this.start();
}

Tree.prototype.start = function () {
   var self = this;
   // self.data = 'Test';
   $.ajax({
       url: '/messages/get-folders-for-user/'+ self.userId,
       type: "GET",
       dataType: "json",
       success: function (data, textStatus, jqxhr) {
           self.folders(data);
           // console.log(data);
       },
       error: function (jqxhr, textStatus, errorThrown) {
           if (self.errors) {
               self.errors = errorThrown;
           } else {
               alert("Tree Error: "+errorThrown);
           }
       },
   });
}