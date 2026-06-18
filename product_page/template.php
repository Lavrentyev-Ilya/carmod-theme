<?
include('custom.php');
VerifyAccess_x('ProductPage.templ');
if(!function_exists('CmProductImageAlt_x')){
    function CmProductImageAlt_x($aRes){
        $ServerName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '';
        return htmlspecialchars(trim($aRes['Brand'].' '.$aRes['ArtNum'].' - '.$aRes['Name'].' '.$ServerName),ENT_QUOTES,'UTF-8');
    }
}

if(!function_exists('CmProductExternalLink_x')){
    function CmProductExternalLink_x($aExternalUrl,$aPageSVG,$imageMode=false){
        if(!is_array($aExternalUrl) OR count($aExternalUrl)==0){return '';}
        $Suffix = $imageMode ? 'Img' : '';
        $IfrClass = $imageMode ? 'CmIfrBut Cm360But' : 'CmIfrBut';

        if(!empty($aExternalUrl['You'])){
            return '<a class="CmExtUrlLink" href="'.htmlspecialchars($aExternalUrl['You'],ENT_QUOTES,'UTF-8').'" target="_blank" rel="noopener">'.$aPageSVG['ExLinkYou'.$Suffix].'</a>';
        }
        if(!empty($aExternalUrl['Pdf'])){
            return '<a class="CmExtUrlLink" href="'.htmlspecialchars($aExternalUrl['Pdf'],ENT_QUOTES,'UTF-8').'" target="_blank" rel="noopener">'.$aPageSVG['ExLinkPdf'.$Suffix].'</a>';
        }

        $aFrameLinks = Array(
            'Spin' => 'ExLinkSpin',
            'Emea' => 'ExLinkImea',
            'Vzaa' => 'ExLinkVzaa'
        );
        foreach($aFrameLinks as $Key=>$SvgKey){
            if(!empty($aExternalUrl[$Key])){
                return '<div class="'.$IfrClass.'" data-eu="'.htmlspecialchars($aExternalUrl[$Key],ENT_QUOTES,'UTF-8').'">'.$aPageSVG[$SvgKey.$Suffix].'</div>';
            }
        }

        foreach($aExternalUrl as $Url){
            if($Url==''){continue;}
            return '<a class="CmExtUrlLink" href="'.htmlspecialchars($Url,ENT_QUOTES,'UTF-8').'" target="_blank" rel="noopener">'.$aPageSVG['ExLinks'.$Suffix].'</a>';
        }
        return '';
    }
}
global $aComt;
$CntCmt=0;
$AnalogsAvail = false;
$HideProps = false;
if(is_array($aComt)){
    foreach($aComt as $a){
        if($a['CACT']>1){$CntCmt++;}
    }
}
$aNumsBlock = [$aRes['OE'], $aRes['ANALOGS'], $aRes['TRADE_NUMBERS'], $aRes['OLD_NUMBERS'], $aRes['NEW_NUMBERS'], $aRes['EANS']];
$aNumRes = Array();
foreach ($aNumsBlock as $key => $value) {
    if($value==''){
        continue;
    }
    $aNumRes[$key] = $value;
}

if($aRes['OE'] || $aRes['ANALOGS'] || $aRes['TRADE_NUMBERS'] || $aRes['OLD_NUMBERS'] || $aRes['NEW_NUMBERS'] || $aRes['EANS']){
	$AnalogsAvail = true;
}
if(!$aRes['PRICES'] && $aRes['HAVE_CROSSES'] && !$aRes['OE'] && !$aRes['IMAGES'] && !$aRes['VEHICLES']){
    $HideProps = true;
}

$aProd = $aRes;
if(!$aRes['PRODUCTS'] && $aRes['PRICES']){
    foreach ($aRes['PRICES'] as $k => $aPr) {
        $aProd['PRICES'][$k] = $aPr;
    }
}

