<?php

/*$jsonContents = file_get_contents("articles.json");

$articles = json_decode($jsonContents, true);

if($articles === null) {
  echo "Error decoding JSON";
  exit;
}*/

$databaseFile = 'articles.db';

$pdo = new PDO('sqlite:' . $databaseFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$query = "SELECT * FROM articles ORDER BY id DESC LIMIT 1";
$stmt = $pdo->prepare($query);
$stmt->execute();

$latestArticle = $stmt->fetch(PDO::FETCH_ASSOC);

//$latestArticle = $articles[count($articles) - 1];

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

$tags = explode(',', $latestArticle["tags"]);

foreach($tags as $tag) {
  $p = $dom->createElement("p", $tag);

  $tagsDiv->appendChild($p);
}

$fragment = $dom->createDocumentFragment();

$fragment->appendXML($latestArticle["content"]);

$content->appendChild($fragment);

$continueReading->setAttribute("href", "/~asharma13/csen161/lab5/index.php/article?id=" . $latestArticle["id"]);

echo $dom->saveHTML();

?>
