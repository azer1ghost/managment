$(document).ready(function (){
    // SelectPicker
    $('.userSelector').selectpicker()
    $('.filterSelector').selectpicker()
    $('.bootstrap-select').selectpicker()

    // Select change
    $('select[name="limit"]').change(function (){
        this.form.submit();
    });

    // Select2
    const select2 = $('.select2');

    select2.select2({
        theme: 'bootstrap4',
    });

    select2.on('select2:open', function (e) {
        document.querySelector('.select2-search__field').focus();
    });

    document.querySelectorAll('.custom-select2').forEach((e) => {
        $(e).select2({
            placeholder: "Search",
            minimumInputLength: 3,
            theme: 'bootstrap4',
            focus: true,
            ajax: {
                delay: 500,
                url: $(e).data('url'),
                dataType: 'json',
                type: 'GET',
                data: function (params) {
                    return {
                        search: params.term,
                    }
                }
            }
        })

        $(e).on('select2:open', function (e) {
            document.querySelector('.select2-search__field').focus();
        });
    });

    // DateRangePicker
    $('.custom-single-daterange').daterangepicker({
            opens: 'left',
            locale: {
                format: "YYYY-MM-DD HH:mm",
            },
            singleDatePicker: true,
            timePicker: true,
            timePicker24Hour: true,
        }, function(start, end, label) {}
    );

    $('.custom-daterange').daterangepicker({
            opens: 'left',
            locale: {
                format: "YYYY-MM-DD HH:mm",
            },
            timePicker: true,
            timePicker24Hour: true,
        }, function(start, end, label) {}
    );

    $(function () {
        $('#daterange').daterangepicker({
                opens: 'left',
                locale: {
                    format: "YYYY/MM/DD",
                },
                maxDate: new Date(),
            }, function (start, end, label) {}
        );
    });

    // DatePicker
    $( "input[name='datetime']" ).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        showAnim: "slideDown",
    });

    $( "input[name='paid_at']" ).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        showAnim: "slideDown",
    });
    $( "input[name='vat_date']" ).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        showAnim: "slideDown",
    });
    $( "input[name='invoiced_date']" ).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        showAnim: "slideDown",
    });

    $( "input[name='date']" ).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd-mm-yy",
        showAnim: "slideDown",
        minDate: '-1m',
        maxDate: new Date()
    });

    // Shown Filter
    $('.showFilter').click(function (){
        const x = document.getElementById("filterContainer");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip({
            html: true,
            content: function(){
                return $(this).attr('title');
            }
        })
    });
});