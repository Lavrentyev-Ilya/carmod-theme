<? if (!defined("CM_PROLOG_INCLUDED") || CM_PROLOG_INCLUDED !== true) {die('Restricted:car_types.templ');}
// $aRes - is incoming data array from controller
// macking of filter by liter buttons 

$aLiter = array();
if (isset($aRes['ACTUAL'])) {
	//echo '<pre>'; print_r($aRes); echo '</pre><br><br>'; die();
	$UValTp = [
		'LITRE' => [],
		'NAME' => [],
		'TONNAGE' => [],
		'AXLE' => [],
		'BODY' => []
	];
    foreach($aRes['ACTUAL'] as $v => $a){
		if (!empty($a['LITRE'])) {
			$UValTp['LITRE'][$a['LITRE']] = true;
		}
		if (!empty($a['NAME'])) {
			$UValTp['NAME'][$a['NAME']] = true;
		}
		if (!empty($a['TONNAGE'])) {
			$UValTp['TONNAGE'][$a['TONNAGE']] = true;
		}
		if (!empty($a['AXLE'])) {
			$UValTp['AXLE'][$a['AXLE']] = true;
		}
		if (!empty($a['BODY'])) {
			$UValTp['BODY'][$a['BODY']] = true;
		}
		
        if ($a['LITRE'] == '' || $a['LITRE'] == 0) {
            continue;
        }
        $aLiter[$v] = $a['LITRE'];
        $explit = explode('.', $a['LITRE']);
        if (is_numeric($aLiter[$v])) {
            if (count($explit) < 2) {
                $aLiter[$v] .= '.0';
            }
        }
    }
    $aResLit = array_unique($aLiter);
    asort($aResLit);
	if($aRes['MODEL_YEAR_END']=='xx'){$EndY=date("Y");}else{$EndY=$aRes['MODEL_YEAR_END'];}
	for($iY=$aRes['MODEL_YEAR_START']; $iY<=$EndY; $iY++){//for filtr by year
		$aRngYears[]=$iY;
	}
	arsort($aRngYears);
	
	$aSelector = [
		'LITRE' => array_keys($UValTp['LITRE']),
		'NAME' => array_keys($UValTp['NAME']),
		'TONNAGE' => array_keys($UValTp['TONNAGE']),
		'AXLE' => array_keys($UValTp['AXLE']),
		'BODY' => array_keys($UValTp['BODY'])
	];
	sort($aSelector['LITRE']);
	sort($aSelector['NAME']);
	sort($aSelector['TONNAGE']);
	sort($aSelector['AXLE']);
	sort($aSelector['BODY']);
	
	//echo '<pre>'; print_r($aSelector); echo '</pre><br><br>'; die();

    foreach ($aRes['ACTUAL'] as $aUniq) {
        $aResUn[] = $aUniq['BODY'];
    }
    $res_un = array_unique($aResUn);
    if(count($res_un)==1){
        $un_test = 'Y';
    }
}
?>

<div class="cmHeadBox">
    <div class="CmTitleBox">
        <a class="cmlinkLogo" href="<?=FURL_x?>/">
            <div class="cmProdLogo" title="<?= $aRes['BRAND_CODE'] ?>" style="background:url(/<?=CM_DIR?>/media/brands/90/<?=$aRes['BRAND_CODE']?>.png)"></div>
        </a>
        <div class="CmFBylit">
            <?if($aResLit AND  count($aResLit)>1){?>
            <div class="block_filt">
                <div class="fByLiterTitle">
                        <?=Lng_x('Filter_by_liter')?>:
                </div>
                <div class="filt_button">
                    <a href="javascript:void(0)" class="lit_but col_fff CmTypeBg"><b><?=Lng_x('All')?></b></a>
                        <?foreach ($aResLit as $aFbyLit){?>
                    <a href="javascript:void(0)" class="lit_but CmTypeColor"><?=$aFbyLit?></a><?
                        }?>
                </div>
                <div class="clear"></div>
            </div>
            <?}?>
        </div>
        <?//if($aRes['MODEL_IMAGE']){?>
            <!-- <div class="imgcar-x" style="background-image:url(<?=$aRes['MODEL_IMAGE']?>)"></div> -->
        <?//}?>
    </div>
</div>

