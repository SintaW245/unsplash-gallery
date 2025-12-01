// assets/js/search.js

// Search functionality untuk Unsplash Gallery

// Auto-complete search suggestions
class SearchAutoComplete {
    constructor() {
        this.searchInput = document.querySelector('.search-form input[name="q"]');
        this.suggestionsContainer = null;
        this.popularSearches = {
            'Nature & Landscape': ['mountains', 'ocean', 'forest', 'sunset', 'waterfall'],
            'Animals': ['cat', 'dog', 'bird', 'lion', 'elephant'],
            'Urban': ['architecture', 'building', 'city', 'street', 'night'],
            'People': ['portrait', 'business', 'family', 'friends', 'happiness'],
            'Food': ['coffee', 'food', 'restaurant', 'dessert', 'fruits'],
            'Travel': ['beach', 'travel', 'hotel', 'vacation', 'airplane'],
            'Technology': ['computer', 'phone', 'laptop', 'gadget', 'technology'],
            'Sports': ['gym', 'running', 'yoga', 'sport', 'fitness']
        };
        
        if (this.searchInput) {
            this.init();
        }
    }
    
    init() {
        // Create suggestions container
        this.createSuggestionsContainer();
        
        // Event listeners
        this.searchInput.addEventListener('input', (e) => this.handleInput(e));
        this.searchInput.addEventListener('focus', () => this.showSuggestions());
        document.addEventListener('click', (e) => this.handleClickOutside(e));
        
        // Load search history
        this.loadSearchHistory();
    }
    
    createSuggestionsContainer() {
        this.suggestionsContainer = document.createElement('div');
        this.suggestionsContainer.className = 'search-suggestions';
        this.suggestionsContainer.style.display = 'none';
        this.searchInput.parentElement.appendChild(this.suggestionsContainer);
    }
    
    handleInput(e) {
        const query = e.target.value.trim().toLowerCase();
        
        if (query.length < 2) {
            this.showPopularSearches();
            return;
        }
        
        this.filterSuggestions(query);
    }
    
    filterSuggestions(query) {
        const matches = [];
        
        // Search dalam popular searches
        for (const [category, keywords] of Object.entries(this.popularSearches)) {
            keywords.forEach(keyword => {
                if (keyword.toLowerCase().includes(query)) {
                    matches.push({
                        text: keyword,
                        category: category
                    });
                }
            });
        }
        
        this.displaySuggestions(matches);
    }
    
    showPopularSearches() {
        const html = `
            <div class="suggestions-header">🔥 Popular Searches</div>
            <div class="suggestions-grid">
                ${Object.entries(this.popularSearches).slice(0, 4).map(([category, keywords]) => `
                    <div class="suggestion-category">
                        <div class="category-title">${category}</div>
                        ${keywords.slice(0, 3).map(keyword => `
                            <div class="suggestion-item" data-query="${keyword}">
                                ${keyword}
                            </div>
                        `).join('')}
                    </div>
                `).join('')}
            </div>
        `;
        
        this.suggestionsContainer.innerHTML = html;
        this.attachSuggestionListeners();
        this.showSuggestions();
    }
    
    displaySuggestions(matches) {
        if (matches.length === 0) {
            this.hideSuggestions();
            return;
        }
        
        const html = `
            <div class="suggestions-list">
                ${matches.slice(0, 8).map(match => `
                    <div class="suggestion-item" data-query="${match.text}">
                        <span class="icon">🔍</span>
                        <span class="text">${match.text}</span>
                        <span class="category-badge">${match.category}</span>
                    </div>
                `).join('')}
            </div>
        `;
        
        this.suggestionsContainer.innerHTML = html;
        this.attachSuggestionListeners();
        this.showSuggestions();
    }
    
    attachSuggestionListeners() {
        const items = this.suggestionsContainer.querySelectorAll('.suggestion-item');
        items.forEach(item => {
            item.addEventListener('click', (e) => {
                const query = e.currentTarget.dataset.query;
                this.selectSuggestion(query);
            });
        });
    }
    
    selectSuggestion(query) {
        this.searchInput.value = query;
        this.hideSuggestions();
        this.searchInput.form.submit();
    }
    
    showSuggestions() {
        this.suggestionsContainer.style.display = 'block';
    }
    
