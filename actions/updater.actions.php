<?php

  // Check for updates
  $updater = new GitHubUpdater('LennardKu', 'wordpress-multitool', 'OmniKit.php');
  if ($updater->checkForUpdates()) {
      add_action('upgrader_process_complete', function ($upgrader, $options) {
          if ($options['type'] == 'plugin' && isset($options['plugins']) && in_array('plugin-folder/plugin-file.php', $options['plugins'])) {
              set_site_transient('update_plugins', null);
          }
      }, 10, 2);
  }

  // Add update information to the plugin metadata
  add_filter('plugins_api', function ($false, $action, $args) {
    if ($action == 'plugin_information' && isset($args->slug) && $args->slug == 'plugin-folder') {
        $updater = new GitHubUpdater('LennardKu', 'wordpress-multitool', 'OmniKit.php');
        $response = $updater->sendRequest('GET', "/repos/repository-owner/repository-name/releases/latest");
        if ($response && isset($response['tag_name'])) {
            return (object) array(
                'name' => 'Plugin Name',
                'slug' => 'plugin-folder',
                'version' => $response['tag_name'],
                'tested' => '5.7.2',
                'requires' => '5.0',
                'author' => 'Author Name',
                'author_profile' => 'https://example.com',
                'last_updated' => $response['published_at'],
                'homepage' => 'https://example.com/plugin',
                'download_link' => $response['zipball_url'],
                'sections' => array(
                    'description' => 'Plugin description.',
                    'changelog' => $response['body']
                )
            );
        }
    }
    return $false;
}, 10, 3);

