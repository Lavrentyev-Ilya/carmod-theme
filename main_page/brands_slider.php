<link rel="stylesheet" href="<?=CmAssetUrl_x(TEMPLATE_PAGE_DIR.'css/brands_slider.css')?>">
<script src="<?=TEMPLATE_PAGE_DIR?>js/brands_slider.js"></script>
<?$brandCount = count($aRes['TOP_BRANDS']);
if($brandCount > 5){?>
	<div class="CmBraSlider">
		<div class="CmBraTrack">
			<?$duplicates = 1;
			if($brandCount <= 8){$duplicates = 4;}
			elseif($brandCount <= 15){$duplicates = 3;}
			elseif ($brandCount <= 25){$duplicates = 2;}
			for($i=0; $i < $duplicates; $i++){
				foreach($aRes['TOP_BRANDS'] as $b => $Rat){?>
					<div class="CmBraItem"><img src="/<?=CM_DIR?>/img/supplier/<?=$b?>.jpg" alt=""></div>
				<?}
			}?>
		</div>
	</div>
<?}?>
