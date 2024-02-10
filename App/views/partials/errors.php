<?php if(isset($errors)) : ?>
            <div class="message bg-red-100 my-3">
            <?php foreach($errors as $error) : ?>
                <ul class="list-inside list-disc px-2 ml-2">
                  <li class="ml-2"><?= $error?></li>
                </ul>
              <?php endforeach ?>
              </div>
            <?php endif ?>