import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');


document.addEventListener('DOMContentLoaded', function () {
    
    let addToCartButton = document.getElementById('add-to-cart');

    addToCartButton.addEventListener('click', function (e) {
        e.preventDefault();

        let articleId = this.getAttribute('data-article-id');
        let userId = this.getAttribute('user-id')

        fetch('/ajoutpanier', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ edition_id: articleId, user_id: userId })
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