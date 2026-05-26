<?php

$jsonContents = file_get_contents("articles.json");

$articles = json_decode($jsonContents, true);

if($articles === null) {
  echo "Error decoding JSON";
  exit;
}

$latestArticle = $articles[count($articles) - 1];

libxml_use_internal_errors(true);

$dom = new DOMDocument();

$dom->loadHTMLFile("index.html");

libxml_clear_errors();

$h1 = $dom->getElementById("article-title");

$tagsDiv = $dom->getElementById("tags");

$content = $dom->getElementById("content");

$continueReading = $dom->getElementById("continue-reading");

if(!$h1 || !$tagsDiv || !$content || !$continueReading) {
  echo "Missing Statements";
  exit;
}

$h1->nodeValue = $latestArticle["title"];

foreach($latestArticle["tags"] as $tag) {
  $p = $dom->createElement("p", $tag);

  $tagsDiv->appendChild($p);
}

$fragment = $dom->createDocumentFragment();

$fragment->appendXML($latestArticle["content"]);

$content->appendChild($fragment);

$continueReading->setAttribute("href", "/lab4/index.php/article?id=" . $latestArticle["id"]);

echo $dom->saveHTML();

?>
