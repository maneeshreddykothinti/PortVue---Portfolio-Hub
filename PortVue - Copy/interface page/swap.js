document.getElementById("swap_host1").addEventListener("click", function() {
    // Show web_upload and hide host_upload
    document.getElementById("web_upload").style.display = "block";
    document.getElementById("host_upload").style.display = "none";

    // Set the active button's background color to transparent and non-active button's color to #3a3d41
    document.getElementById("swap_host1").style.backgroundColor = "transparent";
    document.getElementById("swap_host2").style.backgroundColor = "#3a3d41";
});

document.getElementById("swap_host2").addEventListener("click", function() {
    // Show host_upload and hide web_upload
    document.getElementById("web_upload").style.display = "none";
    document.getElementById("host_upload").style.display = "block";

    // Set the active button's background color to transparent and non-active button's color to #3a3d41
    document.getElementById("swap_host2").style.backgroundColor = "transparent";
    document.getElementById("swap_host1").style.backgroundColor = "#3a3d41";
});
