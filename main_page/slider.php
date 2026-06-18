<link rel="stylesheet" href="<?=CmAssetUrl_x(TEMPLATE_PAGE_DIR.'css/slider.css')?>">
<style>
<?
global $aSlides;
if(count($aSlides)>0){
	foreach($aSlides as $a){
		echo $a['CSS']; 
	}
}?>
</style>
<script src="<?=TEMPLATE_PAGE_DIR?>js/slider.js"></script>
<div class="CmMSdBox">
	<div class="CmSlider" data-autoplay="<?=$CPMod->arSettings['SLIDER_AUTOPLAY']?>">
		<?if(count($aSlides)>0){
			foreach($aSlides as $a){?>
				<div class="CmBanner" style="background:#000000 url(/<?=CM_DIR.$a['PATH']?>);">
					<?=ReplaceConMeta_x($a['CODE'],Array())?>
				</div>
			<?}
		}?>
	</div>
	<div class="CmMSdDots"></div>
</div>
