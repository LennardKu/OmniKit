<?php
class OmniKitCache {
    private $cacheFolder;

    public function __construct($cacheFolder = '') {
        $this->cacheFolder = $cacheFolder ? $cacheFolder : dirname(__FILE__) . '/cache/';

        // Create cache folder if it doesn't exist
        if (!file_exists($this->cacheFolder)) {
            mkdir($this->cacheFolder, 0777, true);
        }
    }

    public function cachePage($url) {
        $cacheFile = $this->getCacheFilename($url);

        // Check if the page is already cached
        if (file_exists($cacheFile)) {
            return;
        }

        // Fetch the page content and cache it
        $pageContent = file_get_contents($url);
        file_put_contents($cacheFile, $pageContent);

        // Add a footer comment to the cached page
        $cachedContent = file_get_contents($cacheFile);
        $cachedContent = str_replace('</html>', "</html> \n <!-- Cached version of $url -->\n<!-- Cashed with OmniKit -->\n<!-- Cashed date " . date('d-m-Y- h:i') . " --> ", $cachedContent);
        file_put_contents($cacheFile, $cachedContent);
    }

    public function deleteAllCachedPages() {
        // Delete all files in the cache folder
        $files = glob($this->cacheFolder . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    public function checkCached ($url = ''){
        $cacheFile = $this->getCacheFilename($url);
        if(file_exists($cacheFile)){
            return $cacheFile;
        }
        return false;
    }

    public function viewAllCachedPages() {
        // Get a list of all cached pages
        $files = glob($this->cacheFolder . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                echo $file . '<br>';
            }
        }
    }

    private function getCacheFilename($url) {
        // Remove any illegal characters from the URL
        $filename = preg_replace('/[^a-zA-Z0-9]/', '_', $url);
        return $this->cacheFolder . '/' . $filename . '.html';
    }
}