import * as THREE from '/js/three/build/three.module.js';
import { OrbitControls } from '/js/three/examples/jsm/controls/OrbitControls.js';
import { FontLoader } from '/js/three/examples/jsm/loaders/FontLoader.js';
import { TextGeometry } from '/js/three/examples/jsm/geometries/TextGeometry.js';

const scene = new THREE.Scene();

// Ciel avec dégradé
const skyGeometry = new THREE.SphereGeometry(50, 32, 32);
const skyMaterial = new THREE.ShaderMaterial({
    uniforms: {
        topColor: { value: new THREE.Color(0x87CEEB) },  // Bleu ciel
        bottomColor: { value: new THREE.Color(0xFFFFFF) },  // Blanc
        offset: { value: 20 },
        exponent: { value: 0.6 }
    },
    vertexShader: `
        varying vec3 vWorldPosition;
        void main() {
            vec4 worldPosition = modelMatrix * vec4(position, 1.0);
            vWorldPosition = worldPosition.xyz;
            gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
        }
    `,
    fragmentShader: `
        uniform vec3 topColor;
        uniform vec3 bottomColor;
        uniform float offset;
        uniform float exponent;
        varying vec3 vWorldPosition;
        void main() {
            float h = normalize(vWorldPosition + offset).y;
            gl_FragColor = vec4(mix(bottomColor, topColor, max(pow(max(h, 0.0), exponent), 0.0)), 1.0);
        }
    `,
    side: THREE.BackSide
});

const sky = new THREE.Mesh(skyGeometry, skyMaterial);
scene.add(sky);

const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
const renderer = new THREE.WebGLRenderer({ antialias: true });
renderer.shadowMap.enabled = true;
renderer.shadowMap.type = THREE.PCFSoftShadowMap;
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
const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
scene.add(ambientLight);

const directionalLight = new THREE.DirectionalLight(0xffffff, 1.2);
directionalLight.position.set(5, 8, 5);
directionalLight.castShadow = true;
directionalLight.shadow.camera.near = 0.1;
directionalLight.shadow.camera.far = 25;
directionalLight.shadow.camera.left = -10;
directionalLight.shadow.camera.right = 10;
directionalLight.shadow.camera.top = 10;
directionalLight.shadow.camera.bottom = -10;
directionalLight.shadow.mapSize.width = 2048;
directionalLight.shadow.mapSize.height = 2048;
scene.add(directionalLight);

// 🎮 Contrôles de la caméra
const controls = new OrbitControls(camera, renderer.domElement);
controls.enableDamping = true;

// Sol amélioré avec dégradé
const groundGeometry = new THREE.CircleGeometry(12, 64);
const groundMaterial = new THREE.MeshPhysicalMaterial({ 
    color: 0x4CAF50,
    metalness: 0.1,
    roughness: 0.8,
    clearcoat: 0.2,
    clearcoatRoughness: 0.4,
    side: THREE.DoubleSide
});
const ground = new THREE.Mesh(groundGeometry, groundMaterial);
ground.rotation.x = -Math.PI / 2;
ground.position.y = -1;
ground.receiveShadow = true;
scene.add(ground);

