<?if (!defined("CM_PROLOG_INCLUDED") || CM_PROLOG_INCLUDED !== true) {die('Restricted:car_info.templ');}
// $aRes - is incoming data array from controller
$aSPEC = $aRes['SPECIFICATIONS'];

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//SECTIONS to SVG Mapping
include('svg.php');
$aBMp = Array(
	165=>'Gas_springs', 
	53=>'Saloon_air_filter', 
	232=>'Wiper_blade', 
	179=>'Accumulator', 
	294=>'Muffler', 
	182=>'Lighting_system', 
	88=>'Brake_pads',
	114=>'Shock_absorber', 
	263=>'Tie_rod_end', 
	244=>'Drive_shaft', 
	79=>'Silent_block', 
	193=>'Starter', 
	132=>'Clutch',
	181=>'Generator', 
);
$aEMp = Array(
	59=>'Belt_drive',
	10=>'Spark_plug', 
	58=>'Gaskets', 
	57=>'Engine_oil', 
	248=>'Fuel_filter', 
	62=>'Engine_oil_filter', 
	65=>'Engine_air_filter',
	64=>'Engine_Timing_Control', 
	60=>'Piston', 
	286=>'Water_pump', 
);
$aSvgBody=[]; $aSvgEngine=[];
if($aSPEC['FUEL_TANK_VALUE']!=''){$FuelTank=true; $SvgBodyCnt++;}
foreach($aBMp as $Nod=>$Code){
	foreach($aRes['SECTIONS'] as $aS){
		if($Nod==$aS['NOD'] AND $SvgBodyCnt<12){
			$aSvgBody[$aS['LINK']]['Svg'] = $aSVG[$Code];
			$aSvgBody[$aS['LINK']]['Code'] = $Code;
			$SvgBodyCnt++;
		}
		foreach($aS['CHILDS'] as $a){
			if($Nod==$a['NOD'] AND $SvgBodyCnt<12){
				$aSvgBody[$a['LINK']]['Svg'] = $aSVG[$Code];
				$aSvgBody[$a['LINK']]['Code'] = $Code;
				$SvgBodyCnt++;
			}
		}
	}
}
foreach($aEMp as $Nod=>$Code){
	foreach($aRes['SECTIONS'] as $aS){
		if($Nod==$aS['NOD'] AND $SvgEngineCnt<12){
			$aSvgEngine[$aS['LINK']]['Svg'] = $aSVG[$Code];
			$aSvgEngine[$aS['LINK']]['Code'] = $Code;
			$SvgEngineCnt++;
		}
		foreach($aS['CHILDS'] as $a){
			if($Nod==$a['NOD']){
				$aSvgEngine[$a['LINK']]['Svg'] = $aSVG[$Code];
				$aSvgEngine[$a['LINK']]['Code'] = $Code;
				$SvgEngineCnt++;
			}
		}
	}
}
if($SvgBodyCnt>$SvgEngineCnt){$sMaxCnt=$SvgBodyCnt;}else{$sMaxCnt=$SvgEngineCnt;}
if($sMaxCnt<=8){$GridRows=206;}
if($sMaxCnt<=3){$GridRows=103;}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//SPECIFICATIONS LINKS MAPPING
$aSpecLiquid  = Array(
	214 => 'AT_OIL',
	202 => 'MT_OIL',
	264 => 'POWER_STEERING_OIL',
	281 => 'ANTIFREEZE',
	135 => 'CLUTCH_FLUID',
	339 => 'REAR_DIFFERENTIAL_OIL',
	92 => 'BRAKE_FLUID',
);
$aSpecFilter = Array(
	217 => 'AT_OIL_FILTER',
	270 => 'POWER_STEERING_OIL_FILTER',
	140 => 'CLUTCH_FLUID',
	878 => 'REAR_DIFFERENTIAL_OIL',
);
foreach($aRes['SECTIONS'] as $aS){
	if(isset($aSpecLiquid[$aS['NOD']])){$aSPEC[$aSpecLiquid[$aS['NOD']].'_LINK'] = $aS['LINK'].'/';}
	if(isset($aSpecFilter[$aS['NOD']])){$aSPEC[$aSpecFilter[$aS['NOD']].'_LINK'] = $aS['LINK'].'/';}
	foreach($aS['CHILDS'] as $a){
		if(isset($aSpecLiquid[$a['NOD']])){$aSPEC[$aSpecLiquid[$a['NOD']].'_LINK'] = $a['LINK'].'/';}
		if(isset($aSpecFilter[$a['NOD']])){$aSPEC[$aSpecFilter[$a['NOD']].'_LINK'] = $a['LINK'].'/';}
	}
}
$aSpecLng = Array(
	'AT_OIL' => 'AT_oil',
	'MT_OIL' => 'MT_oil',
	'POWER_STEERING_OIL' => 'Power_steering_fluid',
	'ANTIFREEZE' => 'Antifreeze',
	'CLUTCH_FLUID' => 'Clutch_fluid',
	'REAR_DIFFERENTIAL_OIL' => 'Rear_differential_oil',
	'BRAKE_FLUID' => 'Brake_fluid',
);


