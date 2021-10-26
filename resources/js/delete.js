$( function() {

    // jQuery(function($) {
    //     $.datepicker.regional['it'] = {
    //         closeText: 'Chiudi', // set a close button text
    //         currentText: 'Oggi', // set today text
    //         monthNames: ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno',   'Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'], // set month names
    //         monthNamesShort: ['Gen','Feb','Mar','Apr','Mag','Giu','Lug','Ago','Set','Ott','Nov','Dic'], // set short month names
    //         dayNames: ['Domenica','Luned&#236','Marted&#236','Mercoled&#236','Gioved&#236','Venerd&#236','Sabato'], // set days names
    //         dayNamesShort: ['Dom','Lun','Mar','Mer','Gio','Ven','Sab'], // set short day names
    //         dayNamesMin: ['Do','Lu','Ma','Me','Gio','Ve','Sa'], // set more short days names
    //         dateFormat: 'dd/mm/yy' // set format date
    //     };
    //
    //     $.datepicker.setDefaults($.datepicker.regional['ru']);
    // });

    $( "input[name='date']" ).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd-mm-yy",
        showAnim: "slideDown",
        minDate: '-1m',
        maxDate: new Date()
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