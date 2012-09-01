<?php 
/* Jednoduchy sablonovaci system, nahrazuje tagy formatu [@tag] v *.tpl sablonach. */
class Template {
    protected $file;
    protected $values = array();

    public function __construct($file) {
        $this->file = $file;
    }

    public function set($key, $value) {
        $this->values[$key] = $value;
    }
    
    public function output() {
        if(!file_exists($this->file) && strstr($this->file, PAGES_DIR)) {
            $this->file = PAGES_DIR . "/404.tpl";
        }
        $output = @file_get_contents($this->file);
        if($output == FALSE) {
            return "<span class=\"error\">[Chyba: Nepodařilo se otevřít šablonu ($this->file).]</span><br>";
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
                ? "Chyba, očekáván typ Template."
                : $template->output();
            $output .= $content . $separator;
        }
        
        return $output;    
    }
}