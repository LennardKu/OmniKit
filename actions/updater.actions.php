<?php

  // Check for updates
//   $updater = new GitHubUpdater('LennardKu', OmniKitPluginName, 'OmniKit.php',"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");
//   if ($updater->checkForUpdates()) {
//       add_action('upgrader_process_complete', function ($upgrader, $options) {
//           if ($options['type'] == 'plugin' && isset($options['plugins']) && in_array('plugin-folder/plugin-file.php', $options['plugins'])) {
//               set_site_transient('update_plugins', null);
//           }
//       }, 10, 2);
//   }

$updater = new GitHubPluginUpdater(OmniKitPluginName."/OmniKit.php", 'LennardKu', 'OmniKit');
