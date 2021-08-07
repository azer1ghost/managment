$( function() {

    $( "input[name='date']" ).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd-mm-yy",
        showAnim: "slideDown"
    });


    $("a[delete]").click(function(e){
        let url  = $(this).attr('href')
        let name = $(this).data('name') ?? 'Record'
        e.preventDefault()
        $.confirm({
            title: 'Confirm delete action',
            content: `Are you sure delete <b>${name}</b> ?`,
            autoClose: 'confirm|8000',
            icon: 'fa fa-question',
            type: 'red',
            theme: 'modern',
            typeAnimated: true,
            buttons: {
                confirm: function () {
                    $.ajax({
                        url:   url,
                        type: 'DELETE',
                        success: function (responseObject, textStatus, xhr)
                        {
                            $.confirm({
                                title: 'Delete successful',
                                icon: 'fa fa-check',
                                content: '<b>:name</b>'.replace(':name',  name),
                                type: 'blue',
                                typeAnimated: true,
                                autoClose: 'reload|3000',
                                theme: 'modern',
                                buttons: {
                                    reload: {
                                        text: 'Ok',
                                        btnClass: 'btn-blue',
                                        keys: ['enter'],
                                        action: function(){
                                            window.location.reload()
                                        }
                                    }
                                }
                            });
                        },
                        error: function ()
                        {
                            $.confirm({
                                title: 'Confirm!',
                                content: 'Ops something went wrong! Please reload page and try again.',
                                type: 'red',
                                typeAnimated: true,
                                buttons: {
                                    cancel: function () {

                                    },
                                    reload: {
                                        text: 'Reload page',
                                        btnClass: 'btn-blue',
                                        keys: ['enter'],
                                        action: function(){
                                            window.location.reload()
                                        }
                                    }
                                }
                            });
                        }
                    });
                },
                cancel: function () {

                },
            }
        });
    });



});