import * as THREE from '/js/three/build/three.module.js';
import { OrbitControls } from '/js/three/examples/jsm/controls/OrbitControls.js';
import { FontLoader } from '/js/three/examples/jsm/loaders/FontLoader.js';
import { TextGeometry } from '/js/three/examples/jsm/geometries/TextGeometry.js';

const scene = new THREE.Scene();
scene.background = new THREE.Color(0xA1E3F9); 
const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
const renderer = new THREE.WebGLRenderer({ antialias: true });
const container = document.getElementById("ar-container");
const containerWidth = container.clientWidth;
const containerHeight = container.clientHeight;

renderer.setSize(containerWidth, containerHeight);
camera.aspect = containerWidth / containerHeight;
camera.updateProjectionMatrix();

document.getElementById("ar-container").appendChild(renderer.domElement);

// 📷 Position de la caméra
camera.position.set(0.4, 2, -10);

// 💡 Ajout des lumières
const ambientLight = new THREE.AmbientLight(0xffffff, 1.5);
scene.add(ambientLight);

const directionalLight = new THREE.DirectionalLight(0xffffff, 2);
directionalLight.position.set(2, 5, 2);
scene.add(directionalLight);

// 🎮 Contrôles de la caméra
const controls = new OrbitControls(camera, renderer.domElement);
controls.enableDamping = true;

const groundGeometry = new THREE.CircleGeometry(10, 32);
const groundMaterial = new THREE.MeshStandardMaterial({ 
    color: 0x5B913B, // 🟢 Vert prairie
    side: THREE.DoubleSide 
});
const ground = new THREE.Mesh(groundGeometry, groundMaterial);
ground.rotation.x = -Math.PI / 2;
ground.position.y = -1;
scene.add(ground);

function createCloud() {
    const cloud = new THREE.Group();
    const cloudGeometry = new THREE.SphereGeometry(0.5, 16, 16);
    const cloudMaterial = new THREE.MeshStandardMaterial({ color: 0xffffff });
    
    for (let i = 0; i < 4; i++) {
        const part = new THREE.Mesh(cloudGeometry, cloudMaterial);
        part.position.set(
            Math.random() * 1.5 - 0.75,
            Math.random() * 0.5 - 0.25,
            Math.random() * 1.5 - 0.75
        );
        part.scale.setScalar(Math.random() * 0.7 + 0.5);
        cloud.add(part);
    }
    
    cloud.position.set(
        Math.random() * 10,
        Math.random() * 10,
        Math.random() * 10
    );
    
    return cloud;
}

for (let i = 0; i < 5; i++) {
    scene.add(createCloud());
}


let letterMesh;
let currentLetter = "A";
const fontLoader = new FontLoader();

function loadLetter(letter) {
    // Suppression sécurisée de l'ancienne lettre
    const removeOldLetter = () => {
        if (letterMesh && scene.children.includes(letterMesh)) {
            scene.remove(letterMesh);
            if (letterMesh.geometry) letterMesh.geometry.dispose();
            if (letterMesh.material) letterMesh.material.dispose();
        }
        letterMesh = null;
    };

    removeOldLetter();

    fontLoader.load('/fonts/helvetiker_regular.typeface.json', (font) => {
        removeOldLetter(); // Double vérification
        
        const geometry = new TextGeometry(letter, {
            font: font,
            size: 2,
            height: 0.03,
            depth: 0.5
        });
        geometry.center(); // Centre la géométrie
        geometry.rotateY(Math.PI); // Rotation corrective
        const material = new THREE.MeshStandardMaterial({
            color: 0xffffff, // couleur de la lettre
            metalness: 0.1,
            roughness: 0.8
        });
        
        letterMesh = new THREE.Mesh(geometry, material);
        letterMesh.position.set(0, 1, 0);
        scene.add(letterMesh);
    }, undefined, (err) => {
        console.error("Erreur chargement police:", err);
    });
}

/* 🛠 Ajout d'un helper pour voir les axes
const axesHelper = new THREE.AxesHelper(5);
scene.add(axesHelper);
*/

// 🔤 Chargement de la première lettre
loadLetter(currentLetter);

// 🎤 Configuration de la reconnaissance vocale
const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
recognition.lang = 'en-US';

// 🎙 Ajout d'un EventListener sur le bouton de démarrage
document.getElementById("start").addEventListener("click", () => {
    recognition.start();
});



let letters = [];

// Liste des lettres déjà réussies
let successfullyCompletedLetters = [];

