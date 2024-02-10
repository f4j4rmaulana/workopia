
<?php if(isset($_SESSION['success_message'])) : ?>
  <div class="message bg-green-500 p-3 my-3 text-white"><?=$_SESSION['success_message']?></div>
  <?php unset($_SESSION['success_message']); ?>
  <?php endif ?>

