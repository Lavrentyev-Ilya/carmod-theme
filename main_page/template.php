<?VerifyAccess_x('main.templ');
//$aRes - is incoming data array from controllers 
//$aSets - Page Settings array (defined at admin side: settings)
//TEMPLATE_PAGE_DIR - RELATIVE site page template folder
//TEMPLATE_HOST_DIR - FULL HOST template path
//aprint_x($aSets);
//echo '<pre>'; print_r($aSets); echo '</pre><br><br>';
$SubSecView = $aSets['VIEWED_SECTIONS']; //Number of viewed subsections inside parent block
if($SubSecView<=0){$SubSecView=6;}


$ReltPath = str_replace($_SERVER['DOCUMENT_ROOT'],'',__DIR__);
$aDir = array_filter(explode('/',$ReltPath));
if(count($aDir)<2){$aDir = array_filter(explode('\\',$ReltPath));} //Windows server back slash fix
list($ModDir) = array_slice($aDir,-4,1);

//echo $ModDir;
if(!defined('CM_PROLOG_INCLUDED')){define('CM_PROLOG_INCLUDED',true);}
global $CPMod;
include($_SERVER['DOCUMENT_ROOT']."/".$ModDir."/config.php");
require_once($_SERVER['DOCUMENT_ROOT']."/".$ModDir."/core/object.php");
//echo '<pre>'; print_r($CPMod->arSettings); echo '</pre><br><br>'; die();

?>

<?if(ShowSEOText_x("TOP",true)){?>
	<div class="CmMetaListWrap">
		<?=ShowSEOText_x("TOP")?>
	</div>
<?}?>
<div class="CmMCont">
	<div class="CmMseSd">
		<?if($aSets['SHOW_MSELECT']){
			?><div class="CmMSelectBlock CmMSelectPositionRight">
				<div class="CmMSeBox">
					<?CmModelSelectorWidget('mainpage',false,false);
					$Pfx=''; $aRegNum_Countries=Array();
					if(isset($CPMod->arSettings['SHOW_HEAD_REGNUM']) AND $CPMod->arSettings['SHOW_HEAD_REGNUM']>0){
						if(LANG_x=='fr'){$aRegNum_Countries = Array('FR');}
						if(LANG_x=='da'){$aRegNum_Countries = Array('DK');}
						if(LANG_x=='en'){$aRegNum_Countries = Array('GB');}
						if(LANG_x=='es'){$aRegNum_Countries = Array('ES'); $Pfx='_es';}
						if(LANG_x=='it'){$aRegNum_Countries = Array('IT');}
						if(LANG_x=='de'){$aRegNum_Countries = Array('A');} //if(LANG_x=='de'){$aRegNum_Countries = Array('A');}
						if(LANG_x=='fi'){$aRegNum_Countries = Array('FI');}
						if(LANG_x=='sv'){$aRegNum_Countries = Array('SW');}
						if(LANG_x=='et'){$aRegNum_Countries = Array('ET');  $Pfx='_et';}
						if(LANG_x=='lv'){$aRegNum_Countries = Array('LV'); $Pfx='_lv';}
						if(LANG_x=='mk'){$aRegNum_Countries = Array('MK');}
					}
					$RegNumCountryCount = count($aRegNum_Countries);?>
					<div>
						<?if(isset($CPMod->arSettings['SHOW_HEAD_VIN']) AND $CPMod->arSettings['SHOW_HEAD_VIN']>0){?>
							<div class="CmBoxMP_rn" style="<?if(isset($CPMod->arSettings['SHOW_HEAD_REGNUM']) AND $CPMod->arSettings['SHOW_HEAD_REGNUM']>0 AND $RegNumCountryCount==1){?>width:55%; float:left;<?}?>">
								<?$VinNum_Template = 'mainpage';
								include(PATH_x.'/add/vinnum/controller.php');?>
							</div>
						<?}?>
						<?if(isset($CPMod->arSettings['SHOW_HEAD_REGNUM']) AND $CPMod->arSettings['SHOW_HEAD_REGNUM']>0){?>
							<?if(count($aRegNum_Countries) == 1){//исключить для стран у которых нету регнум?>
								<div class="CmBoxMP_vn" style="<?if(isset($CPMod->arSettings['SHOW_HEAD_VIN']) AND $CPMod->arSettings['SHOW_HEAD_VIN']>0){?>width:44%; float:right;<?}?>">
									<?$RegNum_Template = 'mainpage';
									if(file_exists(PATH_x.'/add/regnum'.$Pfx.'/controller.php')){
										include(PATH_x.'/add/regnum'.$Pfx.'/controller.php');
									}else{
										include(PATH_x.'/add/regnum/controller.php');
									}
									?>
								</div>
							<?}?>
						<?}?>
					</div>
					<?include_once(PATH_x.'/add/search/mainpage/template.php');?>
					<?if(isset($CPMod->arSettings['SHOW_HELP_FORM']) AND $CPMod->arSettings['SHOW_HELP_FORM']>0){?>
						<script src="<?=TEMPLATE_PAGE_DIR?>js/popup.js"></script>
						<div class="CmBoxButMdl"><a href="#" class="CmSrchLink" id="showButton"><?=Lng_x('Need_help')?></a></div>
					<?}else{?><span style="padding:18px 0;"></span><?}?>
					<div id="modalContainer"></div>
				</div>
			</div>
		<?}?>
		<?if(isset($CPMod->arSettings['SHOW_SLIDER']) AND $CPMod->arSettings['SHOW_SLIDER']>0){?>
			<div class="CmSliderBack">
				<?if(isset($CPMod->arSettings['SHOW_SLIDER']) AND $CPMod->arSettings['SHOW_SLIDER']>0){
					include_once('slider.php');
				}?>
			</div>
		<?}?>
		<?if(!$CPMod->arSettings['SHOW_SLIDER'] AND $aSets['SHOW_MSELECT']){?>
			<div class="CmSliderBack">
				<div class="CmSlBoxSeo"><?=ReplaceConMeta_x($CPMod->arSettings['SLIDER_SEOTEXT'],Array())?></div>
			</div>
		<?}?>
	</div>
