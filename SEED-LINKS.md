# Seed prin link — „Suplimente sub 200 lei”

Fișier de referință cu toate link-urile de seed. **Temporar** — vezi secțiunea
„Curățenie după folosire” la final.

> Scriptul `seed-sub200.php` bootstrap-ează WordPress și rulează seed-ul direct,
> protejat de un **token** în URL. Nu depinde de `functions.php`/opcache.

- **Token:** `mn7x2k9q-sub200-seed` (definit în `seed-sub200.php` → `SEED_TOKEN`)
- **Pe live:** schimbă token-ul în fișier înainte de upload și folosește noua valoare în URL.

---

## Moduri (`mode=`)

| mode    | Ce face                                                              |
|---------|---------------------------------------------------------------------|
| `dry`   | Previzualizare — **nu scrie nimic**. Rulează asta întâi.            |
| `run`   | Creează pagina + scrie doar ce lipsește (nu rescrie ce există).    |
| `force` | Rescrie **tot**: ACF-ul paginii + `protocol_zile`/`forma` pe produse. |
| `page`  | Rescrie **doar** pagina (ACF), fără să atingă produsele.            |

---

## LOCAL — `http://mananaturii-new.local`

**1. Previzualizare (recomandat întâi):**
```
http://mananaturii-new.local/wp-content/themes/sage-nature/seed-sub200.php?token=mn7x2k9q-sub200-seed&mode=dry
```

**2. Aplică tot:**
```
http://mananaturii-new.local/wp-content/themes/sage-nature/seed-sub200.php?token=mn7x2k9q-sub200-seed&mode=force
```

**Doar ce lipsește (fără rescriere):**
```
http://mananaturii-new.local/wp-content/themes/sage-nature/seed-sub200.php?token=mn7x2k9q-sub200-seed&mode=run
```

**Doar pagina, fără produse:**
```
http://mananaturii-new.local/wp-content/themes/sage-nature/seed-sub200.php?token=mn7x2k9q-sub200-seed&mode=page
```

---

## LIVE — `https://DOMENIUL-TAU`

> Înlocuiește `DOMENIUL-TAU` cu domeniul real și, ideal, `TOKEN-NOU` cu token-ul
> schimbat în fișier.

**1. Previzualizare:**
```
https://DOMENIUL-TAU/wp-content/themes/sage-nature/seed-sub200.php?token=TOKEN-NOU&mode=dry
```

**2. Aplică tot:**
```
https://DOMENIUL-TAU/wp-content/themes/sage-nature/seed-sub200.php?token=TOKEN-NOU&mode=force
```

---

## Alternativă fără link (dacă preferi)

- **Admin:** Unelte → „Seed «Sub 200 lei»” (butoane: Previzualizare / Rulează / Rescrie tot / Doar pagina).
- **WP-CLI** (din site shell):
  ```
  wp acorn natura:sub200-seed --dry-run
  wp acorn natura:sub200-seed --force
  wp acorn natura:sub200-seed --force --skip-products
  ```

---

## Ce face seed-ul

1. Creează/găsește pagina cu template `template-sub-200.blade.php` și îi populează
   conținutul editorial în ACF (hero, explain, tabel, bridge, FAQ, CTA).
2. Corectează `informatie_generala` pe produse (`protocol_zile` = zile cură reale,
   `forma`), ca să iasă corect costul/zi:

   | Produs (slug)                                  | forma            | protocol_zile |
   |------------------------------------------------|------------------|---------------|
   | `lionfocus-b6-jeleuri`                         | 60 jeleuri       | 30            |
   | `d-tox-ficat`                                  | 120 capsule      | 120           |
   | `microflora-lemon-shots-500-ml-33-shots`       | 500 ml · 33 doze | 33            |
   | `black-seed-elixir`                            | 240 capsule      | 120           |
   | `collagen-joint-berry-500-ml`                  | 500 ml · 33 doze | 33            |
   | `vita-complete-vegan-shots-500-ml-50-shots`    | 500 ml · 50 doze | 50            |

Idempotent — îl poți rula de câte ori vrei.

---

# Seed prin link — „Cele mai vândute”

Script: `seed-bestseller.php`. Creează/populează pagina cu template „Cele mai vândute”,
scrie conținutul editorial în ACF și **leagă produsele reale** din catalog (după slug).

- **Token:** `mn7x2k9q-bestseller-seed` (în `seed-bestseller.php` → `SEED_TOKEN`)
- **Moduri:** `dry` (previzualizare) · `run` (scrie ce lipsește) · `force` (rescrie tot)

### LOCAL
**1. Previzualizare:**
```
http://mananaturii-new.local/wp-content/themes/sage-nature/seed-bestseller.php?token=mn7x2k9q-bestseller-seed&mode=dry
```
**2. Aplică tot:**
```
http://mananaturii-new.local/wp-content/themes/sage-nature/seed-bestseller.php?token=mn7x2k9q-bestseller-seed&mode=force
```

