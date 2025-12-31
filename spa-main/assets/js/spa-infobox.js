/**
 * SPA Infobox Wizard – Frontend logika
 */

(function() {
    'use strict';

    if (typeof spaConfig === 'undefined') {
        console.error('[SPA Infobox] spaConfig nie je definovaný.');
        return;
    }
    if (typeof spaConfig === 'undefined') {
        console.error('[SPA Infobox] spaConfig nie je definovaný.');
        return; // ← ZASTAV VYKONÁVANIE
    }

    let currentState = 0;
    let wizardData = {
        city_name: '',
        program_name: '',
        program_age: ''
    };

    document.addEventListener('DOMContentLoaded', function() {
        initInfobox();
        watchFormChanges();
    });

    // Gravity Forms AJAX callback
    if (typeof jQuery !== 'undefined') {
        jQuery(document).on('gform_post_render', function() {
            initInfobox();
            watchFormChanges();
        });
    }

    /**
     * Inicializácia infoboxu
     */
    function initInfobox() {
        const infoboxContainer = document.getElementById('spa-infobox-container');
        
        if (!infoboxContainer) {
            console.warn('[SPA Infobox] Container nenájdený v DOM.');
            return;
        }

        // Načítaj úvodný stav
        loadInfoboxContent(0);
        
        console.log('[SPA Infobox] Inicializovaný.');
    }

    /**
     * Sledovanie zmien vo formulári
     */
    function watchFormChanges() {
        // Sleduj zmenu mesta
        const cityField = document.querySelector(`[name="${spaConfig.fields.spa_city}"]`);
        if (cityField) {
            cityField.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                wizardData.city_name = selectedOption.text;
                
                if (this.value) {
                    currentState = 1;
                } else {
                    currentState = 0;
                    wizardData.city_name = '';
                }
                
                loadInfoboxContent(currentState);
            });
        }

        // Sleduj zmenu programu
        const programField = document.querySelector(`[name="${spaConfig.fields.spa_program}"]`);
        if (programField) {
            programField.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                
                if (this.value) {
                    wizardData.program_name = selectedOption.text;
                    wizardData.program_age = selectedOption.getAttribute('data-age-min') + '–' + 
                                            (selectedOption.getAttribute('data-age-max') || '+');
                    currentState = 2;
                } else {
                    currentState = wizardData.city_name ? 1 : 0;
                    wizardData.program_name = '';
                    wizardData.program_age = '';
                }
                
                loadInfoboxContent(currentState);
            });
        }
    }

    /**
     * Načítanie obsahu infoboxu cez AJAX
     */
    function loadInfoboxContent(state) {
        console.log('[SPA Infobox] Loading state:', state, wizardData); // 🔍 DEBUG

        const formData = new FormData();
        formData.append('action', 'spa_get_infobox_content');
        formData.append('state', state);
        formData.append('city_name', wizardData.city_name);
        formData.append('program_name', wizardData.program_name);
        formData.append('program_age', wizardData.program_age);

        fetch(spaConfig.ajaxUrl, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderInfobox(data.data.content, data.data.icons);
            } else {
                console.error('[SPA Infobox] Chyba:', data.data?.message);
            }
        })
        .catch(error => {
            console.error('[SPA Infobox] AJAX error:', error);
        });
    }

    /**
     * Vykreslenie infoboxu
     */
    function renderInfobox(content, icons) {
        const container = document.getElementById('spa-infobox-container');
        if (!container) return;

        // 0. Vyčisti kontajner (JEDINÝ render bod)
        container.innerHTML = '';

        /* ==================================================
        1. OBSAH – WP stránka (SPA Infobox Wizard)
        ================================================== */
        const contentDiv = document.createElement('div');
        contentDiv.className = 'spa-infobox-content';
        contentDiv.innerHTML = content;
        container.appendChild(contentDiv);

        /* ==================================================
        1.5 DYNAMICKÝ SUMMARY (mesto, vek, ...)
        ================================================== */
        if (wizardData.city_name || wizardData.program_age) {

            const summaryDiv = document.createElement('div');
            summaryDiv.className = 'spa-infobox-summary';

            let summaryHtml = '<ul class="spa-summary-list">';

            if (wizardData.city_name) {
                summaryHtml += `
                    <li class="spa-summary-item spa-summary-city">
                        <strong>Mesto:</strong> ${wizardData.city_name}
                    </li>`;
            }

            if (wizardData.program_age) {
                summaryHtml += `
                    <li class="spa-summary-item spa-summary-age">
                        <strong>Vek:</strong> ${wizardData.program_age} rokov
                    </li>`;
            }

            summaryHtml += '</ul>';

            summaryDiv.innerHTML = summaryHtml;
            container.appendChild(summaryDiv);
        }



        /* ==================================================
        2. IKONY – kontrolovaná veľkosť SVG
        ================================================== */
        if (icons && Object.keys(icons).length > 0) {
            const iconsWrapper = document.createElement('div');
            iconsWrapper.className = 'spa-infobox-icons';

            Object.values(icons).forEach(iconSvg => {
                if (!iconSvg) return;

                const iconDiv = document.createElement('div');
                iconDiv.className = 'spa-infobox-icon';
                iconDiv.innerHTML = iconSvg;

                // 🔧 OPRAVA SVG VEĽKOSTI (kľúčová časť)
                const svgEl = iconDiv.querySelector('svg');
                if (svgEl) {
                    // odstráň pevné rozmery zo SVG
                    svgEl.removeAttribute('width');
                    svgEl.removeAttribute('height');

                    // vynúť prispôsobenie rodičovi (.spa-infobox-icon)
                    svgEl.style.width = '100%';
                    svgEl.style.height = '100%';

                    // zachovaj pomer strán
                    svgEl.setAttribute('preserveAspectRatio', 'xMidYMid meet');
                }

                iconsWrapper.appendChild(iconDiv);
            });

            container.appendChild(iconsWrapper);
        }
    }

})();