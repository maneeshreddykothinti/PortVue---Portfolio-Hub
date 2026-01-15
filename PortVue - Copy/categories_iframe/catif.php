<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Categories</title>
  <link rel="stylesheet" href="cat.css?v=2" />
</head>
<body>
<div class="main-container">
  <div class="nav-and-categories">
    <!-- IT -->
    <div class="category-wrapper">
      <div class="navbar-btn">IT</div>
      <div class="category">
        <h2>IT</h2>
        <ul>
          <li class="has-submenu">
            💻 Software & Web Development
            <ul class="submenu">
              <li onclick="filterByCategory('Front-End Developer')">Front-End Developer</li>
              <li onclick="filterByCategory('Back-End Developer')">Back-End Developer</li>
              <li onclick="filterByCategory('Software Engineer')">Software Engineer</li>
              <li onclick="filterByCategory('Application Developer')">Application Developer</li>
              <li onclick="filterByCategory('Mobile App Developer')">Mobile App Developer</li>
              <li onclick="filterByCategory('DevOps Engineer')">DevOps Engineer</li>
              <li onclick="filterByCategory('Cloud Developer')">Cloud Developer</li>
              <li onclick="filterByCategory('API Developer')">API Developer</li>
              <li onclick="filterByCategory('Web Application Engineer')">Web Application Engineer</li>
              <li onclick="filterByCategory('UI/UX Engineer')">UI/UX Engineer</li>
            </ul>
          </li>
          <li class="has-submenu">
            🧠 Data & AI
            <ul class="submenu">
              <li onclick="filterByCategory('Data Analyst')">Data Analyst</li>
              <li onclick="filterByCategory('Data Engineer')">Data Engineer</li>
              <li onclick="filterByCategory('Machine Learning Engineer')">Machine Learning Engineer</li>
              <li onclick="filterByCategory('AI Developer')">AI Developer</li>
              <li onclick="filterByCategory('Business Intelligence Developer')">Business Intelligence Developer</li>
              <li onclick="filterByCategory('Data Scientist')">Data Scientist</li>
            </ul>
          </li>
          <li class="has-submenu">
            ⚙️ Systems & Infrastructure
            <ul class="submenu">
              <li onclick="filterByCategory('Systems Engineer')">Systems Engineer</li>
              <li onclick="filterByCategory('Network Engineer')">Network Engineer</li>
              <li onclick="filterByCategory('Cloud Infrastructure Engineer')">Cloud Infrastructure Engineer</li>
              <li onclick="filterByCategory('Site Reliability Engineer')">Site Reliability Engineer</li>
              <li onclick="filterByCategory('IT Support Engineer')">IT Support Engineer</li>
              <li onclick="filterByCategory('Systems Administrator')">Systems Administrator</li>
            </ul>
          </li>
          <li class="has-submenu">
            🔒 Cybersecurity
            <ul class="submenu">
              <li onclick="filterByCategory('Security Engineer')">Security Engineer</li>
              <li onclick="filterByCategory('Information Security Analyst')">Information Security Analyst</li>
              <li onclick="filterByCategory('Penetration Tester')">Penetration Tester</li>
              <li onclick="filterByCategory('Cloud Security Specialist')">Cloud Security Specialist</li>
              <li onclick="filterByCategory('SOC Analyst')">SOC Analyst</li>
            </ul>
          </li>
          <li class="has-submenu">
            🧩 Specialized / Emerging Roles
            <ul class="submenu">
              <li onclick="filterByCategory('Blockchain Developer')">Blockchain Developer</li>
              <li onclick="filterByCategory('IoT Developer')">IoT Developer</li>
              <li onclick="filterByCategory('AR/VR Developer')">AR/VR Developer</li>
              <li onclick="filterByCategory('Platform Engineer')">Platform Engineer</li>
              <li onclick="filterByCategory('Automation Engineer')">Automation Engineer</li>
            </ul>
          </li>
        </ul>
      </div>
    </div>

    <!-- Healthcare -->
    <div class="category-wrapper">
      <div class="navbar-btn">Healthcare</div>
      <div class="category">
        <h2>Healthcare</h2>
        <ul>
          <li class="has-submenu">
            Medical Practitioners
            <ul class="submenu">
              <li onclick="filterByCategory('Doctor')">Doctor</li>
              <li onclick="filterByCategory('Nurse')">Nurse</li>
              <li onclick="filterByCategory('Surgeon')">Surgeon</li>
              <li onclick="filterByCategory('Anesthesiologist')">Anesthesiologist</li>
              <li onclick="filterByCategory('Psychiatrist')">Psychiatrist</li>
              <li onclick="filterByCategory('Dentist')">Dentist</li>
            </ul>
          </li>
          <li class="has-submenu">
            Allied Health Professions
            <ul class="submenu">
              <li onclick="filterByCategory('Physiotherapist')">Physiotherapist</li>
              <li onclick="filterByCategory('Occupational Therapist')">Occupational Therapist</li>
              <li onclick="filterByCategory('Radiographer')">Radiographer</li>
              <li onclick="filterByCategory('Pharmacist')">Pharmacist</li>
              <li onclick="filterByCategory('Speech-Language Pathologist')">Speech-Language Pathologist</li>
            </ul>
          </li>
          <li class="has-submenu">
            Healthcare Administration
            <ul class="submenu">
              <li onclick="filterByCategory('Medical Administrator')">Medical Administrator</li>
              <li onclick="filterByCategory('Hospital Administrator')">Hospital Administrator</li>
              <li onclick="filterByCategory('Health Services Manager')">Health Services Manager</li>
              <li onclick="filterByCategory('Medical Office Coordinator')">Medical Office Coordinator</li>
            </ul>
          </li>
          <li class="has-submenu">
            Public Health
            <ul class="submenu">
              <li onclick="filterByCategory('Epidemiologist')">Epidemiologist</li>
              <li onclick="filterByCategory('Health Educator')">Health Educator</li>
              <li onclick="filterByCategory('Public Health Analyst')">Public Health Analyst</li>
            </ul>
          </li>
        </ul>
      </div>
    </div>

    <!-- Business & Management -->
    <div class="category-wrapper">
      <div class="navbar-btn">Business & Management</div>
      <div class="category">
        <h2>Business & Management</h2>
        <ul>
          <li class="has-submenu">
            Project Management
            <ul class="submenu">
              <li onclick="filterByCategory('Project Manager')">Project Manager</li>
              <li onclick="filterByCategory('Program Manager')">Program Manager</li>
              <li onclick="filterByCategory('Scrum Master')">Scrum Master</li>
              <li onclick="filterByCategory('Agile Coach')">Agile Coach</li>
            </ul>
          </li>
          <li class="has-submenu">
            Operations & Strategy
            <ul class="submenu">
              <li onclick="filterByCategory('Operations Manager')">Operations Manager</li>
              <li onclick="filterByCategory('Supply Chain Manager')">Supply Chain Manager</li>
              <li onclick="filterByCategory('Business Operations Analyst')">Business Operations Analyst</li>
              <li onclick="filterByCategory('Strategic Planning Manager')">Strategic Planning Manager</li>
            </ul>
          </li>
          <li class="has-submenu">
            Human Resources
            <ul class="submenu">
              <li onclick="filterByCategory('HR Manager')">HR Manager</li>
              <li onclick="filterByCategory('Recruitment Specialist')">Recruitment Specialist</li>
              <li onclick="filterByCategory('HR Coordinator')">HR Coordinator</li>
              <li onclick="filterByCategory('Compensation Analyst')">Compensation Analyst</li>
            </ul>
          </li>
          <li class="has-submenu">
            Marketing & Sales
            <ul class="submenu">
              <li onclick="filterByCategory('Marketing Manager')">Marketing Manager</li>
              <li onclick="filterByCategory('Sales Director')">Sales Director</li>
              <li onclick="filterByCategory('Digital Marketing Specialist')">Digital Marketing Specialist</li>
              <li onclick="filterByCategory('Product Marketing Manager')">Product Marketing Manager</li>
            </ul>
          </li>
        </ul>
      </div>
    </div>

    <!-- Engineering -->
    <div class="category-wrapper">
      <div class="navbar-btn">Engineering</div>
      <div class="category">
        <h2>Engineering</h2>
        <ul>
          <li class="has-submenu">
            Mechanical Engineering
            <ul class="submenu">
              <li onclick="filterByCategory('Mechanical Engineer')">Mechanical Engineer</li>
              <li onclick="filterByCategory('HVAC Engineer')">HVAC Engineer</li>
              <li onclick="filterByCategory('Automotive Engineer')">Automotive Engineer</li>
              <li onclick="filterByCategory('Robotics Engineer')">Robotics Engineer</li>
            </ul>
          </li>
          <li class="has-submenu">
            Electrical Engineering
            <ul class="submenu">
              <li onclick="filterByCategory('Electrical Engineer')">Electrical Engineer</li>
              <li onclick="filterByCategory('Power Systems Engineer')">Power Systems Engineer</li>
              <li onclick="filterByCategory('Electronics Engineer')">Electronics Engineer</li>
              <li onclick="filterByCategory('Control Systems Engineer')">Control Systems Engineer</li>
            </ul>
          </li>
          <li class="has-submenu">
            Civil Engineering
            <ul class="submenu">
              <li onclick="filterByCategory('Civil Engineer')">Civil Engineer</li>
              <li onclick="filterByCategory('Structural Engineer')">Structural Engineer</li>
              <li onclick="filterByCategory('Construction Manager')">Construction Manager</li>
              <li onclick="filterByCategory('Geotechnical Engineer')">Geotechnical Engineer</li>
            </ul>
          </li>
          <li class="has-submenu">
            Chemical Engineering
            <ul class="submenu">
              <li onclick="filterByCategory('Chemical Engineer')">Chemical Engineer</li>
              <li onclick="filterByCategory('Process Engineer')">Process Engineer</li>
              <li onclick="filterByCategory('Environmental Engineer')">Environmental Engineer</li>
            </ul>
          </li>
          <li class="has-submenu">
            Software Engineering
            <ul class="submenu">
              <li onclick="filterByCategory('Embedded Systems Engineer')">Embedded Systems Engineer</li>
              <li onclick="filterByCategory('QA Engineer')">QA Engineer</li>
            </ul>
          </li>
        </ul>
      </div>
    </div>

    <!-- Education & Training -->
    <div class="category-wrapper">
      <div class="navbar-btn">Education & Training</div>
      <div class="category">
        <h2>Education & Training</h2>
        <ul>
          <li class="has-submenu">
            Teaching
            <ul class="submenu">
              <li onclick="filterByCategory('Primary School Teacher')">Primary School Teacher</li>
              <li onclick="filterByCategory('Secondary School Teacher')">Secondary School Teacher</li>
              <li onclick="filterByCategory('College Professor')">College Professor</li>
              <li onclick="filterByCategory('Special Education Teacher')">Special Education Teacher</li>
              <li onclick="filterByCategory('ESL Teacher')">ESL Teacher</li>
            </ul>
          </li>
          <li class="has-submenu">
            Educational Support
            <ul class="submenu">
              <li onclick="filterByCategory('Educational Counselor')">Educational Counselor</li>
              <li onclick="filterByCategory('Instructional Designer')">Instructional Designer</li>
              <li onclick="filterByCategory('Academic Advisor')">Academic Advisor</li>
              <li onclick="filterByCategory('Learning Specialist')">Learning Specialist</li>
            </ul>
          </li>
          <li class="has-submenu">
            Corporate Training
            <ul class="submenu">
              <li onclick="filterByCategory('Corporate Trainer')">Corporate Trainer</li>
              <li onclick="filterByCategory('Training Manager')">Training Manager</li>
              <li onclick="filterByCategory('Learning & Development Specialist')">Learning & Development Specialist</li>
              <li onclick="filterByCategory('E-learning Developer')">E-learning Developer</li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Results Container -->
  <div id="category-results" style="display: none; margin-top: 70px;">
    <button class="back-to-categories" onclick="showCategories()">⬅ Back to Categories</button>
    <div id="portfolios-display"></div>
  </div>