</div>

<?if(!$CPMod->arSettings['SHOW_SLIDER'] AND !$aSets['SHOW_MSELECT']){?>
	<div class="CmBoxMinSelect">
		<?CmModelSelectorWidget('default',96);?>
	</div>
<?}?>

<div class="CmPopup" id="CmPopup">
	<div class="CmPopupDia" id="CmPopupDia">
		<h4 class="CmPopupTtl"><?=Lng_x('Need_help_selecting_spare_parts')?></h4>
		<p id="CmPopupTtlP"><?=Lng_x('Contact_us')?>:</p>
		<form id="CmCarForm" novalidate>
			<div class="CmFormGr">
				<input id="CmCardId" class="CmPopIn" placeholder="<?=Lng_x('VIN_OE_part_number',0)?>..." pattern="[A-Za-z0-9]+" title="" required>
			</div>

			<div class="CmFormRow">
				<div>
					<input id="CmPopupBr" class="CmPopIn" placeholder="<?=Lng_x('Brand_mark',0)?>" pattern="[A-Za-z\s]+" title="" required>
				</div>
				<div>
					<input id="CmPopupMod" class="CmPopIn" placeholder="<?=Lng_x('Model',0)?>" pattern="[A-Za-z0-9\s]+" title="" required>
				</div>
				<div>
					<input id="CmPopupEn" class="CmPopIn" type="text" placeholder="<?=Lng_x('Engine_displacement',0)?>" min="1" title="" required>
				</div>
			</div>

			<div class="CmPopupEmPh">
				<div id="CmPopupEm">
					<input id="CmPopupEmail" class="CmPopIn" type="email" placeholder="E-Mail" required>
				</div>
				<div id="CmPopupPh">
					<input id="CmPopupPhone" class="CmPopIn" type="text" placeholder="<?=Lng_x('Phone_number',0)?>" pattern="[0-9]+" title="" required onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
				</div>
			</div>

			<div class="CmFormGr" style="padding-top: 20px;">
				<textarea id="description" class="CmPopup-textarea" placeholder="<?=Lng_x('Describe_your_request',0)?>" rows="4" pattern="[A-Za-z0-9\s,\.]+"></textarea>
			</div>

			<button type="submit" class="CmPopupBtn CmPopupSubBtn"><?=Lng_x('Send_a_request',0)?></button>
			<button id="CmPopupCancBnt" type="button" class="CmPopupBtn CmPopupCancBnt"><?=Lng_x('Close',0)?></button>
		</form>
	</div>
</div>

