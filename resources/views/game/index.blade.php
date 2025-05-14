@extends('layouts.app')

@section('title', 'Jeu de Prononciation')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header Section -->
                <div class="text-center mb-4">
                    <h1 class="display-5 mb-3">Exercice de Prononciation</h1>
                    <p class="lead text-muted">Améliorez votre prononciation en réalité virtuelle</p>
                </div>

                <!-- Game Interface -->
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4">
                        <!-- Instructions -->
                        <div class="text-center mb-4">
                            <div class="instruction-box p-3 bg-light rounded-3 mb-4">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                <span id="instruction" class="fs-5">Préparez-vous...</span>
                            </div>
                            <div class="d-flex justify-content-center gap-3 mb-4">
                                <button class="btn btn-primary btn-lg px-5" id="start">
                                    <i class="fas fa-microphone me-2"></i>
                                    Dites la lettre
                                </button>
                            </div>
                        </div>
                        <div id="result-container" class="mt-4 mb-4 text-center d-none">
                            <div class="alert custom-alert alert-dismissible fade show" role="alert">
                                <div class="result-icon-wrapper">
                                    <i class="result-icon"></i>
                                 </div>
                                <div class="result-content">
                                    <span id="result-message"></span>
                                    <div class="pronunciation-text mt-2"></div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                        <!-- AR Container -->
                        <div class="ar-wrapper mb-4">
                            <div id="ar-container" class="rounded-3 overflow-hidden" style="width: 100%; height: 70vh; border: 2px solid #e9ecef; background-color: #f8f9fa;"></div>
                        </div>

                        <!-- Controls -->
                        <div class="d-flex justify-content-center mt-4">
                            <button class="btn btn-outline-warning btn-lg px-5" id="skip">
                                <i class="fas fa-forward me-2"></i>
                                Passer cette lettre
                            </button>
                        </div>

                        <!-- Result Message Container -->

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .instruction-box {
        background: linear-gradient(145deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
    }
    
    .ar-wrapper {
        position: relative;
        transition: all 0.3s ease;
    }
    
    .ar-wrapper:hover {
        transform: scale(1.01);
    }
    
    .btn {
        transition: all 0.3s ease;
        min-width: 200px;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    /* Styles améliorés pour le conteneur de résultat */
    #result-container {
        perspective: 1000px;
    }

    .custom-alert {
        display: flex;
        align-items: center;
        max-width: 600px;
        margin: 0 auto;
        padding: 1rem;
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transform-origin: top;
        animation: slideIn 0.3s ease-out;
        background: white;
    }

    .result-icon-wrapper {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-right: 1rem;
    }

    .result-icon {
        font-size: 24px;
        animation: scaleIn 0.3s ease-out;
    }

    .result-content {
        flex: 1;
        text-align: left;
    }

    .pronunciation-text {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .custom-alert.alert-success {
        background: linear-gradient(145deg, #ffffff, #d4edda);
        border-left: 4px solid #28a745;
    }

    .custom-alert.alert-success .result-icon-wrapper {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }
    
    .custom-alert.alert-danger {
        background: linear-gradient(145deg, #ffffff, #f8d7da);
        border-left: 4px solid #dc3545;
    }

    .custom-alert.alert-danger .result-icon-wrapper {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    .btn-close {
        opacity: 0.5;
        transition: opacity 0.2s;
    }

    .btn-close:hover {
        opacity: 1;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px) rotateX(-10deg);
        }
        to {
            opacity: 1;
            transform: translateY(0) rotateX(0);
        }
    }

    @keyframes scaleIn {
        from {
            transform: scale(0);
        }
        to {
            transform: scale(1);
        }
    }

    /* Ajout d'une animation de disparition */
    .custom-alert.fade-out {
        animation: slideOut 0.3s ease-in forwards;
    }

    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateY(0) rotateX(0);
        }
        to {
            opacity: 0;
            transform: translateY(-20px) rotateX(10deg);
        }
    }

    @media (max-width: 768px) {
        .btn {
            width: 100%;
            margin: 0.5rem 0;
        }
    }
    </style>

    <script>
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
    </script>
@endsection
