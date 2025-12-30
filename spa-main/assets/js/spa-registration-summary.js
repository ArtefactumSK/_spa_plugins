/**
 * SPA Registration – Dynamické selecty (City → Program)
 * DEBUG VERSION – detailný logging
 */

(function() {
    'use strict';

    // Kontrola, či existuje konfigurácia
    if (typeof spaConfig === 'undefined') {
        console.error('[SPA] spaConfig nie je definovaný.');
        return;
    }

    console.log('[SPA DEBUG] spaConfig:', spaConfig);

    // Extrakcia field ID z spa-config
    const cityInputId = spaConfig.fields.spa_city;
    const programInputId = spaConfig.fields.spa_program;

    console.log('[SPA DEBUG] cityInputId:', cityInputId);
    console.log('[SPA DEBUG] programInputId:', programInputId);

    if (!cityInputId || !programInputId) {
        console.error('[SPA] Chýbajúce field ID v spa-config.');
        return;
    }

    // Konverzia input_XX na selector ID
    function getFieldSelector(inputId) {
        const fieldNum = inputId.replace('input_', '');
        
        // Nájdi GF formulár
        const formElement = document.querySelector('.gform_wrapper form');
        
        console.log('[SPA DEBUG] formElement:', formElement);
        
        if (!formElement) {
            console.error('[SPA DEBUG] GF formulár nebol nájdený!');
            return null;
        }
        
        const formId = formElement.id.replace('gform_', '');
        const selector = `#input_${formId}_${fieldNum}`;
        
        console.log('[SPA DEBUG] formId:', formId);
        console.log('[SPA DEBUG] Hľadám selector:', selector);
        
        return selector;
    }

    // Inicializácia po načítaní DOM
    document.addEventListener('DOMContentLoaded', function() {
        console.log('[SPA DEBUG] DOMContentLoaded fired');
        initDynamicSelects();
    });

    // Gravity Forms AJAX callback
    if (typeof jQuery !== 'undefined') {
        jQuery(document).on('gform_post_render', function() {
            console.log('[SPA DEBUG] gform_post_render fired');
            initDynamicSelects();
        });
    }

    /**
     * Inicializácia dynamických selectov
     */
    function initDynamicSelects() {
        console.log('[SPA DEBUG] initDynamicSelects() started');
        
        // DEBUG: Vypíš všetky select elementy na stránke
        const allSelects = document.querySelectorAll('select');
        console.log('[SPA DEBUG] Všetky <select> elementy na stránke:', allSelects);
        allSelects.forEach(select => {
            console.log('  - ID:', select.id, 'Name:', select.name, 'Class:', select.className);
        });
        
        const citySelector = getFieldSelector(cityInputId);
        const programSelector = getFieldSelector(programInputId);

        console.log('[SPA DEBUG] citySelector:', citySelector);
        console.log('[SPA DEBUG] programSelector:', programSelector);

        if (!citySelector || !programSelector) {
            console.warn('[SPA] Nemožno vytvoriť selektory pre GF polia.');
            return;
        }

        const cityField = document.querySelector(citySelector);
        const programField = document.querySelector(programSelector);

        console.log('[SPA DEBUG] cityField element:', cityField);
        console.log('[SPA DEBUG] programField element:', programField);

        if (!cityField || !programField) {
            console.error('[SPA] GF select polia neboli nájdené v DOM.');
            console.error('[SPA DEBUG] Hľadal som:', citySelector, 'a', programSelector);
            return;
        }

        // Event listener na zmenu mesta
        cityField.addEventListener('change', function() {
            const cityId = this.value;
            console.log('[SPA DEBUG] City changed to:', cityId);
            
            if (!cityId) {
                resetProgramField(programField);
                return;
            }

            loadPrograms(cityId, programField);
        });

        console.log('[SPA] ✅ Dynamické selecty úspešne inicializované.');
    }

    /**
     * Načítanie programov cez AJAX
     */
    function loadPrograms(cityId, programField) {
        console.log('[SPA DEBUG] loadPrograms() - cityId:', cityId);
        
        setLoadingState(programField, true);

        const formData = new FormData();
        formData.append('action', 'spa_get_programs');
        formData.append('city_id', cityId);
        formData.append('nonce', spaConfig.nonce);

        fetch(spaConfig.ajaxUrl, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            console.log('[SPA DEBUG] AJAX response:', data);
            setLoadingState(programField, false);

            if (data.success && data.data) {
                populateProgramField(programField, data.data);
            } else {
                showError(programField, data.data?.message || 'Chyba pri načítaní programov.');
            }
        })
        .catch(error => {
            setLoadingState(programField, false);
            console.error('[SPA] AJAX error:', error);
            showError(programField, 'Nastala technická chyba.');
        });
    }

    function populateProgramField(selectElement, programs) {
        selectElement.innerHTML = '<option value="">Vyberte program</option>';
        programs.forEach(program => {
            const option = document.createElement('option');
            option.value = program.id;
            option.textContent = program.name;
            selectElement.appendChild(option);
        });
        selectElement.disabled = false;
        console.log('[SPA DEBUG] Program field populated with', programs.length, 'items');
    }

    function resetProgramField(selectElement) {
        selectElement.innerHTML = '<option value="">Najprv vyberte mesto</option>';
        selectElement.disabled = true;
    }

    function setLoadingState(selectElement, isLoading) {
        if (isLoading) {
            selectElement.innerHTML = '<option value="">Načítavam...</option>';
            selectElement.disabled = true;
        }
    }

    function showError(selectElement, message) {
        selectElement.innerHTML = `<option value="">${message}</option>`;
        selectElement.disabled = true;
        console.error('[SPA]', message);
    }

})();