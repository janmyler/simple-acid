<?php
/**
 * Template Class file.
 *
 * Replaces [@tag] tags in template files.
 *
 * @author      Jan Myler <honza.myler@gmail.com>
 * @copyright   Copyright 2012, Jan Myler (http://janmyler.com)
 *
 */

class Template {
	private $file;
	private $values = array();

	public function __construct($file) {
		$this->file = $file;
		_t("test");
	}

	public function set($key, $value) {
		$this->values[$key] = $value;
	}

	public function setValues($values) {
		$this->values = array_merge($this->values, $values);
	}

	public function output() {
		if(!file_exists($this->file) && strstr($this->file, _PATH . _PAGES_DIR)) {
			$this->file = _PATH . _PAGES_DIR . "/404.tpl";
		}
		$output = @file_get_contents($this->file);
		if(!$output) {
			return "<span class=error>" . _t("TEMPLATE_OPEN_ERR") . "($this->file).</span>";
		}
		foreach ($this->values as $key => $value) {
			$tagToReplace = "[@$key]";
			$output = str_replace($tagToReplace, $value, $output);
		}

		return $output;
	}

	static public function merge($templates, $separator = "\n") {
		$output = "";

		foreach ($templates as $template) {
			$content = (get_class($template) !== "Template")
				? _t("TEMPLATE_TYPE_ERR")
				: $template->output();
			$output .= $content . $separator;
		}

		return $output;
	}
}
