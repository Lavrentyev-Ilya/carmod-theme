'use strict'; 

jQuery(document).ready(function () {
    // block of additional info
    /* Admin Tips for Publick side */
    jQuery("body").on("click", ".CmInfoSvgIm", function(e){
        e.preventDefault();
        var blockW = jQuery(this).parent().find('.CmDopInfBlWrap').width();
        var heightTable = jQuery('.CmTypesWrap').innerHeight();
        var dopBlock = jQuery(this).parent().find('.CmDopInfBlWrap').clone();
        if(jQuery(window).width() >= 860) {
            dopBlock.appendTo('.CmTypesWrap').css({top: e.clientY - 20, left: e.clientX - blockW, display: 'block'});
        }else {
            dopBlock.appendTo('.CmTypesWrap').css({top: e.clientY - 20, right: '0px', display: 'block'});
        }
        var outHeight = dopBlock.outerHeight();
        var innHeight = dopBlock.innerHeight();
        var pHeight = window.innerHeight;
        var pos = jQuery('.CmTypesWrap').offset();
		var elem_top = pos.top.toFixed(0);
		var y = e.pageY - elem_top;
        var diff = heightTable - y;
        var numLeft = pHeight - e.clientY;
        if (diff < outHeight || numLeft < blockW) {
            dopBlock.css({top: e.clientY - innHeight + 20, left: e.clientX - blockW});
        }
    });
    jQuery("body").on("mouseleave", ".CmDopInfBlWrap", function(){
        jQuery(this).fadeOut(200);
        setTimeout(() => jQuery(this).remove(), 400);
    });
    jQuery("body").on("click", ".CmCloseBlock", function(e){
        e.stopPropagation();
        jQuery(this).parents('.CmDopInfBlWrap').fadeOut(200);
        setTimeout(() => jQuery(this).parents('.CmDopInfBlWrap').remove(), 400);
    });

    //  filter by liter
    var _content_Rows = jQuery(".CmTypeListWrap");
    var _litbut = jQuery(".lit_but");
    _litbut.click(function () {
        var _letter = jQuery(this);
        var _text = jQuery(this).text();
        _litbut.removeClass("CmTypeBg col_fff").addClass("CmTypeColor");
        _letter.addClass("CmTypeBg col_fff").removeClass("CmTypeColor");
        _content_Rows.hide();
        _content_Rows.each(function (i) {
            if(_text === All_Lng) {
                jQuery(this).show();
            }else{
               var _cellText = jQuery(this).data('liter');
               if (_text == _cellText) {
                jQuery(this).show();
               }
            }
        });
    });

    // PRODUCT LINK
    jQuery('.CmTypesRow').click(function(){
        var link = jQuery(this).data('furl');
        location.href = link;
    });
});