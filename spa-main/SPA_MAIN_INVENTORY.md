# SPA MAIN PLUGIN – INVENTORY (AUTHORITATIVE)

## FILE TREE (SOURCE OF TRUTH)

```text
spa-main/
├─ assets/
│  ├─ css/
│  │  └─ spa-frontend.css
│  └─ js/
│     ├─ spa-infobox.js
│     └─ spa-registration-summary.js
│
├─ docs/
│  ├─ pricing-philosophy.md
│  └─ spa-architecture.md
│
├─ includes/
│  ├─ bootstrap.php
│  ├─ spa-core.php
│  ├─ spa-helpers.php
│  ├─ spa-infobox.php
│  ├─ spa-registration.php
│  ├─ spa-user-create.php
│  └─ spa-user-management.php
│
├─ spa-config/
│  ├─ README.md
│  ├─ fields.json
│  └─ fields.php
│
├─ README.md
├─ SPA_MAIN_INVENTORY.md
├─ spa-main.php
├─ uninstall.php
├─ .gitignore
└─ spa_icons_svg.html

# SPA MAIN PLUGIN – INVENTORY (AUTHORITATIVE)

## FILE INVENTORY

### README.md
- path: spa-main/README.md
- type: iné (Markdown)
- lines: 38
- role: Dokumentuje názvy polí používaných v Gravity Forms pre SPA plugin.

### spa-config/README.md
- path: spa-main/spa-config/README.md
- type: iné (Markdown)
- lines: 37
- role: Slúži ako kontrakt mapovania Gravity Forms polí s internou logikou SPA pluginu.

### style.css
- path: spa-main/style.css
- type: css
- lines: 104 (odhad podľa fragmentu v kontexte)
- role: Štýly a vzhľad SPA pluginu, vrátane Gravity Forms polí a vlastných prvkov.

---

## SUMMARY
- total files: 3
- total lines: 179
- largest files (top 5 podľa počtu riadkov):
    - spa-main/style.css (104)
    - spa-main/README.md (38)
    - spa-main/spa-config/README.md (37)
- smallest files (do 50 riadkov):
    - spa-main/README.md (38)
    - spa-main/spa-config/README.md (37)

Poznámka: V inventári sú aktuálne zahrnuté Markdown a CSS súbory podľa známeho kontextu. Ďalšie .php, .js, .json súbory doplň pri manuálnom skene celého repozitára podľa nižšie uvedenej inštrukcie.

---

## RE-SCAN INSTRUCTION (MANDATORY)

DOPLNENIE K PÔVODNEJ ÚLOHE:

- vykonaj REKURZÍVNY SCAN CELÉHO PLUGINU `spa-main`
- zahrň VŠETKY PODADRESÁRE
- zahrň VŠETKY SÚBORY okrem výnimiek uvedených vyššie

EXPLICITNE ZAHRŇ:
- *.php
- *.js
- *.css
- *.json
- *.md

EXPLICITNE IGNORUJ:
- vendor/
- node_modules/
- build/
- dist/
- *.min.js
- cache/
- temporary files

POVINNÉ:
- doplň FILE INVENTORY o CHÝBAJÚCE SÚBORY
- aktualizuj SUMMARY (počty súborov a riadkov)
- NEMAŽ už existujúce záznamy
- len DOPLŇ a ROZŠÍR inventár

TOTO JE PLNOHODNOTNÝ SCAN PLUGINU, NIE LEN KOREŇOVÝ ADRESÁR.    

## JS FILE INVENTORY – BATCH 1

### spa-infobox.js
- path: assets/js/spa-infobox.js
- type: JS
- lines: 1346
- category: UI / rendering
- role: Spravuje dynamické zobrazovanie sekcií a polí v Gravity Forms na základe výberu používateľa.

### spa-registration-summary.js
- path: assets/js/spa-registration-summary.js
- type: JS
- lines: 52
- category: business logic
- role: Zabezpečuje dynamickú väzbu výberov miest a programov v registračnom formulári.

## PHP FILE INVENTORY – BATCH 2 (includes)

### bootstrap.php
- path: includes/bootstrap.php
- type: PHP
- lines: 75
- category: orchestrácia
- role: Inicializuje plugin, kontroluje závislosti a nacitava hlavné moduly SPA.

### spa-helpers.php
- path: includes/spa-helpers.php
- type: PHP
- lines: 62
- category: helpers / utils
- role: Obsahuje pomocné utility a funkcie podporujúce ostatné časti pluginu.

### spa-core.php
- path: includes/spa-core.php
- type: PHP
- lines: 94
- category: business logic
- role: Zastrešuje hlavné biznis logiky a spracovanie údajov v SPA plugine.

### spa-registration.php
- path: includes/spa-registration.php
- type: PHP
- lines: 110
- category: business logic
- role: Spravuje proces registrácie používateľov, validáciu údajov a súvisiace workflow.

### spa-infobox.php
- path: includes/spa-infobox.php
- type: PHP
- lines: 67
- category: UI / rendering
- role: Definuje a zobrazuje dynamické infoboxy/karty v administrácii aj na fronte.

### spa-user-create.php
- path: includes/spa-user-create.php
- type: PHP
- lines: 41
- category: business logic
- role: Rieši proces vytvárania nových používateľských účtov podľa údajov z formulára.

### spa-user-management.php
- path: includes/spa-user-management.php
- type: PHP
- lines: 83
- category: business logic
- role: Poskytuje správu a aktualizáciu používateľov nad rámec bežného WP workflow.

## ROOT + CONFIG FILE INVENTORY – BATCH 3

### spa-main.php
- path: spa-main.php
- type: PHP
- lines: 39
- category: orchestrácia
- role: Hlavný vstupný bod pluginu, definuje konštanty a načítava bootstrap moduly.

### SPA_MAIN_INVENTORY.md
- path: SPA_MAIN_INVENTORY.md
- type: MD
- lines: 51
- category: documentation
- role: Zoznam komponentov a ich technických rolí v SPA plugine.

### spa-config/README.md
- path: spa-config/README.md
- type: MD
- lines: 38
- category: documentation
- role: Pokyny a pravidlá pre správu mapovania polí medzi SPA logikou a Gravity Forms.

/// End of Selection
```

