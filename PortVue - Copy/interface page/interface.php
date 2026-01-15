<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login page/login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];

// Database connection
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "portvuedb";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user has a portfolio
$has_portfolio = false;
$portfolio_data = null;

$stmt = $conn->prepare("SELECT pid, paddress, tags, description, ratings FROM portfolios WHERE uid = ? LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $has_portfolio = true;
    $portfolio_data = $result->fetch_assoc();
}

$stmt->close();
$conn->close();

// Get upload messages
$upload_success = isset($_SESSION['upload_success']) ? $_SESSION['upload_success'] : '';
$upload_error = isset($_SESSION['upload_error']) ? $_SESSION['upload_error'] : '';

// Get delete messages
$delete_success = isset($_SESSION['delete_success']) ? $_SESSION['delete_success'] : '';
$delete_error = isset($_SESSION['delete_error']) ? $_SESSION['delete_error'] : '';

// Clear messages after displaying
if ($upload_success) {
    unset($_SESSION['upload_success']);
}
if ($upload_error) {
    unset($_SESSION['upload_error']);
}
if ($delete_success) {
    unset($_SESSION['delete_success']);
}
if ($delete_error) {
    unset($_SESSION['delete_error']);
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PortVue</title>
    <link rel="stylesheet" href="i.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="portfolio_preview.css?v=<?php echo time(); ?>">
    <link rel="icon" href="icon1.png" type="image/png">
  </head>
  <body id="body_part">

    <div id="section1">
      
      <a href="" id="logo">
        <img src="icon1.png" alt="Logo" id="icon">
      </a>

      <div id="n">      
        <div id="navcontainer">
          <a href="#section1" class="nav_icons" onclick="home_showing_part()">Home</a>
          <a href="#section4" class="nav_icons">Categories</a>
          <a href="#section3" class="nav_icons">Top Picks</a>
          <a href="../classrooms/clroom.html" class="nav_icons" target="_blank">Classrooms</a>
          <div class="profile-container">
            <a href="#" class="nav_icons">Pro👤file</a>
            <div class="profile-popup">
              <p><strong>Name : </strong> <?php echo htmlspecialchars($user_name); ?></p>
              <a href="#" id="uploads" onclick="show_uploads()">Your Uploads</a>
              <a href="logout.php" class="logout-btn">Logout</a>
            </div>
          </div>
        </div>
      </div>

      <div id="search-container">
        <input type="text" id="search-bar" placeholder="Search...">
        <button type="submit" id="search-button" onclick="hiding_part()">🔍</button>
      </div>

      <div id="hiding_part1">
        <div id="main_title">
          <h1>PortVue</h1>
          <p>🌐 "Bridging skills with endless possibilities"</p>
          <a href="#section2" id="get-started">Get Started</a>
        </div>
      </div>
    </div>
  <div id="hiding_part2">  
    <div id="section2">
      <div id="des_container">
        <h2 id="destitle"></h2>
          <p id="des">
            Upload and showcase your portfolios, projects, and creative works. Whether it's a professional milestone, a personal project, or an artistic endeavor, PortVUe is the place to highlight what you've accomplished and let your work speak for itself.
          </p>
          <br>
          <a href="#section3" id="get-started">Explore</a><span>  <b> / </b>  </span><a href="#section1" onclick="show_uploads()" id="get-started">Upload</a>
          <br><br>
          <a href="#search-bar" id="get-started">On the hunt for something precise 🔍</a>
      </div>
    </div>

    <div id="section3">
      <br>
      <h1>🌟 TOP PICKS</h1>
      <div class="display">
        <iframe src="../top_iframe/topif.php?v=<?php echo time(); ?>" width="100%" height="100%" style="border: 0;"></iframe>
      </div>
    </div>
    <div id="section4">
      <h1>🗂️Categories</h1>
      <div class="display">
        <iframe src="../categories_iframe/catif.php?v=<?php echo time(); ?>" width="100%" height="100%" style="border: 0;"></iframe>
      </div>
    </div>
  </div>
    <div id="contact_footer">
      <div id="sub-container">
        <h1>Subscribe to receive updates!</h1>
        <form action="">
          <input type="email" id="sub-bar" placeholder="Enter Email Adress...">
        <button type="submit" id="sub-button">NOTIFY ME!🔔</button>
        </form>
        <br><br><br><br><br><br><br><br><br><br>
        <span>Copyright &copy; PortVue 2025</span>
      </div>
    </div>
    <div id="showing_results_part" style="display: none;">
      
    </div>

    <div id="ur_uploads" style="display: none;">
      <span id="portvue_heading" style="display: <?php echo $has_portfolio ? 'block' : 'none'; ?>;">Your PortVue😊</span>
      <button id="close_x" onclick="close_x()">x</button>
      <div id="new_upload" style="display: <?php echo $has_portfolio ? 'none' : 'block'; ?>;">
      <button id="swap_host1">Already have a Website</button>
      &nbsp;<span style="margin-top: 12px; position:absolute; left:49.5%">/</span>&nbsp;
      <button id="swap_host2">Host Your Portfolio</button>
      <br> <br>
        <div id="web_upload">
          <br>
          <p>Drop your Portfolio</p>
          <form id="website-link-form" action="upload_website.php" method="POST">
            <input type="url" id="portfolio_address" name="website_link" placeholder="Your Website Link!!!" required></input>
            <br><br> <small style="color: #249595; font-size: 15px; font-weight: 100; opacity: 0.8;">*** Make Sure your website doesn't have X-frame Restrict***</small><br>
            <div class="container">
              <div class="tags-container" id="tags-container-web">
                <span style="margin-left: -70px;">Tags : </span>
              </div>
              <div class="input-container">
                <input type="text" id="tag-input-web" placeholder="Enter a tag" />
                <button type="button" id="add-tag-btn-web">Add Tag</button>
              </div>
            </div>
            <input type="hidden" id="tags-hidden-web" name="tags" value="">
            <textarea id="search-bar" name="description" style="width: 20vw; height: 10vh; margin-top: -30px;" placeholder="Description"></textarea>
            <br> <br>
            <button type="submit" id="get-started">Present Your Craft</button>
          </form>
        </div>

        <div id="host_upload" style="display: none;">
          <br>
          <p>Upload your Portfolio Files</p>
          <form id="portfolio-upload-form" action="upload_portfolio.php" method="POST" enctype="multipart/form-data">
            <div style="margin: 20px 0;">
              <label for="portfolio-files" style="color: #249595; font-size: 16px;">Select Portfolio Folder:</label>
              <input type="file" id="portfolio-files" name="portfolio_files[]" webkitdirectory directory multiple required style="display: block; margin: 10px 0; padding: 10px; width: 20vw;">
              <small style="color: #249595; font-size: 15px; font-weight: 100; opacity: 0.8;">*** Select your portfolio folder. Main file must be named index.html in the root of the folder ***</small>
            </div>
            <br>
            <?php if ($upload_error): ?>
              <div style="background-color: #f44336; color: white; padding: 8px; margin: 10px 0; border-radius: 5px; font-size: 12px; text-align: center;">
                <?php echo htmlspecialchars($upload_error); ?>
              </div>
            <?php endif; ?>
            <div class="container">
              <div class="tags-container" id="tags-container-host">
                <span style="margin-left: -70px;">Tags : </span>
              </div>
              <div class="input-container">
                <input type="text" id="tag-input-host" placeholder="Enter a tag" />
                <button type="button" id="add-tag-btn-host">Add Tag</button>
              </div>
            </div>
            <input type="hidden" id="tags-hidden-host" name="tags" value="">
            <textarea id="search-bar" name="description" style="width: 20vw; height: 10vh; margin-top: -30px;" placeholder="Description"></textarea>
            <br> <br>
            <div style="width: 100%; text-align: center;">
              <button type="submit" id="get-started">Present Your Craft</button>
            </div>
          </form>
        </div>
      </div>

      <div id="edit_upload" style="display: <?php echo $has_portfolio ? 'block' : 'none'; ?>;">
        <?php if ($has_portfolio): ?>
        <div id="portfolio_preview">
          <br>
          <div class="iframe-thumb-edit">
            <iframe src="<?php echo htmlspecialchars($portfolio_data['paddress']); ?>" title="Portfolio preview" loading="lazy" sandbox="allow-same-origin allow-scripts allow-forms" referrerpolicy="no-referrer" scrolling="no" style="overflow:hidden;"></iframe>
          </div>
          <h6><?php echo htmlspecialchars($user_name); ?></h6>
          <button id="visit-btn" onclick="openPortfolio()">visit</button>
          <div id="tags-display">
            <?php echo htmlspecialchars($portfolio_data['tags']); ?>
          </div>
          <div id="des-container-edit">
            <?php echo htmlspecialchars($portfolio_data['description']); ?>
          </div>
          <div id="ratings-display">
            <?php echo htmlspecialchars($portfolio_data['ratings']); ?> ratings
          </div>
          <form id="delete-form" action="delete_portfolio.php" method="POST" style="text-align: center;">
            <button type="button" id="delete-btn" onclick="confirmDelete()">Delete Portfolio</button>
          </form>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <script src="scroll.js?v=<?php echo time(); ?>"></script>
    <script src="search.js?v=<?php echo time(); ?>"></script>
    <script src="tags.js?v=<?php echo time(); ?>"></script>
    <script src="swap.js?v=<?php echo time(); ?>"></script>
    <script>
      // Open portfolio in new tab
      function openPortfolio() {
        const portfolioUrl = '<?php echo $has_portfolio ? addslashes($portfolio_data['paddress']) : ''; ?>';
        if (portfolioUrl) {
          window.open(portfolioUrl, '_blank');
        }
      }
      
      // Confirm delete with user
      function confirmDelete() {
        if (confirm('Are you sure you want to delete your portfolio? This action cannot be undone.')) {
          document.getElementById('delete-form').submit();
        }
      }
      
      // Update tags before form submission for website link
      document.getElementById('website-link-form').addEventListener('submit', function(e) {
        const tagsContainerWeb = document.getElementById('tags-container-web');
        const tagsHiddenWeb = document.getElementById('tags-hidden-web');
        const tags = tagsContainerWeb.querySelectorAll('.tag');
        const tagValues = Array.from(tags).map(tag => {
          return tag.innerText.replace(' x', '').trim();
        });
        tagsHiddenWeb.value = tagValues.map(tag => '#' + tag).join(' ');
      });
      
      // Update tags before form submission for portfolio upload
      document.getElementById('portfolio-upload-form').addEventListener('submit', function(e) {
        const tagsContainerHost = document.getElementById('tags-container-host');
        const tagsHiddenHost = document.getElementById('tags-hidden-host');
        const tags = tagsContainerHost.querySelectorAll('.tag');
        const tagValues = Array.from(tags).map(tag => {
          return tag.innerText.replace(' x', '').trim();
        });
        tagsHiddenHost.value = tagValues.map(tag => '#' + tag).join(' ');
      });
    </script>
    <?php if ($upload_success): ?>
    <script>
      alert('<?php echo addslashes($upload_success); ?>');
    </script>
    <?php endif; ?>
    <?php if ($upload_error): ?>
    <script>
      window.addEventListener('load', function() {
        document.getElementById('ur_uploads').style.display = 'block';
        document.getElementById('swap_host2').click();
      });
    </script>
    <?php endif; ?>
    <?php if ($delete_success): ?>
    <script>
      alert('<?php echo addslashes($delete_success); ?>');
    </script>
    <?php endif; ?> 
    <?php if ($delete_error): ?>
    <script>
      alert('<?php echo addslashes($delete_error); ?>');
    </script>
    <?php endif; ?>
  </body>
</html>