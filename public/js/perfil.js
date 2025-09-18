document.addEventListener('DOMContentLoaded', function() {
    const changePicBtn = document.getElementById('change-pic-btn');
    const uploadFormContainer = document.getElementById('upload-form-container');
    const cancelBtn = document.getElementById('cancel-upload-btn');
    const fileInput = document.getElementById('foto_perfil');
    const fileNameDisplay = document.getElementById('file-name-display');

    if (changePicBtn) {
        changePicBtn.addEventListener('click', function() {
            uploadFormContainer.style.display = 'block';
            this.style.display = 'none';
        });
    }

    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            uploadFormContainer.style.display = 'none';
            changePicBtn.style.display = 'block';
            // Reset file input
            fileInput.value = '';
            if(fileNameDisplay) fileNameDisplay.textContent = '';
        });
    }

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files[0]) {
                if(fileNameDisplay) fileNameDisplay.textContent = 'Archivo: ' + this.files[0].name;
            } else {
                if(fileNameDisplay) fileNameDisplay.textContent = '';
            }
        });
    }
});