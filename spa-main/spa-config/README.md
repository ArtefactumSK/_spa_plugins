

# SPA – Gravity Forms Field Mapping

Tento adresár obsahuje konfiguračné súbory,
ktoré prepájajú SPA logiku s Gravity Forms formulárom.

## KEDY SA TO UPRAVUJE
- po importe GF formulára
- pri presune projektu na nový web / DB

## POSTUP
1. Otvor Gravity Forms → Formulár SPA
2. Klikni na pole → Advanced → Field ID
3. Prepíš príslušné `input_XX` v:
   - fields.php
   - fields.json
4. Ulož súbory
5. Otestuj:
   - JS prehľad registrácie
   - PHP validácie

## PRAVIDLÁ
- logické názvy (vľavo) SA NEMENIA
- mení sa len hodnota `input_XX`
- nikdy nepoužívaj GF ID priamo v kóde

## LOGICKÉ NÁZVY
- program
- variant
- client_email
- client_phone
- client_address
- consent_*

Tento kontrakt je zdroj pravdy pre SPA.
