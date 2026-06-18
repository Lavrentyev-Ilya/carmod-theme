<?VerifyAccess_x('ProductListMain.templ');?><?
//$aRes - Controller Result array with all data
//$aSets - Page Settings array (defined at admin side: settings)
//TEMPLATE_PAGE_DIR - php Constant that contain current page template folder path

// Image ratio
// function calcAspectRatioProdList($srcWidth, $srcHeight, $maxWidth, $maxHeight) {
//     $ratioSize = [$maxWidth / $srcWidth, $maxHeight / $srcHeight];
//     $ratioSize = min($ratioSize[0], $ratioSize[1]);
//     $widthHeight['width'] = $srcWidth*$ratioSize;
//     $widthHeight['height'] .= $srcHeight*$ratioSize;
//     return $widthHeight;
// }
//aprint_x($aRes);
//echo '<pre>'; print_r($aRes); echo '</pre><br><br>'; die();
if(!function_exists('PrintPrices_x')){
	$CmPricesBlockAutoRun = false;
	require_once dirname(__DIR__).'/prices_block/template.php';
}
?>
<div class="Popup_n">
    <div class="PopupContent_n">
        <span class="Close_n">&times;</span>
        <button class="Prev_n">&#10094;</button>
        <div id="ImgContainer" class="image-container">
            <img id="PopupImage_n" src="" alt="">
			<div class="CmShemaCoord" style=""></div>
        </div>
		<div class="CmShemaBraArt"></div>
        <button class="Next_n">&#10095;</button>
        <button class="ZoomIn_n">+</button>
        <button class="ZoomOut_n">−</button>
    </div>
</div>
<?if(!isset($aRes['HIDE_HEADBOX'])){?>
	<div class="CmTopBox">
        <div class="CmHeadTitleWrapBlock">
            <div class="CmTitleBradWrap">
				<?if($aRes['BRAND_CODE']){?>
					<div class="CmTitleBox"><div class="CmHeadSecPicture" style="background-image:url(/<?=CM_DIR?>/media/brands/90/<?=$aRes['BRAND_CODE']?>.png)"></div></div>
				<?}?>
			</div>
            <div class="CmMSelectBlock CmMSelectPositionRight">
                <?CmModelSelectorWidget('default',96);?>
            </div>
        </div>
    </div>
<?}?>
<?php AjaxCut_x();
if(!isset($aRes['HIDE_HEADBOX'])){?> <!--Makes: <div id="CmAjaxBox">  -->
    <div class="CmBrTitleSearchWrap CmBrTitleSearchWrapLight">
        <div id="CmTitlH1Page"><h1><?=H1_x?></h1></div>
    </div>
<?}?>
<?
if($aRes['CONTROLLER']){
    BreadCrumbs_x(); // Edit in: ../templates/default/includes.php
}

//Include SVG
include('svg.php');
?>
<script>
    //PRICE RANGE
    PriceRange();