// Ajout d'herbe stylisée
function createGrassField() {
    const grassGroup = new THREE.Group();
    const grassColors = [
        0x4CAF50, // Vert principal
        0x388E3C  // Vert foncé légèrement différent
    ];

    // Création des brins d'herbe (réduit de 1000 à 300)
    for (let i = 0; i < 300; i++) {
        const angle = Math.random() * Math.PI * 2;
        const radius = Math.random() * 11;
        
        // Création d'un brin d'herbe (plus simple)
        const height = Math.random() * 0.2 + 0.2;
        const grassGeometry = new THREE.ConeGeometry(0.02, height, 3);
        const grassMaterial = new THREE.MeshPhysicalMaterial({
            color: grassColors[Math.floor(Math.random() * grassColors.length)],
            metalness: 0,
            roughness: 0.8
        });
        
        const grass = new THREE.Mesh(grassGeometry, grassMaterial);
        
        // Position et rotation
        grass.position.set(
            Math.cos(angle) * radius,
            height / 2 - 0.9,
            Math.sin(angle) * radius
        );
        
        // Rotation plus subtile
        grass.rotation.y = Math.random() * Math.PI;
        grass.rotation.x = (Math.random() * 0.1) - 0.05;
        
        grass.castShadow = true;
        grass.receiveShadow = true;
        
        grassGroup.add(grass);
    }

    // Création de touffes d'herbe (réduit de 200 à 50)
    for (let i = 0; i < 50; i++) {
        const angle = Math.random() * Math.PI * 2;
        const radius = Math.random() * 10;
        
        const tuftGeometry = new THREE.SphereGeometry(0.15, 4, 4);
        const tuftMaterial = new THREE.MeshPhysicalMaterial({
            color: grassColors[0],
            metalness: 0,
            roughness: 1
        });
        
        const tuft = new THREE.Mesh(tuftGeometry, tuftMaterial);
        
        tuft.position.set(
            Math.cos(angle) * radius,
            -0.85,
            Math.sin(angle) * radius
        );
        
        tuft.scale.y = 0.5;
        tuft.castShadow = true;
        tuft.receiveShadow = true;
        
        grassGroup.add(tuft);
    }

    return grassGroup;
}

// Ajout de l'herbe à la scène
const grassField = createGrassField();
scene.add(grassField);

// Animation de l'herbe (plus douce)
const windSpeed = 0.0005; // Réduit de moitié
const windStrength = 0.015; // Réduit de moitié
let time = 0;

// Mise à jour de la fonction d'animation
function animate() {
    requestAnimationFrame(animate);

    // Animation améliorée des nuages
    clouds.forEach(cloud => {
        cloud.userData.time += 0.005;
        cloud.userData.angle += cloud.userData.speed * 0.02;

        // Mouvement circulaire amélioré
        cloud.position.x = Math.cos(cloud.userData.angle) * cloud.userData.radius;
        cloud.position.z = Math.sin(cloud.userData.angle) * cloud.userData.radius;
        
        // Mouvement vertical doux
        cloud.position.y = cloud.userData.initialY + 
            Math.sin(cloud.userData.time) * cloud.userData.verticalDistance;

        // Rotation douce
        cloud.rotation.y += cloud.userData.rotationSpeed;

        // Animation de l'opacité des parties du nuage
        cloud.children.forEach(part => {
            part.material.opacity = 
                (cloud.scale.x === 0.6 ? 0.7 : 0.9) + // Base opacity depends on cloud layer
                Math.sin(cloud.userData.time * 2) * 0.1;
        });
    });

    // Animation de l'herbe plus subtile
    time += windSpeed;
    if (time % 0.1 < 0.001) {
        grassField.children.forEach((blade, index) => {
            if (blade.geometry.type === 'ConeGeometry') {
                const windEffect = Math.sin(time + index * 0.2) * windStrength;
                blade.rotation.x = blade.userData.originalRotationX + windEffect;
            }
        });
    }

    // Animation de la lettre
    if (letterMesh) {
        letterMesh.userData.time += letterMesh.userData.floatSpeed;
        
        // Animation de flottement
        const floatY = letterMesh.userData.initialY + 
            Math.sin(letterMesh.userData.time) * letterMesh.userData.floatAmplitude;
        letterMesh.position.y = floatY;

        // Rotation subtile
        letterMesh.rotation.y += letterMesh.userData.rotationSpeed;

        // Mise à jour du glow
        if (letterMesh.children[1]) {
            letterMesh.children[1].material.uniforms.viewVector.value = 
                new THREE.Vector3().subVectors(camera.position, letterMesh.position);
        }
    }

    controls.update();
    renderer.render(scene, camera);
}

// Sauvegarde des rotations initiales de l'herbe
grassField.children.forEach(blade => {
    if (blade.geometry.type === 'ConeGeometry') {
        blade.userData.originalRotationX = blade.rotation.x;
    }
});

