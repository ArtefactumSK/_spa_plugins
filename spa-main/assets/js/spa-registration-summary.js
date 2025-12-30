/**
 * SPA Registration – Dynamické selecty (City → Program)
 * FINAL VERSION s načítaním miest
 */

(function() {
    'use strict';

    if (typeof spaConfig === 'undefined') {
        console.error('[SPA] spaConfig nie je definovaný.');
        return;
    }

    const cityInputId = spaConfig.fields.spa_city;
    const programInputId = spaConfig.fields.spa_program;

    if (!cityInputId || !programInputId) {
        console.error('[SPA] Chýbajúce field ID v spa-config.');
        return;
    }

    function getFieldSelector(inputId) {
        const fieldNum = inputId.replace('input_', '');
        const formElement = document.querySelector('.gform_wrapper form');
        if (!formElement) return null;
        
        const formId = formElement.id.replace('gform_', '');
        return `#input_${formId}_${fieldNum}`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        initDynamicSelects();
    });

    if (typeof jQuery !== 'undefined') {
        jQuery(document).on('gform_post_render', function() {
            initDynamicSelects();
        });
    }

    function initDynamicSelects() {
        const citySelector = getFieldSelector(cityInputId);
        const programSelector = getFieldSelector(programInputId);

        if (!citySelector || !programSelector) {
            console.warn('[SPA] Nemožno vytvoriť selektory pre GF polia.');
            return;
        }

        const cityField = document.querySelector(citySelector);
        const programField = document.querySelector(programSelector);

        if (!cityField || !programField) {
            console.warn('[SPA] GF select polia neboli nájdené v DOM.');
            return;
        }

        // Načítaj mestá pri inicializácii
        loadCities(cityField);

        // Event listener na zmenu mesta
        cityField.addEventListener('change', function() {
            const cityId = this.value;
            
            if (!cityId) {
                resetProgramField(programField);
                return;
            }

            loadPrograms(cityId, programField);
        });

        console.log('[SPA] Dynamické selecty inicializované.');
    }

    /**
     * Načítanie miest cez AJAX
     */
    function loadCities(cityField) {
        setLoadingState(cityField, true, 'Načítavam mestá...');

        const formData = new FormData();
        formData.append('action', 'spa_get_cities');

        fetch(spaConfig.ajaxUrl, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                populateCityField(cityField, data.data);
            } else {
                showError(cityField, 'Chyba pri načítaní miest.');
            }
        })
        .catch(error => {
            console.error('[SPA] Cities AJAX error:', error);
            showError(cityField, 'Nastala technická chyba.');
        });
    }

    /**
     * Načítanie programov cez AJAX
     */
    function loadPrograms(cityId, programField) {
        setLoadingState(programField, true, 'Načítavam programy...');

        const formData = new FormData();
        formData.append('action', 'spa_get_programs');
        formData.append('city_id', cityId);

        fetch(spaConfig.ajaxUrl, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                populateProgramField(programField, data.data);
            } else {
                showError(programField, data.data?.message || 'Chyba pri načítaní programov.');
            }
        })
        .catch(error => {
            console.error('[SPA] Programs AJAX error:', error);
            showError(programField, 'Nastala technická chyba.');
        });
    }

    /**
     * Naplnenie city fieldu
     */
    function populateCityField(selectElement, cities) {
        selectElement.innerHTML = '<option value="">Vyberte mesto</option>';
        cities.forEach(city => {
            const option = document.createElement('option');
            option.value = city.id;
            option.textContent = city.name;
            selectElement.appendChild(option);
        });
        selectElement.disabled = false;
    }

    /**
     * Naplnenie program fieldu
     */
    function populateProgramField(selectElement, programs) {
        selectElement.innerHTML = '<option value="">Vyberte program</option>';
        programs.forEach(program => {
            const option = document.createElement('option');
            option.value = program.id;
            option.textContent = program.name;
            selectElement.appendChild(option);
        });
        selectElement.disabled = false;
    }

    /**
     * Reset program fieldu
     */
    function resetProgramField(selectElement) {
        selectElement.innerHTML = '<option value="">Najprv vyberte mesto</option>';
        selectElement.disabled = true;
    }

    /**
     * Zobrazenie loading stavu
     */
    function setLoadingState(selectElement, isLoading, message = 'Načítavam...') {
        if (isLoading) {
            selectElement.innerHTML = `<option value="">${message}</option>`;
            selectElement.disabled = true;
        }
    }

    /**
     * Zobrazenie chybovej správy
     */
    function showError(selectElement, message) {
        selectElement.innerHTML = `<option value="">${message}</option>`;
        selectElement.disabled = true;
        console.error('[SPA]', message);
    }

})();