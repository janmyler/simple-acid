<?php
    /* funkce pro validaci kontaktniho formulare */
    function validateName($name) {
        return (strlen($name) < 2) ? false : true;
    }
    
    function validateSurname($surname) {
        return (strlen($surname) < 2) ? false : true;
    }
    
    function validateEmail($email) {
        return ereg("^[a-zA-Z0-9]+[a-zA-Z0-9._-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$", $email);   
    }
    
    function validateMessage($message) {
        return (strlen($message) < 10) ? false : true;
    }