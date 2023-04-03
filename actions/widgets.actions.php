<?php

function OmniKitInformationWidget() {
  register_sidebar( array(
      'name' => __( 'My Widget Area', 'text_domain' ),
      'id' => 'my-widget-area',
      'description' => __( 'Add widgets here to appear in my widget area.', 'text_domain' ),
      'before_widget' => '<div class="widget">',
      'after_widget' => '</div>',
      'before_title' => '<h2 class="widget-title">',
      'after_title' => '</h2>',
  ) );
}
add_action( 'widgets_init', 'OmniKitInformationWidget' );