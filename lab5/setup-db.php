<?php

$databaseFile = 'articles.db';

try {
  $pdo = new PDO('sqlite:' . $databaseFile);

  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $query = "
    CREATE TABLE IF NOT EXISTS articles (
      id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
      title TEXT NOT NULL,
      tags TEXT NOT NULL,
      content TEXT NOT NULL
)
";

  $pdo->exec($query);

  echo "Database created successfully.";

  $json = file_get_contents('articles.json');
  $articles = json_decode($json, true);

  foreach($articles as $article) {
    $title = $article['title'];
    $tags = implode(',', $article['tags']);
    $content = $article['content'];

    $query = "
INSERT INTO articles (title, tags, content)
VALUES (:title, :tags, :content)
";
    $stmt = $pdo->prepare($query);

    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':tags', $tags);
    $stmt->bindParam(':content', $content);

    $stmt->execute();

    echo "Article with title '$title' was inserted successfully.<br>";

  }

  $query = "SELECT * FROM articles";
  $stmt = $pdo->prepare($query);
  $stmt->execute();
  $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
  var_dump($articles);

} catch (PDOException $e) {
  echo "Database error: " . $e->getMessage();
}
