# SPA – Architektúra systému (Artefactum / Piasecky Academy)

Tento dokument je **záväzný architektonický popis SPA systému**.
Slúži ako jediný zdroj pravdy pri vývoji, úpravách, debugovaní a AI asistencii.

---

## 1. Základné princípy architektúry

SPA systém je **hybridný WordPress systém**, ktorý kombinuje:

- **CPT (wp_posts + wp_postmeta)** → ADMIN / správa / konfigurácia
- **Custom DB tabuľky (wp_ap5_spa_*)** → RUNTIME / výkon / prevádzka

Tieto dve vrstvy:
- NIE SÚ priamo prepojené cudzími kľúčmi
- NESMÚ sa automaticky JOINovať
- majú odlišné účely

---

## 2. CPT vrstva (WordPress – ADMIN)

### 2.1 `spa_place` (SPA Miesta)

**Typ:** Custom Post Type  
**Uloženie:** `wp_posts`, `wp_postmeta`

**Účel:**
- správa fyzických miest (haly, priestory)
- jediný zdroj pravdy pre:
  - mesto
  - adresu
  - GPS
  - kontakt

**Relevantné meta polia:**
- `spa_place_city`
- `spa_place_address`
- `spa_place_gps_lat`
- `spa_place_gps_lng`
- `spa_place_contact`
- `spa_place_type`
- `spa_place_notes`

⚠️ **Dôležité:**
- `spa_place.ID` ≠ `place_id` v custom DB tabuľkách
- CPT slúži pre UI, konfiguráciu a registrácie (GF)

---

### 2.2 `spa_group`

**Typ:** Custom Post Type  
**Účel:**
- logické skupiny / vekové kategórie / programové balíky
- používané najmä pre prezentáciu a orientáciu

---

### 2.3 `spa_registration`

**Typ:** Custom Post Type  
**Účel:**
- evidenčný záznam registrácií
- nie runtime dochádzka
- nie platby

---

## 3. Custom DB vrstva (RUNTIME / výkon)

### 3.1 `wp_ap5_spa_programs`

**Účel:**
- abstraktné programy (napr. „Malí akrobati / september–jún“)
- NEOBSAHUJE mesto ani miesto

**Stĺpce (relevantné):**
- `id`
- `name`
- `active`
- `created_at`

⚠️ Program **nie je viazaný na mesto**.

---

### 3.2 `wp_ap5_spa_training_units`

**Účel:**
- konkrétne tréningové jednotky
- runtime plánovanie a kapacita

**Stĺpce (relevantné):**
- `program_id` → `wp_ap5_spa_programs.id`
- `place_id` → **interné SPA ID (nie wp_posts.ID)**
- `trainer_id`
- `training_date`
- `start_time`
- `end_time`
- `capacity`
- `status` (`scheduled`, `cancelled`, `completed`)

⚠️ **Kritické upozornenie:**
- `place_id` NIE JE prepojený na CPT `spa_place`
- ide o historický / interný identifikátor

---

### 3.3 `wp_ap5_spa_schedule_blocks`

**Účel:**
- blokácie, výnimky, udalosti
- NIE JE zdroj pre registrácie

---

## 4. Povolené a zakázané vzťahy

### ✅ Povolené (platné)
- `spa_training_units.program_id` → `spa_programs.id`

### ❌ Zakázané / neplatné
- `spa_training_units.place_id` → `wp_posts.ID`
- JOIN medzi custom DB tabuľkami a CPT bez explicitnej mapy

---

## 5. Registrácia (Gravity Forms)

### Zásady

- GF pracuje **výhradne s CPT vrstvou**
- GF NIKDY nečíta custom DB tabuľky priamo
- GF slúži na:
  - výber miesta (mesto)
  - výber programu (podľa administratívnej konfigurácie)
  - zber údajov

### Zdroj pravdy v GF

| Položka | Zdroj |
|------|------|
| Mesto | `spa_place` (postmeta `spa_place_city`) |
| Miesto | `spa_place` |
| Program | odvodený z administratívnej väzby miesta |
| Tréningové jednotky | **NIE priamo** |
| Dochádzka | NIE |

---

## 6. AJAX / JS logika

- JS (GF wizard) používa **AJAX endpoint**
- Endpoint pracuje len s:
  - `wp_posts` (`spa_place`)
  - `wp_postmeta`
- Custom DB tabuľky sú mimo rozsahu GF

---

## 7. Časté chyby (POVINNE ČÍTAŤ)

- ❌ Mesto NIE JE vlastnosť programu
- ❌ `place_id` ≠ `spa_place.ID`
- ❌ Custom DB tabuľky ≠ CPT
- ❌ JOIN bez architektonickej mapy = chyba

---

## 8. Odporúčanie do budúcna

Ak vznikne potreba:
- prepájať CPT `spa_place` a custom DB `place_id`

→ vytvoriť **explicitnú mapovaciu tabuľku**
alebo
→ uložiť referenciu `spa_place_id` ako meta do custom DB

---

## 9. Status dokumentu


## System Pages
### SPA Infobox Wizard Page

    SPA používa jednu WordPress stránku ako systémový zdroj obsahu pre infobox vo wizard rozhraní.

    Stránka sa vytvára manuálne administrátorom

    Obsah je plne editovateľný (Block / Elementor)

    Stránka nie je súčasťou verejného frontendu

    Identifikácia:

    → stránka je **identifikovaná výhradne pomocou WordPress Page ID**

    Page ID sa uloží počas inštalácie / aktivácie SPA do systémových nastavení

    slug sa používa len ako fallback pri prvotnej detekcii

    Dôvod:

    Page ID je stabilné a nemenné

    nezávislé od jazyka, prekladov a úprav slugu

    vhodné pre dlhodobú udržateľnosť architektúry




- Stav: **AKTUÁLNY**
- Overené podľa DB: **2025-12**
- Autor: Artefactum / Roman Valent
