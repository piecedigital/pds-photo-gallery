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
  var $gallery_path;

  function __construct($data, $gallery_path) {
    $this->data = $data ? $data : [];
    $this->gallery_path = $gallery_path;
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

  function &search_for_image($images, $url, $return_value=false) {
    $nullGuard = null;
    foreach($images as $img_key => $img_data) {
      if($img_data["url"] == $url) {
        echo "$img_key | $img_data[url]";
        if($return_value) {
          return $img_data;
        } else {
          return $img_key;
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

  function remove_image($category_slug, $url) {
    echo "removing image";
    echo "removing image";
    echo "removing image";
    $images = &$this->search_for_category($category_slug, true)["images"];
    // echo json_encode($images);
    if($images !== null) {
      $index = &$this->search_for_image($images, $url);
      if($index !== null) {
        array_splice($images, $index, 1);
      } else {
        echo "Image not found";
      }
    } else {
      echo "No images found";
    }
  }

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

    $cat_index = &$this->search_for_category($category_slug);
    if($cat_index !== null) {
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

  function get_all() {
    return $this->data;
  }

  function save_data() {
    file_put_contents($this->gallery_path, json_encode($this->data));
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

function check_and_act_on_query_params($gallery) {
  if(array_key_exists("add_image", $_GET) && $_GET["add_image"] == "true") {
    if(array_key_exists("category", $_GET)) {
      $image_url = $_GET["img_url"];
      $image_url_array = explode("/", $image_url);
      $image_name_dot = end( $image_url_array );
      $image_name_dot_array = explode(".", $image_name_dot );
      $image_name = current( $image_name_dot_array );

      $gallery->add_image($_GET["category"], $image_name, $image_url);
      $gallery->save_data();
    } else {
      echo "Need category to add an image";
    }
  };
  if(array_key_exists("remove_image", $_GET) && $_GET["remove_image"] == "true") {
    if(array_key_exists("category", $_GET)) {
      $image_url = $_GET["img_url"];

      $gallery->remove_image($_GET["category"], $image_url);
      $gallery->save_data();
    } else {
      echo "Need category to add an image";
    }
  };
  if(array_key_exists("add_category", $_GET) && $_GET["add_category"] == "true") {
    if(array_key_exists("category", $_GET)) {
      $gallery->add_category($_GET["category"]);
      $gallery->save_data();
    } else {
      echo "Need category name";
    }
  };
  if(array_key_exists("remove_category", $_GET) && $_GET["remove_category"] == "true") {
    if(array_key_exists("category", $_GET)) {
      $gallery->remove_category($_GET["category"]);
      $gallery->save_data();
    } else {
      echo "Need category to add an image";
    }
  };

  if(count($_GET) > 2) {
    purge_query_params();
  }
}

function purge_query_params() {
  $url = $_SERVER["REQUEST_URI"];
  $parsed_url = parse_url($url);
  $new_url = $parsed_url["path"];
  if(array_key_exists("page0", $_GET)) {
    $new_url .= "?page" . "=" . $_GET["page"];
  }
  if(array_key_exists("category", $_GET)) {
    $new_url .= "&category" . "=" . $_GET["category"];
  }
  header("Location: " . $new_url);
  // die();
}

function &get_gallery() {
  $gallery_path = plugin_dir_path(__FILE__) . 'gallery.json';
  // check if json file exists
  $fileExists = file_exists($gallery_path);
  $data = [];
  if($fileExists) {
    $data = json_decode( file_get_contents($gallery_path), true );
  }

  $gallery = new Gallery($data, $gallery_path);
  return $gallery;
}

function init() {
  $gallery = &get_gallery();
  check_and_act_on_query_params($gallery);
}

function pds_photo_gallerys_app() {
  $gallery = &get_gallery();

  // $gallery->add_category("Category Name");
  // $gallery->add_category("New Category Name");
  // $gallery->add_image("new-category-name", "new image", "http://wordpress.local/wp-content/uploads/2017/12/used_btn_off.jpg");
  // $gallery->add_image("category-name", "new image", "http://wordpress.local/wp-content/uploads/2017/12/used_btn_off.jpg");
  // $gallery->remove_category("category-name");
  // $gallery->save_data();

  $media = get_media_library();

  // load admin page
  include("admin-panel.php");
}

function pds_output_widget() {
  $gallery = get_gallery();
  include("display.php");
}

function pds_register_widget() {
  echo "register";
  register_sidebar(array(
    "id" => "pds_photo_gallery_area",
    "name" => "Photo Gallery Area",
    "before_widget" => "<a>",
    "after_widget" => "</a>"
  ));
  wp_register_sidebar_widget( "pds_photo_gallery", "Photo Gallery", "pds_output_widget"/*, $options = array()*/ );
}

// initiation
function pds_test_ui() {
  init();
  add_media_page(_("Photo Gallery"), _("Photo Gallery"), "manage_options", _("Photo Gallery"), "pds_photo_gallerys_app");
}

add_action( "admin_menu", "pds_test_ui");
add_action( "widgets_init", "pds_register_widget");
