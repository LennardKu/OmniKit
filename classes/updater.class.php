<?php
class GitHubUpdater {
  private $repositoryOwner;
  private $repositoryName;
  private $pluginFile;
  private $accessToken;
  
  public function __construct($repositoryOwner, $repositoryName, $pluginFile, $accessToken = null) {
      $this->repositoryOwner = $repositoryOwner;
      $this->repositoryName = $repositoryName;
      $this->pluginFile = $pluginFile;
      $this->accessToken = $accessToken;
  }
  
  public function checkForUpdates() {
      $response = $this->sendRequest("GET", "/repos/{$this->repositoryOwner}/{$this->repositoryName}/releases/latest");
      if ($response && isset($response['tag_name'])) {
          $latestVersion = $response['tag_name'];
          $currentVersion = $this->getCurrentVersion();
          if ($currentVersion && version_compare($latestVersion, $currentVersion) > 0) {
              return $this->downloadUpdate($response['zipball_url']);
          }
      }
      return false;
  }
  
  private function sendRequest($method, $path, $data = null) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, "https://api.github.com{$path}");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
      curl_setopt($ch, CURLOPT_USERAGENT, "GitHubUpdater");
      if ($this->accessToken) {
          curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: token {$this->accessToken}"));
      }
      if ($data) {
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
      }
      $response = curl_exec($ch);
      $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
      if ($statusCode >= 200 && $statusCode < 300) {
          return json_decode($response, true);
      }
      return false;
  }
  
  private function getCurrentVersion() {
      $pluginData = get_plugin_data(WP_PLUGIN_DIR . '/' . $this->pluginFile);
      return $pluginData['Version'];
  }
  
  private function downloadUpdate($url) {
      $tmpFile = tempnam(sys_get_temp_dir(), 'github-updater');
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_FILE, fopen($tmpFile, 'w+'));
      curl_exec($ch);
      $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
      if ($statusCode >= 200 && $statusCode < 300) {
          $zip = new ZipArchive();
          if ($zip->open($tmpFile) === true) {
              $destination = WP_PLUGIN_DIR . '/' . dirname($this->pluginFile);
              if (!file_exists($destination)) {
                  mkdir($destination, 0755, true);
              }
              $zip->extractTo($destination);
              $zip->close();
              unlink($tmpFile);
              return true;
          }
      }
      unlink($tmpFile);
      return false;
  }
}
