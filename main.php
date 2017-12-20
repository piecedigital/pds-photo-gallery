<?php
/*
  Plugin Name: Photo Gallery
  Plugin URI: http://piecedigital.net
  Description: Plugin for generating a basic photo gallery
  Author: Darryl Dixon
  Version: 0.1
  Author URI: http://piecedigital.net
*/

// add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);

// Gallery [
//   categories => [
//     "category-name" => [
//       "display_name" => "Category Name",
//       "slug" => "category-name",
//       "images" => [
//         [ "image_title" => "Image Title", "url" => "<url>" ]
//       ]
//     ],
//     ...
//   ]
// ]

/**
 *
 */
class Gallery {
  var $data;

  function __construct($data) {
    $this->data = $data ? $data : [];
  }

  function &search_for_category($category_slug, $return_value=false) {
    $nullGuard = null;
    foreach($this->data["categories"] as $cat_key => &$cat_value) {
      // echo $cat_key . " | " . ($cat_value["slug"] == $category_slug) . " | $cat_value[slug] == $category_slug<br><br>";
      if($cat_value["slug"] == $category_slug) {
        // echo "found<br><br>";
        if($return_value) {
          return $cat_value;
        } else {
          return $cat_key;
        }
      }
    }
    return $nullGuard;
  }

  function slugify($name) {
    $slug = preg_replace("/\s+/", "-", strtolower($name));
    $slug = preg_replace("/\-+/", "-", $slug);
    return $slug;
  }

  function add_image($category_slug, $title, $url) {
    $cat = &$this->search_for_category($category_slug, true);
    if($cat != null) {
      $images = &$cat["images"];
      // echo json_encode($images);

      array_push($images, [
        "image_title" => $title,
        "url" => $url
      ]);
    }
  }

  // function remove_image($category_slug, $title, $url) {
  //   $images = &$this->search_for_category($category_slug)["images"];
  //   // echo json_encode($images);
  //
  //   array_push($images, [
  //     "image_title" => $title,
  //     "url" => $url
  //   ]);
  // }

  function add_category($display_name) {
    if( !isset($this->data["categories"]) ) {
      $this->data["categories"] = [];
    }

    // echo json_encode($this->data);
    $categories = &$this->data["categories"];
    $slug = $this->slugify($display_name);

    $cat = $this->search_for_category($slug, true);

    if($cat == null) {
      array_push($categories, [
        "display_name" => $display_name,
        "slug" => $slug,
        "images" => []
      ]);
    } else {
      // echo "category exists";
    }

  }

  function remove_category($category_slug) {
    if( !isset($this->data["categories"]) ) {
      $this->data["categories"] = [];
    }

    // echo json_encode($this->data);
    $cats = &$this->data["categories"];

    $cat = &$this->search_for_category($category_slug, true);
    if($cat != null) {
      $cat_index = &$this->search_for_category($category_slug);
      // echo "removing $cat_index <br><br>";
      array_splice($cats, $cat_index, 1);
    } else {
      // echo "category doesn't exists";
    }

  }

  function get_categories() {
    if(array_key_exists("categories", $this->data)) {
      return $this->data["categories"];
    } else {
      return null;
    }
  }

  function get_images($slug) {
    if(array_key_exists("categories", $this->data)) {
      $cat = &$this->search_for_category($slug, true);
      if($cat != null) {
        return $cat["images"];
      } else {
        return null;
      }
    } else {
      return null;
    }
  }

  function save_data($gallery_path) {
    file_put_contents($gallery_path, json_encode($this->data));
  }

}

function get_media_library() {
  $media_query = new WP_Query(
      array(
          'post_type' => 'attachment',
          'post_status' => 'inherit',
          'posts_per_page' => -1,
      )
  );
  $list = array();
  foreach ($media_query->posts as $post) {
      $list[] = wp_get_attachment_url($post->ID);
  }
  // do something with $list here;
  // echo json_encode($list);
  return $list;
}

function pds_test_app() {
  $gallery_path = plugin_dir_path(__FILE__) . 'gallery.json';

  // check if json file exists
  $fileExists = file_exists($gallery_path);
  $data = [];
  if($fileExists) {
    $data = json_decode( file_get_contents($gallery_path), true );
  }

  $gallery = new Gallery($data);

  // $gallery->add_category("Category Name");
  // $gallery->add_category("New Category Name");
  // $gallery->add_image("new-category-name", "new image", "http://wordpress.local/wp-content/uploads/2017/12/used_btn_off.jpg");
  // $gallery->add_image("category-name", "new image", "http://wordpress.local/wp-content/uploads/2017/12/used_btn_off.jpg");
  // $gallery->remove_category("category-name");
  // $gallery->save_data($gallery_path);

  $media = get_media_library();

  // load admin page
  include("admin-panel.php");
}


// initiation
function pds_test_ui() {
  add_media_page(_("Photo Gallery"), _("Photo Gallery"), "manage_options", _("Photo Gallery"), "pds_test_app");
}

add_action( "admin_menu", "pds_test_ui");