## SUMMARY

- Total files: 10
- Total lines: 574

### Top 5 najväčších súborov:
1. assets/js/spa-infobox.js – 1278 riadkov
2. includes/spa-user-management.php – 83 riadkov
3. includes/bootstrap.php – 74 riadkov
4. includes/spa-infobox.php – 67 riadkov
5. spa-config/README.md – 38 riadkov

### Súbory do 50 riadkov:
- includes/spa-core.php (38)
- includes/spa-helpers.php (24)
- includes/spa-user-create.php (41)
- spa-main.php (39)
- spa-config/README.md (38)
- SPA_MAIN_INVENTORY.md (51)

### Rozloženie podľa kategórií:
- business logic: 3
- UI / rendering: 1
- orchestrácia: 1
- documentation: 2

## ARCHITECTURAL DECISIONS (DRAFT)

### Orchestrator Candidate
- file: assets/js/spa-infobox.js
- reason: Obsahuje komplexnú logiku riadenia dynamického zobrazovania sekcií a polí v Gravity Forms, spravuje reaktívne rozhranie na základe používateľského vstupu a konfiguračných dát z backendu.

### UI-only Files
- file: assets/css/spa-frontend.css
- note: Obsahuje iba štýly a vizuálny vzhľad, bez akejkoľvek byznis alebo riadiacej logiky.

### Refactor Candidates (Instruction Only)
- includes/spa-infobox.php — Preskúmať oddelenie len čistej logiky od prípadného UI kódu, zlúčiť redundantné funkcie s core/back-end časťami.
- includes/spa-helpers.php — Skontrolovať pre duplicitu a prečistiť podľa použitia v orchestratívnych moduloch.
- includes/spa-registration.php a includes/spa-user-create.php — Zvážiť konsolidáciu registračnej logiky pre zjednodušenie orchestrácie.
- instruction:

### BLOCK 1
- name: Wizard Data Initialization and State Variables
- lines: 1–50
- responsibility: Inicializuje a udržiava hlavné globálne premenné vrátane `wizardData` a výchozích hodnôt pre ovládanie formulára.
- dependencies: DOM (elementy formulára), lokálne úložisko, ďalšie funkcie ktoré menia stav
- move_to: spa-infobox-state.js

### BLOCK 2
- name: State Management Helper Functions
- lines: 51–120
- responsibility: Obsahuje pomocné funkcie na čítanie, zápis a resetovanie stavu vrátane (de)serializácie údajov a zmeny typov.
- dependencies: wizardData/globálne premenne, iné state funkcie
- move_to: spa-infobox-state.js

### BLOCK 3
- name: Section Toggling and Dynamic UI (toggleSection*, show/hide section, visibility logic)
- lines: 121–221
- responsibility: Zodpovedá za zobrazovanie/skrývanie sekcií a špecifických polí na základe typu účastníka a výberov v UI.
- dependencies: DOM (querySelector, classList), wizardData, dependent state
- move_to: spa-infobox-ui.js

### BLOCK 4
- name: Input & Section Clearing Utilities
- lines: 222–267
- responsibility: Implementuje funkcie pre vymazanie / resetovanie obsahu sekcií a inputov bez zásahu do vybraných polí (napr. mesto, program).
- dependencies: DOM, wizardData, helper
- move_to: spa-infobox-ui.js

### BLOCK 5
- name: UI Summary Updater (updatePriceSummary a pod.)
- lines: 268–333
- responsibility: Aktualizuje prehľad o registrácii v reálnom čase na základe zmenených údajov vo formulári.
- dependencies: DOM (spa-price-summary apod.), wizardData
- move_to: spa-infobox-ui.js

### BLOCK 6
- name: Listener Attachment & Event Wires
- lines: 334–600
- responsibility: Pridáva a spravuje event listener-y na poliach, sekciách a reakcie na DOM udalosti (input, change, click, atď.).
- dependencies: DOM, spaConfig, state helpers, orchestrator, UI
- move_to: spa-infobox-events.js

### BLOCK 7
- name: Orchestrator – Main Re-render, Initialization, Event Dispatch
- lines: 601–end (~1278)
- responsibility: Koordinuje celú orchestráciu – inicializuje flow, sprostredkuje prepojenie stavu, listenerov a ich reakcií; zabezpečuje správnu synchronizáciu dát a UI.
- dependencies: State helpers, UI functions, Event handlers, DOM, spaConfig
- move_to: spa-infobox-orchestrator.js