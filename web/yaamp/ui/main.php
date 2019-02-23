<?php

require('misc.php');
echo <<<END

<!doctype html>
<!--[if IE 7 ]>		 <html class="no-js ie ie7 lte7 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 8 ]>		 <html class="no-js ie ie8 lte8 lte9" lang="en-US"> <![endif]-->
<!--[if IE 9 ]>		 <html class="no-js ie ie9 lte9>" lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->

<head>

<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<meta name="description" content="thepool.life for all your crypto mining needs">
<meta name="keywords" content="thepool,life,anonymous,mining,pool,maxcoin,bitcoin,altcoin,auto,switch,exchange,profit,decred,scrypt,x11,x13,x14,x15,lbry,lyra2re,neoscrypt,sha256,quark,skein2">

END;

$pageTitle = empty($this->pageTitle) ? YAAMP_SITE_NAME : YAAMP_SITE_NAME." - ".$this->pageTitle;

echo '<title>'.$pageTitle.'</title>';

echo CHtml::cssFile("/extensions/jquery/themes/ui-lightness/jquery-ui.css");
echo CHtml::cssFile('/yaamp/ui/css/main.css');
echo CHtml::cssFile('/yaamp/ui/css/table.css');


$cs = app()->getClientScript();
$cs->registerCoreScript('jquery.ui');

echo CHtml::scriptFile('/yaamp/ui/js/jquery.tablesorter.js');

if(!controller()->admin)
echo <<<end
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-31809012-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-31809012-2');
</script>

end;

echo "</head>";

///////////////////////////////////////////////////////////////

echo '<body class="page">';
echo '<a href="/site/mainbtc" style="display: none;">main</a>';

showPageHeader();
showPageContent($content);
showPageFooter();

echo "</body></html>";
return;

/////////////////////////////////////////////////////////////////////

function showItemHeader($selected, $url, $name)
{
	if($selected) $selected_text = "class='selected'";
	else $selected_text = '';

	echo "<span><a $selected_text href='$url'>$name</a></span>";
	echo "&nbsp;";
}

function showPageHeader()
{
	echo '<div class="tabmenu-out">';
	echo '<div class="tabmenu-inner">';

	echo '&nbsp;&nbsp;<a href="/">'.YAAMP_SITE_NAME.'</a>';

	$action = controller()->action->id;
	$wallet = user()->getState('yaamp-wallet');
	$ad = isset($_GET['address']);

	showItemHeader(controller()->id=='site' && $action=='index' && !$ad, '/', 'Home');
	showItemHeader($action=='mining', '/site/mining', 'Pool');
	showItemHeader(controller()->id=='site'&&($action=='index' || $action=='wallet') && $ad, "/?address=$wallet", 'Wallet');
	showItemHeader(controller()->id=='stats', '/stats', 'Graphs');
	showItemHeader($action=='miners', '/site/miners', 'Miners');
	if (YIIMP_PUBLIC_EXPLORER)
		showItemHeader(controller()->id=='explorer', '/explorer', 'Explorers');

	if (YIIMP_PUBLIC_BENCHMARK)
		showItemHeader(controller()->id=='bench', '/bench', 'Benchs');

	if (YAAMP_RENTAL)
		showItemHeader(controller()->id=='renting', '/renting', 'Rental');

	if(controller()->admin)
	{
		if (isAdminIP($_SERVER['REMOTE_ADDR']) === false)
			debuglog("admin {$_SERVER['REMOTE_ADDR']}");

		showItemHeader(controller()->id=='coin', '/coin', 'Coins');
		showItemHeader($action=='common', '/site/common', 'Dashboard');
		showItemHeader(controller()->id=='site'&&$action=='admin', "/site/admin", 'Wallets');

		if (YAAMP_RENTAL)
			showItemHeader(controller()->id=='renting' && $action=='admin', '/renting/admin', 'Jobs');

		if (YAAMP_ALLOW_EXCHANGE)
			showItemHeader(controller()->id=='trading', '/trading', 'Trading');

		if (YAAMP_USE_NICEHASH_API)
			showItemHeader(controller()->id=='nicehash', '/nicehash', 'Nicehash');
	}

	echo '<span style="float: right;">';

	$mining = getdbosql('db_mining');
	$nextpayment = date('m.d.y H:i T', $mining->last_payout+YAAMP_PAYMENTS_FREQ);
	$btc_nextpayment = date('m.d.y H:i T', $mining->last_payout+YAAMP_BTC_PAYMENTS_FREQ);

	echo '<span style="font-size: .8em;">Next Coin Payout: '.$nextpayment.' | Next BTC Payout: '.$btc_nextpayment.' | Server Time: '.date('m.d.y H:i T', time()).' </span>';

	echo "</div>";
	echo "</div>";

}

function showPageFooter()
{
	echo '<div class="footer">';
	$year = date("Y", time());

	echo "<p>&copy; $year ".YAAMP_SITE_NAME.' - '.
		'<a href="https://github.com/cryptopool-builders/Multi-Pool-Installer">Built with Ultimate Crypto-Server Setup Installer</a></p>';

	echo '</div><!-- footer -->';
}
