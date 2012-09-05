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

	public function getPathTo($endKey = "") {
		$path = "/" . ((_DEF_LANG !== $this->lang) ? "/" . $this->lang : "");

		foreach ($this->path as $key => $val) {
			$path .= "/" . $val;
			if ($key === $endKey ) {
				break;
			}
		}

		return $path;
	}

	public function getLang() {
		return $this->lang;
	}

	private function getPageHash($page = null) {
		if (is_null($page)) {
			$page = $this->path["page"];
		}

		return $GLOBALS["pages"][$this->lang][$page]["hash"];
	}

	private function getPageCaption($page) {
		return $GLOBALS["pages"][$this->lang][$page]["caption"];
	}

	private function hashToPage($hash, $lang) {
		// lang and hash must be defined
		if (empty($lang) || empty($hash)) {
			return "";
		}

		foreach ($GLOBALS["pages"][$lang] as $url => $page) {
			if ($page["hash"] === $hash) {
				return $url;
			}
		}

		// return 'homepage' link if no url was found
		return "";
	}

	// TODO: enable two or three level menu rendering
	private function mainMenu() {
		$tpl = new Template(_TEMPLATES_DIR . "/main_menu.tpl");

		foreach ($GLOBALS["pages"][$this->lang] as $url => $page) {
			$item = new Template(_TEMPLATES_DIR . "/menu_item.tpl");
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
			$item = new Template(_TEMPLATES_DIR . "/menu_item.tpl");
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
				$item = new Template(_TEMPLATES_DIR . "/menu_item.tpl");

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
	private function breadcrumbs($sep = "::") {
		$tpl = new Template(_TEMPLATES_DIR . "/breadcrumbs.tpl");
		$content = "";
		$offsetKey = "";

		// find last non empty path item
		foreach ($this->path as $key => $pth) {
			if ($key === "page" || !empty($pth)) {
				$offsetKey = $key;
			} else {
				break;
			}
		}

		// build breadcrumbs
		foreach ($this->path as $key => $pth) {
			if ($key !== $offsetKey) {
				$item = new Template(_TEMPLATES_DIR . "/breadcrumbs_item.tpl");
				$item->setValues(array(
					"url" => $this->getPathTo($key),
					"caption" => $this->getPageCaption($pth),
				));

				$content .= $item->output() . " " . $sep . " ";
			} else {
				$content .= $this->getPageCaption($pth);
				break;
			}
		}

		$tpl->set("content", $content);
		return $tpl->output();
	}

	// TODO: render language switcher (if more langs are available)
	private function langSwitch() {
		$tpl = new Template(_TEMPLATES_DIR . "/lang_switcher.tpl");

		// TODO: da se nejak udelat zachovani #anchor casti adresy? asi ne co? -____-

		foreach ($GLOBALS["langs"] as $langCode => $langInfo) {
			$item = new Template(_TEMPLATES_DIR . "/menu_item.tpl");

			// get proper language prefix
			$url = ((_DEF_LANG !== $langCode) ? "/" . $langCode : "");

			// get proper language page code
			$hash = $this->getPageHash();
			$url .= "/" . $this->hashToPage($hash, $langCode);
			$url .= (!empty($this->path["param1"])) ? "/" . $this->path["param1"] : "";
			$url .= (!empty($this->path["param2"])) ? "/" . $this->path["param2"] : "";

			$item->setValues(array(
				"url" => $url,
				"caption" => $langInfo["title"],
				"active" => ($langCode === $this->lang) ? "active" : "",
			));

			$items[] = $item;
		}

		$tpl->set("content", Template::merge($items));
		return $tpl->output();
	}

	private function header() {
		$tpl = new Template(_TEMPLATES_DIR . "/web_header.tpl");

		$tpl->set('title', _TITLE);
		$tpl->set('caption', _TITLE);

		return $tpl->output();
	}

	private function footer() {
		$tpl = new Template(_TEMPLATES_DIR . "/web_footer.tpl");

		return $tpl->output();
	}

	// TODO: render website content
	private function content() {

	}

	// TODO: render website sidebar
	private function sidebar() {
		$tpl = new Template(_TEMPLATES_DIR . "/web_sidebar.tpl");

		// code may be changes as needed
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
		$tpl->set('menu', $this->sidebarMenu(0, $custom, "products-subnav"));

		return $tpl->output();
	}

	// TODO: render whole page layout! yeah! ^^
	public function render() {

		echo $this->mainMenu();
		echo $this->langSwitch();
		// echo $this->sidebar();
		// echo $this->footerMenu();
		echo $this->breadcrumbs();

	}
}
