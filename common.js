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
    var CmWsRunId = 0;
    var CmWsXhr = null;
    var CmWsStopRequested = false;
    function CmWsPageDir(){
        var match = String(window.location.pathname || '').match(/^\/([^\/]+)/);
        return match ? match[1] : '';
    }
    function CmWsData(elem, name, deep){
        var child;
        var value;
        if(!elem){return '';}
        value = elem.getAttribute('data-' + name) || '';
        if(value || !deep){return value;}
        if(name === 'dir'){
            child = elem.querySelector('[data-dir], [data-moduledir]');
            value = child ? (child.getAttribute('data-dir') || child.getAttribute('data-moduledir') || '') : '';
            return value || CmWsPageDir();
        }
        child = elem.querySelector('[data-' + name + ']');
        return child ? (child.getAttribute('data-' + name) || '') : '';
    }
    function CmWsEnsureLoadBar(elem){
        var bar = elem.querySelector('.CmWsLoadBar');
        if(!bar){
            bar = document.createElement('div');
            bar.className = 'CmWsLoadBar';
            bar.innerHTML = '<div class="CmWsLBCh"></div><div class="CmWsLBCh"></div>';
            elem.appendChild(bar);
        }
        return bar;
    }
    function CmWsStartLoading(elem){
        if(!elem){return;}
        elem.classList.add('CmWsLoading');
        CmWsEnsureLoadBar(elem).style.display = 'block';
    }
    function CmWsStopLoading(elem){
        if(!elem){return;}
        elem.classList.remove('CmWsLoading');
        var bar = elem.querySelector('.CmWsLoadBar');
        if(bar){bar.style.display = 'none';}
    }
    function WebServiceListBlocks(){
        var canUseNestedDebugData = CarModCanShowAdminAjaxPopup();
        CmWsRunId++;
        CmWsStopRequested = false;
        if(CmWsXhr && CmWsXhr.readyState !== 4){
            CmWsXhr.abort();
        }
        ProdListBlocks = [];
        WsPpNum=0;
        document.querySelectorAll('body .rightBlock, body .CmListPrTab_c, body .WsDataTb_x').forEach(function(block){
            var hasOwnWsData = (CmWsData(block, 'artnum') !== '' && CmWsData(block, 'brand') !== '');
            var canReadNestedWsData = canUseNestedDebugData && block.getAttribute('data-wsajax') === '1';
            if(
                hasOwnWsData ||
                (canReadNestedWsData && CmWsData(block, 'artnum', true) !== '' && CmWsData(block, 'brand', true) !== '')
            ){
                ProdListBlocks.push(block);
            }
        });
        return ProdListBlocks;
    }
    WebServiceListBlocks();

    if(ProdListBlocks.length>0){
        WsNextProdPrices();
    }

    function CmWsPriceView(ePrlb){
        var PriceView = CmWsData(ePrlb, 'price-view');
        if(PriceView){return PriceView;}
        if(ePrlb.classList.contains('WsDataTb_x') || ePrlb.classList.contains('CmPricesBlockTableHost')){
            return 'intable';
        }
        if(ePrlb.classList.contains('CmListPrTab_c')){
            return 'card';
        }
        return 'card';
    }
    function CmWsPostString(data){
        var post = [];
        for(var key in data){
            if(data.hasOwnProperty(key)){
                post.push(encodeURIComponent(key) + '=' + encodeURIComponent(data[key]).replace(/%20/g, '+'));
            }
        }
        return post.join('&');
    }
    function CmWsFinishCurrent(RunId, waitForAdmin){
        var next = function(action){
            if(action && action.stopAjax){
                CmWsStopAjaxQueue();
                return;
            }
            if(CmWsStopRequested){return;}
            if(RunId !== CmWsRunId){return;}
            WsPpNum++;
            WsNextProdPrices(RunId);
        };
        if(waitForAdmin && typeof waitForAdmin.then === 'function'){
            waitForAdmin.then(next, next);
        }else{
            next();
        }
    }

    function WsNextProdPrices(RunId){
        if(typeof RunId === 'undefined'){
            RunId = CmWsRunId;
        }
        if(CmWsStopRequested || RunId !== CmWsRunId){
            return;
        }
        var ePrlb = ProdListBlocks[WsPpNum];
        if(ePrlb){
            CmWsStartLoading(ePrlb);
            var Dir = CmWsData(ePrlb, 'dir', true);
            var ArtNum = CmWsData(ePrlb, 'artnum', true);
            var Brand = CmWsData(ePrlb, 'brand', true);
            var PriceView = CmWsPriceView(ePrlb);
            // console.log(Dir+'/ '+ArtNum+'/ '+Brand);
//            var pData = 'CarModAjaxProductPrices=Y&SearchWS=Y&ArtNum='+ArtNum+'&Brand='+Brand+'&Sets=List';
//            ReqFetch('/'+Dir+'/', pData)
//                .then(result => {
//                    jQuery(ePrlb).html(result);
//                    WsPpNum++;
//                    WsNextProdPrices();
//                });
            var postData = CmWsPostString({
                CarModAjaxProductPrices:'Y',
                SearchWS:'Y',
                ArtNum:ArtNum,
                Brand:Brand,
                Sets:'List',
                CmPriceView:PriceView,
                CmPriceContext:'products_list'
            });

            CmWsXhr = new XMLHttpRequest();
            CmWsXhr.open('POST', '/' + Dir + '/', true);
            CmWsXhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            CmWsXhr.onreadystatechange = function(){
                var waitForAdmin = null;
                if(CmWsXhr.readyState !== 4){return;}
                if(RunId !== CmWsRunId){return;}
                CmWsStopLoading(ePrlb);
                if(CmWsXhr.status >= 200 && CmWsXhr.status < 300){
                    var Result = CmWsXhr.responseText;
                    var aResult = Result.split('|CmWsErrors|');
                    var hadWsErrorMarker = false;
                    var wsErrorHtml = '';
                    if(aResult.length > 1){
                        hadWsErrorMarker = true;
                        wsErrorHtml = aResult.shift();
                        if(String(wsErrorHtml || '').trim() !== ''){
                            waitForAdmin = CarModShowApiResultErrorPopup(wsErrorHtml, Brand, ArtNum, false);
                        }
                        Result = aResult.join('|CmWsErrors|');
                    }
                    if(String(Result || '').trim() === ''){
                        if(!hadWsErrorMarker){
                            waitForAdmin = CarModShowApiResultErrorPopup(CarModUiText('emptyWebserviceResponse'), Brand, ArtNum, true);
                        }
                    }else if(CarModLooksLikeBrokenAjax(Result)){
                        waitForAdmin = CarModShowApiResultErrorPopup(Result, Brand, ArtNum, true);
                    }else{
                        var preparedWsHtml = CarModPrepareWsPriceHtml(Result);
                        if(preparedWsHtml.debug){
                            waitForAdmin = CarModShowApiResultErrorPopup(preparedWsHtml.debug, Brand, ArtNum, true);
                        }
                        if(preparedWsHtml.html !== null && (!preparedWsHtml.debug || waitForAdmin)){
                            ePrlb.innerHTML = preparedWsHtml.html;
                        }else if(!preparedWsHtml.debug){
                            waitForAdmin = CarModShowApiResultErrorPopup(Result, Brand, ArtNum, true);
                        }
                    }
                }else{
                    waitForAdmin = CarModShowApiResultErrorPopup(CarModUiText('httpStatus') + ': ' + CmWsXhr.status + '\n\n' + (CmWsXhr.responseText || ''), Brand, ArtNum, true);
                }
                CmWsFinishCurrent(RunId, waitForAdmin);
            };
            CmWsXhr.send(postData);
        }
    }
    function CmWsStopAjaxQueue(){
        CmWsStopRequested = true;
        CmWsRunId++;
        if(CmWsXhr && CmWsXhr.readyState !== 4){
            CmWsXhr.abort();
        }
        if(ProdListBlocks && ProdListBlocks.length){
            ProdListBlocks.forEach(CmWsStopLoading);
        }
        ProdListBlocks = [];
    }

    function CarModUrlObject(url) {
        return new URL(url, window.location.origin);
    }
    function CarModDeleteSearchParam(urlObj, key) {
        urlObj.searchParams.delete(key);
        urlObj.searchParams.delete(key + '[]');
    }
    function CarModDeleteSearchParams(urlObj, keys) {
        if (!Array.isArray(keys)) {
            keys = [keys];
        }
        keys.forEach(function (key) {
            CarModDeleteSearchParam(urlObj, key);
        });
    }
    function CarModGetSearchArray(urlObj, keys) {
        if (!Array.isArray(keys)) {
            keys = [keys];
        }
        var values = [];
        urlObj.searchParams.forEach(function (value, name) {
            var matched = false;
            keys.forEach(function (key) {
                if (name === key || name === key + '[]') {
                    matched = true;
                }
            });
            if (matched) {
                String(value).split(',').forEach(function (item) {
                    item = String(item).trim();
                    if (item !== '' && values.indexOf(item) === -1) {
                        values.push(item);
                    }
                });
            }
        });
        return values;
    }
    function CarModNormalizeSearchValue(key, value) {
        value = String(value).trim();
        if (key === 'b') {
            value = value.toLowerCase();
        }
        return value;
    }
    function CarModSetSearchArray(urlObj, key, values, mode) {
        CarModDeleteSearchParam(urlObj, key);
        values = values.map(function (value) {
            return CarModNormalizeSearchValue(key, value);
        }).filter(function (value, index, arr) {
            return value !== '' && arr.indexOf(value) === index;
        });
        if (mode === 'csv') {
            if (values.length > 0) {
                urlObj.searchParams.set(key, values.join(','));
            }
            return;
        }
        values.forEach(function (value) {
            if (value !== undefined && value !== null && value !== '') {
                urlObj.searchParams.append(key + '[]', value);
            }
        });
    }
    function CarModToggleSearchValue(urlObj, keys, storeKey, value, mode) {
        var values = CarModGetSearchArray(urlObj, keys).map(function (item) {
            return CarModNormalizeSearchValue(storeKey, item);
        });
        value = CarModNormalizeSearchValue(storeKey, value);
        if (values.indexOf(value) !== -1) {
            values = values.filter(function (item) { return item !== value; });
        } else {
            values.push(value);
        }
        CarModDeleteSearchParams(urlObj, keys);
        CarModSetSearchArray(urlObj, storeKey, values, mode);
        return values;
    }
    function CarModUrlToString(urlObj) {
        return urlObj.toString().replace(/%2C/gi, ',');
    }
    function CarModGetActiveOnly() {
        var only = '';
        document.querySelectorAll('#CmAjaxBox .CmTumButn').forEach(function (button) {
            if (button.classList.contains('CmTumPushed')) {
                only = button.getAttribute('data-tumb') || '';
            }
        });
        return only;
    }
    function CarModSetOnly(urlObj, only) {
        urlObj.searchParams.delete('Only');
        if (only && only !== 'All') {
            urlObj.searchParams.set('Only', only);
        }
    }
    function CarModBuildFilterUrl(action, value) {
        var nextUrl = CarModUrlObject(window.location.href);
        var currentProducts = CarModGetSearchArray(nextUrl, ['p', 'pr']);
        var currentBrands = CarModGetSearchArray(nextUrl, ['b', 'br']);
        nextUrl.hash = '';
        nextUrl.searchParams.delete('page');
        CarModDeleteSearchParams(nextUrl, ['p', 'pr']);
        CarModDeleteSearchParams(nextUrl, ['b', 'br']);
        CarModSetSearchArray(nextUrl, 'p', currentProducts, 'csv');
        CarModSetSearchArray(nextUrl, 'b', currentBrands, 'csv');
        CarModSetOnly(nextUrl, CarModGetActiveOnly());
        if (action === 'product') {
            CarModToggleSearchValue(nextUrl, ['p', 'pr'], 'p', value, 'csv');
            CarModDeleteSearchParams(nextUrl, ['b', 'br']);
            CarModDeleteSearchParam(nextUrl, 'cr');
        } else if (action === 'criteria') {
            CarModToggleSearchValue(nextUrl, ['cr'], 'cr', value);
        } else if (action === 'brand') {
            CarModToggleSearchValue(nextUrl, ['b', 'br'], 'b', value, 'csv');
        } else if (action === 'only') {
            CarModSetOnly(nextUrl, value);
        }
        return CarModUrlToString(nextUrl);
    }
    function CarModIsMobileFiltersView() {
        if (window.matchMedia) {
            return window.matchMedia('(max-width: 993px)').matches;
        }
        return (window.innerWidth || document.documentElement.clientWidth || 0) <= 993;
    }
    function CarModRemoveMobileFilterOverlay() {
        document.querySelectorAll('.CmBlockOverPage').forEach(function (overlay) {
            if (overlay.parentNode) {
                overlay.parentNode.removeChild(overlay);
            }
        });
    }
    function CarModCloseMobileFilters() {
        if (!CarModIsMobileFiltersView()) {
            return;
        }
        document.querySelectorAll('.left_fil').forEach(function (panel) {
            panel.classList.remove('CmMobileFiltersOpen');
            panel.style.right = '';
            panel.style.transition = '';
        });
        document.documentElement.classList.remove('CmMobileFiltersActive');
        if (document.body) {
            document.body.classList.remove('CmMobileFiltersActive');
        }
        CarModRemoveMobileFilterOverlay();
    }
    function CarModOpenMobileFilters() {
        var box;
        var panel;
        var overlay;
        if (!CarModIsMobileFiltersView()) {
            return;
        }
        box = CarModGetAjaxBox();
        panel = box ? box.querySelector('.left_fil') : null;
        if (!panel) {
            return;
        }
        CarModRemoveMobileFilterOverlay();
        overlay = document.createElement('div');
        overlay.className = 'CmBlockOverPage';
        document.body.appendChild(overlay);
        panel.style.right = '';
        panel.style.transition = '';
        panel.classList.add('CmMobileFiltersOpen');
        document.documentElement.classList.add('CmMobileFiltersActive');
        document.body.classList.add('CmMobileFiltersActive');
    }
    function CarModGetProductListScrollTop() {
        var Target = jQuery('.CmCrossTitleBl:visible');
        if (!Target.length) {
            Target = jQuery('.CmBrTitleSearchWrap:visible');
        }
        if (!Target.length) {
            Target = jQuery('#CmAjaxBox');
        }
        if (!Target.length) {
            return 0;
        }
        return Math.max(0, Target.offset().top - 5);
    }
    var CarModListAjaxXhr = null;
    var CarModListAjaxRunId = 0;
    function CarModGetAjaxBox() {
        return document.getElementById('CmAjaxBox');
    }
    function CarModClosestAjaxTarget(target, selector) {
        var box = CarModGetAjaxBox();
        var found;
        if (!box || !target) {
            return null;
        }
        if (target.nodeType !== 1) {
            target = target.parentNode;
        }
        found = target && target.closest ? target.closest(selector) : null;
        return (found && box.contains(found)) ? found : null;
    }
    function CarModData(target, key) {
        if (!target) {
            return '';
        }
        return target.getAttribute('data-' + key) || '';
    }
    function CarModStartAjaxLoading(scrollPx) {
        var loader = document.getElementById('Loading');
        if (loader && window.getComputedStyle(loader).display !== 'none') {
            return;
        }
        LoadingToggle('CmContent', scrollPx || 0);
    }
    function CarModStopAjaxLoading() {
        var loader = document.getElementById('Loading');
        if (loader && window.getComputedStyle(loader).display !== 'none') {
            LoadingToggle();
        }
    }
    document.addEventListener('click', function(e){
        var link = e.target && e.target.closest ? e.target.closest('a.CmPagLink, a.cm_moreProd') : null;
        var href;
        if(!link || e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey){return;}
        if(link.classList.contains('CmPageAct')){return;}
        if(link.target && link.target !== '_self'){return;}
        href = (link.getAttribute('href') || '').trim();
        if(href === '' || href === '#' || href.toLowerCase().indexOf('javascript:') === 0){return;}
        CarModStartAjaxLoading(CarModGetProductListScrollTop());
    }, false);
    function CarModEscapeHtml(value) {
        return String(value || '').replace(/[&<>"']/g, function (char) {
            return {'&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#039;'}[char];
        });
    }
    function CarModShowAdminAjaxPopup(html, title, escapeHtml) {
        var overlay = document.getElementById('AjaxPopup_x');
        var container = document.getElementById('AjaxPopupCont_x');
        var loader = document.getElementById('AjaxPopupLoad_x');
        var bodyHtml;
        if (!overlay || !container) {
            return false;
        }
        if (loader) {
            loader.style.display = 'none';
        }
        container.classList.remove('CmPricePopupCont');
        bodyHtml = escapeHtml ? '<pre style="white-space:pre-wrap; margin:0;">' + CarModEscapeHtml(html) + '</pre>' : String(html || '');
        container.innerHTML =
            '<div class="fxClose"></div>' +
            '<div style="min-width:min(760px, calc(100vw - 56px)); max-width:calc(100vw - 56px); max-height:calc(100vh - 90px); overflow:auto; box-sizing:border-box;">' +
                '<div style="font:700 15px/20px Arial, sans-serif; margin:0 0 12px 0; color:#b00020;">' + CarModEscapeHtml(title) + '</div>' +
                '<div style="font:12px/18px Arial, sans-serif; color:#222; text-align:left;">' + bodyHtml + '</div>' +
            '</div>';
        overlay.style.display = 'block';
        return true;
    }
    function CarModCanShowAdminAjaxPopup() {
        return !!(document.getElementById('AjaxPopup_x') && document.getElementById('AjaxPopupCont_x'));
    }
    function CarModUiText(key) {
        return window.CmApiResultErrorText[key];
    }
    function CarModApiErrorBodyHtml(html, escapeHtml) {
        var body = String(html || '');
        var parser;
        var doc;
        var hr;
        var parts = [];
        var node;
        if (typeof DOMParser !== 'undefined') {
            parser = new DOMParser();
            doc = parser.parseFromString(body, 'text/html');
            hr = doc.body.querySelector('hr');
            if (hr && hr.parentNode) {
                node = hr.nextSibling;
                while (node) {
                    parts.push(node.outerHTML || node.textContent || '');
                    node = node.nextSibling;
                }
                if (parts.join('').trim() !== '') {
                    body = parts.join('');
                }
            }
        }
        if (escapeHtml) {
            return '<pre style="white-space:pre-wrap; margin:0; font:13px/20px Consolas, monospace; color:#222;">' + CarModEscapeHtml(body).slice(0, 12000) + '</pre>';
        }
        return body;
    }
    var CmApiResultPopupQueue = [];
    var CmApiResultPopupActive = false;
    function CarModShowApiResultErrorPopup(html, brand, artnum, escapeHtml) {
        if (!CarModCanShowAdminAjaxPopup()) {
            return null;
        }
        return new Promise(function(resolve){
            CmApiResultPopupQueue.push({
                html:html,
                brand:brand,
                artnum:artnum,
                escapeHtml:escapeHtml,
                resolve:resolve
            });
            CmApiResultShowNextPopup();
        });
    }
    function CmApiResultShowNextPopup() {
        var popup;
        var wait;
        var finish = function(action){
            CmApiResultPopupActive = false;
            popup.resolve(action || {});
            if(action && action.stopAjax){
                CmWsStopAjaxQueue();
                while(CmApiResultPopupQueue.length){
                    CmApiResultPopupQueue.shift().resolve(action);
                }
                return;
            }
            window.setTimeout(CmApiResultShowNextPopup, 0);
        };
        if(CmApiResultPopupActive || !CmApiResultPopupQueue.length){
            return;
        }
        popup = CmApiResultPopupQueue.shift();
        CmApiResultPopupActive = true;
        wait = CarModRenderApiResultErrorPopup(popup.html, popup.brand, popup.artnum, popup.escapeHtml);
        if(wait && typeof wait.then === 'function'){
            wait.then(finish, finish);
        }else{
            finish({});
        }
    }
    function CarModRenderApiResultErrorPopup(html, brand, artnum, escapeHtml) {
        var overlay = document.getElementById('AjaxPopup_x');
        var container = document.getElementById('AjaxPopupCont_x');
        var loader = document.getElementById('AjaxPopupLoad_x');
        var titleText = CarModUiText('apiResultTitle');
        var adminOnly = CarModUiText('adminOnly');
        var closeText = CarModUiText('close');
        var stopText = CarModUiText('stopAjax');
        var actionButtonStyle = 'float:none!important; display:inline-flex!important; align-items:center!important; justify-content:center!important; min-height:32px!important; height:32px!important; padding:0 14px!important; margin:0!important; border:0!important; border-bottom:3px solid var(--cm-main,#fa6a00)!important; outline:0!important; appearance:none!important; -webkit-appearance:none!important; cursor:pointer!important; box-sizing:border-box!important; line-height:29px!important;';
        var bodyHtml;
        if (!overlay || !container) {
            return null;
        }
        if (loader) {
            loader.style.display = 'none';
        }
        container.classList.remove('CmPricePopupCont');
        container.classList.add('CmApiResultPopupCont');
        container.style.width = 'auto';
        container.style.minWidth = '300px';
        container.style.minHeight = '0';
        container.style.maxWidth = 'calc(100vw - 44px)';
        container.style.maxHeight = 'calc(100vh - 44px)';
        container.style.overflow = 'auto';
        container.style.boxSizing = 'border-box';
        container.style.padding = '20px 24px 18px';
        bodyHtml = CarModApiErrorBodyHtml(html, escapeHtml);
        return new Promise(function(resolve){
            var resolved = false;
            var closePopup = function(e, stopAjax){
                if (e) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                if (resolved) {return;}
                resolved = true;
                container.removeEventListener('click', clickHandler, true);
                overlay.removeEventListener('click', overlayHandler, true);
                overlay.style.display = 'none';
                container.innerHTML = '';
                container.classList.remove('CmApiResultPopupCont');
                container.style.width = '';
                container.style.minWidth = '';
                container.style.minHeight = '';
                container.style.maxWidth = '';
                container.style.maxHeight = '';
                container.style.overflow = '';
                container.style.boxSizing = '';
                container.style.padding = '';
                resolve({stopAjax: !!stopAjax});
            };
            var clickHandler = function(e){
                var stop = e.target && e.target.closest ? e.target.closest('.CmApiResultStop') : null;
                var close = e.target && e.target.closest ? e.target.closest('.CmApiResultClose') : null;
                if (stop && container.contains(stop)) {
                    closePopup(e, true);
                    return;
                }
                if (close && container.contains(close)) {
                    closePopup(e, false);
                }
            };
            var overlayHandler = function(e){
                if (e.target === overlay) {
                    closePopup(e, false);
                }
            };
            container.innerHTML =
                '<span class="CmFxClose" aria-hidden="true" style="display:none!important;width:0!important;height:0!important;min-width:0!important;min-height:0!important;padding:0!important;margin:0!important;border:0!important;box-shadow:none!important;overflow:hidden!important;"></span>' +
                '<button type="button" class="CmApiResultClose" aria-label="' + CarModEscapeHtml(closeText) + '" title="' + CarModEscapeHtml(closeText) + '" style="position:absolute; right:-14px; top:-14px; display:flex; align-items:center; justify-content:center; width:28px; height:28px; min-width:28px; min-height:28px; padding:0; margin:0; border:0; border-radius:999px; background:#fa6a00; color:#fff; box-shadow:0 2px 7px rgba(0,0,0,.35); cursor:pointer; font:400 24px/28px Arial,sans-serif; z-index:1000002;">&times;</button>' +
                '<div style="min-width:min(620px, calc(100vw - 56px)); max-width:calc(100vw - 56px); max-height:calc(100vh - 90px); overflow:auto; box-sizing:border-box; text-align:left; font:12px/16px Arial, sans-serif; color:#222;">' +
                    '<div style="font:700 15px/18px Arial, sans-serif; color:var(--cm-main,#fa6a00); margin:0 0 5px 0;">' + CarModEscapeHtml(titleText) + '</div>' +
                    '<div style="display:flex; align-items:baseline; justify-content:flex-start; gap:8px; margin:0 0 2px 0; color:#222; flex-wrap:wrap; font:12px/15px Arial, sans-serif;">' +
                        '<b style="font-weight:700;">' + CarModEscapeHtml(brand || '') + '</b>' +
                        '<span style="font-weight:400;">' + CarModEscapeHtml(artnum || '') + '</span>' +
                    '</div>' +
                    '<div style="color:#8a8a8a; font:11px/14px Arial, sans-serif; margin:0 0 8px 0;">' + CarModEscapeHtml(adminOnly) + '</div>' +
                    '<div style="border-top:1px solid #d6d6d6; margin:0 0 10px 0;"></div>' +
                    '<div style="max-height:48vh; overflow:auto; padding:0 2px 0 0; color:#222; font:13px/20px Arial, sans-serif;">' + bodyHtml + '</div>' +
                    '<div style="display:flex; align-items:center; justify-content:space-between; gap:14px; margin:18px 0 0 0;">' +
                        '<button type="button" class="gButDiv CmApiResultAction CmApiResultStop" style="' + actionButtonStyle + '">' + CarModEscapeHtml(stopText) + '</button>' +
                        '<button type="button" class="gButDiv CmApiResultAction CmApiResultClose" style="' + actionButtonStyle + '">' + CarModEscapeHtml(closeText) + '</button>' +
                    '</div>' +
                '</div>';
            container.addEventListener('click', clickHandler, true);
            overlay.addEventListener('click', overlayHandler, true);
            overlay.style.display = 'block';
        });
    }
    window.CmShowAdminAjaxPopup_x = CarModShowAdminAjaxPopup;
    window.CmShowApiResultErrorPopup_x = CarModShowApiResultErrorPopup;
    function CarModShowAjaxError(title, message, details) {
        if (!CarModShowAdminAjaxPopup(
            '<div style="margin-bottom:8px;">' + CarModEscapeHtml(message || '') + '</div>' +
            (details ? '<pre style="white-space:pre-wrap; margin:0; padding:10px; background:#f6f6f6; border:1px solid #ddd;">' + CarModEscapeHtml(details).slice(0, 8000) + '</pre>' : ''),
            title,
            false
        )) {
            if (window.console && console.warn) {
                console.warn(title, message || '', details || '');
            }
        }
    }
    function CarModLooksLikeBrokenAjax(html) {
        return /(<b>\s*(warning|notice|fatal error|parse error)|\bwarning:|\bnotice:|\bfatal error:|\bparse error:)/i.test(String(html || ''));
    }
    function CarModPrepareWsPriceHtml(html) {
        var result = {html:String(html || ''), debug:''};
        var parser;
        var doc;
        var validSelector = '.DelAvalStock, .CmDiscountPrice, .CmProductPriceBox, .CmProductPriceList, .CmTableProductNoStockBlock, .CmProductPriceNoStock, .cmNoInStock, .CmMorePrices, .CmShowMorePrice, .CmProductPriceUnavailable';
        var serviceSelector = '.CmWsLoadBar';
        var clean = [];
        var debug = [];
        var hasPriceHtml = false;
        if (typeof DOMParser === 'undefined') {
            return result;
        }
        parser = new DOMParser();
        doc = parser.parseFromString(result.html, 'text/html');
        Array.prototype.slice.call(doc.body.childNodes).forEach(function (node) {
            var text;
            var nodeHtml;
            if (node.nodeType === 3) {
                text = String(node.textContent || '');
                if (text.trim() !== '') {
                    debug.push(text);
                }
                return;
            }
            if (node.nodeType !== 1) {
                return;
            }
            if ((node.matches && node.matches(validSelector)) || (node.querySelector && node.querySelector(validSelector))) {
                hasPriceHtml = true;
                clean.push(node.outerHTML || '');
                return;
            }
            if ((node.matches && node.matches(serviceSelector)) || (node.querySelector && node.querySelector(serviceSelector))) {
                clean.push(node.outerHTML || '');
                return;
            }
            nodeHtml = node.outerHTML || node.textContent || '';
            if (String(nodeHtml).trim() !== '') {
                debug.push(nodeHtml);
            }
        });
        if (!hasPriceHtml && !doc.body.querySelector(validSelector)) {
            return {html:null, debug:debug.join('\n').trim()};
        }
        return {
            html:clean.join(''),
            debug:debug.join('\n').trim()
        };
    }
    function CarModNormalizeAjaxHtml(result, sourceUrl) {
        var html = String(result || '');
        var marker = '|CmWsErrors|';
        var parts;
        var parser;
        var doc;
        var nestedAjaxBox;
        var hasKnownListMarkup;
        if (html.indexOf(marker) !== -1) {
            parts = html.split(marker);
            CarModShowAdminAjaxPopup(parts[0] || parts[1] || html, CarModUiText('webserviceAjaxError'), false);
            return null;
        }
        if (html.trim() === '') {
            CarModShowAjaxError(CarModUiText('emptyAjaxResponse'), sourceUrl || window.location.href, '');
            return null;
        }
        if (typeof DOMParser === 'undefined') {
            return html;
        }
        parser = new DOMParser();
        doc = parser.parseFromString(html, 'text/html');
        nestedAjaxBox = doc.querySelector('#CmAjaxBox');
        if (nestedAjaxBox) {
            html = nestedAjaxBox.innerHTML;
            doc = parser.parseFromString(html, 'text/html');
        } else if (/<html[\s>]/i.test(html)) {
            CarModShowAjaxError('Wrong AJAX response', 'Server returned full HTML page without #CmAjaxBox.', html);
            return null;
        }
        hasKnownListMarkup = !!doc.querySelector('.CmSortFilterBlock, .CmWrapFlexBlock, .cm_gridView, .cm_listView, .CmPartTableViewRemaster, .cm_NoProduct, .CmBrTitleSearchWrap, .CmCrossTitleBl');
        if (!hasKnownListMarkup && CarModLooksLikeBrokenAjax(html)) {
            CarModShowAjaxError('Broken AJAX response', 'Server returned debug/error output instead of a clean list fragment.', html);
            return null;
        }
        return html;
    }
    function CarModPostAjaxHtml(nextUrl, data, options) {
        var xhr;
        var runId;
        options = options || {};
        if (CarModListAjaxXhr && CarModListAjaxXhr.readyState !== 4) {
            CarModListAjaxXhr.abort();
        }
        runId = ++CarModListAjaxRunId;
        xhr = new XMLHttpRequest();
        CarModListAjaxXhr = xhr;
        xhr.open('POST', nextUrl || window.location.href, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function () {
            var html;
            if (xhr.readyState !== 4) {
                return;
            }
            if (runId !== CarModListAjaxRunId) {
                return;
            }
            if (xhr.status >= 200 && xhr.status < 300) {
                html = CarModNormalizeAjaxHtml(xhr.responseText, nextUrl);
                if (html !== null) {
                    CarModApplyAjaxResult(nextUrl, html, options);
                } else {
                    CarModStopAjaxLoading();
                }
            } else {
                CarModStopAjaxLoading();
                CarModShowAjaxError(CarModUiText('ajaxHttpError'), CarModUiText('httpStatus') + ': ' + xhr.status + ' ' + (xhr.statusText || ''), xhr.responseText || '');
            }
        };
        xhr.send(CmWsPostString(data || {}));
    }
    function CarModApplyAjaxResult(nextUrl, result, options) {
        var box = CarModGetAjaxBox();
        options = options || {};
        if (!box) {
            CarModStopAjaxLoading();
            return;
        }
        box.innerHTML = result;
        if (window.history && window.history.replaceState) {
            window.history.replaceState('object or string', document.title, nextUrl);
        }
        if (CarModIsMobileFiltersView()) {
            CarModCloseMobileFilters();
        }
        CarModStopAjaxLoading();
        WebServiceListBlocks();
        WsNextProdPrices();
    }


        // FILTERS
    document.addEventListener('click', function (e) {
        var openButton = CarModClosestAjaxTarget(e.target, '.CmFilterShowButton');
        var closeButton;
        if (openButton) {
            e.preventDefault();
            e.stopPropagation();
            CarModOpenMobileFilters();
            return;
        }
        if (e.target && e.target.classList && e.target.classList.contains('CmBlockOverPage')) {
            e.preventDefault();
            CarModCloseMobileFilters();
            return;
        }
        closeButton = CarModClosestAjaxTarget(e.target, '.CmHideFiltersBlock');
        if (closeButton) {
            e.preventDefault();
            e.stopPropagation();
            CarModCloseMobileFilters();
        }
    });
    document.addEventListener('click', function (e) {
        var filter = CarModClosestAjaxTarget(e.target, '.CmFilterCheck');
        var nextUrl;
        var prid;
        var crcod;
        var bcode;
        if (!filter) {
            return;
        }
        e.preventDefault();
        CarModStartAjaxLoading(CarModGetProductListScrollTop());
        nextUrl = window.location.href;
        prid = CarModData(filter, 'prid');
        crcod = CarModData(filter, 'crcod');
        bcode = CarModData(filter, 'bcode');
        if(prid){
            nextUrl = CarModBuildFilterUrl('product', prid);
        }else if(crcod){
            nextUrl = CarModBuildFilterUrl('criteria', crcod);
        }else if(bcode){
            nextUrl = CarModBuildFilterUrl('brand', bcode);
        }
        CarModCloseMobileFilters();
        CarModPostAjaxHtml(nextUrl, {CarModAjax:'Y'}, {closeMobileFilters: true});
    });


    //OE, Analog switch
    document.addEventListener('click', function (e) {
        var button = CarModClosestAjaxTarget(e.target, '.CmTumButn');
        var nextUrl;
        if (!button) {
            return;
        }
		if(!button.classList.contains('CmTumPushed')){
			e.preventDefault();
			nextUrl = CarModBuildFilterUrl('only', CarModData(button, 'tumb'));
            CarModStartAjaxLoading(CarModGetProductListScrollTop());
            CarModPostAjaxHtml(nextUrl, {CarModAjax:'Y'}, {closeMobileFilters: true});
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

    document.addEventListener('click', function (e) {
        var pickSection = CarModClosestAjaxTarget(e.target, '.PickSection');
        var nextUrl;
        var code;
        var filterSection;
        var pickHeight;
        if (!pickSection) {
            return;
        }
        e.preventDefault();
        nextUrl = pickSection.getAttribute('href') || window.location.href;
        code = CarModData(pickSection, 'code');
        pickHeight = pickSection.offsetHeight;
        filterSection = pickSection.closest('.cm_FsBlock');
        if (filterSection) {
            filterSection.querySelectorAll('.FilterSection').forEach(function (item) {
                item.style.borderTopWidth = (pickHeight / 2) + 'px';
                item.style.borderBottomWidth = (pickHeight / 2) + 'px';
            });
        }
        CarModStartAjaxLoading(1);
        if (window.history && window.history.pushState) {
            window.history.pushState('object or string', 'Title', nextUrl);
        }
        CarModPostAjaxHtml(window.location.href, {CarModAjax:'Y', PickSection:code}, {});
    });

    // SORT BY PRODUCT_LIST
    function CarModCloseSortMenus(exceptMenu) {
        document.querySelectorAll('#CmAjaxBox .sort_sel.CmSortOpen').forEach(function (menu) {
            if (exceptMenu && menu === exceptMenu) {
                return;
            }
            menu.classList.remove('CmSortOpen');
            var button = menu.querySelector('.show_bl');
            if (button) {
                button.setAttribute('aria-expanded', 'false');
            }
        });
    }
    function CarModToggleSortMenu(menu) {
        var isOpen;
        var button;
        if (!menu) {
            return;
        }
        isOpen = menu.classList.contains('CmSortOpen');
        CarModCloseSortMenus(menu);
        menu.classList.toggle('CmSortOpen', !isOpen);
        button = menu.querySelector('.show_bl');
        if (button) {
            button.setAttribute('aria-expanded', !isOpen ? 'true' : 'false');
        }
    }
    document.addEventListener('click', function (e) {
        var sortItem = CarModClosestAjaxTarget(e.target, '.sort_list');
        var sortControl;
        var sortMenu;
        if (sortItem) {
            e.preventDefault();
            e.stopPropagation();
            CarModCloseSortMenus();
            CarModStartAjaxLoading(CarModGetProductListScrollTop());
            CarModPostAjaxHtml(window.location.href, {CarModAjax:'Y', SortBy:CarModData(sortItem, 'sort')}, {});
            return;
        }
        sortControl = CarModClosestAjaxTarget(e.target, '.sort_bl');
        if (sortControl) {
            e.preventDefault();
            e.stopPropagation();
            sortMenu = sortControl.closest('.sort_sel');
            CarModToggleSortMenu(sortMenu);
            return;
        }
        CarModCloseSortMenus();
    });
    document.addEventListener('keydown', function (e) {
        var sortControl;
        if (e.key === 'Escape') {
            CarModCloseSortMenus();
            return;
        }
        if (e.key !== 'Enter' && e.key !== ' ') {
            return;
        }
        sortControl = CarModClosestAjaxTarget(e.target, '.show_bl');
        if (!sortControl) {
            return;
        }
        e.preventDefault();
        CarModToggleSortMenu(sortControl.closest('.sort_sel'));
    });


    // VIEW SWITCH PRODUCT_LIST
    document.addEventListener('click', function (e) {
        var viewButton = CarModClosestAjaxTarget(e.target, '.cm_viewAct');
        var uri;
        var view;
        if (!viewButton) {
            return;
        }
        uri = CarModData(viewButton, 'urix');
        CarModStartAjaxLoading(jQuery('#CmAjaxBox').offset().top - 20);
        view = CarModData(viewButton, 'view');
        if(view && view!=''){
            CarModPostAjaxHtml(uri, {CarModAjax:'Y', ActivateTab:view}, {});
        } else {
            CarModStopAjaxLoading();
        }
    });

    // SELECT SETUP SIDE
    // front, rear
    document.addEventListener('click', function (e) {
        var sideButton = CarModClosestAjaxTarget(e.target, '.CmSelectCarSide');
        var titPosHrf;
        if (!sideButton) {
            return;
        }
        e.preventDefault();
        document.querySelectorAll('#CmAjaxBox .CmFrRr').forEach(function (item) {
            item.querySelectorAll('.CmCarSide').forEach(function (svg) { svg.style.fill = '#909090'; });
            item.querySelectorAll('.CmCarSideTxt').forEach(function (text) { text.style.color = '#909090'; });
            item.classList.remove('CmSelSideTogg');
            item.classList.add('CmSelectCarSide');
        });
        sideButton.querySelectorAll('.CmCarSide').forEach(function (svg) { svg.style.fill = '#f93a3a'; });
        sideButton.querySelectorAll('.CmCarSideTxt').forEach(function (text) { text.style.color = '#f93a3a'; });
        sideButton.classList.remove('CmSelectCarSide');
        sideButton.classList.add('CmSelSideTogg');
        titPosHrf = sideButton.getAttribute('href');
        CarModStartAjaxLoading(CarModGetProductListScrollTop());
        CarModPostAjaxHtml(titPosHrf, {CarModAjax:'Y'}, {closeMobileFilters: true});
    });
    //left, right
    document.addEventListener('click', function (e) {
        var sideButton = CarModClosestAjaxTarget(e.target, '.CmSelectBVSide');
        var titPosHrf;
        if (!sideButton) {
            return;
        }
        e.preventDefault();
        document.querySelectorAll('#CmAjaxBox .CmLfRt').forEach(function (item) {
            item.querySelectorAll('.CmBackView').forEach(function (svg) { svg.style.fill = '#909090'; });
            item.querySelectorAll('.CmBVTxt').forEach(function (text) { text.style.color = '#909090'; });
            item.classList.remove('CmSelBVTogg');
            item.classList.add('CmSelectBVSide');
        });
        sideButton.querySelectorAll('.CmBackView').forEach(function (svg) { svg.style.fill = '#f93a3a'; });
        sideButton.querySelectorAll('.CmBVTxt').forEach(function (text) { text.style.color = '#f93a3a'; });
        sideButton.classList.remove('CmSelectBVSide');
        sideButton.classList.add('CmSelBVTogg');
        titPosHrf = sideButton.getAttribute('href');
        CarModStartAjaxLoading(CarModGetProductListScrollTop());
        CarModPostAjaxHtml(titPosHrf, {CarModAjax:'Y'}, {closeMobileFilters: true});
    });


    /* Public sticky tips */
    var CmPublicTipTarget = null;

    function CmMovePublicTip(Event){
        var Tip = document.querySelector('.CmTipBox');
        var Gap = 14;
        var Edge = 10;
        var ViewLeft = window.pageXOffset || document.documentElement.scrollLeft || 0;
        var ViewTop = window.pageYOffset || document.documentElement.scrollTop || 0;
        var ViewRight = ViewLeft + window.innerWidth;
        var ViewBottom = ViewTop + window.innerHeight;
        var MaxWidth = Math.max(120, Math.min(320, window.innerWidth - Edge * 2));
        var Left;
        var Top;
        var TipWidth;
        var TipHeight;
        if(!Tip || !Event){return;}
        Tip.style.maxWidth = MaxWidth + 'px';
        TipWidth = Tip.offsetWidth;
        TipHeight = Tip.offsetHeight;
        Left = Event.pageX - 77;
        Top = Event.pageY + 7;
        if(Left + TipWidth + Edge > ViewRight){
            Left = Event.pageX - TipWidth - Gap + 3;
        }
        if(Left < ViewLeft + Edge){
            Left = Math.min(Event.pageX + Gap, ViewRight - TipWidth - Edge);
        }
        if(Top + TipHeight + Edge > ViewBottom){
            Top = Event.pageY - TipHeight - Gap;
        }
        if(Top < ViewTop + Edge){
            Top = ViewTop + Edge;
        }
        Tip.style.top = Top + 'px';
        Tip.style.left = Left + 'px';
    }
    function CmRemovePublicTip(){
        document.querySelectorAll('.CmTipBox').forEach(function(Tip){Tip.remove();});
    }
    function CmRestorePublicTipTarget(Target){
        if(Target && Target.dataset && Target.dataset.cmTipText){
            Target.setAttribute('title', Target.dataset.cmTipText);
            delete Target.dataset.cmTipText;
        }
    }
    function CmGetPublicTipTarget(Event){
        var Target = Event.target && Event.target.closest ? Event.target.closest('.CmTitShow, .cmTitShow') : null;
        var Root = document.getElementById('CmContent');
        if(!Target || !Root || !Root.contains(Target)){return null;}
        return Target;
    }
    function CmEnsurePublicTip(Title){
        var Tip = document.querySelector('.CmTipBox');
        if(!Tip){
            Tip = document.createElement('p');
            Tip.className = 'CmTipBox';
            Tip.style.display = 'block';
            document.body.appendChild(Tip);
        }
        if(Tip.innerHTML !== Title){
            Tip.innerHTML = Title;
        }
        return Tip;
    }
    function CmShowPublicTip(Event){
        var Target = CmGetPublicTipTarget(Event);
        var Title;
        if(!Target || Target.contains(Event.relatedTarget)){return;}
        Title = Target.getAttribute('title') || Target.dataset.cmTipText || '';
        if(!Title){return;}
        if(CmPublicTipTarget && CmPublicTipTarget !== Target){
            CmRestorePublicTipTarget(CmPublicTipTarget);
        }
        if(Target.hasAttribute('title')){
            Target.dataset.cmTipText = Title;
            Target.removeAttribute('title');
        }
        CmPublicTipTarget = Target;
        CmEnsurePublicTip(Title);
        CmMovePublicTip(Event);
    }
    function CmHidePublicTip(Event){
        var Target = CmGetPublicTipTarget(Event);
        if(!Target || Target.contains(Event.relatedTarget)){return;}
        if(CmPublicTipTarget !== Target){return;}
        CmRestorePublicTipTarget(Target);
        CmPublicTipTarget = null;
        CmRemovePublicTip();
    }
    function CmHoverPublicTip(Event){
        var Target = CmGetPublicTipTarget(Event);
        if(!Target){return;}
        if(CmPublicTipTarget === Target || Target.dataset.cmTipText){
            CmMovePublicTip(Event);
        }else{
            CmShowPublicTip(Event);
        }
    }
    (function(){
        document.addEventListener('pointerover', CmShowPublicTip, true);
        document.addEventListener('mouseover', CmShowPublicTip, true);
        document.addEventListener('mouseout', CmHidePublicTip);
        document.addEventListener('mousemove', CmHoverPublicTip);
    })();


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



    }
    document.addEventListener('click', function (e) {
        var brandButton = CarModClosestAjaxTarget(e.target, '.CmBrandSlideCheck');
        var bcode;
        var nextUrl = window.location.href;
        if (!brandButton) {
            return;
        }
        e.preventDefault();
        CarModStartAjaxLoading(CarModGetProductListScrollTop());
        bcode = CarModData(brandButton, 'bcode');
        if (bcode) {
            nextUrl = CarModBuildFilterUrl('brand', bcode);
        }
        CarModPostAjaxHtml(nextUrl, {CarModAjax:'Y'}, {closeMobileFilters: true});
    });
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
