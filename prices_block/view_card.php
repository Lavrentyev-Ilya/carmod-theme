<?php
VerifyAccess_x('PricesBlock_card.templ');
if($PricesCount && !$aRes['FINDPRICE_BUTTON']){ ?>
    <?php
    $CmTopPriceOptions = array();
    if(!empty($firstPr['OPTIONS_VIEW']) && is_array($firstPr['OPTIONS_VIEW'])){
        foreach($firstPr['OPTIONS_VIEW'] as $code => $aOpt){
            if($code == 'Hot_price'){continue;}
            $value = (is_array($aOpt) && isset($aOpt['Value'])) ? $aOpt['Value'] : $aOpt;
            if(trim((string)$value) == ''){continue;}
            $CmTopPriceOptions[] = $value;
        }
    }
    if(count($CmTopPriceOptions) > 0){ ?>
        <div class="DelAvalStock CmProductPriceTopMeta <?php if($aRes['ACTIVE_TAB'] == 'GRID'){ ?>CmProductPriceTopMeta-centered<?php } ?>">
            <div class="CmOptTablePP <?php if(count($CmTopPriceOptions) < 3){ ?>CmOptTabGrid<?php } ?>">
                <?php foreach($CmTopPriceOptions as $value){ ?>
                    <div class="CmOptionTd"><?=$value?></div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
    <div class="CmPriceProd CmProductPriceBox" data-txta="<?=CmProductPriceAvailableTitle_x($firstPr)?>" data-txtd="<?=CmProductPriceDeliveryTitle_x($firstPr)?>">
        <div class="CmAvalBlPriceBl CmProductPriceMainLine">
            <?=CmProductPriceCompactStatus_x($firstPr)?>
            <div class="CmPriceFormated">
                <div class="CmPriceFormText"><?=$firstPr['PRICE_FORMATED'];?></div>
            </div>
        </div>
        <?php CmProductPriceCartAction_x($firstPr, $aProd, $aRes, $aSets, $arConSets, $ProductURL, 'compact'); ?>
        <?php $SupplierStockHtml = CmProductPriceSupplierStock_x($firstPr, $aRes, 'compact');
        if($SupplierStockHtml != ''){ ?>
            <div class="CmProductPriceBottomMeta"><?=$SupplierStockHtml?></div>
        <?php } ?>
    </div>
    <?php if($firstPr['RULE_PATTERN']){ ?>
        <div class="CmDiscountPrice CmProductPriceVatBelow"><span><?=$firstPr['RULE_PATTERN']?></span></div>
    <?php } ?>
<?php }else{
    echo CmProductPriceNoStockActions_x($aProd, $aRes, $ProductURL, ($aRes['ACTIVE_TAB'] == 'TABLE'));
}

if($PricesCount && count($extraPrices) > 0 && !$aRes['FINDPRICE_BUTTON']){ ?>
    <div class="CmMorePrices <?php if($aRes['ACTIVE_TAB'] == 'TABLE'){ ?>CmMorePrices-table<?php } ?>">
        &#9660; <?=Lng_x('Show_more_prices')?> (<?=count($extraPrices)?>)
        <div class="morePricestab CmProductMorePricesPanel">
            <?=CmProductPriceList_x($extraPrices, $firstPr, $aProd, $aRes, $aSets, $arConSets, $ProductURL, $noSuplStock, $showOptions, 0, 'dropdown')?>
            <div class="CmMorePriceBlClose"><?=CmProductPriceIcon_x('close', 'CmCloseMorePr')?></div>
        </div>
    </div>
<?php } ?>
