// Web upload tag variables
const tagInputWeb = document.getElementById('tag-input-web');
const addTagBtnWeb = document.getElementById('add-tag-btn-web');
const tagsContainerWeb = document.getElementById('tags-container-web');

// Host upload tag variables
const tagInputHost = document.getElementById('tag-input-host');
const addTagBtnHost = document.getElementById('add-tag-btn-host');
const tagsContainerHost = document.getElementById('tags-container-host');

// Function to add a tag
function addTag(tagInput, tagsContainer) {
    const tagValue = tagInput.value.trim();
    const maxTags = 4;  // Maximum number of tags

    // Check if the max tag limit is reached
    const currentTagCount = tagsContainer.querySelectorAll('.tag').length;
    if (currentTagCount >= maxTags) {
        alert(`You can only add up to ${maxTags} tags.`);
        return;
    }

    // Check if the input is not empty and not already added
    if (tagValue && !isTagExist(tagValue, tagsContainer)) {
        // Create tag element
        const tagElement = document.createElement('div');
        tagElement.classList.add('tag');
        tagElement.innerHTML = `${tagValue} <span class="tag-close" onclick="removeTag(this)">x</span>`;

        // Add tag to the container
        tagsContainer.appendChild(tagElement);

        // Clear the input field
        tagInput.value = '';
        
        // Update hidden field if it's host upload
        updateHiddenTagsField();
    } else {
        alert('Please enter a valid tag or this tag already exists!');
    }
}

// Function to check if tag already exists
function isTagExist(tag, tagsContainer) {
    const tags = tagsContainer.querySelectorAll('.tag');
    return Array.from(tags).some(t => t.innerText.includes(tag));
}

// Function to remove a tag
function removeTag(element) {
    element.parentElement.remove();
    updateHiddenTagsField();
}

// Function to update hidden tags field for host upload
function updateHiddenTagsField() {
    const tagsHiddenHost = document.getElementById('tags-hidden-host');
    if (tagsHiddenHost) {
        const tags = tagsContainerHost.querySelectorAll('.tag');
        const tagValues = Array.from(tags).map(tag => {
            return tag.innerText.replace(' x', '').trim();
        });
        tagsHiddenHost.value = tagValues.join('#');
    }
    
    const tagsHiddenWeb = document.getElementById('tags-hidden-web');
    if (tagsHiddenWeb) {
        const tags = tagsContainerWeb.querySelectorAll('.tag');
        const tagValues = Array.from(tags).map(tag => {
            return tag.innerText.replace(' x', '').trim();
        });
        tagsHiddenWeb.value = tagValues.join('#');
    }
}

// Add tag when the web upload button is clicked
addTagBtnWeb.addEventListener('click', function() {
    addTag(tagInputWeb, tagsContainerWeb);
});

// Add tag when the host upload button is clicked
addTagBtnHost.addEventListener('click', function() {
    addTag(tagInputHost, tagsContainerHost);
});

// Add tag when Enter key is pressed for web upload
tagInputWeb.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        addTag(tagInputWeb, tagsContainerWeb);
    }
});

// Add tag when Enter key is pressed for host upload
tagInputHost.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        addTag(tagInputHost, tagsContainerHost);
    }
});
