<?php
session_start();
require "config/Config.php";
unset($_SESSION);
session_destroy();
header("Location: ".Config::SITE_URL);
