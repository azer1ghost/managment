$(document).ready(function (){
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
});