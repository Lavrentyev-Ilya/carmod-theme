<link rel="stylesheet" href="<?=CmAssetUrl_x(TEMPLATE_PAGE_DIR.'css/sections.css')?>">
<script src="<?=CmAssetUrl_x(TEMPLATE_PAGE_DIR.'js/sections.js')?>"></script>
<div class="CmSctWrap">
    <div class="CmPtsSrchCon">
        <h1 class="CmSctTit"><?
		$H1_Prts = explode('-', H1_x, 2);
		if(count($H1_Prts) === 2){
			echo '<b>'.trim($H1_Prts[0]).'</b> - '.trim($H1_Prts[1]);
		}else{
			echo '<b>'.H1_x.'</b>';
		}?></h1>
        <div class="CmPtsConIn">
            <div class="CmPtsInpGr">
                <input required type="text" name="text" id="CmPtsSrcId" class="CmPtsSrcInp" autocomplete="off">
                <?=$aSVG['FindCategoryLoupe']?>
                <?=$aSVG['PartsReset']?>
                <label class="CmPtsInpLbl" for=""><?=Lng_x('Find_category',false)?>..</label>
            </div>
        </div>
    </div>
    
	<div class="CmSctGrid">
		<?$Num = 0;
		foreach($aRes['SECTIONS'] as $Nod=>$a){
			if(!isset($a['NOD']) OR $a['NOD']==''){continue;}
			$Num++;
			if($Num>$aSets['VISIBLE_SECTIONS_COUNT']){$a['CLASS']='CmSctHidden';}else{$a['CLASS']='CmSctFixed';}?>
			<div tabindex="<?=($Nod+10)?>" class="CmSctFx <?=$a['CLASS']?>" 
				 data-search="<?=htmlspecialchars(strtolower($a['NAME']))?> <? 
				 foreach($a['CHILDS'] as $c){
					 echo htmlspecialchars(strtolower($c['NAME'])).' ';
				 }?>">
				<a href="<?=FURL_x?>/<?=$a['FURL']?>/">
					<div class="CmSctImg" style="background-image:url(/<?=CM_DIR?>/media/section/<?=$Nod?>.png)"></div>
					<div class="CmSctLn"></div>
					<span class="CmSctName <?if(count($a['CHILDS'])>0){?>CmSctNmHov<?}?>"><?=$a['NAME']?></span>
					<?if(count($a['CHILDS'])>0){?>
						<div class="CmSctSubs">
							<a href="<?=FURL_x?>/<?=$a['FURL']?>/" class="CmSctMa"><?=$a['NAME']?></a>
							<?foreach($a['CHILDS'] as $c){
								if($a['CNT']<6){?><a href="<?=FURL_x?>/<?=$c['FURL']?>/" class="CmSctSa" data-name="<?=htmlspecialchars($c['NAME'], ENT_QUOTES)?>"><?=$c['NAME']?></a>
								<div class="CmSubProdTyp" data-section-id="<?=$Nod?>"></div>
								<? $a['CNT']++;}
							}?>
						</div>
					<?}?>
				</a>
            </div>
        <?}?>
		<span class="CmNoResMess">
			<svg width="50px" height="50px" viewBox="0 -0.5 17 17" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g stroke="none" stroke-width="1" fill="" fill-rule="evenodd"><g transform="translate(1.000000, 0.000000)" fill=""><path d="M13.646,2.371 C10.535,-0.739 5.469,-0.74 2.358,2.371 C-0.753,5.483 -0.752,10.548 2.358,13.66 C5.469,16.77 10.534,16.771 13.646,13.66 C16.758,10.547 16.757,5.483 13.646,2.371 L13.646,2.371 Z M3.587,12.431 C1.148,9.993 1.146,6.028 3.58,3.594 C6.014,1.159 9.979,1.162 12.418,3.6 C14.856,6.038 14.857,10.004 12.424,12.438 C9.988,14.872 6.024,14.869 3.587,12.431 L3.587,12.431 Z" class="si-glyph-fill"></path><path d="M10.164,11.063 C9.982,11.063 9.845,10.991 9.776,10.922 L8.009,9.157 L6.314,10.852 C6.248,10.918 6.095,10.998 5.891,10.998 C5.738,10.998 5.507,10.952 5.288,10.733 C5.067,10.513 5.018,10.295 5.017,10.153 C5.013,9.965 5.086,9.823 5.157,9.753 L6.881,8.028 L5.201,6.35 C5.049,6.197 4.922,5.723 5.321,5.325 C5.546,5.1 5.767,5.053 5.914,5.053 C6.097,5.053 6.234,5.125 6.301,5.194 L8.009,6.9 L9.705,5.204 C9.773,5.137 9.925,5.058 10.129,5.058 C10.283,5.058 10.514,5.104 10.733,5.324 C11.111,5.703 11.035,6.134 10.864,6.304 L9.138,8.03 L10.875,9.766 C10.942,9.834 11.021,9.986 11.021,10.19 C11.021,10.344 10.976,10.573 10.756,10.792 C10.531,11.016 10.311,11.063 10.164,11.063 L10.164,11.063 L10.164,11.063 Z" class="si-glyph-fill"></path></g></g></svg>
			<span><?=Lng_x('No_parts_for_model',false)?></span>
		</span>
    </div>
    <?if($aSets['VISIBLE_SECTIONS_COUNT'] < $Num){?>
		<div class="CmSecBtn">
			<a href="#" id="CmBtnMore"></a>
			<span class="CmSecBtnTxt"><?=Lng_x('Show_all_sections',false)?></span>
			<span class="CmSecBtnTxtMor CmSecBtnAc"><?=Lng_x('Show_less',false)?></span>
			<svg id="CmBtnMoreSvg" width="23" height="20" viewBox="0 0 30 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 7L15 19L27 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
		</div>
	<?}?>
</div>
