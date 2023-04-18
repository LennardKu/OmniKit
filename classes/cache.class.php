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

    public function getHttpResponseCode($url) {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }
    

    public function cachePage($url) {
        $cacheFile = $this->getCacheFilename($url);

        // Check if the page is already cached
        if (file_exists($cacheFile)) {
            return;
        }

        // Fetch the page content and cache it
        if($this->getHttpResponseCode($url) != "200" || $this->getHttpResponseCode($url) != "302"){
            return false;
        }
        
        $pageContent = file_get_contents($url);
        file_put_contents($cacheFile, $pageContent);

        // Add a footer comment to the cached page
        $cachedContent = file_get_contents($cacheFile);
        $cachedContent = $this->minifyHtml($cachedContent);
        $cachedContent = str_replace('</html>', "</html>\n<!-- Cached version of $url -->\n<!-- Cashed with OmniKit -->\n<!-- Cashed date " . date('d-m-Y- h:i') . " --> ", $cachedContent);
        file_put_contents($cacheFile, $cachedContent);
    }

    public function minifyHtml ($code = '') {
        $search = array(
         
            // Remove whitespaces after tags
            '/\>[^\S ]+/s',
             
            // Remove whitespaces before tags
            '/[^\S ]+\</s',
             
            // Remove multiple whitespace sequences
            '/(\s)+/s',
             
            // Removes comments
            '/<!--(.|\s)*?-->/'
        );
        $replace = array('>', '<', '\\1');
        $code = preg_replace($search, $replace, $code);
        return $code;
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