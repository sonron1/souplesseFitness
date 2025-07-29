document.addEventListener('DOMContentLoaded', function() {
    console.log('Register JS - DM Sans loaded');

    // Variables pour le système d'étapes
    let currentStep = 1;
    const totalSteps = 5;

    // Fonctions pour les étapes
    function nextStep() {
        if (currentStep < totalSteps) {
            currentStep++;
            updateStepDisplay();
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            currentStep--;
            updateStepDisplay();
        }
    }

    function updateStepDisplay() {
        // Mettre à jour les contenus d'étapes
        const stepContents = document.querySelectorAll('.step-content');
        stepContents.forEach(content => content.classList.remove('active'));

        const currentContent = document.querySelector(`[data-step="${currentStep}"].step-content`);
        if (currentContent) {
            currentContent.classList.add('active');
        }

        // Mettre à jour les indicateurs d'étapes
        const steps = document.querySelectorAll('.step');
        steps.forEach((step, index) => {
            const stepNumber = index + 1;
            step.classList.remove('active', 'completed');

            if (stepNumber === currentStep) {
                step.classList.add('active');
            } else if (stepNumber < currentStep) {
                step.classList.add('completed');
            }
        });

        // Mettre à jour la barre de progression
        const progressBar = document.getElementById('progressBar');
        if (progressBar) {
            const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
            progressBar.style.width = progress + '%';
        }
    }

    // Initialiser le système d'étapes
    const stepContents = document.querySelectorAll('.step-content');
    if (stepContents.length > 0) {
        updateStepDisplay();

        // Event listeners pour les boutons
        document.querySelectorAll('.btn-next').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                nextStep();
            });
        });

        document.querySelectorAll('.btn-prev').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                prevStep();
            });
        });
    }

    // Gestion du type d'utilisateur et des rôles
    const userTypeSelect = document.getElementById('registration_form_userType');
    if (userTypeSelect) {
        userTypeSelect.addEventListener('change', function() {
            const selectedRole = this.value;
            console.log('Type d\'utilisateur sélectionné:', selectedRole);

            // Ici on peut ajouter une logique spécifique selon le rôle
            if (selectedRole === 'ROLE_COACH') {
                // Logique spécifique pour les coachs
                console.log('Mode coach activé');
            } else if (selectedRole === 'ROLE_CLIENT') {
                // Logique spécifique pour les clients
                console.log('Mode client activé');
            }
        });
    }

    // Validation du formulaire
    const registerForm = document.getElementById('registrationForm');
    if (registerForm) {
        // Validation des mots de passe
        const passwordInput = document.getElementById('registration_form_plainPassword');
        const confirmPasswordInput = document.getElementById('registration_form_confirmPassword');

        if (passwordInput && confirmPasswordInput) {
            function validatePasswords() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;

                if (password.length > 0 && password.length < 6) {
                    showError(passwordInput, 'Le mot de passe doit contenir au moins 6 caractères');
                    return false;
                }

                if (password !== confirmPassword && confirmPassword !== '') {
                    showError(confirmPasswordInput, 'Les mots de passe ne correspondent pas');
                    return false;
                }

                clearError(passwordInput);
                clearError(confirmPasswordInput);
                return true;
            }

            passwordInput.addEventListener('input', validatePasswords);
            confirmPasswordInput.addEventListener('input', validatePasswords);
        }

        // Validation avant soumission
        registerForm.addEventListener('submit', function(e) {
            let isValid = true;

            // Valider tous les champs requis
            const requiredFields = registerForm.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    showError(field, 'Ce champ est obligatoire');
                    isValid = false;
                } else {
                    clearError(field);
                }
            });

            // Valider les mots de passe
            if (passwordInput && confirmPasswordInput) {
                if (!validatePasswords()) {
                    isValid = false;
                }
            }

            if (!isValid) {
                e.preventDefault();
                console.log('Formulaire invalide');
            } else {
                console.log('Formulaire valide - soumission...');
            }
        });
    }

    // Fonctions utilitaires pour les erreurs
    function showError(field, message) {
        clearError(field);
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
        field.classList.add('is-invalid');
    }

    function clearError(field) {
        const existingError = field.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }
        field.classList.remove('is-invalid');
    }
});
