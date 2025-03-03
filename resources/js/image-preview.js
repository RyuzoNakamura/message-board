document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('image');
    const previewContainer = document.getElementById('image-preview-container');
    const preview = document.getElementById('image-preview');

    input.addEventListener('change', function() {
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();

            reader.addEventListener('load', function() {
                preview.src = this.result;
                previewContainer.style.display = 'block';
            });

            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
        }
    });
});
