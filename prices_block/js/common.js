(function($){
    if(!$){return;}

    var CmCartTextResizeTimer = null;

    function CmPriceReqFetch(url, data){
        if(typeof window.ReqFetch === 'function'){
            return window.ReqFetch(url, data);
        }
        return fetch(url, {
            method: 'POST',
            body: data,
            headers: new Headers({'Content-Type':'application/x-www-form-urlencoded'})
        }).then(function(response){
            return response.text();
        }).catch(function(error){
            console.log(error);
            return '';
        });
    }

    function CmQtyParseInt(value, fallback){
        value = String(value === undefined || value === null ? '' : value).replace(/[^\d]/g, '');
        var number = +value;
        return isNaN(number) ? fallback : number;
    }

    function CmQtyUseMobileSingleMode(){
        if(window.matchMedia){
            return window.matchMedia('(max-width: 520px)').matches;
        }
        return $(window).width() <= 520;
    }

    function CmQtyRules(input){
        var $input = $(input);
        var inputNode = $input[0];
        var min = CmQtyParseInt(inputNode ? inputNode.getAttribute('data-minimalqnt') : $input.data('minimalqnt'), 1);
        var multiplicity = CmQtyParseInt(inputNode ? inputNode.getAttribute('data-multiplicity') : $input.data('multiplicity'), 1);
        var max = CmQtyParseInt(inputNode ? inputNode.getAttribute('data-maxaval') : $input.data('maxaval'), 0);
        if(min < 1){min = 1;}
        if(multiplicity < 1){multiplicity = 1;}
        var step = multiplicity > 1 ? multiplicity : 1;
        var minAllowed = min;
        if(multiplicity > 1){
            minAllowed = Math.max(minAllowed, multiplicity);
            minAllowed = Math.ceil(minAllowed / multiplicity) * multiplicity;
        }
        return {min:minAllowed, step:step, max:max, multiplicity:multiplicity};
    }

    function CmQtyFit(value, rules, direction){
        value = CmQtyParseInt(value, rules.min);
        if(value < rules.min){value = rules.min;}
        if(rules.multiplicity > 1){
            value = direction === 'down'
                ? Math.floor(value / rules.multiplicity) * rules.multiplicity
                : Math.ceil(value / rules.multiplicity) * rules.multiplicity;
            if(value < rules.min){value = rules.min;}
        }
        if(rules.max > 0 && value > rules.max){
            value = rules.max;
            if(rules.multiplicity > 1){
                value = Math.floor(value / rules.multiplicity) * rules.multiplicity;
                if(value < rules.min){value = rules.max;}
            }
        }
        return value < 1 ? 1 : value;
    }

    function CmQtyNormalizeInput(input, direction){
        if(CmQtyUseMobileSingleMode()){
            $(input).val(1);
            return 1;
        }
        var rules = CmQtyRules(input);
        var value = CmQtyFit($(input).val(), rules, direction || 'up');
        $(input).val(value);
        return value;
    }

    window.CmNormalizeCartQty = function(input){
        return CmQtyNormalizeInput(input, 'up');
    };

    function CmQtyNormalizeAll(context){
        $(context || document).find('.cm_countRes').each(function(){
            CmQtyNormalizeInput(this, 'up');
        });
    }

    function CmCartTextUpdate(wrap){
        if(!wrap){return;}
        var $wrap = $(wrap);
        $wrap.removeClass('CmPriceQuantWrap-showCartText');
        if($wrap.closest('.CmProductPriceList-intable').length || !$wrap.find('.CmProductPriceCartText').length){
            return;
        }
        var width = wrap.getBoundingClientRect ? wrap.getBoundingClientRect().width : $(wrap).width();
        var qty = wrap.querySelector ? wrap.querySelector('.CmQuantPriceBlock, .blockQty') : null;
        var price = wrap.querySelector ? wrap.querySelector('.CmPriceNum') : null;
        var qtyWidth = qty && $(qty).is(':visible') && qty.getBoundingClientRect ? qty.getBoundingClientRect().width : 0;
        var priceWidth = price && price.getBoundingClientRect ? price.getBoundingClientRect().width : 0;
        var style = window.getComputedStyle ? window.getComputedStyle(wrap) : null;
        var gap = style ? CmQtyParseInt(style.columnGap || style.gap, 6) : 6;
        var requiredWidth = qtyWidth + priceWidth + 104 + (gap * 2) + 24;
        var showText = width >= requiredWidth && !CmQtyUseMobileSingleMode();
        $wrap.toggleClass('CmPriceQuantWrap-showCartText', !!showText);
    }

    function CmCartTextObserve(context){
        var $context = $(context || document);
        var $wraps = $context.is('.CmPriceQuantWrap') ? $context : $context.find('.CmPriceQuantWrap');
        $wraps.each(function(){
            CmCartTextUpdate(this);
        });
    }

    function CmCartTextQueueObserve(context){
        if(CmCartTextResizeTimer){
            clearTimeout(CmCartTextResizeTimer);
        }
        CmCartTextResizeTimer = setTimeout(function(){
            CmCartTextObserve(context || document);
            CmCartTextResizeTimer = null;
        }, 300);
    }

    function CmProductPriceCartNudge(source, direction){
        var $wrap = $(source).closest('.CmPriceQuantWrap');
        var $cart = $wrap.find('.CmProductPriceCartButton').first();
        if(!$cart.length){
            $cart = $(source).closest('.CmProductPriceRow, .CmProductPriceBox, .CmProductMorePricesPanel').find('.CmProductPriceCartButton').first();
        }
        if(!$cart.length){return;}
        var cls = direction == 'down' ? 'CmCartNudgeDown' : 'CmCartNudgeUp';
        var cart = $cart[0];
        $cart.removeClass('CmCartNudgeUp CmCartNudgeDown');
        if(cart && cart.offsetWidth){
            cart.offsetWidth;
        }
        $cart.addClass(cls);
        clearTimeout(cart.CmCartNudgeTimer);
        cart.CmCartNudgeTimer = setTimeout(function(){
            $cart.removeClass(cls);
            cart.CmCartNudgeTimer = null;
        }, 260);
    }

    window.CmProductPriceCartNudge = CmProductPriceCartNudge;

    function CmQtyQueueNormalize(input){
        var rules = CmQtyRules(input);
        if(rules.multiplicity <= 1 && rules.min <= 1){return;}
        if(input.CmQtyNormalizeTimer){
            clearTimeout(input.CmQtyNormalizeTimer);
        }
        input.CmQtyNormalizeTimer = setTimeout(function(){
            CmQtyNormalizeInput(input, 'up');
            input.CmQtyNormalizeTimer = null;
        }, 250);
    }

    function CmQtyClean(input){
        var value = String(input.value || '').replace(/[^\d]/g, '');
        var maxav = CmQtyParseInt($(input).data('maxaval'), 0);
        if(maxav > 0 && +value > maxav){value = String(maxav);}
        if(value === '' || +value < 1){value = '1';}
        input.value = value;
        return value;
    }

    function CmToCartMailOrder(elem, addFolder, e){
        var $elem = $(elem);
        var Brand = $elem.data('brand');
        var Article = $elem.data('artnum');
        var ModuleDir = $elem.data('moduledir');
        var DataLang = $elem.data('lang');
        var Link = $elem.data('link');
        var pData = 'Brand='+Brand+'&Article='+Article+'&Lang='+DataLang+'&ModDir='+ModuleDir+'&Link='+Link;
        e.preventDefault();
        $('.fxOverlay').css('display', 'flex');
        $('.fxCont').addClass('CmAskPriceLoadingShell').html('<div id="tempSaver"></div><div class="CmAskPricePreload" aria-hidden="true"><div class="CmAskPricePreloadDots"><span class="CmColorBg"></span><span class="CmColorBg"></span><span class="CmColorBg"></span></div></div>');

        CmPriceReqFetch('/'+ModuleDir+'/add/'+addFolder+'/controller.php', pData)
            .then(function(result){
                $('.fxCont').removeClass('CmAskPriceLoadingShell').html('<div class="fxClose"></div>'+result);
            });
    }

    $(function(){
        $(document)
            .off('click.CmPricesBlock', '.CmMorePrices')
            .on('click.CmPricesBlock', '.CmMorePrices', function(e){
                if($(e.target).closest('.morePricestab').length){
                    return;
                }
                var morePricesPanel = $(this).find('.morePricestab');
                $('.morePricestab').not(morePricesPanel).stop(true, true).slideUp(100);
                if(!morePricesPanel.is(':visible')){
                    morePricesPanel.stop(true, true).slideDown(200);
                }
            })
            .off('click.CmPricesBlock', '.CmMorePriceBlClose')
            .on('click.CmPricesBlock', '.CmMorePriceBlClose', function(e){
                e.stopPropagation();
                $('.morePricestab').slideUp(200);
                $('.CmMoreHidePr').show();
            })
            .off('click.CmPricesBlock', '.CmMoreHidePr')
            .on('click.CmPricesBlock', '.CmMoreHidePr', function(){
                $('.CmWrapBlMorePrice').removeClass('CmWrapBlHeight');
                $(this).hide();
            })
            .off('click.CmPricesBlock', '.cm_countButM')
            .on('click.CmPricesBlock', '.cm_countButM', function(e){
                e.stopImmediatePropagation();
                var input = $(this).parent().find('.cm_countRes');
                var rules = CmQtyRules(input);
                var count = CmQtyFit(input.val(), rules, 'up') - rules.step;
                input.val(CmQtyFit(count, rules, 'down')).change();
                CmProductPriceCartNudge(this, 'down');
                return false;
            })
            .off('click.CmPricesBlock', '.cm_countButP')
            .on('click.CmPricesBlock', '.cm_countButP', function(e){
                e.stopImmediatePropagation();
                var input = $(this).parent().find('.cm_countRes');
                var rules = CmQtyRules(input);
                var count = CmQtyFit(input.val(), rules, 'down') + rules.step;
                input.val(CmQtyFit(count, rules, 'up')).change();
                CmProductPriceCartNudge(this, 'up');
                return false;
            })
            .off('keyup.CmPricesBlock', '.cm_countRes')
            .on('keyup.CmPricesBlock', '.cm_countRes', function(){
                CmQtyClean(this);
                CmQtyQueueNormalize(this);
            })
            .off('input.CmPricesBlock', '.cm_countRes')
            .on('input.CmPricesBlock', '.cm_countRes', function(){
                CmQtyClean(this);
                CmQtyQueueNormalize(this);
            })
            .off('change.CmPricesBlock blur.CmPricesBlock focusout.CmPricesBlock', '.cm_countRes')
            .on('change.CmPricesBlock blur.CmPricesBlock focusout.CmPricesBlock', '.cm_countRes', function(){
                CmQtyNormalizeInput(this, 'up');
            })
            .off('click.CmPricesBlock', '.CmShowMorePrice')
            .on('click.CmPricesBlock', '.CmShowMorePrice', function(){
                $(this).prev('.CmTablePriceWrap').find('.CmTablePriceValueRow_2').each(function(){
                    var displayType = this.tagName && this.tagName.toLowerCase() === 'tr' ? 'table-row' : 'flex';
                    $(this).show().css('display', displayType);
                });
                $(this).html($(this).data('hide')).addClass('CmHideMorePrice').removeClass('CmShowMorePrice');
            })
            .off('click.CmPricesBlock', '.CmHideMorePrice')
            .on('click.CmPricesBlock', '.CmHideMorePrice', function(){
                $(this).prev('.CmTablePriceWrap').find('.CmTablePriceValueRow_2').hide();
                $(this).html($(this).data('show')).addClass('CmShowMorePrice').removeClass('CmHideMorePrice');
            })
            .off('click.CmPricesBlock', '.ListAskPrice')
            .on('click.CmPricesBlock', '.ListAskPrice', function(e){
                CmToCartMailOrder(this, 'askprice', e);
            })
            .off('click.CmPricesBlock', '.CmMailOrder')
            .on('click.CmPricesBlock', '.CmMailOrder', function(e){
                CmToCartMailOrder(this, 'mail_order', e);
            });

        CmQtyNormalizeAll(document);
        CmCartTextObserve(document);
        $(window).off('resize.CmPricesBlockCartText');
        $(window).on('resize.CmPricesBlockCartText', function(){
            CmCartTextQueueObserve(document);
        });
        $(document).ajaxComplete(function(){
            CmQtyNormalizeAll(document);
            CmCartTextObserve(document);
        });
    });
})(window.jQuery);
