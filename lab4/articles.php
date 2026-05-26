<?php

$jsonContents = file_get_contents("articles.json");

$articles = json_decode($jsonContents, true);

if($articles === null) {
  echo "Error decoding JSON";
  exit;
}

libxml_use_internal_errors(true);

$dom = new DOMDocument();

$dom->loadHTMLFile("articles.html");

libxml_clear_errors();

$container = $dom->getElementById("articles-container");

if(!$container) {
  echo "Container not found";
  exit;
}

foreach($articles as $articleData) {
  $articleDiv = $dom->createElement("div");
  $articleDiv->setAttribute("class", "article");

  $link = $dom->createElement("a");
  $link->setAttribute("href", "article.php?id=" . $articleData["id"]);

  $h2 = $dom->createElement("h2", $articleData["title"]);
  
  $tagsDiv = $dom->createElement("div");
  $tagsDiv->setAttribute("class", "tags");

  foreach($articleData["tags"] as $tag) {
    $pTag = $dom->createElement("p", $tag);
    $tagsDiv->appendChild($pTag);
  }

  $plainText = strip_tags($articleData["content"]);

  $preview = substr($plainText, 0, 120) . "...";

  $previewP = $dom->createElement("p", $preview);

  $link->appendChild($h2);
  $link->appendChild($tagsDiv);
  $link->appendChild($previewP);

  $articleDiv->appendChild($link);

  $container->appendChild($articleDiv);
}

echo $dom->saveHTML();

?>
