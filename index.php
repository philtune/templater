<?php

if(isset($_GET) && isset($_GET['url'])) {
	$url = $_GET['url'];

	require_once 'app.php';
	Page::$regex = [
		'vars'           => RE('<% VARS = ({ .*? }) %>'),
		'blocks'         => RE('<% (@[\w-]+) = %> (.*?) <% end; %>'),
		'macro'          => RE("<%= \S+ %>")
	];
	Page::$resources_templ_arr = [
		'stylesheets'    => '<link rel="stylesheet" href="~">',
		'bottom_scripts' => '<script src="~"></script>',
		'favicon'        => '<link rel="shortcut icon" href="~" type="image/x-icon"><link rel="icon" href="~" type="image/x-icon">',
		'html5_ie8'      => '<!--[if lt IE 9]><script>var e="~".split(","),i=e.length;while(i--){document.createElement(e[i])};</script><![endif]-->'
	];

	$page = new Page($url);

	$page->render();

}

function RE($re) {
	/* return a properly formatted RegEx string */
	$re = str_replace(" ","\s*",$re);
	$re = str_replace(".","[\s\S]",$re);
	$re = "/$re/";
	return $re;
}
