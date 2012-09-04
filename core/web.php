<?php
/**
 * Web Class file.
 *
 * Server requested pages.
 *
 * @author      Jan Myler <honza.myler@gmail.com>
 * @copyright   Copyright 2012, Jan Myler (http://janmyler.com)
 *
 */

require_once("core/template.php");

class Web {
	private static $instance;
	private $path;
	private $err;
	private $lang;

	private function __construct() {
		$this->path = array(
			"page"   => "",
			"param1" => "",
			"param2" => "",
		);

		$this->lang = _DEF_LANG;
	}

	public static function getInstance() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function setPath($path) {
		if (is_array($path)) {
			$this->path = $path;
		}
	}

	public function setError($err) {
		$this->err = $err;
	}

	public function setLang($lang) {
		$this->lang = $lang;
	}

	public function getPath($key = "") {
		if (!empty($key) && array_key_exists($key, $this->path)) {
			return $this->path[$key];
		} elseif (!empty($key)) {
			return "";
		}

		return $this->path;
	}

	public function getLang() {
		return $this->lang;
	}

	private function mainMenu() {
		$tpl = new Template(_TEMPLATES_DIR . "/menu.tpl");
	}

	private function footerMenu() {

	}

	private function sidebarMenu() {

	}

	private function breadcrumbs() {

	}

	private function header() {

	}

	private function footer() {

	}

	private function content() {

	}

	private function sidebar() {

	}

	public function render() {


	}


/*
// menu items creation
foreach ($pages as $page) {
	$menuItem = new Template(_PATH . _TEMPLATES_DIR . "/menu_row.tpl");

	// nastaveni zvyrazneni aktivni stranky v menu
	if ($reqPage == $page["url"]) {
		$page["active"] = "active";
		$requiredPage = $page;  // uchovani informaci pro pozadovanou stranku
	}

	// nahrazeni tagu v sablone
	foreach ($page as $key => $value) {
		$menuItem->set($key, $value);
	}

	$menuItems[] = $menuItem;
}

 // vytvoreni hlavniho menu
$mainMenu = new Template(_PATH . _TEMPLATES_DIR . "/menu.tpl");
$mainMenu->set("content", Template::merge($menuItems));

 // vytvoreni spodniho menu
$bottomMenu = new Template(_PATH . _TEMPLATES_DIR . "/bottom_menu.tpl");
$bottomMenu->set("content", Template::merge($menuItems));

 // nacteni sablony obsahu stranky
if ($requiredPage) {
	$pageContent = new Template(_PATH . _PAGES_DIR . "/" . $requiredPage["template"]);
}
else {
	$pageContent = new Template(_PATH . _PAGES_DIR . $reqPage);
}

 // nacteni a nastaveni layoutu stranky
$pageLayout = new Template(_PATH . _TEMPLATES_DIR . "/layout.tpl");
$pageLayout->set("author", _AUTHOR);
$pageLayout->set("description", (empty($requiredPage["description"])) ? _DESCRIPTION : $requiredPage["description"]);
$pageLayout->set("keywords", (empty($requiredPage["keywords"])) ? _KEYWORDS : $requiredPage["keywords"]);
$pageLayout->set("title", (empty($requiredPage["title"])) ? _TITLE : $requiredPage["title"] . " – " . _TITLE);
$pageLayout->set("menu", $mainMenu->output());
$pageLayout->set("bottom-menu",$bottomMenu->output());

/* zpracovani kontaktniho formulare
if ($requiredPage["url"] == "napiste-nam") {
	// validace na strane serveru pri odeslani formulare
	if (isset($_POST["send"])) {
		$messages[] = null;
		$err = false;

		if (!validateName($_POST["name"])) {
			$msg = new Template(_PATH . _TEMPLATES_DIR . "/validation_row.tpl");
			$msg->set("message", "Neplatné jméno");
			$msg->set("description", "Vyplňte prosím Vaše jméno.");
			$messages[] = $msg;
			$err = true;
		}
		if (!validateSurname($_POST["surname"])) {
			$msg = new Template(_PATH . _TEMPLATES_DIR . "/validation_row.tpl");
			$msg->set("message", "Neplatné příjmení");
			$msg->set("description", "Vyplňte prosím Vaše příjmení.");
			$messages[] = $msg;
			$err = true;
		}
		if (!validateEmail($_POST["email"])) {
			$msg = new Template(_PATH . _TEMPLATES_DIR . "/validation_row.tpl");
			$msg->set("message", "Neplatný e-mail");
			$msg->set("description", "Zadejte validní e-mailovou adresu.");
			$messages[] = $msg;
			$err = true;
		}
		if (!validateMessage($_POST["message"])) {
			$msg = new Template(_PATH . _TEMPLATES_DIR . "/validation_row.tpl");
			$msg->set("message", "Neplatný text zprávy");
			$msg->set("description", "Text Vaší zprávy musí být delší než 10 znaků.");
			$messages[] = $msg;
			$err = true;
		}

		if ($err) {  // nastaveni sablony chyboveho vypisu
			$validationMsg = new Template(_PATH . _TEMPLATES_DIR . "/validation_err.tpl");
			$validationMsg->set("content", Template::merge($messages));
		}
		else {  // nastaveni sablony pro uspech zpracovani zpravy
			$msg = new Template(_PATH . _TEMPLATES_DIR . "/validation_row.tpl");
			$msg->set("message", "Děkujeme!");
			$msg->set("description", "Vaše zpráva byla úspěšně odeslána.");

			$validationMsg = new Template(_PATH . _TEMPLATES_DIR . "/validation_succ.tpl");
			$validationMsg->set("content", $msg->output());
		}

		// predani vypisu do sablony obsahu stranky
		$pageLayout->set("validation", $validationMsg->output());
	}
	else {  // pokud zatim nebyl odeslan formular – nejsou vypisovany informace o zpracovani
		$pageLayout->set("validation", "");
	}
}
else {  // obsah stranky pro jine stranky nez kontaktni formular
	$pageLayout->set("content", $pageContent->output());
}

// zobrazeni stranky na vystup
echo $pageLayout->output();*/
}
