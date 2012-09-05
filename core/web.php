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


	/* array(
	 *	 'lang' => array('url' => array('class' => "", "caption" => ""),),
	 * );
	 */
	private function sidebarMenu($level = 2, $custom = null, $id = "") {
		$tpl = new Template(_TEMPLATES_DIR . "/side_submenu.tpl");

		// set custom id
		if (!empty($id)) {
			$tpl->set('id', $id);
		}

		// build custom menu
		if (is_array($custom)) {
			foreach ($custom[$this->lang] as $url => $page) {
				$item = new Template(_TEMPLATES_DIR . "/menu_row.tpl");

				$item->setValues(array(
					"url" => $url,
					"caption" => $page["caption"],
					"active" => $page["class"],
				));

				$items[] = $item;
			}

			$tpl->set("content", Template::merge($items));
			return $tpl->output();
		}

		// build normal submenu
		// TODO: build normal submenu

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
		$custom = array(
			"en" => array(
				"#cabinets" => array(
					"caption" => "Cabinets",
					"class" => "active",
				),
				"#countertops" => array(
					"caption" => "Countertops",
					"class" => "",
				),
				"#fixtures" => array(
					"caption" => "Fixtures",
					"class" => "",
				),
				"#tile" => array(
					"caption" => "Tile",
					"class" => "",
				),
			),
			"cz" => array(
				"#skrine" => array(
					"caption" => "Skříně",
					"class" => "active",
				),
				"#policky" => array(
					"caption" => "Poličky",
					"class" => "",
				),
				"#kdovico" => array(
					"caption" => "Kdovíco",
					"class" => "",
				),
				"#nevim" => array(
					"caption" => "Nevím",
					"class" => "",
				),
			),
		);
		echo $this->sidebarMenu(0, $custom, "products-subnav");
		echo $this->footerMenu();
	}
}
