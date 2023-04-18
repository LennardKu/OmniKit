<?php
class OmniKitCustomWidget extends WP_Widget {

  function __construct() {
      parent::__construct(
          'omnikit_custom_widget',
          'OmniKit Custom Widget',
          array( 'description' => 'A custom widget for displaying information on the dashboard.' )
      );
  }

  function widget( $args, $instance ) {
      if ( empty( $instance['content'] ) ) {
          return;
      }
      echo $args['before_widget'];
      echo $args['title'];
      echo $instance['content'];
      echo $args['after_widget'];
  }

  function form( $instance ) {
      $content = ! empty( $instance['content'] ) ? $instance['content'] : '';
?>
      <p>
          <label for="<?php echo $this->get_field_id( 'content' ); ?>"><?php _e( 'Content:' ); ?></label>
          <textarea class="widefat" id="<?php echo $this->get_field_id( 'content' ); ?>" name="<?php echo $this->get_field_name( 'content' ); ?>" rows="10"><?php echo esc_attr( $content ); ?></textarea>
      </p>
<?php
  }

  function update( $new_instance, $old_instance ) {
      $instance = array();
      $instance['content'] = ! empty( $new_instance['content'] ) ? strip_tags( $new_instance['content'] ) : '';
      return $instance;
  }
}

class OmniKitCustomWidgets {

  function __construct() {
      add_action( 'wp_dashboard_setup', array( $this, 'omniKitDashboardWidget' ) );
      add_action( 'widgets_init', array( $this, 'omniKitRegisterWidget' ) );
  }

  function omniKitDashboardWidget() {
      wp_add_dashboard_widget( 'omnikit_custom_dashboard_widget', 'OmniKit Custom Widget', array( $this, 'omniKitDashboardWidgetOutput' ) );
  }

  function omniKitDashboardWidgetOutput() {
      $omniKitCustomWidget = new OmniKitCustomWidget();
      $omniKitCustomWidget->widget( array(), $omniKitCustomWidget->get_settings() );
  }

  function omniKitRegisterWidget() {
      register_widget( 'OmniKitCustomWidget' );
  }
}
