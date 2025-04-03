window.onload = function() {
    const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    recognition.lang = 'fr-FR';
    recognition.interimResults = false;

    // Récupérer le token CSRF depuis la balise meta
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    document.getElementById('start').addEventListener('click', () => {
        recognition.start();
    });

    recognition.onresult = function(event) {
        const spokenWord = event.results[0][0].transcript.trim(); // 🗣 Mot prononcé sans modification
        const spokenWordUpper = spokenWord.toUpperCase(); // 🔤 Convertir en majuscules
        document.getElementById('result').textContent = "Vous avez dit : " + spokenWord;

        // Récupérer la lettre actuelle depuis localStorage
        const currentLetter = localStorage.getItem("currentLetter") || "A";

        // Vérification du succès
         const isCorrect = spokenWord.toUpperCase().includes(currentLetter.toUpperCase());

        fetch('/attempt', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,  // Ajout du token CSRF
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                letter: currentLetter,   // 📌 Lettre attendue
                attempted_word: spokenWord, // 🗣 Mot réellement prononcé
                success: isCorrect  // ✅ Succès ou non
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log("Saved:", data);
            if (isCorrect) {
                document.getElementById('result').textContent += " ✅ Correct!";
                
                setTimeout(() => {
                    fetchNextLetter(); // Charger la prochaine lettre
                }, 1000);
            } else {
                document.getElementById('result').textContent += " ❌ Essayez encore.";
            }
        })
        .catch(error => console.error('Erreur:', error));
    };

    recognition.onerror = function(event) {
        console.error('Erreur de reconnaissance:', event.error);
    };

    function fetchNextLetter() {
        fetch('/random-letter')
            .then(response => response.json())
            .then(data => {
                const nextLetter = data.letter || "A"; 
                localStorage.setItem("currentLetter", nextLetter);
                document.getElementById('result').textContent = "Nouvelle lettre : " + nextLetter;
    
                window.dispatchEvent(new CustomEvent("updateLetter", { 
                    detail: { letter: nextLetter } 
                }));
            })
            .catch(error => console.error('Erreur lors du chargement de la lettre:', error));
    }
    
    
};
