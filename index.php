<?php
/**
 * Simple-AcID index file.
 *
 * @author      Jan Myler <honza.myler@gmail.com>
 * @copyright   Copyright 2012, Jan Myler (http://janmyler.com)
 *
 */

require_once(".config.php");
require_once("core/web.php");


// get path params
$path["page"] = (isset($_GET["page"])) ? $_GET["page"] : "";
$path["param1"] = (isset($_GET["param1"])) ? $_GET["param1"] : "";
$path["param2"] = (isset($_GET["param2"])) ? $_GET["param2"] : "";

// create new web object
$web = Web::getInstance();
$web->setPath($path);

// set requested website language
if (isset($_GET["lang"]) && array_key_exists($_GET["lang"], $langs)) {
	$web->setLang($_GET["lang"]);
}

var_dump($web->getPath());
var_dump($web->getLang());

var_dump($web->pageExists());

// check for error code
$err = (isset($_GET["err"])) ? $_GET["err"] : "" ;
$web->setError($err);

if (!$web->pageExists()) {
	$web->setError('404');
}

// display web
echo $web->render();
