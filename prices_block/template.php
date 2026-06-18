<?php
VerifyAccess_x('PricesBlock.templ');

global $arConSets, $aListSVG;
if(!is_array($arConSets)){
    $arConSets = array();
}
require_once __DIR__.'/../products_list/svg.php';

if(!function_exists('CmProductPriceIcon_x')){
    function CmProductPriceIcon_x($name, $class = ''){
        $classAttr = $class ? ' class="'.$class.'"' : '';
        switch($name){
            case 'cart':
                return '<svg'.$classAttr.' viewBox="0 0 24 24"><path d="M10 19.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5zm3.5-1.5c-.828 0-1.5.671-1.5 1.5s.672 1.5 1.5 1.5 1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5zm-10.563-5l-2.937-7h16.812l-1.977 7h-11.898zm11.233-5h-11.162l1.259 3h9.056l.847-3zm5.635-5l-3.432 12h-12.597l.839 2h13.239l3.474-12h1.929l.743-2h-4.195z"/></svg>';
            case 'ask':
                return '<svg'.$classAttr.' viewBox="0 0 24 24"><path d="M12 2c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0-2c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm1.25 17c0 .69-.559 1.25-1.25 1.25-.689 0-1.25-.56-1.25-1.25s.561-1.25 1.25-1.25c.691 0 1.25.56 1.25 1.25zm1.393-9.998c-.608-.616-1.515-.955-2.551-.955-2.18 0-3.59 1.55-3.59 3.95h2.011c0-1.486.829-2.013 1.538-2.013.634 0 1.307.421 1.364 1.226.062.847-.39 1.277-.962 1.821-1.412 1.343-1.438 1.993-1.432 3.468h2.005c-.013-.664.03-1.203.935-2.178.677-.73 1.519-1.638 1.536-3.022.011-.924-.284-1.719-.854-2.297z"/></svg>';
            case 'delivery':
                return '<svg'.$classAttr.' viewBox="0 0 24 24" fill-rule="evenodd" clip-rule="evenodd"><path d="M2 11.741c-1.221-1.009-2-2.535-2-4.241 0-3.036 2.464-5.5 5.5-5.5 1.706 0 3.232.779 4.241 2h4.259c.552 0 1 .448 1 1v2h4.667c1.117 0 1.6.576 1.936 1.107.594.94 1.536 2.432 2.109 3.378.188.312.288.67.288 1.035v4.48c0 1.156-.616 2-2 2h-1c0 1.656-1.344 3-3 3s-3-1.344-3-3h-4c0 1.656-1.344 3-3 3s-3-1.344-3-3h-2c-.552 0-1-.448-1-1v-6.259zm6 6.059c.662 0 1.2.538 1.2 1.2 0 .662-.538 1.2-1.2 1.2-.662 0-1.2-.538-1.2-1.2 0-.662.538-1.2 1.2-1.2zm10 0c.662 0 1.2.538 1.2 1.2 0 .662-.538 1.2-1.2 1.2-.662 0-1.2-.538-1.2-1.2 0-.662.538-1.2 1.2-1.2zm-7.207-11.8c.135.477.207.98.207 1.5 0 3.036-2.464 5.5-5.5 5.5-.52 0-1.023-.072-1.5-.207v4.207h1.765c.549-.614 1.347-1 2.235-1 .888 0 1.686.386 2.235 1h5.53c.549-.614 1.347-1 2.235-1 .888 0 1.686.386 2.235 1h1.765v-4.575l-1.711-2.929c-.179-.307-.508-.496-.863-.496h-4.426v6h-2v-9h-2.207zm5.207 4v3h5l-1.427-2.496c-.178-.312-.509-.504-.868-.504h-2.705zm-10.5-6c1.932 0 3.5 1.568 3.5 3.5s-1.568 3.5-3.5 3.5-3.5-1.568-3.5-3.5 1.568-3.5 3.5-3.5zm.5 3h2v1h-3v-3h1v2z"/></svg>';
            case 'stock':
                return '<svg'.$classAttr.' viewBox="0 0 24 24" fill-rule="evenodd" clip-rule="evenodd"><path d="M5 23h-2v-10l8.991-8.005c1.124.998 2.25 1.997 3.378 2.996l2.255 1.997c1.127.999 2.252 2.013 3.376 3.012v10h-2v-9.118l-7.009-6.215-6.991 6.22v9.113zm2-2h10v2h-10v-2zm0-3h10v2h-10v-2zm10-3v2h-10v-2h10zm-5-14l12 10.632-1.328 1.493-10.672-9.481-10.672 9.481-1.328-1.493 12-10.632z"/></svg>';
            case 'available':
                return '<svg'.$classAttr.' viewBox="0 0 24 24"><path d="M16.677 17.868l-.343.195v-1.717l.343-.195v1.717zm2.823-3.325l-.342.195v1.717l.342-.195v-1.717zm3.5-7.602v11.507l-9.75 5.552-12.25-6.978v-11.507l9.767-5.515 12.233 6.941zm-13.846-3.733l9.022 5.178 1.7-.917-9.113-5.17-1.609.909zm2.846 9.68l-9-5.218v8.19l9 5.126v-8.098zm3.021-2.809l-8.819-5.217-2.044 1.167 8.86 5.138 2.003-1.088zm5.979-.943l-2 1.078v2.786l-3 1.688v-2.856l-2 1.078v8.362l7-3.985v-8.151zm-4.907 7.348l-.349.199v1.713l.349-.195v-1.717zm1.405-.8l-.344.196v1.717l.344-.196v-1.717zm.574-.327l-.343.195v1.717l.343-.195v-1.717zm.584-.333l-.35.199v1.717l.35-.199v-1.717z"/></svg>';
            case 'check':
                return '<svg'.$classAttr.' viewBox="0 0 44 44"><path d="m22,0c-12.2,0-22,9.8-22,22s9.8,22 22,22 22-9.8 22-22-9.8-22-22-22zm12.7,15.1l0,0-16,16.6c-0.2,0.2-0.4,0.3-0.7,0.3-0.3,0-0.6-0.1-0.7-0.3l-7.8-8.4-.2-.2c-0.2-0.2-0.3-0.5-0.3-0.7s0.1-0.5 0.3-0.7l1.4-1.4c0.4-0.4 1-0.4 1.4,0l.1,.1 5.5,5.9c0.2,0.2 0.5,0.2 0.7,0l13.4-13.9h0.1c0.4-0.4 1-0.4 1.4,0l1.4,1.4c0.4,0.3 0.4,0.9 0,1.3z"/></svg>';
            case 'not_available':
                return '<svg'.$classAttr.' viewBox="0 -2 24 24"><path d="M13.5 18c-.828 0-1.5.672-1.5 1.5 0 .829.672 1.5 1.5 1.5s1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5zm-3.5 1.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5zm13.257-14.5h-1.929l-3.473 12h-13.239l-4.616-11h2.169l3.776 9h10.428l3.432-12h4.195l-.743 2zm-12.257 1.475l2.475-2.475 1.414 1.414-2.475 2.475 2.475 2.475-1.414 1.414-2.475-2.475-2.475 2.475-1.414-1.414 2.475-2.475-2.475-2.475 1.414-1.414 2.475 2.475z"/></svg>';
            case 'box':
                return '<svg'.$classAttr.' viewBox="0 0 32 32"><path d="M23 1l-7 6 9 6 7-6z"></path><path d="M16 7l-7-6-9 6 7 6z"></path><path d="M25 13l7 6-9 5-7-6z"></path><path d="M16 18l-9-5-7 6 9 5z"></path><path d="M22.755 26.424l-6.755-5.79-6.755 5.79-4.245-2.358v2.934l11 5 11-5v-2.934z"></path></svg>';
            case 'close':
                return '<svg'.$classAttr.' viewBox="0 0 24 24"><path d="M23.954 21.03l-9.184-9.095 9.092-9.174-2.832-2.807-9.09 9.179-9.176-9.088-2.81 2.81 9.186 9.105-9.095 9.184 2.81 2.81 9.112-9.192 9.18 9.1z"></path></svg>';
        }
        return '';
    }

    function CmProductPriceMinQty_x($price){
        if(isset($price['OPTIONS_VIEW']['Minimal_qnt']['Text']) && $price['OPTIONS_VIEW']['Minimal_qnt']['Text'] !== ''){
            return max(1, intval($price['OPTIONS_VIEW']['Minimal_qnt']['Text']));
        }
        return 1;
    }

    function CmProductPriceMultiplicity_x($price){
        if(isset($price['OPTIONS_VIEW']['Multiplicity']['Text']) && intval($price['OPTIONS_VIEW']['Multiplicity']['Text']) > 1){
            return intval($price['OPTIONS_VIEW']['Multiplicity']['Text']);
        }
        return 1;
    }

    function CmProductPriceInitialQty_x($price){
        $Qty = CmProductPriceMinQty_x($price);
        $Multiplicity = CmProductPriceMultiplicity_x($price);
        if($Multiplicity > 1){
            $Qty = max($Qty, $Multiplicity);
            $Qty = intval(ceil($Qty / $Multiplicity) * $Multiplicity);
        }
        $Max = isset($price['AVAILABLE_NUM']) ? intval($price['AVAILABLE_NUM']) : 0;
        if($Max > 0 && $Qty > $Max){
            $Qty = $Multiplicity > 1 ? intval(floor($Max / $Multiplicity) * $Multiplicity) : $Max;
            if($Qty < 1){$Qty = $Max;}
        }
        return max(1, $Qty);
    }

    function CmProductPriceAvailableTitle_x($price){
        if($price['AVAILABLE_NUM'] == 0){
            return Lng_x('Not_available');
        }
        return HIDE_PRODUCTS_COUNT ? Lng_x('Available') : Lng_x('Availability');
    }

    function CmProductPriceAvailabilityView_x($price){
        if(function_exists('CmNormalizeAvailabilityView_x')){
            $view = CmNormalizeAvailabilityView_x($price);
        }else{
            $view = isset($price['AVAILABLE_VIEW']) ? trim((string)$price['AVAILABLE_VIEW']) : '';
        }
        if($view == '' && isset($price['AVAILABLE_NUM'])){
            $view = (string)$price['AVAILABLE_NUM'];
        }
        if($view == '' || $view === '0'){
            return '<b>&#215;</b>';
        }
        $numView = str_replace(',', '.', $view);
        if(is_numeric($numView)){
            $numView = round((float)$numView, 2);
            if((float)intval($numView) == (float)$numView){
                return (string)intval($numView);
            }
            return rtrim(rtrim((string)$numView, '0'), '.');
        }
        return htmlspecialchars($view, ENT_QUOTES, 'UTF-8');
    }

    function CmProductPriceAvailable_x($price, $res = array()){
        if(HIDE_PRODUCTS_COUNT){
            if($price['AVAILABLE_NUM'] > 0){ ?>
                <div class="cm_InStock <?php if(isset($res['ACTIVE_TAB']) && $res['ACTIVE_TAB'] == 'TABLE'){ ?>StockTableStyle<?php } ?> CmMargZ">
                    <svg class="InStockImg" viewBox="-1 -2 24 24" style="<?php if(isset($res['ACTIVE_TAB']) && $res['ACTIVE_TAB'] == 'TABLE'){ ?>margin:0px;<?php } ?>">
                        <path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/>
                    </svg>
                </div>
            <?php }else{ ?>
                <div class="cm_OutOfStock <?php if(isset($res['ACTIVE_TAB']) && $res['ACTIVE_TAB'] == 'TABLE'){ ?>StockTableStyle<?php } ?>">
                    <svg class="OutStockImg" width="14" height="16" viewBox="0 0 24 24" style="<?php if(isset($res['ACTIVE_TAB']) && $res['ACTIVE_TAB'] == 'TABLE'){ ?>margin:0px;<?php } ?>">
                        <path d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z" fill="#FFFFFF"/>
                    </svg>
                </div>
            <?php }
        }else{ ?>
            <div class="CmListPrAvail"><?=CmProductPriceAvailabilityView_x($price)?></div>
        <?php }
    }

    function CmProductPriceDeliveryTitle_x($price){
        return $price['DELIVERY_NUM'] == 0 ? Lng_x('In_stock') : Lng_x('Dtime_delivery');
    }

    function CmProductPriceHasOptions_x($firstPr, $prOpt){
        return (isset($firstPr['OPTIONS_VIEW']) && is_array($firstPr['OPTIONS_VIEW']) && count($firstPr['OPTIONS_VIEW']) > 0)
            || (is_array($prOpt) && count($prOpt) > 0);
    }

    function CmProductPriceOptionIsSvg_x($aOpt){
        return is_array($aOpt) && isset($aOpt['Type']) && intval($aOpt['Type']) == 0;
    }

    function CmProductPriceHasSvgOptions_x($prices){
        if(!is_array($prices)){return false;}
        foreach($prices as $price){
            if(!isset($price['OPTIONS_VIEW']) || !is_array($price['OPTIONS_VIEW'])){continue;}
            foreach($price['OPTIONS_VIEW'] as $code => $aOpt){
                if($code == 'Hot_price'){continue;}
                if(CmProductPriceOptionIsSvg_x($aOpt) && isset($aOpt['Value']) && trim((string)$aOpt['Value']) != ''){
                    return true;
                }
            }
        }
        return false;
    }

    function CmProductPriceHasOptionCode_x($price, $code){
        if(!isset($price['OPTIONS_VIEW'][$code])){
            return false;
        }
        $aOpt = $price['OPTIONS_VIEW'][$code];
        $value = is_array($aOpt) ? (isset($aOpt['Value']) ? $aOpt['Value'] : (isset($aOpt['Text']) ? $aOpt['Text'] : '')) : $aOpt;
        return trim((string)$value) != '';
    }

    function CmProductPriceOptions_x($price, $wrapClass = 'CmOptionsBlockInfo', $itemClass = 'CmOptionView', $optionType = 'all'){
        if(!isset($price['OPTIONS_VIEW']) || !is_array($price['OPTIONS_VIEW']) || count($price['OPTIONS_VIEW']) == 0){
            return '';
        }
        $aValues = array();
        foreach($price['OPTIONS_VIEW'] as $code => $aOpt){
            if($code == 'Hot_price'){continue;}
            $isSvg = CmProductPriceOptionIsSvg_x($aOpt);
            if($optionType == 'svg' && !$isSvg){continue;}
            if($optionType == 'text' && $isSvg){continue;}
            $value = (is_array($aOpt) && isset($aOpt['Value'])) ? $aOpt['Value'] : $aOpt;
            if(trim((string)$value) == ''){continue;}
            $aValues[] = $value;
        }
        if(count($aValues) == 0){return '';}
        ob_start(); ?>
        <div class="<?=$wrapClass?>">
            <?php foreach($aValues as $value){ ?>
                <div class="<?=$itemClass?>"><?=$value?></div>
            <?php } ?>
        </div>
        <?php return ob_get_clean();
    }
    function CmProductPriceInlineOptions_x($price, $optionType = 'all'){
        $html = CmProductPriceOptions_x($price, 'CmOptionsBlockInfo CmTablePriceOptionsInner', 'CmOptionView', $optionType);
        if($html == ''){return '';}
        return '<div class="CmTablePriceOptions">'.$html.'</div>';
    }

    function CmProductPriceSupplierStock_x($price, $aRes, $mode = 'compact'){
        $showSupplier = ($aRes['SHOW_SUPPLIER'] && isset($price['SUPPLIER_NAME']) && $price['SUPPLIER_NAME'] != '');
        $showStock = ($aRes['SHOW_STOCK'] && isset($price['SUPPLIER_STOCK']) && $price['SUPPLIER_STOCK'] != '');
        if(!$showSupplier && !$showStock){
            return '';
        }
        ob_start();
        if($mode == 'compact'){ ?>
            <div class="CmSuplNameStockWrapBl CmProductPriceSupplierStock">
                <div class="svgStock"><?=CmProductPriceIcon_x('stock', 'stockImg')?></div>
                <?php if($showSupplier){ ?>
                    <div class="stockTd CmTitShow" title="<?=Lng_x('Supplier');?>">
                        <div class="CmProdPrStock"><?=$price['SUPPLIER_NAME']?></div>
                    </div>
                <?php }
                if($showStock && $showSupplier){ ?><span>&nbsp;&frasl;&nbsp;</span><?php }
                if($showStock){ ?>
                    <div class="CmProvTd CmTitShow" title="<?=Lng_x('Stock');?>">
                        <div class="CmProdPrStock"><?=$price['SUPPLIER_STOCK']?></div>
                    </div>
                <?php } ?>
            </div>
        <?php }else{ ?>
            <div class="CmProductPriceSupplierStockText CmTitShow" title="<?php if($showSupplier){ ?><?=Lng_x('Supplier');?>: <?=$price['SUPPLIER_NAME']?><?php } if($showSupplier && $showStock){ ?> / <?php } if($showStock){ ?><?=Lng_x('Stock');?>: <?=$price['SUPPLIER_STOCK']?><?php } ?>">
                <?php if($showSupplier){ ?><?=$price['SUPPLIER_NAME']?><?php } if($showSupplier && $showStock){ ?> / <?php } if($showStock){ ?><?=$price['SUPPLIER_STOCK']?><?php } ?>
            </div>
        <?php }
        return ob_get_clean();
    }

    function CmProductPriceHasSupplierStock_x($prices, $aRes){
        if((empty($aRes['SHOW_SUPPLIER']) && empty($aRes['SHOW_STOCK'])) || !is_array($prices)){
            return false;
        }
        foreach($prices as $price){
            if(!empty($aRes['SHOW_SUPPLIER']) && isset($price['SUPPLIER_NAME']) && trim((string)$price['SUPPLIER_NAME']) != ''){
                return true;
            }
            if(!empty($aRes['SHOW_STOCK']) && isset($price['SUPPLIER_STOCK']) && trim((string)$price['SUPPLIER_STOCK']) != ''){
                return true;
            }
        }
        return false;
    }

    function CmProductPriceCompactStatus_x($price){
        ob_start(); ?>
        <div class="CmAvalProdOptionWrap CmProductPriceStatusWrap">
            <?php if(PRICES_DISPLAY_QUANTITY){ ?>
                <div class="avalTd CmTitShow" title="<?=CmProductPriceAvailableTitle_x($price)?>">
                    <div class="CmAvalImgTextPage <?php if(HIDE_PRODUCTS_COUNT){ ?>CmPaddZ<?php } ?>">
                        <?php if(!HIDE_PRODUCTS_COUNT){ ?>
                            <div class="cm_svgAval"><?=CmProductPriceIcon_x('available', 'CmAvalOnPage fillBg')?></div>
                        <?php }
                        CmProductPriceAvailable_x($price); ?>
                    </div>
                </div>
            <?php }
            if($price['AVAILABLE_NUM'] > 0){ ?>
                <div class="delivTd CmTitShow <?php if(HIDE_PRODUCTS_COUNT){ ?>CmPaddUpDownZ<?php } ?> <?php if($price['DELIVERY_NUM'] == 0){ ?>CmInStockDelivery<?php }else{ ?>CmTimeDelivery<?php } ?>" title="<?=CmProductPriceDeliveryTitle_x($price)?>" data-suplstock="<?=$price['SUPPLIER_STOCK']?>">
                    <div class="svgDeliv"><?=CmProductPriceIcon_x('delivery', 'delivImg')?></div>
                    <?php if($price['DELIVERY_NUM'] == 0){ ?>
                        <div class="svgDeliv"><?=CmProductPriceIcon_x('check', 'CmProductPriceDeliveryCheck')?></div>
                    <?php }else{ ?>
                        <span class="CmDelivTxt"><?=$price['DELIVERY_VIEW']?></span>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <?php return ob_get_clean();
    }

    function CmProductPriceListDelivery_x($price){
        ob_start();
        if($price['DELIVERY_NUM'] == 0){ ?>
            <div class="CmInStockText">
                <?=CmProductPriceIcon_x('check', 'CmProductPriceDeliveryCheck')?>
            </div>
        <?php }else{ ?>
            <div class="CmListPrDelivery CmTitShow <?php if($price['DELIVERY_NUM'] == 0){ ?>CmInStockDelivery<?php }else{ ?>CmTimeDelivery<?php } ?>" title="<?=Lng_x('Dtime_delivery');?>" data-suplstock="<?=$price['SUPPLIER_STOCK']?>">
                <?=$price['DELIVERY_VIEW']?>
            </div>
        <?php }
        return ob_get_clean();
    }

    function CmProductPriceDeliveryTimeIcon_x(){
        global $aListSVG;
        return '<span class="CmProductPriceDeliveryBadgeIcon">'.$aListSVG['Delivery_time'].'</span>';
    }

    function CmProductPriceDeliveryBadge_x($price){
        if(intval($price['AVAILABLE_NUM']) <= 0){return '';}
        $BadgeIcon = '';
        if($price['DELIVERY_NUM'] == 0){
            $BadgeText = Lng_x('In_stock_short');
            $BadgeClass = 'CmProductPriceDeliveryBadge CmProductPriceDeliveryBadge-instock';
            return '<div class="'.$BadgeClass.'"><span class="CmProductPriceDeliveryBadgeText">'.htmlspecialchars($BadgeText,ENT_QUOTES,'UTF-8').'</span></div>';
        }else{
            $BadgeText = trim(strip_tags((string)$price['DELIVERY_VIEW']));
            $BadgeClass = 'CmProductPriceDeliveryBadge CmProductPriceDeliveryBadge-time';
            $BadgeIcon = CmProductPriceDeliveryTimeIcon_x();
        }
        $BadgeTitle = CmProductPriceDeliveryTitle_x($price);
        return '<div class="'.$BadgeClass.' CmTitShow" title="'.htmlspecialchars($BadgeTitle,ENT_QUOTES,'UTF-8').'">'.$BadgeIcon.'<span class="CmProductPriceDeliveryBadgeText">'.htmlspecialchars($BadgeText,ENT_QUOTES,'UTF-8').'</span></div>';
    }

    function CmPricesBlockWhatsAppButton_x($aProd, $wrapClass = ''){
        global $CPMod, $aListSVG;
        if(!$CPMod->arSettings['WHATSAPP_PHONE']){return '';}
        $WhatsappPhone = preg_replace('/\D+/', '', $CPMod->arSettings['WHATSAPP_PHONE']);
        if($WhatsappPhone==''){return '';}
        $WrapClass = trim('CmPricesBlockWhatsApp '.$wrapClass);
        $WhatsappProductName = trim($aProd['Brand'].' '.$aProd['ArtNum']);
        $WhatsappMessage = Lng_x('Order_this_product').': ';
        ob_start(); ?>
            <div class="<?=$WrapClass?>">
                <div class="WhatsAppBut_cm" title="Whatsapp Chat" data-whatsapp-phone="<?=htmlspecialchars($WhatsappPhone,ENT_QUOTES,'UTF-8')?>" data-whatsapp-message="<?=htmlspecialchars($WhatsappMessage,ENT_QUOTES,'UTF-8')?>" data-whatsapp-product="<?=htmlspecialchars($WhatsappProductName,ENT_QUOTES,'UTF-8')?>" onclick="window.open('https://wa.me/'+this.getAttribute('data-whatsapp-phone')+'?text='+encodeURIComponent(this.getAttribute('data-whatsapp-message')+this.getAttribute('data-whatsapp-product')),'_blank')">
                    <span class="CmWhatsAppIcon"><?=$aListSVG['Whatsapp']?></span>
                    <span class="CmWhatsAppText"><span>Whats</span><span>app</span></span>
                </div>
            </div>
        <?php
        return ob_get_clean();
    }

    function CmProductPriceDisabledCart_x($mode = 'list', $reason = ''){
        $cartIconClass = ($mode == 'compact') ? 'CmCartImgPp' : 'cm_HideCartImg';
        $title = $reason != '' ? $reason : Lng_x('Not_available');
        ?>
        <div class="<?php if($mode == 'compact'){ ?>toCartButt CmProductPriceCartButton-compact<?php }else{ ?>CmButtonToCart<?php } ?> CmProductPriceCartButton CmProductPriceCartButton-disabled CmTitShow" title="<?=htmlspecialchars($title,ENT_QUOTES,'UTF-8')?>" role="button" aria-disabled="true">
            <?php if($mode == 'compact'){ ?>
                <span>
                    <?=CmProductPriceIcon_x('cart', $cartIconClass)?>
                    <span class="cartText"><?=Lng_x('To cart')?></span>
                </span>
            <?php }else{ ?>
                <?=CmProductPriceIcon_x('cart', $cartIconClass)?>
                <span class="CmProductPriceCartText"><?=Lng_x('To cart')?></span>
            <?php } ?>
        </div>
        <?php
    }

    function CmProductPriceCartAction_x($price, $aProd, $aRes, $aSets, $arConSets, $ProductURL, $mode = 'list'){
        if($price['AVAILABLE_NUM'] <= 0 && !$aRes['ALLOW_NOTAVAIL']){
            if($mode == 'compact'){ ?>
                <div class="cmNotAvailable CmProductPriceUnavailable">
                    <?=CmProductPriceIcon_x('not_available', 'NotAvalImg')?>
                    <span><?=Lng_x('Not_available')?></span>
                </div>
            <?php }
            CmProductPriceDisabledCart_x($mode, Lng_x('Not_available'));
            return;
        }

        $availableNum = $price['AVAILABLE_NUM'];
        if($availableNum == 0 && $aRes['ALLOW_NOTAVAIL']){
            $availableNum = 99;
        }
        $minQty = CmProductPriceMinQty_x($price);
        $multiplicity = CmProductPriceMultiplicity_x($price);
        $initialQty = CmProductPriceInitialQty_x($price);
        $cartIconClass = ($mode == 'compact') ? 'CmCartImgPp' : 'cm_HideCartImg';

        $oneClickOrder = (!empty($aSets['ONECLICK_EMAIL_ORDER']) || !empty($arConSets['ONECLICK_EMAIL_ORDER']));
        ?>
        <?php if($oneClickOrder){ ?>
            <div class="CmMailOrder toCartButt CmProductPriceMailOrder CmProductPriceCartButton" data-tab="AskPrice" data-artnum="<?=$aProd['ArtNum']?>" data-brand="<?=$aProd['Brand']?>" data-moduledir="<?=CM_DIR?>" data-lang="<?=LANG_x?>" data-link="<?=$ProductURL?>">
                <span>
                    <?=CmProductPriceIcon_x('cart', 'CmCartImgPp')?>
                    <span class="cartText"><?=Lng_x('Order')?></span>
                </span>
            </div>
        <?php }else{ ?>
            <div class="CmQuantBlToCartBl CmProductPriceActions <?php if($mode == 'compact'){ ?>CmProductPriceActions-compact<?php } ?>">
                <div class="<?php if($mode == 'compact'){ ?>blockQty<?php }else{ ?>CmQuantPriceBlock<?php } ?>">
                    <div class="<?php if($mode == 'compact'){ ?>minusButt<?php }else{ ?>CmQuantMinusBut<?php } ?> cm_countButM">-</div>
                    <input name="re_count" type="text" class="CmAddToCartQty <?php if($mode == 'compact'){ ?>quantProd<?php }else{ ?>CmQuantInputProd<?php } ?> cm_countRes" value="<?=$initialQty?>" data-maxaval="<?=$availableNum?>" data-minimalqnt="<?=$minQty?>" data-multiplicity="<?=$multiplicity?>">
                    <div class="<?php if($mode == 'compact'){ ?>plusButt<?php }else{ ?>CmQuantPlusBut<?php } ?> cm_countButP">+</div>
                </div>
                <?php if(isset($aRes['FINDPRICE_DISPLAY_PRICES']) && $aRes['FINDPRICE_DISPLAY_PRICES'] === true){ ?>
                    <a href="<?=$ProductURL?>" class="<?php if($mode == 'compact'){ ?>CmFindPriceLink<?php }else{ ?>CmFPrNotHideLink<?php } ?> CmProductPriceFindLink" <?=$aRes['FindPrice_isBlank']?>>
                        <span><?=CmProductPriceIcon_x('cart', 'CmCartImgPp')?></span>
                    </a>
                <?php }else{ ?>
                    <div class="CmAddToCart <?php if($mode == 'compact'){ ?>toCartButt<?php }else{ ?>CmButtonToCart<?php } ?> CmProductPriceCartButton" data-furl="<?=$ProductURL?>" data-priceid="<?=$price['PriceID']?>">
                        <?php if($mode == 'compact'){ ?>
                            <span>
                                <?=CmProductPriceIcon_x('cart', $cartIconClass)?>
                                <span class="cartText"><?=Lng_x('To cart')?></span>
                            </span>
                        <?php }else{ ?>
                            <?=CmProductPriceIcon_x('cart', $cartIconClass)?>
                            <span class="CmProductPriceCartText"><?=Lng_x('To cart')?></span>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        <?php }
    }

    function CmProductPriceRuleBlock_x($price, $class = 'CmWrapPriceDiscPage'){
        if(!$price['RULE_PATTERN']){
            return '';
        }
        ob_start(); ?>
        <div class="<?=$class?>">
            <div class="CmDiscPrNotHide"><div class="CmOldPrice"><?=$price['RULE_PATTERN']?></div></div>
        </div>
        <?php return ob_get_clean();
    }

    function CmProductPriceValueCell_x($price, $aProd, $aRes, $aSets, $arConSets, $ProductURL, $extraClass = '', $showRule = true, $showInlineOptions = false, $inlineOptionsType = 'all', $mode = 'list'){
        ob_start(); ?>
        <div class="CmProductPriceCell CmProductPriceCell-price <?php if($mode == 'intable'){ ?>CmProductPriceCell-price-intable<?php } ?> <?=$extraClass?>">
            <div class="CmPriceChangeQuant">
                <?php if($showRule){
                    echo CmProductPriceRuleBlock_x($price);
                } ?>
                <div class="CmPriceQuantWrap">
                    <div class="CmPriceNum <?php if($price['AVAILABLE_NUM'] == 0 && !$aRes['ALLOW_NOTAVAIL']){ ?>CmProductPriceNum-muted<?php } ?>">
                        <span><nobr><?=$price['PRICE_VALUE'];?><?php if($mode == 'intable' && isset($price['PRICE_CURRENCY']) && $price['PRICE_CURRENCY'] != ''){ ?><span class="CmPriceCurrency"><?=$price['PRICE_CURRENCY']?></span><?php } ?></nobr></span>
                    </div>
                    <?php CmProductPriceCartAction_x($price, $aProd, $aRes, $aSets, $arConSets, $ProductURL, 'list'); ?>
                </div>
                <?php if($showInlineOptions){
                    echo CmProductPriceInlineOptions_x($price, $inlineOptionsType);
                } ?>
            </div>
        </div>
        <?php return ob_get_clean();
    }

    function CmProductPriceHeader_x($firstPr, $noSuplStock, $showOptions, $showSvgOptionsColumn = false){
        ob_start(); ?>
        <div class="CmProductPriceRow CmProductPriceHeader CmTitleImg">
            <?php if(!HIDE_PRODUCTS_COUNT){ ?>
                <div class="CmProductPriceCell CmProductPriceCell-delivery">
                    <div id="cmdelnum" class="CmSvgDelivNotHide CmTitShow" title="<?=Lng_x('Dtime_delivery')?>">
                        <?=CmProductPriceIcon_x('delivery', 'cm_deliv')?>
                    </div>
                </div>
            <?php }
            if($noSuplStock){ ?>
                <div class="CmProductPriceCell CmProductPriceCell-stock">
                    <div class="CmSvgStockNotHide"><?=CmProductPriceIcon_x('stock', 'cm_stock')?></div>
                </div>
            <?php }
            if(PRICES_DISPLAY_QUANTITY){ ?>
                <div class="CmProductPriceCell CmProductPriceCell-available">
                    <div id="cmavnum" class="CmSvgAvalNotHide CmTitShow" title="<?=Lng_x('Availability')?>">
                        <?=CmProductPriceIcon_x('available', 'CmAvalOnPage fillBg')?>
                    </div>
                </div>
            <?php } ?>
            <?php if($showSvgOptionsColumn){ ?>
                <div class="CmProductPriceCell CmProductPriceCell-svg-options"></div>
            <?php } ?>
            <div class="CmProductPriceCell CmProductPriceCell-price">
                <div id="cmprnum" class="CmPriceTextTitle">
                    <div class="CmPrTitleTxt"><?=Lng_x('Price')?>&nbsp;<span class="CmCurrPrice"><?=$firstPr['PRICE_CURRENCY']?></span></div>
                </div>
            </div>
        </div>
        <?php return ob_get_clean();
    }

    function CmProductPriceRow_x($price, $firstPr, $aProd, $aRes, $aSets, $arConSets, $ProductURL, $noSuplStock, $showOptions, $rowType = 'visible', $mode = 'list', $showSvgOptionsColumn = false){
        $rowClass = 'CmTablePriceValueRow';
        if($rowType == 'first'){
            $rowClass = 'CmPriceValRow';
        }elseif($rowType == 'hidden'){
            $rowClass = 'CmTablePriceValueRow_2 CmProductPriceRowHidden';
        }
        if(CmProductPriceHasOptionCode_x($price, 'Hot_price')){
            $rowClass .= ' CmProductPriceRow-hot';
        }
        if($mode == 'dropdown'){
            $optionsHtml = $showOptions ? CmProductPriceOptions_x($price, 'CmOptionsBlockInfo CmProductPriceDropdownOptionsInner') : '';
            $ruleHtml = CmProductPriceRuleBlock_x($price, 'CmProductPriceDropdownVat');
            $supplierStockHtml = $noSuplStock ? CmProductPriceSupplierStock_x($price, $aRes, 'compact') : '';
            ob_start(); ?>
            <div class="CmProductPriceRow CmProductPriceRow-dropdown <?=$rowClass?>" data-cmdelnum="<?=$price['DELIVERY_NUM']?>" data-cmavnum="<?=$price['AVAILABLE_NUM']?>" data-cmprnum="<?=strip_tags($price['PRICE_VALUE'])?>">
                <?php if($optionsHtml !== '' || $ruleHtml !== ''){ ?>
                    <div class="CmProductPriceDropdownMeta">
                        <?php if($optionsHtml !== ''){ ?>
                            <div class="CmProductPriceDropdownOptions"><?=$optionsHtml?></div>
                        <?php }
                        echo $ruleHtml; ?>
                    </div>
                <?php } ?>
                <div class="CmProductPriceDropdownLine">
                    <div class="CmProductPriceDropdownStatus">
                        <?=CmProductPriceCompactStatus_x($price)?>
                    </div>
                    <?=CmProductPriceValueCell_x($price, $aProd, $aRes, $aSets, $arConSets, $ProductURL, 'CmProductPriceDropdownPrice', false)?>
                </div>
                <?php if($supplierStockHtml != ''){ ?>
                    <div class="CmProductPriceDropdownBottomMeta"><?=$supplierStockHtml?></div>
                <?php } ?>
            </div>
            <?php return ob_get_clean();
        }
        ob_start(); ?>
        <div class="CmProductPriceRow <?=$rowClass?>" data-cmdelnum="<?=$price['DELIVERY_NUM']?>" data-cmavnum="<?=$price['AVAILABLE_NUM']?>" data-cmprnum="<?=strip_tags($price['PRICE_VALUE'])?>">
            <?php if(!HIDE_PRODUCTS_COUNT && $mode != 'intable'){ ?>
                <div class="CmProductPriceCell CmProductPriceCell-delivery"><?=CmProductPriceListDelivery_x($price)?></div>
            <?php }
            if($noSuplStock){ ?>
                <div class="CmProductPriceCell CmProductPriceCell-stock"><?=CmProductPriceSupplierStock_x($price, $aRes, 'list')?></div>
            <?php }
            if(PRICES_DISPLAY_QUANTITY){ ?>
                <div class="CmProductPriceCell CmProductPriceCell-available">
                    <?php if($mode == 'intable'){ ?>
                        <?=CmProductPriceDeliveryBadge_x($price)?>
                        <div class="cm_AvalNotHide CmProductPriceAvailabilityInline CmTitShow" title="<?=CmProductPriceAvailableTitle_x($price)?>">
                            <span class="CmProductPriceAvailabilityIcon"><?=CmProductPriceIcon_x('available', 'CmAvalOnPage fillBg')?></span>
                            <span class="CmProductPriceAvailabilityValue"><?php CmProductPriceAvailable_x($price); ?></span>
                        </div>
                    <?php }else{ ?>
                        <div class="cm_AvalNotHide CmTitShow" title="<?=CmProductPriceAvailableTitle_x($price)?>">
                            <?php CmProductPriceAvailable_x($price); ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <?php if($showSvgOptionsColumn){ ?>
                <div class="CmProductPriceCell CmProductPriceCell-svg-options">
                    <?=CmProductPriceOptions_x($price, 'CmOptionsBlockInfo CmTablePriceSvgOptionsInner', 'CmOptionView', 'svg')?>
                </div>
            <?php } ?>
            <?=CmProductPriceValueCell_x($price, $aProd, $aRes, $aSets, $arConSets, $ProductURL, '', true, $showOptions, $showSvgOptionsColumn ? 'text' : 'all', $mode)?>
        </div>
        <?php return ob_get_clean();
    }

    function CmProductPriceList_x($prices, $firstPr, $aProd, $aRes, $aSets, $arConSets, $ProductURL, $noSuplStock, $showOptions, $limitVisible = 0, $mode = 'list', $showSvgOptionsColumn = false){
        $showSupplierStockColumn = ($noSuplStock && CmProductPriceHasSupplierStock_x($prices, $aRes));
        $listClasses = array('CmProductPriceList', 'CmTablePriceWrap');
        if($mode == 'dropdown'){
            $listClasses[] = 'CmProductPriceList-dropdown';
        }elseif($mode != ''){
            $listClasses[] = 'CmProductPriceList-'.$mode;
        }
        if($mode == 'intable'){
            if($showSupplierStockColumn){
                $listClasses[] = 'CmProductPriceList-has-stock';
            }
            if(PRICES_DISPLAY_QUANTITY){
                $listClasses[] = 'CmProductPriceList-has-available';
            }
        }
        ob_start(); ?>
        <div class="<?=implode(' ', $listClasses)?>">
            <?php if($mode != 'dropdown' && $mode != 'intable'){
                echo CmProductPriceHeader_x($firstPr, $showSupplierStockColumn, $showOptions, $showSvgOptionsColumn);
            } ?>
            <div class="CmTbodyPrice CmProductPriceRows">
                <?php
                $rowIndex = 0;
                foreach($prices as $price){
                    $rowIndex++;
                    $rowType = ($rowIndex == 1) ? 'first' : 'visible';
                    if($limitVisible > 0 && $rowIndex > $limitVisible){
                        $rowType = 'hidden';
                    }
                    echo CmProductPriceRow_x($price, $firstPr, $aProd, $aRes, $aSets, $arConSets, $ProductURL, $showSupplierStockColumn, $showOptions, $rowType, $mode, $showSvgOptionsColumn);
                } ?>
            </div>
        </div>
        <?php return ob_get_clean();
    }

    function CmProductPriceNoStockActions_x($aProd, $aRes, $ProductURL, $tableMode = false, $noStockClass = 'AvalAsk CmProductPriceNoStock'){
        ob_start();
        if($tableMode){ ?>
            <div></div>
            <div class="CmAvalAskPrBlock CmProductPriceNoStockActions">
        <?php }else{ ?>
            <div class="<?=$noStockClass?>" data-artnum="<?=$aProd['ArtNum']?>" data-brand="<?=$aProd['Brand']?>">
        <?php }

        if($aRes['FINDPRICE_BUTTON']){ ?>
            <a href="<?=$aProd['FindPriceLink']?>" class="CmPriceAskBut CmProductPriceAskButton" <?=$aRes['FindPrice_isBlank']?>>
                <?=CmProductPriceIcon_x('ask', 'CmAskImg')?>
                <span><?=Lng_x('Get_a_price',0)?></span>
            </a>
        <?php }else{
            if(!$tableMode){ ?>
                <div class="cmNoInStock">
                    <?=CmProductPriceIcon_x('available', 'CmAvalOnPage')?>
                    <span><?=Lng_x('No_in_stock',1)?></span>
                </div>
                <div class="CmAksNotAvWrapBl">
            <?php }
            if($aRes['ASK_PRICE']){ ?>
                <div class="<?php if($tableMode){ ?>ListAskPrice_t CmAskPrice<?php }else{ ?>ListAskPrice<?php } ?> CmProductPriceAskButton" data-tab="AskPrice" data-artnum="<?=$aProd['ArtNum']?>" data-brand="<?=$aProd['Brand']?>" data-moduledir="<?=CM_DIR?>" data-lang="<?=LANG_x?>" data-link="<?=$ProductURL?>">
                    <?=CmProductPriceIcon_x('ask', $tableMode ? 'cm_askImg_t' : 'CmAskImg')?>
                    <span><?=Lng_x('Ask_price')?></span>
                </div>
            <?php }
            if($aRes['ALLOW_ORDER']){ ?>
                <div class="CmAddToCart <?php if($tableMode){ ?>ListNotAvailable_t<?php }else{ ?>ListNotAvailable<?php } ?> CmProductPriceOrderButton CmProductPriceCartButton" data-furl="<?php if($tableMode){ echo $aProd['Link']; }else{ echo $ProductURL; } ?>" data-priceid="order">
                    <?=CmProductPriceIcon_x('cart', $tableMode ? 'cm_cartImg_t' : 'cm_cartImg')?>
                    <span><?=Lng_x('Order')?></span>
                </div>
            <?php }
            if($tableMode && !$aRes['ASK_PRICE'] && !$aRes['ALLOW_ORDER']){ ?>
                <div class="CmNoInStock_t">
                    <?=CmProductPriceIcon_x('box', 'CmNotInStockImg_t')?>
                    <span><?=Lng_x('No_in_stock',1)?></span>
                </div>
            <?php }
            if(!$tableMode){ ?>
                </div>
            <?php }
        }
        ?></div><?php
        return ob_get_clean();
    }
}

if(!function_exists('CmPricesBlockAssets_x')){
    function CmPricesBlockAssets_x($view){
        if(!empty($GLOBALS['CmPricesBlockAssetsInHead'])){return;}
        static $loaded = array();
        $view = preg_replace('~[^a-z0-9_\-]~i', '', (string)$view);
        $assets = array(
            'css/common.css',
            'css/'.$view.'.css',
            'js/common.js',
            'js/'.$view.'.js'
        );
        foreach($assets as $asset){
            $path = __DIR__.'/'.$asset;
            if(!is_file($path)){continue;}
            $rel = 'templates/'.TEMPLATE_x.'/prices_block/'.$asset;
            if(isset($loaded[$rel])){continue;}
            $loaded[$rel] = true;
            if(substr($asset, -4) == '.css'){
                ?><link rel="stylesheet" href="<?=CmAssetUrl_x($rel)?>" type="text/css"><?php
            }else{
                ?><script src="<?=CmAssetUrl_x($rel)?>"></script><?php
            }
        }
    }

    function CmPricesBlockResolveView_x($params, $aSets, $aRes){
        $view = isset($params['VIEW']) ? (string)$params['VIEW'] : 'auto';
        if($view != '' && $view != 'auto'){
            return $view;
        }
        $context = isset($params['CONTEXT']) ? (string)$params['CONTEXT'] : 'auto';
        $activeTab = isset($aRes['ACTIVE_TAB']) && $aRes['ACTIVE_TAB'] ? $aRes['ACTIVE_TAB'] : 'LIST';
        if($context == 'products_list' && $activeTab == 'TABLE'){
            return 'intable';
        }
        if($context == 'products_list' && $activeTab == 'GRID'){
            return 'card';
        }
        if(!empty($aSets['NOT_HIDE_PRICES'])){
            return 'grid';
        }
        return 'card';
    }

    function PrintPrices_x($params = array()){
        global $aProd, $aRes, $aSets, $arConSets, $CPMod, $ProductURL, $PodPriceNum, $aListSVG;
        if(!is_array($params)){$params = array();}
        if(!isset($params['VIEW']) && isset($_REQUEST['CmPriceView'])){
            $cmRequestView = preg_replace('/[^a-z_]/', '', (string)$_REQUEST['CmPriceView']);
            if(in_array($cmRequestView, array('card','grid','intable'), true)){
                $params['VIEW'] = $cmRequestView;
            }
        }
        if(!isset($params['CONTEXT']) && isset($_REQUEST['CmPriceContext'])){
            $cmRequestContext = preg_replace('/[^a-z_]/', '', (string)$_REQUEST['CmPriceContext']);
            if(in_array($cmRequestContext, array('product_page','products_list','auto'), true)){
                $params['CONTEXT'] = $cmRequestContext;
            }
        }

        $cmProd = isset($params['PRODUCT']) ? $params['PRODUCT'] : $aProd;
        $cmRes = isset($params['RES']) ? $params['RES'] : $aRes;
        $cmSets = isset($params['SETS']) ? $params['SETS'] : $aSets;
        $cmProductUrl = isset($params['PRODUCT_URL']) ? $params['PRODUCT_URL'] : $ProductURL;
        if($cmProductUrl == '' && is_array($cmProd)){
            $cmProductUrl = GetProductLink_x($cmProd);
        }
        if(!is_array($arConSets)){$arConSets = array();}
        if(!isset($cmRes['ACTIVE_TAB']) || !$cmRes['ACTIVE_TAB']){$cmRes['ACTIVE_TAB'] = 'LIST';}

        $cmView = CmPricesBlockResolveView_x($params, $cmSets, $cmRes);
        $cmViewFile = __DIR__.'/view_'.$cmView.'.php';
        if(!is_file($cmViewFile)){
            $cmView = 'card';
            $cmViewFile = __DIR__.'/view_card.php';
        }
        CmPricesBlockAssets_x('view_'.$cmView);

        $cmAjaxCut = !array_key_exists('AJAX_CUT', $params) || $params['AJAX_CUT'];
        if($cmAjaxCut){AjaxCut_x('ProductPrices'.$PodPriceNum);}

        $aProd = $cmProd;
        $aRes = $cmRes;
        $aSets = $cmSets;
        $ProductURL = $cmProductUrl;
        $cmPrices = array();
        if(!empty($aProd['PRICES']) && is_array($aProd['PRICES'])){
            $cmPrices = array_values($aProd['PRICES']);
        }
        $PricesCount = count($cmPrices);
        $firstPr = $PricesCount ? $cmPrices[0] : array();
        $extraPrices = $PricesCount > 1 ? array_slice($cmPrices, 1) : array();
        $visiblePrices = $cmPrices;
        $noSuplStock = (!empty($aRes['SHOW_SUPPLIER']) || !empty($aRes['SHOW_STOCK']));
        $prOpt = array();
        foreach($extraPrices as $priceAr){
            if(!empty($priceAr['OPTIONS_VIEW'])){$prOpt[] = $priceAr['OPTIONS_VIEW'];}
        }
        $showOptions = $PricesCount ? CmProductPriceHasOptions_x($firstPr, $prOpt) : false;
        $showSvgOptionsColumn = $PricesCount ? CmProductPriceHasSvgOptions_x($visiblePrices) : false;
        $cmContext = isset($params['CONTEXT']) ? $params['CONTEXT'] : 'auto';

        $cmVars = array(
            'aProd' => $aProd,
            'aRes' => $aRes,
            'aSets' => $aSets,
            'arConSets' => $arConSets,
            'ProductURL' => $ProductURL,
            'PodPriceNum' => $PodPriceNum,
            'PricesCount' => $PricesCount,
            'firstPr' => $firstPr,
            'extraPrices' => $extraPrices,
            'visiblePrices' => $visiblePrices,
            'noSuplStock' => $noSuplStock,
            'showOptions' => $showOptions,
            'showSvgOptionsColumn' => $showSvgOptionsColumn,
            'cmPriceView' => $cmView,
            'cmPriceContext' => $cmContext
        );
        extract($cmVars, EXTR_SKIP);
        include $cmViewFile;

        if(WS_ACTIVE){ ?>
            <div class="CmWsLoadBar"><div class="CmWsLBCh"></div><div class="CmWsLBCh"></div></div>
        <?php }
        if($cmAjaxCut){AjaxCut_x('ProductPrices'.$PodPriceNum);}

        $showWhatsApp = !array_key_exists('WHATSAPP', $params) || $params['WHATSAPP'];
        if($showWhatsApp){echo CmPricesBlockWhatsAppButton_x($aProd);}
    }
}

$CmPricesBlockAutoRun = isset($CmPricesBlockAutoRun) ? $CmPricesBlockAutoRun : true;
if($CmPricesBlockAutoRun){
    PrintPrices_x(isset($CmPricesBlockParams) && is_array($CmPricesBlockParams) ? $CmPricesBlockParams : array());
}