$SchemeSVG = '<svg x="0px" y="0px" width="14px" height="14px" viewBox="0 0 488.3 488.3">
<path d="M314.25,85.4h-227c-21.3,0-38.6,17.3-38.6,38.6v325.7c0,21.3,17.3,38.6,38.6,38.6h227c21.3,0,38.6-17.3,38.6-38.6V124
			C352.75,102.7,335.45,85.4,314.25,85.4z M325.75,449.6c0,6.4-5.2,11.6-11.6,11.6h-227c-6.4,0-11.6-5.2-11.6-11.6V124
			c0-6.4,5.2-11.6,11.6-11.6h227c6.4,0,11.6,5.2,11.6,11.6V449.6z"/>
		<path d="M401.05,0h-227c-21.3,0-38.6,17.3-38.6,38.6c0,7.5,6,13.5,13.5,13.5s13.5-6,13.5-13.5c0-6.4,5.2-11.6,11.6-11.6h227
			c6.4,0,11.6,5.2,11.6,11.6v325.7c0,6.4-5.2,11.6-11.6,11.6c-7.5,0-13.5,6-13.5,13.5s6,13.5,13.5,13.5c21.3,0,38.6-17.3,38.6-38.6
			V38.6C439.65,17.3,422.35,0,401.05,0z"/>
</svg>';

$aListSVG = [
        // Свёрнуто: шеврон вправо
        'CmPlus' => '<svg class="CmChevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>',
        // Развёрнуто: шеврон вниз
        'CmMinus' => '<svg class="CmChevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>',
];
?>
<div class="CmTopBox">
    <div class="CmHeadTitleWrapBlock">
        <div class="CmTitleBradWrap">
            <div class="CmTitleBox">
                <div class="CmHeadSecPicture" style="background-image:url(/<?=CM_DIR?>/media/brands/90/<?=$aRes['BRAND_CODE']?>.png)"></div>
            </div>
        </div>
        <div class="CmMSelectBlock CmMSelectPositionRight"><?
            CmModelSelectorWidget('default',96);?>
        </div>
    </div>
</div>

<div class="CmBrTitleSearchWrap CmBrTitleSearchWrapLight">
    <div id="CmTitlH1Page"><h1><?=H1_x?></h1></div>
</div>

