<?php
  $journal_file = $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/../../voidcast-journal.json';
  $journal_json_data = file_get_contents($journal_file);

  if (!$journal_json_data) {
    error_log("Error: Unable to read the journal file at {$journal_file}. Error: " . error_get_last()['message']);
    $journal_data = [];
  }

  $journal_data = array_map('json_decode', explode('\n', trim($journal_json_data)));

  if (json_last_error() !== JSON_ERROR_NONE) {
    error_log('Error: Failed to decode JSON data. Error: ' . json_last_error_msg());
    $journal_data = [];
  }

  $filtered_journal_data = array_filter($journal_data, function ($entry) {
    return isset($entry->title, $entry->date, $entry->content);
  });
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Voidcast Journal - Random thoughts & moments cast into the digital void">
    <title>The Voidcast Journal of Robert Groves</title>
  </head>
  <body>
    <main>
      <article>
        <h1>Voidcast Journal</h1>
        <hr />
        <?php
        foreach ($journal_data as $entry) {
          if (!isset($entry->title, $entry->date, $entry->content)) {
            continue;
          }

          $title = htmlspecialchars($entry->title, ENT_QUOTES, 'UTF-8');
          $date = htmlspecialchars($entry->date, ENT_QUOTES, 'UTF-8');
          $content = nl2br(htmlspecialchars($entry->content, ENT_QUOTES, 'UTF-8'));

          echo "<article>";
          echo "  <header>";
          echo "    <h1>" . $title . "</h1>";

          try {
            $date_obj = new DateTimeImmutable($date);
            $date_obj = $date_obj->setTimezone(new DateTimeZone("US/Central"));
            echo "    <time datetime=\"" . $date_obj->format("c") . "\">" . 
            $date_obj->format("M j, Y @ g:i A T") . "</time>";
          } catch (Exception $e) {
            error_log("Error: Invalid date format for entry '{$title}'. Error: " . $e->getMessage());
            echo "    <time>Invalid date</time>";
          }
          echo "  </header>";
          echo "  <p>" . $content . "</p>";
          echo "</article>";
          echo "<hr />";
        }
        ?>
      </article>
    </main>
  </body>
</html>
