<?php

function omniKitDashboardWidget() {
  wp_add_dashboard_widget( 'omnikit_custom_dashboard_widget', 'OmniKit Custom Widget', 'omniKitDashboardWidgetOutput' );
}
add_action( 'wp_dashboard_setup', 'omniKitDashboardWidget' );

function omniKitDashboardWidgetOutput() {
  $omniKitCustomWidget = new OmniKitCustomWidget();
  echo $omniKitCustomWidget->widget( array("TEST"), array("TEST") );
}