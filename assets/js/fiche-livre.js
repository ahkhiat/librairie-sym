document.addEventListener('DOMContentLoaded', function() {
    console.log("fiche livre chargée");

    // Boutons + et -
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


    // Ajout panier
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