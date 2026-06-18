<?
//Google Merchant Center 
// Results Test https://search.google.com/test/rich-results
if(!function_exists('CmProductSchemaPrice_x')){
	function CmProductSchemaPrice_x($Value){
		$Value = html_entity_decode(strip_tags((string)$Value),ENT_QUOTES,'UTF-8');
		$Value = str_replace(array("\xc2\xa0",' '),'',trim($Value));
		$Value = preg_replace('/[^\d.,-]+/','',$Value);
		if($Value==''){return '';}

		$CommaPos = strrpos($Value,',');
		$DotPos = strrpos($Value,'.');
		if($CommaPos!==false AND $DotPos!==false){
			if($CommaPos>$DotPos){
				$Value = str_replace('.','',$Value);
				$Value = str_replace(',','.',$Value);
			}else{
				$Value = str_replace(',','',$Value);
			}
		}else{
			$Value = str_replace(',','.',$Value);
		}
		if(substr_count($Value,'.')>1){
			$LastDotPos = strrpos($Value,'.');
			$Value = str_replace('.','',substr($Value,0,$LastDotPos)).substr($Value,$LastDotPos);
		}
		if(!is_numeric($Value)){return '';}

		$Price = (float)$Value;
		if($Price<=0){return '';}
		return number_format($Price,2,'.','');
	}
}

if(!function_exists('CmProductSchemaCurrency_x')){
	function CmProductSchemaCurrency_x($Value){
		$Value = strtoupper(trim((string)$Value));
		return preg_match('/^[A-Z]{3}$/',$Value) ? $Value : '';
	}
}

$SchemaPrice = CmProductSchemaPrice_x(isset($aRes['Price']) ? $aRes['Price'] : '');
$SchemaCurrency = CmProductSchemaCurrency_x(isset($aRes['Currency']) ? $aRes['Currency'] : '');
$SchemaAvailability = trim((string)(isset($aRes['Availability']) ? $aRes['Availability'] : ''));

