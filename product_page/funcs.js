jQuery(document).ready(function () {

    // Scroll to element if go to pages
    if(jQuery('div').is('.CmCrossTitleBl')){
        var pageNum = jQuery('.CmCrossTitleBl').data('page');
        if(pageNum != ''){
            jQuery('html, body').animate({
                scrollTop: jQuery(".CmCrossTitleBl").offset().top
            }, 500);
        }
    }

    // Scroll to analogs if product not available
    // if(jQuery('.CmAnalogList').data('prodval') == 'N'){
    //     jQuery('html, body').animate({
    //         'scrollTop':   jQuery('.CmAnalogList').offset().top
    //     }, 500);
    // }

    //Show more meta name
    jQuery('body').on('click', '.CmShowMoreMeta', function(){
        jQuery(this).prev('.ulMetaName').find('.cmMetaName_2').show();
        var hide = jQuery(this).data('hide');
        jQuery(this).html(hide).addClass('CmHideMoreMeta').removeClass('CmShowMoreMeta');
    });
    jQuery('body').on('click', '.CmHideMoreMeta', function(){
        jQuery(this).prev('.ulMetaName').find('.cmMetaName_2').hide();
        var show = jQuery(this).data('show');
        jQuery(this).html(show).addClass('CmShowMoreMeta').removeClass('CmHideMoreMeta');

    });

    jQuery('.CmIfrBut').click(function(){
        let eu = jQuery(this).data('eu');
        let rep = /http:/;
        let newUrl = eu.replace(rep, 'https:');
        jQuery('.fxOverlay').css('display','flex');
        jQuery('.fxCont').css({width:'100%', height:'90%'}).append('<div class="fxClose"></div><iframe class="CmIframe" src="'+newUrl+'" style="width:100%; height:100%" frameborder="0" seamless></iframe>');
    });

    //Product Prices block (Webservices AJAX updated)
    function CmProductWsIdentity(){
        var parts = String(window.location.pathname || '').split('/').filter(Boolean);
        var productPos = parts.indexOf('product');
        var brand = '';
        var artnum = '';
        if(productPos >= 0){
            brand = parts[productPos + 1] || '';
            artnum = parts[productPos + 2] || '';
        }
        try{brand = decodeURIComponent(brand).replace(/_/g, ' ');}catch(e){}
        try{artnum = decodeURIComponent(artnum).replace(/_/g, ' ');}catch(e){}
        return {brand:brand, artnum:artnum};
    }
    function CmProductShowWsError(html, escapeHtml){
        var id = CmProductWsIdentity();
        if(window.CmShowApiResultErrorPopup_x){
            window.CmShowApiResultErrorPopup_x(html, id.brand, id.artnum, escapeHtml);
        }else if(window.console && console.warn){
            console.warn('Webservice AJAX error', id.brand, id.artnum, html);
        }
    }
	var WsAct = parseInt(jQuery('.blockProdPrice').data('wsact'));
	if(WsAct){
		jQuery('.blockProdPrice').find('.CmWsLoadBar').show();
        jQuery.ajax({url:window.location.href, type:'POST', dataType:'html', data:{CarModAjaxProductPrices:'Y', SearchWS:'Y'}})
            .done(function(Result){
                var aResult = String(Result || '').split('|CmWsErrors|');
                var priceHtml;
                jQuery('.blockProdPrice').find('.CmWsLoadBar').hide();
                if(aResult.length>1){
                    CmProductShowWsError(aResult.shift(), false);
                    priceHtml = aResult.join('|CmWsErrors|');
                    if(jQuery.trim(priceHtml) !== ''){
                        jQuery('.blockProdPrice').html(priceHtml);
                    }
                }else if(jQuery.trim(Result) !== ''){
                    jQuery('.blockProdPrice').html(Result);//Update Prices block
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                    jQuery('.blockProdPrice').find('.CmWsLoadBar').hide();
                    CmProductShowWsError((jqXHR.responseText || '')+' ['+textStatus+'] '+errorThrown, true);
                });
    }

    ////////IMAGE BLOCK

    //SHOW PICTURES ON HOVER IF >4
    // jQuery(".blSmallFoto").hover(function(e){
    //     e.stopPropagation();
    //     jQuery(this).css('height', 'auto');
    //     jQuery(this).find('.CmHideBlSmalFoto').hide();
    // },function(e){
    //     e.stopPropagation();
    //     jQuery(this).css('height', '60px');
    //     jQuery(this).find('.CmHideBlSmalFoto').show();
    // })
    if(jQuery(".blSmallFoto").data('smfoto')==="Y"){
        const smFotoH = jQuery('.cmChangeImg ').height()+10;
        const mainBlockH = jQuery('.innBlockFoto').height();
        jQuery('.innBlockFoto').height(mainBlockH)+smFotoH;
        // console.log(smFotoH);
        jQuery(".blSmallFoto").hover(function(){
            let elBox = jQuery(this),
            curHeight = elBox.height(),
            autoHeight = elBox.css({height: 'auto', position: 'absolute', bottom: '0px'}).height();
            jQuery(this).find('.CmHideBlSmalFoto').hide();
            elBox.height(curHeight).stop(true, true).animate({height: autoHeight}, 200, "linear");
        },function(){
            jQuery(this).animate({height: '68px'}, 200, "linear");
            jQuery(this).find('.CmHideBlSmalFoto').show();
        })
    }



    // CHANGE SMALL IMAGE TO BIG
    /* jQuery('body').on("click", ".cmChangeImg", function(){
        var smallfoto = jQuery(this).html();
        jQuery('.CmImageToPopup').html(smallfoto);

    });
    //IMAGE TO POPUP
    jQuery('body').on("click", ".CmImageToPopup", function(){
        let imType = jQuery(this).find('.CmSchemBlockWrap').data('imgtype');
        let prodIm = jQuery(this).html();
        let imgWid = jQuery(this).find('img').data('width');
        let imgHei = jQuery(this).find('img').data('height');
        let windHeight = document.body.clientHeight;
        console.log(imgHei+'/'+windHeight);
        jQuery('.fxOverlay').css('display','flex');
        jQuery('.fxCont').html('<div class="fxClose"></div>'+prodIm);
        if(imType == 'scheme' && imgHei > windHeight){
            jQuery('.fxCont').height(windHeight-80);
        }
    }); */
    ///////////

    // Open props block if OE without price
    jQuery('body').on('click', '.CmShowHiddSpecs', function(){
        jQuery('.CmPropsInnerBlock').removeClass('CmProductSummaryInner-compact').css({height: 'auto', overflow: 'visible'});
        jQuery(this).hide();
    });

    // AGENCY BLOCKS
    jQuery('.cmAgencyText').click(function (){
        jQuery('.fxOverlay').css('display','flex');
        jQuery('.fxCont').html(function(){
            var AgenTab = jQuery('.hideBlockAdr').html();
            return '<div class="cmBlockAddr">'+AgenTab+'</div>';
        });
    });

    //MORE PROPERTIES
    jQuery(".CmMorePropBut").click(function(){
        jQuery('.CmPropWrap').removeClass('CmPropTabHeight');
        jQuery(this).hide();
    });

        if(jQuery('.cm_Delivtd').data('suplstock')===''){
           jQuery('.CmListPrDelivery').css('borderRight','unset');
        }

    // Prod info tab active on mobile
    // if(jQuery(window).width()<=960){
    //     jQuery('.tabPartUse').removeClass('activeSecTab CmColorBr CmColorBg');
    //     jQuery('.tabPartUse').find('.cmSvgImg').css('fill','#808080');
    // }
	
	

});
