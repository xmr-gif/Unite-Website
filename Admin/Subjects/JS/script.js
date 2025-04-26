// Get references to elements
const deleteBtn = document.getElementById("delete-button");
const checkboxes = document.querySelectorAll('.checkbox-button'); // Added missing dot for class selector

// Add event listener to all checkboxes
checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', updateDeleteButtonState);
});

function updateDeleteButtonState() {
    const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

    if (anyChecked) {
        deleteBtn.classList.remove('opacity-0', 'pointer-events-none');
        deleteBtn.classList.add('opacity-100');
    } else {
        // Hide and disable delete button
        deleteBtn.classList.add('opacity-0', 'pointer-events-none');
        deleteBtn.classList.remove('opacity-100');
    }
}


updateDeleteButtonState();

// Optional: Add confirmation for delete action
deleteBtn.addEventListener('click', function(e) {
    if (!Array.from(checkboxes).some(checkbox => checkbox.checked)) {
        e.preventDefault();
        alert('Please select at least one subject to delete');
    }
});