<?BreadCrumbs_x(); // Edit in: ../templates/default/includes.php ?>

	<div class="CmSectionCategWrapBlock">
		<div class="CmSearchSectionWrapBl">
			<?if($aRes['SECTIONS']){?>
				<div class="CmBrSearchWrap">
					<div class="CmTitleSearchBlock">
						<?if(count($aRes['SECTIONS'])>6){?>
							<div class="CmSearcSectInput">
								<input class="CmInputSect" data-lng="<?=$aLngCode?>" type="text" placeholder="<?=Lng_x('Find product section',0)?>..">
								<div class="CmClearButt">
									<svg class="material-icon" viewBox="0 1.5 24 24">
										<path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
									</svg>
								</div>
							</div>
						<?}?>
					</div>
				</div>
				<div class="CmSectionBoxWrap">
				<?//echo '<pre>'; print_r($aRes['SECTIONS']); echo '</pre><br><br>';?>
					<?foreach($aRes['SECTIONS'] as $Code => $a){?>
						<div class="CmSubLevNameWrap <?if(!$a['CHILDS']){?>CmPadLeft23<?}?>">
							<div class="CmSecondLevWrapBl">
								<?if($a['CHILDS']){$l++?>
									<div class="CmLickToOpen CmPlusMinusBlock">
										<span class="ShowMinus" style="display:none;"><?=$aListSVG['CmMinus']?></span>
										<span class="ShowPlus" style=""><?=$aListSVG['CmPlus']?></span>
									</div>
									<a href="javascript:void(0);" class="CmSecondLevelLink CmLickToOpen"><?=$a['NAME']?></a>
								<?}else{?>
									<a href="<?=$a['LINK']?>/" class="CmSecondLevelLink"><?=$a['NAME']?></a>
								<?}?>
							</div>
							<ul class="CmThirdLevelList CmShowHide CmMainNod<?=$a['NOD']?>" <?if(count($aRes['SECTIONS'])<3){?>style="display:block;"<?}?> >
								<?foreach($a['CHILDS'] as $aS){?>
									<li class="CmThirdLevelItem">
										<a href="<?=$aS['LINK']?>/" class="CmThirdLevelLink"><?=$aS['NAME']?><span class="CmThirdSec CmIdSec<?=$aS['NOD']?>"></span></a>
									</li>
								<?}?>
							</ul>
						</div>
					<?}?>
					<div style="width:100%;"></div>
				</div>
			<?}?>
		</div>


		<div class="CmCategWrapBlock_new">
			<?if($aRes['VIN']){?>
				<div><div class="CmVinPlate"><div class="CmVinTx">VIN</div><div class="CmVinValue"><?=$aRes['VIN']?></div></div></div>
			<?}?>
			<div class="CmBoxCarBody CmFlexBasis45" <?if($GridRows){?>style="grid-template-rows:100px <?=$GridRows?>px;"<?}?> >
				<div class="CmInnerBlock_1_1">
					<div class="CmModelBody" data-wd="<?=$aRes['TYPE_DRIVE']?>">
						<div class="CmModBodyBl CmColorFi">
							<?=$aSVG[$aRes['TYPE_BODY']]?>
							<div class="CmDriveTypeTxt"><?=Lng_x('Body_'.$aRes['TYPE_BODY'],1)?></div>
							<div class="CmBodyDriveType">
								<div class="CmBodyTypeBl">
									<?if($aRes['TYPE_AXLE'] AND $aRes['TYPE_AXLE']!=''){?>
										<?if($aRes['TYPE_DRIVE']){?>,&nbsp;<?}?><?=Lng_x('Axle',1)?>:&nbsp;<?=$aRes['TYPE_AXLE']?>
									<?}?>
								</div>
								<div class="CmDriveTypeTxt"><?=Lng_x($aRes['TYPE_DRIVE'])?></div>
							</div>
						</div>
					</div>
					<div class="CmModelNameBlock">
						<span class="CmModelNameTxt"><?=$aRes['MODEL_NAME']?></span><br>
						<span class="CmTypeNameTxt"><?=$aRes['TYPE_NAME']?></span><br>
						<?if($aRes['MODEL_VDS']){?><span class="CmVdsTxt">(<?=$aRes['MODEL_VDS']?>)</span><br><?}?>
						<span class="CmModelYearTxt"><?=$aRes['TYPE_YEARS_FULL']?></span>
					</div>
				</div>
				
				
				
				<?if($SvgBodyCnt>0){?>
					<div class="CmInnerBlock_1_2">
						<?if($aSPEC['FUEL_TANK_VALUE']!=''){?>
							<div class="CmFuelTankBl CmStrokeSvg CmTitShow" title="<?=Lng_x('Fuel_tank_capacity')?>">
								<div class="CmFuelTankVal"><?=$aSPEC['FUEL_TANK_VALUE']?><span><?=Lng_x('L.')?></span></div>
								<?=$aSVG['Petrol']?>
							</div>
						<?}?>
						<?foreach($aSvgBody as $Link => $aV){?>
							<a href="<?=$Link?>/" class="CmCategSvgImg CmFillSvg CmStrokeSvg CmTitShow CmColorFih CmColorSth" title="<?=Lng_x($aV['Code'])?>"><?=$aV['Svg']?></a>
								
								<?/* if($v['Nod'] == 172 && ($aSPEC['LOW_BEAM_SPEC'] OR $aSPEC['HIGH_BEAM_SPEC'] OR $aSPEC['ANTIFOG_HEADLIGHT_SPEC'] OR $aSPEC['PARKING_LIGHT_SPEC'] OR $aSPEC['PARKING_LIGHT_SPEC'])){?>
									<div class="CmLightSpecsBlock">
										<a href="<?=$v['Link']?>" class="CmCategSvgImg CmFillSvg CmStrokeSvg CmTitShow">
											<?=$v['Img']?>
										</a>
										<div class="CmLightSpecsInnerBlock CmLightSpecsHov">
											<div class="CmBeamWrap">
												<?if($aSPEC['LOW_BEAM_SPEC'] && ($aSPEC['LOW_BEAM_SPEC'] == $aSPEC['HIGH_BEAM_SPEC'])){?>
													<div class="CmBeamTitle"><?=Lng_x('Low_beam')?> / <?=Lng_x('High_beam')?></div>
													<div class="CmBeamValue"><? echo $aSPEC['LOW_BEAM_SPEC'] ? $aSPEC['LOW_BEAM_SPEC'] : $aSPEC['HIGH_BEAM_SPEC']?></div>
												<?}else{
													if($aSPEC['LOW_BEAM_SPEC']){?>
														<div class="CmLowBeamBl">
															<div class="CmLowBeamTitle"><?=Lng_x('Low_beam')?></div>
															<div class="CmLowBeamValue"><?=$aSPEC['LOW_BEAM_SPEC']?></div>
														</div>
													<?}?>
													<?if($aSPEC['HIGH_BEAM_SPEC']){?>
														<div class="CmHighBeamBl">
															<div class="CmHighBeamTitle"><?=Lng_x('High_beam')?></div>
															<div class="CmHighBeamValue"><?=$aSPEC['HIGH_BEAM_SPEC']?></div>
														</div>
													<?}?>
												<?}?>
											</div>
											<?if($aSPEC['ANTIFOG_HEADLIGHT_SPEC']){?>
												<div class="CmLightBlockWrap">
													<div class="CmLightBlockTitle"><?=Lng_x('Fog_lights')?></div>
													<div class="CmLightBlockValue"><?=$aSPEC['ANTIFOG_HEADLIGHT_SPEC']?></div>
												</div>
											<?}?>
											<?if($aSPEC['PARKING_LIGHT_SPEC']){?>
												<div class="CmLightBlockWrap CmHiddenLightBlock">
													<div class="CmLightBlockTitle"><?=Lng_x('Parking_lights')?></div>
													<div class="CmLightBlockValue"><?=$aSPEC['PARKING_LIGHT_SPEC']?></div>
												</div>
											<?}?>
											<?if($aSPEC['TURN_LIGHT_SPEC']){?>
												<div class="CmLightBlockWrap CmHiddenLightBlock">
													<div class="CmLightBlockTitle"><?=Lng_x('Fog_lights')?></div>
													<div class="CmLightBlockValue"><?=$aSPEC['TURN_LIGHT_SPEC']?></div>
												</div>
											<?}?>
										</div>
									</div>
								<?} */?>
						<?}?>
					</div>
				<?}?>
			</div>

			<div class="CmBoxCarEngine CmFlexBasis45" <?if($GridRows){?>style="grid-template-rows:100px <?=$GridRows?>px;"<?}?> >
				
				<div class="CmInnerBlockGen CmInnerBlock_2_1">
					<div class="CmEngineInfo CmBgTitle">
						<div class="CmEngineImg">
							<?if($aRes['TYPE_LITRE'] AND $aRes['TYPE_LITRE']>0){?>
								<div class="CmEngTxt CmTitShow" title="<?=$aRes['TYPE_CUBTEC']?>&nbsp;<?=Lng_x('sm',0)?><br><?=Lng_x($aRes['TYPE_MIXTURE'],0)?><br><?=Lng_x($aRes['TYPE_ENGINE_TYPE'],0)?><br><?=Lng_x($aRes['TYPE_GEAR'],0)?>">
									<?=$aRes['TYPE_LITRE']?>
								</div>
							<?}elseif($aRes['TYPE_ENGINE_TYPE']=='Electric'){?>
								<div class="CmEngineElectric"><?=$aSVG['Electric']?></div>
							<?}else{?>
								<div class="CmCubeTxt">
									<span class="CmCubeNum"><?=$aRes['TYPE_CUBTEC']?></span>
									<span class="CmCubeL"><?=Lng_x('sm',1)?><sup>3</sup></span>
								</div>
							<?}?>
							<div class="CmEngineSvgImg"><?=$aSVG['Engine']?></div>
						</div>
						<div class="CmEngineInfTxt">
							<div class="CmEInfTxt1 CmTitShow" title="<?=$aRes['TYPE_ENGINES']?>"><?=$aRes['TYPE_ENGINES']?></div>
							<?if($aRes['TYPE_WHREELBASE'] OR $aRes['TYPE_TONNAGE']){?>
								<div class="">
									<?if($aRes['TYPE_WHREELBASE']){?><?=Lng_x('Chassis',1)?>: <?=$aRes['TYPE_WHREELBASE']?><br><?}?>
									<?if($aRes['TYPE_TONNAGE']){?><?=Lng_x('Tonnage',1)?>: <?=$aRes['TYPE_TONNAGE']?><br><?}?>
								</div>
							<?}?>
							<div class="CmEInfTxt2"><span><b><?=$aRes['TYPE_KW']?></b> <?=Lng_x('Kv',1)?>&nbsp;/&nbsp;<b><?=$aRes['TYPE_HP']?></b>&nbsp;<?=Lng_x('Hp',1)?></span>&nbsp;<span class="CmFuelTypeTxt">&nbsp;<?=Lng_x($aRes['TYPE_FUEL'])?></span></div>
							<div class="CmEngTypeTxt2"><span><?=Lng_x($aSPEC['ENGINE_CONTROL_SPEC'])?></span><span><?=$aSPEC['FIRING_SEQUENCE_SPEC']?></span></div>
						</div>
					</div>
				</div>
				
				<?if($SvgEngineCnt){?>
					<div class="CmInnerBlockGen CmInnerBlock_2_2">
						<?foreach($aSvgEngine as $Link => $aV){?>
							<?if($aV['Code']=='Engine_oil' AND ($aSPEC['MOTOR_OIL_VALUE'] OR $aSPEC['MOTOR_OIL_SPEC'] OR $aSPEC['MOTOR_OIL_INTER'])){?>
								<a href="<?=$Link?>/" class="CmEngOilBl">
									<div class="CmEngOilImg CmTitShow" title="<?=Lng_x('Engine_oil_with_filter_Liters')?>">
										<?if(isset($aSPEC['MOTOR_OIL_VALUE']) AND $aSPEC['MOTOR_OIL_VALUE']!=''){?>
											<div class="CmOilLiter"><?=$aSPEC['MOTOR_OIL_VALUE']?></div>
										<?}else{?>
											<div class="CmOilDropImg"><?=$aSVG['Oil_Drop']?></div>
										<?}?>
										<?=$aV['Svg']?>
									</div>
									<div class="CmEngOilTitleDesc">
										<div class="CmEngOilTitle"><?=Lng_x('Engine_oil')?></div>
										<div class="CmEngOilDescBl">
											<div class="CmEngOilDesc CmEngOilSpec CmTitShow" title="<?=Lng_x('Specification')?>"><?=$aSPEC['MOTOR_OIL_SPEC']?></div>
											<div class="CmEngOilDesc CmTitShow" title="<?=Lng_x('Replacement_interval')?>"><?=$aSPEC['MOTOR_OIL_INTER']?></div>
										</div>
									</div>
								</a>
							<?}else{?>
								<a href="<?=$Link?>/" class="CmCategSvgImg CmColorFih CmColorSth CmFillSvg CmStrokeSvg CmTitShow" title="<?=Lng_x($aV['Code'])?>">
									<?if($aV['Code']=='Engine_oil'){?><div class="CmOilDropImg"><?=$aSVG['Oil_Drop']?></div><?}?>
									<?=$aV['Svg']?>
								</a>
							<?}?>
						<?}?>
					</div>
				<?}?>
			</div>
			
			<?if($aSPEC['FRONT_TYRE_PRESSURE_VALUE'] OR $aSPEC['REAR_TYRE_PRESSURE_VALUE'] OR $aRes['TYRE_VARIANTS']){?>
				<div class="CmInnerBlockHov CmInnerBlockG CmFlexBasis45 CmBlockGlobHeight">
					<div class="CmCategSvgImg CmFillSvg CmStrokeSvg"><?=$aSVG['Wheel']?></div>
					<?if($aRes['TYRE_VARIANTS']){?>
						<div class="CmTireDimensWrap">
							<ul class="CmTyreParList">
								<?foreach($aRes['TYRE_VARIANTS'] as $tyreV){?>
									<li class="CmTireDimenWrap">
										<span class="<?if($tyreV['Variant'] === '1'){?>CmTireDimenMain<?}else{?>CmTireDimenAdd<?}?>">
											<?=$tyreV['Width']?>&sol;<?=$tyreV['Profile']?>&nbsp;<span class="CmTyreDia">R<?=$tyreV['Diameter']?></span>&nbsp;<?=$tyreV['Indexes']?>
										</span>
									</li>
								<?}?>
							</ul>
						</div>
					<?}?>
					<?if($aSPEC['FRONT_TYRE_PRESSURE_VALUE'] OR $aSPEC['REAR_TYRE_PRESSURE_VALUE']){?>
						<div class="CmTirePressure">
							<span class="CmUndLn"><?=Lng_x('Tire_pressure',1)?></span>
							<?if($aSPEC['FRONT_TYRE_PRESSURE_VALUE']){?><span><?=Lng_x('Front',1)?>: <b><?=$aSPEC['FRONT_TYRE_PRESSURE_VALUE']?></b> bar.</span><?}?>
							<?if($aSPEC['REAR_TYRE_PRESSURE_VALUE']){?><span><?=Lng_x('Rear',1)?>: <b><?=$aSPEC['REAR_TYRE_PRESSURE_VALUE']?></b> bar.</span><?}?>
						</div>
					<?}?>
				</div>
			<?}?>
			
			<?
			foreach($aSpecLiquid  as $Code){
				if($aSPEC[$Code.'_VALUE'] OR $aSPEC[$Code.'_SPEC'] OR $aSPEC[$Code.'_INTER']){?>
					<div class="CmInnerBlockHov CmInnerBlockG CmFlexBasis45 CmBlockGlobHeight">
						<div class="CmCategSvgImg CmFillSvg CmStrokeSvg">
							<?=$aSVG['Liquid_oil']?>
							<?if($aSPEC[$Code.'_VALUE']){?>
								<div class="CmLiquidLiterValue"><b><?=$aSPEC[$Code.'_VALUE']?></b><?=trim(Lng_x('L.'),'.')?></div>
							<?}else{?>
								<div class="CmOilDropImg"><?=$aSVG['Oil_Drop']?></div>
							<?}?>
						</div>
						<div class="CmSpecLink" href="<?=$aSPEC[$Code.'_LINK']?>">
							<div class="CmTOilTitle"><?=Lng_x($aSpecLng[$Code])?></div>
							<div class="CmOilValSpecs">
								<div class="CmTOilSpecVal CmTOilSpecs CmTitShow" title="<?=$aSPEC[$Code.'_SPEC']?>"><?=$aSPEC[$Code.'_SPEC']?></div>
								<div class="CmTOilSpecs CmTitShow CmSpecTxtColor" title="<?=Lng_x('Replacement_interval')?>"><?=$aSPEC[$Code.'_INTER']?></div>
								<div class="CmTOilSpecs CmTitShow CmSpecTxtColor" title="<?=Lng_x('Filter_replacement_interval')?>"><?=$aSPEC[$Code.'_FILTER_INTER']?></div>
							</div>
						</div>
						<?if($aSPEC[$Code.'_FILTER_LINK']){ ?>
							<a href="<?=$aSPEC[$Code.'_FILTER_LINK']?>" class="CmCategSvgImg CmTitShow CmTransOilSvgImg" title="<?=Lng_x('Liquide_Filter')?>:<br><?=Lng_x($aSpecLng[$Code])?>">
								<?=$aSVG['Engine_oil_filter']?>
							</a>
						<?}?>
					</div>
				<?}
			}?>
			
			<?if(!count($aRes['SECTIONS'])){?>
				<div class="CmMesNoProd">
					<div class="CmNoProdWarn"></div>
					<div class="CmNoProdText">
						<?=Lng_x('No_products_for_this_model',1)?><br>
						<a href="<?=$aRes['TYPES_FURL']?>" class="CmToTypesLink"><?=Lng_x('Try_choose_another_modification')?> ►</a>
					</div>
				</div>
			<?}?>
		</div>
	</div>

<?if($aSets['NARROW_DESIGN']){?>
	<link rel="stylesheet" type="text/css" href="<?=CmAssetUrl_x(TEMPLATE_PAGE_DIR.'blocks/mini.css')?>" />
<?}?>
<br/>
<?aprint_x($aRes, 'aRes');
aprint_x($aSPEC, 'SPECS');
?>
<?=ShowSEOText_x("BOT")?>
