window.onload = function () {
    const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    recognition.lang = 'fr-FR';
    recognition.interimResults = false;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let currentStageId = 1;
    const instruction = document.getElementById('instruction');
    const result = document.getElementById('result');

    let currentLetter = "A";

    document.getElementById('start').addEventListener('click', () => {
        recognition.start();
    });



    function updateStageLabel() {
        const stageLabel = document.getElementById('stage-label');
        if (stageLabel && stageSelect) {
            const label = stageSelect.selectedOptions[0].text;
            stageLabel.textContent = "Niveau : " + label;
        }
    }

    function playLetterSound(letter) {
        const audio = new Audio(`/audio/letters/${letter.toUpperCase()}.mp3`);
        audio.play();
    }

function fetchNextLetter() {
    fetch(`/letters?stage_id=${currentStageId}`)
        .then(response => response.json())
        .then(data => {
            if (!data.length) {
                if (currentStageId < 4) {
                    currentStageId++;
                    console.log(`✅ Niveau ${currentStageId - 1} terminé, passage à ${currentStageId}`);
                    fetchNextLetter();
                } else {
                    alert("🎉 Félicitations ! Vous avez terminé tous les niveaux !");
                }
                return;
            }

            currentLetter = data[Math.floor(Math.random() * data.length)];
            localStorage.setItem("currentLetter", currentLetter);

            // Adapter l'instruction selon le niveau
            if (currentStageId <= 2) {
                instruction.innerHTML = `Cliquez sur le bouton puis dites à haute voix : <strong>"la lettre ${currentLetter}"</strong>`;
            } else {
                instruction.innerHTML = `Cliquez sur le bouton puis dites à haute voix : <strong>"le mot ${currentLetter}"</strong>`;
            }

            window.dispatchEvent(new CustomEvent("updateLetter", {
                detail: { letter: currentLetter }
            }));
        })
        .catch(error => console.error("Erreur chargement lettre :", error));
}


  recognition.onresult = function (event) {
    const spokenWord = event.results[0][0].transcript.trim();
    result.textContent = "Vous avez dit : " + spokenWord;

    const stageId = currentStageId;


    let isCorrect = false;

    if (stageId === 1 || stageId === 2) {
        // Attente d’une réponse de type "la lettre A"
        isCorrect = spokenWord.toUpperCase().includes(`LA LETTRE ${currentLetter.toUpperCase()}`);
        playLetterSound(currentLetter);
    } else {
        // Pour les mots, comparaison simple
        isCorrect = spokenWord.toLowerCase() === currentLetter.toLowerCase();
        playLetterSound(currentLetter);
    }

    fetch('/attempt', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            letter: currentLetter,
            attempted_word: spokenWord,
            success: isCorrect
        })
    })
        .then(response => {
            if (!response.ok) throw new Error("Erreur réponse serveur");
            return response.json();
        })
        .then(data => {
            console.log("Tentative enregistrée :", data);
            if (isCorrect) {
                result.textContent += " ✅ Correct !";
                setTimeout(() => fetchNextLetter(), 1000);
            } else {
                result.textContent += " ❌ Essayez encore.";
            }
        })
        .catch(error => console.error("Erreur :", error));
};


    recognition.onerror = function (event) {
        console.error("Erreur de reconnaissance vocale :", event.error);
    };

    // Skip button (optionnel)
    const skipButton = document.getElementById('skip');
    if (skipButton) {
        skipButton.addEventListener('click', () => {
            fetch('/attempt', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    letter: currentLetter,
                    attempted_word: null,
                    success: false,
                    skipped: true
                })
            })
                .then(response => response.json())
                .then(data => {
                    console.log("Lettre skippée :", data);
                    fetchNextLetter();
                })
                .catch(error => console.error("Erreur skip :", error));
        });
    }

    // Init
    updateStageLabel();
    fetchNextLetter();
};

