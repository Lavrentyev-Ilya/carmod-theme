<?VerifyAccess_x('ProductListList.templ');?>
<?//print_r($_REQUEST);?>
<?

//RATING STARS FUNCTION
function renderStarRating($rating,$maxRating=5) {
    $fullStar = '<svg viewBox="0 0 24 24" width="22"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>';
    $halfStar = '<svg viewBox="0 0 24 24" width="22"><path d="M12 5.173l2.335 4.817 5.305.732-3.861 3.71.942 5.27-4.721-2.524v-12.005zm0-4.586l-3.668 7.568-8.332 1.151 6.064 5.828-1.48 8.279 7.416-3.967 7.416 3.966-1.48-8.279 6.064-5.827-8.332-1.15-3.668-7.569z"/></svg>';
    $emptyStar = '<svg viewBox="0 0 24 24" width="22"><path d="M12 5.173l2.335 4.817 5.305.732-3.861 3.71.942 5.27-4.721-2.524-4.721 2.525.942-5.27-3.861-3.71 5.305-.733 2.335-4.817zm0-4.586l-3.668 7.568-8.332 1.151 6.064 5.828-1.48 8.279 7.416-3.967 7.416 3.966-1.48-8.279 6.064-5.827-8.332-1.15-3.668-7.569z"/></svg>';
    $rating = $rating / 999 * 5.001;
    // $rating = $rating <= $maxRating?$rating:$maxRating;

    $fullStarCount = (int)$rating;
    $halfStarCount = round($rating)-$fullStarCount;
    $emptyStarCount = $maxRating-$fullStarCount-$halfStarCount;

    $html = str_repeat($fullStar,$fullStarCount);
    $html .= str_repeat($halfStar,$halfStarCount);
    $html .= str_repeat($emptyStar,$emptyStarCount);
    $html = '<div class="cmRatingSt_l">'.$html.'</div>';
    return $html;
}
// END RATING STARS FUNCTION
if(!function_exists('CmListFitPosNameKey_x')){
	function CmListFitPosNameKey_x($Name){
		$Name = html_entity_decode(strip_tags((string)$Name),ENT_QUOTES,'UTF-8');
		$Name = str_replace(Array("\xc2\xa0",':','：'),Array(' ','',''),$Name);
		$Name = preg_replace('~\s+~u',' ',trim($Name));
		return mb_strtolower($Name,'UTF-8');
	}
}
if(!function_exists('CmListIsFitPosName_x')){
	function CmListIsFitPosName_x($Name){
		$NameKey = CmListFitPosNameKey_x($Name);
		$aNames = Array(
			Lng_x('Installation side',1),
			Lng_x('Installation side',0),
			'Installation side',
			'Fitting Position',
			'Сторона установки'
		);
		foreach($aNames as $FitName){
			if($NameKey==CmListFitPosNameKey_x($FitName)){return true;}
		}
		return false;
	}
}
if(!function_exists('CmListFitPosExtraText_x')){
	function CmListFitPosExtraText_x($Value,$Unit=''){
		$Value = trim(html_entity_decode(strip_tags((string)$Value),ENT_QUOTES,'UTF-8'));
		$Unit = trim(html_entity_decode(strip_tags((string)$Unit),ENT_QUOTES,'UTF-8'));
		if($Value=='' AND $Unit==''){return '';}
		if($Unit!=''){$Value = trim($Value.' '.$Unit);}
		return htmlspecialchars($Value,ENT_QUOTES,'UTF-8');
	}
}
if(!function_exists('CmListFitPosCleanExtraText_x')){
	function CmListFitPosCleanExtraText_x($Text,$FrontRear){
		$Text = trim(html_entity_decode(strip_tags((string)$Text),ENT_QUOTES,'UTF-8'));
		if($Text==''){return '';}
		if($FrontRear=='R'){
			$aPrefixes = Array(
				Lng_x('Rear',true),
				Lng_x('Rear',0),
				'Rear Axle',
				'Rear',
				'Back',
				'Сзади',
				'Задний мост',
				'Задняя ось',
				'Задний',
				'Задняя',
				'Заднее'
			);
		}else{
			$aPrefixes = Array(
				Lng_x('Front',true),
				Lng_x('Front',0),
				'Front Axle',
				'Front',
				'Передний мост',
				'Передняя ось',
				'Спереди',
				'Передний',
				'Передняя',
				'Переднее'
			);
		}
		usort($aPrefixes,function($a,$b){return mb_strlen($b,'UTF-8')-mb_strlen($a,'UTF-8');});
		$aParts = preg_split('~\s*,\s*~u',$Text);
		$aCleanParts = Array();
		foreach($aParts as $Part){
			$Part = trim($Part);
			foreach($aPrefixes as $Prefix){
				$Prefix = trim((string)$Prefix);
				if($Prefix==''){continue;}
				$Part = preg_replace('~^'.preg_quote($Prefix,'~').'\s*[-–—:]?\s*~iu','',$Part);
			}
			$Part = trim($Part);
			if($Part!=''){$aCleanParts[$Part] = $Part;}
		}
		if(count($aCleanParts)){
			$Text = implode(', ',$aCleanParts);
		}else{
			$Text = '';
		}
		return htmlspecialchars($Text,ENT_QUOTES,'UTF-8');
	}
}
global $CPMod;
?>
<div class="cm_listView">
    <div class="main_bl">
        <?foreach($aRes['PRODUCTS'] as $PKEY=>$aProd) {
            $bColor = $aProd['BColor']?>
            <div class="row_bl CmAdmButsProduct" <?=isAdmButs_Product_x($aProd)?> >
                <div class="CmInnerBlockList">
                    <a href="<?=$aProd['Link']?>" class="CmMobViewBrandNameBlock">
                        <div class="CmProdLinkNameMob" style="color:#<?if($bColor){echo $bColor;}?> !important;" data-rat="<?=$aProd['Rating']?>">
                            <h2 class="CmListName" style="color:#<?if($bColor){echo $bColor.'!important';}else{?>3E3E3E<?}?>;"><?=$aProd['Name']?></h2>
                        </div>
                    </a>
                    <div class="CmleftBlWrap" style="<?if($aSets['NOT_HIDE_PRICES']==1 || $aProd['PRICES']){?>width:190px; min-width:unset;<?}?>">
                        <div class="tit_art" style="background-color:#<?if($bColor){echo $bColor.'24';}else{echo 'cecece';}?>;">
                           <a href="<?=$aProd['Link']?>" class="CmArtBraLink" style="color:#<?echo $bColor?$bColor:'505050';?>"><b><?=$aProd['Brand']?></b> <?=$aProd['ArtNum']?></a>
                        </div>
                        <?EditProductButton_x($aProd['Pom'],Array(
							'Brand'=>$aProd['Brand'],
							'ArtNum'=>$aProd['ArtNum'],
							'ProdID'=>intval($aProd['ProdID']),
							'HasRemoteArticle'=>(!empty($aProd['ArtID'])?1:0)
						)); //Displayed only for ADMIN?>
						<div class="CmImgLogoWrapBlock">
                            <div class="CmImgLogoProd" <?if($aSets['NOT_HIDE_PRICES']==1 || $aProd['PRICES']){?>style="grid-template-columns:1fr; padding:15px;"<?}?>>
                                <?if($aSets['NOT_HIDE_PRICES']!=1){?>
                                    <div class="CmBrandLogoLeftBl" style="background-image:url(<?=$aProd['Logo']?>)"></div>
                                <?}?>
                                <?if(($aProd['Schema_src']&&$aProd['Schema_src']!='')||($aProd['Image']&&$aProd['Image']!='')){
                                    if($aProd['Schema_src'] || $aProd['Image']){?>
                                        <div class="img_bl img_blHov cm_curMove ProductImg BoxGlr_n" style="<?if($aSets['NOT_HIDE_PRICES']==1 && $aProd['PRICES']){?>width:auto; min-width:unset;<?}?>">
                                            <?if($aProd['Schema_src']&&$aProd['Schema_src']!=''){?>
                                                <div class="CmSchemaCoordsWrap">
                                                    <img class="CmProdIm GlrImg_n" src="<?=$aProd['Schema_src']?>" alt="<?=$aProd['Brand'].' '.$aProd['ArtNum'].' - '.$aProd['Name'].' '.$_SERVER['SERVER_NAME']?>" data-pictype="schema">
                                                    <?=$aProd['Schema_html']?>
                                                </div>
                                            <?}else if($aProd['Image']&&$aProd['Image']!=''){?>
                                                <img class="CmProdIm GlrImg_n" src="<?=$aProd['Image']?>" alt="<?=$aProd['Brand'].' '.$aProd['ArtNum'].' - '.$aProd['Name'].' '.$_SERVER['SERVER_NAME']?>">
                                            <?}?>
											<span class="OpenPopup_n"></span>
                                        </div>
                                    <?}
                                }else{?>
                                    <div class="CmNoFotoImg">
                                        <?=$aListSVG['CmNoFotoImg']?>
                                    </div>
                                <?}?>
                                <?if($aProd['Rating']&&$aProd['Rating']!=''){?>
                                    <div class="CmStarRatingInfo">
                                        <?=renderStarRating($aProd['Rating'], 5)?>
                                    </div>
                                <?}?>
                            </div>
                        </div>
                    </div>
                    <div class="CmDescInfoPriceBlock" <?//if($aSets['NOT_HIDE_PRICES']==1 && count($aProd['PRICES'])>1){?><?//}?>>
                        <div class="desc_bl">
                            <div class="CmNameInfoPropsWrapBl">
                                <div class="CmFitInfoBlWrap">
                                    <div class="info_bl" data-artnum="<?=$aProd['ArtNum']?>" data-brand="<?=$aProd['Brand']?>" data-link="<?=$aProd['Link']?>">
										<?if($aProd['COUNTRY']){?>
											<div class="CmCountry_l CmCountryList CmTitShow" title="<?=$aProd['COUNTRY_NAME']?>" style="background-image:url(/<?=CM_DIR?>/media/country/<?=$aProd['COUNTRY']?>.png)"></div>
										<?}?>
										<div class="ProductInfoOe infoIcon CmTitShow" data-furl="<?=PROTOCOL_DOMAIN_x?><?=$aProd['Link']?>" data-moduledir="<?=CM_DIR?>" data-tab="Articles" title="<?=Lng_x('OE_Numbers');?>">
                                            <svg class="cm_svgInfo" viewBox="0 -1 24 24"><path d="M12 2c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0-2c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-.001 5.75c.69 0 1.251.56 1.251 1.25s-.561 1.25-1.251 1.25-1.249-.56-1.249-1.25.559-1.25 1.249-1.25zm2.001 12.25h-4v-1c.484-.179 1-.201 1-.735v-4.467c0-.534-.516-.618-1-.797v-1h3v6.265c0 .535.517.558 1 .735v.999z"/></svg>
                                        </div>
                                        <div class="ProductInfoSuit carIcon CmTitShow" data-furl="<?=PROTOCOL_DOMAIN_x?><?=$aProd['Link']?>" data-moduledir="<?=CM_DIR?>" data-tab="Vehicles" title="<?=Lng_x('Suitable_vehicles');?>">
                                            <svg class="material-icon car_x" viewBox="0 0 24 24">
                                                <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>
                                            </svg>
                                        </div>
                                        <div class="analogButt cm_evenButt CmTitShow" title="<?=Lng_x('Lookup_analogues');?>">
                                            <a class="CmLookAnalogHook" href="<?=$aProd['Link']?>#ProductsList">
                                                <svg class="material-icon analog_x" viewBox="0 -3 24 24">
                                                    <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <a href="<?=$aProd['Link']?>" class="CmNameInfoWrapBl">
                                    <div class="CmRateNameBlock">
                                        <div class="CmProductLinkArtName" style="color:#<?if($bColor){echo $bColor;}?> !important;">
                                            <h2 class="CmListName"><?=$aProd['Name']?></h2>
                                            <?if($aProd['Names']){?>
                                                <span class="CmAdditName" style="color:#<?if($bColor){echo $bColor.'!important';}else{?>3E3E3E<?}?>;"><?=current($aProd['Names'])?></span>
                                            <?}?>
                                        </div>
                                    </div>
                                </a>
                                <div class="CmNamePropsBlock">
									<?if($aProd['TrdNums']){?>
                                        <div class="CmListTrdNums"><b><?=Lng_x('Trade_numbers',1)?></b>: <?=$aProd['TrdNums']?></div>
                                    <?}?>
                                    <?if($aProd['PartsIncluded']){?>
                                        <div class="CmListTrdNums"><b><?=Lng_x('Parts_included',1)?></b>: <?=$aProd['PartsIncluded']?></div>
                                    <?}?>
									
									
									
									
									
									<?
									//Car Fitting position
									//////////////////////
									$FitPosCode = GetFitPosCode_x($aProd);
									$ShowFitPositionBlock = false;
									$aFitPositionExtraTexts = Array();
									$aSkipFitCriteria = Array();
									$aSkipFitProperties = Array();
									if($FitPosCode!=''){
										if(is_array($aProd['CRITERIAS'])){
											foreach($aProd['CRITERIAS'] as $CriID=>$aCri){
												if(CmListIsFitPosName_x($aCri['Name'])){
													$FitExtraText = CmListFitPosExtraText_x($aCri['Value'],$aCri['Unit']);
													if($FitExtraText!=''){$aFitPositionExtraTexts[$FitExtraText] = $FitExtraText;}
													$aSkipFitCriteria[$CriID] = true;
												}
											}
										}
										if(is_array($aProd['PROPERTIES'])){
											foreach($aProd['PROPERTIES'] as $PropName=>$PropValue){
												if(CmListIsFitPosName_x($PropName)){
													$FitExtraText = CmListFitPosExtraText_x($PropValue);
													if($FitExtraText!=''){$aFitPositionExtraTexts[$FitExtraText] = $FitExtraText;}
													$aSkipFitProperties[$PropName] = true;
												}
											}
										}
									}
									if($FitPosCode!=''){
										$FiTx=''; $FiShow=false;
										$FrontRear = substr($FitPosCode,0,1);
										$LeftRight = substr($FitPosCode,1,1);
										if($FrontRear=='F'){$FiTx=Lng_x('Front',0).' '; $FiShow=true;}
										if($FrontRear=='R'){$FiTx=Lng_x('Rear',0).' '; $FiShow=true;}
										if($LeftRight=='L'){$FiTx.=Lng_x('Left',0).' ';}
										if($LeftRight=='R'){$FiTx.=Lng_x('Right',0).' ';}
										if(count($aFitPositionExtraTexts)){
											$aCleanFitPositionExtraTexts = Array();
											foreach($aFitPositionExtraTexts as $FitExtraText){
												$FitExtraText = CmListFitPosCleanExtraText_x($FitExtraText,$FrontRear);
												if($FitExtraText!=''){$aCleanFitPositionExtraTexts[$FitExtraText] = $FitExtraText;}
											}
											$aFitPositionExtraTexts = $aCleanFitPositionExtraTexts;
										}
										if($FiShow){$ShowFitPositionBlock = true;?>
											<div class="CmCarFitPos">
												<div class="CmCarFitPosSvg OnlyPos<?=$LeftRight?>">
													<?=$aListSVG['Car'.$FrontRear.'X']?>
												</div>
												<div class="CmCarFitPosTxt">
													<?=Lng_x('Fitting',0)?>:<br>
													<span><?=$FiTx?></span>
													<?if(count($aFitPositionExtraTexts)){?>
														<div class="CmCarFitPosExtra"><?=implode('<br>',$aFitPositionExtraTexts)?></div>
													<?}?>
												</div>
											</div><?
										}
									}?>
									
									
									
									
                                    <?if($aProd['CRITERIAS']){
									  $aVisibleCriteria = Array();
									  foreach($aProd['CRITERIAS'] as $CriID=>$aCri){
										  if($ShowFitPositionBlock AND isset($aSkipFitCriteria[$CriID])){continue;}
										  $aVisibleCriteria[$CriID] = $aCri;
									  }
                                      $countCr = count($aRes['CRITERIAS']);
                                      $resCr = count($aRes['CRITERIAS']) - 4;
									  if(count($aVisibleCriteria)){?>
                                        <div class="desc_3">
                                            <ul class="props_l c_Tx">
                                            <?foreach($aVisibleCriteria as $CriID=>$aCri){?>
                                                <li><?=$aCri['Name']?>: <?=$aCri['Value']?> <?=$aCri['Unit']?></li>
                                            <?}?>
                                            </ul>
                                        </div>
									  <?}?>
                                    <?}?>
                                    <?if($aProd['PROPERTIES']){
										$aVisibleProperties = $aProd['PROPERTIES'];
										if($ShowFitPositionBlock AND count($aSkipFitProperties)){
											foreach($aSkipFitProperties as $PropName=>$SkipValue){unset($aVisibleProperties[$PropName]);}
										}
                                        $i = 0;
										if(count($aVisibleProperties)){?>
                                        <div class="CmPropsWrapBl" <?if(count($aVisibleProperties)>4){?>data-props="Y"<?}?>>
                                            <?foreach($aVisibleProperties as $propN => $propV) {$i++;
                                                //if($i<=4){?>
                                                    <div class="CmPropsListItem CmListProps">
                                                        <div class="CmPropsInnerBlock">
                                                            <span class="CmPropDesc"><?=$propN?></span>
                                                            <?if(mb_strlen(strip_tags($propV))>25){?>
                                                                <br>
                                                            <?}?>
                                                            <?if($propV!=''){?>
                                                                <span class="CmPropVal" data-str="<?=mb_strlen(strip_tags($propV))?>"><b><?=$propV?></b></span>
                                                            <?}?>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="CmPropsListItem CmListProps">
                                                        <span class="CmPropDesc"><?=$propN?></span>
                                                        <?if(mb_strlen(strip_tags($propV))>25){?>
                                                            <br>
                                                        <?}?>
                                                        <?if($propV!=''){?>
                                                            <span class="CmPropVal" data-str="<?=mb_strlen(strip_tags($propV))?>"><b><?=$propV?></b></span>
                                                        <?}?>
                                                    </div>-->
                                                <?//continue;}?>
                                                <!-- <div class="CmPropsListItem CmListProps_2">
                                                    <span class="CmPropDesc"><?=$propN?></span>
                                                    <?if(mb_strlen(strip_tags($propV))>25){?>
                                                        <br>
                                                    <?}?>
                                                    <?if($propV!=''){?>
                                                        <span class="CmPropVal" data-str="<?=mb_strlen(strip_tags($propV))?>"><b><?=$propV?></b></span>
                                                    <?}?>
                                                </div> -->
                                            <?//}?>
                                            <?//if(count($aProd['PROPERTIES'])>4){?>
                                                <!-- <div class="CmHideOverBl"></div> -->
                                            <?}?>
                                        </div>
										<?}?>
                                    <?}?>

                                </div>
                            </div>
                        </div>
                        <!-- PRICE BLOCK -->
                        <div class="rightBlock" data-price-view="<?if(!empty($aSets['NOT_HIDE_PRICES'])){?>grid<?}else{?>card<?}?>" data-price-context="products_list" <?if($aProd['WsAjax']){?>data-wsajax="1" data-dir="<?=CM_DIR?>" data-artnum="<?=$aProd['ArtNum']?>" data-brand="<?=$aProd['Brand']?>"<?}?>>
                            <?$PodPriceNum++;
                            $ProductURL = GetProductLink_x($aProd); //AddCart URL
                            PrintPrices_x(array('CONTEXT'=>'products_list','PRODUCT'=>$aProd,'RES'=>$aRes,'PRODUCT_URL'=>$ProductURL,'AJAX_CUT'=>false));?>
                            <?AdmWsRequests_x($aProd); //WebServices messages for Admin?>
                            <div class="info_bl_MobView" data-artnum="<?=$aProd['ArtNum']?>" data-brand="<?=$aProd['Brand']?>" data-link="<?=$aProd['Link']?>">
								<?if($aProd['COUNTRY']){?>
									<div class="CmCountry_l CmCountryList CmTitShow" title="<?=$aProd['COUNTRY_NAME']?>" style="margin-top:13px; background-image:url(/<?=CM_DIR?>/media/country/<?=$aProd['COUNTRY']?>.png)"></div>
								<?}?>
                                <div class="ProductInfoOe infoBlockElem infoIconMob CmTitShow" data-furl="<?=PROTOCOL_DOMAIN_x?><?=$aProd['Link']?>" data-moduledir="<?=CM_DIR?>" data-tab="Articles" title="<?=Lng_x('OE_Numbers');?>">
                                    <svg class="cm_svgInfoMob" viewBox="0 0 24 24"><path d="M12 2c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0-2c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-.001 5.75c.69 0 1.251.56 1.251 1.25s-.561 1.25-1.251 1.25-1.249-.56-1.249-1.25.559-1.25 1.249-1.25zm2.001 12.25h-4v-1c.484-.179 1-.201 1-.735v-4.467c0-.534-.516-.618-1-.797v-1h3v6.265c0 .535.517.558 1 .735v.999z"/></svg>
                                </div>
                                <div class="ProductInfoSuit infoBlockElem carIconMob CmTitShow" data-furl="<?=PROTOCOL_DOMAIN_x?><?=$aProd['Link']?>" data-moduledir="<?=CM_DIR?>" data-tab="Vehicles" title="<?=Lng_x('Suitable_vehicles');?>">
                                    <svg class="material-icon car_xMob" viewBox="0 0 24 24">
                                        <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>
                                    </svg>
                                </div>
                                <div class="infoBlockElem analogButtMob cm_evenButtMob">
                                  <a class="CmLookAnalogHook" href="<?=$aProd['Link']?>#ProductsList">
                                    <svg class="material-icon analog_xMob" viewBox="0 -2 24 24">
                                        <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"></path>
                                    </svg>
                                  </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		<?//aprint_x($aProd, '$aProd');
		}?>
	</div>
</div>
