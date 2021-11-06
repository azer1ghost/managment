$( function() {
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
                        error: function (err)
                        {
                            console.log(err);
                            $.confirm({
                                title: 'Ops something went wrong!',
                                content: err?.responseJSON,
                                type: 'red',
                                typeAnimated: true,
                                buttons: {
                                    close: {
                                        text: 'Close',
                                        btnClass: 'btn-blue',
                                        keys: ['enter'],
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