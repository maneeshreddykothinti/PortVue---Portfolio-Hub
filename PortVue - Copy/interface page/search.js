function hiding_part()
{
    document.getElementById("hiding_part1").style.display="none";
    document.getElementById("hiding_part2").style.display="none";
    document.getElementById("body_part").style.backgroundColor="black";
    document.getElementById("showing_results_part").style.removeProperty("display");
    document.getElementById("contact_footer").style.top="auto";
    document.getElementById("contact_footer").style.marginTop="50%";
    
    // Perform search when showing results
    performSearch();
};

function performSearch() {
    const searchQuery = document.getElementById("search-bar").value.trim();
    
    fetch(`search_portfolios.php?query=${encodeURIComponent(searchQuery)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displaySearchResults(data.portfolios, data.user_likes);
            } else {
                console.error('Search failed:', data.error);
            }
        })
        .catch(error => {
            console.error('Error fetching search results:', error);
        });
}

function displaySearchResults(portfolios, userLikes) {
    const resultsContainer = document.getElementById("showing_results_part");
    
    if (portfolios.length === 0) {
        resultsContainer.innerHTML = `
            <div id="no-portfolios">
                <h2 style="color: white;">No PortVues Found!</h2>
            </div>
        `;
        return;
    }
    
    let html = '<div id="search-portfolios-container">';
    
    portfolios.forEach(portfolio => {
        const isLiked = userLikes.includes(portfolio.pid);
        const likeClass = isLiked ? 'liked' : '';
        
        html += `
            <div class="search-portfolio-item">
                <div class="search-iframe-thumb">
                    <iframe src="${escapeHtml(portfolio.paddress)}" 
                            title="Portfolio preview" 
                            loading="lazy" 
                            sandbox="allow-same-origin allow-scripts allow-forms" 
                            referrerpolicy="no-referrer" 
                            scrolling="no" 
                            style="overflow:hidden;">
                    </iframe>
                </div>
                <h6>${escapeHtml(portfolio.uname)}</h6>
                <button class="search-expand-btn" onclick="fopenSearch('${escapeHtml(portfolio.paddress)}')">⛶</button>
                <span class="search-tags">
                    ${escapeHtml(portfolio.tags)}
                </span>
                <div class="search-des-container">
                    ${escapeHtml(portfolio.description)}
                </div>
                <div class="search-ratings-display">
                    Ratings: <span class="search-ratings-value" id="search-rating-${portfolio.pid}">${escapeHtml(portfolio.ratings)}</span>
                    <button class="search-like-btn ${likeClass}" 
                            data-portfolio-id="${portfolio.pid}" 
                            onclick="toggleSearchLike(${portfolio.pid})">
                        ❤
                    </button>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    
    // Add fullscreen viewer
    html += `
        <div id="search-fullscreen" style="display: none;">
            <div class="search-iframe-full">
                <iframe id="search-fullscreen-iframe" src="" title="Portfolio" frameborder="0" scrolling="yes"></iframe>
            </div>
            <button class="search-fullscreen-btn search-visit-btn" onclick="visitSearchPortfolio()">visit</button>
            <button class="search-fullscreen-btn search-close-btn" onclick="closeSearchFullscreen()">X</button>
        </div>
    `;
    
    resultsContainer.innerHTML = html;
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return String(text).replace(/[&<>"']/g, m => map[m]);
}

let currentSearchPortfolioUrl = '';

function fopenSearch(url) {
    currentSearchPortfolioUrl = url;
    const fs = document.getElementById('search-fullscreen');
    const iframe = document.getElementById('search-fullscreen-iframe');
    iframe.src = url;
    fs.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeSearchFullscreen() {
    const fs = document.getElementById('search-fullscreen');
    const iframe = document.getElementById('search-fullscreen-iframe');
    fs.style.display = 'none';
    iframe.src = '';
    document.body.style.overflow = '';
}

function visitSearchPortfolio() {
    if (currentSearchPortfolioUrl) {
        window.open(currentSearchPortfolioUrl);
    }
}

function toggleSearchLike(portfolioId) {
    const likeBtn = document.querySelector(`.search-like-btn[data-portfolio-id="${portfolioId}"]`);
    const ratingSpan = document.getElementById(`search-rating-${portfolioId}`);
    
    const formData = new FormData();
    formData.append('portfolio_id', portfolioId);
    
    fetch('../top_iframe/handle_like.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            ratingSpan.textContent = data.ratings;
            if (data.liked) {
                likeBtn.classList.add('liked');
            } else {
                likeBtn.classList.remove('liked');
            }
        } else {
            alert(data.message || 'Failed to update like');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing your request');
    });
}

function home_showing_part()
{
    document.getElementById("hiding_part1").style.display="block";
    document.getElementById("hiding_part2").style.display="block";
    document.getElementById("body_part").style.backgroundColor="rgb(255, 255, 255)";
    document.getElementById("showing_results_part").style.display="none";
    document.getElementById("contact_footer").style.top="400%";
    document.getElementById("contact_footer").style.marginTop="0%";
}

function show_uploads()
{
    document.getElementById("ur_uploads").style.display="flex";
}
function close_x()
{
    document.getElementById("ur_uploads").style.display="none";
}