</div>

<p id="pp" style="display: none;">No results found!!!</p>

<script>
// Prevent default click behavior on parent menu items
document.addEventListener('DOMContentLoaded', function() {
  const parentItems = document.querySelectorAll('.has-submenu');
  parentItems.forEach(item => {
    item.addEventListener('click', function(e) {
      e.stopPropagation();
    });
  });
});

function filterByCategory(category) {
  // Hide category menu
  document.querySelector('.nav-and-categories').style.display = 'none';
  document.getElementById('category-results').style.display = 'block';
  document.getElementById('pp').style.display = 'none';
  
  // Fetch filtered portfolios
  fetch(`category_filter.php?category=${encodeURIComponent(category)}`)
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        displayCategoryResults(data.portfolios, data.user_likes);
      } else {
        console.error('Filter failed:', data.error);
      }
    })
    .catch(error => {
      console.error('Error fetching category results:', error);
    });
}

function displayCategoryResults(portfolios, userLikes) {
  const resultsContainer = document.getElementById('portfolios-display');
  
  if (portfolios.length === 0) {
    document.getElementById('pp').style.display = 'block';
    resultsContainer.innerHTML = '';
    return;
  }
  
  document.getElementById('pp').style.display = 'none';
  let html = '<div id="cat-portfolios-container">';
  
  portfolios.forEach(portfolio => {
    const isLiked = userLikes.includes(portfolio.pid);
    const likeClass = isLiked ? 'liked' : '';
    
    html += `
      <div class="cat-portfolio-item">
        <div class="cat-iframe-thumb">
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
        <button class="cat-expand-btn" onclick="fopenCat('${escapeHtml(portfolio.paddress)}')">⛶</button>
        <span class="cat-tags">
          ${escapeHtml(portfolio.tags)}
        </span>
        <div class="cat-des-container">
          ${escapeHtml(portfolio.description)}
        </div>
        <div class="cat-ratings-display">
          Ratings: <span class="cat-ratings-value" id="cat-rating-${portfolio.pid}">${escapeHtml(portfolio.ratings)}</span>
          <button class="cat-like-btn ${likeClass}" 
                  data-portfolio-id="${portfolio.pid}" 
                  onclick="toggleCatLike(${portfolio.pid})">
            ❤
          </button>
        </div>
      </div>
    `;
  });
  
  html += '</div>';
  
  // Add fullscreen viewer
  html += `
    <div id="cat-fullscreen" style="display: none;">
      <div class="cat-iframe-full">
        <iframe id="cat-fullscreen-iframe" src="" title="Portfolio" frameborder="0" scrolling="yes"></iframe>
      </div>
      <button class="cat-fullscreen-btn cat-visit-btn" onclick="visitCatPortfolio()">visit</button>
      <button class="cat-fullscreen-btn cat-close-btn" onclick="closeCatFullscreen()">X</button>
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

function showCategories() {
  document.querySelector('.nav-and-categories').style.display = 'flex';
  document.getElementById('category-results').style.display = 'none';
  document.getElementById('pp').style.display = 'none';
}

let currentCatPortfolioUrl = '';

function fopenCat(url) {
  currentCatPortfolioUrl = url;
  const fs = document.getElementById('cat-fullscreen');
  const iframe = document.getElementById('cat-fullscreen-iframe');
  iframe.src = url;
  fs.style.display = 'block';
  document.body.style.overflow = 'hidden';
}

function closeCatFullscreen() {
  const fs = document.getElementById('cat-fullscreen');
  const iframe = document.getElementById('cat-fullscreen-iframe');
  fs.style.display = 'none';
  iframe.src = '';
  document.body.style.overflow = '';
}

function visitCatPortfolio() {
  if (currentCatPortfolioUrl) {
    window.open(currentCatPortfolioUrl);
  }
}

function toggleCatLike(portfolioId) {
  const likeBtn = document.querySelector(`.cat-like-btn[data-portfolio-id="${portfolioId}"]`);
  const ratingSpan = document.getElementById(`cat-rating-${portfolioId}`);
  
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
</script>
</body>
</html>