### LIVE
```
https://DOMENIUL-TAU/wp-content/themes/sage-nature/seed-bestseller.php?token=TOKEN-NOU&mode=force
```

### Alternative
- **Admin:** Unelte → „Seed «Cele mai vândute»”.
- **WP-CLI:** `wp acorn natura:bestseller-seed --dry-run` / `--force`.

### Produsele
Repeater-ul ACF (tab „Produse (top)”) conține produse reale; seed-ul le pre-completează
cu 5 produse din catalog (Microflora+, D-Tox Ficat, Black Seed, Vita Complete+,
Collagen Joint+). **Le poți reordona / înlocui / adăuga** oricând din ACF — numele,
imaginea, prețul, beneficiile și costul/zi vin automat din produs.

> Notă: dacă un slug nu există în catalog, apare `[ATENTIE] Produs negăsit (sărit)`
> — îmi spui slug-ul corect sau îl alegi manual din ACF.

---

# Seed prin link — „Noutăți · În curând”

Script: `seed-noutati.php`. Creează/populează pagina cu template „Noutăți · În curând”
și scrie tot conținutul editorial în ACF (inclusiv cele 3 tincturi: compoziție,
beneficii, contraindicații, status). Tincturile sunt produse VIITOARE — date editoriale.

- **Token:** `mn7x2k9q-noutati-seed`
- **Moduri:** `dry` · `run` · `force`

### LOCAL
**1. Previzualizare:**
```
http://mananaturii-new.local/wp-content/themes/sage-nature/seed-noutati.php?token=mn7x2k9q-noutati-seed&mode=dry
```
**2. Aplică tot:**
```
http://mananaturii-new.local/wp-content/themes/sage-nature/seed-noutati.php?token=mn7x2k9q-noutati-seed&mode=force
```

### LIVE
```
https://DOMENIUL-TAU/wp-content/themes/sage-nature/seed-noutati.php?token=TOKEN-NOU&mode=force
```

### Alternative
- **Admin:** Unelte → „Seed «Noutăți»”.
- **WP-CLI:** `wp acorn natura:noutati-seed --dry-run` / `--force`.

### Note
- Formularul „Anunță-mă” este **vizual deocamdată** (fără backend) — îl cablăm funcțional ulterior.
- Fiecare tinctură are un câmp opțional „Produs WC” — dacă legi un produs real (când apare un draft), cardul afișează imaginea reală în locul sticlei desenate.

---

# Seed prin link — Mega-meniu „Suplimente”

Script: `seed-mega-suplimente.php`. Populează etichetele editoriale ale mega-meniului
„Suplimente” în **ACF options „Meniu”** (format, quick links, featured, bandă jos).
Categoriile și prețurile vin live din WooCommerce.

- **Token:** `mn7x2k9q-megasup-seed`
- **Moduri:** `dry` · `run` · `force`

### LOCAL
```
http://mananaturii-new.local/wp-content/themes/sage-nature/seed-mega-suplimente.php?token=mn7x2k9q-megasup-seed&mode=dry
http://mananaturii-new.local/wp-content/themes/sage-nature/seed-mega-suplimente.php?token=mn7x2k9q-megasup-seed&mode=force
```

### LIVE
```
https://DOMENIUL-TAU/wp-content/themes/sage-nature/seed-mega-suplimente.php?token=TOKEN-NOU&mode=force
```

### Alternative
- **Admin:** Unelte → „Seed «Mega Suplimente»”.
- **WP-CLI:** `wp acorn natura:mega-suplimente-seed --dry-run` / `--force`.

### ⚠️ Obligatoriu pentru ca mega-meniul să apară
Itemul de meniu **„Suplimente”** trebuie să aibă clasa CSS **`mega-produse`**:
Aspect → Meniuri → (Opțiuni ecran, bifează „Clase CSS”) → pune `mega-produse` pe itemul Suplimente.

---

# Seed prin link — „După simptom (pagini detaliu)”

Script: `seed-simptom.php`. Creează paginile de simptom (template
`template-simptom.blade.php`, ACF din `database/seeds/simptome.php`) și le **mută
sub `/dupa-simptom/<slug>/`**. Bootstrap-ează WP și rulează comanda Acorn
`natura:simptom-seed` prin kernel. Face `flush_rewrite_rules()` la `run`/`force`.

- **Token:** `mn7x2k9q-simptom-seed`
- **Moduri:** `dry` (previzualizare) · `run` (creează/mută, păstrează ACF) · `force` (rescrie ACF)

