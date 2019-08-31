$(document).ready(function() {
    // $(".opus-index").css("padding-bottom", "200px")

    $(".opus-index").find("tbody").find("tr").each(function() {
        $(this).find("td").eq(4).addClass('tdImg');
        // $(this).find("td").eq(6).addClass('tdImg');
    })

    $(".tdImg").mouseover(function (e) {
        if ($(this).find('p').length !== 0 || $(this).find('img').length === 0) {
            return
        }
        var imgUrl = window.location.host;
        var defaultImgUrl = $(this).find('img').attr("src");
        var imgSr;
        if (defaultImgUrl.substring(0, 4) === 'http') {
            imgSr = defaultImgUrl;
        } else {
            imgSr = 'http://' + imgUrl + '/' + defaultImgUrl;
        }
        $(this).append("<p class='preview'><img src='" + imgSr + "'width='auto' height='300' />" + "</p>");
        var xOffset = 10;
        var yOffset = -10;
        $(".preview")
            .css({
                "top": (e.pageY + yOffset) + "px",
                "left": (e.pageX + xOffset) + "px",
                "position": "absolute",
                "background": "#fff",
                "padding": "10px"
            })
            .fadeIn("fast");
    })

    $(".tdImg").mouseout(function (e) {
        $(this).find(".preview").remove();
    });

    $(".tdImg").mousemove(function (e) {
        var xOffset = 10;
        var yOffset = -10;
        $(".preview")
            .css("top", (e.pageY - yOffset) + "px")
            .css("left", (e.pageX + xOffset) + "px");
    });
});