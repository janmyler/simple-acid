<?php
   $link = (isset($_GET["page"])) ? $_GET["page"] : "uvod";
   $err = (isset($_GET["err"])) ? $_GET["err"] : "none";
   echo "Hello world, you requested <strong>$link</strong> | $err!";