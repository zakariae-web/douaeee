 document.addEventListener('DOMContentLoaded', function() {
        const resultContainer = document.getElementById('result-container');
        const resultMessage = document.getElementById('result-message');
        const pronunciationText = document.querySelector('.pronunciation-text');

        window.showResultMessage = function(message, isSuccess, spokenWord = '') {
            const alert = resultContainer.querySelector('.alert');
            const icon = resultContainer.querySelector('.result-icon');
            
            // Reset classes
            alert.classList.remove('alert-success', 'alert-danger', 'fade-out');
            
            // Set message and icon
            resultMessage.innerHTML = message;
            icon.className = 'result-icon fas ' + (isSuccess ? 'fa-check-circle' : 'fa-times-circle');
            
            // Set alert type
            alert.classList.add(isSuccess ? 'alert-success' : 'alert-danger');

            // Show pronunciation if available
            if (spokenWord) {
                pronunciationText.textContent = `Prononciation : "${spokenWord}"`;
                pronunciationText.classList.remove('d-none');
            } else {
                pronunciationText.classList.add('d-none');
            }
            
            // Show container
            resultContainer.classList.remove('d-none');
            
            // Auto-hide success messages after 3 seconds
            if (isSuccess) {
                setTimeout(() => {
                    alert.classList.add('fade-out');
                    setTimeout(() => {
                        resultContainer.classList.add('d-none');
                        alert.classList.remove('fade-out');
                    }, 300);
                }, 3000);
            }
        };

        // Gérer la fermeture manuelle
        document.querySelectorAll('.btn-close').forEach(button => {
            button.addEventListener('click', function() {
                const alert = this.closest('.alert');
                alert.classList.add('fade-out');
            setTimeout(() => {
                    resultContainer.classList.add('d-none');
                    alert.classList.remove('fade-out');
                }, 300);
        });
        });
    });