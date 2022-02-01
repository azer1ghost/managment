$(document).ready(function (){
    // Hamburger and Sidebar
    const body = $('body');
    const hamburger = $(".hamburger");

    body.addClass(sidebarStatus(checkWindowWidth()));

    if(!body.hasClass('active')){
        hamburger.addClass('is-active');
    }

    hamburger.click(function(){
        if(body.hasClass('active')){
            hamburger.addClass('is-active');
            body.removeClass('active');
            body.addClass('inactive');
            localStorage.setItem("navbar", 'inactive');
        }else{
            hamburger.removeClass('is-active');
            body.removeClass('inactive');
            body.addClass('active');
            localStorage.setItem("navbar", 'active');
        }
    });

    function checkWindowWidth(){
        if($(window).width() < 576){
            return 'active';
        }else{
            return 'inactive';
        }
    }

    function sidebarStatus(status){
        if($(window).width() < 576){
            return 'active';
        }
        if(localStorage.getItem("navbar") !== null){
            return localStorage.getItem("navbar");
        }else{
            localStorage.setItem("navbar", status);
            return localStorage.getItem("navbar");
        }
    }

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
        const url = $(e).data('url');
        $(`[data-url='${url}']`).select2({
            placeholder: "Search",
            minimumInputLength: 3,
            theme: 'bootstrap4',
            focus: true,
            ajax: {
                delay: 500,
                url: url,
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

    // Shown Filter
    $('.showFilter').click(function (){
        const x = document.getElementById("showenFilter");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    });
});