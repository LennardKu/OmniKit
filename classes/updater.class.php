<?php
class GitHubPluginUpdater {
    private $pluginSlug;
    private $githubUsername;
    private $githubRepository;

    public function __construct($pluginSlug, $githubUsername, $githubRepository) {
        $this->pluginSlug = $pluginSlug;
        $this->githubUsername = $githubUsername;
        $this->githubRepository = $githubRepository;

        add_filter('pre_set_site_transient_update_plugins', array($this, 'checkForUpdate'));
        add_filter('plugins_api', array($this, 'pluginInfo'), 10, 3);
    }

    public function checkForUpdate($transient) {
        if (empty($transient->checked)) {
            return $transient;
        }

        $latestVersion = $this->getLatestVersion();
        if (version_compare($latestVersion, $transient->checked[$this->pluginSlug], '>')) {
            $response = array(
                'slug' => $this->pluginSlug,
                'new_version' => $latestVersion,
                'url' => $this->getPluginHomepage(),
                'package' => $this->getDownloadUrl(),
            );

            $transient->response[$this->pluginSlug] = (object) $response;
        }

        return $transient;
    }

    public function pluginInfo($false, $action, $response) {
        if ($action === 'plugin_information' && isset($response->slug) && $response->slug === $this->pluginSlug) {
            $response->last_updated = $this->getLatestReleaseDate();
            $response->slug = $this->pluginSlug;
            $response->name = $this->getPluginName();
            $response->homepage = $this->getPluginHomepage();
            $response->version = $this->getLatestVersion();
            $response->author = $this->getPluginAuthor();
            $response->download_link = $this->getDownloadUrl();
            $response->sections = array(
                'description' => $this->getPluginDescription(),
                'changelog' => $this->getPluginChangelog(),
            );

            return $response;
        }

        return false;
    }

    private function getLatestVersion() {
        $releases = $this->getReleases();
        return isset($releases[0]) ? $releases[0]['tag_name'] : false;
    }

    private function getLatestReleaseDate() {
        $releases = $this->getReleases();
        return isset($releases[0]) ? date('Y-m-d', strtotime($releases[0]['published_at'])) : false;
    }

    private function getPluginName() {
        $pluginData = get_plugin_data($this->getPluginFile());
        return $pluginData['Name'];
    }

    private function getPluginDescription() {
        $pluginData = get_plugin_data($this->getPluginFile());
        return $pluginData['Description'];
    }

    private function getPluginAuthor() {
        $pluginData = get_plugin_data($this->getPluginFile());
        return $pluginData['Author'];
    }

    private function getPluginHomepage() {
        $pluginData = get_plugin_data($this->getPluginFile());
        return $pluginData['PluginURI'];
    }

    private function getPluginChangelog() {
        $releases = $this->getReleases();
        $changelog = '';

        foreach ($releases as $release) {
            $changelog .= '<h2>' . $release['tag_name'] . ' - ' . date('F j, Y', strtotime($release['published_at'])) . '</h2>' . "\n";
            $changelog .= $release['body'] . "\n";
        }

        return $changelog;
    }

    private function getDownloadUrl() {
        $releases = $this->getReleases();
        return isset($releases[0]) ? $releases[0]['zipball_url'] : false;
    }

    private function getReleases() {
        $response = wp_remote_get('https://api.github.com/repos/' . $this->githubUsername . '/' . $this->githubRepository . '/releases');
        $body = wp_remote_retrieve_body($response);
        $releases = json_decode($body, true);

        return is_array($releases) ? $releases : array();
    }

    private function getPluginFile() {
        $plugins = get_plugins();
        return isset($plugins[$this->pluginSlug]) ? WP_PLUGIN_DIR . '/' . $this->pluginSlug : false;
    }
}