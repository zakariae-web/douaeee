import * as THREE from '/js/three/build/three.module.js';
import { OrbitControls } from '/js/three/examples/jsm/controls/OrbitControls.js';
import { FontLoader } from '/js/three/examples/jsm/loaders/FontLoader.js';
import { TextGeometry } from '/js/three/examples/jsm/geometries/TextGeometry.js';

const scene = new THREE.Scene();
scene.background = new THREE.Color(0x87CEEB); 
const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
const renderer = new THREE.WebGLRenderer({ antialias: true });
renderer.setSize(window.innerWidth, window.innerHeight);
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
    color: 0x7CFC00, // 🟢 Vert prairie
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
            color: 0xE0FFFF,
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

let letters = [];

async function fetchLetters() {
    try {
        const response = await fetch('/letters');
        letters = await response.json();
        currentLetter = getNextLetter();
        loadLetter(currentLetter);
    } catch (error) {
        console.error("Erreur lors du chargement des lettres:", error);
    }
}

function getNextLetter() {
    if (letters.length === 0) return "A";  
    let nextLetter = letters[Math.floor(Math.random() * letters.length)];
    localStorage.setItem("currentLetter", nextLetter);
    return nextLetter;
}

// Charger les lettres au démarrage
fetchLetters();

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

// 🎬 Boucle d'animation (sans rotation)
function animate() {
    requestAnimationFrame(animate);

    controls.update();
    renderer.render(scene, camera);
}

// 🚀 Démarrage de l'animation
animate();
