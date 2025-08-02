
// --- Animation au scroll avec Animate.css ---
document.addEventListener('DOMContentLoaded', function() {
    const observed = document.querySelectorAll('.animate-on-scroll');
    if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const anim = el.dataset.animate || 'fadeIn';
                    el.classList.add('animate__animated', `animate__${anim}`);
                    observer.unobserve(el);
                }
            });
        }, { threshold: 0.15 });
        observed.forEach(el => io.observe(el));
    } else {
        observed.forEach(el => {
            const anim = el.dataset.animate || 'fadeIn';
            el.classList.add('animate__animated', `animate__${anim}`);
        });
    }
});

// Validation des numéros de téléphone béninois mis à jour
function validateBeninPhone(phone) {
    const regex = /^01[0-9]{8}$/;
    return regex.test(phone.replace(/\s+/g, ''));
}

// Validation de l'âge minimum
function validateAge(birthDate) {
    const today = new Date();
    const birth = new Date(birthDate);
    let age = today.getFullYear() - birth.getFullYear();
    const monthDiff = today.getMonth() - birth.getMonth();

    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
        age--;
    }

    return age >= 18;
}

// Amélioration de l'affichage du select
document.addEventListener('DOMContentLoaded', function() {
    // Formatage automatique des numéros de téléphone
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            e.target.value = value;
        });

        input.addEventListener('blur', function(e) {
            const phone = e.target.value.replace(/\s+/g, '');
            if (phone && !validateBeninPhone(phone)) {
                e.target.classList.add('field-invalid');
                showError(e.target, 'Numéro de téléphone béninois invalide (doit commencer par 01)');
            } else {
                e.target.classList.remove('field-invalid');
                hideError(e.target);
            }
        });
    });

    // Validation de l'âge en temps réel
    const birthDateInput = document.querySelector('input[name="registration_form[birthDate]"]');
    if (birthDateInput) {
        birthDateInput.addEventListener('change', function(e) {
            const birthDate = e.target.value;
            if (birthDate && !validateAge(birthDate)) {
                e.target.classList.add('field-invalid');
                showError(e.target, 'Vous devez être âgé d\'au moins 18 ans pour vous inscrire');
            } else {
                e.target.classList.remove('field-invalid');
                hideError(e.target);
            }
        });
    }

    // Amélioration du select avec placeholder - Fix pour les flèches multiples
    const selectInputs = document.querySelectorAll('.custom-select');
    selectInputs.forEach(select => {
        // Supprimer les attributs qui peuvent causer des flèches multiples
        select.removeAttribute('data-bs-toggle');
        select.removeAttribute('data-toggle');

        // Mettre à jour le label quand la sélection change
        select.addEventListener('change', function(e) {
            const label = e.target.parentElement.querySelector('label');
            if (e.target.value !== '') {
                label.style.top = '-0.5rem';
                label.style.left = '0.5rem';
                label.style.fontSize = '0.9rem';
                label.style.color = '#FFE457';
                label.style.fontWeight = 'bold';
            } else {
                label.style.top = '1rem';
                label.style.left = '1rem';
                label.style.fontSize = '1rem';
                label.style.color = '#6C757D';
                label.style.fontWeight = 'normal';
            }
        });

        // Initialiser l'état du label
        if (select.value !== '') {
            const label = select.parentElement.querySelector('label');
            if (label) {
                label.style.top = '-0.5rem';
                label.style.left = '0.5rem';
                label.style.fontSize = '0.9rem';
                label.style.color = '#FFE457';
                label.style.fontWeight = 'bold';
            }
        }
    });

    // Validation des mots de passe
    const passwordInput = document.querySelector('#password');
    const confirmPasswordInput = document.querySelector('#confirmPassword');

    if (passwordInput && confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', function(e) {
            if (passwordInput.value !== e.target.value) {
                e.target.classList.add('field-invalid');
                showError(e.target, 'Les mots de passe ne correspondent pas');
            } else {
                e.target.classList.remove('field-invalid');
                hideError(e.target);
            }
        });
    }

    // Gestion des étapes
    let currentStep = 1;
    const totalSteps = 5;

    function updateProgressBar() {
        const progressBar = document.getElementById('progressBar');
        if (progressBar) {
            const progress = (currentStep - 1) / (totalSteps - 1) * 100;
            progressBar.style.width = progress + '%';
        }
    }

    function updateStepIndicator() {
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
    }

    function showStep(step) {
        const stepContents = document.querySelectorAll('.step-content');
        stepContents.forEach((content, index) => {
            content.classList.remove('active');
            if (index + 1 === step) {
                content.classList.add('active');
            }
        });
    }

    function validateCurrentStep() {
        const currentStepContent = document.querySelector(`.step-content[data-step="${currentStep}"]`);
        if (!currentStepContent) return true;

        const requiredFields = currentStepContent.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('field-invalid');
                isValid = false;
            } else {
                field.classList.remove('field-invalid');
            }
        });

        return isValid;
    }

    // Boutons de navigation
    document.querySelectorAll('.btn-next').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (validateCurrentStep() && currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
                updateStepIndicator();
                updateProgressBar();
            }
        });
    });

    document.querySelectorAll('.btn-prev').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
                updateStepIndicator();
                updateProgressBar();
            }
        });
    });

    // Initialisation
    updateStepIndicator();
    updateProgressBar();
});

// Fonctions utilitaires pour les erreurs
function showError(element, message) {
    hideError(element);
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    element.parentElement.appendChild(errorDiv);
}

function hideError(element) {
    const existingError = element.parentElement.querySelector('.invalid-feedback');
    if (existingError) {
        existingError.remove();
    }
}

// Export pour utilisation dans d'autres fichiers
window.registerUtils = {
    validateBeninPhone,
    validateAge,
    showError,
    hideError
};
