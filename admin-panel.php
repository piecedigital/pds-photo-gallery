<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) ?>style.css">
<?php $base_query = "?page=$_GET[page]"; ?>
<?php if(array_key_exists("category", $_GET)) $base_query .= "&category=$_GET[category]"; ?>

<div class="pds-photo-gallery">
  <h1 class="main-title">Photo Gallery Admin Panel</h1>
  <div class="gallery-manager">
    <h1>Category Manager</h1>
    <div class="sections">
      <!-- top column -->
      <div class="section-header left-section-header">
        <h2>Categories</h2>
      </div>
      <div class="section-header right-section-header">
        <h2>Category Images</h2>
      </div>
      <!-- /top column -->
      <!-- middle column -->
      <div class="section left-section gallery-categories">
        <?php
        $cats = $gallery->get_categories();
        if( count( $cats ) > 0 ) { ?>
          <ul>
            <?php foreach($cats as $cat => $cat_value) { ?>
              <li class="gallery-category">
                <a href="<?php echo $base_query; ?>"><h3><?php echo $cat_value["display_name"]; ?></h3> <h6>(<?php echo count($cat_value["images"]); ?>)</h6></a>
                <div class="remove-category">
                  <a href="<?php echo $base_query . "&remove_category=true"; ?>">Remove Category</a>
                </div>
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
                  <div class="category-image-info">
                    <div class="img">
                      <img src="<?php echo $image_data["url"]; ?>" alt="">
                    </div>
                    <h3><?php echo $image_data["image_title"] ?></h3>
                    <div class="info">
                      <h6><?php echo ($key+1) . "/" . count($images); ?></h6>
                    </div>
                    <div class="tools">
                      <div class="remove-from-category">
                        <a href="<?php echo $base_query . "&remove_image=true&img_url=$image_data[url]"; ?>">Remove from Category</a>
                      </div>
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
      <!-- /middle column -->
      <!-- bottom column -->
      <div class="section add-category">
        <h2>Add Category</h2>
        <form class="add-category form" action="#" method="get">
          <input type="hidden" name="page" value="<?php echo "$_GET[page]"; ?>">
          <input type="text" name="category" value="">
          <button type="submit" name="add_category" value="true">Add Category</button>
        </form>
      </div>
      <!-- /bottom column -->
    </div>
  </div>
  <div class="media-library">
    <h1>Media Library</h1>
    <div class="media">
      <ul>
        <?php foreach($media as $url) { ?>
          <li>
            <div class="">
              <div class="image">
                <img src="<?php echo $url ?>" alt="">
              </div>
              <div class="tools">
                <div class="add-to-category">
                  <a href="<?php echo $base_query . "&add_image=true&img_url=$url"; ?>">
                    Add To Category
                  </a>
                </div>
              </div>
            </div>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</div>
