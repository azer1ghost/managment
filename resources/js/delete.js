$( function() {
    $("a[delete]").click(function(e){
        let url  = $(this).attr('href')
        let name = $(this).data('name') ?? 'Record'
        let statusTitle = $(this).data('status-title') ?? 'Confirm delete action'
        let status = $(this).data('status') ?? 'Are you sure to delete'
        let type =  $(this).data('type') ?? 'DELETE';
        e.preventDefault()
        $.confirm({
            title: statusTitle,
            content: `${status} <b>${name}</b> ?`,
            autoClose: 'confirm|8000',
            icon: 'fa fa-question',
            type: 'red',
            theme: 'modern',
            typeAnimated: true,
            buttons: {
                confirm: function () {
                    $.ajax({
                        url:   url,
                        type: type,
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