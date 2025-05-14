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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Result Modal -->
    <div id="resultModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-comment-dots me-2 text-primary"></i>
                        Résultat
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div id="modalResult" class="mb-4">
                        <!-- Success Icon -->
                        <div class="success-icon mb-3 d-none">
                            <i class="fas fa-check-circle text-success fa-3x"></i>
                        </div>
                        <!-- Error Icon -->
                        <div class="error-icon mb-3 d-none">
                            <i class="fas fa-times-circle text-danger fa-3x"></i>
                        </div>
                    </div>
                    <p id="modalMessage" class="mb-3 fs-5">Résultat ici...</p>
                    <div class="pronunciation-feedback mt-3 d-none">
                        <p class="text-muted mb-2">Votre prononciation :</p>
                        <div class="pronunciation-text fw-bold"></div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" id="tryAgainBtn">Réessayer</button>
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
    
    .modal-content {
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .modal-header, .modal-footer {
        background-color: transparent;
    }

    .modal-body {
        padding: 2rem;
    }
    
    #start {
        background: linear-gradient(45deg, #2196F3, #1976D2);
        border: none;
        padding: 1rem 2rem;
    }
    
    #skip {
        border: 2px solid #FFC107;
        color: #FFC107;
        padding: 1rem 2rem;
    }
    
    #skip:hover {
        background: #FFC107;
        color: white;
    }

    .success-icon, .error-icon {
        animation: scaleIn 0.3s ease-in-out;
    }

    .pronunciation-feedback {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
    }

    @keyframes scaleIn {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    @media (max-width: 768px) {
        .btn {
            width: 100%;
            margin: 0.5rem 0;
        }
        .modal-body {
            padding: 1.5rem;
        }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('resultModal'));
        const modalResult = document.getElementById('modalResult');
        const modalMessage = document.getElementById('modalMessage');
        const successIcon = modalResult.querySelector('.success-icon');
        const errorIcon = modalResult.querySelector('.error-icon');
        const pronunciationFeedback = document.querySelector('.pronunciation-feedback');
        const pronunciationText = document.querySelector('.pronunciation-text');
        const tryAgainBtn = document.getElementById('tryAgainBtn');

        // Fonction pour afficher le modal avec le résultat
        window.showResultModal = function(success, message, spokenWord) {
            // Reset previous state
            successIcon.classList.add('d-none');
            errorIcon.classList.add('d-none');
            pronunciationFeedback.classList.add('d-none');

            // Show appropriate icon
            if (success) {
                successIcon.classList.remove('d-none');
                modalMessage.classList.add('text-success');
            } else {
                errorIcon.classList.remove('d-none');
                modalMessage.classList.add('text-danger');
            }

            // Set message
            modalMessage.textContent = message;

            // Show pronunciation feedback if we have a spoken word
            if (spokenWord) {
                pronunciationFeedback.classList.remove('d-none');
                pronunciationText.textContent = spokenWord;
            }

            // Show modal
            modal.show();
        };

        // Event listener for Try Again button
        tryAgainBtn.addEventListener('click', function() {
            modal.hide();
            // Trigger the start button after a short delay
            setTimeout(() => {
                document.getElementById('start').click();
            }, 500);
        });

        // Clean up when modal is hidden
        document.getElementById('resultModal').addEventListener('hidden.bs.modal', function () {
            modalMessage.className = 'mb-3 fs-5'; // Reset message classes
            pronunciationFeedback.classList.add('d-none');
        });
    });
    </script>
@endsection
