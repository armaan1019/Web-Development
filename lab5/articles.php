<?php

/*$jsonContents = file_get_contents("articles.json");

$articles = json_decode($jsonContents, true);

if($articles === null) {
  echo "Error decoding JSON";
  exit;
}*/

libxml_use_internal_errors(true);

$dom = new DOMDocument();

$dom->loadHTMLFile("articles.html");

libxml_clear_errors();

$container = $dom->getElementById("articles-container");

if(!$container) {
  echo "Container not found";
  exit;
}

$databaseFile = 'articles.db';

$pdo = new PDO('sqlite:' . $databaseFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$query = "SELECT * FROM articles";

$stmt = $pdo->prepare($query);
$stmt->execute();

$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($articles as $articleData) {
  $articleDiv = $dom->createElement("div");
  $articleDiv->setAttribute("class", "article");

  $link = $dom->createElement("a");
  $link->setAttribute("href", "article?id=" . $articleData["id"]);

  $h2 = $dom->createElement("h2", $articleData["title"]);
  
  $tagsDiv = $dom->createElement("div");
  $tagsDiv->setAttribute("class", "tags");

  $tags = explode(',', $articleData["tags"]);

  foreach($tags as $tag) {
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
