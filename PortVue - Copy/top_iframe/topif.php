<?php
session_start();

// Database configuration
$host = 'localhost';
$db_name = 'portvuedb';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create portfolio_likes table if it doesn't exist
$create_table_sql = "CREATE TABLE IF NOT EXISTS portfolio_likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    portfolio_id INT NOT NULL,
    user_id INT NOT NULL,
    liked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_like (portfolio_id, user_id),
    FOREIGN KEY (portfolio_id) REFERENCES portfolios(pid) ON DELETE CASCADE
)";
$conn->query($create_table_sql);

// Fetch all portfolios sorted by ratings (high to low)
$sql = "SELECT * FROM portfolios ORDER BY ratings DESC";
$result = $conn->query($sql);

$portfolios = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $portfolios[] = $row;
    }
}

// Get user's liked portfolios if logged in
$user_likes = [];
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $likes_sql = "SELECT portfolio_id FROM portfolio_likes WHERE user_id = ?";
    $stmt = $conn->prepare($likes_sql);
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $likes_result = $stmt->get_result();
        while($like = $likes_result->fetch_assoc()) {
            $user_likes[] = $like['portfolio_id'];
        }
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Top Picks</title>
  <link rel="stylesheet" href="top.css?v=3" />
</head>
<body>
  
  <div id="portfolios-container">
    <?php if (count($portfolios) > 0): ?>
      <?php foreach ($portfolios as $portfolio): ?>
        <div class="portfolio-item">
          <div class="iframe-thumb">
            <iframe src="<?php echo htmlspecialchars($portfolio['paddress']); ?>" title="Portfolio preview" loading="lazy" sandbox="allow-same-origin allow-scripts allow-forms" referrerpolicy="no-referrer" scrolling="no" style="overflow:hidden;"></iframe>
          </div>
          <h6><?php echo htmlspecialchars($portfolio['uname']); ?></h6>
          <button class="expand-btn" onclick="fopen('<?php echo htmlspecialchars($portfolio['paddress'], ENT_QUOTES); ?>')">⛶</button>
          <span class="tags">
            <?php echo htmlspecialchars($portfolio['tags']); ?>
          </span>
          <div class="des-container">
            <?php echo htmlspecialchars($portfolio['description']); ?>
          </div>
          <div class="ratings-display">
            Ratings: <span class="ratings-value" id="rating-<?php echo $portfolio['pid']; ?>"><?php echo htmlspecialchars($portfolio['ratings']); ?></span>
            <?php 
            $is_liked = in_array($portfolio['pid'], $user_likes);
            $like_class = $is_liked ? 'liked' : '';
            ?>
            <button class="like-btn <?php echo $like_class; ?>" 
                    data-portfolio-id="<?php echo $portfolio['pid']; ?>" 
                    onclick="toggleLike(<?php echo $portfolio['pid']; ?>)">
              ❤
            </button>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div id="no-portfolios">
        <h2>No PortVues Found!</h2>
      </div>
    <?php endif; ?>
  </div>

  <div id="fullscreen" style="display: none;">
    <div class="iframe-full">
      <iframe id="fullscreen-iframe" src="" title="Portfolio" frameborder="0" scrolling="yes"></iframe>
    </div>
    <button class="fullscreen-btn visit-btn" onclick="visitPortfolio()">visit</button>
    <button class="fullscreen-btn close-btn" onclick="close_x()">X</button>
  </div>

  <script>
    let currentPortfolioUrl = '';
    
    function fopen(url) {
      currentPortfolioUrl = url;
      const fs = document.getElementById('fullscreen');
      const iframe = document.getElementById('fullscreen-iframe');
      iframe.src = url;
      fs.style.display = 'block';
      document.body.style.overflow = 'hidden';
    }
    
    function close_x() {
      const fs = document.getElementById('fullscreen');
      const iframe = document.getElementById('fullscreen-iframe');
      fs.style.display = 'none';
      iframe.src = '';
      document.body.style.overflow = '';
    }
    
    function visitPortfolio() {
      if (currentPortfolioUrl) {
        window.open(currentPortfolioUrl);
      }
    }
    
    function toggleLike(portfolioId) {
      const likeBtn = document.querySelector(`.like-btn[data-portfolio-id="${portfolioId}"]`);
      const ratingSpan = document.getElementById(`rating-${portfolioId}`);
      
      // Send AJAX request
      const formData = new FormData();
      formData.append('portfolio_id', portfolioId);
      
      fetch('handle_like.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Update rating display
          ratingSpan.textContent = data.ratings;
          
          // Toggle like button state
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
