/**
 * PaintWall Image Generation Script
 */

// HTML要素への参照を取得する
// Get references to HTML elements
const imageUpload = document.getElementById('imageUpload');
const originalImage = document.getElementById('originalImage');
const generatedImage = document.getElementById('generatedImage');
const previewArea = document.getElementById('previewArea');
const downloadBtn = document.getElementById('downloadBtn');
const fullscreenBtn = document.getElementById('fullscreenBtn');
const colorPicker = document.getElementById('colorPicker');
const colorName = document.getElementById('colorName');
const submitButton = document.getElementById('submitButton');
const processingMessage = document.getElementById('processingMessage');
const processTime = document.getElementById('processTime');

// イベントリスナーを追加する
// Add event listeners
imageUpload.addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            originalImage.src = e.target.result;
            originalImage.style.display = 'block';
            generatedImage.src = ASSET_URL + 'preview-placeholder.jpg';
            generatedImage.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

colorName.addEventListener('change', (event) => {
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
    let downloadName = imageUpload.files[0].name.replace(/\.[^/.]+$/, "");
    downloadName += `_${colorName.value === 'custom' ? colorPicker.value : colorName.value}-color`;
    downloadName += `_${new Date().toISOString().slice(0, 19).replace(/:/g, '-')}.jpg`;

    const link = document.createElement('a');
    link.href = generatedImage.src;
    link.download = downloadName;
    link.click();
});

document.getElementById('imageForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    if (!imageUpload.files.length) {
        showAlert('画像をアップロードしてください。');
        return;
    };

    const form = e.currentTarget;
    
    submitButton.disabled = true;
    processingMessage.style.visibility = 'visible';
    generatedImage.src = ASSET_URL + 'preview-placeholder.jpg';
    downloadBtn.disabled = true;
    fullscreenBtn.disabled = true;
    downloadBtn.style.display = 'inline-block';
    fullscreenBtn.style.display = 'inline-block';
    downloadBtn.href = '';
    processTime.style.display = 'block';
    processTime.innerText = '処理中...';
    closeAlert();

    const formData = new FormData(form);
    /* let colorName = form.elements.colorName.value;
    const colorHex = form.elements.colorPicker.value;
    if(colorName === 'custom') {
        colorName = ntc.name(colorHex)[1];
    }
    formData.set('color_name', colorName);
    formData.delete('color_custom'); */

    const beginTime = Date.now();
    try {
        const response = await fetch(form.action, {
            method: form.method,
            body: formData
        });
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        processTime.innerText = `処理時間: ${(Date.now() - beginTime) / 1000}s`;
        
        const json = await response.json();

        const imageURl = json.image_url;
        const downloadUrl = json.download_url;
        const base64Image = json.base64_image;
        // generatedImage.src = imageURl;
        // downloadBtn.href = downloadUrl;
        const base64ImageUrl = `data:image/jpg;base64,${base64Image}`;
        generatedImage.src = base64ImageUrl;

        downloadBtn.disabled = false;
        fullscreenBtn.disabled = false;
    } catch (error) {
        showAlert('画像の生成に失敗しました。');
        processTime.innerText = '画像の生成に失敗しました。';

        console.error(error);
    }
    submitButton.disabled = false;
    processingMessage.style.visibility = 'hidden';
});

// アラートの表示と非表示
// Alert
function showAlert(text) {
    document.getElementById("alertText").innerText = text;
    document.getElementById("alert").classList.add("show");
}

function closeAlert() {
    document.getElementById("alertText").innerText = '';
    document.getElementById("alert").classList.remove("show");
}