### LOCAL
```
http://mananaturii-new.local/wp-content/themes/sage-nature/seed-simptom.php?token=mn7x2k9q-simptom-seed&mode=dry
http://mananaturii-new.local/wp-content/themes/sage-nature/seed-simptom.php?token=mn7x2k9q-simptom-seed&mode=run
```
### LIVE
```
https://DOMENIUL-TAU/wp-content/themes/sage-nature/seed-simptom.php?token=TOKEN-NOU&mode=run
```
### Alternativă
- **WP-CLI:** `wp acorn natura:simptom-seed --dry-run` / `` / `--force` / `--force --only=<slug>`

---

# Seed prin link — „Hub După simptom (index ACF)”

Script: `seed-dupa-simptom.php`. Populează ACF-ul hub-ului `/dupa-simptom/` (cele 4
grupe + carduri din `database/seeds/dupa-simptom-grupe.php`) și **leagă cardurile**
de paginile de detaliu. Rulează comanda Acorn `natura:dupa-simptom-seed` prin kernel.

> ⚠️ Rulează **DUPĂ** `seed-simptom.php` — are nevoie ca paginile de detaliu să existe
> ca să le poată lega.

- **Token:** `mn7x2k9q-dupasimptom-seed`
- **Moduri:** `dry` · `run` (scrie doar dacă ACF e gol) · `force` (rescrie, inclusiv editări din admin)

### LOCAL
```
http://mananaturii-new.local/wp-content/themes/sage-nature/seed-dupa-simptom.php?token=mn7x2k9q-dupasimptom-seed&mode=dry
http://mananaturii-new.local/wp-content/themes/sage-nature/seed-dupa-simptom.php?token=mn7x2k9q-dupasimptom-seed&mode=run
```
### LIVE
```
https://DOMENIUL-TAU/wp-content/themes/sage-nature/seed-dupa-simptom.php?token=TOKEN-NOU&mode=run
```
### Alternativă
- **WP-CLI:** `wp acorn natura:dupa-simptom-seed --dry-run` / `` / `--force`

---

## Alte seed-uri existente (WP-CLI)

Acestea **nu au link** — generează mai multe pagini-copil, deci se rulează din
site shell prin WP-CLI (`wp acorn …`). Sunt idempotente; fără `--force` nu rescriu
paginile existente.

### „După obiectiv” — `natura:obiectiv-seed`
Asigură hub-ul `/dupa-obiectiv/`, apoi creează câte o pagină-copil per obiectiv
(`/dupa-obiectiv/<slug>/`, template `template-obiectiv.blade.php`) și le populează
ACF din `database/seeds/obiective.php`.
```
wp acorn natura:obiectiv-seed --dry-run
wp acorn natura:obiectiv-seed
wp acorn natura:obiectiv-seed --force
wp acorn natura:obiectiv-seed --force --only=<slug>
```
| Opțiune    | Efect                                                   |
|------------|---------------------------------------------------------|
| `--dry-run`| Afișează ce ar face, fără să scrie nimic                |
| `--force`  | Rescrie ACF-ul și pe paginile care există deja          |
| `--only=`  | Procesează doar obiectivul cu acel slug                  |

### Import coduri poștale — `natura:address-import`
Nu e seed de pagină — importă un CSV de coduri poștale în fișiere JSON pe shard-uri
(pentru cascada de adresă client-side).
```
wp acorn natura:address-import <cale/catre/sursa.csv>
wp acorn natura:address-import <cale.csv> --out=public/data/postcodes --shard-bytes=800000
```
| Argument / opțiune | Efect                                                |
|--------------------|------------------------------------------------------|
| `source` (oblig.)  | Calea către CSV-ul sursă                             |
| `--out=`           | Directorul de ieșire (relativ la rădăcina temei)     |
| `--shard-bytes=`   | Bytes max per shard înainte de split alfabetic       |

> Notă: aceste comenzi fac parte din temă (nu sunt temporare) — **nu** le șterge.

---

## Curățenie după folosire (local + live)

După ce ai rulat seed-urile, **șterge** (obligatoriu — singurele cu acces fără login):

1. **`seed-sub200.php`**
2. **`seed-bestseller.php`**
3. **`seed-noutati.php`**
4. **`seed-mega-suplimente.php`**
5. **`seed-simptom.php`**
6. **`seed-dupa-simptom.php`**
7. **`SEED-LINKS.md`** — acest fișier.

Opțional, dacă nu mai vrei deloc seed-ul (pagina rămâne pe fallback-urile din cod,
**dar nu mai e editabilă din ACF**):

- `app/sub200-seed.php`
- `app/acf-sub200.php`  ← ștergerea lui dezactivează editarea ACF a paginii
- `database/seeds/sub200.php`
- `app/Console/Commands/Sub200Seed.php`
- intrările `'acf-sub200', 'sub200-seed'` din `functions.php`

> Recomandat: păstrează `app/acf-sub200.php` (editabilitate ACF) și șterge doar
> `seed-sub200.php` + `SEED-LINKS.md` (accesul public).
