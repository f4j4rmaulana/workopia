<?php
/**
 * Get the base path
 * @param string $path
 * @return string
 */
function basePath($path = '') 
{
  return __DIR__  . '/' . $path; //mengembalikan path setelah/ menjadi tidak ada atau ''
}

/**
 * load a view
 * 
 * @param string $name
 * @return void
 * void not return anything
 */
function loadView($name, $data = [])
{
  $viewPath = basePath("views/{$name}.view.php");

  // inspect($name);
  // inspectAndDie($viewPath);

  if (file_exists($viewPath)) {
    extract($data);
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

/**
 * Format Salary
 * 
 * @param $salary
 * @return string $formattedSalary
 */
function formatSalary($salary) {
  $formattedSalary = 'Rp. ' . number_format($salary, 2, '.', ',');
  return $formattedSalary;
}
?>
