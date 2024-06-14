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


    // calcul sous total sur Panier
    let allLignesPanier = document.querySelectorAll(".ligne-panier-container")
    let totalPrix = 0;
    let sousTotal = document.querySelector("#sous-total");
    let fpd = document.querySelector("#fpd");
    let total = document.querySelector("#total");

        allLignesPanier.forEach(ligne => {
            let lignePrix = ligne.querySelector(".ligne-panier")
            let prix = parseFloat(lignePrix.dataset.prix) 
            // dataset permet de recuperer la valeur d'un attribut qu'on a nommé data-*
            
            let quantityInput = ligne.querySelector(".input-quantity");
            let quantity = parseInt(quantityInput.value);

            totalPrix += prix * quantity
        })
        console.log("total prix: " + totalPrix.toFixed(2) + "€")
        sousTotal.innerText = totalPrix.toFixed(2) 

        total.innerText = totalPrix.toFixed(2)








