var coll = document.getElementsByClassName("collapse");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("activated");
    var contentc = this.nextElementSibling;
    if (contentc.style.maxHeight){
      contentc.style.maxHeight = null;
    } else {
      contentc.style.maxHeight = contentc.scrollHeight + "px";
    }
  });
}

$(function() {
    $('.t-expl').tablesorter();
});

$(function() {
    $('.stable').tablesorter();
});

tippy('[title]', {
	delay: 0,
	arrow: true,
	arrowTransform: 'scaleX(0.1)',
	size: 'small',
	followCursor: false,
	placement: 'top',
	theme: 'honeybee',
	duration: 300,
	animation: 'fade'
})

jQuery.timeago.settings.allowFuture = true;

jQuery(document).ready(function() {
  jQuery("time").timeago();
});

$(".index-right").fadeIn(1000);

$("#tb").click(function(){
	if (Cookies.get('cs') == 'black') {
        $("#theme").attr("href", "/styles/orange.css");
    	Cookies.set('cs', 'orange');
    } else if (Cookies.get('cs') == 'orange') {
        $("#theme").attr("href", "/styles/icy.css");
    	Cookies.set('cs', 'icy');
    } else if (Cookies.get('cs') == 'icy') {
        $("#theme").attr("href", "/styles/white.css");
    	Cookies.set('cs', 'white');
    } else {
        $("#theme").attr("href", "/styles/black.css");
        Cookies.set('cs', 'black');
    }
});

if (typeof rounds !=="undefined") {
var loading = false;
var offset = parseInt($('.active').text());
var max_offset = 10;
var fl = false;

$(document).ready(function() {
    $.ajax({
        dataType: "text",
        url: '/api/?data=rounds_tp',
        success: function(data){
            max_offset = data;
        }
    });
});

$(window).scroll(function() {
    if((($(window).scrollTop()+$(window).height())+250)>=$(document).height()) {
        if(loading == false && fl == false){
            loading = true;
            $.ajax({
              dataType: "html",
              url: '/api/?data=rounds_fastload&offset=' + (offset + 1),
              success: function(data){
                if (data !== '<center>HACKING DENIED</center>') {
                    if (offset == max_offset + 1) {
                        fl = true;
                    }
                    $("#rnds").find('tbody').append(data);
                    jQuery("time").timeago();
                    offset += 1;
                    loading = false;
                }
              }
            });
        };
    };
});
};
