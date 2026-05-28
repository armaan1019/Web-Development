<?php

$articleId = $_GET["id"] ?? null;


//var_dump($articleId);
if(!$articleId) {
    echo "Error: No article id provided.";
    exit;
}

//echo "Article ID received: " . $articleId;

$jsonContents = file_get_contents("articles.json");

//var_dump($jsonContents);

$articles = json_decode($jsonContents, true);

if($articles === null) {
  echo "Error: Failed to decode JSON.";
  exit;
}

//var_dump($articles);

$matchingArticle = null;

foreach($articles as $article) {
  if($article["id"] === $articleId) {
    $matchingArticle = $article;
    break;
  }
}

if($matchingArticle === null) {
  echo "Error: Article not found.";
  exit;
}

//var_dump($matchingArticle);

//echo "Article found: " . $matchingArticle["title"];

if(!file_exists("article.html")) {
  echo "Error: article.html file not found";
  exit;
}

libxml_use_internal_errors(true);

$dom = new DOMDocument();

$dom->loadHTMLFile("article.html");

libxml_clear_errors();

$h1 = $dom->getElementsByTagName("h1")->item(0);

$div = $dom->getElementById("tags");

$articleTag = $dom->getElementById("content");

if(!$h1) {
  echo "Error: h1 tag not found";
  exit;
}

if(!$div) {
  echo "Error: div tag not found";
  exit;
}

if(!$articleTag) {
  echo "Error: article tag not found";
  exit;
}

$h1->nodeValue = $matchingArticle["title"];

foreach($matchingArticle["tags"] as $tag) {
  $p = $dom->createElement("p");

  $p->nodeValue = $tag;

  $div->appendChild($p);
}

$fragment = $dom->createDocumentFragment();

$fragment->appendXML($matchingArticle["content"]);

$articleTag->appendChild($fragment);

$currentIndex = -1;

foreach($articles as $index => $article) {
  if($article["id"] === $articleId) {
    $currentIndex = $index;
    break;
  }
}

$nav = $dom->getElementById("navigation");

if($nav) {
  while($nav->firstChild) {
    $nav->removeChild($nav->firstChild);
  }

  if($currentIndex > 0) {
    $prevArticle = $articles[$currentIndex - 1];

    $prevLink = $dom->createElement("a", "Previous");
    $prevLink->setAttribute("href", "/~asharma13/csen161/lab4/index.php/article?id=" . $prevArticle["id"]);
    $nav->appendChild($prevLink);
  }

  $topLink = $dom->createElement("a", "Top");
  $topLink->setAttribute("href", "#top");

  $nav->appendChild($topLink);

  if($currentIndex < count($articles) - 1) {
    $nextArticle = $articles[$currentIndex + 1];

    $nextLink = $dom->createElement("a", "Next");
    $nextLink->setAttribute("href", "/~asharma13/csen161/lab4/index.php/article?id=" . $nextArticle["id"]);
    $nextLink->setAttribute("class", "blue");
    $nav->appendChild($nextLink);
  }
}

echo $dom->saveHTML();

?>
