<?php

$request = $_SERVER["REQUEST_URI"];

$request = parse_url($request, PHP_URL_PATH);

$base = "/~asharma13/csen161/lab4/index.php";

if(str_starts_with($request, $base)) {
  $request = substr($request, strlen($base));
}

$page = trim($request, "/");

if($page === "") {
  $page = "home";
}

$phpScriptPath = $page . ".php";

$fullPath = __DIR__ . "/" . $phpScriptPath;

if(file_exists($fullPath)) {
  include($fullPath);
} else {
  echo "Error: Page not found";
}

?>
