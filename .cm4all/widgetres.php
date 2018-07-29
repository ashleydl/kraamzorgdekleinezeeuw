<?php
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."include/base.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."include/BrowserRequest.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."include/BengProxy.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."include/config.php");

function handleWidgetResRequest() {
    global $config;
    $bpr = new BengProxyHandler(isset($config["hosting-backend-server"]) ? $config["hosting-backend-server"] : "cm4all-hosting-backend.local", new BrowserRequest(), '/.cm4all/widgetres' . $_SERVER["PATH_INFO"] . '?' . $_SERVER["QUERY_STRING"]);
    $bpr->execRequest();
    $bpr->sendResponse();
}


handleWidgetResRequest();
?>