// Amélioration des nuages
function createCloud() {
    const cloud = new THREE.Group();
    
    // Formes de base pour un nuage plus réaliste
    const cloudShapes = [
        { radius: 0.5, y: 0, x: 0, z: 0 },
        { radius: 0.4, y: 0.2, x: 0.4, z: 0 },
        { radius: 0.4, y: 0.1, x: -0.4, z: 0 },
        { radius: 0.4, y: 0.2, x: 0, z: 0.4 },
        { radius: 0.4, y: 0.1, x: 0, z: -0.4 },
        { radius: 0.3, y: 0.3, x: 0.2, z: 0.2 },  // Ajout de formes supplémentaires
        { radius: 0.3, y: 0.2, x: -0.2, z: -0.2 }
    ];

    const cloudMaterial = new THREE.MeshPhysicalMaterial({
        color: 0xffffff,
        transparent: true,
        opacity: 0.9,
        metalness: 0,
        roughness: 1,
        clearcoat: 0.1,
        clearcoatRoughness: 0.5
    });

    // Création de nuages de tailles différentes
    const scale = Math.random() * 0.5 + 0.8; // Échelle aléatoire entre 0.8 et 1.3

    cloudShapes.forEach(shape => {
        const geometry = new THREE.SphereGeometry(shape.radius, 16, 16);
        const part = new THREE.Mesh(geometry, cloudMaterial);
        part.position.set(shape.x, shape.y, shape.z);
        
        // Déformation aléatoire pour plus de naturel
        part.scale.set(
            scale * (1 + Math.random() * 0.2),
            scale * (0.8 + Math.random() * 0.3),
            scale * (1 + Math.random() * 0.2)
        );
        
        cloud.add(part);
    });

    // Distribution améliorée des nuages dans le ciel
    const angle = Math.random() * Math.PI * 2;
    const radiusFromCenter = Math.random() * 35 + 5; // Distance du centre entre 5 et 40
    const height = Math.random() * 5 + 6; // Hauteur entre 6 et 11

    cloud.position.set(
        Math.cos(angle) * radiusFromCenter,
        height,
        Math.sin(angle) * radiusFromCenter
    );

    // Rotation aléatoire
    cloud.rotation.y = Math.random() * Math.PI * 2;
    cloud.rotation.z = (Math.random() - 0.5) * 0.2; // Légère inclinaison

    // Propriétés d'animation améliorées
    cloud.userData = {
        speed: Math.random() * 0.008 + 0.004, // Vitesse légèrement réduite
        rotationSpeed: (Math.random() - 0.5) * 0.0008,
        verticalSpeed: Math.random() * 0.0008,
        verticalDistance: Math.random() * 0.3,
        initialY: cloud.position.y,
        time: Math.random() * Math.PI * 2,
        radius: radiusFromCenter,
        angle: angle
    };

    return cloud;
}

// Création de plus de nuages avec différentes couches
const clouds = [];
// Couche principale de nuages
for (let i = 0; i < 20; i++) {
    const cloud = createCloud();
    clouds.push(cloud);
    scene.add(cloud);
}

// Petits nuages d'arrière-plan
for (let i = 0; i < 15; i++) {
    const cloud = createCloud();
    cloud.scale.multiplyScalar(0.6); // Plus petits
    cloud.position.y += 3; // Plus hauts
    cloud.children.forEach(part => {
        part.material.opacity = 0.7; // Plus transparents
    });
    clouds.push(cloud);
    scene.add(cloud);
}

let letterMesh;
let currentLetter = "A";
const fontLoader = new FontLoader();

