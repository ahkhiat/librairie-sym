import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

// Boutons + et -
document.addEventListener('DOMContentLoaded', function() {
    const buttonPlus = document.getElementById('button-plus');
    const buttonMinus = document.getElementById('button-minus');
    const quantityInput = document.getElementById('quantity-input');

    buttonPlus.addEventListener('click', function() {
        let value = parseInt(quantityInput.value);
        quantityInput.value = value + 1;
    });

    buttonMinus.addEventListener('click', function() {
        let value = parseInt(quantityInput.value);
        if (value > 1) {
            quantityInput.value = value - 1;
        }
    });
});

// Ajout panier
document.addEventListener('DOMContentLoaded', function () {
    
    let addToCartButton = document.querySelector("#add-to-cart");

    addToCartButton.addEventListener('click', function (e) {
        e.preventDefault();

        let articleId = this.getAttribute('data-article-id');
        let userId = this.getAttribute('user-id')
        let quantityInput = document.querySelector("#quantity-input").value;


        fetch('/ajoutpanier', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ edition_id: articleId, user_id: userId, quantite: quantityInput })
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de l\'ajout de l\'article au panier.');
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour mettre à jour les quantités
    function updateQuantity(id, change) {
        const quantityInput = document.getElementById('quantity-' + id);
        let currentQuantity = parseInt(quantityInput.value);
        let newQuantity = currentQuantity + change;

        if (newQuantity > 0) {
            // Mettre à jour la quantité dans l'input
            quantityInput.value = newQuantity;

            // Envoyer une requête AJAX pour mettre à jour la quantité sur le serveur
            fetch('/update-quantity', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ id: id, quantite: newQuantity })
            })
            .then(response => response.json())
            .then(data => {
                // Mettre à jour le prix total dans le tableau
                document.getElementById('total-' + id).textContent = (newQuantity * data.prix_vente / 100).toFixed(2) + ' €';
                // Vous pouvez également mettre à jour le total général du panier ici si nécessaire
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la mise à jour de la quantité.');
            });
        }
    }

    // Fonction pour supprimer un article
    function removeItem(edition_id) {
        // Envoyer une requête AJAX pour supprimer l'article
        fetch('/remove-item', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ edition_id: edition_id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Supprimer la ligne de l'article dans le tableau
                document.getElementById('row-' + id).remove();
                // Vous pouvez également mettre à jour le total général du panier ici si nécessaire
            } else {
                alert('Erreur lors de la suppression de l\'article.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la suppression de l\'article.');
        });
    }

    // Ajout des écouteurs d'événements pour les boutons plus et moins
    document.querySelectorAll('.btn-plus').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            updateQuantity(id, 1);
        });
    });

    document.querySelectorAll('.btn-minus').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            updateQuantity(id, -1);
        });
    });

    // Ajout des écouteurs d'événements pour les boutons de suppression
    document.querySelectorAll('.btn-remove').forEach(button => {
        button.addEventListener('click', function() {
            const edition_id = this.getAttribute('data-id');
            removeItem(edition_id);
        });
    });
});


