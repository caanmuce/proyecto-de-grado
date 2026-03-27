<?php
header('Content-Type: text/plain; charset=utf-8');
echo "GEMINI_API_KEY = " . (getenv('GEMINI_API_KEY') ?: '(vacía)') . PHP_EOL;
?>
