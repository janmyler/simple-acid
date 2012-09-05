<?php
/**
 * Simple-AcID configuration file.
 *
 * @author      Jan Myler <honza.myler@gmail.com>
 * @copyright   Copyright 2012, Jan Myler (http://janmyler.com)
 *
 */

// debug on/off
define("_DEBUG", true);

// folders config
define("_PATH", $_SERVER['DOCUMENT_ROOT'] . "/");
define("_TEMPLATES_DIR", "templates/layout");
define("_PAGES_DIR", "templates/content");

// global meta tags config
define("_AUTHOR", "Jan Myler");
define("_DESCRIPTION", "");
define("_KEYWORDS", "");
define("_TITLE", "Simple-AcID");

// Google Analytics UA code
define("_GA", "");

// enable Old IE BrowseHappy?
define("_OLD_IE", false);

// webpage default language
define("_DEF_LANG", "en");

// webpage supported languages
$langs = array("cz", "en");

// language codes
$langCode = array(
	"cz" => "cs-cz",
	"en" => "en-us",
);

// pages config
$pages = array(	// TODO: second a third level subpages?
	"cz" => array(
		"" => array(
			"caption"     => "Úvod",
			"title"       => "Úvod",
			"description" => "",
			"keywords"    => "",
			"template"    => "uvod.tpl",
		),
		"o-nas" => array(
			"caption"     => "O nás",
			"title"       => "O nás",
			"description" => "",
			"keywords"    => "",
			"template"    => "o_nas.tpl",
		),
	),
	"en" => array(
		"" => array(
			"caption"     => "Home",
			"title"       => "Home",
			"description" => "",
			"keywords"    => "",
			"template"    => "home.tpl",
		),
		"about-us" => array(
			"caption"     => "About Us",
			"title"       => "About Us",
			"description" => "",
			"keywords"    => "",
			"template"    => "about_us.tpl",
		),
	),
);

// custom messages config
$msg = array(
	"TEMPLATE_OPEN_ERR" => array(
		"cz" => "Nepodařilo se otevřít šablonu.",
		"en" => "Failed to open a template file.",
	),
	"TEMPLATE_TYPE_ERR" => array(
		"cz" => "Očekáván typ Template.",
		"en" => "Expected type Template.",
	),

);

// outputs messages in particular language
function _t($code) {
	return (array_key_exists($code, $GLOBALS["msg"])) ? $msg[$code][$web->getLang] : "";
}