</script>
<?//if(IsADMIN_x){
if($aRes['HAVE_FILTERS']){?>
	<div class="CmFilterShowButton">
		<svg class="CmSvgFilterImg" viewBox="0 0 24 24"><path d="M1 0h22l-9 15.094v8.906l-4-3v-5.906z"/></svg>
		<span class="CmFilterButtonText"><?=Lng_x('Filters')?></span>
	</div>
<?}?>
<?//}?>
<div class="CmWrapFlexBlock">
	<?=ShowSEOText_x("TOP")?>
    <div class="CmFiltersMainP">
        <?if($aRes['HAVE_FILTERS']){?>
            <div class="left_fil <?if($aRes['ACTIVE_TAB']=='LIST'){echo 'CmLeftFilBor';}?>">
                <?/* if($aRes['FILTER_SECTIONS']){?>
                    <div class="cm_FsBlock">
                        <div class="CmSectionBoxWrap">
                            <a href="<?=$aRes['SECTION_PARENT_URL']?>" class="CmParentSectName CmUnderLineHover"><?=$aRes['SECTION_PARENT_NAME']?></a>
                            <div class="CmSubLevelsSectWrap">
								<?//aprint_x($aRes['FILTER_SECTIONS'])?>
                                <?foreach ($aRes['FILTER_SECTIONS'] as $Code => $a) {?>
									<?//aprint_x($a['SubSections'])?>
                                    <div class="CmSubLevNameWrap <?if(!$a['SubSections']){?>CmPadLeft23<?}?>">
                                        <?if($a['SubSections']){$l++?>

                                            <div class="<?if(!$a['isExpanded']){?>CmLickToOpen<?}?> CmPlusMinusBlock CmPlusImg CmColorFih">
                                                <?=$aListSVG['CmPlus']?>
                                            </div>
                                            <div class="<?if(!$a['isExpanded']){?>CmLickToOpen<?}?> CmPlusMinusBlock CmMinusImg CmShowHide CmColorFih">
                                                <?=$aListSVG['CmMinus']?>
                                            </div>
                                        <?}?>
                                        <a href="<?=$a['Link']?>" class="CmSecondLevelLink <?if($a['isActive']){?>CmActiveItem CmColorBr<?}?>" data-exp="<?=$a['isExpanded']?>" data-act="<?=$a['isActive']?>"><?=$a['Name']?></a>
                                    </div>
                                    <ul class="CmThirdLevelList <?if(!$a['isExpanded']){?>CmShowHide<?}?>">
                                        <?foreach($a['SubSections'] as $SubCode => $aS){?>
                                            <li class="CmThirdLevelItem">
                                                <a href="<?=$aS['Link']?>" class="CmThirdLevelLink <?if($aS['isActive']){?>CmActiveItem CmColorBr<?}?> CmColorTxh" data-act="<?=$aS['isActive']?>"><?=$aS['Name']?></a>
                                            <li>
                                        <?}?>
                                    </ul>
                                <?}?>
                            </div>
                        </div>
                    </div>
                <?} */?>
				<div class="CmBoxSrchSct">
					<div class="CmBrSearchWrap">
						<div class="CmTitleSearchBlock">
							<div class="CmSearcSectInput">
								<?
								$CmSectionSearchPlaceholder = mb_strtolower((string)Lng_x('Find product section',0),'UTF-8');
								$CmSectionSearchPlaceholder = mb_strtoupper(mb_substr($CmSectionSearchPlaceholder,0,1,'UTF-8'),'UTF-8').mb_substr($CmSectionSearchPlaceholder,1,null,'UTF-8');
								?>
								<input class="CmInputSect" data-lng="" type="text" placeholder="<?=$CmSectionSearchPlaceholder?>..">
								<div class="CmSectionSearchLoader" aria-hidden="true"><?=$aListSVG['LoadingArrows']?></div>
								<div class="CmClearButt">
									<svg class="material-icon" viewBox="0 1.5 24 24">
										<path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path>
									</svg>
								</div>
							</div>
						</div>
					</div>
					<div class="CmBoxResSct" id="CmBoxResScts">
						<div class="CmListResSects"></div>
					</div>
				</div>
				<?if($aRes['FILTER_SECTIONS']){?>
                    <div class="cm_FsBlock">
                        <div class="CmSectionBoxWrap">
                            <a href="<?=$aRes['SECTION_PARENT_URL']?>" class="CmParentSectName CmUnderLineHover"><?=$aRes['SECTION_PARENT_NAME']?></a>
                            <div class="CmSubLevelsSectWrap">
                                <?foreach ($aRes['FILTER_SECTIONS'] as $Code => $a) {?>
                                    <div class="CmSubLevNameWrap <?if(!$a['SubSections']){?>CmPadLeft23<?}?>">
                                        <?if($a['SubSections']){$l++?>
                                            <div class="<?if(!$a['isExpanded']){?>CmLickToOpen<?}?> CmPlusMinusBlock">
												<span class="ShowMinus" style="<?if(!$a['isExpanded']){?>display:none;<?}?>"><?=$aListSVG['CmMinus']?></span>
												<span class="ShowPlus" style="<?if($a['isExpanded']){?>display:none;<?}?>"><?=$aListSVG['CmPlus']?></span>
                                            </div>
                                        <?}?>
                                        <a href="<?=$a['Link']?>" class="CmSecondLevelLink <?if($a['isActive']){?>CmActiveItem<?}?>" data-exp="<?=$a['isExpanded']?>" data-act="<?=$a['isActive']?>" style="<?if($a['isExpanded']){?>text-decoration:underline;<?}?>"><?=$a['Name']?></a>
										<ul class="CmThirdLevelList <?if(!$a['isExpanded']){?>CmShowHide<?}?>">
											<?foreach($a['SubSections'] as $SubCode => $aS){?>
												<li class="CmThirdLevelItem">
													<a href="<?=$aS['Link']?>" class="CmThirdLevelLink <?if($aS['isActive']){?>CmActiveItem<?}?>" data-act="<?=$aS['isActive']?>"><?=$aS['Name']?></a>
												<li>
												<?if($aS['isActive']){
													if($aRes['FILTER_PRODUCTS']){?>
														<li class="CmProductTypeFilter">
														<?foreach($aRes['FILTER_PRODUCTS'] as $ProdID=>$aProd){?>
															<div class="CmFilterCheck CmProductTypeItem" data-prid="<?=$ProdID?>" >
																<div class="CmProductTypeCheckbox">
																	<?if($aProd['Checked']){?><div class="CmProductTypeCheckboxIn"></div><?}?>
																</div>
																<span class="CmFilCheckTxt <?if($aProd['Checked']){?>TxBold<?}?> " title="<?=$aProd['Info']?>">
																	<?=$aProd['Name']?>
																	<sup>&nbsp;<?=$aRes['PRODUCTS_TYPES'][$ProdID]?></sup><br>
																	<span><?=$aProd['Info']?></span>
																</span>
															</div>
														<?}
														$aRes['FILTER_PRODUCTS']=false; //Hide in bottom?>
														<li><?
													}
												}
											}?>
										</ul>
                                    </div>
                                <?}?>
                            </div>
                        </div>
                    </div>
                <?}?>

                <div class="CmWrapLpartBlock">
					<?if($aRes['FILTER_PRODUCTS']){?>
                        <div class="l_part">
                            <div class="l_title"><?=Lng_x('Product_type',1)?></div>
                            <hr class="titleSet">
                            <?foreach($aRes['FILTER_PRODUCTS'] as $ProdID=>$aProd){?>
                                <div class="CmFilterCheck <?if($aProd['Checked']){?>CmFilterCheckActive TxBold CmO CmBgW<?}?>" data-prid="<?=$ProdID?>">
                                    <div class="check_b">
                                        <?if($aProd['Checked']){?>
                                            <svg class="material-icon" viewBox="2 2 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                        <?}?>
                                    </div>
                                    <span class="CmFilCheckTxt" title="<?=$aProd['Info']?>"><?=$aProd['Name']?><sup>&nbsp;<?=$aRes['PRODUCTS_TYPES'][$ProdID]?></sup><br><span><?=$aProd['Info']?></span></span>
                                </div>
                            <?}?>
                        </div>
                    <?}?>
					<?if($aRes['FILTER_OEM_ANALOG']){?>
						<div class="l_part">
							<div class="l_title"><?=Lng_x('Show_only',1)?></div>
							<hr class="titleSet">
							<div class="CmTumblers">
								<?if($aRes['Articles_OEM_Count']){?>
									<a <?=$aRes['Articles_OEM_Href']?> class="CmTumButn CmTum<?=$aRes['Articles_OEM_Pushed']?>" data-tumb="OEM" >
										<span>OEM</span>
										<?if(!$aRes['HIDE_OUTPRICE']){?><span class="CmItemCount"><?=$aRes['Articles_OEM_Count']?></span><?}?>
									</a>
								<?}?>
								<?if($aRes['Articles_ANALOG_Count']){?>
									<a <?=$aRes['Articles_ANALOG_Href']?> class="CmTumButn CmTum<?=$aRes['Articles_ANALOG_Pushed']?>" data-tumb="Analogs">
										<span><?=Lng_x('Analogs')?></span>
										<?if(!$aRes['HIDE_OUTPRICE']){?><span class="CmItemCount"><?=$aRes['Articles_ANALOG_Count']?></span><?}?>
									</a>
								<?}?>
								<?if($aRes['Articles_ALL_Count']){?>
									<a <?=$aRes['Articles_ALL_Href']?> class="CmTumButn CmTum<?=$aRes['Articles_ALL_Pushed']?>" data-tumb="All">
										<span><?=Lng_x('All')?></span>
										<?if(!$aRes['HIDE_OUTPRICE']){?><span class="CmItemCount"><?=$aRes['Articles_ALL_Count']?></span><?}?>
									</a>
								<?}?>
							</div>
						</div>
					<?}?>
					<?if($aRes['FILTER_FITPOSITION']){?>
						<div class="CmSelCarSideWrap">
							<div class="CmSideTitle">
								<div class="l_title"><?=Lng_x('Installation side',1)?></div>
								<hr class="titleSet">
							</div>
							<div class="CmInstSideBlock">
								<div class="CmCarFrRR">
									<?if($aRes['FILTER_FitPos_FrontRear']){?>
										<a class="CmSelectCarSide CmFrontBl CmSelSide<?=$aRes['FILTER_FitPos_Front_Pushed']?> CmFrRr" href="<?=$aRes['FILTER_FitPos_Link_Front']?>" >
											<span class="CmCarSideTxt CmSelTxt<?=$aRes['FILTER_FitPos_Front_Pushed']?>"><?=Lng_x('Front')?></span>
											<svg class="CmCarSide CmSelSvg<?=$aRes['FILTER_FitPos_Front_Pushed']?>" viewBox="-3 32 40.967 36.800"><?=$aListSVG['CarFront']?></svg>
										</a>
									<?}?>
									<?if($aRes['FILTER_FitPos_FrontRear']){?>
                                        <a class="CmSelectCarSide CmRearBl CmSelSide<?=$aRes['FILTER_FitPos_Rear_Pushed']?> CmFrRr" href="<?=$aRes['FILTER_FitPos_Link_Rear']?>">
                                            <svg class="CmCarSide CmSelSvg<?=$aRes['FILTER_FitPos_Rear_Pushed']?>" viewBox="58 30 46.967 37.967"><?=$aListSVG['CarRear']?></svg>
                                            <span class="CmCarSideTxt CmSelTxt<?=$aRes['FILTER_FitPos_Rear_Pushed']?>"><?=Lng_x('Rear')?></span>
                                        </a>
                                    <?}?>
								</div>
								<div class="CmBackViewCar" style="<?if(!$aRes['FILTER_FitPos_FrontRear']){?>border-top:1px solid #969597<?}?>">
                                    <?if($aRes['FILTER_FitPos_LeftRight']){?>
                                        <a class="CmSelectBVSide CmBVLeft CmSelSide<?=$aRes['FILTER_FitPos_Left_Pushed']?> CmLfRt" href="<?=$aRes['FILTER_FitPos_Link_Left']?>" >
                                            <span class="CmBVTxt CmSelTxt<?=$aRes['FILTER_FitPos_Left_Pushed']?>"><?=Lng_x('SLeft')?></span>
                                            <svg class="CmBackView CmBVL CmSelSvg<?=$aRes['FILTER_FitPos_Left_Pushed']?>" viewBox="-641 0 1280.000000 825.000000"><?=$aListSVG['CarBackLeft']?></svg>
                                        </a>
                                    <?}?>
									<?if($aRes['FILTER_FitPos_LeftRight']){?>
										<a class="CmSelectBVSide CmBVRight CmSelSide<?=$aRes['FILTER_FitPos_Right_Pushed']?> CmLfRt" href="<?=$aRes['FILTER_FitPos_Link_Right']?>">
											<svg class="CmBackView CmBVR CmSelSvg<?=$aRes['FILTER_FitPos_Right_Pushed']?>" viewBox="653 0 1280.000000 825.000000"><?=$aListSVG['CarBackRight']?></svg>
											<span class="CmBVTxt CmSelTxt<?=$aRes['FILTER_FitPos_Right_Pushed']?>"><?=Lng_x('SRight')?></span>
										</a>
									<?}?>
								</div>
							</div>
						</div>
					<?}?>
					
                    <?if($aRes['FILTER_CRITERIA']){?>
                        <div class="l_part"><?
                            foreach($aRes['FILTER_CRITERIA'] as $aCri){?>
                              <div class="l_title"><?=$aCri['Name']?>:</div>
                              <hr class="titleSet">
                                <div class="l_filter"><?
                                    foreach($aCri['Values'] as $CrCode=>$CrName){
                                    if($aCri['Checked'] AND in_array($CrCode,$aCri['Checked'])){$Checked=true;}else{$Checked=false;}?>
                                      <div class="CmFilterCheck <?if($Checked){?>c_Txi TxBold<?}?>" data-crcod="<?=$CrCode?>" >
                                            <div class="check_b">
                                                <?if($Checked){?><svg class="material-icon fillBg" viewBox="2 2 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg><?}?>
                                            </div>
                                            <span>
                                                <?=$CrName?>
                                                <sup><?=$aCri['ProductsCnt'][$CrCode]?></sup>
                                            </span>
                                      </div>
                                    <?}?>
                                </div>
                            <?}?>
                        </div>
                    <?}?>
                    <?if(IsADMIN_x AND $UsePriceFilter){
                    $rangeStep = ($aRes['MAX_PRICE']-$aRes['MIN_PRICE'])/4;
                    $rangeFl = $aRes['MIN_PRICE']+$rangeStep;
                    $rangeInt = intval($aRes['MIN_PRICE']+$rangeStep);?>
                        <div class="l_title"><b><?=Lng_x('Price')?>&nbsp;<?=$aRes['CURRENCY_TEMPLATE']?></b></div>
                        <hr class="titleSet">
                        <div class="CmPriceRangeSl">
                            <div class="CmTitlePriceBl">
                                <p>
                                    <input class="CmPriceNum" type="text" id="amount" data-priceFrom="<?=$aRes['MIN_PRICE']?>" data-priceTo="<?=$aRes['MAX_PRICE']?>" readonly>
                                </p>
                                <div id="slider-range"></div>
                            </div>
                            <div class="rangePriceBlock">
                                <div class="bigRangeTextBl" style="left:0%">
                                    <span class="bigRange"></span>
                                    <span class="bigRangeText"><?=$aRes['MIN_PRICE']?></span>
                                </div>
                                    <span class="smallRange" style="left:5%"></span>
                                    <span class="smallRange" style="left:10%"></span>
                                    <span class="smallRange" style="left:15%"></span>
                                    <span class="smallRange" style="left:20%"></span>
                                <div class="bigRangeTextBl" style="left:25%">
                                    <span class="bigRange"></span>
                                    <span class="bigRangeText"><?if($aRes['MIN_PRICE']>0 && $aRes['MAX_PRICE']<=10){echo $rangeFl;}else{echo $rangeInt;}?></span>
                                </div>
                                    <span class="smallRange" style="left:30%"></span>
                                    <span class="smallRange" style="left:35%"></span>
                                    <span class="smallRange" style="left:40%"></span>
                                    <span class="smallRange" style="left:45%"></span>
                                <div class="bigRangeTextBl" style="left:50%">
                                    <span class="bigRange"></span>
                                    <span class="bigRangeText"><?if($aRes['MIN_PRICE']<10 && $aRes['MAX_PRICE']==10){echo $rangeFl*2;}else{echo $rangeInt*2;}?></span>
                                </div>
                                    <span class="smallRange" style="left:55%"></span>
                                    <span class="smallRange" style="left:60%"></span>
                                    <span class="smallRange" style="left:65%"></span>
                                    <span class="smallRange" style="left:70%"></span>
                                <div class="bigRangeTextBl" style="left:75.4%">
                                    <span class="bigRange"></span>
                                    <span class="bigRangeText"><?if($aRes['MIN_PRICE']<10 && $aRes['MAX_PRICE']==10){echo $rangeFl*3;}else{echo $rangeInt*3;}?></span>
                                </div>
                                    <span class="smallRange" style="left:80%"></span>
                                    <span class="smallRange" style="left:85%"></span>
                                    <span class="smallRange" style="left:90%"></span>
                                    <span class="smallRange" style="left:95%"></span>
                                <div class="bigRangeTextBl" style="left:100%">
                                    <span class="bigRange"></span>
                                    <span class="bigRangeText"><?=$aRes['MAX_PRICE']?></span>
                                </div>
                            </div>
                            <div class="CmRangePrSubmBut">
                                <?//if($aRes['PRICE_UPTO']>0){?>
                                    <span class="CmResetBut"><?=Lng_x('Reset',1)?></span>
                                <?//}?>
                                <span class="CmApplyBut"><?=Lng_x('Apply',1)?></span>
                            </div>
                        </div>
                    <?}?>
                    
                    <?$sUrl = '';
					if($aRes['FILTER_BRANDS']){
						$BrCnt=count($aRes['FILTER_BRANDS']);?>
						<div class="l_title"><b><?=Lng_x('Manufacturer',1)?></b></div>
						<hr class="titleSet">
                        <div class="l_part">
                            <?if($BrCnt>9){?>
								<div class="searc_inp">
									<input class="filt_sect" type="text" placeholder="<?=Lng_x('Search_button',0)?>..">
									<div class="clearButt">
										<svg class="material-icon" viewBox="0 -1 24 24">
											<path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
										</svg>
									</div>
									<span class="CmSearchNoResTxt"><?=Lng_x('No_search_result');?></span>
								</div>
							<?}?>
							<div class="CmBrandInnerBl <?if($BrCnt>25){?>CmLeftFilHeight<?}?>">
	                            <div class="l_filterBr">
	                                <?foreach($aRes['FILTER_BRANDS'] as $BCode=>$aBrand){?>
	                                    <div class="CmBrandFilter CmFilterCheck <?if($aBrand['Checked']){?>CmFilterCheckActive TxBold<?}?>" data-bcode="<?=$BCode?>">
	                                        <div class="check_b">
	                                            <?if($aBrand['Checked']){?>
	                                                <svg class="material-icon" viewBox="2 3 24 24">
	                                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
	                                                </svg>
	                                            <?}?>
	                                        </div>
	                                        <span class="CmBranName"><?=$aBrand['Name']?>
	                                            <sup><?=$aBrand['Count']?></sup>
	                                        </span>
	                                    </div>
	                                <?}?>
	                            </div>
                            </div>
                        </div>
                    <?}?>
                </div>
            </div>
        <?}?>

        <div class="main_part" style="<?if(!$aRes['HAVE_FILTERS']){echo 'width:100%';} ?>">

            <?// SCHEMES
            if($aRes['SCHEMES_COUNT']){?>
                <div class="CmSchemaTitle"><?=Lng_x('Select_OEM_schema',1);?>:</div>
                <div class="CmSchemaBox" data-schcount="<?=$aRes['SCHEMES_COUNT']?>" <?if($aRes['SCHEMES_COUNT'] <= 5){?>style="height:auto;"<?}?>>
                    <?foreach($aRes['SCHEMES'] as $aSchema){
                        $MinSize = 100; //px?>
                        <div class="CmSchema" style="background-image:url(<?=$aSchema['PicSrc']?>); height:<?=$MinSize*$aSchema['RatioH']?>px;" data-picid="<?=$aSchema['PicID']?>" data-lng="<?=LANG_x?>">
                            <div class="CmSchemName">
                                <div class="CmSchNameTxt"><?=$aSchema['Name']?></div>
                            </div>
                            <div class="CmSchLoadWrap"><div class="CmSchLoading"><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div></div></div>
                        </div>
                    <?}
                    if($aRes['SCHEMES_COUNT'] > 5){?>
                        <div class="CmSchemeHideOver"></div>
                    <?}?>
                    <!-- <div class="CmSchemaShowAll CmColorBg">&#9660; <?=Lng_x('Show_all_schemes',1);?></div> -->
                </div><?
            }
            ?>
                <div class="CmSortFilterBlock">
                    <div class="sort_but">
                        <div class="butt_sel" data-loadview="<?=$aRes['ACTIVE_TAB']?>">
                            <div class="viewlist_cm cm_viewAct <?if($aRes['ACTIVE_TAB']=='LIST'){echo 'CmViewActive cm_viewShad';}?>" data-view="LIST" data-urix="<?=PROTOCOL_DOMAIN_x?><?=$_SERVER['REQUEST_URI']?>">
                                <svg class="material-icon icon_im" viewBox="0 0 24 24" position="50">
                                    <path d="M4 14h4v-4H4v4zm0 5h4v-4H4v4zM4 9h4V5H4v4zm5 5h12v-4H9v4zm0 5h12v-4H9v4zM9 5v4h12V5H9z"/>
                                </svg>
                                <div class="but_text"></div>
                            </div>
                            <div class="viewgrid_cm cm_viewAct <?if($aRes['ACTIVE_TAB']=='GRID'){echo 'CmViewActive cm_viewShad';}?>" data-view="GRID" data-urix="<?=PROTOCOL_DOMAIN_x?><?=$_SERVER['REQUEST_URI']?>">
                                <svg class="material-icon icon_im" viewBox="0 0 24 24">
                                    <path d="M4 11h5V5H4v6zm0 7h5v-6H4v6zm6 0h5v-6h-5v6zm6 0h5v-6h-5v6zm-6-7h5V5h-5v6zm6-6v6h5V5h-5z"/>
                                </svg>
                                <div class="but_text"></div>
                            </div>
                            <div class="viewtable_cm cm_viewAct <?if($aRes['ACTIVE_TAB']=='TABLE'){echo 'CmViewActive cm_viewShad';}?>" data-view="TABLE" data-urix="<?=PROTOCOL_DOMAIN_x?><?=$_SERVER['REQUEST_URI']?>">
                                <svg class="material-icon icon_im" viewBox="0 0 24 24" position="50">
                                    <path d="M4 15h16v-2H4v2zm0 4h16v-2H4v2zm0-8h16V9H4v2zm0-6v2h16V5H4z"/>
                                </svg>
                                <div class="but_text"></div>
                            </div>
                        </div>
                        <?if(count($aRes['PRODUCTS']) >= 3 AND $aRes['SORT']!='ByName'){?>
                            <div class="sort_sel">
                                <span class="sort_tit"><?=Lng_x('Sort_by')?>: </span>
                                <div class="sort_bl" data-sort-control="Y">
                                    <div class="show_bl" role="button" tabindex="0" aria-haspopup="listbox" aria-expanded="false">
                                        <?$aSort = Array('LowPrice'=>Lng_x('Sort_lowest_price'), 'Delivery'=>Lng_x('Sort_delivery_time'), 'Available'=>Lng_x('Sort_available'), 'Rating'=>Lng_x('Sort_rating')); // ?>
                                        <span class="sort_text"><?=Lng_x($aSort[$aRes['SORT']])?></span>
                                        <div class="open_bl" aria-hidden="true">&#9660;</div>
                                    </div>
                                    <div class="hide_bl" role="listbox">
                                        <?foreach($aSort as $SCode=>$Name){
                                            if($SCode==$aRes['SORT']){continue;}
                                            $SortIcon = array(
                                                'LowPrice' => 'SortPrice',
                                                'Delivery' => 'SortDelivery',
                                                'Available' => 'SortAvailable',
                                                'Rating' => 'SortRating'
                                            );
                                            $SortIconKey = $SortIcon[$SCode];?>
                                            <div class="sort_list" role="option" tabindex="-1" data-sort="<?=$SCode?>" aria-selected="false">
                                                <span class="CmSortListIcon"><?=$aListSVG[$SortIconKey]?></span>
                                                <span class="CmSortListText"><?=$Name?></span>
                                            </div>
                                        <?}?>
                                    </div>
                                </div>
                            </div>
                        <?}?>
                        <div class="cm_countProdBl">
                            <span><?=Lng_x('Products_count',0)?>: </span><span class="cm_cPr"><?=$aRes['TOTAL_PRODUCTS']?></span>
                        </div>
                    </div>
                </div>
            <?if($aRes['TOTAL_PRODUCTS']){

                // Include VIEW template
                include('view_'.mb_strtolower($aRes['ACTIVE_TAB']).'.php');
                $aRes['MORE_PRODUCTS_COUNT']=false;
                if($aRes['MORE_PRODUCTS_COUNT']){?>
                    <a href="?page=2#ProductsList" class="cm_moreProd">
                        <div class="cm_moreImg">
                            <div class="cm_imgbl">
                                <div class="cm_imgBlock">
                                    <svg class="cm_imgRedo" viewBox="0 0 24 24">
                                        <path d="M12 0c3.31 0 6.291 1.353 8.459 3.522l2.48-2.48 1.061 7.341-7.437-.966 2.489-2.489c-1.808-1.807-4.299-2.928-7.052-2.928-5.514 0-10 4.486-10 10s4.486 10 10 10c3.872 0 7.229-2.216 8.89-5.443l1.717 1.046c-2.012 3.803-6.005 6.397-10.607 6.397-6.627 0-12-5.373-12-12s5.373-12 12-12z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="cm_moreText">
                                <span><?=Lng_x('Show_more_products',0)?> (<?=$aRes['MORE_PRODUCTS_COUNT']?>)</span>
                                <span><?=Lng_x('or_use_product_filter',0)?></span>
                            </div>
                        </div>
                    </a>
                <?}elseif($aRes['PAGE_TOTAL']>1){?>
                    <div class="CmPagination">
						<div class="CmPagTextBlock"><?=Lng_x('Page')?>:</div>
						<?$adjacentCount = 4;
					    $firstPage = 1;
					    $lastPage  = $aRes['PAGE_TOTAL'];
					    if ($lastPage == 1) {
					        return;
					    }
					    if ($aRes['PAGE_CURRENT'] <= $adjacentCount + $adjacentCount) {
					        $firstAdjacentPage = $firstPage;
					        $lastAdjacentPage  = min($firstPage + $adjacentCount + $adjacentCount, $lastPage);
					    } elseif ($aRes['PAGE_CURRENT'] > $lastPage - $adjacentCount - $adjacentCount) {
					        $lastAdjacentPage  = $lastPage;
					        $firstAdjacentPage = $lastPage - $adjacentCount - $adjacentCount;
					    } else {
					        $firstAdjacentPage = $aRes['PAGE_CURRENT'] - $adjacentCount;
					        $lastAdjacentPage  = $aRes['PAGE_CURRENT'] + $adjacentCount;
					    }?>
					    <div class="CmPageLinkBlock">
							<?if(substr(URL_x, -1, 1) != '/'){
								$LinkPag = URL_x.'/';
							}else{$LinkPag = URL_x;}?>
						    <?if ($firstAdjacentPage > $firstPage) {?>
						        <a class="CmPagLink <?if($firstAdjacentPage==$aRes['PAGE_CURRENT']){?>CmPageAct<?}?>" href="<?=$LinkPag.QueryString_x('page', $firstPage)?>"><?=$firstPage?></a>
						        <?if ($firstAdjacentPage > $firstPage + 1) {?>
						            <span>...</span>
						        <?}
						    }
							
						    for ($i = $firstAdjacentPage; $i <= $lastAdjacentPage; $i++) {?>
					            <a class="CmPagLink <?if($i==$aRes['PAGE_CURRENT']){?>CmPageAct<?}?>" href="<?=PROTOCOL_DOMAIN_x.$LinkPag.QueryString_x('page', $i)?>"><?=$i?></a>
						    <?}
						    if ($lastAdjacentPage < $lastPage) {
						        if ($lastAdjacentPage < $lastPage - 1) {?>
						            <span>...</span>
						        <?}?>
						        <a class="CmPagLink <?if($lastAdjacentPage==$aRes['PAGE_CURRENT']){?>CmPageAct<?}?>" href="<?=$LinkPag.QueryString_x('page', $lastPage)?>"><?=$lastPage?></a>
						    <?}?>
					    </div>
                    </div>

                <?}
            }else{?>
                <div class="cm_NoProduct"><span><?=Lng_x('No products')?></span></div>
            <?}?>
        </div>
    </div>
	<?=ShowSEOText_x("BOT")?>
</div>
<?if($aSets['NARROW_DESIGN']){?>
	<link rel="stylesheet" type="text/css" href="<?=CmAssetUrl_x(TEMPLATE_PAGE_DIR.'blocks/mini.css')?>" />
<?}?>
<?php AjaxCut_x(); //Makes: </div> ?>
