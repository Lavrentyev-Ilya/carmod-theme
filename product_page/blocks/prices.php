<?php
VerifyAccess_x('ProductPage_prices.templ');

if(!function_exists('PrintPrices_x')){
    $CmPricesBlockAutoRun = false;
    require_once dirname(dirname(__DIR__)).'/prices_block/template.php';
}

PrintPrices_x(array(
    'CONTEXT' => 'auto'
));
