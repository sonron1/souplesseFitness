document.addEventListener('DOMContentLoaded', function() {
    const confirmModal = document.getElementById('confirmModal');
    const confirmForm = document.getElementById('confirmForm');
    const filterSelect = document.getElementById('filterAbonnements');

    // ===========================
    // GESTION DU FILTRE
    // ===========================
    if (filterSelect) {
        filterSelect.addEventListener('change', function() {
            const selectedType = this.value;
            const allItems = document.querySelectorAll('.abonnement-item');
            const allSections = document.querySelectorAll('#promoSection, #simpleSection, #coupleSection');

            // Animation de transition
            allItems.forEach(function(item) {
                item.style.transition = 'all 0.3s ease';
            });

            // Filtrage des éléments
            allItems.forEach(function(item) {
                const itemType = item.getAttribute('data-type');
                const itemCategory = item.getAttribute('data-category');

                let shouldShow = false;

                if (selectedType === 'all') {
                    shouldShow = true;
                } else if (selectedType === 'mensuel_couple') {
                    // Afficher seulement les abonnements couple
                    shouldShow = (itemCategory === 'couple' || itemType === 'mensuel_couple');
                } else {
                    // Filtrer par type
                    shouldShow = (itemType === selectedType);
                }

                if (shouldShow) {
                    item.style.display = '';
                    item.style.opacity = '0';
                    setTimeout(function() {
                        item.style.opacity = '1';
                        item.style.transform = 'scale(1)';
                    }, 100);
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.8)';
                    setTimeout(function() {
                        item.style.display = 'none';
                    }, 300);
                }
            });

            // Masquer les sections vides avec animation
            setTimeout(function() {
                allSections.forEach(function(section) {
                    const visibleItems = section.querySelectorAll('.abonnement-item:not([style*="display: none"])');
                    if (visibleItems.length === 0) {
                        section.style.opacity = '0';
                        section.style.transform = 'translateY(-20px)';
                        setTimeout(function() {
                            section.style.display = 'none';
                        }, 300);
                    } else {
                        section.style.display = '';
                        section.style.opacity = '1';
                        section.style.transform = 'translateY(0)';
                    }
                });
            }, 400);
        });
    }

    // ===========================
    // GESTION DU MODAL DE CONFIRMATION
    // ===========================
    document.querySelectorAll('.btn-subscribe').forEach(function(button) {
        button.addEventListener('click', function() {
            const abonnementId = this.getAttribute('data-abonnement-id');
            const abonnementNom = this.getAttribute('data-abonnement-nom');
            const abonnementPrix = this.getAttribute('data-abonnement-prix');
            const abonnementPrixOriginal = this.getAttribute('data-abonnement-prix-original');
            const abonnementType = this.getAttribute('data-abonnement-type');
            const abonnementDescription = this.getAttribute('data-abonnement-description');
            const isPromo = this.getAttribute('data-is-promo') === 'true';

            // Mettre à jour le contenu du modal
            if (document.getElementById('modal-abonnement-nom')) {
                document.getElementById('modal-abonnement-nom').textContent = abonnementNom;
            }
            if (document.getElementById('modal-abonnement-prix')) {
                document.getElementById('modal-abonnement-prix').textContent = abonnementPrix;
            }
            if (document.getElementById('modal-prix-final')) {
                document.getElementById('modal-prix-final').textContent = abonnementPrix;
            }
            if (document.getElementById('modal-abonnement-type')) {
                // Formatage du type pour l'affichage
                let typeDisplay = abonnementType;
                if (abonnementType === 'seance') typeDisplay = 'À l\'unité';
                else if (abonnementType === 'carnet') typeDisplay = 'Carnet';
                else if (abonnementType === 'mensuel_couple') typeDisplay = 'Couple';
                else typeDisplay = abonnementType.charAt(0).toUpperCase() + abonnementType.slice(1);

                document.getElementById('modal-abonnement-type').textContent = typeDisplay;
            }
            if (document.getElementById('modal-abonnement-description')) {
                document.getElementById('modal-abonnement-description').textContent = abonnementDescription || 'Aucune description disponible';
            }

            // Gestion du badge promo et prix original
            const promoBadge = document.getElementById('modal-promo-badge');
            const prixOriginalDiv = document.getElementById('modal-prix-original');

            if (isPromo && abonnementPrixOriginal && promoBadge && prixOriginalDiv) {
                promoBadge.classList.remove('d-none');
                prixOriginalDiv.classList.remove('d-none');
                const prixOriginalValue = document.getElementById('modal-prix-original-value');
                if (prixOriginalValue) {
                    prixOriginalValue.textContent = abonnementPrixOriginal;
                }
            } else {
                if (promoBadge) promoBadge.classList.add('d-none');
                if (prixOriginalDiv) prixOriginalDiv.classList.add('d-none');
            }

            // Mettre à jour l'action du formulaire
            if (confirmForm) {
                confirmForm.setAttribute('action', '/client/souscrire/' + abonnementId);
            }

            // Animation d'entrée du modal
            if (confirmModal) {
                confirmModal.addEventListener('shown.bs.modal', function() {
                    this.querySelector('.modal-content').style.animation = 'modalSlideIn 0.3s ease-out';
                }, { once: true });
            }
        });
    });

    // ===========================
    // ANIMATIONS AU SCROLL
    // ===========================
    const observeCards = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.style.animationDelay = Math.random() * 0.3 + 's';
                entry.target.classList.add('animate');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    // Observer toutes les cartes d'abonnement
    document.querySelectorAll('.abonnement-card').forEach(function(card) {
        observeCards.observe(card);
    });

    // ===========================
    // SMOOTH SCROLL POUR MOBILE
    // ===========================
    document.querySelectorAll('.abonnements-scroll').forEach(function(scrollContainer) {
        let isScrolling = false;
        let startX = 0;
        let scrollLeft = 0;

        // Touch events pour un scroll plus fluide sur mobile
        scrollContainer.addEventListener('touchstart', function(e) {
            isScrolling = true;
            startX = e.touches[0].pageX - scrollContainer.offsetLeft;
            scrollLeft = scrollContainer.scrollLeft;
        });

        scrollContainer.addEventListener('touchmove', function(e) {
            if (!isScrolling) return;
            e.preventDefault();
            const x = e.touches[0].pageX - scrollContainer.offsetLeft;
            const walk = (x - startX) * 2;
            scrollContainer.scrollLeft = scrollLeft - walk;
        });

        scrollContainer.addEventListener('touchend', function() {
            isScrolling = false;
        });
    });

    // ===========================
    // GESTION DES ERREURS
    // ===========================
    window.addEventListener('error', function(e) {
        console.warn('Erreur dans abonnement.js:', e.error);
    });

    // ===========================
    // UTILITAIRES
    // ===========================

    // Fonction pour formater les prix
    function formatPrice(price) {
        return new Intl.NumberFormat('fr-FR').format(price);
    }

    // Fonction pour valider les données d'abonnement
    function validateAbonnementData(data) {
        const required = ['id', 'nom', 'prix', 'type'];
        return required.every(field => data[field] !== null && data[field] !== undefined);
    }

    // Fonction pour animer l'apparition des éléments
    function animateElement(element, animationClass) {
        element.classList.add(animationClass);
        element.addEventListener('animationend', function() {
            element.classList.remove(animationClass);
        }, { once: true });
    }

    // ===========================
    // ACCESSIBILITY IMPROVEMENTS
    // ===========================

    // Améliorer la navigation au clavier
    document.querySelectorAll('.btn-subscribe').forEach(function(button) {
        button.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    });

    // Améliorer l'accessibilité du filtre
    if (filterSelect) {
        filterSelect.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                this.dispatchEvent(new Event('change'));
            }
        });

        // Annoncer les changements pour les lecteurs d'écran
        filterSelect.addEventListener('change', function() {
            const announcement = document.createElement('div');
            announcement.setAttribute('aria-live', 'polite');
            announcement.setAttribute('aria-atomic', 'true');
            announcement.className = 'sr-only';
            announcement.textContent = `Filtre appliqué: ${this.options[this.selectedIndex].text}`;
            document.body.appendChild(announcement);

            setTimeout(function() {
                document.body.removeChild(announcement);
            }, 1000);
        });
    }

    console.log('Abonnement.js initialisé avec succès');
});
