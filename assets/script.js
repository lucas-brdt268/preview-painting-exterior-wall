const imageUpload = document.getElementById('imageUpload');
const originalImage = document.getElementById('originalImage');
const generatedImage = document.getElementById('generatedImage');
const previewArea = document.getElementById('previewArea');
const downloadBtn = document.getElementById('downloadBtn');
const fullscreenBtn = document.getElementById('fullscreenBtn');
const colorPicker = document.getElementById('colorPicker');
const colorNames = document.getElementById('colorNames');
const submitButton = document.getElementById('submitButton');
const processingMessage = document.getElementById('processingMessage');

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

document.getElementById('imageForm').addEventListener('submit', async (e) => {
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
    e.preventDefault();
    const form = e.currentTarget;
    submitButton.disabled = true;
    downloadBtn.disabled = true;
    fullscreenBtn.disabled = true;

    processingMessage.style.display = 'inline';
    try {
        const response = await fetch(form.action, {
            method: form.method,
            body: new FormData(form)
        });
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const json = await response.json();

        const generated = json.g;
        generatedImage.src = "data:image/png;base64," + generated;
    } catch (error) {
        console.error(error);
        alert('画像の生成に失敗しました。');
    }
    submitButton.disabled = false;
    downloadBtn.disabled = false;
    fullscreenBtn.disabled = false;
    processingMessage.style.display = 'none';
});