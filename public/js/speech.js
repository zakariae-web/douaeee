window.onload = function() {
    const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    recognition.lang = 'fr-FR';
    recognition.interimResults = false;

    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    document.getElementById('start').addEventListener('click', () => {
        recognition.start();
    });

    recognition.onresult = function(event) {
        const spokenWord = event.results[0][0].transcript;
        document.getElementById('result').textContent = "Vous avez dit : " + spokenWord;
    
        fetch('/attempt', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,  // Add CSRF token
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                letter: "A",  // À remplacer dynamiquement
                success: spokenWord.toLowerCase() === "a"
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => console.log(data))
        .catch(error => console.error('Error:', error));
    };

    recognition.onerror = function(event) {
        console.error('Recognition error:', event.error);
    };
};