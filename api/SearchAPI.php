<?php
// api/SearchAPI.php

require_once __DIR__ . '/../UnsplashAPI.php';

class SearchAPI extends UnsplashAPI {
    
    /**
     * Search photos dengan berbagai filter
     * @param string $query - Kata kunci pencarian
     * @param array $options - Opsi tambahan (page, per_page, orientation, color)
     * @return array
     */
    public function advancedSearch($query, $options = []) {
        $url = $this->apiUrl . "/search/photos";
        
        $params = [
            'query' => $query,
            'page' => isset($options['page']) ? $options['page'] : 1,
            'per_page' => isset($options['per_page']) ? $options['per_page'] : 20,
        ];
        
        // Tambahan filter opsional
        if (isset($options['orientation'])) {
            $params['orientation'] = $options['orientation']; // landscape, portrait, squarish
        }
        
        if (isset($options['color'])) {
            $params['color'] = $options['color']; // black_and_white, black, white, yellow, orange, red, purple, magenta, green, teal, blue
        }
        
        if (isset($options['order_by'])) {
            $params['order_by'] = $options['order_by']; // relevant, latest
        }
        
        return $this->makeRequest($url, $params);
    }
    
    /**
     * Get suggested search terms
     * @param string $query - Kata kunci
     * @return array
     */
    public function getSearchSuggestions($query) {
        // Daftar keyword populer untuk foto
        $suggestions = [
            'nature' => ['mountains', 'forest', 'ocean', 'sunset', 'landscape', 'trees', 'sky'],
            'animal' => ['cat', 'dog', 'bird', 'wildlife', 'pets', 'horse'],
            'city' => ['urban', 'architecture', 'building', 'street', 'downtown'],
            'people' => ['portrait', 'family', 'children', 'friends', 'business'],
            'food' => ['restaurant', 'cooking', 'dessert', 'fruits', 'vegetables'],
            'travel' => ['beach', 'vacation', 'adventure', 'tourism', 'culture'],
            'technology' => ['computer', 'phone', 'gadget', 'coding', 'digital'],
            'sports' => ['football', 'basketball', 'gym', 'running', 'cycling'],
            'art' => ['painting', 'drawing', 'sculpture', 'design', 'creative'],
            'fashion' => ['style', 'clothing', 'shoes', 'accessories', 'model']
        ];
        
        $results = [];
        $query = strtolower($query);
        
        // Cari keyword yang match
        foreach ($suggestions as $category => $keywords) {
            if (strpos($category, $query) !== false) {
                $results[] = $category;
            }
            foreach ($keywords as $keyword) {
                if (strpos($keyword, $query) !== false) {
                    $results[] = $keyword;
                }
            }
        }
        
        return array_unique($results);
    }
    
    /**
     * Search collections by title
     * @param string $query - Collection title
     * @param int $page - Page number
     * @return array
     */
    public function searchCollections($query, $page = 1) {
        $url = $this->apiUrl . "/search/collections";
        $params = [
            'query' => $query,
            'page' => $page,
            'per_page' => 10
        ];
        
        return $this->makeRequest($url, $params);
    }
    
    /**
     * Search users by name
     * @param string $query - User name
     * @param int $page - Page number
     * @return array
     */
    public function searchUsers($query, $page = 1) {
        $url = $this->apiUrl . "/search/users";
        $params = [
            'query' => $query,
            'page' => $page,
            'per_page' => 10
        ];
        
        return $this->makeRequest($url, $params);
    }
    
    /**
     * Get popular search queries
     * @return array
     */
    public function getPopularSearches() {
        return [
            'Nature & Landscape' => ['mountains', 'ocean', 'forest', 'sunset', 'waterfall'],
            'Animals & Wildlife' => ['cat', 'dog', 'bird', 'lion', 'elephant'],
            'Urban & City' => ['architecture', 'building', 'city', 'street', 'night'],
            'People & Lifestyle' => ['portrait', 'business', 'family', 'friends', 'happiness'],
            'Food & Drink' => ['coffee', 'food', 'restaurant', 'dessert', 'fruits'],
            'Travel & Places' => ['beach', 'travel', 'hotel', 'vacation', 'airplane'],
            'Technology' => ['computer', 'phone', 'laptop', 'gadget', 'technology'],
            'Sports & Fitness' => ['gym', 'running', 'yoga', 'sport', 'fitness']
        ];
    }
    
    /**
     * Format search results untuk display
     * @param array $results - Raw results dari API
     * @return array
     */
    public function formatSearchResults($results) {
        if (isset($results['error']) || empty($results['results'])) {
            return $results;
        }
        
        $formatted = [
            'total' => $results['total'],
            'total_pages' => $results['total_pages'],
            'results' => []
        ];
        
        foreach ($results['results'] as $photo) {
            $formatted['results'][] = [
                'id' => $photo['id'],
                'description' => $photo['description'] ?? $photo['alt_description'] ?? 'Untitled',
                'urls' => $photo['urls'],
                'user' => [
                    'name' => $photo['user']['name'],
                    'username' => $photo['user']['username'],
                    'profile_image' => $photo['user']['profile_image']['small']
                ],
                'likes' => $photo['likes'],
                'views' => isset($photo['views']) ? $photo['views'] : 0,
                'downloads' => isset($photo['downloads']) ? $photo['downloads'] : 0,
                'created_at' => $photo['created_at']
            ];
        }
        
        return $formatted;
    }
    
    /**
     * Validate search query
     * @param string $query - Search query
     * @return array ['valid' => bool, 'message' => string]
     */
    public function validateQuery($query) {
        $query = trim($query);
        
        if (empty($query)) {
            return [
                'valid' => false,
                'message' => 'Kata kunci pencarian tidak boleh kosong'
            ];
        }
        
        if (strlen($query) < 2) {
            return [
                'valid' => false,
                'message' => 'Kata kunci minimal 2 karakter'
            ];
        }
        
        if (strlen($query) > 100) {
            return [
                'valid' => false,
                'message' => 'Kata kunci maksimal 100 karakter'
            ];
        }
        
        // Filter kata-kata yang tidak pantas (opsional)
        $blacklist = ['xxx', 'porn', 'sex', 'nude']; // Tambahkan sesuai kebutuhan
        foreach ($blacklist as $word) {
            if (stripos($query, $word) !== false) {
                return [
                    'valid' => false,
                    'message' => 'Kata kunci tidak diperbolehkan'
                ];
            }
        }
        
        return [
            'valid' => true,
            'message' => 'Valid'
        ];
    }
    
    /**
     * Get search history dari session
     * @return array
     */
    public function getSearchHistory() {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        return isset($_SESSION['search_history']) ? $_SESSION['search_history'] : [];
    }
    
    /**
     * Save search ke history
     * @param string $query - Search query
     * @return void
     */
    public function saveToHistory($query) {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        if (!isset($_SESSION['search_history'])) {
            $_SESSION['search_history'] = [];
        }
        
        // Tambah ke awal array
        array_unshift($_SESSION['search_history'], [
            'query' => $query,
            'timestamp' => time()
        ]);
        
        // Limit history ke 10 terakhir
        $_SESSION['search_history'] = array_slice($_SESSION['search_history'], 0, 10);
        
        // Remove duplicates
        $unique = [];
        $seen = [];
        foreach ($_SESSION['search_history'] as $item) {
            if (!in_array($item['query'], $seen)) {
                $unique[] = $item;
                $seen[] = $item['query'];
            }
        }
        $_SESSION['search_history'] = $unique;
    }
    
    /**
     * Clear search history
     * @return void
     */
    public function clearHistory() {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        unset($_SESSION['search_history']);
    }
}