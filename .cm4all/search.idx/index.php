<?php

require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."../include/BrowserRequest.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."../include/BengProxy.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."../include/config.php");

global $config;

$bpr = new BengProxyHandler(
    isset($config["hosting-backend-server"]) ?
    $config["hosting-backend-server"] :
    "cm4all-hosting-backend.local", new BrowserRequest(),
    "/.cm4all/search.idx");
$bpr->execRequest();
$bpr->sendResponse();

?>