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
		$path = ((_DEF_LANG !== $this->lang) ? "/" . $this->lang : "");

		foreach ($this->path as $key => $val) {
			if (empty($val)) {
				break;
			}

			$path .= "/" . $val;

			if ($key === $endKey) {
				break;
			}
		}

		return $path;
	}

	public function getLang() {
		return $this->lang;
	}

	public function pageExists() {
		$page = $GLOBALS["pages"][$this->lang];

		foreach ($this->path as $key => $val) {
			if (($key === "page" || !empty($val)) && !isset($page[$val])) {
				return false;
			}

			if (isset($page[$val]["sub"])) {
				$page = $page[$val]["sub"];
			} else {
				$page = array();
			}
		}

		return true;
	}

	private function getPageHash($path = null) {
		if (is_null($path)) {
			$path = $this->getPathTo();
		}

		$offset = (_DEF_LANG !== $this->lang) ? strpos($path, '/', 1) : 0;
		if (!$offset) {
			$offset = strlen($path);
		} else {
			$offset += 1;
		}

		$path = substr($path, $offset);
		$path = explode('/', $path);

		$page = null;
		foreach ($path as $pth) {
			if (is_null($page)) {
				$page = $GLOBALS["pages"][$this->lang][$pth];
			} else {
				$page = $page["sub"][$pth];
			}
		}

		return $page["hash"];
	}

	private function getPageInfo($path = null) {
		if (is_null($path)) {
			$path = $this->getPathTo();
		}

		$offset = (_DEF_LANG !== $this->lang) ? strpos($path, '/', 1) + 1 : 1;
		$path = substr($path, $offset);
		$path = explode('/', $path);

		$page = null;
		foreach ($path as $pth) {
			if (is_null($page)) {
				$page = $GLOBALS["pages"][$this->lang][$pth];
			} else {
				$page = $page["sub"][$pth];
			}
		}

		return array(
			"caption" => $page["caption"],
			"title" => $page["title"],
			"description" => $page["description"],
			"keywords" => $page["keywords"],
		);
	}

	private function getPageCaption($path = null) {
		$page = $this->getPageInfo($path);
		return $page["caption"];
	}

	private function hashToPage($hash, $lang) {
		// lang and hash must be defined
		if (empty($lang) || empty($hash)) {
			return "";
		}

		$path = "";
		$offset = strpos($hash, ":", 1);
		$offset = $offset ? $offset : strlen($hash);
		$pages = $GLOBALS["pages"][$lang];

		while (!empty($pages)) {
			$url = key($pages);
			$page = array_shift($pages);

			if ($page["hash"] === substr($hash, 0, $offset)) {
				$path .= "/" . $url;
				$pages = isset($page["sub"]) ? $page['sub'] : array();

				if ($offset === strlen($hash)) {
					break;
				} elseif ($offset < strlen($hash)) {
					$offset = strpos($hash, ":", ++$offset);
				}

				if (!$offset) {
					$offset = strlen($hash);
				}
			}
		}

		return $path;
	}

	private function mainMenu($maxLevel = 1, $showAll = false) {
		$tpl = new Template(_TEMPLATES_DIR . "/main_menu.tpl");
		$prefix = ((_DEF_LANG !== $this->lang) ? "/" . $this->lang : "");

		foreach ($GLOBALS["pages"][$this->lang] as $url1 => $page1) {
			$item1 = new Template(_TEMPLATES_DIR . "/menu_item.tpl");
			$level = 1;
			$tpl2 = null;

			if ($level < $maxLevel && isset($page1["sub"]) && is_array($page1["sub"])) {
				if ($showAll || $url1 === $this->path["page"]) {
					$tpl2 = new Template(_TEMPLATES_DIR . "/second_submenu.tpl");

					foreach ($page1["sub"] as $url2 => $page2) {
						$item2 = new Template(_TEMPLATES_DIR . "/menu_item.tpl");
						$level = 2;
						$tpl3 = null;

						if ($level < $maxLevel && isset($page2["sub"]) && is_array($page2["sub"])) {
							if ($showAll || $url2 === $this->path["param1"]) {
								$tpl3 = new Template(_TEMPLATES_DIR . "/third_submenu.tpl");

								foreach ($page2["sub"] as $url3 => $page3) {
									$item3 = new Template(_TEMPLATES_DIR . "/menu_item.tpl");
									$level = 3;

									$item3->setValues(array(
										"url" => $prefix . "/" . $url1 . "/" . $url2 . "/" . $url3,
										"caption" => $page3["caption"],
										"active" => ($url3 === $this->path["param2"]) ? "active" : "",
										"append" => "",
									));

									$items3[] = $item3;
								}

								$tpl3->set("content", Template::merge($items3));
							}
						}

						$item2->setValues(array(
							"url" => $prefix . "/" . $url1 . "/" . $url2,
							"caption" => $page2["caption"],
							"active" => ($url2 === $this->path["param1"]) ? "active" : "",
							"append" => (!is_null($tpl3)) ? $tpl3->output() : "",
						));

						$items2[] = $item2;
					}

					$tpl2->set("content", Template::merge($items2));
				}
			}

			$item1->setValues(array(
				"url" => $prefix . "/" . $url1,
				"caption" => $page1["caption"],
				"active" => ($url1 === $this->path["page"]) ? "active" : "",
				"append" => (!is_null($tpl2)) ? $tpl2->output() : "",
			));

			$items1[] = $item1;
		}

		$tpl->set("content", Template::merge($items1));
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
				"append" => "", // TODO: append
			));

			$items[] = $item;
		}

		$tpl->set("content", Template::merge($items));
		return $tpl->output();
	}

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
					"append" => "", // TODO: append
				));

				$items[] = $item;
			}

			$tpl->set("content", Template::merge($items));
			return $tpl->output();
		}

		// build normal submenu
		// TODO: build normal submenu

	}

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
			$path = $this->getPathTo($key);

			if ($key !== $offsetKey) {
				$item = new Template(_TEMPLATES_DIR . "/breadcrumbs_item.tpl");
				$item->setValues(array(
					"url" => $path,
					"caption" => $this->getPageCaption($path),
				));

				$content .= $item->output() . " " . $sep . " ";
			} else {
				$content .= $this->getPageCaption($path);
				break;
			}
		}

		$tpl->set("content", $content);
		return $tpl->output();
	}

	// FOXME: not working good
	private function langSwitch() {
		$tpl = new Template(_TEMPLATES_DIR . "/lang_switcher.tpl");

		// TODO: da se nejak udelat zachovani #anchor casti adresy? asi ne co? -____-

		// get proper hash
		$hash = $this->getPageHash();

		foreach ($GLOBALS["langs"] as $langCode => $langInfo) {
			$item = new Template(_TEMPLATES_DIR . "/menu_item.tpl");

			// get proper language prefix
			$url = ((_DEF_LANG !== $langCode) ? "/" . $langCode : "");

			$url .= $this->hashToPage($hash, $langCode);

			$item->setValues(array(
				"url" => $url,
				"caption" => $langInfo["title"],
				"active" => ($langCode === $this->lang) ? "active" : "",
				"append" => "",
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

		$tpl->set("bottom-menu", $this->footerMenu());

		return $tpl->output();
	}

	private function gaSnippet() {
		if (_GA !== "") {
			$tpl = new Template(_TEMPLATES_DIR . "/ga_snippet.tpl");
			$tpl->set("ua", _GA);

			return $tpl->output();
		} else {
			return "";
		}
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

	public function error() {
		return $this->err();
	}

	public function render() {
		// test for the errors
		if (!empty($this->err)) {
			return $this->error();
		} else {
			$tpl = new Template(_TEMPLATES_DIR . "/layout.tpl");
			$page = $this->getPageInfo();

			// set header values
			$tpl->setValues(array(
				'lang' => $GLOBALS['langs'][$this->lang]['code'],
				'title' => $page["title"] . ' | ' . _TITLE,
				'author' => _AUTHOR,
				'description' => (!empty($page["description"]) ? $page["description"] : _DESCRIPTION),
				'keywords' => (!empty($page["keywords"]) ? $page["keywords"] : _KEYWORDS),
				'analytics-snippet' => $this->gaSnippet(),
			));

			if (_OLD_IE) {
				$tpl->set("oldIE", '<!--[if lt IE 7]><p class="chromeframe">Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->');
			} else {
				$tpl->set("oldIE", "");
			}

			$tpl->setValues(array(
				"header" => $this->header(),
				"footer" => $this->footer(),
				// "content" =>

			));

			return $tpl->output();

			// echo $this->header();
			// echo $this->mainMenu(3, true);
			// echo $this->langSwitch();
			// echo $this->breadcrumbs();
			// echo $this->sidebar();
			// echo $this->footerMenu();
			// echo $this->footer();
		}
	}
}
