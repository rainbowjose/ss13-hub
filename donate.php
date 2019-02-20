<?php define("INDEX", "yes"); include 'api/main.php'  ?>
<!DOCTYPE html>
<html>

<head>
    <title><?php $api->sitename() ?>Online</title>
	<?php $api->bootstrap() ?>
</head>

<body translate="no">
	<?php $api->navbar() ?>
    <div class="index-page">
        <?php
        $m_shop = '708147517';
        $m_orderid = '1';
        $m_amount = number_format(100, 2, '.', '');
        $m_curr = 'RUB';
        $m_desc = base64_encode('Test');
        $m_key = 'sex';

        $arHash = array(
        	$m_shop,
        	$m_orderid,
        	$m_amount,
        	$m_curr,
        	$m_desc
        );

        /*
        $arParams = array(
        	'success_url' => 'http://frosty.space/new_success_url',
        	//'fail_url' => 'http://frosty.space/new_fail_url',
        	//'status_url' => 'http://frosty.space/new_status_url',
        	'reference' => array(
        		'var1' => '1',
        		//'var2' => '2',
        		//'var3' => '3',
        		//'var4' => '4',
        		//'var5' => '5',
        	),
        );

        $key = md5('Ключ для шифрования дополнительных параметров'.$m_orderid);

        $m_params = urlencode(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, json_encode($arParams), MCRYPT_MODE_ECB)));

        $arHash[] = $m_params;
        */

        $arHash[] = $m_key;

        $sign = strtoupper(hash('sha256', implode(':', $arHash)));
        ?>
        <form method="post" action="https://payeer.com/merchant/?lang=ru">
        <input name="ckey" value="">
        <input type="hidden" name="m_shop" value="<?=$m_shop?>">
        <input type="hidden" name="m_orderid" value="<?=$m_orderid?>">
        <input name="m_amount" value="<?=$m_amount?>">
        <input type="hidden" name="m_curr" value="<?=$m_curr?>">
        <input type="hidden" name="m_desc" value="<?=$m_desc?>">
        <input type="hidden" name="m_sign" value="<?=$sign?>">
        <?php /*
        <input type="hidden" name="form[ps]" value="2609">
        <input type="hidden" name="form[curr[2609]]" value="USD">
        */ ?>
        <?php /*
        <input type="hidden" name="m_params" value="<?=$m_params?>">
        */ ?>
        <input type="submit" name="m_process" value="send" />
        </form>
    </div>
	<?php $api->footer() ?>
	<?php $api->styles() ?>
</body>

</html>
