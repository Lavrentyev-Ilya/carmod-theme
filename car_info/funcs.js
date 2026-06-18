$(document).ready(function () {

    const topEl = document.querySelector('.CmCategWrapBlock_new');
    if(topEl){

    // Add Hook to emgone block for slide to title
    const idHook = '';
	
    //-- Front or Rear WD color
    const veHicImg = document.querySelector('.CmModelBody');
    const frontWh = document.querySelector('#FrontWheel');
    const rearWh = document.querySelector('#RearWheel');
    const wdColor = veHicImg.dataset.wd;
    if(wdColor && wdColor == 'Front WD'){
		if(frontWh){frontWh.classList.add('CmFillColSvg');}
    }else if(wdColor && wdColor == 'Rear WD'){
		if(rearWh){rearWh.classList.add('CmFillColSvg');}
    }else if(wdColor && wdColor == 'All WD'){
		if(frontWh){frontWh.classList.add('CmFillColSvg');}
		if(rearWh){rearWh.classList.add('CmFillColSvg');}
	}
	
    // Responsive font size on engine block
    const  TxtFontS = () => {
        // Engine text responsive font size
        const engBlock = document.querySelector('.CmEngineImg');
        const engText = document.querySelector('.CmEngTxt');
		const cubeNum = document.querySelector('.CmCubeNum');
		const cubeL = document.querySelector('.CmCubeL');
		if(engBlock){
            if(engText){
                let eW = engBlock.offsetWidth;
                engText.style.fontSize = eW / 5 + "px";
            }else if(cubeNum){
                let cW = engBlock.offsetWidth;
                cubeNum.style.fontSize = cW / 6 + "px";
                cubeL.style.fontSize = cW / 7 + "px";
            }
        }

        // Fuel text responsive font size
        const fuelBlock = document.querySelector('.CmFuelTypeImg');
        const fuelTtext = document.querySelector('.CmFuelLiterTxt');
        if(fuelBlock){
            let fW = fuelBlock.offsetWidth;
            fuelTtext.style.fontSize = fW / 3.3 + "px";
        }

		// Engine Oil text responsive font size
        const engOilBlock = document.querySelector('.CmEngOilImg');
        const engOilTtext = document.querySelector('.CmOilLiter');
        if(engOilTtext && engOilBlock){
            let eNW = engOilBlock.offsetWidth;
            $('.CmOilLiter').css('font-size', eNW / 6 + "px");
        }
		
    }
    window.addEventListener('load', TxtFontS());
    window.addEventListener('resize', TxtFontS);
	
	//SPECIFICATIONS link
	$('.CmSpecLink').on('click', function () {
		var Link = $(this).attr("href");
		if(Link!=''){window.location.href = Link;}
	});
	
	//Выпадающий список подразделов
	$('.showAllSect').on('click', function () {
        $(this).hide();
        var showLNext = $(this).attr("showLNext");
        var widthBoxSect = $('.boxSect_x').width();
        showLNext.toString();
        $('.CmListNSectBl').css({ width: '100%' });
        $(this).closest('.boxSect_x').css({ boxShadow: '0px 0px 10px 1px #d0d0d0' });

        $('#' + showLNext).slideDown(400);
        $(this).parent().parent().parent().parent('.boxSect_x').mouseleave(function () {
            $(this).find('.CmListNSectBl').slideUp(400);
            $(this).closest('.boxSect_x').css({ boxShadow: 'none' });
            $(this).find('.showAllSect').delay(500).fadeIn(200);
        });
	});

	//Прятать подразделы
	$('.hideAllSect').on('click', function () {
		$(this).parent().slideUp(400);
		$(this).closest('.boxSect_x').css({ boxShadow: 'none' });
	});

	//Выпадающий список разделов
	$('.butAllSec').on('click', function () {
		$('.boxSect_x').show();
		$('.butAllSec').remove();
	});

    //MORE SPECS POPUP
    $('.more_specs').on('click', function () {
        $('.CmOverlaySpecs').show().css({display:'flex', justifyContent:'center'});
        $('.CmSpecs').show();
        var cmspec = $('.CmSpecsBl').detach();
        cmspec.appendTo('.CmContSpecs');
        $('.CmContSpecs').append('<div class="CmCloseButSpecs"></div>');
    });

    //CLOSE MORE SPECS POPUP
    $('#CmContent').on('click', ".CmCloseButSpecs", function (e) {
        e.stopPropagation();
        $(".CmOverlaySpecs").hide();
        return false;
    });
    $(document).mousedown(function (e){
        if($(".CmModalSpecs").has(e.target).length === 0){
            $(".CmOverlaySpecs").hide();
        }
    });

    //OPEN EACh SPECS IN POPUP
    $('#CmContent').on('click', '.sp_name', function () {
        $('.spec_name').each(function () {
            $(this).find('.sp_name').removeClass('SpNameCol');
            $(this).find('span').removeClass('SpNameSpanCol');
        });
        $('.CmSubSpec').hide();
        $(this).parent('.spec_name').find('.CmSubSpec').show();
        $(this).addClass('SpNameCol');
        $(this).find('span').addClass('SpNameSpanCol');
    });

    //IMAGE IN SPECS POPUP
    $('#CmContent').on('click', '.cm_Spimg', function (e){
        e.stopPropagation();
        $('.CmOverlayImg').show().css({display:'flex', justifyContent:'center'});
        var img = $(this).data('img');
        $('.CmContImg').html('<div class="CmCloseButImg"></div><img src="'+img+'"/>');
    });

    //CLOSE IMAGE IN SPECS POPUP
    $('#CmContent').on('click', ".CmCloseButImg", function (e) {
        e.stopPropagation();
        $(".CmOverlayImg").hide();
        return false;
    });
    $(document).mousedown(function (e){
        e.stopPropagation();
        if($(".CmModalImg").has(e.target).length === 0){
            $(".CmOverlayImg").hide();
        }
    });

    // Another modification
    $("#CmContent").on("click", ".CmVehicDescWrap ", function (e) {
        e.preventDefault();
        $('.fxOverlay').css('display', 'flex');
        $('.fxCont').html('<div class="CmSchLoadWrap" style="display:block; top:0; left:0;"><div class="CmSchLoading" style="width:65px; height:50px;"><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div><div class="CmLoadDot"></div></div></div>');
        $.post($(this).data('href'), { CarModAjax: 'Y' }, function (data) {
            $('.fxCont').html('<div class="fxClose"></div>'+data);
            $('.fxCont').css('width', '1080px');
        });
    });

    var SpecHeight = $('.hid_text').data('height');
    if(SpecHeight!='0'){
        $('.hid_text').show();
        $('.CmSpecsBlockWrap').css({height:SpecHeight+'px'});
    }

	}


	// Section filter --------------------------
	function ResetFilter(iVal = '') {
		if(iVal == 1){
			$('.CmInputSect').val('');
		}
		$('.CmSubLevNameWrap').show();
		$('.CmSubLevNameWrap').find('.CmThirdLevelItem').show();
		$('.CmSubLevNameWrap').find('.CmThirdLevelList').slideUp(300);
		$('.CmSubLevNameWrap').find('.ShowMinus').hide();
		$('.CmSubLevNameWrap').find('.ShowPlus').show();
		$('.CmSubLevNameWrap').find('.CmSecondLevelLink').removeClass('CmActiveItem');
		$('.CmSubLevNameWrap').find('.CmThirdLevelLink').removeClass('CmActiveItem');

	}
	function OpenSection(element, noSubSec = '') {
		$(element).show();
		//$(element).find('.CmSecondLevelLink').addClass('CmColorTx');
		$(element).find('.CmThirdLevelList').slideDown(300);
		$(element).find('.ShowMinus').show();
		$(element).find('.ShowPlus').hide();
		if(noSubSec == 'sub'){
			$(element).addClass('CmNotHideBlock');
		}
	}
	
	$('.CmInputSect').keyup(function(){
		if($(this).val().length >= 2){
			$('.CmClearButt').fadeIn(300);
			const inputVal = $(this).val();
			//console.log(inputVal);
			
			$('.CmThirdSec').text('');
			var oSrchSct = {};
			oSrchSct['CarModAjax']='Y';
			oSrchSct['CmSrchScts']=$(this).val();
			$.post("", oSrchSct, function(Res){
				//alert(Res);
				$('.CmThirdPt').remove();
				var ob = $.parseJSON(Res);
				
				if(typeof(ob.sc) != "undefined" && ob.sc !== null){
					$.each(ob.sc, function(Nod, oNod){
						//alert(Nod);
						//$('.CmIdSec'+oNod.Parent).text(' / '+oNod.Name);
						//$('.CmIdSec'+oNod.Parent).parent().attr('href', oNod.FUrl+'/');
						$('.CmIdSec'+oNod.Parent).parent().parent().parent().append('<li class="CmThirdLevelItem CmThirdPt"><a href="'+oNod.FUrl+'" class="CmThirdLevelLink">'+oNod.ParentName+'<span class="CmThirdSec CmIdSec'+Nod+'"> / '+oNod.Name+'</span></a></li>');
					});
				}
			
				
				if(typeof(ob.pt) != "undefined" && ob.pt !== null){
					$.each(ob.pt, function(Nod, oPt){
						$('.CmMainNod'+Nod).append('<li class="CmThirdLevelItem CmThirdPt"><a href="'+oPt.FUrl+'/?pr='+oPt.PrID+'" class="CmThirdLevelLink">'+oPt.PtName+'<span class="CmThirdSec CmIdSec'+Nod+'"></span></a></li>');
					});
				}
				
				const secLevParent = $('.CmSubLevNameWrap');
				const thLevLink = $('.CmThirdLevelLink');
				const regexTit = new RegExp('\\s*' + inputVal,'i');
				$(secLevParent).each(function(p){
					const secLink = $(this).find('.CmSecondLevelLink').text();
					const thirLink = $(this).find('.CmThirdLevelItem').text();
					if(regexTit.test(secLink)){
						//alert('2'+secLink);
						OpenSection($(this), 'sub');
						$(this).find('.CmSecondLevelLink').addClass('CmActiveItem');
					}else if(regexTit.test(thirLink)){
						OpenSection($(this));
						$($(thLevLink).parent()).each(function(i){
							if(regexTit.test($(this).text())){
								$(this).show();
								$(this).find('.CmThirdLevelLink').addClass('CmActiveItem');
							}else{
								$(this).hide();
								$(this).find('.CmThirdLevelLink').removeClass('CmActiveItem');
							}
						});
					}else{
						$(this).hide();
					}
				});
				$('.CmNotHideBlock').find('.CmThirdLevelItem').show();
				
				// ДОБАВЛЕННЫЙ КОД: скрытие дублирующихся элементов (оставляем тот, у которого больше вложенность и есть GET-параметр)
            var seenNames = {};
            $('.CmThirdLevelItem').each(function() {
                var $this = $(this);
                var link = $this.find('.CmThirdLevelLink');
                var name = link.text().trim();
                var url = link.attr('href');
                
                // Вычисляем вес URL (чем больше вес, тем лучше)
                var urlWeight = 0;
                if(url) {
                    // Вес за GET-параметры
                    if(url.indexOf('?') !== -1) {
                        urlWeight += 10;
                    }
                    // Вес за вложенность (количество слэшей после домена)
                    var slashCount = (url.match(/\//g) || []).length;
                    urlWeight += slashCount;
                }
                
                if(seenNames[name]) {
                    // Если элемент с таким названием уже есть
                    var existingWeight = seenNames[name].weight;
                    var existingElement = seenNames[name].element;
                    
                    if(urlWeight > existingWeight) {
                        // Текущий элемент имеет больший вес - скрываем существующий
                        existingElement.hide();
                        seenNames[name] = {element: $this, weight: urlWeight};
                    } else {
                        // Текущий элемент имеет меньший вес - скрываем его
                        $this.hide();
                    }
                } else {
                    // Запоминаем элемент
                    seenNames[name] = {element: $this, weight: urlWeight};
                }
            });
            // КОНЕЦ ДОБАВЛЕННОГО КОДА
				
			});
			
		}else{
			$('.CmClearButt').fadeOut(300);
			$('.CmThirdSec').text('');
			ResetFilter();
		}
		// ------------------------- Section filter

		

		$('.CmSectionBox').mouseleave(function(e){
				e.preventDefault();
		});
		$('.CmListSectBl').find('li').addClass('f_list');
		$('.CmSectionBox').find('.nameSect_x').addClass('f_title');
		$('.CmListNSectBl').find('li').addClass('f_Hlist');
	//     if($('.CmInputSect').val().length == 0){
	//         $('.CmClearButt').hide();
	//    }
	//     if($('.CmInputSect').val().length > 0){
	//          $('.CmClearButt').show();
	//     }
		if($('.CmInputSect').val().length >= 3){
				var val_inp = $('.CmInputSect').val();
				var sw_x = 0;
			var regexTitle = new RegExp(val_inp,'i');
				var regSubTitle = new RegExp('\\s' + val_inp,'i');
			$('.f_title').each(function(){
					var f_title = $(this).text();
					if (regexTitle.test(f_title)) {
						sw_x = 1;
					}
				});
				$('.f_list').each(function(){
					var f_list = $(this).text();
					if (regexTitle.test(f_list)) {
						sw_x = 1;
					}
				});
				$('.f_Hlist').each(function(){
					var f_Hlist = $(this).text();
					if (regexTitle.test(f_Hlist)) {
						sw_x = 1;
					}
				});
			if(sw_x == 1){
					$('.f_title').each(function(){
						var val_title = $(this).text();
						if (regexTitle.test(val_title)) {
								$(this).show();
						}else{
								$(this).hide().removeClass('f_title');
						}
					});
					$('.f_list').each(function(){
						var val_list = $(this).text();
						if (regSubTitle.test(val_list)) {
								$(this).show();
								$(this).parent().siblings('.CmSectionTitle').show();
						}else{
								$(this).hide().removeClass('f_list');
						}
					});
					$('.f_Hlist').each(function(){
						var val_Hlist = $(this).text();
						if (regSubTitle.test(val_Hlist)) {
								$(this).show();
								$(this).parent().siblings().find('.CmSectionTitle').show();
								$('.hideAllSect').text('');
						}else{
								$(this).hide().removeClass('f_Hlist');
						}
					});
					$('.CmSectionBox').each(function(){
						var titl = $(this).find('.f_title').length;
						var listN = $(this).find('.f_list').length;
						var hideL = $(this).find('.f_Hlist').length;
						if(titl > 0 || listN > 0 || hideL > 0){
								$(this).show();
						}else{
								$(this).hide().removeClass('boxSel_x');
						}
						if(titl == 0 && listN == 0 && hideL > 0){
								$(this).find('.CmListNSectBl').show().addClass('f_sec_block');
								$(this).find('.showAllSect').hide();
						}
						if(hideL == 0){
								$(this).find('.CmListNSectBl').hide().removeClass('f_sec_block');
								$(this).find('.showAllSect').hide();
						}
						if(titl == 1 && (listN == 0 || hideL == 0)){
								$(this).find('.sh_list').show();
								$(this).find('.hi_list').show();
								$(this).find('.CmListNSectBl').hide().removeClass('f_sec_block');
								$(this).find('.showAllSect').show();
						}
					});
				}
		}else{
			$('.f_title, .f_list, .f_Hlist, .boxSect_x, .showAllSect').show();
			$('.CmListSectBl').find('li').addClass('f_list');
			$('.boxSect_x').find('.nameSect_x').addClass('f_title');
			$('.CmListNSectBl').find('li').addClass('f_Hlist');
			$('.CmListNSectBl').hide().removeClass('f_sec_block');
		}
	});
	$('body').on('click', '.CmClearButt', function(){
		$(this).hide();
		ResetFilter(1);
	});

	// Open subsections on click event
	$(document).on('click','.CmLickToOpen',function(){
		$(this).parent().siblings('.CmThirdLevelList').slideToggle(300);
		$(this).children('.ShowMinus').toggle();
		$(this).children('.ShowPlus').toggle();
    });

});
