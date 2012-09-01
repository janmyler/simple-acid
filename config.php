<?php
/* slozka sablon struktury */
define("TEMPLATES_DIR", "templates");

/* slozka sablon obsahu */
define("PAGES_DIR", "pages");

/* autor webu */
define("AUTHOR", "");

/* globalni popis webu, pouzito pokud neni nastaven popis pro zvolenou stranku */
define("DESCRIPTION", "");

/* globalni klicova slova, pouzito pokud nejsou nastaveny klicova slova pro zvolenou stranku */
define("KEYWORDS", "");

/* globalni titulek stranky – je doplnen titulkem jednotlivych stranek */
define("TITLE", "oborove-katalogy.eu");

/* konfigurace jednotlivych stranek webu */
$pages = array(
    array(
        "active"      => "",            // nastaveno automaticky pro prave zobrazovanou stranku
        "caption"     => "Úvod",        // popisek odkazu v hlavnim menu
        "description" => "",            // popis do meta-tagu description
        "keywords"    => "",            // klicova slova do meta-tagu keywords
        "template"    => "uvod.tpl",    // soubor sablony
        "title"       => "Úvod",        // lokalni titulek pro stranku
        "url"         => "uvod",        // pretty-url odkaz
    ),
    array(
        "active"      => "",
        "caption"     => "Proč PR články",
        "description" => "",
        "keywords"    => "",
        "template"    => "pr_clanky.tpl",
        "title"       => "Proč PR články",
        "url"         => "proc-pr-clanky",
    ),
    array(
        "active"      => "",
        "caption"     => "Ceník registrace",
        "description" => "",
        "keywords"    => "",
        "template"    => "cenik.tpl",
        "title"       => "Ceník registrace",
        "url"         => "cenik-registrace",
    ),
    array(
        "active"      => "",
        "caption"     => "Obchodní podmínky",
        "description" => "",
        "keywords"    => "",
        "template"    => "obchodni_podminky.tpl",
        "title"       => "Obchodní podmínky",
        "url"         => "obchodni-podminky",
    ),
    array(
        "active"      => "",
        "caption"     => "Reklamní prostor",
        "description" => "",
        "keywords"    => "",
        "template"    => "reklama.tpl",
        "title"       => "Reklamní prostor",
        "url"         => "reklamni-prostor",
    ),
    array(
        "active"      => "",
        "caption"     => "Napište nám",
        "description" => "",
        "keywords"    => "",
        "template"    => "napiste_nam.tpl",
        "title"       => "Napište nám",
        "url"         => "napiste-nam",
    ),
    array(
        "active"      => "",
        "caption"     => "Kontakt",
        "description" => "",
        "keywords"    => "",
        "template"    => "kontakt.tpl",
        "title"       => "Kontakt",
        "url"         => "kontakt",
    ),
);