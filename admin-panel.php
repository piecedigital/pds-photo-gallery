<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) ?>style.css">
<div class="pds-photo-gallery">
  <div class="gallery-manager">
    <h1>Photo Gallery Admin Panel</h1>
    <div class="sections">
      <div class="section-header left-section-header">
        <h2>Categories</h2>
      </div>
      <div class="section-header right-section-header">
        <h2>Category Images</h2>
      </div>
      <div class="section left-section gallery-categories">
        <?php
        $cats = $gallery->get_categories();
        if( count( $cats ) > 0 ) { ?>
          <ul>
            <?php foreach($cats as $cat => $cat_value) { ?>
              <li class="gallery-category">
                <h3><?php echo $cat_value["display_name"]; ?></h3>
              </li>
            <?php } ?>
          </ul>
        <?php } else { ?>
          You have not created any categories. <span>Create a new one now.</span>
        <?php } ?>
      </div>
      <div class="section right-section category-images">
        <?php

        ?>
      </div>
    </div>
  </div>
  <div class="media-library">
    <div class="media">
      <ul>
        <?php foreach($media as $url) { ?>
          <li>
            <div class="image">
              <img src="<?php echo $url ?>" alt="">
            </div>
            <div class="tools">
              <div class="add-to-category">
                Add To Category
              </div>
            </div>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</div>
