document.addEventListener('DOMContentLoaded', function() {

    // calcul sous total sur Panier
    let allLignesPanier = document.querySelectorAll(".ligne-panier-container")
    let sousTotal = document.querySelector("#sous-total");
    let fpd = document.querySelector("#fpd");
    let total = document.querySelector("#total");

    function updateTotal() {
        let totalPrix = 0;

        allLignesPanier.forEach(ligne => {
            let lignePrix = ligne.querySelector(".ligne-panier")
            let prix = parseFloat(lignePrix.dataset.prix) 
            let quantityInput = ligne.querySelector(".input-quantity");
            let quantity = parseInt(quantityInput.value);
            totalPrix += prix * quantity
        });
        console.log("total prix: " + totalPrix.toFixed(2) + "€")
        sousTotal.innerText = totalPrix.toFixed(2) 

        total.innerText = totalPrix.toFixed(2)
    }

    function updateCartInDatabase(articleId, quantity) {
        console.log('Updating cart with articleId:', articleId, 'quantity:', quantity);

        fetch('/panierupdate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ article_id: articleId, quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Cart updated successfully');
            } else {
                console.error('Failed to update cart');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }


    allLignesPanier.forEach(ligne => {
        let lignePrix = ligne.querySelector(".ligne-panier");
        let prix = parseFloat(lignePrix.dataset.prix);

        const buttonPlus = ligne.querySelector('#button-plus');
        const buttonMinus = ligne.querySelector('#button-minus');

        let quantityInput = ligne.querySelector(".input-quantity");

        buttonPlus.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            quantityInput.value = value + 1;
            let articleId = ligne.querySelector('.ligne-panier').dataset.articleId;
            updateCartInDatabase(articleId, quantityInput.value);
            updateTotal();
        });
    
        buttonMinus.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            if (value > 1) {
                quantityInput.value = value - 1;
                let articleId = ligne.querySelector('.ligne-panier').dataset.articleId;
                updateCartInDatabase(articleId, quantityInput.value);
                updateTotal();
            }
        });

    })
    updateTotal();
});