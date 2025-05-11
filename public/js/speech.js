window.onload = function () {
    const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    recognition.lang = 'fr-FR';
    recognition.interimResults = false;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const stageSelect = document.getElementById('stage-select');
    const instruction = document.getElementById('instruction');
    const result = document.getElementById('result');

    let currentLetter = "A";

    document.getElementById('start').addEventListener('click', () => {
        recognition.start();
    });

    if (stageSelect) {
        stageSelect.addEventListener('change', () => {
            updateStageLabel();
            fetchNextLetter(); // Nouvelle lettre au changement de stage
        });
    }

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
        const stageId = stageSelect ? stageSelect.value : 1;

        fetch(`/letters?stage_id=${stageId}`)
            .then(response => response.json())
            .then(data => {
                if (!data.length) throw new Error("Aucune lettre trouvée");
                currentLetter = data[Math.floor(Math.random() * data.length)];
                localStorage.setItem("currentLetter", currentLetter);
                instruction.innerHTML = `Cliquez sur le bouton puis dites à haute voix : <strong>"la lettre ${currentLetter}"</strong>`;
                window.dispatchEvent(new CustomEvent("updateLetter", {
                    detail: { letter: currentLetter }
                }));
            })
            .catch(error => console.error("Erreur chargement lettre :", error));
    }

    recognition.onresult = function (event) {
        const spokenWord = event.results[0][0].transcript.trim();
        result.textContent = "Vous avez dit : " + spokenWord;

        const isCorrect = spokenWord.toUpperCase().includes(`LA LETTRE ${currentLetter}`);
        playLetterSound(currentLetter);

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
