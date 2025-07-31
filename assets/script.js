/**
 * PaintWall Image Generation Script
 */

// HTML要素への参照を取得する
// Get references to HTML elements
const imageForm = document.getElementById('imageForm');
const imageUpload = document.getElementById('imageUpload');
const fileUploadBtn = document.getElementById('fileUploadBtn');
const fileName = document.getElementById('fileName');
const originalImage = document.getElementById('originalImage');
const generatedImage = document.getElementById('generatedImage');
const previewArea = document.getElementById('previewArea');
const helpButtons = document.getElementById('helpButtons');
const downloadBtn = document.getElementById('downloadBtn');
const fullscreenBtn = document.getElementById('fullscreenBtn');
// const colorPicker = document.getElementById('colorPicker');
// const colorName = document.getElementById('colorName');
const submitButton = document.getElementById('submitButton');
const processingMessage = document.getElementById('processingMessage');
const processTime = document.getElementById('processTime');

// 定数の定義
// Define variables
let originalImageName = '';
let selectedColor = {
    hex: '',
    name: ''
};
let usedColor = '';

// イベントリスナーを追加する
// Add event listeners
fileUploadBtn.addEventListener('click', () => {
    imageUpload.click();
});

imageUpload.addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
        fileName.textContent = file.name;

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

/* colorName.addEventListener('change', (event) => {
    if (event.target.value === 'custom') {
        colorPicker.style.display = 'block';
    } else {
        colorPicker.style.display = 'none';
        colorPicker.value = event.target.value;
    }
}); */

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
    let downloadName = originalImageName;
    downloadName += `_${usedColor}`;
    downloadName += `_${new Date().toISOString().slice(0, 19).replace(/:/g, '-')}.jpg`;

    const link = document.createElement('a');
    link.href = generatedImage.src;
    link.download = downloadName;
    link.click();
});

imageForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    if (!imageUpload.files.length) {
        showAlert('画像をアップロードしてください。');
        return;
    }
    if (!selectedColor.hex) {
        showAlert('色を選択してください。');
        return;
    }

    const form = e.currentTarget;

    submitButton.disabled = true;
    processingMessage.style.visibility = 'visible';
    generatedImage.src = ASSET_URL + 'preview-placeholder.jpg';
    downloadBtn.disabled = true;
    fullscreenBtn.disabled = true;
    helpButtons.style.display = 'flex';
    downloadBtn.href = '';
    processTime.style.display = 'block';
    processTime.innerText = '処理中...';
    closeAlert();

    const formData = new FormData(form);
    // let colorName = form.elements.colorName.value;
    // const colorHex = form.elements.colorPicker.value;
   /*  if(colorName === 'custom') {
        colorName = ntc.name(colorHex)[1];
    }
    formData.set('color_name', colorName);
    formData.delete('color_custom'); */

    const beginTime = Date.now();
    try {
        // const colorName = await getColorName(selectedColor.hex);
        formData.set('color', selectedColor.hex);
        const response = await fetch(form.action, {
            method: form.method,
            body: formData
        });

        const json = await response.json();
        if (!response.ok) {
            throw new Error(json.error);
        }

        processTime.innerText = `処理時間: ${(Date.now() - beginTime) / 1000}s`;

        // const imageURl = json.image_url;
        // const downloadUrl = json.download_url;
        const base64Image = json.base64_image;
        // generatedImage.src = imageURl;
        // downloadBtn.href = downloadUrl;
        const base64ImageUrl = `data:image/jpg;base64,${base64Image}`;
        generatedImage.src = base64ImageUrl;

        originalImageName = imageUpload.files[0].name.replace(/\.[^/.]+$/, "");
        usedColor = selectedColor.name;

        downloadBtn.disabled = false;
        fullscreenBtn.disabled = false;
    } catch (error) {
        showAlert(error.message || '画像の生成に失敗しました。');
        processTime.innerText = '画像の生成に失敗しました。';
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

// 色の選択
// Select color
function selectColor(hex, name) {
    // colorPicker.value = hex;
    // colorPicker.setAttribute('data-color-name', name);
    selectedColor.hex = hex;
    selectedColor.name = name;
    document.querySelectorAll('.color-item.selected').forEach(item => {
        item.classList.remove('selected');
    });
}

// 色名でフィルタリング
// Filter by color name
function filterColorList(query) {
    const items = document.querySelectorAll('#colorList .color-item-wrap');
    query = query.trim().replace('-', '').toLowerCase();
    items.forEach(item => {
        const name = item.querySelector('.color-item').textContent.replace('-', '').toLowerCase();
        item.style.display = name.includes(query) ? '' : 'none';
    });
}

// 色の名前を取得
// Get color name
async function getColorName(hex) {
    const url = "https://www.thecolorapi.com/id?hex=" + hex.replace(/^#/, '');
    try {
        const response = await fetch(url, { method: 'GET' });
        const data = await response.json();
        // data.name.closet_named_hex
        return data.name.value;
    } catch (error) {
        throw new Error('色分析中にエラーが発生しました。');
    }
}