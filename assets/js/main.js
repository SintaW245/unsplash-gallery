// assets/js/main.js

// Lazy loading images optimization
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll for back button
    const backButtons = document.querySelectorAll('.back-button');
    backButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            window.history.back();
        });
    });

    // Image load animation
    const images = document.querySelectorAll('.photo-card img, .detail-image img');
    images.forEach(img => {
        img.addEventListener('load', function() {
            this.classList.add('loaded');
        });
    });

    // Search form validation
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const input = this.querySelector('input[name="q"]');
            if (input.value.trim() === '') {
                e.preventDefault();
                alert('Silakan masukkan kata kunci pencarian');
                input.focus();
            }
        });
    }

    // Add animation class when elements come into view
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    const photoCards = document.querySelectorAll('.photo-card');
    photoCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s, transform 0.5s';
        observer.observe(card);
    });
});

// Track download function
function trackDownload(downloadLocationUrl) {
    fetch(downloadLocationUrl)
        .then(response => response.json())
        .catch(error => console.log('Download tracked'));
}

// Loading indicator
function showLoading() {
    const loader = document.createElement('div');
    loader.className = 'loader';
    loader.innerHTML = '<div class="spinner"></div>';
    document.body.appendChild(loader);
}

function hideLoading() {
    const loader = document.querySelector('.loader');
    if (loader) {
        loader.remove();
    }
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    // ESC key to go back
    if (e.key === 'Escape' && window.location.pathname.includes('detail.php')) {
        window.history.back();
    }
    
    // Ctrl/Cmd + K for search focus
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.querySelector('.search-form input');
        if (searchInput) {
            searchInput.focus();
        } else {
            window.location.href = 'search.php';
        }
    }
});

// Copy photo link (for detail page)
function copyPhotoLink() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(function() {
        alert('Link foto berhasil disalin!');
    }).catch(function() {
        alert('Gagal menyalin link');
    });
}

// Add to favorites (localStorage)
function toggleFavorite(photoId) {
    let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
    
    if (favorites.includes(photoId)) {
        favorites = favorites.filter(id => id !== photoId);
        alert('Dihapus dari favorit');
    } else {
        favorites.push(photoId);
        alert('Ditambahkan ke favorit');
    }
    
    localStorage.setItem('favorites', JSON.stringify(favorites));
}

