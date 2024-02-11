<?php
  use Framework\Session;
?>

<?php $successMessage = Session::getFlashMessage('success_message'); ?>
<?php if($successMessage !== null) :?>
  <div class="message bg-green-500 p-3 my-3 text-white"><?=$successMessage?></div>
<?php endif ?> 

<?php $errorMessage = Session::getFlashMessage('error_message'); ?>
<?php if($errorMessage !== null) :?>
  <div class="message bg-red-500 p-3 my-3 text-white"><?=$errorMessage?></div>
<?php endif ?> 
  
  