<script>
document.getElementById("CmCarForm").addEventListener("submit", function(event) {
    event.preventDefault();
    
    // Получаем элементы формы
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.textContent;
    
    // Показываем состояние загрузки
    submitBtn.disabled = true;
    submitBtn.textContent = 'Sending...';
    
    // Собираем данные формы
    const formData = {
        fcardId: document.getElementById("CmCardId").value.trim(),
        fbrand: document.getElementById("CmPopupBr").value.trim(),
        fmodel: document.getElementById("CmPopupMod").value.trim(),
        fengine: document.getElementById("CmPopupEn").value.trim(),
        femail: document.getElementById("CmPopupEmail").value.trim(),
        fphone: document.getElementById("CmPopupPhone").value.trim(),
        fdescription: document.getElementById("description").value.trim()
    };

    /* // Валидация формы
	// Проверка email (обязательное поле + валидный формат)
	const emailField = document.getElementById("CmPopupEmail");
	const emailValue = formData.femail;
	let hasErrors = false;
	
	if (!emailValue) {
		emailField.classList.add('CmErrorField');
		emailField.title = "Email is required";
		hasErrors = true;
	} else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue)) {
		emailField.classList.add('CmErrorField');
		emailField.title = "Valid email is required (Example: user@example.com)";
		hasErrors = true;
	} else {
		emailField.classList.remove('CmErrorField');
		emailField.title = "";
	}
	
	const descField = document.getElementById("description");
	if (!formData.fdescription) {
		descField.classList.add('CmErrorField');
		hasErrors = true;
	} else {
		descField.classList.remove('CmErrorField');
		descField.title = "";
	}
	
	if (hasErrors) {
		submitBtn.disabled = false;
		submitBtn.textContent = originalBtnText;
		return;
	} */
	
	// Валидация формы - проверка, что заполнено хотя бы одно из полей
	const emailField = document.getElementById("CmPopupEmail");
	const phoneField = document.getElementById("CmPopupPhone");
	const emailValue = formData.femail;
	const phoneValue = formData.fphone;
	let hasErrors = false;

	// Сбрасываем предыдущие ошибки
	emailField.classList.remove('CmErrorField');
	emailField.title = "";
	phoneField.classList.remove('CmErrorField');
	phoneField.title = "";

	// Проверка, что заполнено хотя бы одно поле
	if (!emailValue && !phoneValue) {
		emailField.classList.add('CmErrorField');
		phoneField.classList.add('CmErrorField');
		emailField.title = "Please fill in at least one contact field";
		phoneField.title = "Please fill in at least one contact field";
		hasErrors = true;
	} 
	// Если email заполнен, проверяем его валидность
	else if (emailValue && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue)) {
		emailField.classList.add('CmErrorField');
		emailField.title = "Valid email is required (Example: user@example.com)";
		hasErrors = true;
	}

	const descField = document.getElementById("description");
	if (!formData.fdescription) {
		descField.classList.add('CmErrorField');
		hasErrors = true;
	} else {
		descField.classList.remove('CmErrorField');
		descField.title = "";
	}

	if (hasErrors) {
		submitBtn.disabled = false;
		submitBtn.textContent = originalBtnText;
		return;
	}

    // Отправка данных на сервер
    fetch('<?=TEMPLATE_PAGE_DIR?>request.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        // Сначала получаем текст ответа
        return response.text().then(text => {
            console.log("Raw response text:", text);
            
            // Пробуем распарсить JSON
            let parsedData;
            try {
                parsedData = text ? JSON.parse(text) : {};
            } catch (e) {
                console.warn("Failed to parse JSON:", text);
                return {
                    success: false,
                    message: "The server returned invalid data",
                    rawResponse: text
                };
            }
            
            return {
                status: response.status,
                ok: response.ok,
                data: parsedData,
                rawResponse: text
            };
        });
    })
    .then(result => {
        console.log("Processed response:", result);
        /* alert("Ответ сервера:\n" + 
              "Status: " + result.status + "\n" +
              "Data: " + JSON.stringify(result.data, null, 2) + "\n" +
              "Raw: " + result.rawResponse.substring(0, 100) + "..."); */
        
        if (result.ok && result.data.success) {
			CreatePopup('success', '<?=Lng_x('The_form_has_been_successfully_submitted',0)?>!', '<?=Lng_x('We_will_contact_you_shortly',0)?>');
			
			/* // Показываем попап
			setTimeout(() => {
			  popupElement.classList.add('show');
			}, 100);

			// Закрытие попапа
			document.querySelector('.CmCloseSucPopup').addEventListener('click', () => {
			  popupElement.classList.remove('show');
			  setTimeout(() => {
				popupElement.remove();
				document.getElementById("CmPopup").style.display = "none";
				this.reset();
			  }, 300);
			}); */
        } else {
            const errorMsg = result.data.message || 
                           `Server Error (status ${result.status})`;
            //alert(errorMsg);
			showErrorPopup(errorMsg);
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
       // alert("Connection error: " + error.message);
		showErrorPopup("Connection error: " + error.message);
    })
    .finally(() => {
        // Восстанавливаем кнопку
        submitBtn.disabled = false;
        submitBtn.textContent = originalBtnText;
    });
});