$ProductURL = GetProductLink_x($aRes);
$ProductImageAlt = CmProductImageAlt_x($aRes);
$AjaxProductPrice = (isset($_REQUEST['CarModAjaxProdPrice']) AND $_REQUEST['CarModAjaxProdPrice']=='Y');
$AjaxOeNumbers = (isset($_REQUEST['CarModAjaxOENumbers']) AND $_REQUEST['CarModAjaxOENumbers']==='Y');
$ShowCommentsTab = isset($_GET['cmt']);

include('svg.php');
if(!function_exists('PrintPrices_x')){
    $CmPricesBlockAutoRun = false;
    require_once dirname(__DIR__).'/prices_block/template.php';
}

StatTime_x('Tpl ## Schema before');
include('blocks/schema.php');
StatTime_x('Tpl ## Schema after');
?>
<div class="Popup_n">
    <div class="PopupContent_n">
        <span class="Close_n">&times;</span>
        <button class="Prev_n">&#10094;</button>
        <div id="ImgContainer" class="image-container">
            <img id="PopupImage_n" src="" alt="">
			<div class="CmShemaCoord"></div>
        </div>
		<div class="CmShemaBraArt"></div>
        <button class="Next_n">&#10095;</button>
        <button class="ZoomIn_n">+</button>
        <button class="ZoomOut_n">&minus;</button>
    </div>
</div>

<div class="CmTopBox">
    <div class="CmHeadTitleWrapBlock">
        <div class="CmTitleBox">
            <div class="cmInnerBl">
                <div class="cmWrapLogoBl">
                    <div class="blockLogo CmProductBrandLogo">
                        <img class="CmProductBrandLogoImg" src="<?=$aRes['Logo']?>" alt="<?=htmlspecialchars($aRes['Brand'],ENT_QUOTES,'UTF-8')?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="CmMSelectBlock CmMSelectPositionRight">
            <?CmModelSelectorWidget('default',96);?>
        </div>
    </div>
</div>
<div class="CmBrTitleSearchWrap">
    <div id="CmTitlH1Page"><h1><?=H1_x?></h1></div>
</div>
<?BreadCrumbs_x();?>