if($SchemaPrice==''){
	$aSchemaPriceRows = array();
	if(!empty($aRes['PRICES']) AND is_array($aRes['PRICES'])){
		$aSchemaPriceRows = $aRes['PRICES'];
	}elseif(isset($aProd['PRICES']) AND is_array($aProd['PRICES'])){
		$aSchemaPriceRows = $aProd['PRICES'];
	}
	foreach($aSchemaPriceRows as $aSchemaPriceRow){
		if(!is_array($aSchemaPriceRow)){continue;}
		$CandidatePrice = '';
		$CandidateCurrency = '';
		if(isset($aSchemaPriceRow['PRICE_CONVERTED'])){
			$CandidatePrice = CmProductSchemaPrice_x($aSchemaPriceRow['PRICE_CONVERTED']);
			$CandidateCurrency = CmProductSchemaCurrency_x(isset($aSchemaPriceRow['CURRENCY_CONVERTED']) ? $aSchemaPriceRow['CURRENCY_CONVERTED'] : '');
		}
		if($CandidatePrice==''){
			$CandidatePrice = CmProductSchemaPrice_x(isset($aSchemaPriceRow['PRICE_DISCOUNTED']) ? $aSchemaPriceRow['PRICE_DISCOUNTED'] : '');
			$CandidateCurrency = CmProductSchemaCurrency_x(isset($aSchemaPriceRow['CURRENCY']) ? $aSchemaPriceRow['CURRENCY'] : '');
		}
		if($CandidatePrice==''){
			$CandidatePrice = CmProductSchemaPrice_x(isset($aSchemaPriceRow['PRICE_LOADED']) ? $aSchemaPriceRow['PRICE_LOADED'] : '');
			$CandidateCurrency = CmProductSchemaCurrency_x(isset($aSchemaPriceRow['CURRENCY']) ? $aSchemaPriceRow['CURRENCY'] : '');
		}
		if($CandidatePrice==''){
			$CandidatePrice = CmProductSchemaPrice_x(isset($aSchemaPriceRow['PRICE_VALUE']) ? $aSchemaPriceRow['PRICE_VALUE'] : '');
			$CandidateCurrency = CmProductSchemaCurrency_x(isset($aSchemaPriceRow['CURRENCY_CONVERTED']) ? $aSchemaPriceRow['CURRENCY_CONVERTED'] : '');
		}
		if($CandidatePrice==''){continue;}
		$SchemaPrice = $CandidatePrice;
		if($SchemaCurrency==''){$SchemaCurrency = $CandidateCurrency;}
		if($SchemaAvailability=='' AND isset($aSchemaPriceRow['AVAILABLE_NUM'])){
			$SchemaAvailability = intval($aSchemaPriceRow['AVAILABLE_NUM'])>0 ? 'InStock' : 'OutOfStock';
		}
		break;
	}
}
if($SchemaCurrency=='' AND defined('CURR_x')){
	$SchemaCurrency = CmProductSchemaCurrency_x(CURR_x);
}
if($SchemaAvailability==''){
	$SchemaAvailability = 'InStock';
}
if($SchemaPrice!='' AND $SchemaCurrency!=''){
	if(!$aRes['Name']){$aRes['Name']=$aRes['Brand'].' '.$aRes['AKey'];}
	$LdName = trim($aRes['Brand'].' '.$aRes['ArtNum'].' '.$aRes['Name']);
	if($LdName==''){$LdName = trim((string)$aRes['Name']);}
	if(defined('CM_CANONICAL') AND CM_CANONICAL!=''){
		$SchemaUrl = CM_CANONICAL;
	}else{
		$SchemaUrl = trim((string)$aRes['DETAIL_PAGE_URL']);
	}
	$SchemaData = Array(
		"@context" => "https://schema.org",
		"@type" => "Product",
		"name" => $LdName,
		"url" => $SchemaUrl,
		"sku" => trim((string)$aRes['AKey']),
		"brand" => Array(
			"@type" => "Brand",
			"name" => trim((string)$aRes['Brand'])
		),
		"offers" => Array(
			"@type" => "Offer",
			"url" => $SchemaUrl,
			"priceCurrency" => $SchemaCurrency,
			"price" => $SchemaPrice,
			"availability" => "https://schema.org/".preg_replace('/[^A-Za-z]/','',$SchemaAvailability),
			"itemCondition" => "https://schema.org/NewCondition",
			"seller" => Array(
				"@type" => "Organization",
				"name" => trim((string)$_SERVER['SERVER_NAME'])
			)
		)
	);

	if(trim((string)$aRes['ArtNum'])!=''){
		$SchemaData['mpn'] = trim((string)$aRes['ArtNum']);
	}

	$aSchemaImages = Array();
	if(!empty($aRes['IMAGES']) AND is_array($aRes['IMAGES'])){
		foreach($aRes['IMAGES'] as $aImage){
			$SchemaImage = trim((string)$aImage['Src']);
			if($SchemaImage==''){continue;}
			if(!preg_match('~^https?://~i',$SchemaImage)){
				if(substr($SchemaImage,0,1)!='/'){$SchemaImage = '/'.$SchemaImage;}
				$SchemaImage = PROTOCOL_DOMAIN_x.$SchemaImage;
			}
			$aSchemaImages[$SchemaImage] = $SchemaImage;
		}
	}
	if(!count($aSchemaImages)){
		$SchemaImage = trim((string)META_IMAGE_SRC);
		if($SchemaImage!=''){
			if(!preg_match('~^https?://~i',$SchemaImage)){
				if(substr($SchemaImage,0,1)!='/'){$SchemaImage = '/'.$SchemaImage;}
				$SchemaImage = PROTOCOL_DOMAIN_x.$SchemaImage;
			}
			$aSchemaImages[$SchemaImage] = $SchemaImage;
		}
	}
	if(count($aSchemaImages)==1){
		$SchemaData['image'] = array_shift($aSchemaImages);
	}elseif(count($aSchemaImages)>1){
		$SchemaData['image'] = array_values($aSchemaImages);
	}

	$SchemaDescription = trim(strip_tags((string)$aRes['Descriptions']));
	if($SchemaDescription!=''){
		$SchemaData['description'] = $SchemaDescription;
	}

	if(!empty($aRes['EANS']) AND is_array($aRes['EANS'])){
		foreach($aRes['EANS'] as $Ean){
			$SchemaGTIN = preg_replace('/\D+/','',(string)$Ean);
			if($SchemaGTIN!=''){
				if(strlen($SchemaGTIN)==13){
					$SchemaData['gtin13'] = $SchemaGTIN;
				}elseif(strlen($SchemaGTIN)==14){
					$SchemaData['gtin14'] = $SchemaGTIN;
				}else{
					$SchemaData['gtin'] = $SchemaGTIN;
				}
				break;
			}
		}
	}

	if(!empty($aRes['PROPERTIES']) AND is_array($aRes['PROPERTIES'])){
		$aAdditionalProperty = Array();
		foreach($aRes['PROPERTIES'] as $Name=>$Value){
			$PropName = trim(strip_tags(str_replace(':','',$Name)));
			$PropValue = trim(strip_tags($Value));
			if($PropName=='' OR $PropValue==''){continue;}
			$aAdditionalProperty[] = Array(
				"@type" => "PropertyValue",
				"name" => $PropName,
				"value" => $PropValue
			);
		}
		if(count($aAdditionalProperty)>0){
			$SchemaData['additionalProperty'] = $aAdditionalProperty;
		}
	}
	?>
	<script type="application/ld+json">
<?=json_encode($SchemaData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)?>
	</script>
	<?
}
