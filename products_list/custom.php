<?
// Custom sorting of product type filter
//echo '<pre>'; print_r($aRes);echo '</pre>';

$aSort = Array(
	1 => 2234, //Турбина
	2 => 4973, //Картридж турбины
	3 => 9904, //Вал турбины
	4 => 4974, //Геометрия турбины
	5 => 9922, //Крыльчатка (колесо трубины)
	6 => 2420, //Установочный комплект (монтажные наборы)
	//7 => 1111, //Рем.комплект
	8 => 1656, //Стопорное кольцо
	//9 => 2222, //Втукла
	10 => 9918, //Корпус турбины (улитка)
	//11 => 3333, //Уплотнительное кольцо
	//12 => 4444, //Комплект прокладок
	13 => 9916, //Термощиты
	//14 => 5555, //Болты
);

$aSource = $aRes['FILTER_PRODUCTS'];
if($aSource AND count($aSource)){
	$aNew = Array();
	foreach($aSort as $s=>$id){
		foreach($aSource as $ProdID=>$aProduct){
			if($id==$ProdID){
				$aNew[$ProdID] = $aProduct;
				unset($aSource[$ProdID]);
			}
		}
	}
	if($aSource AND count($aSource)){
		foreach($aSource as $ProdID=>$aProduct){
			$aNew[$ProdID] = $aProduct;
		}
	}
	if(count($aNew)){$aRes['FILTER_PRODUCTS'] = $aNew; }
}

?>