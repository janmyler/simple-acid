<?php
/**
 * Simple-AcID configuration file.
 *
 * @author      Jan Myler <info@janmyler.com>
 * @copyright   Copyright 2012, Jan Myler (http://janmyler.com)
 *
 */

// debug on/off
define("_DEBUG", true);

// max execution time limit (mainly for gallery purposes)
define("_MAX_TIME_LIMIT", 60 * 3);

// folders config
define("_PATH", 'http://' . $_SERVER['HTTP_HOST'] . "/");
define("_TEMPLATES_DIR", "templates/layout");
define("_PAGES_DIR", "templates/content");
define("_GALLERY_DIR", "photos/");

// global meta tags config
define("_AUTHOR", "Jan Myler");
define("_DESCRIPTION", "");
define("_KEYWORDS", "");
define("_TITLE", "Simple-AcID");

// web specific defines
define("_EMAIL", "info@janmyler.com");

// Google Analytics UA code
define("_GA", "");

// enable Old IE BrowseHappy?
define("_OLD_IE", false);

// webpage default language
define("_DEF_LANG", "en");

// webpage supported languages
$langs = array(
	"cz" => array(
		"title" => "Česky",
		"code" => "cs-cz",
	),
	"en" => array(
		"title" => "English",
		"code" => "en-us",
	),
);

// security salt (64 characters long)
define("_SSALT", "dUX6T=V*db@G%8%DLQS)qUmdfKj9J@+!2yM7_jf-@vHK@83c5-tXucgnE)@Q+FA*");

// DB settings
define("_DB_SERVER", "localhost");
define("_DB_DTB", "");
define("_DB_LOGIN", "");
define("_DB_PASS", "");

// gallery images sizes and configs
define("_THUMB_NAME", "thumb.jpg");
define("_THUMB_SIZE_W", 150);
define("_THUMB_SIZE_H", 150);

$sizes = array(
	'_f' => array(
		'w' => 1280,
		'h' => 1024,
	),
	'_n' => array(
		'w' => 212,
		'h' => 212,
	),
	'_s' => array(
		'w' => 100,
		'h' => 100,
	),
	'_t' => array(
		'w' => 80,
		'h' => 80,
	),
);

// pages config
$pages = array(	// TODO: second a third level subpages?
	"cz" => array(
		"" => array(
			"caption" => "Úvod",
			"title" => "Úvod",
			"description" => "",
			"keywords" => "",
			"template" => "uvod.tpl",
			"hash" => ":home",
		),
		"o-nas" => array(
			"caption" => "O nás",
			"title" => "O nás",
			"description" => "",
			"keywords" => "",
			"template" => "o_nas.tpl",
			"hash" => ":about-us",
			"sub" => array(
				"adresa" => array(
					"caption" => "Adresa",
					"title" => "Adresa",
					"description" => "",
					"keywords" => "",
					"template" => "adresa.tpl",
					"hash" => ":about-us:address",
				),
				"mapa" => array(
					"caption" => "Mapa",
					"title" => "Mapa",
					"description" => "",
					"keywords" => "",
					"template" => "mapa.tpl",
					"hash" => ":about-us:map",
				),
			),
		),
		"kontakt" => array(
			"caption" => "Kontakt",
			"title" => "Kontakt",
			"description" => "",
			"keywords" => "",
			"template" => "kontakt.tpl",
			"hash" => ":contact-us",
		),
	),
	"en" => array(
		"" => array(
			"caption" => "Home",
			"title" => "Home",
			"description" => "",
			"keywords" => "",
			"template" => "home.tpl",
			"hash" => ":home",
		),
		"about-us" => array(
			"caption" => "About Us",
			"title" => "About Us",
			"description" => "",
			"keywords" => "",
			"template" => "about_us.tpl",
			"hash" => ":about-us",
			"sub" => array(
				"address" => array(
					"caption" => "Address",
					"title" => "Address",
					"description" => "",
					"keywords" => "",
					"template" => "address.tpl",
					"hash" => ":about-us:address",
					"sub" => array(
						"test" => array(
							"caption" => "Test",
							"title" => "Test",
							"description" => "",
							"keywords" => "",
							"template" => "test.tpl",
							"hash" => ":about-us:address:test",
						),
					),
				),
				"map" => array(
					"caption" => "Map",
					"title" => "Map",
					"description" => "",
					"keywords" => "",
					"template" => "map.tpl",
					"hash" => ":about-us:map",
				),
			),
		),
		"contact-us" => array(
			"caption" => "Contact Us",
			"title" => "Contact Us",
			"description" => "",
			"keywords" => "",
			"template" => "contact_us.tpl",
			"hash" => ":contact-us",
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
	"GALLERY_NO_PHOTOS" => array(
		"cz" => "Galerie neobsahuje žádné fotky.",
		"en" => "This gallery is empty.",
	),
	'GALLERY_NO_ROOTDIR' => array(
		"cz" => "Složka galerie neexistuje.",
		"en" => "Gallery root directory does not exist.",
	),
	'FORM_UNKNOWN_VALIDATION' => array(
		"cz" => "Neznámé pravidlo validace.",
		"en" => "Unknow validation rule.",
	),
	'EMAIL_SEND_ERROR' => array(
		"cz" => "Jejda, došlo k chybě. Vaše zpráva nebyla odeslána. Zkuste to prosím znovu.",
		"en" => "Oops! An error occured. Your message has not been sent. Please, try it again.",
	),
	'EMAIL_SEND_OK' => array(
		"cz" => "Vaše zpráva byla odeslána, děkujeme.",
		"en" => "Your message has been sent. Thank you!",
	),
);

// outputs messages in particular language
function _t($code) {
	$web = Web::getInstance();
	$msg = $GLOBALS['msg'];

	return (array_key_exists($code, $msg)) ? $msg[$code][$web->getLang()] : "";
}
