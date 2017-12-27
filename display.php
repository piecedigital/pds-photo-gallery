<div class="">
  <ul>
    <li>
      <?php
      $categories = $gallery->get_all();
      foreach($images as $key => $image_data) {
        foreach($images as $key => $image_data) { ?>
          <li>
            <div class="category-image-info">
              <div class="img">
                <img src="<?php echo $image_data["url"]; ?>" alt="">
              </div>
              <h3><?php echo $image_data["image_title"] ?></h3>
              <div class="info">
                <h6><?php echo ($key+1) . "/" . count($images); ?></h6>
              </div>
            </div>
          </li>
        <?php }
      } ?>
    </li>
  </ul>
</div>