function loadLetter(letter) {
    const removeOldLetter = () => {
        if (letterMesh && scene.children.includes(letterMesh)) {
            scene.remove(letterMesh);
            if (letterMesh.geometry) letterMesh.geometry.dispose();
            if (letterMesh.material) letterMesh.material.dispose();
        }
        letterMesh = null;
    };

    removeOldLetter();

    fontLoader.load('/fonts/droid/droid_sans_regular.typeface.json', (font) => {
        removeOldLetter();
        
        const geometry = new TextGeometry(letter, {
            font: font,
            size: 2,
            height: 0.2,
            depth: 0.5,
            curveSegments: 12,
            bevelEnabled: true,
            bevelThickness: 0.03,
            bevelSize: 0.02,
            bevelOffset: 0,
            bevelSegments: 5
        });
        
        geometry.center();
        geometry.rotateY(Math.PI);

        // Matériau principal amélioré
        const material = new THREE.MeshPhysicalMaterial({
            color: 0x2196F3,
            metalness: 0.4,
            roughness: 0.3,
            clearcoat: 0.5,
            clearcoatRoughness: 0.3,
            reflectivity: 1
        });

        // Création du groupe pour la lettre et son contour
        const letterGroup = new THREE.Group();
        
        // Mesh principal
        const mainLetter = new THREE.Mesh(geometry, material);
        mainLetter.castShadow = true;
        mainLetter.position.y = 1;
        letterGroup.add(mainLetter);

        // Effet de contour lumineux
        const glowMaterial = new THREE.ShaderMaterial({
            uniforms: {
                color: { value: new THREE.Color(0x4FC3F7) },
                viewVector: { value: camera.position }
            },
            vertexShader: `
                uniform vec3 viewVector;
                varying float intensity;
                void main() {
                    vec3 vNormal = normalize(normalMatrix * normal);
                    vec3 vNormel = normalize(normalMatrix * viewVector);
                    intensity = pow(0.6 - dot(vNormal, vNormel), 2.0);
                    gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
                }
            `,
            fragmentShader: `
                uniform vec3 color;
                varying float intensity;
                void main() {
                    gl_FragColor = vec4(color, intensity * 0.5);
                }
            `,
            side: THREE.BackSide,
            blending: THREE.AdditiveBlending,
            transparent: true
        });

        const glowMesh = new THREE.Mesh(geometry, glowMaterial);
        glowMesh.scale.multiplyScalar(1.05);
        glowMesh.position.copy(mainLetter.position);
        letterGroup.add(glowMesh);

        // Animation properties
        letterGroup.userData.initialY = 1;
        letterGroup.userData.floatSpeed = 0.002;
        letterGroup.userData.floatAmplitude = 0.1;
        letterGroup.userData.rotationSpeed = 0.001;
        letterGroup.userData.time = Math.random() * Math.PI * 2;

        scene.add(letterGroup);
        letterMesh = letterGroup;
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
let currentStageId = 1;

// Fonction de récupération des lettres
async function fetchLetters() {
    try {
        const response = await fetch(`/letters?stage_id=${currentStageId}`);
        if (!response.ok) throw new Error(`Erreur HTTP: ${response.status}`);

        letters = await response.json();

        if (letters.length > 0) {
            currentLetter = getNextLetter(letters);

            window.dispatchEvent(new CustomEvent("updateLetter", {
                detail: { letter: currentLetter }
            }));
        } else {
            if (currentStageId < 4) {
                currentStageId++;
                console.log(`🎉 Niveau terminé, passage au niveau ${currentStageId}`);
                await fetchLetters();
            } else {
                alert("Félicitations ! Vous avez terminé tous les niveaux !");
            }
        }
    } catch (error) {
        console.error("Erreur de chargement des lettres:", error);
    }
}

document.getElementById("stage-select")?.addEventListener("change", fetchLetters);

function getNextLetter(availableLetters) {
    const index = Math.floor(Math.random() * availableLetters.length);
    return availableLetters[index];
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
            success: success,
            stage_id: document.getElementById("stage-select").value  // Inclure l'ID du stage
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
    console.log(`Lettres pour le stage  :`, letters);
    // Mise à jour de la variable globale
    currentLetter = newLetter;
    
    // Chargement de la lettre
    loadLetter(newLetter);
    
    // Mise à jour du localStorage si nécessaire
    localStorage.setItem("currentLetter", newLetter);
    console.log("Lettre actuelle enregistrée:", newLetter);

    if (currentStageId <= 2) {
        instruction.innerHTML = `Cliquez sur le bouton puis dites à haute voix : <strong>"la lettre ${currentLetter}"</strong>`;
    } else {
        instruction.innerHTML = `Cliquez sur le bouton puis dites à haute voix le mot : <strong>"${currentLetter}"</strong>`;
    }
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

// 🚀 Démarrage de l'animation
animate();