    hideSuggestions() {
        this.suggestionsContainer.style.display = 'none';
    }
    
    handleClickOutside(e) {
        if (!this.searchInput.contains(e.target) && !this.suggestionsContainer.contains(e.target)) {
            this.hideSuggestions();
        }
    }
    
    loadSearchHistory() {
        const history = this.getSearchHistory();
        if (history.length > 0) {
            // Display history bisa ditambahkan di sini
        }
    }
    
    getSearchHistory() {
        const history = localStorage.getItem('unsplash_search_history');
        return history ? JSON.parse(history) : [];
    }
    
    saveToHistory(query) {
        let history = this.getSearchHistory();
        
        // Remove if exists
        history = history.filter(item => item !== query);
        
        // Add to beginning
        history.unshift(query);
        
        // Keep only 10 items
        history = history.slice(0, 10);
        
        localStorage.setItem('unsplash_search_history', JSON.stringify(history));
    }
}

// Search filters
class SearchFilters {
    constructor() {
        this.filters = {
            orientation: null,
            color: null,
            order_by: 'relevant'
        };
        
        this.init();
    }
    
    init() {
        this.createFilterUI();
        this.attachListeners();
    }
    
    createFilterUI() {
        const searchSection = document.querySelector('.search-section');
        if (!searchSection) return;
        
        const filterHTML = `
            <div class="search-filters">
                <button class="filter-toggle">⚙️ Filters</button>
                <div class="filter-options" style="display: none;">
                    <div class="filter-group">
                        <label>Orientation:</label>
                        <select id="filter-orientation">
                            <option value="">All</option>
                            <option value="landscape">Landscape</option>
                            <option value="portrait">Portrait</option>
                            <option value="squarish">Square</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>Color:</label>
                        <select id="filter-color">
                            <option value="">All Colors</option>
                            <option value="black_and_white">Black & White</option>
                            <option value="black">Black</option>
                            <option value="white">White</option>
                            <option value="yellow">Yellow</option>
                            <option value="orange">Orange</option>
                            <option value="red">Red</option>
                            <option value="purple">Purple</option>
                            <option value="magenta">Magenta</option>
                            <option value="green">Green</option>
                            <option value="teal">Teal</option>
                            <option value="blue">Blue</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>Sort by:</label>
                        <select id="filter-order">
                            <option value="relevant">Most Relevant</option>
                            <option value="latest">Latest</option>
                        </select>
                    </div>
                    
                    <button class="apply-filters">Apply Filters</button>
                    <button class="clear-filters">Clear</button>
                </div>
            </div>
        `;
        
        searchSection.insertAdjacentHTML('beforeend', filterHTML);
    }
    
    attachListeners() {
        const toggleBtn = document.querySelector('.filter-toggle');
        const filterOptions = document.querySelector('.filter-options');
        const applyBtn = document.querySelector('.apply-filters');
        const clearBtn = document.querySelector('.clear-filters');
        
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                filterOptions.style.display = filterOptions.style.display === 'none' ? 'block' : 'none';
            });
        }
        
        if (applyBtn) {
            applyBtn.addEventListener('click', () => this.applyFilters());
        }
        
        if (clearBtn) {
            clearBtn.addEventListener('click', () => this.clearFilters());
        }
    }
    
    applyFilters() {
        const orientation = document.getElementById('filter-orientation')?.value;
        const color = document.getElementById('filter-color')?.value;
        const order = document.getElementById('filter-order')?.value;
        
        const urlParams = new URLSearchParams(window.location.search);
        const query = urlParams.get('q');
        
        if (!query) return;
        
        let url = `search.php?q=${encodeURIComponent(query)}`;
        if (orientation) url += `&orientation=${orientation}`;
        if (color) url += `&color=${color}`;
        if (order) url += `&order_by=${order}`;
        
        window.location.href = url;
    }
    
    clearFilters() {
        document.getElementById('filter-orientation').value = '';
        document.getElementById('filter-color').value = '';
        document.getElementById('filter-order').value = 'relevant';
        
        const urlParams = new URLSearchParams(window.location.search);
        const query = urlParams.get('q');
        
        if (query) {
            window.location.href = `search.php?q=${encodeURIComponent(query)}`;
        }
    }
}

