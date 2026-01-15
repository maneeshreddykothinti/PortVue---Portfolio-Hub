function showCreate()
{
    document.getElementById("createSection").style.display="block";
    document.getElementById("joinSection").style.display="none";
    document.getElementById("opt1").style.display="none";
    document.getElementById("opt2").style.display="none";
    document.getElementById("opt3").style.display="none";
    document.getElementById("login_hub").style.display="none";
}

function showJoin() 
{
    document.getElementById("createSection").style.display="none";
    document.getElementById("joinSection").style.display="block";
    document.getElementById("opt1").style.display="none";
    document.getElementById("opt2").style.display="none";
    document.getElementById("opt3").style.display="none";
    document.getElementById("login_hub").style.display="none";
}

function home()
{
    // Clear success message if exists
    const successMsg = document.getElementById('successMessage');
    if (successMsg) {
        successMsg.remove();
    }
    
    // Clear delete button container
    const deleteButtonContainer = document.getElementById('delete_button_container');
    if (deleteButtonContainer) {
        deleteButtonContainer.innerHTML = '';
    }
    
    // Reset portfolio form
    const portfolioForm = document.getElementById('portfolioForm');
    if (portfolioForm) {
        portfolioForm.style.display = 'flex';
        portfolioForm.style.flexDirection = 'column';
        portfolioForm.style.alignItems = 'center';
        portfolioForm.reset();
    }
    
    // Clear selected files
    selectedPortfolioFiles = [];
    if (document.getElementById('fileList')) {
        document.getElementById('fileList').style.display = 'none';
    }
    if (document.getElementById('previewIframe')) {
        document.getElementById('previewIframe').style.display = 'none';
    }
    if (document.getElementById('show_publish')) {
        document.getElementById('show_publish').style.display = 'none';
    }
    
    document.getElementById("opt1").style.display="block";
    document.getElementById("opt2").style.display="block";
    document.getElementById("opt3").style.display="block";
    document.getElementById("createSection").style.display="none";
    document.getElementById("joinSection").style.display="none";
    document.getElementById("login_hub").style.display="none";
    document.getElementById("AdminHubArea").style.display="none";
    document.getElementById("classroomArea").style.display="none";
}

function show_classroom()
{
    document.getElementById("opt1").style.display="none";
    document.getElementById("opt2").style.display="none";
    document.getElementById("opt3").style.display="none";
    document.getElementById("createSection").style.display="none";
    document.getElementById("joinSection").style.display="none";
    document.getElementById("classroomArea").style.display="block";
    document.getElementById("login_hub").style.display="none";
}

function creating() {
    const createForm = document.getElementById('createForm');
    if (!createForm.checkValidity()) {
        alert("Please fill in all required fields.");
        return;
    }
    
    const inputs = createForm.querySelectorAll('input');
    const username = inputs[0].value.trim();
    const ccode = inputs[1].value.trim();
    const pass = inputs[2].value.trim();
    
    const formData = new FormData();
    formData.append('username', username);
    formData.append('ccode', ccode);
    formData.append('pass', pass);
    
    fetch('create_classroom.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.msg + '\nClass Code: ' + data.ccode);
            showJoin();
        } else {
            alert('Error: ' + data.msg);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while creating classroom');
    });
}

function joining() {
    const joinForm = document.getElementById('joinForm');
    if (!joinForm.checkValidity()) {
        alert("Please fill in all required fields.");
        return;
    }
    
    const inputs = joinForm.querySelectorAll('input');
    const ccode = inputs[0].value.trim();
    const pass = inputs[1].value.trim();
    
    const formData = new FormData();
    formData.append('ccode', ccode);
    formData.append('pass', pass);
    
    fetch('join_classroom.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Ask for roll number to check if already submitted
            const rollNumber = prompt('Please enter your Roll Number:');
            if (!rollNumber) {
                alert('Roll number is required to join the classroom');
                return;
            }
            
            // Check if this roll number already has a submission
            checkAndShowAppropriateScreen(rollNumber.trim());
        } else {
            alert('Error: ' + data.msg);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while joining classroom');
    });
}

