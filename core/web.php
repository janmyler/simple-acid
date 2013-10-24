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
require_once("core/gallery.php");

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
			"param3" => "",
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

	public function getPathTo($endKey = "", $firstSlash = true) {
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

		if (!$firstSlash) {
			$path = substr($path, 1);
		}

		return $path;
	}

	public function getLang() {
		return $this->lang;
	}

	public function pageExists() {
		if (!array_key_exists($this->lang, $GLOBALS["langs"])) {
			return false;
		}

		$page = $GLOBALS["pages"][$this->lang];

		// exception for the gallery pages
		if ($this->path['page'] === 'gallery') {
			if (!empty($this->path['param1']) && !isset($page["gallery"]["sub"][$this->path["param1"]])) {
				return false;
			}

			return true;
		}

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
		$offset += 1;

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

		// remove the language part of the path (if is set)
		if (array_key_exists($path[0], $GLOBALS['langs'])) {
			array_shift($path);
		}

		$page = null;
		foreach ($path as $pth) {
			if (is_null($page)) {
				$page = $GLOBALS["pages"][$this->lang][$pth];
			} else {
				// gallery pages exception
				if ($this->path['page'] === 'gallery' && $pth === $this->path['param2']) {
					$page = $page;
				} else {
					$page = $page["sub"][$pth];
				}
			}
		}

		$info = array(
			"caption" => $page["caption"],
			"title" => $page["title"],
			"description" => $page["description"],
			"keywords" => $page["keywords"],
			"template" => $page["template"],
		);

		if (isset($page["custom"])) {
			$info["custom"] = $page["custom"];
		}

		return $info;
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

			// skip hidden items
			if (isset($page1["hidden"]) && $page1["hidden"]) {
				continue;
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

			// skip hidden items
			if (isset($page["hidden"]) && $page["hidden"]) {
				continue;
			}

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
				$item = new Template('<li class="[@class]">&rsaquo; <a href="[@url]" class="[@active]">[@caption]</a>[@append]</li>', true);

				$item->setValues(array(
					"url" => $url,
					"caption" => $page["caption"],
					"active" => $page["class"],
					"class" => $page["class"],
					"append" => "", // TODO: append
				));

				$items[] = $item;
			}

			$tpl->set("content", Template::merge($items));
			return $tpl->output();
		}

		// build normal submenu
		// FIXME: does not work with the 'level' parameter (not even for multiple menu levels)
		$subpages = $GLOBALS["pages"][$this->lang][$this->path['page']]['sub'];
		foreach ($subpages as $url => $page) {
			$item = new Template('<li>&rsaquo; <a href="[@url]" class="[@active]">[@caption]</a>[@append]</li>', true);
			$prefix = ((_DEF_LANG !== $this->lang) ? "/" . $this->lang : "");

			$item->setValues(array(
				"url" => $prefix . "/" . $this->path['page'] . "/" . $url,
				"caption" => $page["caption"],
				"active" => ($url === $this->path["param1"]) ? "active" : "",
				"append" => "", // TODO: append
			));

			$items[] = $item;
		}

		$tpl->set("content", Template::merge($items));
		return $tpl->output();

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

	// FIXME: not working good
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

		// set copyright info
		$year = (int) date('Y');
		if ($year > 2013) {
			$copy = '2013&ndash;' . $year;
		} else {
			$copy = '2013';
		}
		$tpl->set('copy-years', $copy);

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

	private function indexContent() {
		$tpl = new Template(_TEMPLATES_DIR . "/web_index.tpl");

		$tpl->setValues(array(
			'slider' => $this->slider(),
			'sidebar' => $this->indexSidebar(),
		));

		return $tpl->output();
	}

	private function pageContent() {
		$tpl = new Template(_TEMPLATES_DIR . "/web_page.tpl");

		$tpl->set('sidebar', $this->pageSidebar());

		// Gallery page
		if ($this->path['page'] === 'gallery') {
			$gallery = new Gallery(_GALLERY_DIR, $GLOBALS['sizes']);
			$galTpl = new Template(_TEMPLATES_DIR . "/gallery_list.tpl");
			if (!empty($this->path['param1']) && empty($this->path['param2'])) {

				$galTpl->setValues(array(
					'caption' => $this->getPageCaption(),
					'chooser' => $gallery->getList($this->path['param1']),
				));
				$galTpl->setValuesEmpty();

				$tpl->set('content', $galTpl->output());
			} else if (!empty($this->path['param2'])) {
				$galTpl->setValues(array(
					'caption' => $this->getPageCaption(),
					'content' => $gallery->getGalleryName($this->path['param1'] . '/' . $this->path['param2']),
					'gallery' => $gallery->getGallery($this->path['param1'] . '/' . $this->path['param2']),
				));
				$galTpl->setValuesEmpty();

				// specific gallery title modification
				if (!is_null($layoutTpl)) {
					$page = $this->getPageInfo();
					$galName = $gallery->getGalleryName($this->path['param1'] . '/' . $this->path['param2'], false);
					$layoutTpl->set('title', $page["title"] . ' – ' . $galName . ' | ' . _TITLE);
				}

				$tpl->set('content', $galTpl->output());
			}
		}

		return $tpl->output();
	}

	private function indexSidebar() {
		$tpl = new Template(_TEMPLATES_DIR . "/web_index_sidebar.tpl");

		return $tpl->output();
	}

	private function pageSidebar() {
		$tpl = new Template(_TEMPLATES_DIR . "/web_page_sidebar.tpl");

		// insert custom menu if defined
		$page = $this->getPageInfo();

		if (isset($page['custom'])) {
			$tpl->set('menu', $this->sidebarMenu(0, $page['custom'], "products-subnav"));
		} else if ($this->path['page'] === 'gallery') {
			$tpl->set('menu', $this->sidebarMenu(0, null, "gallery-subnav"));
		} else {
			$tpl->set('menu', '');
		}

		return $tpl->output();
	}

	public function error() {
		$tpl = new Template(_TEMPLATES_DIR . "/web_error.tpl");

		// sets header values
		$tpl->setValues(array(
			'lang' => $GLOBALS['langs'][$this->lang]['code'],
			'author' => _AUTHOR,
		));

		$content  = '<img src="' . _PATH . 'img/hammer.jpg" width="478" height="411" alt="Hammer">';
		$content .= '<div class="gray">Oops, something is broken</div>';

		switch ($this->err) {
			case 401:
				$tpl->set('title', '401 Unauthorized | ' . _TITLE);
				$content .= '<h1><span class="red">[401]</span> Unauthorized</h1>';
				$content .= '<p>Sorry, you are not authorized to view this page.</p>';
				break;
			case 403:
				$tpl->set('title', '403 Forbidden | ' . _TITLE);
				$content .= '<h1><span class="red">[403]</span> Forbidden</h1>';
				$content .= '<p>Sorry, the link you used is invalid.</p>';
				break;
			case 404:
				$tpl->set('title', '404 Page not found | ' . _TITLE);
				$content .= '<h1><span class="red">[404]</span> Page not found</h1>';
				$content .= '<p>Sorry, but the page you are looking for cannot be found.</p>';
				break;
			case 500:
				$tpl->set('title', '500 Internal error | ' . _TITLE);
				$content .= '<h1><span class="red">[500]</span> Internal error</h1>';
				$content .= '<p>Something terrible has happened. Help us to solve this problem by contacting the <a href="mailto:info@janmyler.com">web administrator</a>.</p>';
				break;
			default:
				$tpl->set('title', 'Error | ' . _TITLE);
				$content .= '<h1><span class="red">[o_O]</span> Unknown error</h1>';
				$content .= '<p>Something terrible has happened. Help us to solve this problem by contacting the <a href="mailto:info@janmyler.com">web administrator</a>.</p>';
				break;
		}

		$content .= '<p class="button"><a href="' . _PATH . '" class="small-button">Return to the homepage</a></p>';
		$tpl->set('content', $content);

		return $tpl->output();
	}

	private function debug() {
		echo "<div class=debug>";

		echo "<pre>Path: </pre>";
		var_dump($this->getPath());

		echo "<pre>Lang: </pre>";
		var_dump($this->getLang());

		echo "<pre>Exists: </pre>";
		var_dump($this->pageExists());

		echo "<pre>Err: </pre>";
		var_dump($this->err);

		echo "</div>\n";
	}

	private function preprocessContent(&$tpl) {
		if ($this->path['page'] === 'gallery') {
			return;
		}

		$hash = $this->getPageHash();
		if ($hash === ':contact-us') {
			// check the form validation
			if (isset($_SESSION['form_info'])) {
				$info = $_SESSION['form_info'];
				unset($_SESSION['form_info']);

				if ($info['sent'])	{
					$tpl->set($info['type'] . '_message', $info['message']);
				} else {
					// var_dump($info); exit;

					foreach ($info['data'] as $field => $data) {
						$tpl->set($info['type'] . '_' . $field, $data);
					}
					foreach($info['errors'] as $field => $error) {
						$tpl->set($info['type'] . '_' . $field . '_error', $error);
						$tpl->set($info['type'] . '_' . $field . '_class', 'error');
					}
				}
			}

			$tpl->setValuesEmpty();
		}
	}

	public function render() {
		// print debug info
		if (_DEBUG) {
			$this->debug();
		}

		// test for errors
		if (!empty($this->err)) {
			return $this->error();
		} else {
			$tpl = new Template(_TEMPLATES_DIR . "/web_layout.tpl");
			$page = $this->getPageInfo();

			// sets header values
			$tpl->setValues(array(
				'lang' => $GLOBALS['langs'][$this->lang]['code'],
				'title' => $page["title"] . ' | ' . _TITLE,
				'author' => _AUTHOR,
				'description' => (!empty($page["description"]) ? $page["description"] : _DESCRIPTION),
				'keywords' => (!empty($page["keywords"]) ? $page["keywords"] : _KEYWORDS),
				'url' => $this->getPathTo('', false),
			));

			// sets IE 6 warning
			if (_OLD_IE) {
				$tpl->set("oldIE", '<!--[if lt IE 7]><p class="chromeframe">Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->');
			} else {
				$tpl->set("oldIE", "");
			}

			// sets template blocks' values
			$tpl->setValues(array(
				'header' => $this->header(),
				'main-navi' => $this->mainMenu(),
				'footer' => $this->footer(),
				'analytics-snippet' => $this->gaSnippet(),
			));

			// sets page's content layout
			switch ($this->getPath('page')) {
				case '':		// homepage
					$content = $this->indexContent();
					break;
				default: 		// regular page
					$content = $this->pageContent();
			}

			$tpl->set('content', $content);

			// sets page text content (don't get confused by double content replacement - it is a different content tag this time)
			$pageText = new Template(_PAGES_DIR . '/' . $this->lang . '/' . $page['template']);
			$this->preprocessContent($pageText);

			$tpl = new Template($tpl->output(), true);
			$tpl->set('content', $pageText->output());

			return $tpl->output();
		}
	}
}
