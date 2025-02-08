// Variables globales pour suivre les dates et heures des messages
let currentDate = '';
let lastMessageTime = '00:00';
let lastMessageDate = '';

/**
 * Bascule l'affichage de la fenêtre de chat
 * Met à jour les messages et scroll en bas si la fenêtre est active
 */
function toggleChat() {
    const chatWindow = document.getElementById('chatWindow');
    chatWindow.classList.toggle('active');
    
    if (chatWindow.classList.contains('active')) {
        scrollToBottom();
        updateMessages();
    }
}

/**
 * Fait défiler la fenêtre de chat vers le bas
 */
function scrollToBottom() {
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

/**
 * Formate une date en français avec gestion spéciale pour "Aujourd'hui" et "Hier"
 * @param {string} dateStr - La date à formater
 * @returns {string} La date formatée en français
 */
function formatDateFr(dateStr) {
    const date = new Date(dateStr);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const yesterday = new Date(today);
    yesterday.setDate(yesterday.getDate() - 1);
    
    console.log(date);
    console.log(today);

    if (date >= today) {
        return "Aujourd'hui";
    } else if (date >= yesterday) {
        return "Hier";
    } else {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        return new Intl.DateTimeFormat('fr-FR', options).format(date);
    }
}

/**
 * Récupère les nouveaux messages du serveur et les ajoute au chat
 * Se relance automatiquement toutes les 5 secondes
 */
function updateMessages() {
    // Ne met à jour que si la fenêtre de chat est active
    if (!document.getElementById('chatWindow').classList.contains('active')) {
        return;
    }

    fetch(`get_messages.php?last=${lastMessageTime}&lastDate=${lastMessageDate}`)
        .then(response => response.json())
        .then(messages => {
            messages.forEach(data => {
                appendMessage(data, data.isSender);
                lastMessageTime = data.time;
                lastMessageDate = data.date;
            });
            
            // Planifie la prochaine mise à jour
            setTimeout(updateMessages, 5000);
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des messages:', error);
            setTimeout(updateMessages, 5000);
        });
}

// Initialisation au chargement de la page
window.addEventListener('DOMContentLoaded', function() {
    // Récupère la dernière date affichée
    const lastDateSeparator = document.querySelector('.date-separator:last-child');
    if (lastDateSeparator) {
        currentDate = lastDateSeparator.getAttribute('data-date');
    }

    // Récupère l'heure du dernier message
    const lastMessage = document.querySelector('.chat-bubble:last-child .timestamp');
    if (lastMessage) {
        lastMessageTime = lastMessage.textContent;
    }

    scrollToBottom();
    updateMessages();

    // Configuration du formulaire d'envoi de messages
    const chatForm = document.querySelector('.chat-input');
    if (chatForm) {
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const input = this.querySelector('input[name="message"]');
            const message = input.value.trim();
            
            if (message) {
                const submitButton = this.querySelector('button');
                submitButton.disabled = true;

                // Envoie le message au serveur
                fetch('send_message.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `message=${encodeURIComponent(message)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        appendMessage(data, true);
                        input.value = '';
                        lastMessageTime = data.time;
                        lastMessageDate = data.date;
                        scrollToBottom();
                    } else {
                        alert(data.error || 'Erreur lors de l\'envoi du message');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors de l\'envoi du message');
                })
                .finally(() => {
                    submitButton.disabled = false;
                    input.focus();
                });
            }
        });

        // Gestion de la touche Entrée pour envoyer le message
        const input = chatForm.querySelector('input[name="message"]');
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatForm.dispatchEvent(new Event('submit'));
            }
        });
    }
});

/**
 * Ajoute un nouveau message dans la fenêtre de chat
 * @param {Object} data - Les données du message
 * @param {boolean} isSender - Indique si l'utilisateur actuel est l'expéditeur
 */
function appendMessage(data, isSender) {
    const chatMessages = document.getElementById('chatMessages');
    
    // Vérifie si un séparateur de date existe déjà pour cette date
    const existingTodaySeparator = document.querySelector('.date-separator[data-date="' + data.date + '"]');
    
    // Ajoute un nouveau séparateur de date si nécessaire
    if (data.date && data.date !== currentDate && !existingTodaySeparator) {
        const dateWrapper = document.createElement('div');
        dateWrapper.className = 'date-separator-wrapper';
        
        const dateDiv = document.createElement('div');
        dateDiv.className = 'date-separator';
        dateDiv.setAttribute('data-date', data.date);
        dateDiv.textContent = formatDateFr(data.date);
        
        dateWrapper.appendChild(dateDiv);
        chatMessages.appendChild(dateWrapper);
        currentDate = data.date;
    }
    
    // Crée et ajoute le message
    const messageDiv = document.createElement('div');
    messageDiv.className = `chat-bubble ${isSender ? 'user' : 'other'}`;
    
    // Fonction de sécurité pour échapper le HTML
    const sanitizeHTML = (str) => {
        const temp = document.createElement('div');
        temp.textContent = str;
        return temp.innerHTML;
    };
    
    // Construction du HTML du message
    messageDiv.innerHTML = `
        <span class="username">${sanitizeHTML(data.username)}</span>
        <span class="message-content">${sanitizeHTML(data.message)}</span>
        <span class="timestamp">${sanitizeHTML(data.time)}</span>
    `;
    
    chatMessages.appendChild(messageDiv);
    
    // Limite le nombre de messages affichés à 50
    const messages = chatMessages.getElementsByClassName('chat-bubble');
    if (messages.length > 50) {
        messages[0].remove();
    }
    
    // Défilement automatique si l'utilisateur est proche du bas
    const shouldScroll = chatMessages.scrollTop + chatMessages.clientHeight >= chatMessages.scrollHeight - 100;
    if (shouldScroll) {
        scrollToBottom();
    }
}

// Gestion du défilement automatique
document.getElementById('chatMessages').addEventListener('scroll', function() {
    const shouldScroll = this.scrollTop + this.clientHeight >= this.scrollHeight - 100;
    if (shouldScroll) {
        scrollToBottom();
    }
});