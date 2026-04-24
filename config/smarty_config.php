<?php

require_once __DIR__ . '/../vendor/autoload.php';

function getSmarty(): Smarty
{
    $smarty = new Smarty();

    $smarty->setTemplateDir(__DIR__ . '/../templates');
    $smarty->setCompileDir(__DIR__ . '/../templates_c');
    $smarty->setCacheDir(__DIR__ . '/../cache');
    $smarty->setConfigDir(__DIR__ . '/../config');

    // Enable caching for production, disable for development
    $smarty->setCaching(Smarty::CACHING_OFF);
    $smarty->setCompileCheck(true); // Recompile templates if source changes

    return $smarty;
}
