<?VerifyAccess_x('main.manufacturers.templ'); ?>
<link rel="stylesheet" href="<?=CmAssetUrl_x(TEMPLATE_PAGE_DIR.'css/manufacturers.css')?>">
<script src="<?=TEMPLATE_PAGE_DIR?>js/manufacturers.js"></script>

<div class="CmLogoWr">
	<div class="CmLoHeadCont">
		<div class="CmLoSrchCon">
			<h2 class="CmLoTtl"><?=Lng_x('Brand_mark') ?> - <span class="CmHeadManuf"><?=Lng_x('Passengers', false)?></span></h2>
			<div class="CmLoBntCon">
				<div class="CmLoConIn">
					<input required type="text" name="text" id="CmLoSrcId" class="CmLoSrcInp" autocomplete="off">
					<?=$aSVG['FindCategoryLoupe']?>
					<?=$aSVG['BrandReset']?>
					<label class="CmLoInpLbl"><?=Lng_x('Find_brand', false) ?></label>
				</div> 
				<div class="CmLoVehBtn">
					<?if (($aRes['COUNT_PAS'] ? 1 : 0) + ($aRes['COUNT_COM'] ? 1 : 0) + ($aRes['COUNT_MOT'] ? 1 : 0) > 1){?>
						<ul class="CmLoBtnSec">
							<?if($aRes['COUNT_PAS']){?><li data-type="passenger" selname="<?=Lng_x('Passengers', false)?>" title="<?=Lng_x('Passengers', false)?>" class="CmLoBtn active"><?=$aSVG['PassengerSvg']?></li><?}?>
							<?if($aRes['COUNT_COM']){?><li data-type="comm" selname="<?=Lng_x('Commercial_vehicles', false)?>" title="<?=Lng_x('Commercial_vehicles', false)?>" class="CmLoBtn"><?=$aSVG['CommercialSvg']?></li><?}?>
							<?if($aRes['COUNT_MOT']){?><li data-type="moto" selname="<?=Lng_x('Motorcycles', false)?>" title="<?=Lng_x('Motorcycles', false)?>" class="CmLoBtn"><?=$aSVG['MotorcycleSvg']?></li><?}?>
						</ul>
					<?}?>
				</div>
				
			</div>
		</div>
	</div>
	<?if($aRes['COUNT_PAS']){?>
		<div class="CmSrcErr">Not Found</div>
		<div class="CmLogoCont" data-type="passenger">
			<?if($aRes['COUNT_PAS_FAVORITE']){?>
				<?foreach($aRes['PAS']['FAVORITE'] as $prod){?>
					<a class="CmBrLo" 
					   style="background-image: url('/<?=CM_DIR?>/media/brands/90/<?=$prod['CODE']?>.png')" 
					   data-brand="<?= htmlspecialchars($prod['NAME'])?>" 
					   href="<?= $prod['LINK']?>/" 
					   title="<?=htmlspecialchars($prod['NAME'])?>"></a>
				<?}?>
			<?}?>
		</div>
		
		<div class="CmScBrTit" data-type="passenger">
			<div class="CmSecBrandCont">
				<?if($aRes['COUNT_PAS_MAIN']){?>				
				<?$brandCount = 0;
					$maxVisible = 21;
					foreach($aRes['PAS']['MAIN'] as $prod) {
						$isHidden = $brandCount >=$maxVisible ? 'hidden' : '';?>
						<a href="<?=$prod['LINK'] ?>/" class="CmSecBrand <?=$isHidden?>" title="<?=htmlspecialchars($prod['NAME'])?>">
							<?=htmlspecialchars($prod['NAME'])?>
						</a>
					<?$brandCount++;}?>
				<?}?>
			</div>
			<?if($aRes['COUNT_PAS_MAIN'] > $maxVisible){?>
				<div class="CmSecBtn">
					<a href="#" id="CmBtnMore"></a>
					<span class="CmSecBtnTxt"><?=Lng_x('Show_all',false)?></span>
					<span class="CmSecBtnTxtMor CmSecBtnAc"><?=Lng_x('Show_less',false)?></span>
					<?=$aSVG['BtnArrowSvg']?>
				</div>
			<?}?>
		</div>
	<?}?>

	<?if($aRes['COUNT_COM']){?>
		<div class="CmLogoCont" data-type="comm">
			<?if($aRes['COUNT_COM_FAVORITE']){?>
				<?foreach($aRes['COM']['FAVORITE'] as $prod){?>
					<a class="CmBrLo" 
					   style="background-image: url('/<?= CM_DIR ?>/media/brands/90/<?=$prod['CODE']?>.png')" 
					   data-brand="<?= htmlspecialchars($prod['NAME'])?>" 
					   href="<?=$prod['LINK']?>/" 
					   title="<?= htmlspecialchars($prod['NAME'])?>"></a>
				<? } ?>
			<?}?>
		</div>
		
		<div class="CmScBrTit" data-type="comm" style="display: none;">
			<div class="CmSecBrandCont">
				<?if($aRes['COUNT_COM_MAIN']) { ?>	
				<?$brandCount = 0;
					$maxVisible = 21;
					foreach($aRes['COM']['MAIN'] as $prod) {
						$isHidden = $brandCount >= $maxVisible ? 'hidden' : '';?>
						<a href="<?=$prod['LINK']?>/" class="CmSecBrand <?=$isHidden?>" title="<?=htmlspecialchars($prod['NAME'])?>">
							<?=htmlspecialchars($prod['NAME'])?>
						</a>
					<?$brandCount++;}?>
				<?}?>
			</div>
			<?if($aRes['COUNT_COM_MAIN'] > $maxVisible){?>
				<div class="CmSecBtn">
					<a href="#" id="CmBtnMore"></a>
					<span class="CmSecBtnTxt"><?=Lng_x('Show_all',false)?></span>
					<span class="CmSecBtnTxtMor CmSecBtnAc"><?=Lng_x('Show_less',false)?></span>
					<?=$aSVG['BtnArrowSvg']?>
				</div>
			<?}?>
		</div>
	<?}?>

	<?if($aRes['COUNT_MOT']){?>
		<div class="CmLogoCont" data-type="moto">
			<?if($aRes['COUNT_MOT_FAVORITE']){?>
				<?foreach($aRes['MOT']['FAVORITE'] as $prod){?>
					<a class="CmBrLo" 
					   style="background-image: url('/<?=CM_DIR?>/media/brands/90/<?=$prod['CODE']?>.png')" 
					   data-brand="<?= htmlspecialchars($prod['NAME']) ?>" 
					   href="<?=$prod['LINK']?>/" 
					   title="<?=htmlspecialchars($prod['NAME'])?>"></a>
				<? } ?>
			<?}?>
		</div>
		
		<div class="CmScBrTit" data-type="moto" style="display: none;">
			<div class="CmSecBrandCont">
				<?if($aRes['COUNT_MOT_MAIN']) { ?>	
				<?$brandCount = 0;
					$maxVisible = 21;
					foreach($aRes['MOT']['MAIN'] as $prod) {
						$isHidden = $brandCount >= $maxVisible ? 'hidden' : '';?>
						<a href="<?= $prod['LINK'] ?>/" class="CmSecBrand <?= $isHidden ?>" title="<?= htmlspecialchars($prod['NAME'])?>">
							<?= htmlspecialchars($prod['NAME']) ?>
						</a>
					<?$brandCount++;}?>
				<?}?>
			</div>
			<?if($aRes['COUNT_MOT_MAIN'] > $maxVisible){?>
				<div class="CmSecBtn">
					<a href="#" id="CmBtnMore"></a>
					<span class="CmSecBtnTxt"><?=Lng_x('Show_all',false)?></span>
					<span class="CmSecBtnTxtMor CmSecBtnAc"><?=Lng_x('Show_less',false)?></span>
					<?=$aSVG['BtnArrowSvg']?>
				</div>
			<?}?>
		</div>
	<?}?>
