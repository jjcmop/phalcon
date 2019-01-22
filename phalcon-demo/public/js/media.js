$(function () {
    //andriod ios 鼠标移上去显示隐藏
    /*$(".andriod-down").hover(function () {
        $(".andriod-down .down-mask").show(300);
    }, function () {
        $(".andriod-down .down-mask").hide(300);
    });

    $(".ios-down").hover(function () {
        $(".ios-down .down-mask").show(300);
    }, function () {
        $(".ios-down .down-mask").hide(300);
    });*/

    //显示导航栏
    $(window).scroll(function() {
        $(this).scrollTop() > 90 ?  $('.nav-bar').addClass('fixed'): $('.nav-bar').removeClass('fixed');
    });

    //点击向上滑动
    var $go = $('.go-top');
    $(window).scroll(function() {
        $(this).scrollTop() > 50 ? $go.show() : $go.hide();
    });
    $go.click(function() {
        $("html,body").animate({ scrollTop: 0 }, 500);
    });
});