// Quick search buttons
class QuickSearch {
    constructor() {
        this.init();
    }
    
    init() {
        this.createQuickSearchUI();
    }
    
    createQuickSearchUI() {
        const container = document.querySelector('.search-section');
        if (!container) return;
        
        const quickSearches = [
            { icon: '🏔️', text: 'Mountains' },
            { icon: '🌊', text: 'Ocean' },
            { icon: '🌲', text: 'Forest' },
            { icon: '🌅', text: 'Sunset' },
            { icon: '🏙️', text: 'City' },
            { icon: '🐱', text: 'Cats' },
            { icon: '☕', text: 'Coffee' },
            { icon: '✈️', text: 'Travel' }
        ];
        
        const html = `
            <div class="quick-searches">
                <p class="quick-search-title">Quick Search:</p>
                <div class="quick-search-buttons">
                    ${quickSearches.map(item => `
                        <button class="quick-search-btn" data-query="${item.text}">
                            ${item.icon} ${item.text}
                        </button>
                    `).join('')}
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', html);
        
        // Attach listeners
        document.querySelectorAll('.quick-search-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const query = e.currentTarget.dataset.query;
                window.location.href = `search.php?q=${encodeURIComponent(query)}`;
            });
        });
    }
}

// Search statistics
class SearchStats {
    constructor() {
        this.init();
    }
    
    init() {
        this.displayStats();
    }
    
    displayStats() {
        const searchInfo = document.querySelector('.search-info');
        if (!searchInfo) return;
        
        const urlParams = new URLSearchParams(window.location.search);
        const query = urlParams.get('q');
        
        if (query) {
            // Save to history
            this.saveSearch(query);
            
            // Track search
            this.trackSearch(query);
        }
    }
    
    saveSearch(query) {
        let searches = JSON.parse(localStorage.getItem('search_stats') || '{}');
        
        if (searches[query]) {
            searches[query].count++;
            searches[query].lastSearched = Date.now();
        } else {
            searches[query] = {
                count: 1,
                firstSearched: Date.now(),
                lastSearched: Date.now()
            };
        }
        
        localStorage.setItem('search_stats', JSON.stringify(searches));
    }
    
    trackSearch(query) {
        // You can send analytics here
        console.log('Search tracked:', query);
    }
    
    getMostSearched() {
        const searches = JSON.parse(localStorage.getItem('search_stats') || '{}');
        return Object.entries(searches)
            .sort((a, b) => b[1].count - a[1].count)
            .slice(0, 5)
            .map(([query, data]) => ({ query, ...data }));
    }
}

// Keyboard shortcuts
class SearchKeyboard {
    constructor() {
        this.init();
    }
    
    init() {
        document.addEventListener('keydown', (e) => {
            // Focus search dengan '/'
            if (e.key === '/' && !this.isInputFocused()) {
                e.preventDefault();
                const searchInput = document.querySelector('.search-form input[name="q"]');
                if (searchInput) searchInput.focus();
            }
            
            // Clear search dengan Escape
            if (e.key === 'Escape') {
                const searchInput = document.querySelector('.search-form input[name="q"]');
                if (searchInput && searchInput === document.activeElement) {
                    searchInput.value = '';
                    searchInput.blur();
                }
            }
        });
    }
    
    isInputFocused() {
        const activeElement = document.activeElement;
        return activeElement.tagName === 'INPUT' || activeElement.tagName === 'TEXTAREA';
    }
}

// Initialize all search features
document.addEventListener('DOMContentLoaded', () => {
    // Check if we're on search page
    if (document.querySelector('.search-form')) {
        new SearchAutoComplete();
        new SearchFilters();
        new QuickSearch();
        new SearchStats();
        new SearchKeyboard();
        
        // Add loading indicator to search form
        const searchForm = document.querySelector('.search-form');
        if (searchForm) {
            searchForm.addEventListener('submit', () => {
                const button = searchForm.querySelector('button');
                button.innerHTML = '⏳ Searching...';
                button.disabled = true;
            });
        }
    }
});

// Export untuk digunakan di file lain
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        SearchAutoComplete,
        SearchFilters,
        QuickSearch,
        SearchStats,
        SearchKeyboard
    };
}