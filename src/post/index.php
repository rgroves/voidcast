<?php
  const STATUS_FAILURE = "FAILURE";
  $journal_file = $_SERVER["CONTEXT_DOCUMENT_ROOT"] . "/../../voidcast-journal.json";

  function handle_post_data(): array {
    $title = $_POST["title"] ?? "";
    $content = $_POST["content"] ?? "";
    $errors = [];

    if (!is_string($title)) {
      $errors["title"] = "Title is invalid.";
    } elseif (strlen(trim($title)) === 0) {
      $title = "[untitled]";
    }

    if (!is_string($content) || strlen(trim($content)) === 0) {
      $errors["content"] = "Cannot create an entry with no content.";
    }

    return [
      "title" => $title,
      "content" => $content,
      "errors" => $errors
    ];
  }

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    ["title" => $title, "content" => $content, "errors" => $errors] = handle_post_data();

    if (count($errors) === 0) {
      try {
        $date = new DateTimeImmutable("now", new DateTimeZone("UTC"));
        $entry = [
          "title" => $title,
          "content" => $content,
          "date" => $date->format("c")
        ];
        $json = json_encode($entry);
        file_put_contents($journal_file, $json . PHP_EOL, FILE_APPEND);
        # Redirect to created page (PRG pattern)
        header("Location: /post/created.php");
      } catch (Exception $e) {
        $errors[$STATUS_FAILURE] = $e->getMessage();
      }
    }
  } else {
    $title = null;
    $content = null;
    $errors = [];
    $entry = [];
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow" />
  <title>Cast Into The Void</title>
  <link rel="stylesheet" href="./post.css" />
</head>
<body>
  <main>
    <h1>Voidcast Journal - Create Entry</h1>
    <form id="postform" action="/post/" method="post">
      <label for="title">Title</label>
      <input type="text" name="title" id="title" type="text">
      <?php if (isset($errors["title"])) { ?>
        <span class="error" aria-live="polite">
          <?php echo $errors["title"]; ?>
        </span>
      <?php } ?>
      <label for="content">Content</label>
      <textarea name="content" id="content" required></textarea>
      <?php if (isset($errors["content"])) { ?>
        <span class="error" aria-live="polite">
          <?php echo $errors["content"]; ?>
        </span>
      <?php } ?>
      <button type="submit">Cast Into The Void</button>
      <?php if (isset($errors[STATUS_FAILURE])) { ?>
        <span class="error" aria-live="polite">
          <?php echo $errors[STATUS_FAILURE]; ?>
        </span>
      <?php } ?>
    </form>
  </main>
</body>
</html>
