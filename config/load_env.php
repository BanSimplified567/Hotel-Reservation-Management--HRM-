<?php
// load_env.php - Environment Loader

function loadEnv($filePath = null)
{
  if ($filePath === null) {
    $filePath = __DIR__ . '/../.env';
  }

  if (!file_exists($filePath)) {
    return false; // Don't create automatically, just return false
  }

  $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

  foreach ($lines as $line) {
    $line = trim($line);

    // Skip comments
    if (strpos($line, '#') === 0) {
      continue;
    }

    // Parse key=value
    if (strpos($line, '=') !== false) {
      list($key, $value) = explode('=', $line, 2);
      $key = trim($key);
      $value = trim($value);

      // Remove surrounding quotes
      if (preg_match('/^(["\'])(.*)\1$/', $value, $matches)) {
        $value = $matches[2];
      }

      // Set in environment
      $_ENV[$key] = $value;
      $_SERVER[$key] = $value;
      putenv("$key=$value");
    }
  }

  return true;
}

// Load environment variables on include
loadEnv();

function env($key, $default = null)
{
  // Check in order: getenv, $_ENV, $_SERVER
  $value = getenv($key);

  if ($value === false) {
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? $default;
  }

  // Handle special values
  if (is_string($value)) {
    $lowerValue = strtolower($value);
    switch ($lowerValue) {
      case 'true':
        return true;
      case 'false':
        return false;
      case 'null':
        return null;
      case '':
        return $default; // Empty string might want default
    }
  }

  return $value;
}
