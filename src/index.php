<?php
  $journal_file = $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/../../voidcast-journal.json';
  $journal_json_data = file_get_contents($journal_file);

  if ($journal_json_data === "") {
    $journal_data = [];
  } elseif (!$journal_json_data) {
    error_log("Error: Unable to read the journal file at {$journal_file}. Error: " . error_get_last()['message']);
    $journal_data = [];
  } else {
    $journal_data = array_map('json_decode', explode("\n", trim($journal_json_data)));

    if (json_last_error() !== JSON_ERROR_NONE) {
      error_log('Error: Failed to decode JSON data. Error: ' . json_last_error_msg());
      $journal_data = [];
    }
  }
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
      <article class="vc-journal">
        <h1>Voidcast Journal</h1>
        <hr />
<?php foreach ($journal_data as $entry) : ?>
        <article class="vc-journal-entry">
          <header>
            <h1><?= htmlspecialchars($entry->title, ENT_QUOTES, 'UTF-8'); ?></h1>
<?php
            try {
              $date_obj = new DateTimeImmutable($entry->date);
              $date_obj = $date_obj->setTimezone(new DateTimeZone('US/Central'));
              echo "            <time datetime=\"{$date_obj->format('c')}\">{$date_obj->format('M j, Y @ g:i A T')}</time>";
            } catch (Exception $e) {
              error_log("Error: Invalid date format for entry '{$entry->title}'. Error: " . $e->getMessage());
            }
?>

          </header>
          <p><?= nl2br(htmlspecialchars($entry->content, ENT_QUOTES, 'UTF-8')); ?></p>
        </article>
        <hr />
<?php endforeach; ?>
      </article>
    </main>
  </body>
</html>
