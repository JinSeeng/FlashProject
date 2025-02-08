function checkPasswordStrength(password) {
    // Initialise les variables/prérequis pour la force du MDP
    const metrics = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        number: /[0-9]/.test(password),
        specialChar: /[^a-zA-Z0-9]/.test(password)
    };

    // Pour calculer la force du MDP
    let strength = 0;
    if (metrics.length) strength++;
    if (metrics.uppercase) strength++;
    if (metrics.number) strength++;
    if (metrics.specialChar) strength++;

    return {
        strength: strength,
        details: metrics
    };
}

function updatePasswordStrengthBar(passwordInput, strengthBar, strengthText) {
    const password = passwordInput.value;
    const result = checkPasswordStrength(password);

    // Pour reset les modifications de la barre en fonction de la force du MDP 
    strengthBar.classList.remove('weak', 'medium', 'strong');
    strengthText.textContent = '';

    if (password.length === 0) {
        // Pour vider la barre
        strengthBar.style.width = '0%';
        return;
    }

    // Pour associer la force du MDP à la couleur de la barre
    if (result.strength <= 1) {
        // Faible
        strengthBar.classList.add('weak');
        strengthBar.style.width = '33%';
        strengthText.textContent = 'Mot de passe faible';
        strengthText.style.color = '#e74c3c';
    } else if (result.strength === 2 || result.strength === 3) {
        // Moyen
        strengthBar.classList.add('medium');
        strengthBar.style.width = '66%';
        strengthText.textContent = 'Mot de passe moyen';
        strengthText.style.color = '#f39c12';
    } else {
        // Fort
        strengthBar.classList.add('strong');
        strengthBar.style.width = '100%';
        strengthText.textContent = 'Mot de passe fort';
        strengthText.style.color = '#2ecc71';
    }
}
// Fonction pour relier la force du MDP à la saisie de texte du joueur
function addPasswordStrengthMeter(passwordInputId) {
    const passwordInput = document.getElementById(passwordInputId);
    
    // Pour créer la barre et le texte d'en-dessous
    const strengthBar = document.createElement('div');
    strengthBar.id = 'passwordStrength';
    strengthBar.classList.add('strength-bar');

    const strengthText = document.createElement('div');
    strengthText.id = 'strengthText';
    strengthText.classList.add('strength-text');

    // Afficher les changements de la barre après la saisie du joueur
    passwordInput.parentNode.insertBefore(strengthBar, passwordInput.nextSibling);
    passwordInput.parentNode.insertBefore(strengthText, strengthBar.nextSibling);

    // Pour ajouter les EventListener
    passwordInput.addEventListener('input', () => {
        updatePasswordStrengthBar(passwordInput, strengthBar, strengthText);
    });
}