<?php
/**
 * Get the base path
 * @param string $path
 * @return string
 */
function basePath($path = '') 
{
  return __DIR__  . '/' . $path;
}

/**
 * load a view
 * 
 * @param string $name
 * @return void
 * void not return anything
 */
function loadView($name)
{
  $viewPath = basePath("views/{$name}.view.php");

  // inspect($name);
  // inspectAndDie($viewPath);

  if (file_exists($viewPath)) {
    require $viewPath;
  } else {
    echo "View '{$name} not found!'";
  }
}

/**
 * load a partial
 * 
 * @param string $name
 * @return void
 */
function loadPartial($name)
{
  $partialPath = basePath("views/partials/{$name}.php");

  if (file_exists($partialPath)) {
    require $partialPath;
  } else {
    echo "View '{$name} not found!'";
  }
}

/**
 * Inspect a value
 * 
 * @param string $value
 * @return void
 */
function inspect($value) {
  echo '<pre>';
  var_dump($value);
  echo '</pre>';
}

/**
 * Inspect a value and die
 * 
 * @param string $value
 * @return void
 */
function inspectAndDie($value) {
  echo '<pre>';
  die(var_dump($value)); 
  echo '</pre>';
}
?>
