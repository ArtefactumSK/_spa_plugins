/**
 * SPA Gravity Forms Wizard – Registration Type Logic
 */
(function() {
    'use strict';

    if (typeof spaConfig === 'undefined') {
        return;
    }

    // Tento JS beží LEN v kontexte GF formulára
    if (!document.querySelector('.gform_wrapper')) {
        return;
    }

    document.addEventListener('DOMContentLoaded', function() {
        watchProgramSelection();
    });

    if (typeof jQuery !== 'undefined') {
        jQuery(document).on('gform_post_render', function() {
            watchProgramSelection();
        });
    }

    function watchProgramSelection() {
        const programSelector = `[name="${spaConfig.fields.spa_program}"]`;
        const programField = document.querySelector(programSelector);

        if (!programField) return;

        programField.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (!selectedOption || !selectedOption.value) {
                resetResolvedType();
                return;
            }

            const target = selectedOption.getAttribute('data-target');
            
            if (!target) return;

            let resolvedType = 'child';
            
            if (target === 'adult') {
                resolvedType = 'adult';
            } else if (target === 'youth' || target === 'child') {
                resolvedType = 'child';
            }

            setResolvedType(resolvedType);
        });
    }

    function setResolvedType(type) {
        window.spaResolvedParticipantType = type;
        
        const inputName = spaConfig.fields.spa_resolved_type;
        const hiddenField = document.querySelector(`input[name="${inputName}"]`);
        
        if (hiddenField) {
            hiddenField.value = type;
            console.log('[SPA GF Wizard] Resolved type:', type);
        }

        if (typeof jQuery !== 'undefined') {
            jQuery(document).trigger('gform_post_render');
        }
    }

    function resetResolvedType() {
        window.spaResolvedParticipantType = null;
        
        const inputName = spaConfig.fields.spa_resolved_type;
        const hiddenField = document.querySelector(`input[name="${inputName}"]`);
        
        if (hiddenField) {
            hiddenField.value = '';
        }
    }

})();