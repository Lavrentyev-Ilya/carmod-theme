<?php
VerifyAccess_x('PricesBlock_intable.templ');
if($PricesCount && !$aRes['FINDPRICE_BUTTON']){
    echo CmProductPriceList_x($visiblePrices, $firstPr, $aProd, $aRes, $aSets, $arConSets, $ProductURL, $noSuplStock, $showOptions, 0, 'intable', false);
}else{
    ?><div class="CmTableProductNoStockBlock"><?=CmProductPriceNoStockActions_x($aProd, $aRes, $ProductURL, false, 'CmProductPriceNoStock')?></div><?php
}
?>