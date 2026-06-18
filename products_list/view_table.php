<?VerifyAccess_x('ProductListTable.templ'); ?>
<?
global $CPMod, $aListSVG;
if(!function_exists('CmTableProductFitPosText_x')){
	function CmTableProductFitPosText_x($aProd){
		$FitPosCode = GetFitPosCode_x($aProd);
		if($FitPosCode==''){return '';}
		$FrontRear = substr($FitPosCode,0,1);
		$LeftRight = substr($FitPosCode,1,1);
		$aText = Array();
		if($FrontRear=='F'){$aText[] = Lng_x('Front',0);}
		if($FrontRear=='R'){$aText[] = Lng_x('Rear',0);}
		if($LeftRight=='L'){$aText[] = Lng_x('Left',0);}
		if($LeftRight=='R'){$aText[] = Lng_x('Right',0);}
		return trim(implode(', ',$aText));
	}
}
if(!function_exists('CmTableProductTextKey_x')){
	function CmTableProductTextKey_x($Text){
		$Text = html_entity_decode(strip_tags((string)$Text),ENT_QUOTES,'UTF-8');
		$Text = str_replace(Array("\xc2\xa0",':'),Array(' ',''),$Text);
		$Text = preg_replace('~\s+~u',' ',trim($Text));
		return mb_strtolower($Text,'UTF-8');
	}
}
if(!function_exists('CmTableProductIsFitPosName_x')){
	function CmTableProductIsFitPosName_x($Name){
		$NameKey = CmTableProductTextKey_x($Name);
		$aNames = Array(
			Lng_x('Installation side',1),
			Lng_x('Installation side',0),
			'Installation side',
			'Fitting Position'
		);
		foreach($aNames as $FitName){
			if($NameKey==CmTableProductTextKey_x($FitName)){return true;}
		}
		return false;
	}
}
if(!function_exists('CmTableProductPropLine_x')){
	function CmTableProductPropLine_x($Name,$Value,$Unit=''){
		$Name = trim(html_entity_decode(strip_tags((string)$Name),ENT_QUOTES,'UTF-8'));
		$Value = trim(html_entity_decode(strip_tags((string)$Value),ENT_QUOTES,'UTF-8'));
		$Unit = trim(html_entity_decode(strip_tags((string)$Unit),ENT_QUOTES,'UTF-8'));
		$Name = rtrim($Name,": \t\n\r\0\x0B");
		if($Name=='' OR $Value==''){return '';}
		if($Unit!=''){$Value = trim($Value.' '.$Unit);}
		$Title = htmlspecialchars($Name.': '.$Value,ENT_QUOTES,'UTF-8');
		return '<div class="CmTableProductProp" title="'.$Title.'"><span class="CmTableProductPropName">'.htmlspecialchars($Name,ENT_QUOTES,'UTF-8').':</span><span class="CmTableProductPropValue">'.htmlspecialchars($Value,ENT_QUOTES,'UTF-8').'</span></div>';
	}
}
if(!function_exists('CmTableProductPropsHtml_x')){
	function CmTableProductPropsHtml_x($aProd,$FitPosText=''){
		$aLines = Array();
		$aSeen = Array();
		if(trim((string)$FitPosText)!=''){
			$Line = CmTableProductPropLine_x(Lng_x('Fitting',0),$FitPosText);
			if($Line!=''){$aLines[] = $Line;}
		}
		if(!empty($aProd['CRITERIAS']) && is_array($aProd['CRITERIAS'])){
			foreach($aProd['CRITERIAS'] as $aCri){
				if(!is_array($aCri) OR CmTableProductIsFitPosName_x($aCri['Name'])){continue;}
				$Line = CmTableProductPropLine_x($aCri['Name'],$aCri['Value'],$aCri['Unit']);
				if($Line==''){continue;}
				$SeenKey = CmTableProductTextKey_x($aCri['Name'].' '.$aCri['Value'].' '.$aCri['Unit']);
				if(isset($aSeen[$SeenKey])){continue;}
				$aSeen[$SeenKey] = true;
				$aLines[] = $Line;
			}
		}
		if(!empty($aProd['PROPERTIES']) && is_array($aProd['PROPERTIES'])){
			foreach($aProd['PROPERTIES'] as $PropName=>$PropValue){
				if(is_array($PropValue) OR CmTableProductIsFitPosName_x($PropName)){continue;}
				$Line = CmTableProductPropLine_x($PropName,$PropValue);
				if($Line==''){continue;}
				$SeenKey = CmTableProductTextKey_x($PropName.' '.$PropValue);
				if(isset($aSeen[$SeenKey])){continue;}
				$aSeen[$SeenKey] = true;
				$aLines[] = $Line;
			}
		}
		if(!count($aLines)){return '';}
		return '<div class="CmTableProductProps">'.implode('',$aLines).'</div>';
	}
}
?>
<div class="CmPartTableView CmPartTableViewRemaster">
	<?foreach($aRes['PRODUCTS'] as $PKEY=>$aProd){?>
		<?
		$CmTableHasPriceList = (!empty($aProd['PRICES']) && is_array($aProd['PRICES']) && count($aProd['PRICES']) > 0 && !$aRes['FINDPRICE_BUTTON']);
		$CmTableHasSupplierStockColumn = false;
		if($CmTableHasPriceList && (!empty($aRes['SHOW_SUPPLIER']) || !empty($aRes['SHOW_STOCK']))){
			foreach($aProd['PRICES'] as $CmTablePrice){
				if(!empty($aRes['SHOW_SUPPLIER']) && isset($CmTablePrice['SUPPLIER_NAME']) && trim((string)$CmTablePrice['SUPPLIER_NAME']) != ''){
					$CmTableHasSupplierStockColumn = true;
					break;
				}
				if(!empty($aRes['SHOW_STOCK']) && isset($CmTablePrice['SUPPLIER_STOCK']) && trim((string)$CmTablePrice['SUPPLIER_STOCK']) != ''){
					$CmTableHasSupplierStockColumn = true;
					break;
				}
			}
		}
		$CmTableFitPosText = CmTableProductFitPosText_x($aProd);
		$ProductURL = GetProductLink_x($aProd); // AddCart URL
		?>
		<div class="CmTableProductRow <?if(!$CmTableHasPriceList){?>CmTableProductRow-no-stock<?}?> <?if(!$CmTableHasSupplierStockColumn){?>CmTableProductRow-no-supplier-stock<?}?> CmAdmButsProduct" <?=isAdmButs_Product_x($aProd)?>>
			<div class="CmTableProductPhotoCell">
				<div class="CmTableProductImage BoxGlr_n" data-imgsrc="<?=$aProd['Image']?>">
					<?if($aProd['Image']){?>
						<img class="CmTableProductImg GlrImg_n" src="<?=$aProd['Image']?>" alt="<?=$aProd['Brand'].' '.$aProd['ArtNum'].' - '.$aProd['Name'].' '.$_SERVER['SERVER_NAME']?>">
					<?}else{?>
						<div class="CmTableProductNoImage"></div>
					<?}?>
					<span class="OpenPopup_n"></span>
				</div>
			</div>
			<div class="CmTableProductInfoCell">
				<a class="CmTableProductBrandArt" href="<?=$aProd['Link']?>"><span><?=$aProd['Brand']?></span> <?=$aProd['ArtNum']?></a>
				<a class="CmTableProductName" href="<?=$aProd['Link']?>"><?=$aProd['Name']?></a>
				<?if($CmTableHasPriceList){?><?=CmTableProductPropsHtml_x($aProd,$CmTableFitPosText)?><?}?>
				<?=function_exists('CmPricesBlockWhatsAppButton_x') ? CmPricesBlockWhatsAppButton_x($aProd, 'CmTableProductInfoWhatsApp') : ''?>
			</div>
			<div class="CmTableProductPricesCell <?if(!$CmTableHasPriceList){?>CmTableProductPricesCell-no-stock<?}?> <?if(!$CmTableHasSupplierStockColumn){?>CmTableProductPricesCell-no-supplier-stock<?}?>">
				<?$PodPriceNum++;?>
				<div class="WsDataTb_x CmPricesBlockTableHost CmTableProductPricesBlock" data-price-view="intable" data-price-context="products_list" <?if($aProd['WsAjax']){?>data-wsajax="1" data-dir="<?=CM_DIR?>" data-artnum="<?=$aProd['ArtNum']?>" data-brand="<?=$aProd['Brand']?>"<?}?>>
					<?PrintPrices_x(array('CONTEXT'=>'products_list','VIEW'=>'intable','PRODUCT'=>$aProd,'RES'=>$aRes,'PRODUCT_URL'=>$ProductURL,'AJAX_CUT'=>false,'WHATSAPP'=>false));?>
				</div>
			</div>
		</div>
	<?}?>
</div>
<?//aprint_x($aRes, 'aRes');?>