// Fonction de récupération des lettres
async function fetchLetters() {
    try {
        const stageSelect = document.getElementById("stage-select");
        const stageId = stageSelect ? stageSelect.value : 1;

        const response = await fetch(`/letters?stage_id=${stageId}`);
        if (!response.ok) throw new Error(`Erreur HTTP: ${response.status}`);

        letters = await response.json();
        console.log(`Lettres pour le stage ${stageId} :`, letters);

        if (letters.length > 0) {
            currentLetter = getNextLetter();
            window.dispatchEvent(new CustomEvent("updateLetter", { 
                detail: { letter: currentLetter }
            }));
        } else {
            console.warn("Aucune lettre trouvée pour ce stage.");
        }
    } catch (error) {
        console.error("Erreur de chargement des lettres:", error);
    }
}




document.getElementById("stage-select")?.addEventListener("change", fetchLetters);


function getNextLetter() {
    if (letters.length === 0) {
        console.error("Aucune lettre disponible, retour à A");
        return "A";
    }
    
    // Sélection aléatoire robuste
    const randomIndex = Math.floor(Math.random() * letters.length);
    const nextLetter = letters[randomIndex];
    
    console.log("Prochaine lettre :", nextLetter);
    return nextLetter;
}

// Charger les lettres au démarrage
fetchLetters();

// 📝 Enregistrement des tentatives dans Laravel
function saveAttempt(letter, spokenWord, success) {
    fetch('/attempt', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            letter: letter,
            attempted_word: spokenWord, // Utilisation correcte du paramètre
            success: success
        }),
        credentials: 'include'
    }).then(response => response.json())
      .then(data => console.log("Saved:", data))
      .catch(error => console.error("Error:", error));
}
function playLetterSound(letter) {
    const audio = new Audio(`/audio/letters/${letter.toUpperCase()}.mp3`);
    audio.play();
}

// Supprimez la version dupliquée et gardez cette seule version
recognition.onresult = function(event) {
    const spokenWord = event.results[0][0].transcript.trim().toUpperCase();
    document.getElementById("result").innerText = "You said: " + spokenWord;

    const isCorrect = spokenWord === currentLetter;
    
    // Mise à jour correcte des paramètres
    saveAttempt(currentLetter, spokenWord, isCorrect);
    playLetterSound(currentLetter);

    if (isCorrect) {
    document.getElementById("result").innerText += "Correct!";
    
    // Ajouter la lettre réussie à la liste des lettres complétées
    successfullyCompletedLetters.push(currentLetter);

    setTimeout(() => {
        currentLetter = getNextLetter(letters);  // Passer à la lettre suivante
        window.dispatchEvent(new CustomEvent("updateLetter", { 
            detail: { letter: currentLetter } 
        }));
    }, 1000);
}
};
window.addEventListener("updateLetter", (event) => {
    const newLetter = event.detail.letter; // Accès correct à la propriété

    console.log("Nouvelle lettre reçue :", newLetter);
    
    // Mise à jour de la variable globale
    currentLetter = newLetter;
    
    // Chargement de la lettre
    loadLetter(newLetter);
    
    // Mise à jour du localStorage si nécessaire
    localStorage.setItem("currentLetter", newLetter);
    console.log("Lettre actuelle enregistrée:", newLetter);

    instruction.innerHTML = `Cliquez sur le bouton puis dites à haute voix: <strong>"la lettre ${newLetter}"</strong>.`;
});

window.addEventListener("keydown", function (event) {
    if (["ArrowUp", "ArrowDown", "ArrowLeft", "ArrowRight"].includes(event.code)) {
        event.preventDefault(); // Bloquer le scrolling de la page
    }
});

document.addEventListener("keydown", function (event) {
    const speed = 0.5; // Vitesse de déplacement

    switch (event.code) {
        case "ArrowUp":
            camera.position.z -= speed;
            break;
        case "ArrowDown":
            camera.position.z += speed;
            break;
        case "ArrowLeft":
            camera.position.x -= speed;
            break;
        case "ArrowRight":
            camera.position.x += speed;
            break;
    }
});

document.getElementById("skip").addEventListener("click", () => {
    saveSkipped(currentLetter); // Enregistrer comme skippée

    // Charger la lettre suivante
    currentLetter = getNextLetter();

    // Mettre à jour la scène avec la nouvelle lettre
    window.dispatchEvent(new CustomEvent("updateLetter", { 
        detail: { letter: currentLetter } 
    }));
});
function saveSkipped(letter) {
    fetch('/attempt', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            letter: letter,
            attempted_word: null,
            success: false,
            skipped: true
        }),
        credentials: 'include'
    })
    .then(response => response.json())
    .then(data => console.log("Lettre skippée enregistrée:", data))
    .catch(error => console.error("Erreur enregistrement skip:", error));
}


// 🎬 Boucle d'animation (sans rotation)
function animate() {
    requestAnimationFrame(animate);

    controls.update();
    renderer.render(scene, camera);
}

// 🚀 Démarrage de l'animation
animate();