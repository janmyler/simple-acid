<?php
/**
 * Template Class file.
 *
 * This class either reads the template string from a file or from the given string.
 * It replaces all tags (in format [@tag]) for their values and returns back
 * the resulting string.
 *
 * @author      Jan Myler <info@janmyler.com>
 * @copyright   Copyright 2012, Jan Myler (http://janmyler.com)
 *
 */

class Template {
    /**
     * $inline Indicates if the template is in a file or given in
     * a simple template string.
     *
     * @var boolean
     * @access private
     */
	private $inline;

    /**
     * $file Is a name of the file with a template or simply the template
     * string itself ($inline parameter must be set to truthy value in this case).
     *
     * @var string
     * @access private
     */
	private $file;

    /**
     * $values An array of keys and values which are replaced in the template.
     *
     * @var array
     * @access private
     */
	private $values = array();

    /**
     * $emptyValues Is a flag if keywords are replaced with actual value or an
     * empty string.
     *
     * @var boolean
     * @access private
     */
	private $emptyValues;

    /**
     * __construct
     *
     * @param string $file 		Template filename or a string containing the template.
     * @param boolean $inline 	Indicates if the $file contains a filename or a string.
     *
     * @access public
     * @return void
     */
	public function __construct($file, $inline = false) {
		$this->file = $file;
		$this->inline = $inline;
		$this->values['path'] = _PATH;
		$this->emptyValues = false;
	}

    /**
     * set
     *
     * @param string $key   Keyword from the template (is replaced with the value).
     * @param string $value Data that will be inserted into the template.
     *
     * @access public
     * @return void
     */
	public function set($key, $value) {
		$this->values[$key] = $value;
	}

    /**
     * setValues
     *
     * @param array $values The array of keys and values in (key => value) format.
     *
     * @access public
     * @return void
     */
	public function setValues($values) {
		$this->values = array_merge($this->values, $values);
	}

    /**
     * setValuesEmpty All remaining keywords (after the replacing) are removed if
     * this method is called before the output method executes.
     *
     * @access public
     * @return void
     */
	public function setValuesEmpty() {
		$this->emptyValues = true;
	}

    /**
     * Replaces the keywords in the template and returns the output string.
     *
     * @access public
     * @return string String with replaced key words for their values.
     */
	public function output() {
		if(!$this->inline && !file_exists($this->file) && strstr($this->file, _PATH . _PAGES_DIR)) {
			$this->file = _PATH . _PAGES_DIR . "/404.tpl";
		} else if (!$this->inline) {
			$output = @file_get_contents($this->file);
		} else {
			$output = $this->file;
		}

		// FIXME: move to the line 36 under file opening?
		if(!$output) {
			return "<span class=error>" . _t("TEMPLATE_OPEN_ERR") . " [$this->file]</span>";
		}


		foreach ($this->values as $key => $value) {
			$tagToReplace = "[@$key]";
			$output = str_replace($tagToReplace, $value, $output);
		}

		// removes all remaining keywords
		if ($this->emptyValues) {
			$output = preg_replace('/\[@\w+?\]/', '', $output);
		}

		return $output;
	}

	/**
	 * Merges an array of Template objects (their outputs) and returns a string.
	 *
	 * @param array $templates Array of the Template class objects.
	 * @param string $separator Character or a string which separates
	 * the templates' outputs.
	 *
	 * @static
	 * @access public
	 * @return string Merged templates
	 */
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
