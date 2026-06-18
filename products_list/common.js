/*
Lazy loading of background images. To use it:
document.addEventListener("DOMContentLoaded", function(){
	lazyBackground("ClassHere", "bg");
});
*/
function lazyBackground(className = "MyClass", attrName = "bg") {
	const selector = `.${className}[data-${attrName}]`;
	const lazyBlocks = document.querySelectorAll(selector);
	if ("IntersectionObserver" in window) {
		const observer = new IntersectionObserver((entries, obs) => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					const el = entry.target;
					const url = el.dataset[attrName];
					if (url){
						el.style.backgroundImage = `url(${url})`;
						el.removeAttribute(`data-${attrName}`);
					}
					obs.unobserve(el);
				}
			});
		});
		lazyBlocks.forEach(el => observer.observe(el));
	} else {
		lazyBlocks.forEach(el => { // fallback for Old browsers
			const url = el.dataset[attrName];
			if (url){
				el.style.backgroundImage = `url(${url})`;
				el.removeAttribute(`data-${attrName}`);
			}
		});
	}
}


function calculateAspectRatioFit(srcWidth, srcHeight, maxWidth, maxHeight) {
    var ratio = [maxWidth / srcWidth, maxHeight / srcHeight];
    ratio = Math.min(ratio[0], ratio[1]);
    var width = srcWidth*ratio;
    var height = srcHeight*ratio;
    jQuery(".CmSchPicture").css({width: width, height: height});
}

var scrollFix = function(e){
    if(e.keyCode == 38 || e.keyCode == 40 || e.type == 'mousewheel'){
        return false;
    }
    jQuery(this).scrollTop(position);

};

//SET COOKIE FUNCTION
function setCookie(key, value, expireDays, expireHours, expireMinutes, expireSeconds){
    var expireDate = new Date();
    if (expireDays) {
        expireDate.setDate(expireDate.getDate() + expireDays);
    }
    if (expireHours) {
        expireDate.setHours(expireDate.getHours() + expireHours);
    }
    if (expireMinutes) {
        expireDate.setMinutes(expireDate.getMinutes() + expireMinutes);
    }
    if (expireSeconds) {
        expireDate.setSeconds(expireDate.getSeconds() + expireSeconds);
    }
    var cleaned_host;
    if(location.host.indexOf('www.') === 0){
        cleaned_host = location.host.replace('www.','');
    }else{
        cleaned_host = window.location.hostname;
    }
    document.cookie = key +"="+ escape(value) +
        ";domain="+ cleaned_host +
        ";path=/"+
        ";expires="+expireDate.toUTCString();
}
// DELETE COOKIE
function deleteCookie(name){
    setCookie(name, "", null , null , null, -1);
}
//GET COOKIE FUNC
function get_cookie ( cookie_name ){
    var results = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)' );
    if ( results )
        return ( unescape ( results[2] ) );
    else
        return null;
}

