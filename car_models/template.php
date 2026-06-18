<?VerifyAccess_x('Models.templ');
//Filter - Years groups
if($aRes['YEARS_FULL'] AND is_array($aRes['YEARS_FULL']) AND count($aRes['YEARS_FULL'])>0){
	foreach($aRes['YEARS_FULL'] as $Year){
		$gY = substr($Year,0,3);
		$aY[$gY.'0'][] = $Year;
	}
	rsort($aRes['YEARS_FULL']);
	//echo '<pre>';	print_r($aY); echo '</pre>'; die();
}
//Template Block setting
$BLOCK = $aSets['TEMPLATE_BLOCK'];
//echo '<pre>'; print_r($aRes['MODELS']); echo '</pre><br><br>'; die(); 
?>

<?php AjaxCut_x(); //Makes: <div id="CmAjaxBox"> ?>

<div class="CmHeadBox">
	
	<div class="boxInOver" id="sectionBox" style="display: none;">
		<div class="bxIOPosit" style="max-width:500px;">
			<div class="CmTitleBox c_BrTop3px">
				<a href="<?=FURL_x?>"><div class="cmProdLogo" title="<?=$aRes['BRAND_CODE']?>" style="background:url(/<?=CM_DIR?>/media/brands/90/<?=$aRes['BRAND_CODE']?>.png)"></div></a>
				<!-- <a href="javascript:void(0)" onclick="jQuery('#sectionBox').fadeIn(400);"><div class="cmH1"><h1 class="c_H1b"><?=H1_x?></h1></div></a> -->
			</div>
		</div>
	</div>
	
    <div class="CmFilterSwitchWrap">
        <div class="CmTitleBox">
			<div class="CmLetYearFilterBl">
				<div class="cmProdLogo" title="<?=$aRes['BRAND_CODE']?>" style="background:url(/<?=CM_DIR?>/media/brands/90/<?=$aRes['BRAND_CODE']?>.png)"></div>
				<?if($aRes['MODELS_COUNT']>7){
					foreach ($aRes['MODELS'] as $aMod) {
						$aModLet[] = substr($aMod['MOD_TITLE'], 0, 1);
					}
					$aModUn = array_unique($aModLet);
					natcasesort($aModUn);?>
					<div class="CmFiltersWrap">
						<div class="CmLettNameFilt">
							<div class="CmFiltersInner">
								<div class="fByName">
									<div class="fByNameButs">
										<div class="CmActFB CmFtColorCmColor-fff" data-alllang="<?=Lng_x('All')?>"><?=Lng_x('All')?></div>
										<?foreach($aModUn as $firstLett){?>
											<div class="CmModFirstLett"><?=$firstLett?></div>
										<?}?>
									</div>
									<?=ShowSEOText_x("TOP")?>
								</div>
								<div id="yearBox" class="yearBox">
									<div class="CmYearFiltBlock">
										<div class="CmYearGrBl">
											<div class="fYGroupe"><?=Lng_x('Year')?></div> &nbsp;
											<span class="CmYearArrow">
												<svg class="CmYArrSvg" viewBox="0 0 24 24"><path d="M3 12l18-12v24z"/></svg>
											</span>
										</div>
										<div class="CmYearItem" data-yearg="">
											<div fytxt="<?=Lng_x('Year')?>" class="fYear">
												<span class="CmYearAll"><?=Lng_x('All')?></span>
											</div>
											<?foreach($aRes['YEARS_FULL'] as $Year){?>
												<div class="fYear">
													<span class="CmYearTxt"><?=$Year?></span>
													<?/* <span>
														<svg class='CmYearSvg' viewBox="0 0 24 24"><path d="M5 3l3.057-3 11.943 12-11.943 12-3.057-3 9-9z"/></svg>
													</span> */?>
												</div>
											<?}?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="CmNameFiltBlock">					
							<div class="CmAllItemBut"><?=Lng_x('All')?></div>
							<ul class="CmNameFItems">
								<?foreach($aRes['MODELS'] as $k=>$aModF){
									$aTitl[] = $aModF['MOD_TITLE'];
								}
								$aResTitle = array_unique($aTitl);
								$i=0;
								foreach($aResTitle as $titleTxt){$i++;
									if($i<=30){?>
										<li class="CmModelTitl CmNFiltItem"><?=$titleTxt?></li>
									<?continue;}?>
									<li class="CmModelTitl CmNFiltItem2"><?=$titleTxt?></li>
								<?}?>
							</ul>
							<div class="CmShowNames" data-hide="<?=Lng_x('Hide')?>&nbsp;&#9650;" data-show="<?=Lng_x('Show_all')?>&nbsp;&#9660;"><?=Lng_x('Show_all')?>&nbsp;&#9660;</div>
						</div>
					</div>
				<?}?>
			</div>
		</div>
        <?//TDMShowBreadCumbs()?>
        
    </div>
</div>
<div class="CmBrTitleSearchWrap">
    <div id="CmTitlH1Page"><h1 class=""><?=H1_x?></h1></div>
</div>
<?BreadCrumbs_x(); // Edit in: ../templates/default/includes.php ?>
<div class="CmTitleModelBlock">
	<div class="boxMod" data-typespopup="<?=$aSets['TYPES_POPUP']?>">
		<?if($aRes['MODELS_COUNT']>0){
			foreach($aRes['MODELS'] as $aModel){
				// $l = mb_substr($aModel['MOD_TITLE'], 0, 1);
					$aVds = explode(',',$aModel['VDS']);
				?><a href="<?=$aModel['FURL']?>" class="ModBox" data-bg="<?=$aModel['IMAGE_PATH']?>" data-mname="<?=$aModel['MOD_TITLE']?>" data-yfrom="<?=$aModel['YEAR_START']?>" data-yto="<?=$aModel['YEAR_END']?>" title="<?=$aModel['ID']?>">
					<div class="ModName"><span class="CmModTitleN"><?=$aModel['MOD_TITLE']?></span><br><?if($aModel['BODY']!=''){?><i><?=$aModel['BODY']?></i><?}?></div>
					<div class="CmModYVDS" <?if(count($aVds)>4){?>style="top:100px;"<?}?>>
						<?if($aModel['YEAR_START']){?>
							<div class="ModYear"><?=$aModel['YEAR_START']?><?if($aModel['YEAR_END']>0){echo '-'.$aModel['YEAR_END'];}else{echo '-'.$aModel['YEAR_TO'];}?></div>
						<?}?>
						<div class="ModVDS"><?=$aModel['VDS']?></div>
					</div>
				</a>
			<?}
		}else{
			echo Lng_x('No_models',1).'...';
		}?>
		<?//echo '<pre>'; print_r($arY); echo '</pre><br><br>';
		//echo max($arY);?>
	</div>
</div>
<?php AjaxCut_x(); //Makes: </div> ?>
<?aprint_x($aRes, '$aRes');?>
<div class="tclear"></div>
<link rel="stylesheet" type="text/css" href="<?=CmAssetUrl_x(TEMPLATE_PAGE_DIR.'blocks/'.$BLOCK.'.css')?>" />


<?=ShowSEOText_x("BOT")?>
