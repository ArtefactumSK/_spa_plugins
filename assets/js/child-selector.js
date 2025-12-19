/**
 * SPA Child Selector – JavaScript
 * 
 * Vyplní hidden field v GF Form 1 pri kliknutí na dieťa
 */

(function() {
    'use strict';

    // Počkaj na načítanie DOM
    document.addEventListener('DOMContentLoaded', function() {
        
        // Nájdi všetky tlačidlá detí
        const childButtons = document.querySelectorAll('.spa-child-btn');
        
        if (!childButtons.length) {
            console.log('[SPA] No child buttons found');
            return;
        }

        console.log('[SPA] Found ' + childButtons.length + ' child buttons');

        // Nájdi GF hidden field (field ID 26)
        const childIdField = document.querySelector('input[name="input_26"]');
        
        if (!childIdField) {
            console.error('[SPA] GF field input_26 (child_id) not found!');
            return;
        }

        console.log('[SPA] GF field input_26 found');

        // Pri kliknutí na dieťa
        childButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                // Odstráň active class zo všetkých tlačidiel
                childButtons.forEach(function(btn) {
                    btn.classList.remove('active');
                });

                // Pridaj active class na kliknuté tlačidlo
                this.classList.add('active');

                // Získaj child_id z data atribútu
                const childId = this.getAttribute('data-child-id');
                const childName = this.textContent.trim();

                if (!childId) {
                    console.error('[SPA] Child ID missing!');
                    return;
                }

                // Vyplň hidden field
                childIdField.value = childId;

                console.log('[SPA] Selected child: ' + childName + ' (ID: ' + childId + ')');

                // Voliteľne: zobraz feedback užívateľovi
                const feedback = document.querySelector('.spa-child-feedback');
                if (feedback) {
                    feedback.innerHTML = '✅ Vybrané dieťa: <strong>' + childName + '</strong>';
                    feedback.style.display = 'block';
                }
            });
        });

        // Pri submit GF Form – over, či je vybrané dieťa
        const gfForm = document.querySelector('#gform_1'); // GF Form 1
        
        if (gfForm) {
            gfForm.addEventListener('submit', function(e) {
                const childId = childIdField.value;

                if (!childId) {
                    e.preventDefault();
                    alert('⚠️ Prosím vyberte dieťa zo zoznamu.');
                    console.error('[SPA] Form submit blocked: child_id is empty');
                    return false;
                }

                console.log('[SPA] Form submitted with child_id=' + childId);
            });
        }
    });

})();