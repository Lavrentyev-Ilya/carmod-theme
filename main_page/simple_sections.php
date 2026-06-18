<?if(!$aSets['HIDE_SECTIONS']){?>
	<?if($aSets['MAINPAGE_DESIGN']){?>
		<div class="BoxSects_x">
			<?foreach($aRes['SECTIONS'] as $aSct){
				if(!isset($aSct['FURL'])){continue;}?>
				<a href="<?=FURL_x?>/<?=$aSct['FURL']?>/">
					<div class="BoxMSct_x">
						<div class="ImgSct_x" style="background-image:url(/<?=CM_DIR?>/media/section/<?=$aSct['NOD']?>.png);"></div>
						<h2 class="NameSct_x"><?=$aSct['NAME']?></h2>
					</div>
				</a>
			<?}?>
		</div>
	<?}else{?>
		<div class="CmSectionWrapBl">
			<?if(isset($aRes['SECTIONS']) AND count($aRes['SECTIONS'])>2){?>
				<div class="CmSearcSectInput">
					<div><input class="CmInputSect" data-lng="<?=$aLngCode?>" type="text" placeholder="<?=Lng_x('Find product section',0)?>.."></div>
					<div class="clearButt">
						<svg class="material-icon" viewBox="0 4 24 24">
							<path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
						</svg>
					</div>
				</div>
			<?}?>
			<div class="boxSections_x">
				<div class="non_res">No result</div>
				<?foreach($aRes['SECTIONS'] as $aSect){
					$ctnSect++; $ChCnt=0; $ShowMoreSub=false; 
					$ChCount = count($aSect['CHILDS']);?>
					<div class="boxSect_x f_box boxSel_x" style="<?if($ctnSect>$aSets['VISIBLE_SECTIONS_COUNT'] && $ctnSect>6){?>display:none<?}?>">
						<div class="boxOverLSect">
							<div class="CmListSectBl">
								<h2 class="nameSect_x f_title" data-fil="<?=$ctnSect?>"><?=$aSect['NAME']?></h2>
								<ul class="CmListSect">
									<?foreach($aSect['CHILDS'] as $aChild){ $ChCnt++;?>
										<li class="sh_list f_list no_a_list" title="<?=$aChild['NAME']?>">
											<a class="" href="<?=FURL_x?>/<?=$aChild['FURL']?>/"><?=$aChild['NAME']?></a>
										</li>
										<?if($ChCnt==$SubSecView AND $ChCount>($SubSecView+1)){
											?><li class="showAllSect" showLNext="sectL<?=$ctnSect;?>"><?=Lng_x('All_sections')?> <span>&#9660;</span></li><?
											$ShowMoreSub=true;
											break;
										}?>
									<?}?>
								</ul>
							</div>
							<?if($ShowMoreSub){?>
								<div id="sectL<?=$ctnSect?>" class="CmListNSectBl" style="display:none;">
									<ul><?$ctnC=0;
										foreach($aSect['CHILDS'] as $aChild){ $ctnC++;?>
											<?if($ctnC>$SubSecView){?>
												<li class="hi_list f_Hlist" title="<?=$aChild['NAME']?>">
													<a class="" href="<?=FURL_x?>/<?=$aChild['FURL']?>/"><?=$aChild['NAME']?></a>
												</li>
											<?}?>
										<?}?>
									</ul>
									<div class="hideAllSect">&#9650;</div>
								</div>
							<?}?>
						</div>
						<div class="CmSectImgBL CmSec_<?=$aNodImg[$aSect['NOD']]?>"></div>
					</div>
					<?//if($ctnSect>5){break;}
				}?>
			</div>
			<?if(isset($aRes['SECTIONS']) AND count($aRes['SECTIONS'])>6 AND $aSets['VISIBLE_SECTIONS_COUNT'] < count($aRes['SECTIONS'])){?>
				<div class="butAllSec c_TxHov"><?=Lng_x('More_sections')?> <span>&#9660;</span></div>
			<?}?>
		</div>
	<?}?>
<?}?>
