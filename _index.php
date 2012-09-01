<?php
    /* trida pro nahrazeni tagu v sablonach */
    include("core/template.class.php");

    /* funkce pro validaci kontaktniho formulare */
    include("core/validate.php");

    /* konfiguracni soubor webu */
    include("config.php");

    /* zjisteni pozadovane stranky */
    $link = (isset($_GET["page"])) ? $_GET["page"] : "uvod";
    $requiredPage = null;

    /* vytvoreni polozek menu */
    foreach ($pages as $page) {
        $menuItem = new Template(TEMPLATES_DIR . "/menu_row.tpl");

        // nastaveni zvyrazneni aktivni stranky v menu
        if ($link == $page["url"]) {
            $page["active"] = "active";
            $requiredPage = $page;  // uchovani informaci pro pozadovanou stranku
        }

        // nahrazeni tagu v sablone
        foreach ($page as $key => $value) {
            $menuItem->set($key, $value);
        }

        $menuItems[] = $menuItem;
    }

    /* vytvoreni hlavniho menu */
    $mainMenu = new Template(TEMPLATES_DIR . "/menu.tpl");
    $mainMenu->set("content", Template::merge($menuItems));

    /* vytvoreni spodniho menu */
    $bottomMenu = new Template(TEMPLATES_DIR . "/bottom_menu.tpl");
    $bottomMenu->set("content", Template::merge($menuItems));

    /* nacteni sablony obsahu stranky */
    if ($requiredPage) {
        $pageContent = new Template(PAGES_DIR . "/" . $requiredPage["template"]);
    }
    else {
        $pageContent = new Template(PAGES_DIR . $link);
    }

    /* nacteni a nastaveni layoutu stranky */
    $pageLayout = new Template(TEMPLATES_DIR . "/layout.tpl");
    $pageLayout->set("author", AUTHOR);
    $pageLayout->set("description", (empty($requiredPage["description"])) ? DESCRIPTION : $requiredPage["description"]);
    $pageLayout->set("keywords", (empty($requiredPage["keywords"])) ? KEYWORDS : $requiredPage["keywords"]);
    $pageLayout->set("title", (empty($requiredPage["title"])) ? TITLE : $requiredPage["title"] . " – " . TITLE);
    $pageLayout->set("menu", $mainMenu->output());
    $pageLayout->set("bottom-menu",$bottomMenu->output());

    /* zpracovani kontaktniho formulare */
    if ($requiredPage["url"] == "napiste-nam") {
        // validace na strane serveru pri odeslani formulare
        if (isset($_POST["send"])) {
            $messages[] = null;
            $err = false;

            if (!validateName($_POST["name"])) {
                $msg = new Template(TEMPLATES_DIR . "/validation_row.tpl");
                $msg->set("message", "Neplatné jméno");
                $msg->set("description", "Vyplňte prosím Vaše jméno.");
                $messages[] = $msg;
                $err = true;
            }
            if (!validateSurname($_POST["surname"])) {
                $msg = new Template(TEMPLATES_DIR . "/validation_row.tpl");
                $msg->set("message", "Neplatné příjmení");
                $msg->set("description", "Vyplňte prosím Vaše příjmení.");
                $messages[] = $msg;
                $err = true;
            }
            if (!validateEmail($_POST["email"])) {
                $msg = new Template(TEMPLATES_DIR . "/validation_row.tpl");
                $msg->set("message", "Neplatný e-mail");
                $msg->set("description", "Zadejte validní e-mailovou adresu.");
                $messages[] = $msg;
                $err = true;
            }
            if (!validateMessage($_POST["message"])) {
                $msg = new Template(TEMPLATES_DIR . "/validation_row.tpl");
                $msg->set("message", "Neplatný text zprávy");
                $msg->set("description", "Text Vaší zprávy musí být delší než 10 znaků.");
                $messages[] = $msg;
                $err = true;
            }

            if ($err) {  // nastaveni sablony chyboveho vypisu
                $validationMsg = new Template(TEMPLATES_DIR . "/validation_err.tpl");
                $validationMsg->set("content", Template::merge($messages));
            }
            else {  // nastaveni sablony pro uspech zpracovani zpravy
                $msg = new Template(TEMPLATES_DIR . "/validation_row.tpl");
                $msg->set("message", "Děkujeme!");
                $msg->set("description", "Vaše zpráva byla úspěšně odeslána.");

                $validationMsg = new Template(TEMPLATES_DIR . "/validation_succ.tpl");
                $validationMsg->set("content", $msg->output());
            }

            // predani vypisu do sablony obsahu stranky
            $pageLayout->set("validation", $validationMsg->output());
        }
        else {  // pokud zatim nebyl odeslan formular – nejsou vypisovany informace o zpracovani
            $pageLayout->set("validation", "");
        }
    }
    else {  // obsah stranky pro jine stranky nez kontaktni formular
        $pageLayout->set("content", $pageContent->output());
    }

    /* zobrazeni stranky na vystup */
    echo $pageLayout->output();



