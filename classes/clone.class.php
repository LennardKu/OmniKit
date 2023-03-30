<?php

class OmniKitClone {
  private $postId;

  public function __construct($postId) {
    $this->postId = $postId;
  }

  public function clone_post() {
    $post = get_post($this->postId);

    if (!$post) {
      return false;
    }

    $new_post = array(
      'post_title' => $post->post_title . '  - Clone',
      'post_content' => $post->post_content,
      'post_status' => $post->post_status,
      'post_date' => $post->post_date,
      'post_author' => $post->post_author,
      'post_type' => $post->post_type
    );

    $new_post_id = wp_insert_post($new_post);

    if ($new_post_id) {
      $this->copy_post_meta($this->post_id, $new_post_id);
      return $new_post_id;
    }

    return false;
  }

  private function copy_post_meta($post_id, $new_post_id) {
    $post_meta_keys = get_post_custom_keys($post_id);

    if (!$post_meta_keys) {
      return;
    }

    foreach ($post_meta_keys as $meta_key) {
      $meta_values = get_post_custom_values($meta_key, $post_id);

      foreach ($meta_values as $meta_value) {
        add_post_meta($new_post_id, $meta_key, $meta_value);
      }
    }
  }
}