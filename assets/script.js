const imageUpload = document.getElementById('imageUpload');
const originalImage = document.getElementById('originalImage');
const generatedImage = document.getElementById('generatedImage');
const previewArea = document.getElementById('previewArea');
const downloadBtn = document.getElementById('downloadBtn');
const fullscreenBtn = document.getElementById('fullscreenBtn');
const colorPicker = document.getElementById('colorPicker');
const colorNames = document.getElementById('colorNames');

imageUpload.addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            originalImage.src = e.target.result;
            originalImage.style.display = 'block';
            generatedImage.src = e.target.result;
            generatedImage.style.display = 'block';
            downloadBtn.style.display = 'inline-block';
            fullscreenBtn.style.display = 'inline-block';
        };
        reader.readAsDataURL(file);
    }
});

colorNames.addEventListener('change', (event) => {
    if (event.target.value === 'custom') {
        colorPicker.style.display = 'block';
    } else {
        colorPicker.style.display = 'none';
        colorPicker.value = event.target.value;
    }
});

fullscreenBtn.addEventListener('click', () => {
    if (generatedImage.requestFullscreen) {
        generatedImage.requestFullscreen();
    } else if (generatedImage.webkitRequestFullscreen) {
        generatedImage.webkitRequestFullscreen();
    } else if (generatedImage.msRequestFullscreen) {
        generatedImage.msRequestFullscreen();
    }
});

downloadBtn.addEventListener('click', () => {
    const link = document.createElement('a');
    link.href = generatedImage.src;
    link.download = 'generated-image.png';
    link.click();
});

document.getElementById('imageForm').addEventListener('submit', (e) => {
    /* e.preventDefault();
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    const img = new Image();
    img.src = originalImage.src;
    img.onload = () => {
        canvas.width = 1024;
        canvas.height = 1024;
        ctx.drawImage(img, 0, 0, 1024, 1024);
        ctx.fillStyle = colorPicker.value;
        ctx.globalAlpha = 0.5;
        ctx.fillRect(0, 0, 1024, 1024);
        generatedImage.src = canvas.toDataURL();
    }; */
});