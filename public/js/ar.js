import * as THREE from '/js/three/build/three.module.js';
import { OrbitControls } from '/js/three/examples/jsm/controls/OrbitControls.js';
import { FontLoader } from '/js/three/examples/jsm/loaders/FontLoader.js';
import { TextGeometry } from '/js/three/examples/jsm/geometries/TextGeometry.js';

const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
const renderer = new THREE.WebGLRenderer({ antialias: true });
renderer.setSize(window.innerWidth, window.innerHeight);
document.getElementById("ar-container").appendChild(renderer.domElement);

// 📷 Position de la caméra
camera.position.set(0, 2, 5);

// 💡 Ajout des lumières
const ambientLight = new THREE.AmbientLight(0xffffff, 1.5);
scene.add(ambientLight);

const directionalLight = new THREE.DirectionalLight(0xffffff, 2);
directionalLight.position.set(2, 5, 2);
scene.add(directionalLight);

// 🎮 Contrôles de la caméra
const controls = new OrbitControls(camera, renderer.domElement);
controls.enableDamping = true;

// 🔤 Chargement des lettres avec FontLoader
let letterMesh;
let currentLetter = "A";
const fontLoader = new FontLoader();

function loadLetter(letter) {
    if (letterMesh) {
        scene.remove(letterMesh);
    }

    fontLoader.load('/fonts/helvetiker_regular.typeface.json', function (font) {
        const geometry = new TextGeometry(letter, {
            font: font,
            size: 1.5, // Augmentation de la taille
            height: 0.3
        });

        const material = new THREE.MeshStandardMaterial({
            color: 0xffa79d, 
            metalness: 0.3, 
            roughness: 0.5
        });
        
        letterMesh = new THREE.Mesh(geometry, material);
        letterMesh.position.set(-0.5, 1, 0);
        scene.add(letterMesh);
    });
}

// 🛠 Ajout d'un helper pour voir les axes
const axesHelper = new THREE.AxesHelper(5);
scene.add(axesHelper);

// 🔤 Chargement de la première lettre
loadLetter(currentLetter);

// 🎤 Configuration de la reconnaissance vocale
const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
recognition.lang = 'en-US';

// 🎙 Ajout d'un EventListener sur le bouton de démarrage
document.getElementById("start").addEventListener("click", () => {
    recognition.start();
});

recognition.onresult = function (event) {
    const spokenWord = event.results[0][0].transcript.trim().toUpperCase();
    document.getElementById("result").innerText = "You said: " + spokenWord;

    if (spokenWord === currentLetter) {
        document.getElementById("result").innerText += " ✅ Correct!";
        saveAttempt(currentLetter, true);

        // Chargement de la lettre suivante après 1 seconde
        setTimeout(() => {
            currentLetter = getNextLetter();
            loadLetter(currentLetter);
        }, 1000);
    } else {
        document.getElementById("result").innerText += " ❌ Try again.";
        saveAttempt(currentLetter, false);
    }
};

// 🔠 Fonction pour obtenir la lettre suivante aléatoirement
function getNextLetter() {
    const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    return letters[Math.floor(Math.random() * letters.length)];
}

// 📝 Enregistrement des tentatives dans Laravel
function saveAttempt(letter, success) {
    fetch('/attempt', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            letter: letter,
            success: success
        }),
        credentials: 'include'
    }).then(response => response.json())
      .then(data => console.log("Saved:", data))
      .catch(error => console.error("Error:", error));
}

// 🎬 Boucle d'animation
function animate() {
    requestAnimationFrame(animate);
    
    if (letterMesh) {
        letterMesh.rotation.y += 0.01;
    }

    controls.update();
    renderer.render(scene, camera);
}

// 🚀 Démarrage de l'animation
animate();