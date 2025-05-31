<?php
function load_dotenv($file) {
  if (!file_exists($file)) return;
  foreach (file($file, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES) as $line) {
    if (strpos(trim($line),'#')===0) continue;
    list($key,$val) = array_map('trim', explode('=',$line,2));
    $val = preg_replace('/^([\'"])(.*)\1$/','$2',$val);
    putenv("$key=$val");
    $_ENV[$key] = $val;
  }
}
