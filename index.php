<!DOCTYPE html>
<html>
   <head>
      <title>Search Results</title>
   </head>
   <body>
      <?php
      ini_set('display_errors', 1);
      ini_set('display_startup_errors', 1);
      error_reporting(E_ALL);
      ?>

      <div>
         <?php require_once 'GenerateResults.php'; ?>
      </div>
   </body>
</html>