jQuery(document).ready(function() {
    //Product Prices block (Webservices AJAX updated)
    var ProdListBlocks;
    var WsPpNum;
    function WebServiceListBlocks(){
        ProdListBlocks = [];
        WsPpNum=0;
        jQuery('body .rightBlock, body .CmListPrTab_c, body .WsDataTb_x').each(function(){
            if(typeof jQuery(this).data('artnum')!== 'undefined' && typeof jQuery(this).data('brand')!== 'undefined'){
                if(jQuery(this).data('artnum')!='' && jQuery(this).data('brand')!=''){
                    ProdListBlocks.push(this);
                }
            }
        });
        return ProdListBlocks;
    }
    WebServiceListBlocks();

    if(ProdListBlocks.length>0){
        WsNextProdPrices();
    }

    function WsNextProdPrices(){
        var ePrlb = ProdListBlocks[WsPpNum];
        if(ePrlb){
            jQuery(ePrlb).find('.CmWsLoadBar').show();
            var Dir = jQuery(ePrlb).data('dir');
            var ArtNum = jQuery(ePrlb).data('artnum');
            var Brand = jQuery(ePrlb).data('brand');
            // console.log(Dir+'/ '+ArtNum+'/ '+Brand);
//            var pData = 'CarModAjaxProductPrices=Y&SearchWS=Y&ArtNum='+ArtNum+'&Brand='+Brand+'&Sets=List';
//            ReqFetch('/'+Dir+'/', pData)
//                .then(result => {
//                    jQuery(ePrlb).html(result);
//                    WsPpNum++;
//                    WsNextProdPrices();
//                });
            jQuery.ajax({url:'/'+Dir+'/', type:'POST', dataType:'html', data:{CarModAjaxProductPrices:'Y', SearchWS:'Y', ArtNum:ArtNum, Brand:Brand, Sets:'Grid'}})
               .done(function(Result){
                    //Check for WS Errors for admin
                    var aResult = Result.split('|CmWsErrors|');
                    if(aResult.length>1){
                        jQuery('.fxCont').html(aResult[1]).css('text-align','left');
                        jQuery('.fxOverlay').css('display', 'flex');
                    }else{
                        //Update Prices block
                        jQuery(ePrlb).html(Result);
                        WsPpNum++;
                        WsNextProdPrices(); //Next search (if block exists)
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    jQuery(ePrlb).html(jqXHR.responseText+' ['+textStatus+'] '+errorThrown);
                    WsPpNum++;
                    WsNextProdPrices();
                });
        }
    }


        // FILTERS
    $("#CmAjaxBox").on("click",'.CmFilterCheck', function () {
        $(this).find('.check_b').toggleClass('check_back');
        LoadingToggle('CmContent', $('#CmAjaxBox').offset().top-20);
        var oData = {};
        oData['CarModAjax']='Y';
        var prid = $(this).data('prid');
        var crcod = $(this).data('crcod');
        var bcode = $(this).data('bcode');
        if(prid){
            oData['ByProductID']=prid;
        }else if(crcod){
            oData['ByCriteriaCode']=crcod;
        }else if(bcode){
            oData['ByBrandCode']=bcode;
        }
		$('.CmTumButn').each(function(){
			if($(this).hasClass('CmTumPushed')){
				oData['Only']=$(this).data('tumb');
			}
        });
		$.post(window.location.href, oData, function(Result){
			//alert();
            $("#CmAjaxBox").html(Result);
            if($(window).width() <= 992) {
                $('.left_fil').css('right', '315px');
            }
            LoadingToggle();
            WebServiceListBlocks();
            WsNextProdPrices();
        });
    });


    //OE, Analog switch
    jQuery('#CmAjaxBox').on('click', '.CmTumButn', function(e){
		if(!jQuery(this).hasClass('CmTumPushed')){
			e.preventDefault();
			jQuery('.CmTumButn').each(function(){
				jQuery(this).removeClass('CmTumPushed');
			});
			jQuery(this).addClass('CmTumPushed');
			var titPosHrf = jQuery(this).attr('href');
			LoadingToggle('CmContent', jQuery('#CmAjaxBox').offset().top-20);
			jQuery.ajax({url:titPosHrf, type:'POST', dataType:'html', data:{CarModAjax:'Y'}})
				.done(function(Result){
					jQuery("#CmAjaxBox").html(Result);
					if(jQuery(window).width() <= 992) {
						jQuery('.left_fil').css('right', '315px');
					}
					LoadingToggle();
					WebServiceListBlocks();
					WsNextProdPrices();
				});
		}
    });

    // Select Products SubSection

    // const pickSect = document.querySelectorAll('.CmSectExpanBl');
    // const next = document.querySelector('.CmSectExpanBl').nextElementSibling;
    // console.log(next);
	// for(let i = 0; i < pickSect.length;i++){
	// 	if(pickSect[i].dataset.exp === 'Expanded'){
	// 		const childPick = pickSect[i].querySelector('.Down');
	// 		childPick.classList.add('DownActive');
    //         pickSect[i].nextElementSibling.style.display = 'block';
    //         // jQuery(pickSect[i]).next().slideToggle(400);
	// 	}
	// }

    jQuery("#CmAjaxBox").on("click",".PickSection", function(e){
        LoadingToggle('CmContent',1);
        window.history.pushState('object or string', 'Title', jQuery(this).attr('href'));
        e.preventDefault();
        var Code = jQuery(this).data('code');
        var pickHeight = jQuery(this).height();
        jQuery(this).parents('.cm_FsBlock').find('.FilterSection').css('border-top-width') == pickHeight / 2;
        jQuery(this).parents('.cm_FsBlock').find('.FilterSection').css('border-bottom-width') == pickHeight / 2;
        jQuery.post(window.location.href, {CarModAjax:'Y', PickSection:Code}, function(Result){
            jQuery("#CmAjaxBox").html(Result);
            if(jQuery(window).width() <= 992) {
                jQuery('.left_fil').css('right', '315px');
            }
            LoadingToggle('CmContent',1);
            WebServiceListBlocks();
            WsNextProdPrices();
        });
    });

    // SORT BY PRODUCT_LIST
    jQuery("#CmAjaxBox ").on("click", ".sort_bl", function(e){
        e.stopPropagation();
        jQuery('.hide_bl').toggleClass('hiBlbor');
        jQuery('.show_bl').toggleClass('shBlbor');
        jQuery('.hide_bl').toggle();
    });
    jQuery("#CmAjaxBox ").on("click", ".sort_list", function(e){
        e.stopPropagation();
        jQuery('.hide_bl').toggleClass('hiBlbor');
        jQuery('.show_bl').toggleClass('shBlbor');
        jQuery('.hide_bl').hide();
        LoadingToggle('CmContent', jQuery('#CmAjaxBox').offset().top-20);
        jQuery.post(window.location.href, {CarModAjax:'Y', SortBy:jQuery(this).data('sort') }, function(Result){
            jQuery("#CmAjaxBox").html(Result);
            LoadingToggle('CmContent');
            WebServiceListBlocks();
            WsNextProdPrices();
        });
    });
    jQuery("#CmAjaxBox").on("mouseleave", ".hide_bl", function (){
        jQuery(this).hide();
    });
    jQuery('.CmSortBlockClose').on('click',function (e){
        jQuery(".hide_bl").hide();
    });


    // VIEW SWITCH PRODUCT_LIST
    jQuery("#CmAjaxBox").on("click",".cm_viewAct", function(e){
        var uri = jQuery(this).data('urix');
        LoadingToggle('CmContent', jQuery('#CmAjaxBox').offset().top-20);
        var view = jQuery(this).data('view');
        if(view && view!=''){
            jQuery.post(uri, {CarModAjax:'Y', ActivateTab:view}, function(Result){
                jQuery("#CmAjaxBox").html(Result);
                LoadingToggle();
                WebServiceListBlocks();
                WsNextProdPrices();
            });
        }
    });

    // SELECT SETUP SIDE
    // front, rear
    jQuery('#CmAjaxBox').on('click', '.CmSelectCarSide', function(e){
        e.preventDefault();
        jQuery('.CmFrRr').each(function(){
            jQuery(this).find('.CmCarSide').css('fill','#909090');
            jQuery(this).find('.CmCarSideTxt').css('color','#909090');
            jQuery(this).removeClass('CmSelSideTogg');
            jQuery(this).addClass('CmSelectCarSide');
        });
        jQuery(this).find('.CmCarSide').css('fill','#f93a3a');
        jQuery(this).find('.CmCarSideTxt').css('color','#f93a3a');
        jQuery(this).removeClass('CmSelectCarSide');
        jQuery(this).addClass('CmSelSideTogg');
        var titPosHrf = jQuery(this).attr('href');
        LoadingToggle('CmContent', jQuery('#CmAjaxBox').offset().top-20);
        jQuery.ajax({url:titPosHrf, type:'POST', dataType:'html', data:{CarModAjax:'Y'}})
            .done(function(Result){
                jQuery("#CmAjaxBox").html(Result);
                if(jQuery(window).width() <= 992) {
                    jQuery('.left_fil').css('right', '315px');
                }
                LoadingToggle();
                WebServiceListBlocks();
                WsNextProdPrices();
            });
    });
    //left, right
    jQuery('#CmAjaxBox').on('click', '.CmSelectBVSide', function(e){
        e.preventDefault();
        jQuery('.CmLfRt').each(function(){
            jQuery(this).find('.CmBackView').css('fill','#909090');
            jQuery(this).find('.CmBVTxt').css('color','#909090');
            jQuery(this).removeClass('CmSelBVTogg');
            jQuery(this).addClass('CmSelectBVSide');
        });
        jQuery(this).find('.CmBackView').css('fill','#f93a3a');
        jQuery(this).find('.CmBVTxt').css('color','#f93a3a');
        jQuery(this).removeClass('CmSelectBVSide');
        jQuery(this).addClass('CmSelBVTogg');
        var titPosHrf = jQuery(this).attr('href');
        LoadingToggle('CmContent', jQuery('#CmAjaxBox').offset().top-20);
        jQuery.ajax({url:titPosHrf, type:'POST', dataType:'html', data:{CarModAjax:'Y'}})
            .done(function(Result){
                jQuery("#CmAjaxBox").html(Result);
                LoadingToggle();
                WebServiceListBlocks();
                WsNextProdPrices();
            });
    });


    /* Admin Tips for Public side */
    jQuery("#CmContent").on("mouseover", ".CmTitShow", function(){
        var title = jQuery(this).attr('title');
        if(title){
            jQuery(this).data('tipText', title).removeAttr('title');
            jQuery('<p class="CmTipBox"></p>').html(title).appendTo('body').show(); //alert('+'+title);
        }else{return false;}
    });
    jQuery("#CmContent").on("mouseleave", ".CmTitShow", function(){
        jQuery(this).attr('title', jQuery(this).data('tipText'));
        jQuery('.CmTipBox').remove();
    });
    jQuery("#CmContent").on("mousemove", ".CmTitShow", function(e){
        var mousex = e.pageX - 80; //Get X coordinates
        var mousey = e.pageY + 10; //Get Y coordinates
        jQuery('.CmTipBox').css({ top:mousey, left:mousex });
    });


    /* ====== SCHEMES ==== =============================================== */
    /* show more toggle */
    function ShemeHover() {
        let elBox;
        jQuery(".CmSchemaBox").hover(function(){
            elBox = jQuery(this),
            curHeight = elBox.height(),
            autoHeight = elBox.css('height', 'auto').height();
            elBox.height(curHeight).stop(true, true).animate({height: autoHeight}, 200, "linear");
        },function(){
            elBox.animate({height: '82px'}, 200, "linear");
        })
    }
    const schCount = jQuery('.CmSchemaBox').data('schcount');
    if(schCount > 5){
        ShemeHover();
        jQuery(document).ajaxComplete(function () {
            ShemeHover();
        });
    }

    jQuery("body").on("click", '.CmSchema', function (e){
        var SchPicID = jQuery(this).data('picid'),
        Lng = jQuery(this).data('lng');
        jQuery(this).find('.CmSchLoadWrap').show().css('display','flex');
        jQuery.ajax({url:window.location.href, type:'POST', dataType:'html', data:{SchPicID:SchPicID, Lng:Lng}})
        .done(function(Result){
            jQuery('.fxOverlay').css('display', 'flex');
            jQuery('.CmLoadWrap').css('display', 'flex');
            jQuery('.fxCont').height(window.innerHeight-100);
            jQuery('.fxCont').width(1180);
            jQuery('.fxCont').html('<div class="fxClose"></div>'+Result);
            jQuery('.CmLoadWrap').css('display', 'flex');
            var maxWidth = jQuery('.CmSchemeBlockWrap').width(),
            maxHeight = jQuery('.CmSchemeBlockWrap').height('inherit'),
            srcHeight = jQuery('.CmSchemeGridWrap').data('height'),
            srcWidth = jQuery('.CmSchemeGridWrap').data('width');
            calculateAspectRatioFit(srcWidth, srcHeight, maxWidth, maxHeight);
            jQuery('.CmLoadWrap').hide();
            jQuery('.CmSchLoadWrap').hide();
        });
    });

     //more prices
    jQuery("#CmAjaxBox, .blockProdPrice").on('click', '.CmMorePrices', function(){
		jQuery('#CmAjaxBox, .blockProdPrice').find('.morePricestab').slideUp(100);
        jQuery(this).find('.morePricestab').slideDown(200);
    });

    //close more prices
    jQuery('#CmAjaxBox, .blockProdPrice').on('click','.CmMorePriceBlClose', function(e){
        e.stopPropagation();
        jQuery('.morePricestab').slideUp(200);
        jQuery('.CmMoreHidePr').show();
    });
    // jQuery(document).mousedown(function (e){ // событие клика по веб-документу
    //     var div = jQuery(".morePricestab"); // тут указываем ID элемента
    //     if (!div.is(e.target) && div.has(e.target).length === 0) {
    //         jQuery('.morePricestab').slideUp(200);
    //     }
    // });

    //SHOW MORE PRICE IN HIDE BLOCK
    jQuery("#CmAjaxBox, .blockProdPrice").on('click', '.CmMoreHidePr', function(){
        jQuery('.CmWrapBlMorePrice ').removeClass('CmWrapBlHeight');
        jQuery(this).hide();
    });

   // PRICE QUANTITY
    jQuery("#CmAjaxBox, .blockProdPrice").on("click", ".cm_countButM", function () {
        const min_quant = jQuery(this).siblings('.cm_countRes').data('minimalqnt');
        const input = jQuery(this).parent().find('.cm_countRes');
        let count = '';
        if(min_quant){
            count = parseInt(input.val()) - min_quant;
            count = count <= 0 ? min_quant : count;
        }else{
            count = parseInt(input.val()) - 1;
        }
        count = count <= 0 ? 1 : count;
        input.val(count);
        input.change();
        return false;
    });
    jQuery("#CmAjaxBox, .blockProdPrice").on("click", ".cm_countButP", function () {
        const min_quant = jQuery(this).siblings('.cm_countRes').data('minimalqnt');
        const maxaval = jQuery(this).parent().find('.cm_countRes').data('maxaval');
        //var IsMore = maxaval.indexOf('+',0);
		//if(IsMore==0){
			const input = jQuery(this).parent().find('.cm_countRes');
			let count = '';
			if(min_quant){
				count = parseInt(input.val()) + min_quant;
			}else{
				count = parseInt(input.val()) + 1;
			}
			count = count > maxaval ? maxaval : count;
			input.val(count);
			input.change();
			return false;
		//}
    });
    jQuery("#CmAjaxBox, .blockProdPrice").on("keyup", '.cm_countRes', function () {
        checkSymb(this);
    });
    function checkSymb(input){
        var value = input.value;
        var maxav = jQuery(input).data('maxaval');
        var rep = /[-\.;":'a-zA-Zа-яА-Я]/;
        if (rep.test(value)){
            value = value.replace(rep, '');
            input.value = value;
        }
        if(value>maxav){
            value = value.replace(value, maxav);
            input.value = value;
        }
        if(value==0){
            value = 1;
            input.value = value;
        }
        return value;
    }

    // SLIDER FILTER
    const brandCount = document.querySelector('.CmBrandFiltWrap');
    if (brandCount) {
        function BrandFilterSl () {
            let position = 0;
            const slidesToShow = 8;
            const slidesToScroll = 1;

            const track = document.querySelector('.CmBrandSlTrack');
            const btnPrev = document.querySelector('.CmBrSlPrev');
            const btnNext = document.querySelector('.CmBrSlNext');
            const items = document.querySelectorAll('.CmBrandSlideCheck');
            const itemCount = items.length;
			if($(document).hasClass('CmBrFiltCont')){
				const container = document.querySelector('.CmBrFiltCont');
			}else{
				const container = 1;
			}
			const itemWidth = container.clientWidth / slidesToShow + 16;
            const movePosition = slidesToScroll * itemWidth

            items.forEach((item) => {
                item.style.minWidth = `${itemWidth - 15}px`;
            });

            function SlideTractPos () {
                const itemLeft = itemCount - (Math.abs(position) + slidesToShow * itemWidth) / itemWidth;
                position -= itemLeft >= slidesToScroll ? movePosition : itemLeft * itemWidth;
                SetPosition();
                CheckBtns();
            }

            btnNext.addEventListener('click', () => {
                SlideTractPos ();
            });

            btnPrev.addEventListener('click', () => {
                const itemLeft = Math.abs(position) / itemWidth;
                position += itemLeft >= slidesToScroll ? movePosition : itemLeft * itemWidth;
                SetPosition();
                CheckBtns();
            });

            const SetPosition = () => {
                track.style.transform = `translateX(${position}px)`;
            };

            const CheckBtns = () => {
                btnPrev.disabled = position === 0;
                btnNext.disabled = position <= - (itemCount - slidesToShow) * itemWidth;
            };


            track.classList.add('Cm-translate-3s');
            let intSlide = setInterval(SlideTractPos, 4000);
            brandCount.onmouseover = function () {
                for ( var i = 1; i <= intSlide; i++ ) {
                    clearInterval ( i );
                }
                // clearInterval(intSlide);
                track.classList.remove('Cm-translate-3s');
                track.classList.add('Cm-translate-05s');
            };
            brandCount.onmouseleave = function () {
                intSlide = setInterval(SlideTractPos, 4000);
                track.classList.add('Cm-translate-3s');
                track.classList.remove('Cm-translate-05s');
            }
        }
        BrandFilterSl ();

        jQuery(document).ajaxComplete(function () {
            if (brandCount !== null) {
                BrandFilterSl ();
            }
        });
        // END SLIDER



        jQuery("#CmAjaxBox").on("click",'.CmBrandSlideCheck', function () {
            let elem = jQuery(this);
            LoadingToggle('CmContent', jQuery('#CmAjaxBox').offset().top-20);
            var oData = {};
            oData['CarModAjax']='Y';
            var bcode = jQuery(this).data('bcode');
            if(bcode){
                oData['ByBrandCode']=bcode;
            }
            jQuery.post(window.location.href, oData, function(Result){
                jQuery("#CmAjaxBox").html(Result);
                elem.toggleClass('CmActive');
                LoadingToggle();
                WebServiceListBlocks();
                WsNextProdPrices();
            });
        });
    }
});

// MODAL Window
var doc = jQuery(document);
jQuery(document).mousedown(function (e){
	var div = jQuery(".fxCont");
	if (!div.is(e.target)
	&& div.has(e.target).length === 0) {
		jQuery('.fxOverlay').hide(); 
		jQuery('.fxCont').html('').css({width:'unset'});
		doc.unbind('scroll keydown mousewheel', scrollFix);
	}
});
jQuery('body').on('click', '.fxClose', function(){
	jQuery('.fxOverlay').hide();
	jQuery('.fxCont').html('');//.css({width:'unset', height:'unset'});
	doc.unbind('scroll keydown mousewheel', scrollFix);
});


// LOADING overlay
function LoadingToggle(IdContentBox, ScrollPx){
    IdContentBox=IdContentBox||'CmContent';
    ScrollPx=ScrollPx||0;
    if(jQuery("#Loading").css('display') !== 'none'){
        jQuery("#Loading").hide();
    }else{
        var Cont = jQuery("#"+IdContentBox);
        var CTop = Cont.position().top;
        if(ScrollPx==-1){ScrollPx=CTop;}
        var CLeft = Cont.position().left; //alert('Top:'+CTop+'; Left:'+CLeft); // .offset()
        if(ScrollPx>0){
            jQuery('html, body').animate({ scrollTop:ScrollPx }, 500);
        }
        var CWidth = Cont.outerWidth();
        if(!jQuery.isNumeric(CWidth)){CWidth = Cont.width() + (parseInt(Cont.css('padding-left')) + parseInt(Cont.css('padding-right')) );}
        var CHeight = Cont.outerHeight();
        if(!jQuery.isNumeric(CHeight)){CHeight = Cont.height() + (parseInt(Cont.css('padding-top')) + parseInt(Cont.css('padding-bottom')) );}
        if(IdContentBox!='CmContent'){ //Для элементов внитри контента CarMod
            var PadT = parseInt(jQuery("#CmContent").css('padding-top'));
            var PadR = parseInt(jQuery("#CmContent").css('padding-right'));
            var PadB = parseInt(jQuery("#CmContent").css('padding-bottom'));
            var PadL = parseInt( jQuery("#CmContent").css('padding-left'));
            jQuery("#Loading").css({top:CTop-PadT, left:CLeft-PadL});
            jQuery("#Loading").width(CWidth+PadL+PadR).height(CHeight+PadT+PadB).show();
        }else{ // Для #CmContent

            jQuery("#Loading").width(CWidth).height(CHeight).show();
        }
    }
}

// SHOW & HIDE MORE NOT HIDE PRICE
jQuery('body').on('click', '.CmShowMorePrice', function(){
    jQuery(this).prev('.CmTablePriceWrap').find('.CmTablePriceValueRow_2').show().css('display', 'table-row');
    var hide = jQuery(this).data('hide');
    jQuery(this).html(hide).addClass('CmHideMorePrice').removeClass('CmShowMorePrice');
});
jQuery('body').on('click', '.CmHideMorePrice', function(){
    jQuery(this).prev('.CmTablePriceWrap').find('.CmTablePriceValueRow_2').hide();
    var show = jQuery(this).data('show');
    jQuery(this).html(show).addClass('CmShowMorePrice').removeClass('CmHideMorePrice');

});


// SHOW MORE OE NUMBERS
 jQuery('body').on('click', '.CmShowHidOeNum', function(){
    jQuery('.CmHiddenOeNum').each(function(){
        jQuery(this).hide().parents('.CmOeNumsTd').find('.CmHideOeNum').hide();
        jQuery(this).parents('.CmOeNumsTd').find('.CmShowHidOeNum').show();
    });
    jQuery(this).hide();
    jQuery(this).siblings('.CmOeNumWrap').find('.CmHiddenOeNum').show();
    jQuery(this).siblings('.CmHideOeNum').show().css('align-self','flex-end');
    if((jQuery(this).parent().siblings('.CmOeBrName').data('check')=='Y' && jQuery(this).siblings('.CmOeNumWrap').find('.CmHiddenOeNum').length>2) || jQuery(this).siblings('.CmOeNumWrap').find('.CmHiddenOeNum').length>6){
        jQuery('.CmOeBlockInside').removeClass('CmOeNumHeightToHide');
        jQuery('.CmHideOe').show();
        jQuery('.CmShowOe').hide();
    }
});
jQuery('body').on('click', '.CmHideOeNum', function(){
    jQuery(this).siblings('.CmOeNumWrap').find('.CmHiddenOeNum').hide();
    jQuery(this).hide();
    jQuery(this).siblings('.CmShowHidOeNum').show();
});

//REDIRECT FROM PRODUCT_LIST
jQuery('body').on('click','.CmLookAnalogHook',function(){
    jQuery('.tabOeNum').addClass('activeSecTab');
    jQuery('.tabPartUse').removeClass('activeSecTab');
    jQuery('.centBlockInfo').addClass('CmAddClassFlex');
    jQuery('.cmSuitBlock').hide();
    jQuery('.tabOeNum').find('.cmSvgImg').css('fill','#ffffff');
    jQuery('.tabPartUse').find('.cmSvgImg').css('fill','#808080');
});

// TABS ON PRODUCT PAGE
jQuery(document).ready(function( jQuery ) {

    const urlPage = jQuery('.wrapBlTabsMenu').data('url'),
    cmDir = jQuery('.CmModelSuitBlock').data('cmdir'),
    aCarModels = new Array();
     //Open brand models function
    function OpenBrandModels(fxPlace='') {
        jQuery('body '+fxPlace).on('click', '.CmLogoBrandImg', function(){
            jQuery('.CmModifListBlock').html('');
            jQuery('.CmSelectModelTxt, .CmSelectModTitl').show();
            const brName = jQuery(this).data('brname');
            const hasClick = jQuery(this).attr("clicked");
            jQuery(fxPlace+' .CmSelectBrandTxt').hide();
            if (hasClick === "N") {
                jQuery(fxPlace+' .CmModelModif').html('');
                jQuery.each(aCarModels[brName], function(key, val) {
                    jQuery(fxPlace+' .CmModelModif').append(`<div class="CmModeItem" data-code="${key}" clicked="N">${val}</div>`);
                });
            }
            jQuery(fxPlace+' .CmLogoBrandImg').each(function(){
                jQuery(this).attr("clicked", "N").removeClass('CmBordForAct');
            });
            jQuery(this).attr("clicked", "Y").addClass('CmBordForAct');
        });
    }

    //Get models request
    function AjaxModelRequest(urlP, fxPlace='', modDir=''){
        jQuery.ajax({url:urlP, type:'POST', dataType:'html', data:{GetModels:'Y'}})
        .done(function(Res){
			//console.log(Res);
            const aResJson = JSON.parse(Res);

            if(aResJson!==null){
                if(aResJson[0]!=='None'){
                    jQuery(fxPlace+' .CmModBlockInner').show().css('display','flex');
                    jQuery.each(aResJson, function(key, val) {
                        aCarModels[key] = val['MODELS'];
                        jQuery(fxPlace+' .CmBrandNameBl').append(`<div class="CmLogoBrandImg CmTitShow" style="background-image:url(/${modDir}/media/brands/${val['CODE']}.png)" data-brname="${key}" clicked="N" title="${key}"></div>`);
                    });
                    const modCount = Object.keys(aCarModels).length;
                    if(modCount == 1){
                        OpenBrandModels();
                        AjaxModifRequest(urlPage);
                        jQuery('.CmLogoBrandImg').addClass('CmBordForAct').trigger('click');
                        jQuery('.CmModeItem:first-child').trigger('click');
                    }
                }else{
                    jQuery(fxPlace+' .CmNotFoundInfo').css('display','flex');
                }
            }else{
                jQuery(fxPlace+' .CmNotFoundInfo').css('display','flex');
            }
            jQuery(fxPlace+' .CmModelWaitLoad').hide();
        });
    }

    //Request model after load page
    if(jQuery('.wrapBlTabsMenu').data('avail')==='N'){
        AjaxModelRequest(urlPage, '', cmDir);
        //Open brand models function call
        OpenBrandModels();
        jQuery('.tabPartUse').attr("clicked", "Y");
        //Open model modify
        AjaxModifRequest(urlPage);
    }

    //Get modifications
    function AjaxModifRequest(urlP, fxPlace='') {
        jQuery('body '+fxPlace).on('click','.CmModeItem',function(){
            jQuery(fxPlace+' .CmSelectModelTxt').hide();
            var modCode = jQuery(this).data('code');
            jQuery(fxPlace+' .CmModeItem').each(function(){
                jQuery(this).removeClass('CmModeItemActive');
            });
            jQuery(this).addClass('CmModeItemActive');
            const hasClick = jQuery(this).attr("clicked");
            if (hasClick === "N") {
                jQuery(fxPlace+' .CmSelectModTitl').hide();
                jQuery(fxPlace+' .CmModifListOverf').css({alignItems:'center'});
                jQuery(fxPlace+' .CmModifListBlock').html('');
                jQuery(fxPlace+' .CmModifListBlock').append('<div class="CmSmLoading"><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div></div>');
                jQuery.ajax({url:urlP, type:'POST', dataType:'html', data:{GetVhApp:modCode}})
                    .done(function(Res){
                    var aJRes = JSON.parse(Res);
                    jQuery.each(aJRes, function(key, val) {
                        jQuery(fxPlace+' .CmModifListOverf').css({alignItems:'flex-start'});
                        jQuery(fxPlace+' .CmModifListBlock').append(`<div class="CmTypesList">${val}</div>`).css('width', '100%');
                    });
                    jQuery(fxPlace+' .CmSmLoading').css('display','none');
                });
            }
            jQuery(fxPlace+' .CmModeItem').each(function(){
                jQuery(this).attr("clicked", "N");
            });
            jQuery(this).attr("clicked", "Y");
        });
    }

    jQuery('body').on('click', '.tabPartUse', function(){
        let hasClick = jQuery(this).attr("clicked"),
        cmDir = jQuery('.CmModelSuitBlock').data('cmdir');
        if(hasClick === "N"){
            jQuery('.cmBlockInfo').append('<div class="CmModelWaitLoad"><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div></div>');
            AjaxModelRequest(urlPage, '', cmDir);
        }
        jQuery('.tabPartUse').attr("clicked", "Y");

        //Open brand models function call
        OpenBrandModels();

        //Open model modify
        AjaxModifRequest(urlPage);
    });


    jQuery('body').on('click', '.CmtabSelBut', function(){
        jQuery('.CmtabSelBut').removeClass('activeSecTab c_boxShad');
        jQuery(this).addClass('activeSecTab');
        if(jQuery(this).data('change')==='OeNum'){
            jQuery(this).addClass('CmTabShadRight');
        }else if(jQuery(this).data('change')==='Suite'){
            jQuery(this).addClass('CmTabShadLeft');
        }
		if(jQuery(this).data('change')==='Comments'){
           jQuery('.centBlockInfo').css({height: 0, opacity: 0});
           jQuery('.cmSuitBlock').css({height: 0, opacity: 0});
		   jQuery('.centBlockComments').css({display: 'block'});
        }
        if(jQuery(this).data('change')==='OeNum'){
           jQuery('.centBlockInfo').css({height: 'auto', opacity: 1});
            jQuery('.cmSuitBlock').css({height: 0, opacity: 0});
			jQuery('.centBlockComments').css({display: 'none'});
        }
        if(jQuery(this).data('change')==='Suite'){
            jQuery('.centBlockInfo').css({height: 0, opacity: 0});
            jQuery('.cmSuitBlock').css({height: 'auto', opacity: 1});
			jQuery('.centBlockComments').css({display: 'none'});
        }
        if(jQuery(this).data('change')==='ProdInfo'){
            jQuery('.centBlockInfo').css({height: 0, opacity: 0});
            jQuery('.cmSuitBlock').css({height: 0, opacity: 0});
			jQuery('.centBlockComments').css({display: 'none'});
        }
    });

    //Request from view_list
    jQuery("#CmAjaxBox").on("click", '.ProductInfoOe', function (e){
        e.preventDefault();
        var thisEl = jQuery(this);
        var furl = jQuery(this).data('furl');
        jQuery('.fxOverlay').css('display', 'flex');
        jQuery('.fxCont').html('<div class="CmSchLoadWrap" style="display:flex; top:0; left:0;"><div class="CmSchLoading"><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div></div></div>');
        jQuery.ajax({url:furl, type:'POST', dataType:'html', data:{ProdPrice:'Y', CarModAjaxOENumbers:'Y', IncludeFuncs:'Yes', ArtNum:jQuery(this).parent().data('artnum'), Brand:jQuery(this).parent().data('brand'), Tab:jQuery(this).data('tab'), HideStat:'Y', OENumbers:'Y'}})
            .done(function(Result){
                jQuery('.fxCont').html('<div class="fxClose"></div>'+Result);
                jQuery('.fxCont .centBlockInfo').css({height: 'auto', opacity: 1});
                jQuery('.fxCont .cmSuitBlock').css({height: 0, opacity: 0});
                const countBlock = document.querySelectorAll('.CmInfoInBlock');
                if(countBlock.length > 0) document.querySelector('.CmNoInfo').style.display = 'none';
            });
        });
    jQuery("#CmAjaxBox").on("click", '.ProductInfoSuit', function (e){
        e.preventDefault();
        let thisEl = jQuery(this);
        const furl = jQuery(this).data('furl'),
        cmDir = jQuery(this).data('moduledir');
        jQuery('.fxOverlay').css('display', 'flex');
        jQuery('.fxCont').html('<div class="CmSchLoadWrap" style="display:flex; top:0; left:0;"><div class="CmSchLoading"><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div></div></div>');
        jQuery.ajax({url:furl, type:'POST', dataType:'html', data:{ProdPrice:'Y', CarModAjaxSuitVehicle:'Y', IncludeFuncs:'Yes', ArtNum:jQuery(this).parent().data('artnum'), Brand:jQuery(this).parent().data('brand'), Tab:jQuery(this).data('tab'), HideStat:'Y', ProdVehicle:'Y'}})
            .done(function(Result){
                jQuery('.fxCont').html('<div class="fxClose"></div>'+Result);
                AjaxModelRequest(furl, '.fxCont', cmDir);
                OpenBrandModels('.fxCont');
                AjaxModifRequest(furl, '.fxCont');
            });
        });
    //APPLIED TO MODEL
    //More brand models
    // jQuery('body').on('click', '.CmBrandNameBl', function(){
    //     jQuery('.CmSelectModelTxt').show();
    //     var bn = jQuery(this).data('brandname');
    //     jQuery('.CmBrandNameBl').each(function(){
    //         jQuery(this).removeClass('');
    //     });
    //     jQuery(this).addClass('CmColorBr CmColorBgL CmBordForAct CmColorOu');
    //     jQuery('.CmModelList').hide();
    //     jQuery('.CmTypesList').hide();
    //     jQuery('.CmModelList').each(function(){
    //         var mc = jQuery(this).data('modname');
    //         if(bn == mc){
    //             jQuery(this).show();
    //         }
    //     });
    //     jQuery('.CmSelectModTitl').show();
    // });
    // jQuery('.CmBrandNameBl:first-child').click();




    //MORE ANALOGS
    jQuery('body').on('click','.CmShowA',function(){
       jQuery('.CmAnalogBlockInside').removeClass('CmBlockHeightToHIde');
       jQuery('.CmHideA').show();
       jQuery('.CmHideTextBlock').hide();
       jQuery(this).hide();
    });
    jQuery('body').on('click','.CmHideA',function(){
        jQuery('.CmAnalogBlockInside').addClass('CmBlockHeightToHIde');
        jQuery('.CmShowA, .CmHideTextBlock').show();
        jQuery(this).hide();

    });

    //MORE VEHICLES
    jQuery('body').on('click','.CmShowV',function(){
       jQuery('.CmVehicBlockWrap').removeClass('CmVehicleHeightBl');
       jQuery('.CmHideV').show();
       jQuery('.CmHideTextVehicBlock').hide();
       jQuery(this).hide();
    });
    jQuery('body').on('click','.CmHideV',function(){
        jQuery('.CmVehicBlockWrap').addClass('CmVehicleHeightBl');
        jQuery('.CmShowV, .CmHideTextVehicBlock').show();
        jQuery(this).hide();
        jQuery(window).scrollTop(300);
    });

    //MORE OE NUMBERS
    jQuery('body').on('click','.CmShowOe',function(){
       jQuery('.CmOeBlockInside').removeClass('CmOeNumHeightToHide');
       jQuery('.CmHideOe').show();
       jQuery(this).hide();
    });
    jQuery('body').on('click','.CmHideOe',function(){
        jQuery('.CmOeBlockInside').addClass('CmOeNumHeightToHide');
        jQuery('.CmShowOe, .CmShowHidOeNum').show();
        jQuery('.CmHiddenOeNum, .CmHideOeNum').hide();
        jQuery(this).hide();
        jQuery(window).scrollTop(500);
    });

    //Hover on price block
    jQuery('body').on('mouseenter', '.CmPriceProd', function(){
        var hintTxtA = jQuery(this).data('txta');
        var hintTxtD = jQuery(this).data('txtd');
        var hintBlockA = '<div class="CmShowHintBl CmAvalHintBl">'+hintTxtA+'</div>';
        var hintBlockD = '<div class="CmShowHintBl CmDelivHintBl">'+hintTxtD+'</div>';
        jQuery(this).find('.avalTd').append(hintBlockA);
        jQuery(this).find('.delivTd').append(hintBlockD);
        setTimeout(() =>
        jQuery('.CmShowHintBl').addClass('CmShowHintPopup'),
        jQuery('.CmAvalImgTextPage').css('border-radius', '0px 0px 0px 3px'),
        jQuery('.delivTd ').css('border-radius', '0px 0px 3px 0px'));
    });
    jQuery('body').on('mouseleave', '.CmPriceProd', function(){
        setTimeout(() => jQuery(this).find('.CmShowHintBl').removeClass('CmShowHintPopup'));
        setTimeout(() => jQuery(this).find('.CmShowHintBl').remove(), 200);
        jQuery('.CmAvalImgTextPage').css('border-radius', '3px 0px 0px 3px');
        jQuery('.delivTd ').css('border-radius', '0px 3px 3px 0px');
    });

});

// Fetch request
async function ReqFetch(url, data) {
    try{
        const response = await fetch(url, {
            method: 'POST',
            body: data,
            headers: new Headers({
                'Content-Type': 'application/x-www-form-urlencoded'
            })
        });
        const resData = await response.text();
        return resData;
    } catch(error){
        console.log(error);
    }
}

//ASK PRICE AND MAIL ORDER POPUP BLOCK
function ToCartMailOrder(elem, addFolder, e){
    var Brand = jQuery(elem).data('brand'),
    Article = jQuery(elem).data('artnum'),
    ModuleDir = jQuery(elem).data('moduledir'),
    DataLang = jQuery(elem).data('lang'),
    Link = jQuery(elem).data('link'),
    pData = 'Brand='+Brand+'&Article='+Article+'&Lang='+DataLang+'&ModDir='+ModuleDir+'&Link='+Link;
    e.preventDefault();
    jQuery('.fxOverlay').css('display', 'flex');
    jQuery('.fxCont').html('<div id="tempSaver"></div><div class="CmSchLoadWrap" style="display:flex; margin:auto;"><div class="CmSchLoading"><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div></div></div>');

    ReqFetch('/'+ModuleDir+'/add/'+addFolder+'/controller.php', pData)
        .then(result => jQuery('.fxCont').html('<div class="fxClose"></div>'+result));

    // DON'T DELETE

    // jQuery.post('/'+ModuleDir+'/add/'+addFolder+'/controller.php', {Brand:Brand, Article:Article, Lang:DataLang, ModDir:ModuleDir, Link:Link}, function(Result){
    //     jQuery('.fxCont').find('#tempSaver').html(Result);
    //     setTimeout(() => {
    //         jQuery('.fxCont').html('<div class="fxClose"></div>'+Result);
    //     }, 300);
    //     jQuery('.fxCont').find('#tempSaver').html('');
    // });
}
jQuery(document).ready(function(jQuery) {
    jQuery("#CmAjaxBox, .CmPriceProd, .blockProdPrice").on("click", '.ListAskPrice', function (e){
        var elem = jQuery(this);
        ToCartMailOrder(elem, 'askprice', e);
    });
    jQuery("#CmAjaxBox, .CmPriceProd").on("click", '.CmMailOrder', function (e){
        var elem = jQuery(this);
        ToCartMailOrder(elem, 'mail_order', e);
    });
	
});

//Popup for images
document.addEventListener("DOMContentLoaded", function () {
    let currentGallery = [];
    let currentIndex = 0;
    let scale = 1;
    let isDragging = false;
    let startX, startY, translateX = 0, translateY = 0;

    let pinchStartDist = 0;
    let pinchStartScale = 1;

    const Popup_n = document.querySelector(".Popup_n");
	if(!Popup_n){
        return;
    }
	const popupImage = document.getElementById("PopupImage_n");
	const ImgContainer = document.getElementById("ImgContainer");
	const popupCoord = ImgContainer.querySelector(".CmShemaCoord"); // Блок с координатами в попапе
	const popupBraArt = Popup_n.querySelector(".CmShemaBraArt");
	const closeBtn = document.querySelector(".Close_n");
	const prevBtn = document.querySelector(".Prev_n");
	const nextBtn = document.querySelector(".Next_n");
	const zoomInBtn = document.querySelector(".ZoomIn_n");
	const zoomOutBtn = document.querySelector(".ZoomOut_n");

    function openPopup(index, galleryImages) {
        currentGallery = galleryImages;
        currentIndex = index;
        updateImage();
        Popup_n.style.display = "flex";

        if (currentGallery.length === 1) {
            prevBtn.style.display = "none";
            nextBtn.style.display = "none";
        } else {
            prevBtn.style.display = "block";
            nextBtn.style.display = "block";
        }
    }

    function closePopup() {
        Popup_n.style.display = "none";
        resetImage();
    }

    function showNext() {
        currentIndex = (currentIndex + 1) % currentGallery.length;
        updateImage();
    }

    function showPrev() {
        currentIndex = (currentIndex - 1 + currentGallery.length) % currentGallery.length;
        updateImage();
    }

    function updateImage() {
        let currentImg = currentGallery[currentIndex];
        popupImage.src = currentImg.src;
        resetImage();

        // Найти соответствующий CmShemaCoord у текущего изображения
        let coordBlock = currentImg.closest(".CmSchemBlockWrap")?.querySelector(".CmShemaCoord");
        if (coordBlock) {
            popupCoord.style.cssText = coordBlock.style.cssText;
            popupCoord.style.display = "block";
        } else {
            popupCoord.style.display = "none";
        }
		
		let NameArtBra = currentImg.closest(".CmSchemBlockWrap")?.querySelector(".CmShemaBraArt");
		if (NameArtBra) {
			popupBraArt.innerHTML = NameArtBra.innerHTML
            popupBraArt.style.display = "block";
        } else {
            popupBraArt.style.display = "none";
        }
    }

    function resetImage() {
        scale = 1;
        translateX = 0;
        translateY = 0;
        ImgContainer.style.transform = `translate(0px, 0px) scale(1)`;
    }

    function zoomIn() {
        scale = Math.min(scale + 0.2, 3);
        updateTransform();
    }

    function zoomOut() {
        scale = Math.max(scale - 0.2, 1);
        updateTransform();
    }

    function updateTransform() {
        ImgContainer.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
    }

    function startDrag(event) {
        event.preventDefault();
        isDragging = true;
        startX = (event.clientX || event.touches[0].clientX) - translateX;
        startY = (event.clientY || event.touches[0].clientY) - translateY;
        popupImage.style.cursor = "grabbing";
    }

    function drag(event) {
        if (!isDragging) return;
        translateX = (event.clientX || event.touches[0].clientX) - startX;
        translateY = (event.clientY || event.touches[0].clientY) - startY;
        updateTransform();
    }

    function endDrag() {
        isDragging = false;
        popupImage.style.cursor = "grab";
    }

    function zoom(event) {
        event.preventDefault();
        let delta = event.deltaY > 0 ? -0.1 : 0.1;
        scale = Math.min(Math.max(1, scale + delta), 3);
        updateTransform();
    }

    function getDistance(touches) {
        let dx = touches[0].clientX - touches[1].clientX;
        let dy = touches[0].clientY - touches[1].clientY;
        return Math.sqrt(dx * dx + dy * dy);
    }

    function pinchStart(event) {
        if (event.touches.length === 2) {
            pinchStartDist = getDistance(event.touches);
            pinchStartScale = scale;
        }
    }

    function pinchMove(event) {
        if (event.touches.length === 2) {
            let pinchDist = getDistance(event.touches);
            scale = Math.min(Math.max(1, pinchStartScale * (pinchDist / pinchStartDist)), 3);
            updateTransform();
        }
    }

    function initializeGallery() {
        document.querySelectorAll(".BoxGlr_n").forEach(gallery => {
            let images = gallery.querySelectorAll(".GlrImg_n");
            let openButton = gallery.querySelector(".OpenPopup_n");

            if (openButton && !openButton.dataset.listenerAdded) {
                openButton.dataset.listenerAdded = "true";
                openButton.addEventListener("click", () => {
                    if (images.length > 0) {
                        openPopup(0, images);
                    }
                });
            }

            images.forEach((img, index) => {
                if (!img.dataset.listenerAdded) {
                    img.dataset.listenerAdded = "true";
                    img.addEventListener("click", () => openPopup(index, images));
                }
            });
        });
    }

    initializeGallery();

    const observer = new MutationObserver(() => {
        initializeGallery();
    });

    observer.observe(document.body, { childList: true, subtree: true });

    closeBtn.addEventListener("mousedown", closePopup);
    nextBtn.addEventListener("click", showNext);
    prevBtn.addEventListener("click", showPrev);
    zoomInBtn.addEventListener("click", zoomIn);
    zoomOutBtn.addEventListener("click", zoomOut);

    Popup_n.addEventListener("mousedown", (e) => {
        if (e.target === Popup_n) closePopup();
    });

    document.addEventListener("keydown", (e) => {
        if (Popup_n.style.display === "flex") {
            if (e.key === "ArrowRight") showNext();
            if (e.key === "ArrowLeft") showPrev();
            if (e.key === "Escape") closePopup();
        }
    });

    popupImage.addEventListener("mousedown", startDrag);
    document.addEventListener("mousemove", drag);
    document.addEventListener("mouseup", endDrag);
    popupImage.addEventListener("touchstart", startDrag, { passive: false });
    popupImage.addEventListener("touchmove", drag, { passive: false });
    popupImage.addEventListener("touchend", endDrag);
    ImgContainer.addEventListener("wheel", zoom);
    popupImage.addEventListener("touchstart", pinchStart, { passive: false });
    popupImage.addEventListener("touchmove", pinchMove, { passive: false });
});

function initFilters() {
    // Очищаем предыдущие обработчики
    document.querySelectorAll('.custom-select .CmSelOption').forEach(el => {
        el.removeEventListener('click', handleSelectClick);
    });
    document.querySelectorAll('.custom-select .CmSelOpt').forEach(el => {
        el.removeEventListener('click', handleOptionClick);
    });
    document.getElementById('CmFtSrcEngn')?.removeEventListener('input', handleEngineSearch);

    const filterSelects = document.querySelectorAll('.custom-select');
    const engineSearchInput = document.getElementById('CmFtSrcEngn');
    const items = document.querySelectorAll('.CmTypeListWrap');
    
    const activeFilters = {};
    let engineSearchTerm = '';
    
    // Обработчик клика по выбранной опции
    function handleSelectClick() {
        this.closest('.custom-select').classList.toggle('active');
    }
    
    // Обработчик выбора опции
    function handleOptionClick() {
        const select = this.closest('.custom-select');
        const selectedOption = select.querySelector('.CmSelOption span');
        const value = this.dataset.value;
        const text = this.textContent;
        const filterType = select.dataset.filter;
        
        selectedOption.textContent = text;
        select.classList.remove('active');
        
        if (value) {
            activeFilters[filterType] = value;
        } else {
            delete activeFilters[filterType];
        }
        
        applyFilters();
    }
    
    // Обработчик поиска по двигателю
    function handleEngineSearch() {
        engineSearchTerm = this.value.trim().toLowerCase();
        applyFilters();
    }
    
    // Инициализация кастомных селекторов
    filterSelects.forEach(select => {
        select.querySelector('.CmSelOption').addEventListener('click', handleSelectClick);
        
        select.querySelectorAll('.CmSelOpt').forEach(option => {
            option.addEventListener('click', handleOptionClick);
        });
    });
    
    if (engineSearchInput) {
        engineSearchInput.addEventListener('input', handleEngineSearch);
    }
    
    function applyFilters() {
        const filterKeys = Object.keys(activeFilters);
        
        items.forEach(item => {
            let shouldShow = true;
            
            for (const filterType of filterKeys) {
                const filterValue = activeFilters[filterType];
                const itemValue = item.dataset[filterType];
                
                if (filterType === 'year') {
                    if (!checkYearMatch(filterValue, itemValue)) {
                        shouldShow = false;
                        break;
                    }
                } 
                else if (itemValue !== filterValue) {
                    shouldShow = false;
                    break;
                }
            }
            
            if (shouldShow && engineSearchTerm) {
                const engineData = item.dataset.engine ? item.dataset.engine.toLowerCase() : '';
                shouldShow = engineData.includes(engineSearchTerm);
            }
            
            item.style.display = shouldShow ? '' : 'none';
        });
    }
    
    function checkYearMatch(selectedYear, yearRange) {
        if (!selectedYear || !yearRange) return true;
        
        const yearNum = parseInt(selectedYear);
        if (isNaN(yearNum)) return true;
        
        if (yearRange.includes('-')) {
            const [startStr, endStr] = yearRange.split('-');
            const startYear = parseInt(startStr);
            
            const endYear = parseInt(endStr);
            if (isNaN(endYear)) {
                return yearNum >= startYear;
            } else {
                return yearNum >= startYear && yearNum <= endYear;
            }
        } else {
            return parseInt(yearRange) === yearNum;
        }
    }
}

function CreatePopup(iconType, title, message) {
    const successPopup = document.createElement('div');
    successPopup.id = 'successPopup';
    
    // Определяем SVG в зависимости от типа иконки
    let svgIcon, popupClass, svgClass;
    
    if (iconType === 'error') {
        svgIcon = '<path fill="currentColor" d="M12,2C17.53,2 22,6.47 22,12C22,17.53 17.53,22 12,22C6.47,22 2,17.53 2,12C2,6.47 6.47,2 12,2M15.59,7L12,10.59L8.41,7L7,8.41L10.59,12L7,15.59L8.41,17L12,13.41L15.59,17L17,15.59L13.41,12L17,8.41L15.59,7Z"/>';
        popupClass = 'success-popup-content error-popup';
        svgClass = 'success-icon error-icon';
    } else {
        svgIcon = '<path fill="currentColor" d="M12 2C6.5 2 2 6.5 2 12S6.5 22 12 22 22 17.5 22 12 17.5 2 12 2M10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z"/>';
        popupClass = 'success-popup-content';
        svgClass = 'success-icon';
    }
    
    successPopup.innerHTML = `
        <div class="${popupClass}">
            <svg viewBox="0 0 24 24" class="${svgClass}">
                ${svgIcon}
            </svg>
            <h3 class="CmPpapHd">${title}</h3>
            <p class="CmPpapStr">${message}</p>
            <button class="CmCloseSucPopup">OK</button>
        </div>
    `;

    document.body.appendChild(successPopup);
    
    // Добавляем класс для анимации
    setTimeout(() => {
        successPopup.classList.add('show');
    }, 100);

    // Обработчик закрытия
    document.querySelector('.CmCloseSucPopup').addEventListener('click', () => {
        successPopup.classList.remove('show');
        setTimeout(() => successPopup.remove(), 300);
    });
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', initFilters);

// Инициализация после AJAX загрузки
function onAjaxComplete() {
    initFilters();
}

// Пример вызова после успешной AJAX загрузки:
// function loadPopupContent() {
//     fetch('/your-url').then(response => {
//         // вставить контент в попап
//         onAjaxComplete();
//     });
// }


/* document.addEventListener("DOMContentLoaded", function () {
    let currentGallery = [];
    let currentIndex = 0;
    let scale = 1;
    let isDragging = false;
    let startX, startY, translateX = 0, translateY = 0;

    let pinchStartDist = 0;
    let pinchStartScale = 1;

    const Popup_n = document.querySelector(".Popup_n");
    const popupImage = document.getElementById("PopupImage_n");
	const ImgContainer = document.getElementById("ImgContainer");
    const closeBtn = document.querySelector(".Close_n");
    const prevBtn = document.querySelector(".Prev_n");
    const nextBtn = document.querySelector(".Next_n");
    const zoomInBtn = document.querySelector(".ZoomIn_n");
    const zoomOutBtn = document.querySelector(".ZoomOut_n");

    function openPopup(index, galleryImages) {
        currentGallery = galleryImages;
        currentIndex = index;
        updateImage();
        Popup_n.style.display = "flex";
		
		if (currentGallery.length === 1) {
			prevBtn.style.display = "none";
			nextBtn.style.display = "none";
		} else {
			prevBtn.style.display = "block";
			nextBtn.style.display = "block";
		}
    }

    function closePopup() {
        Popup_n.style.display = "none";
        resetImage();
    }

    function showNext() {
        currentIndex = (currentIndex + 1) % currentGallery.length;
        updateImage();
    }

    function showPrev() {
        currentIndex = (currentIndex - 1 + currentGallery.length) % currentGallery.length;
        updateImage();
    }

    function updateImage() {
        popupImage.src = currentGallery[currentIndex].src;
        resetImage();
    }

    function resetImage() {
        scale = 1;
        translateX = 0;
        translateY = 0;
        ImgContainer.style.transform = `translate(0px, 0px) scale(1)`;
    }

    function zoomIn() {
        scale = Math.min(scale + 0.2, 3);
        updateTransform();
    }

    function zoomOut() {
        scale = Math.max(scale - 0.2, 1);
        updateTransform();
    }

    function updateTransform() {
        ImgContainer.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
    }

    function startDrag(event) {
        event.preventDefault();
        isDragging = true;
        startX = (event.clientX || event.touches[0].clientX) - translateX;
        startY = (event.clientY || event.touches[0].clientY) - translateY;
        popupImage.style.cursor = "grabbing";
    }

    function drag(event) {
        if (!isDragging) return;
        translateX = (event.clientX || event.touches[0].clientX) - startX;
        translateY = (event.clientY || event.touches[0].clientY) - startY;
        updateTransform();
    }

    function endDrag() {
        isDragging = false;
        popupImage.style.cursor = "grab";
    }

    function zoom(event) {
        event.preventDefault();
        let delta = event.deltaY > 0 ? -0.1 : 0.1;
        scale = Math.min(Math.max(1, scale + delta), 3);
        updateTransform();
    }

    function getDistance(touches) {
        let dx = touches[0].clientX - touches[1].clientX;
        let dy = touches[0].clientY - touches[1].clientY;
        return Math.sqrt(dx * dx + dy * dy);
    }

    function pinchStart(event) {
        if (event.touches.length === 2) {
            pinchStartDist = getDistance(event.touches);
            pinchStartScale = scale;
        }
    }

    function pinchMove(event) {
        if (event.touches.length === 2) {
            let pinchDist = getDistance(event.touches);
            scale = Math.min(Math.max(1, pinchStartScale * (pinchDist / pinchStartDist)), 3);
            updateTransform();
        }
    }

	function initializeGallery() {
		document.querySelectorAll(".BoxGlr_n").forEach(gallery => {
			let images = gallery.querySelectorAll(".GlrImg_n");
			let openButton = gallery.querySelector(".OpenPopup_n");

			if (openButton && !openButton.dataset.listenerAdded) {
				openButton.dataset.listenerAdded = "true";
				openButton.addEventListener("click", () => {
					if (images.length > 0) {
						openPopup(0, images);
					}
				});
			}

			images.forEach((img, index) => {
				if (!img.dataset.listenerAdded) {
					img.dataset.listenerAdded = "true";
					img.addEventListener("click", () => openPopup(index, images));
				}
			});
		});
	}

	// Запускаем при загрузке страницы
	initializeGallery();

	// Создаем наблюдатель за изменениями в DOM
	const observer = new MutationObserver(() => {
		initializeGallery();
	});
	observer.observe(document.body, { childList: true, subtree: true });

    closeBtn.addEventListener("click", closePopup);
    nextBtn.addEventListener("click", showNext);
    prevBtn.addEventListener("click", showPrev);
    zoomInBtn.addEventListener("click", zoomIn);
    zoomOutBtn.addEventListener("click", zoomOut);

    Popup_n.addEventListener("mousedown", (e) => {
        if (e.target === Popup_n) closePopup();
    });

    document.addEventListener("keydown", (e) => {
        if (Popup_n.style.display === "flex") {
            if (e.key === "ArrowRight") showNext();
            if (e.key === "ArrowLeft") showPrev();
            if (e.key === "Escape") closePopup();
        }
    });

    popupImage.addEventListener("mousedown", startDrag);
    document.addEventListener("mousemove", drag);
    document.addEventListener("mouseup", endDrag);
    popupImage.addEventListener("touchstart", startDrag, { passive: false });
    popupImage.addEventListener("touchmove", drag, { passive: false });
    popupImage.addEventListener("touchend", endDrag);
    ImgContainer.addEventListener("wheel", zoom);
    
    popupImage.addEventListener("touchstart", pinchStart, { passive: false });
    popupImage.addEventListener("touchmove", pinchMove, { passive: false });
}); */


// Image Drag
/* document.addEventListener("DOMContentLoaded", function () {
        let isDragging = false;
        let offsetX, offsetY;
        let activeElement = null;

        document.addEventListener("mousedown", startDrag);
        document.addEventListener("touchstart", startDrag);
        document.addEventListener("mousemove", doDrag);
        document.addEventListener("touchmove", doDrag);
        document.addEventListener("mouseup", stopDrag);
        document.addEventListener("touchend", stopDrag);

        function startDrag(e) {
            let target = e.target.closest(".draggable");
            if (!target) return;
            
            isDragging = true;
            activeElement = target;

            let touch = e.type.startsWith("touch") ? e.touches[0] : e;
            offsetX = touch.clientX - activeElement.getBoundingClientRect().left;
            offsetY = touch.clientY - activeElement.getBoundingClientRect().top;

            activeElement.style.cursor = "grabbing";
            activeElement.style.position = "absolute";
        }

        function doDrag(e) {
            if (!isDragging || !activeElement) return;
            
            let touch = e.type.startsWith("touch") ? e.touches[0] : e;
            activeElement.style.left = touch.clientX - offsetX + "px";
            activeElement.style.top = touch.clientY - offsetY + "px";

            e.preventDefault(); // Убираем скролл на мобильных
        }

        function stopDrag() {
            if (!isDragging || !activeElement) return;
            
            isDragging = false;
            activeElement.style.cursor = "grab";
            activeElement = null;
        }
    }); */
