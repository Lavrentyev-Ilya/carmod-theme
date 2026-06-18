<?php
VerifyAccess_x('PricesBlock_grid.templ');
if($PricesCount && !$aRes['FINDPRICE_BUTTON']){
    echo CmProductPriceList_x($visiblePrices, $firstPr, $aProd, $aRes, $aSets, $arConSets, $ProductURL, $noSuplStock, $showOptions, 4, 'list', $showSvgOptionsColumn);
    if($PricesCount > 4){ ?>
        <div class="CmShowMorePrice" data-hide="<?=Lng_x('Hide')?>&nbsp;&#9650;" data-show="<?=Lng_x('Show_more')?>(<?=$PricesCount - 4?>)&nbsp;&#9660;"><?=Lng_x('Show_more')?>(<?=$PricesCount - 4?>)&nbsp;&#9660;</div>
    <?php }
}else{
    echo CmProductPriceNoStockActions_x($aProd, $aRes, $ProductURL, ($aRes['ACTIVE_TAB'] == 'TABLE'));
}
?>