<div class="CmProductPage blockMainPart">
	<?EditProductButton_x($aProd['Pom'],Array(
		'Brand'=>$aProd['Brand'],
		'ArtNum'=>$aProd['ArtNum'],
		'ProdID'=>intval($aProd['ProdID']),
		'HasRemoteArticle'=>(!empty($aProd['HasRemoteArticle'])?1:0)
	));?>
	<div class="CmClrb"></div>
	<article class="CmProductCard blockProdCard">
        <section class="CmProductGallery blockProdFoto <?if($HideProps){?>CmProductGallery-compact<?}?>"><?
            if($aRes['IMAGES']){
                $aImg = reset($aRes['IMAGES']);
            }?>
            <div class="CmProductGalleryFrame innBlockFoto BoxGlr_n <?if($HideProps){?>CmProductGalleryFrame-compact<?}?>">

                <?if($aRes['IMAGES']){?>
                    <div class="CmProductImagePopup CmImageToPopup OpenPopup_n <?if($HideProps){?>CmProductImagePopup-compact<?}?>">
                        <div class="CmSchemBlockWrap"  data-imgtype="scheme">
                            <img class="CmProdImgBl <?if(!$aImg['Html']){?>CmImgHLimit <?}?><?if(isset($aRes['IMAGES']) AND count($aRes['IMAGES']) <= 1){?>GlrImg_n<?}?>" src="<?=$aImg['Src']?>" alt="<?=$ProductImageAlt?>" data-width="<?=$aImg['Width']?>" data-height="<?=$aImg['Height']?>">
                            <?=$aImg['Html']?>
                        </div>
                    </div>
                <?}else{?>
                    <div class="CmProductNoPhoto imgNoFoto <?if($HideProps){?>CmProductNoPhoto-compact<?}else{?>CmProductNoPhoto-regular<?}?>" data-imgsrc="<?=$firstImg?>">
                        <img class="CmLogoNoFoto" src="<?=$aRes['Logo']?>" alt="<?=htmlspecialchars($aRes['Brand'],ENT_QUOTES,'UTF-8')?>">
                    </div>
                <?}?>
				<div class="CmFlagBox">
					<div class="CmFlagPic" title="<?=$aRes['BRAND']['CName']?> <?=$aRes['BRAND']['Corp']?>">
                        <img src="/<?=CM_DIR?>/media/country/<?=$aRes['BRAND']['CCode']?>.png" alt="<?=$aRes['BRAND']['CCode']?>">
                    </div>
				</div>
				<?
                if(isset($aRes['IMAGES']) AND count($aRes['IMAGES']) > 1){?>
                    <div class="CmProductThumbs blSmallFoto <?echo count($aRes['IMAGES'])>12?'CmProductThumbs-many" data-smfoto="Y':'CmProductThumbs-auto'?>"><?
                        foreach($aRes['IMAGES'] as $i){
                            if($i['Class']&&$i['Class']!=''){
                                ?><div class="cmChangeImg">
                                    <div class="CmSchemBlockWrap"  data-imgtype="scheme">
                                        <img class="<?=$i['Class']?> CmProdImgBl GlrImg_n" src="<?=$i['Src']?>" alt="<?=$ProductImageAlt?>" data-width="<?=$i['Width']?>" data-height="<?=$i['Height']?>">
                                        <?=$i['Html']?>
                                    </div>
                                </div><?
                            }else{
                                ?><div class="cmChangeImg">
                                    <img class="CmProdSmallImgBl GlrImg_n" src="<?=$i['Src']?>" alt="<?=$ProductImageAlt?>">
                                </div><?
                            }
                        }
                        if($aRes['EXTERNAL_URL']){?>
                            <div class="CmExtUrlImgWrap">
                                <div class="CmExtUrlImgBlock">
                                    <?=CmProductExternalLink_x($aRes['EXTERNAL_URL'],$aPageSVG,false)?>
                                </div>
                            </div>
                        <?}
                        if(count($aRes['IMAGES'])>12){?>
                            <div class="CmHideBlSmalFoto"></div>
                        <?}?>
                    </div><?
                }
                    if($aRes['EXTERNAL_URL'] && (!$aRes['IMAGES'] || count($aRes['IMAGES']) == 1)){
                        ?><div class="CmExtUrlWrap">
                            <div class="CmExtUrlBlock">
                                <?=CmProductExternalLink_x($aRes['EXTERNAL_URL'],$aPageSVG,true)?>
                            </div>
                        </div>
                    <?}?>
            </div>
        </section>
        <div class="CmProductContent CmBlockPropsPriceWrap">

			<section class="CmProductSummary blockProdProps">
				<div class="CmProductSummaryInner CmPropsInnerBlock <?if($HideProps){?>CmProductSummaryInner-compact<?}?>">
					
					<div class="CmProductText innBlockProps">
						 <div class="CmMetaListWrap">
							<ul class="ulMetaName">
								<li class="cmProdName"><?=$aRes['Name']?></li>
                                <?$MetaCount = 0;?>
								<?if($aRes['META']){
									$li = 0;?>
									<?foreach($aRes['META'] as $aMeta){
										foreach($aMeta as $val){ $li++;
                                            $MetaCount++;
											if($li <= 2){?>
												<li class="cmMetaName"><?=$val?></li>
												<?continue;
											}?>
											<li class="cmMetaName_2"><?=$val?></li>
										<?}
									}?>
								<?}?>
								<?if($aRes['VEHICLES_TITLE']){
									?><li class="cmMetaName"><?
									foreach($aRes['VEHICLES_TITLE'] as $Man=>$Models){
										?><b><?=$Man?></b> <?=$Models?>; <?
									}
									?></li><?
								}?>
								<?if(ShowSEOText_x("TOP",true)){?><li class="cmMetaName"><?=ShowSEOText_x("TOP")?></li><?}?>
								<?if(defined('SEOTEXT_x') AND SEOTEXT_x!=''){?><li class="cmMetaName"><?=SEOTEXT_x?></li><?}?>
							</ul>
							<?if($MetaCount>2){?>
								<div class="CmShowMoreMeta" data-hide="<?=Lng_x('Show_less')?>&nbsp;&#9650;" data-show="<?=Lng_x('More_information')?>&nbsp;&#9660;"><?=Lng_x('More_information',1)?>&nbsp;&#9660;</div>
							<?}?>
						 </div>
						 
						 <?if($aRes['VEHICLE_CRITERIA']){?>
							 <div class="propTitle CmVehCriTop"><?=Lng_x('Applicability_to', 1)?> <b><?=$aRes['VEHICLE_SELECTED']?></b></div>
							 <dl class="cmWrapPropTab CmVehCriTab">
								 <?foreach($aRes['VEHICLE_CRITERIA'] as $pP=>$aNV){?>
									 <div class="CmFullProp">
										 <dt class="propTdName"><?=$aNV['Name']?>: </dt>
										 <dd class="propTdVal"><?=$aNV['Value']?></dd>
									 </div>
								 <?}?>
							</dl>
						 <?}?>
						 
						 <?if($aRes['PROPERTIES']){?>
							 <?if(!$HideProps){?><div class="propTitle"><?=Lng_x('Characteristics', 1)?>:</div><?}?>
							 <div class="cmWrapPropTab">
								 <div class="CmPropWrap <?if(count($aRes['PROPERTIES'])>14){?>CmPropTabHeight<?}?>">
									 <dl class="CmProperTab">
										 <?foreach($aRes['PROPERTIES'] as $Name=>$Value){?>
											 <div class="CmFullProp">
												 <dt class="propTdName"><?=$Name?></dt>
												 <dd class="propTdVal"><?=$Value?></dd>
											 </div>
										 <?}?>
									 </dl>
								 </div>
							 </div>
						 <?}?>
					 </div>
					 
					 <?if($aRes['ShortNumbers']){?>
						 <div class="CmShortNumWrap">
							 <div class="CmShortNumTit"><?=Lng_x('Short_codes_Internal_numbers')?></div>
							 <div class="CmShortNumTab">
								 <?foreach($aRes['ShortNumbers'] as $Name=>$Value){?>
									 <div class="CmShortNumb">
										 <div class="CmShortName"><?=$Name?>:</div>
										 <div class="CmShortValTd">
											 <?foreach($Value as $Val){?>
												 <div class="CmShortValTxt"><?=str_replace(' ', '', $Val)?></div>
											 <?}?>
										 </div>
									 </div>
								 <?}?>
							 </div>
						 </div>
					 <?}?>
				</div>
				<?if($HideProps && $aRes['PROPERTIES']){?>
					<div class="CmShowHiddSpecs"><div class="CmArrDownOpen"><?=$aPageSVG['CmArrowDown']?></div></div>
				<?}?>
			</section>
		</div>
		<aside class="CmProductPurchase CmPriceEditPrButWrap">
			<div class="CmProductPrices blockProdPrice" data-wsact="<?if($aRes['WsAjax']){echo '1';}?>" >
				<?StatTime_x('Tpl ## Prices block before'); PrintPrices_x(array('CONTEXT'=>'product_page','PRODUCT'=>$aProd,'RES'=>$aRes,'PRODUCT_URL'=>$ProductURL)); StatTime_x('Tpl ## Prices block after');?>
			</div>
			<?AdmWsRequests_x($aRes);?>
		</aside>
       
    </article>
    <div class="CmProductTabs wrapBlTabsMenu <?if($AjaxProductPrice){?>CmProductTabs-ajax<?}?>" data-cmdir="<?=CM_DIR?>" data-request="<?if($AjaxProductPrice){?>Y<?}?>" data-url="<?=$aRes['DETAIL_PAGE_URL']?>" data-avail="<?if(!$AnalogsAvail){?>N<?}?>">
        <div class="CmProductTabsNav cmBlockTabs">
            <?if($AnalogsAvail){?>
                <div class="tabOeNum CmtabSelBut <?if(!isset($_GET['cmt'])){?>activeSecTab <?}?> CmTabShadRight" data-change="OeNum">
                    <svg class="cmSvgInfo cmSvgImg <?if(!isset($_GET['cmt'])){?>CmSvgActive<?}?>" viewBox="-1 -1 27 24"><path d="M12 2c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0-2c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-.001 5.75c.69 0 1.251.56 1.251 1.25s-.561 1.25-1.251 1.25-1.249-.56-1.249-1.25.559-1.25 1.249-1.25zm2.001 12.25h-4v-1c.484-.179 1-.201 1-.735v-4.467c0-.534-.516-.618-1-.797v-1h3v6.265c0 .535.517.558 1 .735v.999z"/></svg>
                    <?if(!$AjaxProductPrice){?>
                        <span class="cmTabText"><?=Lng_x('OE_Numbers')?></span>
                    <?}?>
                </div>
            <?}?>
            <div class="tabPartUse CmtabSelBut <?if(!$AnalogsAvail AND !isset($_GET['cmt'])){?>activeSecTab<?}?>" data-change="Suite" clicked="N">
                <svg class="cmSvgCar cmSvgImg <?if(!$AnalogsAvail AND !isset($_GET['cmt'])){?>CmSvgActive<?}?>" viewBox="0 0 22 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
                <?if(!$AjaxProductPrice){?>
                    <span class="cmTabText"><?=Lng_x('Suitable_vehicles')?></span>
                <?}?>
            </div>
			<div class="tabOeNum CmtabSelBut <?if(isset($_GET['cmt'])){?>activeSecTab <?}?> CmTabShadRight" data-change="Comments">
				<svg class="cmSvgInfo cmSvgImg <?if(isset($_GET['cmt'])){?>CmSvgActive<?}?>" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
					<path d="M9.0001 8.517C8.58589 8.517 8.2501 8.85279 8.2501 9.267C8.2501 9.68121 8.58589 10.017 9.0001 10.017V8.517ZM16.0001 10.017C16.4143 10.017 16.7501 9.68121 16.7501 9.267C16.7501 8.85279 16.4143 8.517 16.0001 8.517V10.017ZM9.8751 11.076C9.46089 11.076 9.1251 11.4118 9.1251 11.826C9.1251 12.2402 9.46089 12.576 9.8751 12.576V11.076ZM15.1251 12.576C15.5393 12.576 15.8751 12.2402 15.8751 11.826C15.8751 11.4118 15.5393 11.076 15.1251 11.076V12.576ZM9.1631 5V4.24998L9.15763 4.25002L9.1631 5ZM15.8381 5L15.8438 4.25H15.8381V5ZM19.5001 8.717L18.7501 8.71149V8.717H19.5001ZM19.5001 13.23H18.7501L18.7501 13.2355L19.5001 13.23ZM18.4384 15.8472L17.9042 15.3207L17.9042 15.3207L18.4384 15.8472ZM15.8371 16.947V17.697L15.8426 17.697L15.8371 16.947ZM9.1631 16.947V16.197C9.03469 16.197 8.90843 16.23 8.79641 16.2928L9.1631 16.947ZM5.5001 19H4.7501C4.7501 19.2662 4.89125 19.5125 5.12097 19.6471C5.35068 19.7817 5.63454 19.7844 5.86679 19.6542L5.5001 19ZM5.5001 8.717H6.25012L6.25008 8.71149L5.5001 8.717ZM6.56175 6.09984L6.02756 5.5734H6.02756L6.56175 6.09984ZM9.0001 10.017H16.0001V8.517H9.0001V10.017ZM9.8751 12.576H15.1251V11.076H9.8751V12.576ZM9.1631 5.75H15.8381V4.25H9.1631V5.75ZM15.8324 5.74998C17.4559 5.76225 18.762 7.08806 18.7501 8.71149L20.2501 8.72251C20.2681 6.2708 18.2955 4.26856 15.8438 4.25002L15.8324 5.74998ZM18.7501 8.717V13.23H20.2501V8.717H18.7501ZM18.7501 13.2355C18.7558 14.0153 18.4516 14.7653 17.9042 15.3207L18.9726 16.3736C19.7992 15.5348 20.2587 14.4021 20.2501 13.2245L18.7501 13.2355ZM17.9042 15.3207C17.3569 15.8761 16.6114 16.1913 15.8316 16.197L15.8426 17.697C17.0201 17.6884 18.1461 17.2124 18.9726 16.3736L17.9042 15.3207ZM15.8371 16.197H9.1631V17.697H15.8371V16.197ZM8.79641 16.2928L5.13341 18.3458L5.86679 19.6542L9.52979 17.6012L8.79641 16.2928ZM6.2501 19V8.717H4.7501V19H6.2501ZM6.25008 8.71149C6.24435 7.93175 6.54862 7.18167 7.09595 6.62627L6.02756 5.5734C5.20098 6.41216 4.74147 7.54494 4.75012 8.72251L6.25008 8.71149ZM7.09595 6.62627C7.64328 6.07088 8.38882 5.75566 9.16857 5.74998L9.15763 4.25002C7.98006 4.2586 6.85413 4.73464 6.02756 5.5734L7.09595 6.62627Z"/>
				</svg>
				<?if(!$AjaxProductPrice){?>
					<span class="cmTabText"><?=Lng_x('Reviews')?> (<?=$CntCmt?>)</span>
				<?}?>
			</div>
        </div>
        <div class="cmBlockInfo">
            <?AjaxCut_x('OENumbers');?>
			
			<div class="CmProductCommentsPane centBlockComments <?if(!$ShowCommentsTab){?>CmProductPane-hidden<?}?>">
				<?StatTime_x('Tpl ## Comments block before'); include('blocks/comments.php'); StatTime_x('Tpl ## Comments block after');?>
			</div>
			
            <?if($AnalogsAvail){?>
                <div class="CmProductRefsPane centBlockInfo <?if($ShowCommentsTab){?>CmProductPane-collapsed<?}?> <?if($AjaxOeNumbers){?>CmProductRefsPane-single<?}?>">
                    <?if($aRes['ANALOGS']){?>
                        <div class="CmAnalogBlockWrap CmInfoInBlock">
                            <div class="anNumTitleBl">
                                <div class="cmAnalogTitle"><?=Lng_x('Analogs')?>:</div>
                            </div>
                            <div class="CmAnalogBlockInside <?if(count($aRes['ANALOGS'])>12){?>CmBlockHeightToHIde<?}?>">
                                <?if(count($aRes['ANALOGS'])>12){?>
                                    <div class="CmHideTextBlock"></div>
                                <?}?>
                                <table class="CmAnalogsTable">
                                    <?foreach($aRes['ANALOGS'] as $Brand=>$aAnalogNum){?>
                                        <tr class="cmAnalogBlocks">
                                            <td class="cmAnBrandName"><?=$Brand?></td>
                                            <td class="cmAnArtNum">
                                                <div class="CmWrapBlockArtNum">
                                                    <?foreach($aAnalogNum as $aNum){?>
                                                        <a class="CmAnalogLink" href="<?=$aNum['Link']?>"><?=$aNum['ArtNum']?><span>,</span></a>
                                                    <?}?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?}?>
                                </table>
                                <?if(count($aRes['ANALOGS'])>12){?>
                                    <div class="CmMoreAnalogsNum">
                                        <span class="CmShowA"><?=Lng_x('Show_more')?>&nbsp;&#9660;</span>
                                        <span class="CmHideA"><?=Lng_x('Hide')?>&nbsp;&#9650;</span>
                                    </div>
                                <?}?>
                            </div>
                        </div>
                    <?}?>
                    <?if($aRes['OE']){?>
                        <div class="CmOeNumBlockWrap CmInfoInBlock">
                            <div class="CmOeBlockInside">
                                <div class="modelNumTable ">
                                    <?$s=0;?>
                                    <table class="CmOeNumTable">
                                        <?foreach($aRes['OE'] as $Brand=>$aOENums){$Cm='';$s++?>
                                            <tr>
                                                <td class="CmOeNameTd <?if(count($aNumRes) == 1){?>CmProductOeBrand-narrow<?}?>" <?if($s>6){?>data-check="Y"<?}?>><span><?=$Brand?></span></td>
                                                <td class="CmNumbTd">
                                                    <div class="CmOeNumsTd <?if($AjaxProductPrice){?>CmProductOeNums-ajax<?}?>">
                                                        <?foreach($aOENums as $aOENum){?>
                                                            <a href="<?=$aOENum['Link']?>">
                                                                <span class="CmOeNumberLink"><?=str_replace(' ','',$aOENum['ArtNum'])?></span>
                                                            </a>
                                                        <?}?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?}?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?}?>
                    <?if($aRes['TRADE_NUMBERS']){?>
                        <table class="tradNumBlock CmInfoInBlock">
                            <tr class="cmTradTitle">
                                <td colspan="2"><?=Lng_x('Trade_numbers')?>:</td>
                            </tr>
                            <?foreach($aRes['TRADE_NUMBERS'] as $aJNumbers){?>
                                <tr class="tradeNumTr">
                                    <td><?=$aJNumbers['Name']?></td>
                                    <td><?foreach($aJNumbers['JNumbers'] as $JNumber=>$JLink){?><a class="CmTradeNumLink" href="<?=$JLink?>"><?=$JNumber?></a>, <?}?></td>
                                </tr>
                            <?}?>
                        </table>
                    <?}?>
                    <?if($aRes['OLD_NUMBERS'] || $aRes['NEW_NUMBERS']){?>
                        <div class="blOldNewNum CmInfoInBlock">
                            <div class="cmNewOldTitleTd cmNewOldTitle" colspan="2"><?if($aRes['OLD_NUMBERS']){echo Lng_x('Replaced_Numbers');}else if($aRes['NEW_NUMBERS']){echo Lng_x('New_Number');}?>:</div>
                            <?if($aRes['OLD_NUMBERS']){
                                foreach($aRes['OLD_NUMBERS'] as $Num => $Link){?>
                                    <div class="OldNewNumtd OldNewlinkNum"><a class="CmOldNewLink" href="<?=$Link?>" target="_blank" rel="noopener"><?=$Num?></a></div>
                                <?}
                            }else if($aRes['NEW_NUMBERS']){
                                foreach($aRes['NEW_NUMBERS'] as $Num => $Link){?>
                                    <div class="OldNewNumtd OldNewlinkNum"><a class="CmOldNewLink" href="<?=$Link?>" target="_blank" rel="noopener"><?=$Num?></a></div>
                                <?}
                            }?>
                        </div>
                    <?}?>
                    <?if($aRes['EANS'] && !$_REQUEST['ProdPrice']){?>
                        <div class="CmBrCodeWrapBl CmInfoInBlock">
                            <div class="cmEanTitle">
                                <span><?=Lng_x('Barcode')?>:</span>
                            </div>
                            <table class="cmBarcodeBlock">
                                <?foreach($aRes['EANS'] as $Ean){ $fEAN=$Ean;?>
                                    <tr class="EanBarTr">
                                        <td class="barcodText"><?=$Ean?></td>
                                    </tr>
                                <?}?>
                                <tr>
                                    <td class="EanBarcodeImg">
                                        <div class="ImgBarEan">
                                            <?require(PATH_x.'/media/barcode.php');
                                            ShowBarCode_x($fEAN);
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    <?}?>
                </div>
            <?}
            if($AjaxOeNumbers){?>
                <div class="CmNoInfo">
                    <svg class="CmNoInfoPic" viewBox="0 0 254.533 254.533">
                        <?=$aPageSVG['CmNoModel']?>
                    </svg>
                    <span class="CmNoInfoTxt"><?=Lng_x('No_info',1)?></span>
                </div>
            <?}?>
            <?AjaxCut_x('OENumbers');?>
            <?AjaxCut_x('SuitVehicle');?>
            <div class="CmModelSuitBlock cmSuitBlock" data-cmdir="<?=CM_DIR?>">
                <div class="CmNotFoundInfo">
                    <svg class="CmNoInfoPic" viewBox="0 0 254.533 254.533">
                        <?=$aPageSVG['CmNoModel']?>
                    </svg>
                    <span class="CmNoInfoTxt"><?=Lng_x('No_info',1)?></span>
                </div>
                <div class="CmModBlockInner">
                    <div class="CmBrandBlockWrap">
                        <div class="CmBrandListBlWrap">
                            <div class="CmBrandNameBl"></div>
                        </div>
                    </div>
                    <div class="CmModelModifWrap">
                        <div class="CmModelListBlWrap">
                            <div class="CmTitleNameTx"><?=Lng_x('Model')?>,&nbsp;<?=Lng_x('Year')?></div>
                            <div class="CmModelListOverf" id="idscroll">
                                <div class="CmModelListBlock">
                                    <div class="CmModelList CmVehicStyle cmVehicModHov" data-pageurl="<?=$aRes['DETAIL_PAGE_URL']?>" data-modcode="<?=$key?>" data-modname="<?=$K?>" data-moduledir="<?=CM_DIR?>">
                                       <div class="CmSelectBrandTxt">
                                            <svg class="CmUpArrowImg" viewBox="0 0 24 24"><path d="M3 12l18-12v24z"/></svg>
                                            <div class="CmSelBrandTitl"><?=Lng_x('Select_brand', 1)?></div>
                                        </div>
                                        <div class="CmModelModif" data-ajaxreq="<?if($_REQUEST['ProdPrice']==="Y"){?>Y<?}?>"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="CmModifBlWrap">
                            <div class="CmTitleNameTx"><?=Lng_x('Engine')?></div>
                            <div class="CmModifListOverf">
                                <div class="CmSelectModelTxt">
                                    <svg class="CmLArrowImg" viewBox="0 0 24 24"><path d="M3 12l18-12v24z"/></svg>
                                    <div class="CmSelectModTitl"><?=Lng_x('select_model')?></div>
                                </div>
                                <div class="CmModifListBlock"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?AjaxCut_x('SuitVehicle');?>
        </div>
        <a name="ProductsList" class="CmAnalogList" <?if(!$aRes['PRICES']){?>data-prodval="N"<?}?>></a>
    </div>

    <?=ShowSEOText_x("BOT")?>
    <?if($aRes['RELATED_PRODUCTS']){?>
        <div class="CmRelProdWrap">
            <div class="CmRelProdTitWr">
                <div class="CmRelProdTitBl">
                    <span class="CmRelProdTit"><?=Lng_x('Related_products')?></span>
                </div>
            </div>
            <div class="CmRelProdWrapper">
                <?foreach($aRes['RELATED_PRODUCTS'] as $key => $rProd){?>
                    <div class="CmRelProdBlock">
                        <div class="CmRelProdItem">
                            <a href="<?=$rProd['Link']?>">
                                <div class="CmRelProdTitTxt">
                                    <div class="CmRelProdBrandN"><?=$rProd['Brand']?></div>
                                    <div class="CmRelProdArtNum"><?=$rProd['ArtNum']?></div>
                                </div>
                                <div class="CmRelProdContentItemWr">
                                    <div class="CmRelProdItemImg">
                                        <img alt="<?=htmlspecialchars($rProd['Brand'].' '.$rProd['ArtNum'],ENT_QUOTES,'UTF-8')?>" src="<?=$rProd['Img']?>"/>
                                    </div>
                                    <div class="CmRelProdNamePriceWr">
                                        <div class="CmRelProdItemName <?if(!$rProd['Price']){?>CmRelProdItemName-bottom<?}?>"><?=$rProd['Name']?></div>
                                        <div class="CmRelProdItemPrice">
                                            <?if($rProd['Price']){?>
                                                <span><?=Lng_x('from')?>&nbsp;</span><?=$rProd['Price']?><span class="CmRprodCurr"><?=$rProd['Currency']?></span>
                                            <?}?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                <?}?>
            </div>
        </div>
    <?}?>
</div>
<?if($aRes['HAVE_CROSSES']){?>
    <div class="CmCrossTitleBl" data-page="<?=$_GET['page']?>">
        <div class="CmTitleCrossText">
            <span class="CmTextCr"><?=Lng_x('Analogs_and_kits')?></span>
        </div>
    </div>
<?}?>
