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

	// TODO: enable two or three level menu rendering
	private function mainMenu() {
		$tpl = new Template(_TEMPLATES_DIR . "/main_menu.tpl");

		foreach ($GLOBALS["pages"][$this->lang] as $url => $page) {
			$item = new Template(_TEMPLATES_DIR . "/menu_row.tpl");
			$prefix = ((_DEF_LANG !== $this->lang) ? "/" . $this->lang : "");

			$item->setValues(array(
				"url" => $prefix . "/" . $url,
				"caption" => $page["caption"],
				"active" => ($url === $this->path["page"]) ? "active" : "",
			));

			$items[] = $item;
		}

		$tpl->set("content", Template::merge($items));
		return $tpl->output();
	}

	private function footerMenu() {
		$tpl = new Template(_TEMPLATES_DIR . "/footer_menu.tpl");

		foreach ($GLOBALS["pages"][$this->lang] as $url => $page) {
			$item = new Template(_TEMPLATES_DIR . "/menu_row.tpl");
			$prefix = ((_DEF_LANG !== $this->lang) ? "/" . $this->lang : "");

			$item->setValues(array(
				"url" => $prefix . "/" . $url,
				"caption" => $page["caption"],
				"active" => ($url === $this->path["page"]) ? "active" : "",
			));

			$items[] = $item;
		}

		$tpl->set("content", Template::merge($items));
		return $tpl->output();
	}

	// TODO: render perhaps second or third level or supply custom links via array
	private function sidebarMenu() {

	}

	// TODO: render breadcrumbs path
	private function breadcrumbs() {

	}

	// TODO: render language switcher (if more langs are available)
	private function langSwitch() {

	}

	// TODO: render website header
	private function header() {

	}

	// TODO: render website footer
	private function footer() {

	}

	// TODO: render website content
	private function content() {

	}

	// TODO: render website sidebar
	private function sidebar() {

	}

	// TODO: render whole page layout! yeah! ^^
	public function render() {
		echo $this->mainMenu();
		echo $this->footerMenu();
	}
}
