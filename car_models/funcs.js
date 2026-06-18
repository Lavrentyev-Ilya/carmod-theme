"use strict";

document.addEventListener("DOMContentLoaded", function(){
	lazyBackground("ModBox", "bg");
});

jQuery(document).ready(function() { 

    const _contentRows = jQuery('.ModBox');
    // Filter by letter
    // const letButton = document.querySelectorAll('.CmModFirstLett');

    // for (let i = 0; i < letButton.length; i++) {
    //     const element = array[i];

    // }

    jQuery('body').on('click', '.CmModFirstLett', function(){
        jQuery('.CmActFB').removeClass('CmFtColor CmColor-fff');
        const lett = jQuery(this).text();
        jQuery('.CmModTitleN').each(function() {
            const firstLet = jQuery(this).text().substr(0,1);
            if(firstLet === lett){
                $(this).parent().parent().removeClass('CmHideLet');
            }else{
                $(this).parent().parent().addClass('CmHideLet');
            }
        });
        jQuery('.CmModFirstLett').each(function () {
            if(jQuery(this).text() !== lett){
                jQuery(this).removeClass('CmFtColor CmColor-fff');
            }else{
                jQuery(this).addClass('CmFtColor CmColor-fff');
            }
        });
        jQuery('.CmModelTitl').each(function(){
            const firModLet = jQuery(this).text().substr(0,1);
            if(firModLet !== lett){
                jQuery(this).hide();
            }else{
                jQuery(this).show();
            }
            jQuery('.CmShowNames').hide();
        });


    });
    jQuery('.CmActFB').on('click', function () {
        jQuery(this).addClass('CmFtColor CmColor-fff');
        jQuery('.ModBox').removeClass('CmHideLet');
        jQuery('.CmModFirstLett').removeClass('CmFtColor CmColor-fff');
        jQuery('.CmNFiltItem').show();
        jQuery('.CmNFiltItem2').hide();
        jQuery('.CmShowNames').show();
    });



    //Filter by name
    jQuery('body').on('click', '.CmModelTitl', function(){
        jQuery('.CmAllItemBut').removeClass('CmAllItemButActive CmColor-fff');
        const titleTxt = jQuery(this).text();
        jQuery('.CmModelTitl').each(function(){
            if(jQuery(this).text() != titleTxt){
                jQuery(this).removeClass('CmActiveFilItem');
            }else{
                jQuery(this).addClass('CmActiveFilItem');
            }
        });
        jQuery('.ModBox').each(function(){
            const modTitle = jQuery(this).find('.CmModTitleN').text();
            if(modTitle!=titleTxt){
                jQuery(this).hide();
            }else{
                jQuery(this).fadeIn(200);
            }
        });
        setTimeout(() => jQuery('.CmAllItemBut').fadeIn(), 500);
        jQuery('.CmAllItemBut').on('click', function(){
            jQuery('.ModBox').show();
            jQuery('.CmModelTitl').removeClass('CmActiveFilItem');
            jQuery(this).addClass('CmAllItemButActive CmColor-fff');
            setTimeout(() => {
                jQuery(this).fadeOut();
            }, 500);
        });
    });

   /*  jQuery('.ModBox').mouseover(function(){
        jQuery(this).find('.ModName').addClass('CmColorBg');
    });
    jQuery('.ModBox').mouseleave(function(){
        jQuery(this).find('.ModName').removeClass('CmColorBg');
    }); */

    jQuery('body').on('click', '.CmShowNames', function(){
        const dHide = this.getAttribute('data-hide');
        this.textContent = dHide;
        this.classList.remove('CmShowNames');
        this.classList.add('CmHideNames');
        jQuery('.CmNFiltItem2').show();
    });
    jQuery('body').on('click', '.CmHideNames', function(){
        const dShow = this.getAttribute('data-show');
        this.textContent = dShow;
        this.classList.remove('CmHideNames');
        this.classList.add('CmShowNames');
        jQuery('.CmNFiltItem2').hide();
    });

    //Filter Years
	$('body').on('click', '.CmYearGrBl', function(){
		$('.CmYearItem').fadeIn(50);
	});
	$(document).mousedown(function (e){
		var div = $(".CmYearItem");
		if (!div.is(e.target) && div.has(e.target).length === 0) {
			$('.CmYearItem').fadeOut(50);
		}
	});
	
/* 	
    jQuery('body').on('click', '.CmYearGrBl', function(e){
        e.preventDefault();
        // Add the correct active class
        if(jQuery(this).hasClass('CmYearActive')) {
            // Remove active classes
            jQuery('.CmYearGrBl').removeClass('CmColorBg').find('.fYGroupe').removeClass('CmYearActive');
            jQuery('.CmYearGrBl').find('.CmYArrSvg').removeClass('CmYearSvg-fff');
        } else {
            // Remove active classes
            jQuery('.CmYearGrBl').removeClass('CmColorBg').find('.fYGroupe').removeClass('CmYearActive');
            jQuery('.CmYearGrBl').find('.CmYArrSvg').removeClass('CmYearSvg-fff');

            // Add the active class
            jQuery(this).addClass('CmColorBg').find('.fYGroupe').addClass('CmYearActive');
            jQuery(this).find('.CmYArrSvg').addClass('CmYearSvg-fff');
        }

        // Show the content
        const content = jQuery(this).find('.CmYearItem');
        content.slideToggle(100);
        jQuery('.CmYearGrBl .CmYearItem').not(content).slideUp('fast');
    }); */

    let nameM = new Array();
    const aModName = new Set(document.getElementsByClassName('CmModelTitl'));

    jQuery('body').on('click', '#yearBox .fYear', function(e){
        const yearTxt = jQuery(this).find('.CmYearTxt').text();
        jQuery('.fYGroupe').each(function () {
            if(jQuery(this).hasClass('actliveGroup')){
               // const yearGr = jQuery(this).parent().find('.CmYearItem').data('yearg');
                //jQuery(this).text(yearGr).removeClass('actliveGroup');
            }
        })
        setTimeout(() =>
            jQuery(this).parent().slideUp(100),
            jQuery(this).parent().parent().find('.fYGroupe').text(yearTxt).addClass('actliveGroup')
            , 500
        );

        jQuery(this).addClass('CmFtColor CmColor-fff').find('.CmYearSvg').addClass('CmYearSvg-fff');
        jQuery('.fYear').not(jQuery(this)).removeClass('CmFtColor CmColor-fff').find('.CmYearSvg').removeClass('CmYearSvg-fff');
        e.stopPropagation();
		if ($(this).attr('fytxt') && $(this).attr('fytxt').trim() !== '') {
			var fytxt = $(this).attr('fytxt');
			$('.ModBox').removeClass("hideYear").addClass("litShow activeBox");
			$('.fYGroupe').html(fytxt);
		}else{
			const curYear = jQuery(this).find('.CmYearTxt').html();
			jQuery('.fByYearSelected').html(' - '+curYear+' &#9660;');
			_contentRows.each(function (i) {
				const yfrom = jQuery(this).data('yfrom').toString();
				const yto = jQuery(this).data('yto').toString();
				if(curYear < yfrom  || (curYear > yto && yto != 0)){
					jQuery(this).addClass("hideYear").removeClass("litShow");
				}else{
					jQuery(this).removeClass("hideYear").addClass("litShow");
					jQuery(this).addClass('activeBox');
				}
			});
		}
        jQuery('.activeBox').each(function () {
            nameM.push(jQuery(this).find('.CmModTitleN').text());
        });
        const unName = new Set(nameM);
        console.log(`${unName} / ${aModName}`);
    });

	/* Actual Selection AJAX */
    jQuery('#CmContent').on('click', '.sliderTg', function(){
        var selactual = jQuery(this).attr("selactual").toString();
        if(selactual!=''){
        LoadingToggle();
            jQuery.post(window.location.href, {CarModAjax:'Y', All:selactual}, function(Result){
                jQuery('#CmAjaxBox').html(Result);
                LoadingToggle();
            });
        }
    });

    jQuery('#fByYearSel').on('click', function(){
        jQuery('#yearBox').toggle(0);
    });

    /* Type Selection PopUp */
    var typPopup = jQuery('.boxMod').data('typespopup');
    if(typPopup==1){
        jQuery("#CmContent").on("click",".ModBox", function(e){
            e.preventDefault();
            jQuery('.fxOverlay').css('display', 'flex');
            jQuery('.fxCont').html('<div class="CmSchLoadWrap" style="display:block; top:0; left:0;"><div class="CmSchLoading" style="width:65px; height:50px;"><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div></div></div>');
            jQuery.post(jQuery(this).attr('href'), {CarModAjax:'Y',HideStat:'Y'}, function(data){
                jQuery('.fxCont').html('<div class="fxClose"></div>'+data);
                jQuery('.fxCont').css({width:'1080px', minHeight:'440px'});
				onAjaxComplete();
            });
        });
    }
});
