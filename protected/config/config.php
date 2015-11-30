<?php
if(version_compare(PHP_VERSION, "5.3.0", "<")) die("require PHP > 5.3.0!");

define("CONFIG_PATH",APP_PATH."/config");
define("BASE_PATH",APP_PATH."/base");
define("COMPONENT_PATH",APP_PATH."/component");
define("C_PATH",APP_PATH."/controllers");
define("M_PATH",APP_PATH."/models");
define("V_PATH",APP_PATH."/views");
