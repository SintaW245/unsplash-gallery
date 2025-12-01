<?php
// api/UnsplashAPI.php

class UnsplashAPI {
    private $accessKey;
    private $apiUrl;
    
    public function __construct() {
        require_once __DIR__ . '/../config/config.php';
        $this->accessKey = UNSPLASH_ACCESS_KEY;
        $this->apiUrl = UNSPLASH_API_URL;
    }
    
    /**
     * Get nature photos
     * @param int $page - Page number
     * @param int $perPage - Photos per page
     * @return array
     */
    public function getNaturePhotos($page = 1, $perPage = 20) {
        $url = $this->apiUrl . "/search/photos";
        $params = [
            'query' => 'nature',
            'page' => $page,
            'per_page' => $perPage,
            'orientation' => 'landscape'
        ];
        
        return $this->makeRequest($url, $params);
    }
    
    /**
     * Search photos by query
     * @param string $query - Search term
     * @param int $page - Page number
     * @param int $perPage - Photos per page
     * @return array
     */
    public function searchPhotos($query, $page = 1, $perPage = 20) {
        $url = $this->apiUrl . "/search/photos";
        $params = [
            'query' => $query,
            'page' => $page,
            'per_page' => $perPage
        ];
        
        return $this->makeRequest($url, $params);
    }
    
    /**
     * Get photo detail by ID
     * @param string $photoId - Photo ID
     * @return array
     */
    public function getPhotoDetail($photoId) {
        $url = $this->apiUrl . "/photos/" . $photoId;
        return $this->makeRequest($url);
    }
    
    /**
     * Download photo (track download for Unsplash API guidelines)
     * @param string $downloadUrl - Download URL from photo object
     * @return void
     */
    public function trackDownload($downloadUrl) {
        $this->makeRequest($downloadUrl);
    }
    
    /**
     * Make HTTP request to Unsplash API
     * @param string $url - API endpoint
     * @param array $params - Query parameters
     * @return array
     */
    private function makeRequest($url, $params = []) {
        // Add access key to params
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Client-ID ' . $this->accessKey,
            'Accept-Version: v1'
        ]);
        
        // Execute request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        // Handle response
        if ($httpCode === 200) {
            return json_decode($response, true);
        } else {
            return [
                'error' => true,
                'message' => 'API request failed with code ' . $httpCode,
                'response' => $response
            ];
        }
    }
}