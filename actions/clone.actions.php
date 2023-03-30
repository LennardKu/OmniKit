<?php

function add_clone_post_button() {
  global $post;

  if (isset($post)) {
    $button_url = admin_url('admin-ajax.php') . '?action=clone_post&post_id=' . $post->ID;
    echo '<a href="' . $button_url . '" class="button button-secondary">Clone Post</a>';
  }
}
add_action('post_submitbox_misc_actions', 'add_clone_post_button');

function clone_post_callback() {
  $post_id = intval($_REQUEST['post_id']);
  $post_clone = new OmniKitClone($post_id);
  $new_post_id = $post_clone->clone_post();
  if ($new_post_id) {
    wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
  } else {
    wp_die('Post cloning failed.');
  }
}
add_action('wp_ajax_clone_post', 'clone_post_callback');