// Пример использования:
//const popupElement = createPopup('success', 'Заголовок', 'Текст сообщения');
//document.body.appendChild(popupElement);

// Функция показа попапа с ошибкой
function showErrorPopup(message) {
  const errorPopup = document.createElement('div');
  errorPopup.id = 'successPopup';
  errorPopup.innerHTML = `
    <div class="success-popup-content error-popup">
      <svg viewBox="0 0 24 24" class="success-icon error-icon">
        <path fill="currentColor" d="M12,2C17.53,2 22,6.47 22,12C22,17.53 17.53,22 12,22C6.47,22 2,17.53 2,12C2,6.47 6.47,2 12,2M15.59,7L12,10.59L8.41,7L7,8.41L10.59,12L7,15.59L8.41,17L12,13.41L15.59,17L17,15.59L13.41,12L17,8.41L15.59,7Z"/>
      </svg>
      <h3><?=Lng_x('Error',0)?></h3>
      <p>${message}</p>
      <button class="CmCloseSucPopup">OK</button>
    </div>
  `;

  document.body.appendChild(errorPopup);
  
  setTimeout(() => {
    errorPopup.classList.add('show');
  }, 100);

  document.querySelector('.CmCloseSucPopup').addEventListener('click', () => {
    errorPopup.classList.remove('show');
    setTimeout(() => errorPopup.remove(), 300);
  });
}
</script>

<?/*<div class="CmTopBox">
	<?if($aSets['SHOW_MSELECT']){
		$MSelect_Position='Right'; //Left
		?><div class="CmMSelectBlock CmMSelectPosition<?=$MSelect_Position?>"><?
		$Selector_Template = 'default';
		include_once(PATH_x.'/add/selector/controller.php');
		?></div><?
	}
	?>
</div>

<?=ShowSEOText_x("TOP")?>
<div class="CmBrTitleSearchWrap CmBrTitleSearchWrapLight">
    <div id="CmTitlH1Page"><h1><?=H1_x?></h1></div>
</div>
*/

$_SESSION['SECTIONS'] = $aRes['SECTIONS'];
include_once('top_first.php');

include_once('top_second.php');

$aSortBlocks['SORT_SECTIONS'] = $aSets['SORT_SECTIONS'];
$aSortBlocks['SORT_MANUF'] = $aSets['SORT_MANUF'];
$aSortBlocks['SORT_FEATURED'] = $aSets['SORT_FEATURED'];
$aSortBlocks['SORT_CAROUSEL'] = $aSets['SORT_CAROUSEL'];

asort($aSortBlocks);

// SVG array
include_once('svg.php');

foreach($aSortBlocks as $kBlc=>$Order){
	if($kBlc == 'SORT_SECTIONS' AND $aSets['SHOW_SECTIONS']){
		if($aSets['MAINPAGE_DESIGN']){
			include_once('simple_sections.php');
		}else{
			include_once('sections.php');
		}
	}elseif($kBlc == 'SORT_MANUF' AND $aSets['SHOW_MANUF']){
		include_once('manufacturers.php');
	}elseif($kBlc == 'SORT_FEATURED' AND $aSets['SHOW_FEATURED']){
		//include_once('featured.php');
	}elseif($kBlc == 'SORT_CAROUSEL' AND $aSets['SHOW_CAROUSEL']){
		if(count($aRes['TOP_BRANDS'])>2){
			include_once('brands_slider.php');
		}
	}
}?>
<br/>
<?aprint_x($aSect, '$aSect');?>
<?=ShowSEOText_x("BOT")?>
