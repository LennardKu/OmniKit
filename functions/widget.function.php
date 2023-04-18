<?php

function omniKitDashboardWidget() {
  wp_add_dashboard_widget( 'omnikit_custom_dashboard_widget', 'Omnikit dashboard', 'omniKitDashboardWidgetOutput' );
}
add_action( 'wp_dashboard_setup', 'omniKitDashboardWidget' );

function omniKitDashboardWidgetOutput() {
  $omniKitCustomWidget = new OmniKitCustomWidget();
  echo $omniKitCustomWidget->widget( array("title"=>"","before_widget"=>'',"after_widget"=>""), array("content"=>"OmniKit Custom dashboard item ") );
}