</div>


<? /*
<div class="ltabs">
	<ul>
		<?if($aRes['COUNT_PAS']){?><li class="CmTabCars CmTabSelManuf" data-name="CmPassVehicBlock"><span><?=Lng_x('Passengers')?></span></li><?}?>
		<?if($aRes['COUNT_COM']){?><li class="CmTabTrucks CmTabSelManuf" data-name="CmComVehicBlock"><span><?=Lng_x('Commercial_vehicles')?></span></li><?}?>
		<?if($aRes['COUNT_MOT']){?><li class="CmTabMotorbike CmTabSelManuf" data-name="CmMotoVehicBlock"><span><?=Lng_x('Motorcycles')?></span></li><?}?>
	</ul><br>
	
		//<?<script>var AllLng = '<?=Lng('All',1,0)?>';</script><div class="carsfilter"><a href="javascript:void(0)"><?=Lng('All',1,0)?></a></div>?>
		
		<?if($aRes['COUNT_PAS']){?>
			<div id="CmPassVehicBlock" class="CmManufContBlock">
				<?if($aRes['COUNT_PAS_FAVORITE']){?>
                    <div class="CmFavManuf">
                        <?foreach($aRes['PAS']['FAVORITE'] as $aProd){
                            ?><a href="<?=$aProd['LINK']?>/" class="favlogo_x" style="background-image:url(/<?=CM_DIR?>/media/brands/90/<?=$aProd['CODE']?>.png)" title="<?=$aProd['NAME']?>"></a><?
                        }?>
                    </div>
				<?}?>
				<?if($aRes['COUNT_PAS_MAIN']){?>
                    <div class="CmMainManuf">
                        <?foreach($aRes['PAS']['MAIN'] as $aProd){?>
                            <a href="<?=$aProd['LINK']?>/" class="mbut_x">
                                <div class="mbutlogo_x" style="background-image:url(/<?=CM_DIR?>/media/brands/<?=$aProd['CODE']?>.png)"></div>
                                <div class="mbuttext_x"><?=$aProd['NAME']?></div>
                            </a><?
                        }?>
                    </div>
				<?}?>
			</div>
		<?}?>
		<?if($aRes['COUNT_COM']){?>
			<div id="CmComVehicBlock" class="CmManufContBlock">
				<?if($aRes['COUNT_COM_FAVORITE']){?>
                    <div class="CmFavManuf">
                        <?foreach($aRes['COM']['FAVORITE'] as $aProd){
                            ?><a href="<?=$aProd['LINK']?>/" class="favlogo_x" style="background-image:url(/<?=CM_DIR?>/media/brands/90/<?=$aProd['CODE']?>.png) " title="<?=$aProd['NAME']?>"></a><?
                        }?>
					</div>
				<?}?>
				<?if($aRes['COUNT_COM_MAIN']){?>
                    <div class="CmMainManuf">
                        <?foreach($aRes['COM']['MAIN'] as $aProd){?>
                            <a href="<?=$aProd['LINK']?>/" class="mbut_x">
                                <div class="mbutlogo_x" style="background-image:url(/<?=CM_DIR?>/media/brands/<?=$aProd['CODE']?>.png)"></div>
                                <div class="mbuttext_x"><?=$aProd['NAME']?></div>
                            </a><?
                        }?>
					</div>
				<?}?>
			</div>
		<?}?>
		<?if($aRes['COUNT_MOT']){?>
			<div id="CmMotoVehicBlock" class="CmManufContBlock">
				<?if($aRes['COUNT_MOT_FAVORITE']){?>
                    <div class="CmFavManuf">
                        <?foreach($aRes['MOT']['FAVORITE'] as $aProd){
                            ?><a href="<?=$aProd['LINK']?>/" class="favlogo_x" style="background-image:url(/<?=CM_DIR?>/media/brands/90/<?=$aProd['CODE']?>.png)" title="<?=$aProd['NAME']?>"></a><?
                        }?>
					</div>
				<?}?>
				<?if($aRes['COUNT_MOT_MAIN']){?>
                    <div class="CmMainManuf">
                        <?foreach($aRes['MOT']['MAIN'] as $aProd){?>
                            <a href="<?=$aProd['LINK']?>/" class="mbut_x">
                                <div class="mbutlogo_x" style="background-image:url(/<?=CM_DIR?>/media/brands/<?=$aProd['CODE']?>.png)"></div>
                                <div class="mbuttext_x"><?=$aProd['NAME']?></div>
                            </a><?
                        }?>
					</div>
				<?}?>
			</div>
		<?}?>           
</div>
 */ ?>