function checkAndShowAppropriateScreen(rollNumber) {
    const checkData = new FormData();
    checkData.append('roll_number', rollNumber);
    
    fetch('check_submission.php', {
        method: 'POST',
        body: checkData
    })
    .then(response => response.json())
    .then(checkResult => {
        if (checkResult.success && checkResult.exists) {
            // Student already submitted - show success page
            alert('Welcome back! You have already submitted a project.');
            showSuccessStateOnJoin(rollNumber);
        } else {
            // No previous submission - show upload form
            alert('Welcome! Please submit your project.');
            show_classroom_with_roll(rollNumber);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // If check fails, show upload form
        alert('Welcome! Please submit your project.');
        show_classroom_with_roll(rollNumber);
    });
}

function show_classroom_with_roll(rollNumber) {
    show_classroom();
    // Pre-fill roll number
    const portfolioForm = document.getElementById('portfolioForm');
    const rollInput = portfolioForm.querySelector('input[type="text"]');
    if (rollInput) {
        rollInput.value = rollNumber;
    }
}

function showSuccessStateOnJoin(rollNumber) {
    document.getElementById("opt1").style.display="none";
    document.getElementById("opt2").style.display="none";
    document.getElementById("opt3").style.display="none";
    document.getElementById("createSection").style.display="none";
    document.getElementById("joinSection").style.display="none";
    document.getElementById("classroomArea").style.display="block";
    document.getElementById("login_hub").style.display="none";
    
    // Hide form and show success message immediately
    const portfolioForm = document.getElementById('portfolioForm');
    portfolioForm.style.display = 'none';
    
    const classroomArea = document.getElementById('classroomArea');
    
    // Remove any existing success message
    const existingMsg = document.getElementById('successMessage');
    if (existingMsg) {
        existingMsg.remove();
    }
    
    const successDiv = document.createElement('div');
    successDiv.id = 'successMessage';
    successDiv.style.cssText = 'text-align: center; padding: 40px; background: rgba(36, 149, 149, 0.1); border-radius: 10px; margin: 20px 0;';
    successDiv.innerHTML = `
        <h3 style="color: #249595; margin-bottom: 20px;">✅ Project Successfully Published!</h3>
        <p style="font-size: 16px; color: #666; margin-bottom: 30px;">Your project has been uploaded and is now available for review.</p>
        <button onclick="resubmitProjectWithRoll('${rollNumber}')" style="padding: 12px 24px; background: #249595; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; margin-right: 10px;">
            🔄 Resubmit
        </button>
    `;
    
    const backButton = classroomArea.querySelector('#back_home');
    classroomArea.insertBefore(successDiv, backButton);
}


let selectedPortfolioFiles = [];

function handleFolderChange(event)
{
    const files = Array.from(event.target.files || []);
    selectedPortfolioFiles = files;

    const fileListDiv = document.getElementById("fileList");
    if (files.length === 0) {
        fileListDiv.style.display = "none";
        fileListDiv.innerHTML = "";
        return;
    }

    // Show a short list of files (first 20) for confirmation
    const items = files
        .slice(0, 20)
        .map(function(file) {
            var rel = file.webkitRelativePath || file.name;
            return "<div>" + rel + "</div>";
        })
        .join("");

    const extraCount = files.length > 20 ? (files.length - 20) : 0;
    fileListDiv.innerHTML = items + (extraCount > 0 ? ("<div>… and " + extraCount + " more</div>") : "");
    fileListDiv.style.display = "block";
}

function previewWebsite() {
  if (!selectedPortfolioFiles || selectedPortfolioFiles.length === 0) {
      alert("Please select a portfolio folder first.");
      return;
  }

  // Build quick lookup by relative path
  var pathToFile = {};
  selectedPortfolioFiles.forEach(function(file){
      var rel = file.webkitRelativePath || file.name;
      pathToFile[rel] = file;
  });

  // Find root index.html (at the root of selected folder)
  var indexFile = selectedPortfolioFiles.find(function(file){
      var rel = file.webkitRelativePath || file.name;
      // Normalize path separators to forward slash
      var normalizedPath = rel.replace(/\\/g, '/');
      var parts = normalizedPath.split('/');
      
      // For webkitRelativePath: should have exactly 2 parts (foldername/index.html)
      // For single file: should have 1 part (index.html)
      var isRoot = (parts.length === 2 || parts.length === 1);
      var isIndexHtml = parts[parts.length - 1].toLowerCase() === 'index.html';
      
      return isRoot && isIndexHtml;
  });

  if (!indexFile) {
      alert("Main file 'index.html' must be in the root of the selected folder.");
      return;
  }

  // Read index.html, CSS, JS, and convert images to base64
  Promise.all([
      readFileAsText(indexFile),
      readManyOfType(selectedPortfolioFiles, 'css'),
      readManyOfType(selectedPortfolioFiles, 'js'),
      readImagesAsDataURL(selectedPortfolioFiles)
  ]).then(function(results){
      var indexHtml = results[0];
      var cssBundle = results[1].join('\n\n');
      var jsBundle = results[2].join('\n\n');
      var imageMap = results[3]; // Map of filename to data URL

      // Replace image src attributes and CSS background images with data URLs
      Object.keys(imageMap).forEach(function(imagePath){
          var dataURL = imageMap[imagePath];
          // Get just the filename from the path
          var fileName = imagePath.split('/').pop();
          
          // Escape special regex characters in filename
          var escapeRegex = function(str) {
              return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
          };
          var escapedFileName = escapeRegex(fileName);
          
          // Replace various possible image src formats in HTML
          var regex1 = new RegExp('src=["\']' + escapedFileName + '["\']', 'gi');
          var regex2 = new RegExp('src=["\']./' + escapedFileName + '["\']', 'gi');
          var regex3 = new RegExp('src=["\']' + escapeRegex(imagePath) + '["\']', 'gi');
          
          indexHtml = indexHtml.replace(regex1, 'src="' + dataURL + '"');
          indexHtml = indexHtml.replace(regex2, 'src="' + dataURL + '"');
          indexHtml = indexHtml.replace(regex3, 'src="' + dataURL + '"');
          
          // Replace CSS background-image URLs
          var cssRegex1 = new RegExp('url\\(["\']?' + escapedFileName + '["\']?\\)', 'gi');
          var cssRegex2 = new RegExp('url\\(["\']?./' + escapedFileName + '["\']?\\)', 'gi');
          
          indexHtml = indexHtml.replace(cssRegex1, 'url("' + dataURL + '")');
          indexHtml = indexHtml.replace(cssRegex2, 'url("' + dataURL + '")');
          cssBundle = cssBundle.replace(cssRegex1, 'url("' + dataURL + '")');
          cssBundle = cssBundle.replace(cssRegex2, 'url("' + dataURL + '")');
      });

      var iframe = document.getElementById('previewIframe');
      var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

      iframe.style.display = 'block';
      iframeDoc.open();
      iframeDoc.write(indexHtml);
      if (cssBundle.trim().length > 0) {
          iframeDoc.write('<style>' + cssBundle + '</style>');
      }
      if (jsBundle.trim().length > 0) {
          iframeDoc.write('<script>' + jsBundle + '<' + '/script>');
      }
      iframeDoc.close();

      document.getElementById("show_publish").style.display = "block";
  }).catch(function(error){
      console.error('Preview error:', error);
      alert("Could not read selected files. Please try again.");
  });
}

function readFileAsText(file)
{
    return new Promise(function(resolve, reject){
        var reader = new FileReader();
        reader.onload = function(e){ resolve(String(e.target.result || '')); };
        reader.onerror = function(){ reject(); };
        reader.readAsText(file);
    });
}

function readManyOfType(files, ext)
{
    var ofType = files.filter(function(f){
        var name = (f.webkitRelativePath || f.name).toLowerCase();
        return name.endsWith('.' + ext);
    });
    return Promise.all(ofType.map(readFileAsText));
}

function readImagesAsDataURL(files) {
    // Supported image extensions
    var imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg', 'ico'];
    
    // Filter image files
    var imageFiles = files.filter(function(f){
        var name = (f.webkitRelativePath || f.name).toLowerCase();
        return imageExtensions.some(function(ext){
            return name.endsWith('.' + ext);
        });
    });
    
    // Read each image as data URL
    var promises = imageFiles.map(function(file){
        return new Promise(function(resolve, reject){
            var reader = new FileReader();
            reader.onload = function(e){
                var path = file.webkitRelativePath || file.name;
                resolve({ path: path, dataURL: e.target.result });
            };
            reader.onerror = function(){ reject(); };
            reader.readAsDataURL(file);
        });
    });
    
    // Return a map of path to data URL
    return Promise.all(promises).then(function(results){
        var imageMap = {};
        results.forEach(function(item){
            imageMap[item.path] = item.dataURL;
        });
        return imageMap;
    });
}

function published(event) {
    if (event) event.preventDefault();
    
    const portfolioForm = document.getElementById('portfolioForm');
    const rollInput = portfolioForm.querySelector('input[type="text"]');
    const rollNumber = rollInput.value.trim();
    
    if (!rollNumber) {
        alert('Please enter your roll number');
        return;
    }
    
    if (!selectedPortfolioFiles || selectedPortfolioFiles.length === 0) {
        alert('Please select portfolio files');
        return;
    }
    
    // First, check if student already has a submission
    const checkData = new FormData();
    checkData.append('roll_number', rollNumber);
    
    fetch('check_submission.php', {
        method: 'POST',
        body: checkData
    })
    .then(response => response.json())
    .then(checkResult => {
        if (checkResult.success && checkResult.exists) {
            // Student already has a submission - show warning
            const confirmResubmit = confirm(
                '⚠️ WARNING: You have already submitted a project for this classroom.\n\n' +
                'If you submit again:\n' +
                '• Your PREVIOUS project will be DELETED\n' +
                '• The NEW project will be uploaded\n\n' +
                'Do you want to continue and replace your previous submission?'
            );
            
            if (!confirmResubmit) {
                return; // User cancelled
            }
        }
        
        // Proceed with upload
        uploadProject(rollNumber);
    })
    .catch(error => {
        console.error('Error checking submission:', error);
        // If check fails, ask user if they want to proceed anyway
        const proceed = confirm('Could not verify existing submission. Do you want to proceed with upload?');
        if (proceed) {
            uploadProject(rollNumber);
        }
    });
}

function uploadProject(rollNumber) {
    const formData = new FormData();
    formData.append('roll_number', rollNumber);
    
    // Add all files with their relative paths
    selectedPortfolioFiles.forEach(file => {
        formData.append('files[]', file);
        const relativePath = file.webkitRelativePath || file.name;
        formData.append('relative_paths[]', relativePath);
    });
    
    // Show uploading message
    const publishBtn = document.getElementById('show_publish');
    const originalText = publishBtn.textContent;
    publishBtn.textContent = 'Uploading...';
    publishBtn.disabled = true;
    
    fetch('submit_project.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        publishBtn.textContent = originalText;
        publishBtn.disabled = false;
        
        if (data.success) {
            // Show success state with roll number
            showSuccessState(rollNumber);
        } else {
            alert('Error: ' + data.msg);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        publishBtn.textContent = originalText;
        publishBtn.disabled = false;
        alert('An error occurred while uploading project');
    });
}

function showSuccessState(rollNumber) {
    // Hide all upload controls
    const portfolioForm = document.getElementById('portfolioForm');
    portfolioForm.style.display = 'none';
    
    // Show success message
    const classroomArea = document.getElementById('classroomArea');
    
    // Remove any existing success message
    const existingMsg = document.getElementById('successMessage');
    if (existingMsg) {
        existingMsg.remove();
    }
    
    const successDiv = document.createElement('div');
    successDiv.id = 'successMessage';
    successDiv.style.cssText = 'text-align: center; padding: 40px; background: rgba(36, 149, 149, 0.1); border-radius: 10px; margin: 20px 0;';
    successDiv.innerHTML = `
        <h3 style="color: #249595; margin-bottom: 20px;">✅ Project Successfully Published!</h3>
        <p style="font-size: 16px; color: #666; margin-bottom: 30px;">Your project has been uploaded and is now available for review.</p>
        <button onclick="resubmitProjectWithRoll('${rollNumber}')" style="padding: 12px 24px; background: #249595; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; margin-right: 10px;">
            🔄 Resubmit
        </button>
    `;
    
    // Insert success message before the back button
    const backButton = classroomArea.querySelector('#back_home');
    classroomArea.insertBefore(successDiv, backButton);
}

function resubmitProject() {
    // Remove success message
    const successMsg = document.getElementById('successMessage');
    if (successMsg) {
        successMsg.remove();
    }
    
    // Show form again and reset it
    const portfolioForm = document.getElementById('portfolioForm');
    portfolioForm.style.display = 'flex';
    portfolioForm.style.flexDirection = 'column';
    portfolioForm.style.alignItems = 'center';
    portfolioForm.reset();
    selectedPortfolioFiles = [];
    document.getElementById('fileList').style.display = 'none';
    document.getElementById('previewIframe').style.display = 'none';
    document.getElementById('show_publish').style.display = 'none';
}

function resubmitProjectWithRoll(rollNumber) {
    // Remove success message
    const successMsg = document.getElementById('successMessage');
    if (successMsg) {
        successMsg.remove();
    }
    
    // Show form again and pre-fill roll number
    const portfolioForm = document.getElementById('portfolioForm');
    portfolioForm.style.display = 'flex';
    portfolioForm.style.flexDirection = 'column';
    portfolioForm.style.alignItems = 'center';
    
    const rollInput = portfolioForm.querySelector('input[type="text"]');
    if (rollInput) {
        rollInput.value = rollNumber;
    }
    selectedPortfolioFiles = [];
    document.getElementById('fileList').style.display = 'none';
    document.getElementById('previewIframe').style.display = 'none';
    document.getElementById('show_publish').style.display = 'none';
}

function showAdminHub()
{
    document.getElementById("createSection").style.display="none";
    document.getElementById("joinSection").style.display="none";
    document.getElementById("opt1").style.display="none";
    document.getElementById("opt2").style.display="none";
    document.getElementById("opt3").style.display="none";
    document.getElementById("login_hub").style.display="block";
    document.getElementById("AdminHubArea").style.display="none";
}

function admin_hub() {
    const hubLoginForm = document.getElementById('hub_login');
    if (!hubLoginForm.checkValidity()) {
        alert("Please fill in all required fields.");
        return;
    }
    
    const inputs = hubLoginForm.querySelectorAll('input');
    const username = inputs[0].value.trim();
    const ccode = inputs[1].value.trim();
    const pass = inputs[2].value.trim();
    
    const formData = new FormData();
    formData.append('username', username);
    formData.append('ccode', ccode);
    formData.append('pass', pass);
    
    fetch('admin_hub_login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Load projects
            loadProjects(data.ccode);
            document.getElementById("createSection").style.display="none";
            document.getElementById("joinSection").style.display="none";
            document.getElementById("opt1").style.display="none";
            document.getElementById("opt2").style.display="none";
            document.getElementById("opt3").style.display="none";
            document.getElementById("login_hub").style.display="none";
            document.getElementById("AdminHubArea").style.display="block";
        } else {
            alert('Error: ' + data.msg);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while accessing admin hub');
    });
}

