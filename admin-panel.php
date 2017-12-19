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
                <a href="?page=<?php echo $_GET["page"]; ?>&category=<?php echo $cat_value["slug"]; ?>"><h3><?php echo $cat_value["display_name"]; ?></h3></a>
              </li>
            <?php } ?>
          </ul>
        <?php } else { ?>
          You have not created any categories. <span>Create a new one now.</span>
        <?php } ?>
      </div>
      <div class="section right-section category-images">
        <?php
        if( array_key_exists("category", $_GET) ) {
          $category_query = $gallery->slugify($_GET["category"]);
          $images = $gallery->get_images($category_query);
          if($images != null) {
            if(count($images) > 0) { ?>
              <ul>
              <?php foreach($images as $key => $image_data) { ?>
                <li>
                  <img src="<?php echo $image_data["url"]; ?>" alt="">
                  <h3><?php echo $image_data["image_title"] ?></h3>
                  <div class="tools">
                    <div class="remove-from-category">
                      Remove from Category
                    </div>
                  </div>
                </li>
              <?php } ?>
              </ul>
            <?php } else { ?>
              <h2>No images in this category</h2>
            <?php } ?>
          <?php } else { ?>
            <h2>No images in this category</h2>
          <?php }
        } else {?>
          <h2>Pick a category</h2>
        <?php } ?>
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