<?php AjaxCut_x(); //Top of AJAX requested content?>
<script>var All_Lng='<?= Lng_x('All') ?>';</script>
<link rel="stylesheet" href="<?=CmAssetUrl_x(TEMPLDIR_x.'car_types/style.css')?>">
<?if($_REQUEST['CarModAjax']=="Y"){?>
<script src="<?=TEMPLDIR_x?>car_types/funcs.js"></script>
<?}?>
<div class="CmBrTitleSearchWrap">
    <div id="CmTitlH1Page"><h1 class=""><?
		$H1_Prts = explode('-', H1_x, 2);
		if(count($H1_Prts) === 2){
			echo '<b>'.trim($H1_Prts[0]).'</b> — '.trim($H1_Prts[1]);
		}else{
			echo '<b>'.H1_x.'</b>';
		}?></h1></div>
</div>
<?if(!isset($_REQUEST['CarModAjax'])){
    BreadCrumbs_x(); // Edit in: ../templates/default/includes.php
}?>
<div class="CmTypesWrap">
   <?/* <div class="CmFilterH1">
        <?if(isset($_REQUEST['CarModAjax'])){?>
        <div class="CmFBylit <?if(isset($_REQUEST['CarModAjax'])){?>CmFByLitMargin<?}?>">
                <?if(count($aResLit)>1){?>
            <div class="block_filt">
                <div class="fByLiterTitle"><?=Lng_x('Filter_by_liter')?>:</div>
                <div class="filt_button">
                    <a href="javascript:void(0)" class="lit_but col_fff CmTypeBg"><b><?=Lng_x('All')?></b></a>
                            <?foreach ($aResLit as $aFbyLit){?>
                    <a href="javascript:void(0)" class="lit_but CmTypeColor"><?=$aFbyLit?></a><?
                            }?>
                </div>
                <div class="clear"></div>
            </div><?
                }?>
        </div>
        <?}?>
    </div> */?>
    <!-- TYPES TABLE -->
    <div class="CmTypeTable_mod">
        <div class="CmTypeTitleWrap" style="<?if($_REQUEST['CarModAjax'] == 'Y' && count($aRes['ACTUAL']) > 9){?>grid-template-columns: 5fr 0.2fr;<?}?>">
            <div class="CmTypeTitleHead <?if($aRes['VEHICLE_TYPE']=='COM'){?>CmComGridTab<?}?>" >
                <!-- <div class="CmModYearWr"> -->
				<div class="CmLiterTitTxt CmTypeColor">
					<?if($aRes['VEHICLE_TYPE']=='COM'){?>
						 <div class="CmFtSelType custom-select" data-filter="axle">
							<div class="CmSelOption">
								<span><?=Lng_x('Axle')?></span>
								<svg class="dropdown-arrow" viewBox="0 0 24 24">
									<path d="M7 10l5 5 5-5z"/>
								</svg>
							</div>
							<div class="CmOptContainer">
								<div class="CmSelOpt" data-value=""><?=Lng_x('Axle')?></div>
								<?foreach($aSelector['AXLE'] as $v){?>
									<div class="CmSelOpt" data-value="<?=$v?>"><?=$v?></div>
								<?}?>
							</div>
						</div>
					<?}else{?>
						<div class="CmFtSelType custom-select" data-filter="liter">
							<div class="CmSelOption">
								<span>L.</span>
								<svg class="dropdown-arrow" viewBox="0 0 24 24">
									<path d="M7 10l5 5 5-5z"/>
								</svg>
							</div>
							<div class="CmOptContainer">
								<div class="CmSelOpt" data-value="">L.</div>
								<?foreach($aSelector['LITRE'] as $v){?>
									<div class="CmSelOpt" data-value="<?=$v?>"><?=$v?></div>
								<?}?>
							</div>
						</div>
					<?}?>
				</div>
				<div class="CmModelTitTxt CmTypeColor"><b>
					<div class="CmFtSelType custom-select" data-filter="names">
						<div class="CmSelOption">
							<span><?=Lng_x('Model')?></span>
							<svg class="dropdown-arrow" viewBox="0 0 24 24">
								<path d="M7 10l5 5 5-5z"/>
							</svg>
						</div>
						<div class="CmOptContainer">
							<div class="CmSelOpt" data-value=""><?=Lng_x('Model')?></div>
							<?foreach($aSelector['NAME'] as $v){?>
								<div class="CmSelOpt" data-value="<?=$v?>"><?=$v?></div>
							<?}?>
						</div>
					</div>
				</b><div class="CmEngMobBlock"><?=Lng_x('Engine')?></div></div>
                <div class="CmYearTitTxt CmTypeColor">
					<div class="CmFtSelType custom-select" data-filter="year">
						<div class="CmSelOption">
							<span><?=Lng_x('Year')?></span>
							<svg class="dropdown-arrow" viewBox="0 0 24 24">
								<path d="M7 10l5 5 5-5z"/>
							</svg>
						</div>
						<div class="CmOptContainer">
							<div class="CmSelOpt" data-value=""><?=Lng_x('Year')?></div>
							<?foreach($aRngYears as $v){?>
								<div class="CmSelOpt" data-value="<?=$v?>"><?=$v?></div>
							<?}?>
						</div>
					</div>
					<div class="CmLitMobBl">L.</div>
				</div>
				<?if($aRes['VEHICLE_TYPE']=='COM'){?>
					<div class="CmTonTitTxt CmTypeColor">
						<div class="CmFtSelType custom-select" data-filter="tonnage">
							<div class="CmSelOption">
								<span><?=Lng_x('Tonnage')?></span>
								<svg class="dropdown-arrow" viewBox="0 0 24 24">
									<path d="M7 10l5 5 5-5z"/>
								</svg>
							</div>
							<div class="CmOptContainer">
								<div class="CmSelOpt" data-value=""><?=Lng_x('Tonnage')?></div>
								<?foreach($aSelector['TONNAGE'] as $v){?>
									<div class="CmSelOpt" data-value="<?=$v?>"><?=round(($v/1000),2)?></div>
								<?}?>
							</div>
						</div>
					</div>
				<?}?>
                <div class="CmEngTitTxt CmTypeColor">
					<input type="text" id="CmFtSrcEngn" placeholder="<?=Lng_x('Engine_type')?>" class="filter-input ImpTypeSt">
				</div>
                <!-- </div> -->
                <div class="CmHpLitCylWr CmTypeColor">
                    <span><?=Lng_x('Power')?></span>
                    <div class="CmTypesMobDriveTit CmTypeColor">
                        <?if($un_test != 'Y'){
                            echo Lng_x('Body');
                        }else{
                            echo Lng_x('Drive');
                        }?>
                    </div>
                    <!--
                    <div><span class="CmCylBl"><?=Lng_x('Cylinder')?></span></div> -->
                </div>
                <div class="CmDriveBlTit">
                    <span class="CmTypeColor">
                        <?if($un_test != 'Y'){?>
							<div class="CmFtSelType custom-select" data-filter="body">
								<div class="CmSelOption">
									<span><?=Lng_x('Body')?></span>
									<svg class="dropdown-arrow" viewBox="0 0 24 24">
										<path d="M7 10l5 5 5-5z"/>
									</svg>
								</div>
								<div class="CmOptContainer">
									<div class="CmSelOpt" data-value=""><?=Lng_x('Body')?></div>
									<?foreach($aSelector['BODY'] as $v){?>
										<div class="CmSelOpt" data-value="<?=$v?>"><?=$v?></div>
									<?}?>
								</div>
							</div>
						<?}else{
                            echo Lng_x('Drive');
                        }?>
                    </span>
                </div>
                <div class="CmEmptyBl"></div>
            </div>
            <div style="padding:0px 14px;"></div>
            <?if($_REQUEST['CarModAjax'] == 'Y' && count($aRes['ACTUAL']) > 9){?>
                <div></div>
            <?}?>
        </div>
        <?//aprint_x($aRes['ACTUAL']);?>
        <div class="CmMainTypeTable">
            <div class="CmTypeTabInner" <?if($_REQUEST['CarModAjax']=="Y"){?>style="max-height:400px;"<?}?>>
                <?if(isset($aRes['ACTUAL'])) {
                    if(count($aRes['ACTUAL'])<8){
                        echo '<style>.block_filt{display:none;}</style>';
                    }
                    foreach ($aRes['ACTUAL'] as $TypID=>$a) {
                        if ($a['LITRE']=='' || $a['LITRE']=='0'){
                            $LitCode = 'X';
                        }else{
                            $LitCode = $a['LITRE'];
                        }?>
                    <div class="CmTypeListWrap" data-liter="<?=$a['LITRE']?>" data-axle="<?=$a['AXLE']?>" data-names="<?=$a['NAME']?>" data-year="<?=$a['YEARS_FULL']?>" data-tonnage="<?=$a['TONNAGE']?>" data-body="<?=$a['BODY']?>" data-engine="<?=$a['ENGINE_CODE']?>">
                        <a class="CmModelLink <?if($aRes['VEHICLE_TYPE']=='COM'){?>CmComGridTab<?}?>" href="<?=$a['FURL']?>/">
                            <div class="CmModelNameLiter">
								<?if($aRes['VEHICLE_TYPE']=='COM'){
									echo $a['AXLE'];
								}else{
									if($a['LITRE']>0){echo $a['LITRE'];}else{echo '--';}
								}?>
                            </div>
							<div class="CmModelName">
                                <div class="CmModBlWrap">
                                    <span class="CmTypeColor"><b><?=$a['NAME']?></b></span>
                                    <?if($a['VDS']){ echo '<span class="CmProto"> ('.$a['VDS'].')</span> ';}?>
                                </div>
                                <div class="CmEngMobTxtBlock">
                                    <span class="asup eCol<?=$a['ENGINE_TYPE']?>"><?=Lng_x($a['ENGINE_TYPE'])?>,&nbsp;</span>
                                    <?if($a['ENGINE_CODE']!=''){
                                        echo $a['ENGINE_CODE'];
                                    }else{echo '--';}?>
                                </div>
                            </div>
                            <div class="CmYearsBl">
                                <span class="CmYearFull"><nobr><?=$a['YEARS_FULL']?></nobr></span>
                            </div>
                            <div class="CmYearsBlShort">
                                <span class="CmYearFull"><nobr><?=$a['YEARS_SHORT']?></nobr></span>
                                <div class="CmLiterMobTxt CmTypeColor">
                                    <?if($a['LITRE']>0){?><span class="CmTypeColor"><b><?=$a['LITRE'];?></b></span><?}else{echo '--';}?>
                                </div>
                            </div>
                            <?if($aRes['VEHICLE_TYPE']=='COM'){?>
								<div class="CmTonTitTxt CmTypeColor"><?=round(($a['TONNAGE']/1000),2)?></div>
							<?}?>
                            <div class="CmEngTitTxt">
                                <span class="asup eCol<?=$a['ENGINE_TYPE']?>"><?=Lng_x($a['ENGINE_TYPE'])?>,&nbsp;</span>
                                <?if($a['ENGINE_CODE']!=''){
                                    echo $a['ENGINE_CODE'];
                                }else{echo '--';}?>
                            </div>
                            <div class="CmHpLitCylWrap">
                                <?if(isset($a['KW'])){?>
                                    <div class="CmKwBl"><b><?=$a['KW']?></b><?=Lng_x('Kw')?></div>
                                    <div class="CmSpace">/</div>
                                    <div class="CmHpBl"><b><?=$a['HP']?></b><?=Lng_x('Hp')?></div>
                                <?}?>
                                <div class="CmTypesMobDriveView">
                                    <?if($un_test!='Y'){?>
                                        <?=Lng_x($a['BODY'])?>
                                    <?}else{
                                        if($a['DRIVE']){
                                            $bodyRepl = str_replace(' ', '', $a['DRIVE']);
                                            $bodyLow = strtolower($bodyRepl);?>
                                            <img src="<?=TEMPLDIR_x?>car_types/images/<?=$bodyLow?>.png"/>
                                        <?}
                                    }?>
                                </div>
                            </div>
                            <div class="CmDriveBl">
                                <?if($un_test!='Y'){?>
                                    <?=Lng_x($a['BODY'])?>
                                <?}else{
                                    if($a['DRIVE']){
                                        $bodyRepl = str_replace(' ', '', $a['DRIVE']);
                                        $bodyLow = strtolower($bodyRepl);?>
                                        <img src="<?=TEMPLDIR_x?>car_types/images/<?=$bodyLow?>.png" title="<?=Lng_x($a['DRIVE'],0)?>"/>
                                    <?}
                                }?>
                            </div>
                        </a>
                        <div class="CmInfoBl CmDopInfoShow">
                            <svg class="CmInfoSvgIm" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-.001 5.75c.69 0 1.251.56 1.251 1.25s-.561 1.25-1.251 1.25-1.249-.56-1.249-1.25.559-1.25 1.249-1.25zm2.001 12.25h-4v-1c.484-.179 1-.201 1-.735v-4.467c0-.534-.516-.618-1-.797v-1h3v6.265c0 .535.517.558 1 .735v.999z"/></svg>
                            <div class="CmDopInfBlWrap">
                                <div class="CmDopInfBlock">
                                    <div class="CmCloseBlock">
                                        <svg class="CmClButImage" viewBox="0 0 24 24"><path d="M23.954 21.03l-9.184-9.095 9.092-9.174-2.832-2.807-9.09 9.179-9.176-9.088-2.81 2.81 9.186 9.105-9.095 9.184 2.81 2.81 9.112-9.192 9.18 9.1z"/></svg>
                                    </div>
                                    <div class="CmInfoRowBl">
                                        <div class="CmDopTitle CmTypeColor">
                                            <?=Lng_x('Power')?>:
                                        </div>
                                        <div class="CmDopVal">
                                            <?if (isset($a['KW'])){
                                                echo $a['KW'];?>
                                                <span><?=Lng_x('Kv', 1, 0)?></span>/
                                                <?=$a['HP']?>
                                                <span><?=Lng_x('Hp',1,0)?></span>
                                            <?}?>
                                        </div>
                                    </div>
                                    <div class="CmInfoRowBl">
                                        <div class="CmDopTitle CmTypeColor">
                                            <?=Lng_x('Capacity')?>:
                                        </div>
                                        <div class="CmDopVal">
                                            <?if(isset($a['LITRE'])){
                                                echo $a['LITRE'];?>
                                                <span><?=Lng_x('sm',1,0) ?><sup>3</sup></span>
                                            <?}?>
                                        </div>
                                    </div>
                                    <div class="CmInfoRowBl">
                                        <div class="CmDopTitle CmTypeColor">
                                            <?=Lng_x('Cylinder')?>:
                                        </div>
                                        <div class="CmDopVal">
                                            <?=$a['CYLINDER'] ?>
                                        </div>
                                    </div>
                                    <?if($a['ENGINE_TYPE']){?>
                                        <div class="CmInfoRowBl">
                                            <div class="CmDopTitle CmTypeColor">
                                                <?=Lng_x('Type_of_engine')?>:
                                            </div>
                                            <div class="CmDopVal">
                                                <?=Lng_x($a['ENGINE_TYPE'])?>
                                            </div>
                                        </div>
                                    <?}?>
                                    <?if($a['DRIVE']){?>
                                        <div class="CmInfoRowBl">
                                            <div class="CmDopTitle CmTypeColor">
                                                <?=Lng_x('Drive')?>:
                                            </div>
                                            <div class="CmDopVal">
                                                <?=Lng_x($a['DRIVE'])?>
                                            </div>
                                        </div>
                                    <?}?>
                                    <?if($a['BODY']){?>
                                        <div class="CmInfoRowBl">
                                            <div class="CmDopTitle CmTypeColor">
                                                <?=Lng_x('Body')?>:
                                            </div>
                                            <div class="CmDopVal">
                                                <?=Lng_x($a['BODY'])?>
                                            </div>
                                        </div>
                                    <?}?>
                                    <?if($a['MIXTURE']){?>
                                        <div class="CmInfoRowBl">
                                            <div class="CmDopTitle CmTypeColor">
                                                <?=Lng_x('fuel_supply')?>:
                                            </div>
                                            <div class="CmDopVal">
                                                <?=Lng_x($a['MIXTURE'])?>
                                            </div>
                                        </div>
                                    <?}?>
                                    <?if($a['GEAR']){?>
                                        <div class="CmInfoRowBl">
                                            <div class="CmDopTitle CmTypeColor">
                                                <?=Lng_x('transmission')?>:
                                            </div>
                                            <div class="CmDopVal">
                                                <?=Lng_x($a['GEAR'])?>
                                            </div>
                                        </div>
                                    <?}?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?}
                }?>
            </div>
        </div>
    </div>
</div>
<?php AjaxCut_x();?>
<?=aprint_x($aRes, 'aRes');
//Bottom of AJAX requested content
echo ShowSEOText_x("BOT")?>