function loadProjects(ccode) {
    fetch('get_projects.php?ccode=' + encodeURIComponent(ccode))
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayProjects(data.projects, data.ccode);
        } else {
            alert('Error loading projects: ' + data.msg);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while loading projects');
    });
}

function displayProjects(projects, ccode) {
    const container = document.getElementById('hub_container');
    const deleteButtonContainer = document.getElementById('delete_button_container');
    
    // Add delete classroom button OUTSIDE the grey container
    deleteButtonContainer.innerHTML = `
        <button onclick="deleteClassroom('${ccode}')" 
                style="padding: 10px 20px; background: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: bold;">
            🗑️ Delete Classroom
        </button>
    `;
    
    let html = '';
    
    if (projects.length === 0) {
        html = '<div style="text-align:center; padding:40px; color:#666;"><h3>No projects submitted yet</h3></div>';
        container.innerHTML = html;
        return;
    }
    
    html = '<div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; padding: 20px;">';
    
    projects.forEach(project => {
        html += `
            <div style="border: 2px solid #249595; border-radius: 10px; padding: 15px; background: rgba(36, 149, 149, 0.05);">
                <div style="width: 100%; height: 200px; border: 1px solid #ccc; border-radius: 8px; overflow: hidden; margin-bottom: 10px; background: white;">
                    <iframe src="${project.address}" 
                            style="width: 100%; height: 200px; border: none; transform: scale(0.5); transform-origin: 0 0; width: 200%; height: 400px;" 
                            sandbox="allow-same-origin allow-scripts" 
                            scrolling="no"
                            onload="console.log('Iframe loaded: ${project.address}')"
                            onerror="console.error('Iframe error: ${project.address}')"></iframe>
                </div>
                <h6 style="margin: 10px 0; color: #249595; font-size: 16px;">Roll: ${project.roll}</h6>
                <div style="display: flex; gap: 10px; margin-top: 10px;">
                    <button onclick="openFullscreen('${project.address}')" 
                            style="flex: 1; padding: 8px; background: #249595; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
                        ⛶ View
                    </button>
                    <button onclick="downloadProject('${project.folder}', '${ccode}')" 
                            style="flex: 1; padding: 8px; background: #1a7070; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
                        ⬇ Download
                    </button>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    container.innerHTML = html;
    
    console.log('Projects displayed:', projects.length);
    console.log('Project URLs:', projects.map(p => p.address));
}

function openFullscreen(url) {
    const fullscreenDiv = document.createElement('div');
    fullscreenDiv.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.95); z-index: 10000; display: flex; flex-direction: column;';
    
    fullscreenDiv.innerHTML = `
        <div style="padding: 10px; background: #1a1a1a; display: flex; justify-content: space-between; align-items: center;">
            <button onclick="window.open('${url}', '_blank')" 
                    style="padding: 10px 20px; background: #249595; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
                Visit Site
            </button>
            <button onclick="this.parentElement.parentElement.remove()" 
                    style="padding: 10px 20px; background: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold;">
                ✕ Close
            </button>
        </div>
        <iframe src="${url}" style="flex: 1; border: none; width: 100%; background: white;"></iframe>
    `;
    
    document.body.appendChild(fullscreenDiv);
}

function downloadProject(folder, ccode) {
    window.location.href = 'download_project.php?folder=' + encodeURIComponent(folder) + '&ccode=' + encodeURIComponent(ccode);
}

function deleteClassroom(ccode) {
    const confirmDelete = confirm('⚠️ WARNING: This will permanently delete the classroom "' + ccode + '" and all student submissions. This action cannot be undone. Are you sure?');
    
    if (!confirmDelete) {
        return;
    }
    
    const doubleConfirm = confirm('This is your final confirmation. Delete classroom "' + ccode + '" and all its data?');
    
    if (!doubleConfirm) {
        return;
    }
    
    const formData = new FormData();
    formData.append('ccode', ccode);
    
    fetch('delete_classroom.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.msg);
            // Go back to home
            home();
        } else {
            alert('Error: ' + data.msg);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting classroom');
    });
}
