<?php
require_once 'db.php';

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$selected_cat = isset($_GET['category']) ? (int)$_GET['category'] : null;

if ($selected_cat) {
    $stmt = $pdo->prepare("SELECT * FROM pictograms WHERE category_id = ?");
    $stmt->execute([$selected_cat]);
    $pictograms = $stmt->fetchAll();
} else {
    $pictograms = $pdo->query("SELECT * FROM pictograms")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application PECS Web Pro</title>
    <style>
        
        :root {
            --bg-color: #F8F5F0;
            --text-color: #212121;
            --card-bg: #ffffff;
            --border-color: #e0e0e0;
            --picto-size: 120px;
            --font-size-base: 1.2rem;
        }

        [data-theme="dark"] {
            --bg-color: #121212;
            --text-color: #E0E0E0;
            --card-bg: #1E1E1E;
            --border-color: #333333;
        }

        [data-accessibility="large"] {
            --picto-size: 180px; 
            --font-size-base: 1.6rem;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 20px;
            transition: all 0.3s ease;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

        .admin-panel {
            background: var(--card-bg);
            border: 2px solid var(--border-color);
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
            justify-content: space-between;
        }

        .panel-section {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .sentence-builder-zone {
            background-color: var(--card-bg);
            border: 4px dashed #FF9800;
            border-radius: 15px;
            padding: 20px;
            min-height: 150px;
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            overflow-x: auto;
        }

        .sentence-builder-zone .picto-card {
            cursor: pointer;
            border: 2px solid #FF9800;
        }

        .written-sentence {
            background-color: #FFF3E0;
            border: 2px solid #FFB74D;
            border-radius: 10px;
            padding: 15px;
            font-size: 1.5rem;
            font-weight: bold;
            color: #E65100;
            margin-bottom: 25px;
            text-align: center;
            min-height: 35px;
        }

        [data-theme="dark"] .written-sentence {
            background-color: #2E1A00;
            color: #FFB74D;
        }

        .controls {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .btn {
            padding: 12px 24px;
            font-size: 1.1rem;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.1s;
        }
        .btn:active { transform: scale(0.95); }
        .btn-speak { background-color: #4CAF50; color: white; }
        .btn-clear { background-color: #f44336; color: white; }
        .btn-toggle { background-color: #2196F3; color: white; }
        .btn-dark { background-color: #555; color: white; }

        .categories-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .category-btn {
            padding: 12px 24px;
            font-size: 1.1rem;
            text-decoration: none;
            color: white;
            border-radius: 8px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .pictograms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(calc(var(--picto-size) + 30px), 1fr));
            gap: 20px;
        }

        .picto-card {
            background-color: var(--card-bg);
            border: 3px solid var(--border-color);
            border-radius: 12px;
            padding: 10px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.2s;
            cursor: pointer;
            user-select: none;
        }

        .picto-card:hover {
            transform: translateY(-5px);
            border-color: #4CAF50;
        }

        .picto-card img {
            width: var(--picto-size);
            height: var(--picto-size);
            object-fit: contain;
            display: block;
            margin: 0 auto 10px auto;
        }

        .picto-card span {
            font-size: var(--font-size-base);
            font-weight: bold;
            display: block;
            text-transform: capitalize;
            color: var(--text-color);
        }
                .analytics-box {
            background: #e3f2fd;
            padding: 10px;
            border-radius: 8px;
            margin-top: 15px;
            font-size: 0.9rem;
            color: #0d47a1;
        }
        [data-theme="dark"] .analytics-box {
            background: #0d47a1;
            color: #e3f2fd;
        }
    </style>
</head>
<body>

<header>
    <h1>Système de Communication PECS</h1>
</header>

<div class="admin-panel">
    <div class="panel-section">
        <label for="childProfile">🧒 Enfant: </label>
        <select id="childProfile" onchange="switchProfile()" style="padding: 5px; font-size:1rem;">
            <option value="default">Enfant 1</option>
            <option value="child_b">Enfant 2</option>
        </select>
    </div>

    <div class="panel-section">
        <label style="font-weight: bold;">➕ Ajouter Photo:</label>
        <input type="text" id="customLabel" placeholder="Nom de l'image (ex: Maman)" style="padding: 5px;">
        <input type="file" id="customFile" accept="image/*" style="width: 180px;">
        <button class="btn" style="padding:5px 10px; background:#4CAF50; color:white;" onclick="addCustomPicto()">Ajouter</button>
    </div>

    <div class="panel-section">
        <button class="btn btn-toggle" id="boardModeBtn" onclick="toggleBoardMode()">📋 Mode: Construction</button>
        <button class="btn btn-dark" onclick="toggleDarkMode()">🌓 Mode Sombre</button>
        <button class="btn" style="background:#9c27b0; color:white;" onclick="toggleAccessibilitySize()">🔍 Zoom</button>
    </div>
</div>

<div id="builderContainer">
    <div class="sentence-builder-zone" id="sentenceZone"></div>
    <div class="written-sentence" id="textSentenceZone">...</div>
    <div class="controls">
        <button class="btn btn-speak" onclick="speakSentence()">🔊 Lire la phrase</button>
        <button class="btn btn-clear" onclick="clearSentence()">🔄 Effacer</button>
    </div>
</div>

<div class="categories-bar">
    <a href="index.php" class="category-btn" style="background-color: #212121;">Tous</a>
    <?php foreach ($categories as $cat): ?>
        <a href="index.php?category=<?= $cat['id'] ?>" class="category-btn" style="background-color: <?= htmlspecialchars($cat['color']) ?>;">
            <?= htmlspecialchars($cat['name']) ?>
        </a>
    <?php endforeach; ?>
</div>

<div class="pictograms-grid" id="mainGrid">
    <?php if (count($pictograms) > 0): ?>
        <?php foreach ($pictograms as $picto): ?>
            <div class="picto-card" onclick="handlePictoClick('<?= htmlspecialchars($picto['label']) ?>', '<?= htmlspecialchars($picto['image_path']) ?>', <?= (int)$picto['category_id'] ?>)">
                <img src="<?= htmlspecialchars($picto['image_path']) ?>" alt="<?= htmlspecialchars($picto['label']) ?>">
                <span><?= htmlspecialchars($picto['label']) ?></span>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="analytics-box">
    📊 <strong>Statistiques d'utilisation (<span id="statsName">Enfant 1</span>):</strong> 
    <span id="topDemands">Aucune demande enregistrée pour le moment.</span>
</div>

<script>
let currentSentence = [];
let isBoardMode = false; 
let currentProfile = "default";

const emotionAdjectives = ['triste', 'joyeux', 'content', 'en colère', 'fatigué', 'peur'];
const emotionVerbs = ['pleurer', 'crier', 'rigoler'];
const FOOD_CATEGORY_ID = 1;  
const DRINK_CATEGORY_ID = 2; 

function switchProfile() {
    currentProfile = document.getElementById('childProfile').value;
    document.getElementById('statsName').innerText = document.getElementById('childProfile').options[document.getElementById('childProfile').selectedIndex].text;
    
    loadCustomPictograms();
    updateAnalyticsDisplay();
    clearSentence();
}

function logCommunication(word) {
    let history = JSON.parse(localStorage.getItem('pecs_history_' + currentProfile)) || {};
    history[word] = (history[word] || 0) + 1;
    localStorage.setItem('pecs_history_' + currentProfile, JSON.stringify(history));
    updateAnalyticsDisplay();
}

function updateAnalyticsDisplay() {
    let history = JSON.parse(localStorage.getItem('pecs_history_' + currentProfile)) || {};
    let sorted = Object.entries(history).sort((a,b) => b[1] - a[1]).slice(0, 3);
    
    if(sorted.length > 0) {
        document.getElementById('topDemands').innerText = "Plus demandés : " + sorted.map(item => `${item[0]} (${item[1]}x)`).join(', ');
    } else {
        document.getElementById('topDemands').innerText = "Aucune demande enregistrée.";
    }
}
function addCustomPicto() {
    const label = document.getElementById('customLabel').value.trim();
    const fileInput = document.getElementById('customFile');
    
    if (!label || fileInput.files.length === 0) {
        alert("Veuillez donner un nom et sélectionner une image.");
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        const base64Image = e.target.result;
        let customPictos = JSON.parse(localStorage.getItem('custom_pictos_' + currentProfile)) || [];
        
        customPictos.push({ label: label, image: base64Image, categoryId: 999 }); 
        localStorage.setItem('custom_pictos_' + currentProfile, JSON.stringify(customPictos));
        
        document.getElementById('customLabel').value = '';
        fileInput.value = '';
        loadCustomPictograms();
    };
    reader.readAsDataURL(fileInput.files[0]);
}

function loadCustomPictograms() {
    
    document.querySelectorAll('.custom-card').forEach(el => el.remove());
    
    let customPictos = JSON.parse(localStorage.getItem('custom_pictos_' + currentProfile)) || [];
    const grid = document.getElementById('mainGrid');
    
    customPictos.forEach(picto => {
        const card = document.createElement('div');
        card.className = 'picto-card custom-card';
        card.onclick = () => handlePictoClick(picto.label, picto.image, picto.categoryId);
        card.innerHTML = `<img src="${picto.image}" alt="${picto.label}"><span>${picto.label}</span>`;
        grid.insertBefore(card, grid.firstChild); 
    });
}
function handlePictoClick(label, imagePath, categoryId) {
    if (isBoardMode) {
        logCommunication(label);
        speakDirectWord(label);
    } else {
        addToSentence(label, imagePath, categoryId);
    }
}

function toggleBoardMode() {
    isBoardMode = !isBoardMode;
    const btn = document.getElementById('boardModeBtn');
    const builder = document.getElementById('builderContainer');
    
    if (isBoardMode) {
        btn.innerText = "📋 Mode: Tableau (Fixe)";
        btn.style.background = "#E91E63";
        builder.style.display = "none";
    } else {
        btn.innerText = "📋 Mode: Construction";
        btn.style.background = "#2196F3";
        builder.style.display = "block";
    }
}

function speakDirectWord(word) {
    if (window.speechSynthesis.speaking) window.speechSynthesis.cancel();
    let utterance = new SpeechSynthesisUtterance(word);
    utterance.lang = 'fr-FR';
    window.speechSynthesis.speak(utterance);
}
function toggleDarkMode() {
    const currentTheme = document.documentElement.getAttribute('data-theme');
    if (currentTheme === 'dark') {
        document.documentElement.removeAttribute('data-theme');
    } else {
        document.documentElement.setAttribute('data-theme', 'dark');
    }
}
function toggleAccessibilitySize() {
    const currentSize = document.documentElement.getAttribute('data-accessibility');
    if (currentSize === 'large') {
        document.documentElement.removeAttribute('data-accessibility');
    } else {
        document.documentElement.setAttribute('data-accessibility', 'large');
    }
}

function buildFullSentence() {
    if (currentSentence.length === 0) return "...";
    
    let firstWord = currentSentence[0].text.toLowerCase();
    let firstWordCatId = currentSentence[0].categoryId;
    let restOfSentence = currentSentence.map(item => item.text).join(' ');
    
    if (firstWordCatId === FOOD_CATEGORY_ID) {
        return "Je veux manger " + restOfSentence;
    } else if (firstWordCatId === DRINK_CATEGORY_ID) {
        return "Je veux boire " + restOfSentence;
    } else if (emotionAdjectives.includes(firstWord) || emotionVerbs.includes(firstWord)) {
        return "Je " + restOfSentence;
    } else {
        return "Je veux " + restOfSentence;
    }
}

function updateWrittenSentence() {
    document.getElementById('textSentenceZone').innerText = buildFullSentence();
}

function addToSentence(label, imagePath, categoryId) {
    const sentenceZone = document.getElementById('sentenceZone');
    const uniqueId = 'picto_' + Date.now() + '_' + Math.floor(Math.random() * 1000);
    
    const card = document.createElement('div');
    card.className = 'picto-card';
    card.setAttribute('data-id', uniqueId);
    card.innerHTML = `<img src="${imagePath}" alt="${label}"><span>${label}</span>`;
    
    card.onclick = function() {
        sentenceZone.removeChild(card);
        currentSentence = currentSentence.filter(item => item.id !== uniqueId);
        updateWrittenSentence();
    };

    sentenceZone.appendChild(card);
    currentSentence.push({ id: uniqueId, text: label, categoryId: categoryId });
    updateWrittenSentence();
}

function clearSentence() {
    document.getElementById('sentenceZone').innerHTML = '';
    currentSentence = [];
    updateWrittenSentence();
}

function speakSentence() {
    if (currentSentence.length === 0) return;
    
    let textToSpeak = buildFullSentence();
    

    currentSentence.forEach(item => logCommunication(item.text));
    
    if (window.speechSynthesis.speaking) window.speechSynthesis.cancel();
    
    let utterance = new SpeechSynthesisUtterance(textToSpeak);
    utterance.lang = 'fr-FR'; 
    utterance.rate = 0.85; 

    window.speechSynthesis.speak(utterance);
}
window.onload = function() {
    loadCustomPictograms();
    updateAnalyticsDisplay();
};
</script>
</body>
</html>