<?php

/**
 * Date de seed pentru paginile de simptom.
 *
 * Sursă: mockup-urile din `preferinte/Pagina simptom - *.html`. Fiecare element
 * (indexat pe slug) conține tot conținutul celor 8 secțiuni, transcris fidel.
 * Consumat de App\Console\Commands\SimptomSeed (`wp acorn natura:simptom-seed`),
 * care creează paginile și scrie câmpurile ACF (vezi app/acf-simptom.php).
 *
 * La `produse[].slug` punem slug-ul WooCommerce presupus; comanda îl rezolvă la
 * ID (câmpul ACF `produs`). `nume`/`pret` rămân fallback de afișare dacă produsul
 * nu există încă în catalog. `autotest.intrebari[].default`: 0=Da, 1=Uneori, 2=Nu.
 *
 * Notă: câteva răspunsuri FAQ lipseau din mockup-uri (erau doar întrebările);
 * acolo am completat răspunsuri scurte, factuale, în spiritul paginii.
 */

return [

    // =====================================================================
    // BALONARE
    // =====================================================================
    'balonare' => [
        'title' => 'Balonare',
        'hero' => [
            'eyebrow' => 'După simptom',
            'titlu' => 'Balonare. Ce este, când e normală <em>și când nu.</em>',
            'lede' => 'Aproape toată lumea o pățește. În 80% din cazuri, e o reacție temporară la mâncare, ritm sau stres. Restul de 20% merită o privire mai atentă.',
            'chips' => [
                'Afectează ~60% dintre adulți',
                'De obicei se calmează în 24–48h',
                'Poate semnala ceva, dacă persistă',
            ],
        ],
        'definitie' => [
            'eyebrow' => 'Definiție simplă',
            'titlu' => 'Balonarea e <em>o senzație, nu un diagnostic.</em>',
            'cells' => [
                ['titlu' => 'Senzația', 'text' => 'Abdomen plin, pântec umflat, presiune internă. Uneori e doar o senzație, alteori e vizibilă. De obicei dispare singură după câteva ore.'],
                ['titlu' => 'Cauza fizică', 'text' => 'Gazele care se acumulează în stomac sau intestin. Vin din digestie, din ce înghiți când vorbești sau din mâncăruri care fermentează.'],
                ['titlu' => 'Când e doar o reacție', 'text' => 'După o masă mare. La menstruație. După câteva zile de stres. Când nu ai băut suficientă apă. În toate aceste cazuri, nu ai nevoie de niciun supliment.'],
            ],
        ],
        'semne' => [
            'eyebrow' => 'De unde vine de obicei',
            'titlu' => 'Trei cauze pe care <em>le-am văzut cel mai des.</em>',
            'items' => [
                ['titlu' => 'Mâncăruri care fermentează mult', 'desc' => 'Varză, fasole, ceapă crudă, lapte la oameni cu sensibilitate la lactoză, pâine albă în cantitate mare. Nu înseamnă că sunt nesănătoase, doar că unele organisme le procesează mai greu.', 'ajuta' => 'Mese mai mici, mai des. Probiotice, dacă e persistent.'],
                ['titlu' => 'Ritm digestiv neregulat', 'desc' => 'Când mănânci la ore diferite în fiecare zi, sistemul digestiv își pierde ritmul. Mesele luate în fugă, când vorbești la telefon sau te uiți la ecran, agravează problema.', 'ajuta' => 'Ore regulate, mâncatul în liniște 15–20 minute.'],
                ['titlu' => 'Stres prelungit', 'desc' => 'Stresul afectează direct digestia prin sistemul nervos. Dacă ai săptămâni stresante la rând, balonarea apare frecvent, chiar dacă mănânci la fel ca înainte.', 'ajuta' => 'Somn mai bun, mișcare zilnică, respirație. Adaptogene precum așwagandha, doar după ce ai încercat astea.'],
            ],
        ],
        'autotest' => [
            'eyebrow' => 'Verificare rapidă',
            'titlu' => 'Răspunde la 4 întrebări, <em>vezi ce ți se potrivește.</em>',
            'intrebari' => [
                ['q' => '1. Apare mai des după mese mari?', 'default' => 0],
                ['q' => '2. S-a accentuat în ultimele săptămâni cu stres?', 'default' => 1],
                ['q' => '3. Există alimente specifice care o declanșează (lactate, pâine, varză)?', 'default' => 0],
                ['q' => '4. Durează mai mult de 24 de ore consecutiv?', 'default' => 2],
            ],
            'rezultat_strong' => 'Răspunsuri ca acestea sugerează o legătură cu dieta sau stresul.',
            'rezultat_text' => 'Prima dată, încearcă schimbările de obicei. Dacă nu se calmează în 2–3 săptămâni, un probiotic poate ajuta.',
        ],
        'medic' => [
            'titlu' => 'Când balonarea nu mai e <em>doar balonare.</em>',
            'lede' => 'Dacă oricare dintre semnele de mai jos apare, prioritar este să consulți un medic, nu un supliment.',
            'semnale' => [
                'Durează mai mult de 2 săptămâni fără explicație clară.',
                'E însoțită de durere puternică sau persistentă.',
                'Apar modificări vizibile în tranzit (frecvența, culoarea sau consistența scaunului).',
                'Pierzi în greutate fără să-ți propui.',
                'Ai antecedente familiale de afecțiuni digestive sau cancere abdominale.',
            ],
            'foot' => 'Menționarea unui medic aici nu e clauză legală. Este pur și simplu cel mai bun sfat pe care ți-l putem da în aceste situații.',
        ],
        'produse' => [
            'eyebrow' => 'Dacă ai încercat schimbările de obicei și nu ajung',
            'titlu' => 'Trei opțiuni, <em>în ordine logică.</em>',
            'intro' => 'Începe cu prima. Dacă după 4 săptămâni nu vezi schimbare, treci la următoarea.',
            'items' => [
                ['slug' => 'microflora-lemon-shots-500-ml-33-shots', 'nume' => 'Microflora+', 'pret' => '119 lei', 'opt' => 'Opțiune 01', 'category' => 'Digestie · probiotic', 'why' => '15 tulpini, 50 miliarde CFU, capsule gastro-rezistente. Pentru cazurile unde dezechilibrul florei intestinale e cauza principală.', 'cta' => 'Vezi produsul', 'cta_class' => 'btn-terra'],
                ['slug' => 'd-tox-ficat', 'nume' => 'D-Tox Ficat', 'pret' => '89 lei', 'opt' => 'Opțiune 02', 'category' => 'Detox · ficat', 'why' => 'Extract de armurariu, anghinare, tămâiță. Pentru când ficatul nu prelucrează suficient de eficient și digestia suferă indirect.', 'cta' => 'Vezi produsul', 'cta_class' => 'btn-secondary-g'],
                ['slug' => 'lionfocus-b6-jeleuri', 'nume' => 'LionFocus B6', 'pret' => '99 lei', 'opt' => 'Opțiune 03', 'category' => 'Stres · adaptogene', 'why' => 'Așwagandha KSM-66, vitamina B6, rhodiola. Pentru când balonarea e legată clar de stres prelungit și somn agitat.', 'cta' => 'Vezi produsul', 'cta_class' => 'btn-secondary-g'],
            ],
        ],
        'mituri' => [
            'eyebrow' => 'Câteva lucruri pe care le auzim des',
            'titlu' => 'Mituri despre balonare, <em>calm onest.</em>',
            'items' => [
                ['mit' => '„Apa rece la masă cauzează balonarea."', 'real' => 'Nu există dovezi solide. Temperatura apei nu modifică semnificativ digestia. Cantitatea și ritmul cu care bei, da.'],
                ['mit' => '„Dacă balonezi, ai intoleranță la gluten."', 'real' => 'Doar 1% din populație are boală celiacă. Sensibilitatea non-celiacă e mai frecventă, dar nu e prima ipoteză. Mai întâi ar trebui să verifici cu un medic, nu să elimini glutenul total fără motiv.'],
                ['mit' => '„Suplimentele «detox» curăță intestinul."', 'real' => 'Intestinul și ficatul se curăță singure, asta-i meseria lor. Suplimentele bune susțin funcțiile naturale, nu «scot toxine». Cine spune asta, vinde.'],
            ],
        ],
        'faq' => [
            'eyebrow' => 'Despre balonare',
            'titlu' => 'Răspunsuri <em>scurte.</em>',
            'items' => [
                ['q' => 'Cât timp durează ca un probiotic să ajute?', 'a' => 'De obicei <strong>2 până la 4 săptămâni</strong> pentru a observa schimbări vizibile, cu condiția să iei zilnic și să mănânci normal în restul timpului. Sub 14 zile e prea devreme pentru concluzii. Dacă în 6 săptămâni nu vezi nimic, probabil cauza nu e flora intestinală.'],
                ['q' => 'Pot să iau probiotic și antibiotic în același timp?', 'a' => 'Da, dar la distanță de <strong>2–3 ore</strong> de antibiotic, ca bacteriile bune să nu fie distruse imediat. Ideal, continuă probioticul încă 1–2 săptămâni după ce termini antibioticul.'],
                ['q' => 'De ce mi se umflă doar dimineața?', 'a' => 'De obicei e legat de cină târzie, digestie lentă peste noapte sau retenție de apă. Încearcă o cină mai ușoară, cu 3 ore înainte de culcare, și hidratare dimineața.'],
                ['q' => 'E balonarea legată de hormoni?', 'a' => 'Da, la femei balonarea fluctuează cu ciclul menstrual (mai ales înainte de menstruație), din cauza estrogenului și progesteronului. E normală dacă apare ciclic și se calmează în câteva zile.'],
            ],
        ],
    ],

    // =====================================================================
    // CEAȚA MENTALĂ
    // =====================================================================
    'ceata-mentala' => [
        'title' => 'Ceața mentală',
        'hero' => [
            'eyebrow' => 'După simptom',
            'titlu' => 'Ceață mentală. <em>Nu ești tu,</em> e creierul înfometat.',
            'lede' => 'Dacă uiți cuvinte simple, dacă pierzi firul în conversații, dacă citești același paragraf de 3 ori, nu e vârsta și nu e demență. Creierul cere combustibil specific: omega-3 pentru membrane, B12 pentru neurotransmițători, glucoză stabilă. Când lipsește, claritatea se evaporă.',
            'chips' => [
                'Creierul consumă 20% din energia corpului',
                'B12 scade după 40 ani natural',
                'Focus-ul se reglează în 4–8 săptămâni',
            ],
        ],
        'definitie' => [
            'eyebrow' => 'Definiție simplă',
            'titlu' => 'Creierul are <em>nevoi specifice.</em>',
            'cells' => [
                ['titlu' => 'Omega-3 construiește membranele', 'text' => '60% din creier e grăsime. Omega-3 (DHA) e structura membranelor neuronale. Deficitul dă ceață, dispoziție scăzută, memorie slăbită.'],
                ['titlu' => 'B12 și B6 fac neurotransmițătorii', 'text' => 'Serotonina, dopamina, noradrenalina au nevoie de B12 și B6 pentru sinteză. Deficitul dă ceață, oboseală cognitivă, dispoziție schimbătoare.'],
                ['titlu' => 'Lion\'s Mane susține regenerarea', 'text' => 'Ciuperca medicinală cu efect demonstrat pe NGF (Nerve Growth Factor). Susține claritatea și memoria de lucru pe termen lung.'],
            ],
        ],
        'semne' => [
            'eyebrow' => 'Cum se simte de obicei',
            'titlu' => 'Trei tipuri de <em>ceață care apar primele.</em>',
            'items' => [
                ['titlu' => 'Uiți cuvinte simple sau nume', 'desc' => '„Am uitat cum se spune la…" Indică deficit de B12, omega-3 sau hidratare slabă.', 'ajuta' => 'Omega-3 1000–2000 mg/zi, B12 (mai ales vegetarieni și peste 40), 2 litri apă/zi.'],
                ['titlu' => 'Pierzi firul în conversații', 'desc' => 'Mintea se distrage la jumătatea propoziției. Indică oboseală cognitivă, glicemie instabilă sau lipsa Lion\'s Mane.', 'ajuta' => 'Mese cu proteine, pauze de 5 min la 90 min de muncă, plimbare în natură 30 min/zi.'],
                ['titlu' => 'Citești același text repetat', 'desc' => 'Memoria de lucru slăbită, concentrarea fragmentată. Indică multitasking cronic, telefon obsesiv, somn slab.', 'ajuta' => 'Deep work 90 min fără telefon, somn 7–8h, Lion\'s Mane 500–1000 mg/zi.'],
            ],
        ],
        'autotest' => [
            'eyebrow' => 'Verificare rapidă',
            'titlu' => 'Răspunde la 4 întrebări, <em>vezi ce ți se potrivește.</em>',
            'intrebari' => [
                ['q' => '1. Uiți cuvinte sau nume pe care le știi bine?', 'default' => 0],
                ['q' => '2. Citești același paragraf de mai multe ori fără să-l înțelegi?', 'default' => 0],
                ['q' => '3. Folosești telefonul cu 4+ aplicații deschise simultan?', 'default' => 0],
                ['q' => '4. Ai măsurat B12 în sânge în ultimii 2 ani?', 'default' => 2],
            ],
            'rezultat_strong' => 'Răspunsurile sugerează oboseală cognitivă din supraîncărcare și posibile deficite (B12, omega-3).',
            'rezultat_text' => 'Începe cu deep work, somn de calitate și suplimente specifice. Măsoară B12 dacă nu te-ai măsurat.',
        ],
        'medic' => [
            'titlu' => 'Când ceața nu mai e <em>doar ceață.</em>',
            'lede' => 'Dacă oricare dintre semnele de mai jos apare, prioritar este să consulți un medic, nu să încerci suplimentele singur.',
            'semnale' => [
                'Confuzie severă sau dezorientare bruscă.',
                'Pierderi de memorie care interferează cu viața zilnică.',
                'Dificultate de a recunoaște persoane apropiate.',
                'Schimbări de personalitate observate de cei din jur.',
                'Pierderea cuvintelor familiare în mod accelerat.',
            ],
            'foot' => 'Ceața mentală persistentă sau în agravare merită evaluare medicală (neurologic, psihiatric). Suplimentele nu înlocuiesc diagnosticul.',
        ],
        'produse' => [
            'eyebrow' => 'Dacă ai încercat schimbări de obicei și nu ajung',
            'titlu' => 'Trei opțiuni, <em>în ordine logică.</em>',
            'intro' => 'Începe cu prima. Dacă după 4–8 săptămâni nu vezi schimbare, treci la următoarea.',
            'items' => [
                ['slug' => 'pachet-focus', 'nume' => 'Pachet Focus', 'pret' => '292 lei', 'opt' => 'Opțiune 01', 'category' => 'Focus · claritate', 'why' => 'Claritate mentală, concentrare și energie, suplimente vegan premium, 50 zile. Pentru ceață mentală, lipsă de claritate, uitări frecvente, memorie de lucru slabă.', 'cta' => 'Vezi pachetul', 'cta_class' => 'btn-terra'],
                ['slug' => 'pachet-energie', 'nume' => 'Pachet Energie', 'pret' => '306 lei', 'opt' => 'Opțiune 02', 'category' => 'Energie · adaptogen', 'why' => 'Multivitamine și adaptogeni vegan, 120 zile. Pentru oboseală cognitivă, dependență de cafea, prăbușire după-amiaza.', 'cta' => 'Vezi pachetul', 'cta_class' => 'btn-secondary-g'],
                ['slug' => 'black-seed-elixir', 'nume' => 'Black Seed Elixir', 'pret' => '184 lei', 'opt' => 'Opțiune 03', 'category' => 'Metabolic · creier', 'why' => 'Ulei chimen negru egiptean și vitamina E, 240 capsule vegane, 120 doze. Pentru protecție celulară, echilibru metabolic care susține funcția cognitivă.', 'cta' => 'Vezi produsul', 'cta_class' => 'btn-secondary-g'],
            ],
        ],
        'mituri' => [
            'eyebrow' => 'Câteva lucruri pe care le auzim des',
            'titlu' => 'Mituri despre focus și memorie, <em>calm onest.</em>',
            'items' => [
                ['mit' => '„Multitasking-ul mă face mai productiv."', 'real' => 'Creierul nu face multitask, ci comută rapid între sarcini. Fiecare comutare costă energie cognitivă. Studiile arată scădere de 40% în productivitate la multitasking real.'],
                ['mit' => '„Am «memorie de pește», nu se poate îmbunătăți."', 'real' => 'Memoria e antrenabilă la orice vârstă. Somnul, exercițiile de atenție și suplimentele specifice (omega-3, B12) o îmbunătățesc măsurabil în 8–12 săptămâni.'],
                ['mit' => '„Cafeaua mă ajută să mă concentrez."', 'real' => 'Cafeaua îți crește alerta, nu focus-ul profund. În exces, fragmentează atenția și slăbește somnul, ceea ce scade focus-ul a doua zi. Maxim 1–2/zi, niciodată după 14:00.'],
            ],
        ],
        'faq' => [
            'eyebrow' => 'Despre ceață mentală',
            'titlu' => 'Răspunsuri <em>scurte.</em>',
            'items' => [
                ['q' => 'Când voi vedea îmbunătățire?', 'a' => 'Primele schimbări în <strong>2–3 săptămâni</strong> (mai multă claritate dimineața). Memoria de lucru se vede după 6–8 săptămâni. Important: nu cumula prea multe schimbări odată.'],
                ['q' => 'Pot lua Lion\'s Mane cu antidepresive?', 'a' => 'Discută cu psihiatrul. Lion\'s Mane influențează NGF (Nerve Growth Factor) și poate interacționa cu medicația psihiatrică. Nu începe singur.'],
                ['q' => 'Omega-3 din pește sau din alge?', 'a' => 'Ambele funcționează. Peștele are EPA+DHA în proporție naturală, algele sunt vegan-friendly cu DHA dominant. Verifică IFOS sau certificare similară împotriva metalelor grele.'],
                ['q' => 'Ce alimente ajută zilnic focus-ul?', 'a' => 'Pește gras de 2 ori pe săptămână, gălbenuș de ou (colină), avocado, fructe de pădure, ciocolată neagră 85%+. Evită cereale dulci la mic dejun.'],
            ],
        ],
    ],

    // =====================================================================
    // OBOSEALĂ CRONICĂ
    // =====================================================================
    'oboseala-cronica' => [
        'title' => 'Oboseală cronică',
        'hero' => [
            'eyebrow' => 'După simptom',
            'titlu' => 'Oboseală care <em>nu trece cu somn.</em>',
            'lede' => 'Dacă te trezești deja obosit, dacă cafeaua nu mai funcționează, dacă la 14:00 te prăbușești, nu e vinovată vârsta. E motorul celular: mitocondriile, B-complexul, magneziul și Q10 care fac energia. Când unul lipsește, sistemul își reduce ritmul.',
            'chips' => [
                '1 din 3 adulți acuză oboseală cronică',
                'Deficitele subclinice apar înainte de analize',
                'Energia se reface în 2–4 săptămâni',
            ],
        ],
        'definitie' => [
            'eyebrow' => 'Definiție simplă',
            'titlu' => 'Energia e <em>un proces, nu o băutură.</em>',
            'cells' => [
                ['titlu' => 'Mitocondriile fac energia', 'text' => 'Fiecare celulă are mitocondrii care produc ATP (energia chimică). Au nevoie de Q10, magneziu și B-complex ca să funcționeze.'],
                ['titlu' => 'B-complexul transformă mâncarea în energie', 'text' => 'B1, B2, B6, B12 sunt esențiali pentru conversia hranei în ATP. Deficitul dă oboseală chiar dacă dormi destul.'],
                ['titlu' => 'Vitamina D3 și fierul susțin oxigenarea', 'text' => 'Iarna, deficitul de D3 e regulă. Femeile la menstruație pot avea deficit subclinic de fier care taie energia.'],
            ],
        ],
        'semne' => [
            'eyebrow' => 'Cum se simte de obicei',
            'titlu' => 'Trei tipuri de <em>oboseală care apar primele.</em>',
            'items' => [
                ['titlu' => 'Dimineața trezești obosit(ă)', 'desc' => 'Deși ai dormit 7–8 ore, te trezești ca și cum n-ai fi dormit. Indică microbiom dezechilibrat, magneziu deficient sau apnee de somn netratată.', 'ajuta' => 'Magneziu bisglicinat seara, reduci alcoolul, verifici dacă sforăi intens.'],
                ['titlu' => 'Prăbușire la 14:00', 'desc' => 'Lipsa totală de energie după-amiaza. Indică glicemie instabilă, dependență de cafeină și mic dejun cu carbohidrați simpli.', 'ajuta' => 'Mic dejun cu proteine și grăsimi, B-complex dimineața, plimbare 10 min după prânz.'],
                ['titlu' => 'Oboseală după efort minim', 'desc' => 'Scări, plimbări scurte care te epuizează. Indică mitocondrii slăbite, deficit de Q10 sau anemie.', 'ajuta' => 'Q10 (CoQ10) 100–200 mg/zi, mișcare progresivă, analize de sânge complete.'],
            ],
        ],
        'autotest' => [
            'eyebrow' => 'Verificare rapidă',
            'titlu' => 'Răspunde la 4 întrebări, <em>vezi ce ți se potrivește.</em>',
            'intrebari' => [
                ['q' => '1. Te trezești obosit(ă) chiar dacă dormi 7–8 ore?', 'default' => 0],
                ['q' => '2. Ai nevoie de 2–3 cafele/zi ca să funcționezi?', 'default' => 0],
                ['q' => '3. Te prăbușești după-amiaza, mai ales în jur de 14:00?', 'default' => 1],
                ['q' => '4. Ai făcut analize de sânge complete în ultimul an?', 'default' => 2],
            ],
            'rezultat_strong' => 'Răspunsurile sugerează oboseală din cauză micronutritivă.',
            'rezultat_text' => 'Începe cu somn de calitate, B-complex și Q10 în 2–4 săptămâni. Dacă nu vezi schimbare, fă analize complete (B12, D3, fier, feritină, tiroidă).',
        ],
        'medic' => [
            'titlu' => 'Când oboseala nu mai e <em>doar oboseală.</em>',
            'lede' => 'Dacă oricare dintre semnele de mai jos apare, prioritar este să consulți un medic și să faci analize, nu să încerci suplimentele singur.',
            'semnale' => [
                'Oboseală severă mai mult de 6 săptămâni.',
                'Sufocație la efort ușor sau în repaus.',
                'Pierdere de greutate fără să-ți propui.',
                'Ganglioni umflați persistenți.',
                'Depresie persistentă sau lipsa totală de interes.',
            ],
            'foot' => 'Oboseala cronică poate ascunde tiroidă, anemie severă, apnee sau condiții care merită investigate medical.',
        ],
        'produse' => [
            'eyebrow' => 'Dacă ai încercat schimbări de obicei și nu ajung',
            'titlu' => 'Trei opțiuni, <em>în ordine logică.</em>',
            'intro' => 'Începe cu prima. Dacă după 4 săptămâni nu vezi schimbare, treci la următoarea.',
            'items' => [
                ['slug' => 'pachet-energie', 'nume' => 'Pachet Energie', 'pret' => '306 lei', 'opt' => 'Opțiune 01', 'category' => 'Energie · adaptogen', 'why' => 'Multivitamine și adaptogeni vegan, 120 zile. Pentru oboseală persistentă, trezire grea, cafea obligatorie, prăbușire la 14:00.', 'cta' => 'Vezi pachetul', 'cta_class' => 'btn-terra'],
                ['slug' => 'black-seed-elixir', 'nume' => 'Black Seed Elixir', 'pret' => '184 lei', 'opt' => 'Opțiune 02', 'category' => 'Metabolic · echilibru', 'why' => 'Ulei chimen negru egiptean și vitamina E, 240 capsule vegane, 120 doze. Pentru echilibru metabolic, suport ficat, glicemie în echilibru.', 'cta' => 'Vezi produsul', 'cta_class' => 'btn-secondary-g'],
                ['slug' => 'pachet-complex-sanatate', 'nume' => 'Pachet Complex Sănătate 40+', 'pret' => '499 lei', 'opt' => 'Opțiune 03', 'category' => 'Complet · 40+', 'why' => 'Energie, imunitate, protecție celulară, 120 zile. Pentru epuizare cronică după 40, energie scăzută, metabolism încetinit, abordare completă.', 'cta' => 'Vezi pachetul', 'cta_class' => 'btn-secondary-g'],
            ],
        ],
        'mituri' => [
            'eyebrow' => 'Câteva lucruri pe care le auzim des',
            'titlu' => 'Mituri despre oboseală, <em>calm onest.</em>',
            'items' => [
                ['mit' => '„Cafeaua îți dă energie."', 'real' => 'Cafeaua blochează adenozina (semnalul de oboseală), nu creează energie. Când efectul trece, oboseala revine amplificată. Pe termen lung, sistemul devine dependent.'],
                ['mit' => '„Dacă dorm mai mult, voi avea mai multă energie."', 'real' => 'Calitatea contează mai mult decât cantitatea. 7 ore de somn profund sunt mai bune decât 9 ore de somn fragmentat. Magneziul, redusul alcoolului și întunericul total ajută calitatea.'],
                ['mit' => '„Dacă mănânc dulciuri, prind energie."', 'real' => 'Zahărul dă vârf de 15–30 min, apoi prăbușire. Creează cerc vicios. Proteinele și grăsimile bune susțin energia stabil 3–4 ore.'],
            ],
        ],
        'faq' => [
            'eyebrow' => 'Despre oboseală',
            'titlu' => 'Răspunsuri <em>scurte.</em>',
            'items' => [
                ['q' => 'Cât timp durează până simt energie?', 'a' => 'Primele schimbări în <strong>2–3 săptămâni</strong>. Reglarea completă cere 2–4 luni de constanță. Dacă nu vezi nimic în 6 săptămâni, cauza e altundeva.'],
                ['q' => 'Pot lua B-complex și Q10 împreună?', 'a' => 'Da, sunt complementare. B-complex se ia dimineața (energizant), Q10 la prânz cu o masă cu grăsimi (se absoarbe mai bine).'],
                ['q' => 'Am hipotiroidie, ce suplimente să evit?', 'a' => 'Evită suplimente cu iod neprescrise medical. Soia în doze mari poate interfera cu medicația tiroidiană. Discută cu endocrinologul înainte.'],
                ['q' => 'Când iau cafeaua dacă vreau să reduc dependența?', 'a' => 'După 9:30 dimineața (cortizolul natural scade), niciodată după 14:00. Maxim 1–2 cafele/zi. Hidratare înainte de cafea, nu în loc.'],
            ],
        ],
    ],

    // =====================================================================
    // ARTICULAȚII
    // =====================================================================
    'articulatii' => [
        'title' => 'Articulații & mobilitate',
        'hero' => [
            'eyebrow' => 'După simptom',
            'titlu' => 'Articulații. <em>Cartilajul</em> nu se reface peste noapte, dar se hrănește zilnic.',
            'lede' => 'Articulațiile au nevoie de colagen tip II, vitamina C, glucozamină, omega-3, magneziu, și de mișcare zilnică. Nu doar pastile. Mobilitatea contează mai mult decât orice supliment, suplimentele susțin matricea, mișcarea o hrănește.',
            'chips' => [
                'Cartilajul nu are vase de sânge',
                'Lichid sinovial reciclat la 1–2 ore prin mișcare',
                '60–70% din articulație e apă',
            ],
        ],
        'definitie' => [
            'eyebrow' => 'Definiție simplă',
            'titlu' => 'Articulația e <em>colagen, apă și mișcare.</em>',
            'cells' => [
                ['titlu' => 'Colagen tip II construiește cartilajul', 'text' => 'Cartilajul e 70% colagen tip II și apă. Spre deosebire de tipul I (piele, oase), tipul II e specific articulațiilor. Producția scade vizibil după 40 de ani.'],
                ['titlu' => 'Vitamina C sintetizează colagenul', 'text' => 'Fără vitamina C, corpul nu poate produce colagen, indiferent câți aminoacizi îi dai. Cofactor obligatoriu. Cantitatea zilnică naturală din legume colorate e suficientă, dar mulți nu o ating.'],
                ['titlu' => 'Omega-3 reduce inflamația de fond', 'text' => 'EPA și DHA din uleiul de pește reduc inflamația cronică care erodează cartilajul. Studii constante: 2–3 g/zi îmbunătățesc rigiditatea matinală.'],
            ],
        ],
        'semne' => [
            'eyebrow' => 'Ce simți de obicei',
            'titlu' => 'Trei semne <em>care apar primele.</em>',
            'items' => [
                ['titlu' => 'Genunchi sau șold care scrâșnesc, pocnesc', 'desc' => 'La urcat scări, la ridicat din șezut, la mers prelungit. Fără durere, pocnetul e de obicei benign (bule de gaz, tendoane). Cu durere, e semnal.', 'ajuta' => 'Colagen tip II 5–10 g/zi, mișcare zilnică (30 min mers), încălzire înainte de efort.'],
                ['titlu' => 'Rigiditate care cedează după mișcare', 'desc' => 'Dimineața te miști greu primele 15–30 minute, apoi se relaxează. Indică inflamație de fond și lichid sinovial care „își ia ritmul" prin mișcare.', 'ajuta' => 'Stretching 5 min dimineața, omega-3, hidratare 2 litri/zi.'],
                ['titlu' => 'Disconfort după efort prelungit', 'desc' => 'Mers de o oră, urcat 4 etaje, grădinărit. Articulațiile se obosesc mai repede ca înainte. Indică uzură progresivă a cartilajului.', 'ajuta' => 'Colagen + vitamina C, magneziu (relaxare musculară), pauze active în loc de odihnă totală.'],
            ],
        ],
        'autotest' => [
            'eyebrow' => 'Verificare rapidă',
            'titlu' => 'Răspunde la 4 întrebări, <em>vezi ce ți se potrivește.</em>',
            'intrebari' => [
                ['q' => '1. Simți rigiditate mai mult de 30 minute dimineața?', 'default' => 0],
                ['q' => '2. Ai disconfort la urcat sau coborât scări?', 'default' => 0],
                ['q' => '3. Articulațiile sună sau pocnesc des?', 'default' => 1],
                ['q' => '4. Ai redus mișcarea din cauza durerii?', 'default' => 0],
            ],
            'rezultat_strong' => 'Răspunsurile sugerează uzură de fond și matrice slăbită.',
            'rezultat_text' => 'Începe cu colagen tip II zilnic, mișcare blândă 30 min/zi, omega-3 și hidratare. Schimbările vizibile apar în 6–12 săptămâni.',
        ],
        'medic' => [
            'titlu' => 'Când nu mai e <em>doar uzură.</em>',
            'lede' => 'Dacă oricare dintre semnele de mai jos apare, prioritar este să consulți un medic reumatolog, nu să încerci suplimentele singur.',
            'semnale' => [
                'Umflătură caldă și roșie a unei articulații (posibil artrită inflamatorie).',
                'Durere nocturnă care te trezește din somn.',
                'Articulație blocată sau care cedează brusc la sprijin.',
                'Febră asociată cu durere articulară.',
                'Durere migratoare între articulații sau simetrică.',
            ],
            'foot' => 'Acestea cer consult reumatologic, nu suplimente. Artritele inflamatorii și autoimune au tratamente specifice care nu pot fi înlocuite.',
        ],
        'produse' => [
            'eyebrow' => 'Dacă mișcarea zilnică nu e suficientă',
            'titlu' => 'Trei opțiuni, <em>în ordine logică.</em>',
            'intro' => 'Începe cu prima. Dacă după 8–12 săptămâni nu vezi schimbare, treci la următoarea.',
            'items' => [
                ['slug' => 'collagen-joint-berry-500-ml', 'nume' => 'Collagen Joint+ Berry', 'pret' => '184 lei', 'opt' => 'Opțiune 01', 'category' => 'Articulații · colagen tip II', 'why' => 'Colagen hidrolizat tip II cu vitamina C, formulat specific pentru articulații și mobilitate. Aromă naturală de fructe de pădure, ușor de băut.', 'cta' => 'Vezi produsul', 'cta_class' => 'btn-terra'],
                ['slug' => 'pachet-regenerare-celulara', 'nume' => 'Pachet Regenerare Celulară', 'pret' => '524 lei', 'opt' => 'Opțiune 02', 'category' => 'Profund · antioxidant', 'why' => 'Pentru abordare profundă, antioxidantă, când vrei să lucrezi și la inflamația de fond, nu doar la simptom. 3 suplimente vegan, 120 zile.', 'cta' => 'Vezi pachetul', 'cta_class' => 'btn-secondary-g'],
                ['slug' => 'pachet-frumusete', 'nume' => 'Pachet Frumusețe', 'pret' => '349 lei', 'opt' => 'Opțiune 03', 'category' => 'Bonus · păr unghii ten', 'why' => 'Colagenul lucrează și pentru păr, unghii, ten — aceiași aminoacizi. Bonus dacă observi și unghii fragile sau ten obosit alături de rigiditate articulară.', 'cta' => 'Vezi pachetul', 'cta_class' => 'btn-secondary-g'],
            ],
        ],
        'mituri' => [
            'eyebrow' => 'Câteva lucruri pe care le auzim des',
            'titlu' => 'Mituri despre articulații, <em>calm onest.</em>',
            'items' => [
                ['mit' => '„Pocnitul înseamnă că ai artroză."', 'real' => 'Cele mai multe pocnete sunt bule de gaz în lichidul sinovial sau tendoane care alunecă peste oase. Fără durere, fără umflătură, fără rigiditate persistentă, nu sunt semn de boală.'],
                ['mit' => '„Dacă doare, trebuie să te odihnești."', 'real' => 'Imobilitatea agravează în majoritatea cazurilor. Mișcarea blândă hrănește cartilajul prin lichidul sinovial (cartilajul nu are vase de sânge). Odihnă totală doar la inflamație acută, niciodată la uzură.'],
                ['mit' => '„Glucozamina rezolvă orice problemă."', 'real' => 'Dovezile sunt mixte. Funcționează la unii, nu la alții. Colagenul tip II are date mai consistente în ultimii ani. Verifică pe tine 8–12 săptămâni, dacă nu simți schimbare, treci la altceva.'],
            ],
        ],
        'faq' => [
            'eyebrow' => 'Despre articulații',
            'titlu' => 'Răspunsuri <em>scurte.</em>',
            'items' => [
                ['q' => 'Cât timp durează până simt diferența?', 'a' => 'Rigiditatea matinală scade în <strong>3–4 săptămâni</strong>. Mobilitatea generală se îmbunătățește la 2–3 luni de colagen + mișcare. Cartilajul nu se reface complet, dar matricea se hidratează și se susține mai bine.'],
                ['q' => 'Colagen tip I sau tip II pentru articulații?', 'a' => 'Tip II e specific cartilajului articular. Tip I e pentru piele, oase, tendoane. Dacă obiectivul principal e articulația, tip II are date mai țintite. Pentru beneficii combinate (piele + articulații), tipuri I+III funcționează general.'],
                ['q' => 'Pot face sport dacă mă doare?', 'a' => 'Da, dar adaptat. Mers, înot, bicicletă, yoga blândă, fără impact. Evită alergat pe asfalt, sărit, squat-uri grele. Mișcarea hrănește cartilajul, repausul total îl atrofiază. Dacă durerea crește la efort, oprește și consultă un specialist.'],
                ['q' => 'Trebuie să iau colagen pe viață?', 'a' => 'Nu pe viață, dar cure de 3–4 luni cu pauze de 1 lună sunt rezonabile, mai ales după 40 de ani când sinteza naturală scade. Important: dieta cu proteine adecvate (ouă, pește, leguminoase) și mișcare zilnică rămân fundația.'],
            ],
        ],
    ],

    // =====================================================================
    // RĂCELI FRECVENTE
    // =====================================================================
    'raceli-frecvente' => [
        'title' => 'Răceli frecvente',
        'hero' => [
            'eyebrow' => 'După simptom',
            'titlu' => 'Răceli peste răceli. <em>Sistemul imunitar</em> îți spune ceva.',
            'lede' => 'Dacă răcești de 4–5 ori pe an, dacă virozele durează mai mult de o săptămână, dacă te vindeci greu, nu e ghinion. Imunitatea se construiește zilnic prin somn, mișcare, hrană și micronutrienți. Când unul lipsește, sistemul devine vulnerabil.',
            'chips' => [
                'Adulții fac în medie 2–3 răceli/an',
                'Imunitatea cere 4–8 săptămâni pentru reglare',
                'Vit D3 + Zinc sunt cele mai des deficitare',
            ],
        ],
        'definitie' => [
            'eyebrow' => 'Definiție simplă',
            'titlu' => 'Imunitatea e <em>un sistem, nu un produs.</em>',
            'cells' => [
                ['titlu' => 'Vitamina D3 reglează imunitatea', 'text' => 'Iarna, când nu vezi soarele, nivelul scade dramatic. 80% din români au deficit în lunile reci.'],
                ['titlu' => 'Zincul accelerează răspunsul', 'text' => 'Participă în crearea celulelor imunitare. Deficitul de zinc prelungește virozele.'],
                ['titlu' => 'Vitamina C susține, nu vindecă', 'text' => 'Nu oprește răceala, dar scurtează durata cu 1–2 zile dacă e luată constant, nu doar când ești deja bolnav.'],
            ],
        ],
        'semne' => [
            'eyebrow' => 'Cum se vede de obicei',
            'titlu' => 'Trei semne <em>că imunitatea cere ajutor.</em>',
            'items' => [
                ['titlu' => 'Răceli mai des de 3 ori pe an', 'desc' => 'Frecvența indică un sistem suprasolicitat. Stresul cronic, somnul slab și deficitele subclinice cresc vulnerabilitatea.', 'ajuta' => 'Somn 7–8h, vitamina D3 5000 UI iarna, reducerea zahărului.'],
                ['titlu' => 'Vindecare lentă după virus', 'desc' => 'Tuse care ține 3 săptămâni după răceală, oboseală persistentă. Indică rezerve epuizate.', 'ajuta' => 'Zinc 15–25 mg, repaus real, hidratare cu electroliți, evitarea efortului intens.'],
                ['titlu' => 'Alergii sezoniere care nu cedează', 'desc' => 'Reacții la praf, polen, anumite alimente. Imunitatea care reacționează disproporționat sau confuz.', 'ajuta' => 'Quercetină, omega-3, microbiom echilibrat (probiotice).'],
            ],
        ],
        'autotest' => [
            'eyebrow' => 'Verificare rapidă',
            'titlu' => 'Răspunde la 4 întrebări, <em>vezi ce ți se potrivește.</em>',
            'intrebari' => [
                ['q' => '1. Ai răcit de mai mult de 3 ori în ultimul an?', 'default' => 0],
                ['q' => '2. Te vindeci greu după viroze (peste 7–10 zile)?', 'default' => 0],
                ['q' => '3. Dormi sub 7 ore pe noapte în mod regulat?', 'default' => 1],
                ['q' => '4. Ți s-a măsurat vreodată vitamina D în sânge în ultimii 2 ani?', 'default' => 2],
            ],
            'rezultat_strong' => 'Răspunsurile sugerează că sistemul imunitar e suprasolicitat.',
            'rezultat_text' => 'Începe cu somn, vitamina D3 și zinc în sezonul rece. Dacă după 6–8 săptămâni nu vezi schimbare, măsoară-ți analizele.',
        ],
        'medic' => [
            'titlu' => 'Când răcelile nu mai sunt <em>doar răceli.</em>',
            'lede' => 'Dacă oricare dintre semnele de mai jos apare, prioritar este să consulți un medic, nu să încerci suplimentele singur.',
            'semnale' => [
                'Febră peste 38°C mai mult de 3 zile.',
                'Dureri toracice sau respirație dificilă.',
                'Tuse cu sânge sau secreții verzi/galbene dense.',
                'Pierdere în greutate inexplicabilă.',
                'Infecții repetate (mai mult de 4 pe an cu antibiotice).',
            ],
            'foot' => 'Menționarea unui medic aici nu e clauză legală. Infecțiile repetate merită investigații dincolo de suplimente.',
        ],
        'produse' => [
            'eyebrow' => 'Dacă ai încercat schimbări de obicei și nu ajung',
            'titlu' => 'Trei opțiuni, <em>în ordine logică.</em>',
            'intro' => 'Începe cu prima. Dacă după 6 săptămâni nu vezi schimbare, treci la următoarea.',
            'items' => [
                ['slug' => 'pachet-imunitate', 'nume' => 'Pachet Imunitate', 'pret' => '349 lei', 'opt' => 'Opțiune 01', 'category' => 'Imunitate · complet', 'why' => 'Vitamina C + Vitamina E, vegan, 120 zile. Pentru răceli frecvente, alergii, imunitate slabă, vindecare lentă. Vit C 250% + Zinc + D3 + timoquinonă.', 'cta' => 'Vezi pachetul', 'cta_class' => 'btn-terra'],
                ['slug' => 'black-seed-elixir', 'nume' => 'Black Seed Elixir', 'pret' => '184 lei', 'opt' => 'Opțiune 02', 'category' => 'Metabolic · imunitate', 'why' => 'Ulei chimen negru egiptean și vitamina E, 240 capsule vegane, 120 doze. Pentru protecție imunitară zilnică și echilibru metabolic natural.', 'cta' => 'Vezi produsul', 'cta_class' => 'btn-secondary-g'],
                ['slug' => 'pachet-complex-sanatate', 'nume' => 'Pachet Complex Sănătate 40+', 'pret' => '499 lei', 'opt' => 'Opțiune 03', 'category' => 'Complet · 40+', 'why' => 'Energie, imunitate, protecție celulară, 120 zile. Pentru cei după 40 cu răceli frecvente, oboseală cronică și metabolism încetinit, abordare completă.', 'cta' => 'Vezi pachetul', 'cta_class' => 'btn-secondary-g'],
            ],
        ],
        'mituri' => [
            'eyebrow' => 'Câteva lucruri pe care le auzim des',
            'titlu' => 'Mituri despre imunitate, <em>calm onest.</em>',
            'items' => [
                ['mit' => '„Vitamina C în doze mari oprește răceala."', 'real' => 'Studiile arată că vitamina C luată constant scurtează durata cu 1–2 zile. Megadozele (peste 2000 mg) nu opresc răceala și pot da disconfort digestiv. Constant, nu reactiv.'],
                ['mit' => '„Dacă mă îmbrac gros, nu răcesc."', 'real' => 'Răcelile sunt virale, nu cauzate de frig direct. Frigul scade imunitatea locală în mucoase, dar virusurile sunt sursa. Îmbracă-te corect, dar nu uita de somn, D3 și zinc.'],
                ['mit' => '„Antibioticele ajută la răceală."', 'real' => 'Antibioticele nu funcționează pe viruși. Luate inutil, distrug microbiomul și slăbesc imunitatea în viitor. Răcelile cer repaus, hidratare și timp.'],
            ],
        ],
        'faq' => [
            'eyebrow' => 'Despre imunitate',
            'titlu' => 'Răspunsuri <em>scurte.</em>',
            'items' => [
                ['q' => 'Când încep să iau vitamina D3?', 'a' => 'Din <strong>octombrie până în aprilie</strong>, zilnic. În restul anului, doar dacă nu te expui la soare minim 20 min/zi. Ideal: măsoară-ți nivelul înainte.'],
                ['q' => 'Pot lua zinc și vitamina C împreună?', 'a' => 'Da, sunt complementare. Zincul acționează pe celulele imunitare, vitamina C susține bariera. Combinația e standard în protocoalele de sezon.'],
                ['q' => 'Am copii, le pot da aceleași suplimente?', 'a' => 'Nu înainte de a consulta pediatrul. Dozele pentru copii sunt diferite și unele suplimente nu sunt indicate sub 12 ani.'],
                ['q' => 'Cât timp durează până văd schimbare?', 'a' => 'Primele efecte în 2–4 săptămâni, reducerea frecvenței răcelilor se vede pe un sezon întreg (3–4 luni).'],
            ],
        ],
    ],

    // =====================================================================
    // RECUPERARE DUPĂ ANTRENAMENT
    // =====================================================================
    'recuperare-antrenament' => [
        'title' => 'Recuperare după antrenament',
        'hero' => [
            'eyebrow' => 'După simptom',
            'titlu' => 'Recuperare. <em>Mușchiul</em> nu crește în sală, ci între antrenamente.',
            'lede' => 'Progresul vine din ciclul efort → hrană → somn → repaus. Proteinele de calitate, creatina, electroliții, magneziul și somnul fac diferența. Mai mult antrenament fără mai multă recuperare e drumul cel mai sigur către platou și accidentări.',
            'chips' => [
                'Sinteza proteică ține 24–48h post-efort',
                'Necesar: 1,6–2,2 g proteină/kg/zi',
                'Creatina e suplimentul cu cele mai multe studii',
            ],
        ],
        'definitie' => [
            'eyebrow' => 'Definiție simplă',
            'titlu' => 'Recuperarea e <em>proteină, mineral, somn.</em>',
            'cells' => [
                ['titlu' => 'Proteină pentru reparare', 'text' => 'După efort, fibrele musculare au microleziuni. Aminoacizii din proteină le repară și le îngroașă. Whey, vegan, mâncare reală, toate funcționează dacă atingi 1,6–2,2 g/kg/zi.'],
                ['titlu' => 'Creatină pentru putere', 'text' => 'Crește energia ATP în efort scurt și intens. Mai multe repetări în setul greu, recuperare mai bună între serii. Doză: 3–5 g/zi, constant, fără faze de încărcare necesare.'],
                ['titlu' => 'Electroliți și magneziu', 'text' => 'Pierdute prin transpirație: sodiu, potasiu, magneziu. Necesare pentru contracția musculară și echilibru hidric. Deficitul dă crampe și senzația că mușchiul „nu trage".'],
            ],
        ],
        'semne' => [
            'eyebrow' => 'Ce simți de obicei',
            'titlu' => 'Trei semne <em>care apar primele.</em>',
            'items' => [
                ['titlu' => 'DOMS care durează 24–72 ore', 'desc' => 'Febra musculară post-antrenament. Apare la 12–24h, vârf la 48h, dispare în 72h. Normală la stimul nou, prelungită la deficit de proteină sau somn.', 'ajuta' => 'Proteină în 1–2h post-efort, somn 7–8h, hidratare cu electroliți, mișcare ușoară a doua zi.'],
                ['titlu' => 'Energie scăzută în zilele următoare', 'desc' => 'După o sesiune intensă, te simți „bătut" 2–3 zile. Indică recuperare incompletă, deficit caloric sau lipsă de glicogen.', 'ajuta' => 'Carbohidrați suficienți, somn de calitate, sesiuni alternative grele/ușoare, deload o săptămână la 4–6.'],
                ['titlu' => 'Crampe sau senzația că mușchiul „nu trage"', 'desc' => 'Performanță sub așteptări la sesiunea următoare, crampe la efort sau noaptea. Indică deficit de electroliți, magneziu sau creatină.', 'ajuta' => 'Electroliți pre/intra/post-antrenament, magneziu seara 200–400 mg, creatină constantă 3–5 g/zi.'],
            ],
        ],
        'autotest' => [
            'eyebrow' => 'Verificare rapidă',
            'titlu' => 'Răspunde la 4 întrebări, <em>vezi ce ți se potrivește.</em>',
            'intrebari' => [
                ['q' => '1. Ai DOMS care durează peste 72h frecvent?', 'default' => 0],
                ['q' => '2. Simți oboseală cumulată după 2–3 sesiuni?', 'default' => 0],
                ['q' => '3. Ai crampe sau spasme după efort?', 'default' => 1],
                ['q' => '4. Simți că nu progresezi deși te antrenezi constant?', 'default' => 0],
            ],
            'rezultat_strong' => 'Răspunsurile sugerează recuperare incompletă și aport proteic sau mineral insuficient.',
            'rezultat_text' => 'Începe cu proteină 1,6–2,2 g/kg/zi, creatină constantă, electroliți și somn 7–8h. Schimbarea apare în 3–4 săptămâni.',
        ],
        'medic' => [
            'titlu' => 'Când nu mai e <em>doar recuperare.</em>',
            'lede' => 'Dacă oricare dintre semnele de mai jos apare, prioritar este să consulți un medic sau medic sportiv, nu să încerci suplimentele singur.',
            'semnale' => [
                'Durere ascuțită într-un singur punct (posibil leziune musculară sau tendinoasă).',
                'Urină foarte închisă la culoare după efort intens (posibil rabdomioliză, urgență).',
                'Tahicardie persistentă în repaus, sub puls normal nu coboară.',
                'Cădere bruscă de performanță + insomnie + iritabilitate (posibil overtraining).',
                'Umflătură sau vânătaie nejustificată în zonele de efort.',
            ],
            'foot' => 'Acestea cer consult medical sau medic sportiv, nu suplimente. Recuperarea peste o leziune ignorată e cea mai rea variantă.',
        ],
        'produse' => [
            'eyebrow' => 'Dacă somnul și mâncarea nu sunt suficiente',
            'titlu' => 'Trei opțiuni, <em>în ordine logică.</em>',
            'intro' => 'Începe cu prima. Dacă după 4–6 săptămâni nu vezi schimbare, treci la următoarea.',
            'items' => [
                ['slug' => 'chocoprotein-1000g', 'nume' => 'ChocoProtein', 'pret' => '219 lei', 'opt' => 'Opțiune 01', 'category' => 'Reparare · proteină', 'why' => 'Proteină cu profil complet de aminoacizi pentru reparare musculară și sațietate post-efort. Aromă naturală de ciocolată, fără îndulcitori artificiali agresivi.', 'cta' => 'Vezi produsul', 'cta_class' => 'btn-terra'],
                ['slug' => 'creatine-monohidrate-pro-1000g', 'nume' => 'Creatine Monohidrate Pro', 'pret' => '219 lei', 'opt' => 'Opțiune 02', 'category' => 'Forță · putere', 'why' => 'Suplimentul cel mai studiat din sport: forță, putere, recuperare între serii, creșterea volumului de antrenament. Doză eficientă: 3–5 g/zi constant.', 'cta' => 'Vezi produsul', 'cta_class' => 'btn-secondary-g'],
                ['slug' => 'pachet-energie', 'nume' => 'Pachet Energie', 'pret' => '306 lei', 'opt' => 'Opțiune 03', 'category' => 'Susținere globală', 'why' => 'Pentru cei care vor susținere mai largă: vitalitate, energie, recuperare globală. Multivitamine și adaptogeni vegan, 120 zile.', 'cta' => 'Vezi pachetul', 'cta_class' => 'btn-secondary-g'],
            ],
        ],
        'mituri' => [
            'eyebrow' => 'Câteva lucruri pe care le auzim des',
            'titlu' => 'Mituri despre recuperare, <em>calm onest.</em>',
            'items' => [
                ['mit' => '„Fereastra anabolică e de 30 minute post-antrenament."', 'real' => 'Sinteza proteică e crescută 24–48h. Aportul total zilnic contează mai mult decât timing-ul strict. Dacă mănânci în 2–3h, nu pierzi nimic. Distribuie proteinele în 4 mese, nu doar post-efort.'],
                ['mit' => '„Creatina face mușchii să rețină apă și ți se umflă fața."', 'real' => 'Apa se reține intracelular, în fibra musculară, nu subcutanat. Nu cauzează umflături vizibile pe față. Volumul muscular ușor crescut e exact efectul dorit. Beneficiile depășesc cu mult mitul.'],
                ['mit' => '„Dacă nu ai DOMS, n-ai făcut treabă bună."', 'real' => 'DOMS arată stimul nou, nu progres. Poți crește în forță și mușchi fără să te doară după fiecare antrenament. Cu cât te adaptezi, cu atât scade DOMS, deși volumul de antrenament crește.'],
            ],
        ],
        'faq' => [
            'eyebrow' => 'Despre recuperare',
            'titlu' => 'Răspunsuri <em>scurte.</em>',
            'items' => [
                ['q' => 'Cât de repede simt efectul creatinei?', 'a' => 'Cu fază de încărcare (20 g/zi 5 zile): <strong>1 săptămână</strong>. Fără încărcare (3–5 g/zi constant): <strong>3–4 săptămâni</strong> ca să saturi mușchii. Diferența finală e zero. Alege ce ți se potrivește.'],
                ['q' => 'Câtă proteină trebuie să iau zilnic?', 'a' => 'Pentru construcție musculară: <strong>1,6–2,2 g/kg/zi</strong>. Pentru întreținere generală: 1,2–1,6 g/kg. Distribuită în 3–4 mese de 30–40 g. Mai mult de 2,5 g/kg nu aduce beneficii adiționale.'],
                ['q' => 'Pot lua creatină și proteină împreună?', 'a' => 'Da, e combinația standard. Creatina cu carbohidrați (banană, glucoză) se absoarbe ușor mai bine, dar nu obligatoriu. Cu shake-ul de proteine post-efort merge perfect.'],
                ['q' => 'Trebuie să fac deload?', 'a' => 'Da, la fiecare 4–6 săptămâni de antrenament progresiv. O săptămână cu volum și intensitate la 50–70%. Permite recuperarea sistemului nervos, articulațiilor și a chimiei hormonale. Surprinzător de important.'],
            ],
        ],
    ],

    // =====================================================================
    // STRES & SOMN
    // =====================================================================
    'stres-si-somn' => [
        'title' => 'Stres & somn',
        'hero' => [
            'eyebrow' => 'După simptom',
            'titlu' => 'Stresul nu te lasă <em>să dormi.</em> Somnul slab te face <em>mai stresat.</em>',
            'lede' => 'E bucla pe care o cunoaștem toți. Cortizolul ridicat seara blochează melatonina, somnul devine fragmentat, dimineața ești epuizat și mai puțin echipat să gestionezi stresul. Ieși din ea cu schimbări mici de obicei și sprijin pentru sistemul nervos.',
            'chips' => [
                '40% din adulți au insomnie cel puțin o noapte/săptămână',
                'Magneziul scade sub stres prelungit',
                'Adaptogenii cer 4–6 săptămâni de constanță',
            ],
        ],
        'definitie' => [
            'eyebrow' => 'Definiție simplă',
            'titlu' => 'Stres și somn sunt <em>aceeași conversație.</em>',
            'cells' => [
                ['titlu' => 'Cortizolul are ritm zilnic', 'text' => 'Dimineața urcă (te trezește), seara coboară (te lasă să dormi). Stresul cronic inversează curba.'],
                ['titlu' => 'Magneziul calmează sistemul nervos', 'text' => 'Participă în peste 300 de reacții enzimatice. Sub stres, corpul elimină mai mult magneziu prin urină.'],
                ['titlu' => 'Adaptogenii reglează răspunsul', 'text' => 'Plante precum ashwagandha sau rhodiola ajută corpul să răspundă echilibrat la stres. Nu sedează, calibrează.'],
            ],
        ],
        'semne' => [
            'eyebrow' => 'Cum se simte de obicei',
            'titlu' => 'Trei tipare <em>de stres-somn care apar primele.</em>',
            'items' => [
                ['titlu' => 'Adormi greu, mintea nu se oprește', 'desc' => 'Te întinzi în pat și creierul rulează scările zilei. Cortizolul încă e ridicat.', 'ajuta' => 'Magneziu bisglicinat 200–400 mg cu 1 oră înainte de somn, ecrane stinse cu 30 min înainte, cărți pe hârtie.'],
                ['titlu' => 'Te trezești la 2–3 noaptea', 'desc' => 'Adormi ușor, dar te trezești în mijlocul nopții și nu mai poți dormi. Indică glicemie instabilă sau cortizol dezechilibrat.', 'ajuta' => 'Cina cu proteine și grăsimi (nu carbohidrați), redu alcoolul, evită ecranele după 22.'],
                ['titlu' => 'Tensiune fără motiv, anxietate ușoară', 'desc' => 'Stare permanentă de alertă, palpitații, respirație superficială. Sistemul nervos blocat în simpatic.', 'ajuta' => 'Respirație 4-7-8, plimbări în natură, ashwagandha 300–600 mg/zi 4–6 săptămâni.'],
            ],
        ],
        'autotest' => [
            'eyebrow' => 'Verificare rapidă',
            'titlu' => 'Răspunde la 4 întrebări, <em>vezi ce ți se potrivește.</em>',
            'intrebari' => [
                ['q' => '1. Adormi mai greu de 30 minute în mod regulat?', 'default' => 0],
                ['q' => '2. Te trezești noaptea și nu mai poți adormi?', 'default' => 1],
                ['q' => '3. Simți tensiune în umeri sau maxilar mai mereu?', 'default' => 0],
                ['q' => '4. Folosești alcool, telefon sau Netflix ca să te relaxezi?', 'default' => 0],
            ],
            'rezultat_strong' => 'Răspunsurile sugerează că sistemul nervos e în hiperactivitate.',
            'rezultat_text' => 'Începe cu magneziu seara, ecran stins cu 30 min înainte de somn și adaptogeni în 4–6 săptămâni.',
        ],
        'medic' => [
            'titlu' => 'Când anxietatea nu mai e <em>doar stres.</em>',
            'lede' => 'Dacă oricare dintre semnele de mai jos apare, prioritar este să cauți sprijin specializat, nu să încerci suplimentele singur.',
            'semnale' => [
                'Atacuri de panică cu palpitații și senzație de moarte iminentă.',
                'Gânduri intrusive persistente sau ritualuri compulsive.',
                'Insomnie severă zilnică mai mult de 3 săptămâni.',
                'Sentimente de deznădejde sau gânduri de auto-rănire.',
                'Depresie persistentă sau lipsa totală de interes pentru activități.',
            ],
            'foot' => 'Anxietatea și insomnia clinică merită ajutor specializat. Suplimentele sunt sprijin colateral, nu înlocuiesc terapia sau medicul.',
        ],
        'produse' => [
            'eyebrow' => 'Dacă ai încercat schimbări de obicei și nu ajung',
            'titlu' => 'Trei opțiuni, <em>în ordine logică.</em>',
            'intro' => 'Începe cu prima. Dacă după 4–6 săptămâni nu vezi schimbare, treci la următoarea.',
            'items' => [
                ['slug' => 'pachet-energie', 'nume' => 'Pachet Energie', 'pret' => '306 lei', 'opt' => 'Opțiune 01', 'category' => 'Somn · nervos', 'why' => 'Multivitamine și adaptogeni vegan, 120 zile. Pentru sistem nervos suprasolicitat, dependență de cafea, oboseală după-amiaza, somn fragmentat.', 'cta' => 'Vezi pachetul', 'cta_class' => 'btn-terra'],
                ['slug' => 'black-seed-elixir', 'nume' => 'Black Seed Elixir', 'pret' => '184 lei', 'opt' => 'Opțiune 02', 'category' => 'Metabolic · echilibru', 'why' => 'Ulei chimen negru egiptean și vitamina E, 240 capsule vegane, 120 doze. Pentru echilibru metabolic care susține și sistemul nervos prin glicemie stabilă.', 'cta' => 'Vezi produsul', 'cta_class' => 'btn-secondary-g'],
                ['slug' => 'pachet-complex-sanatate', 'nume' => 'Pachet Complex Sănătate 40+', 'pret' => '499 lei', 'opt' => 'Opțiune 03', 'category' => 'Complet · 40+', 'why' => 'Energie, imunitate, protecție celulară, 120 zile. Pentru cei după 40 cu stres acumulat, somn nereparator și energie scăzută.', 'cta' => 'Vezi pachetul', 'cta_class' => 'btn-secondary-g'],
            ],
        ],
        'mituri' => [
            'eyebrow' => 'Câteva lucruri pe care le auzim des',
            'titlu' => 'Mituri despre stres și somn, <em>calm onest.</em>',
            'items' => [
                ['mit' => '„Un pahar de vin seara ajută la somn."', 'real' => 'Alcoolul te adoarme mai ușor, dar fragmentează somnul REM în a doua jumătate a nopții. Te trezești la 2–3 noaptea și nu mai dormi profund. Calitatea scade dramatic.'],
                ['mit' => '„Dacă nu pot dormi, mă uit la telefon până obosesc."', 'real' => 'Lumina albastră blochează melatonina. Creează un cerc: nu poți dormi, telefon, melatonină scăzută, mai puțin somn. Ridică-te din pat, citește o carte 15 min, apoi încearcă din nou.'],
                ['mit' => '„Adaptogenii sunt doar trend de wellness."', 'real' => 'Ashwagandha are studii clinice care arată reducerea cortizolului cu 14–28% în 8 săptămâni. Nu e magie, dar nici trend gol. Cere constanță și nu funcționează pentru toată lumea.'],
            ],
        ],
        'faq' => [
            'eyebrow' => 'Despre stres și somn',
            'titlu' => 'Răspunsuri <em>scurte.</em>',
            'items' => [
                ['q' => 'Când iau magneziul, dimineața sau seara?', 'a' => '<strong>Seara</strong>, cu 30–60 min înainte de somn. Magneziul bisglicinat se absoarbe bine și are efect calmant blând, fără a fi sedativ.'],
                ['q' => 'Ashwagandha mă face să fiu adormit(ă)?', 'a' => 'Nu, e adaptogen, nu sedativ. Reglează răspunsul la stres. Poți să o iei dimineața sau la prânz. Efectul vine în 4–6 săptămâni de constanță.'],
                ['q' => 'Pot lua melatonină în fiecare seară?', 'a' => 'Melatonina e bună ocazional pentru jet-lag sau insomnii punctuale. Pentru somnul cronic slab, e mai bine să corectezi cauza (cortizol, magneziu, lumină) decât să înlocuiești melatonina naturală.'],
                ['q' => 'Cât timp durează până văd diferență?', 'a' => 'Magneziul: 1–2 săptămâni. Adaptogenii: 4–6 săptămâni. Schimbările de obicei (ecran stins, carte înainte de somn): 7–14 zile.'],
            ],
        ],
    ],

    // =====================================================================
    // CURĂ DETOX
    // =====================================================================
    'cura-detox' => [
        'title' => 'Cură detox',
        'hero' => [
            'eyebrow' => 'După simptom',
            'titlu' => 'Cură detox. Ce înseamnă cu adevărat <em>și când chiar ai nevoie.</em>',
            'lede' => 'După sărbători, după un tratament cu antibiotice sau după o perioadă cu alcool și mese grele, corpul are nevoie de sprijin. Dar „detox" nu înseamnă curățare magică în 3 zile, înseamnă să dai ficatului, intestinului și rinichilor condițiile să își facă treaba.',
            'chips' => [
                '~70% caută detox post-sărbători',
                'Ficatul se regenerează în 4–8 săptămâni',
                'Nu există „toxine" de eliminat instant',
            ],
        ],
        'definitie' => [
            'eyebrow' => 'Definiție simplă',
            'titlu' => 'Detox-ul real e <em>muncă de fundal.</em>',
            'cells' => [
                ['titlu' => 'Ficatul filtrează', 'text' => 'Toate substanțele trec prin ficat. După alcool, medicamente sau exces alimentar, are nevoie de 4–8 săptămâni să își refacă enzimele.'],
                ['titlu' => 'Microbiomul se reface', 'text' => 'Antibioticele șterg și bacteriile bune. Refacerea cere 2–6 luni cu probiotice și fibre, nu 7 zile.'],
                ['titlu' => 'Rinichii și intestinul evacuează', 'text' => 'Apa, fibrele și mișcarea fac 80% din „detox". Suplimentul susține, nu înlocuiește.'],
            ],
        ],
        'semne' => [
            'eyebrow' => 'Când are sens o cură',
            'titlu' => 'Trei situații <em>când chiar are sens.</em>',
            'items' => [
                ['titlu' => 'După sărbători sau exces alimentar', 'desc' => 'Mese grele consecutive solicită ficatul și vezica biliară. 2–4 săptămâni de cură ușoară ajută digestia să revină la ritm.', 'ajuta' => 'Mese mai mici, plante amare (anghinare, armurariu), hidratare.'],
                ['titlu' => 'După un tratament cu antibiotice', 'desc' => 'Antibioticele șterg microbiomul intestinal. Refacerea cu probiotice și fibre prebiotice durează 2–3 luni.', 'ajuta' => 'Probiotice cu tulpini multiple, iaurt natur, fibre solubile.'],
                ['titlu' => 'După o perioadă cu alcool ocazional', 'desc' => 'Alcoolul taxează ficatul direct. Pauza și sprijinul cu silimarină și antioxidanți reduc inflamația în 3–6 săptămâni.', 'ajuta' => 'Pauză minimă 14 zile, armurariu (silimarină), vitamina E, somn 7–8h.'],
            ],
        ],
        'autotest' => [
            'eyebrow' => 'Verificare rapidă',
            'titlu' => 'Răspunde la 4 întrebări, <em>vezi ce ți se potrivește.</em>',
            'intrebari' => [
                ['q' => '1. Ai trecut recent printr-o perioadă de exces (sărbători, vacanță, mese repetate)?', 'default' => 0],
                ['q' => '2. Ai luat antibiotice în ultimele 3 luni?', 'default' => 1],
                ['q' => '3. Consumi alcool mai mult de 2–3 ori pe săptămână?', 'default' => 1],
                ['q' => '4. Te simți moleșit după mese grase, ai ten gălbui sau treziri la 2–3 noaptea?', 'default' => 0],
            ],
            'rezultat_strong' => 'Răspunsurile sugerează că o cură ușoară de 4–6 săptămâni',
            'rezultat_text' => 'cu sprijin pentru ficat și microbiom ar avea sens. Începe cu hidratare, mese regulate și un protocol cu silimarină + probiotice.',
        ],
        'medic' => [
            'titlu' => 'Când detox-ul nu mai e <em>doar oboseală post-exces.</em>',
            'lede' => 'Dacă oricare dintre semnele de mai jos apare, prioritar este să consulți un medic, nu un supliment.',
            'semnale' => [
                'Icter (galben la piele sau albul ochiului).',
                'Durere persistentă în partea dreaptă sub coaste.',
                'Urină foarte închisă la culoare mai mult de 2 zile.',
                'Greață și vomă fără explicație clară.',
                'Pierdere în greutate fără să-ți propui.',
            ],
            'foot' => 'Menționarea unui medic aici nu e clauză legală. Este pur și simplu cel mai bun sfat pe care ți-l putem da în aceste situații.',
        ],
        'produse' => [
            'eyebrow' => 'Dacă ai încercat schimbări de obicei și nu ajung',
            'titlu' => 'Trei opțiuni, <em>în ordine logică.</em>',
            'intro' => 'Începe cu prima. Dacă după 4 săptămâni nu vezi schimbare, treci la următoarea.',
            'items' => [
                ['slug' => 'pachet-confort-digestiv', 'nume' => 'Pachet Confort Digestiv', 'pret' => '283 lei', 'opt' => 'Opțiune 01', 'category' => 'Digestiv · probiotic', 'why' => 'Probiotice și Detox Ficat, 2 suplimente vegan, 120 zile. Pentru balonare după mese, recuperare post-antibiotice, digestie greoaie a grăsimilor.', 'cta' => 'Vezi pachetul', 'cta_class' => 'btn-terra'],
                ['slug' => 'pachet-detox', 'nume' => 'Pachet Detox', 'pret' => '283 lei', 'opt' => 'Opțiune 02', 'category' => 'Detox · ficat', 'why' => '2 suplimente vegan naturale, 120 zile. Pentru balonare cronică, oboseală fără cauză, ten gălbui, slăbit greoi după mese grase.', 'cta' => 'Vezi pachetul', 'cta_class' => 'btn-secondary-g'],
                ['slug' => 'pachet-detox-plus', 'nume' => 'Pachet Detox Plus', 'pret' => '457 lei', 'opt' => 'Opțiune 03', 'category' => 'Complet · 3 suplimente', 'why' => 'Curățare profundă ficat și sistem digestiv, 3 suplimente vegan, 120 zile. Pentru recuperare post-antibiotice intensă, halenă matinală, sensibilitate la cofeină și medicamente.', 'cta' => 'Vezi pachetul', 'cta_class' => 'btn-secondary-g'],
            ],
        ],
        'mituri' => [
            'eyebrow' => 'Câteva lucruri pe care le auzim des',
            'titlu' => 'Mituri despre detox, <em>calm onest.</em>',
            'items' => [
                ['mit' => '„Sucuri detox 3 zile curăță organismul."', 'real' => 'Ficatul și rinichii lucrează 24/7. Sucurile pot ajuta cu hidratarea și fibrele, dar nu „curăță" nimic suplimentar. Beneficiul real e că te oprești din mese grele câteva zile.'],
                ['mit' => '„Dacă transpir mult la saună, elimin toxine."', 'real' => 'Transpirația conține 99% apă și electroliți. Eliminarea reală a substanțelor o face ficatul prin bilă și rinichii prin urină. Sauna ajută circulația, nu detox-ul.'],
                ['mit' => '„Trebuie să fac detox de 2–3 ori pe an."', 'real' => 'Un corp sănătos cu mese echilibrate nu are nevoie de „cure" periodice. Detox-ul are sens situațional: după sărbători, antibiotice sau perioade de exces, nu pe calendar fix.'],
            ],
        ],
        'faq' => [
            'eyebrow' => 'Despre detox',
            'titlu' => 'Răspunsuri <em>scurte.</em>',
            'items' => [
                ['q' => 'Cât durează o cură de detox eficientă?', 'a' => 'Minimum <strong>4 săptămâni</strong> pentru a observa schimbări reale, ideal 8–12 săptămâni pentru regenerarea enzimelor hepatice și refacerea microbiomului. Sub 14 zile e mai degrabă o pauză alimentară decât o cură. Dacă nu vezi nimic după 6 săptămâni, probabil cauza nu e ficatul.'],
                ['q' => 'Pot să iau probiotice și silimarină în același timp?', 'a' => 'Da, sunt complementare: silimarina susține ficatul, probioticele refac microbiomul. Le poți lua în aceeași zi, ideal la mese diferite.'],
                ['q' => 'Detox-ul e potrivit în sarcină sau alăptare?', 'a' => 'Nu fără avizul medicului. În sarcină și alăptare multe plante (inclusiv silimarina) nu sunt recomandate. Prioritar e o alimentație echilibrată și hidratare, sub supraveghere medicală.'],
                ['q' => 'Ce mănânc în timpul curei?', 'a' => 'Legume colorate, fibre, proteine slabe, plante amare (anghinare, păpădie), multă apă. Reduce alcoolul, zahărul și mesele grase. Nu e nevoie de înfometare, ci de mese curate și regulate.'],
            ],
        ],
    ],

    // =====================================================================
    // PĂR FRAGIL & TEN STINS
    // =====================================================================
    'par-si-ten' => [
        'title' => 'Păr fragil & ten stins',
        'hero' => [
            'eyebrow' => 'După simptom',
            'titlu' => 'Părul, unghiile și tenul. <em>Oglinda</em> a ce mănânci, nu a ce aplici.',
            'lede' => '80% din schimbările vizibile pe păr, unghii și ten reflectă starea nutrițională internă. Proteine, fier, zinc, biotină, seleniu, colagen și omega-3 fac structura. Cremele și șampoanele au limitele lor, foliculul și unghia se construiesc din interior.',
            'chips' => [
                'Părul crește ~1 cm/lună',
                'Ciclul firului de păr: 2–7 ani',
                '90% din fir e cheratină',
            ],
        ],
        'definitie' => [
            'eyebrow' => 'Definiție simplă',
            'titlu' => 'Frumusețea exterioară e <em>nutriție interioară.</em>',
            'cells' => [
                ['titlu' => 'Biotină și zinc construiesc structura', 'text' => 'Biotina face cheratina (proteina firului de păr și unghiilor). Zincul susține creșterea celulelor de la baza foliculului. Deficitul dă păr fragil și unghii care se rup.'],
                ['titlu' => 'Fier și proteine hrănesc foliculul', 'text' => 'Foliculul de păr are nevoie de oxigen prin sânge. Fierul transportă oxigenul, proteinele dau aminoacizii pentru cheratină. Femeile la menstruație au mai des deficit subclinic.'],
                ['titlu' => 'Colagen și vitamina C dau elasticitate', 'text' => 'Colagenul e proteina structurală a pielii. După 25 de ani producția scade cu 1% pe an. Vitamina C e cofactor obligatoriu pentru sinteză. Împreună susțin elasticitatea și regenerarea.'],
            ],
        ],
        'semne' => [
            'eyebrow' => 'Ce observi de obicei',
            'titlu' => 'Trei semne <em>care apar primele.</em>',
            'items' => [
                ['titlu' => 'Părul cade vizibil mai mult', 'desc' => 'La spălat, pe pernă, pe pieptăn, mai mult ca înainte. Normal e 50–100 fire/zi. Peste 150 și constant, e semnal.', 'ajuta' => 'Biotină 5000 mcg, zinc 15 mg, fier (dacă analize confirmă deficit), proteine la fiecare masă.'],
                ['titlu' => 'Unghiile se rup, se exfoliază, cresc lent', 'desc' => 'Striuri verticale, vârfuri care se desfac în straturi, creștere mai puțin de 3 mm/lună. Indică deficit de proteine, biotină sau seleniu.', 'ajuta' => 'Biotină constantă 3–6 luni, ouă sau leguminoase zilnic, hidratare 2 litri/zi.'],
                ['titlu' => 'Ten stins, vindecare lentă', 'desc' => 'Lipsă de luminozitate, ten mat chiar și după somn bun, zgârieturi care se vindecă în 2 săptămâni în loc de 5 zile. Indică colagen scăzut sau deficit de vitamina C.', 'ajuta' => 'Colagen hidrolizat + vitamina C, omega-3, somn 7–8h, reducerea zahărului.'],
            ],
        ],
        'autotest' => [
            'eyebrow' => 'Verificare rapidă',
            'titlu' => 'Răspunde la 4 întrebări, <em>vezi ce ți se potrivește.</em>',
            'intrebari' => [
                ['q' => '1. Pierzi vizibil mai mult păr de peste 3 luni?', 'default' => 0],
                ['q' => '2. Unghiile se rup sau au striuri verticale?', 'default' => 0],
                ['q' => '3. Tenul pare obosit chiar și după somn bun?', 'default' => 1],
                ['q' => '4. Ai redus recent proteinele sau ai ținut dietă restrictivă?', 'default' => 0],
            ],
            'rezultat_strong' => 'Răspunsurile sugerează deficit de proteine, biotină sau colagen.',
            'rezultat_text' => 'Începe cu mese cu proteine la fiecare masă, biotină + zinc, colagen hidrolizat. Schimbările vizibile apar în 8–12 săptămâni.',
        ],
        'medic' => [
            'titlu' => 'Când nu mai e <em>doar estetic.</em>',
            'lede' => 'Dacă oricare dintre semnele de mai jos apare, prioritar este să consulți un medic, nu să încerci suplimentele singur.',
            'semnale' => [
                'Căderea masivă pe zone delimitate (posibil alopecia areata).',
                'Păr fragil și oboseală extremă și senzație constantă de frig (posibil tiroidă).',
                'Unghii foarte palide sau cu linii orizontale adânci (posibil anemie sau infecții).',
                'Erupții cutanate persistente, prurit fără cauză clară.',
                'Modificări bruște ale pielii sau ale aluniței.',
            ],
            'foot' => 'Acestea cer consult medical (dermatologic sau endocrin), nu suplimente. Pentru ele, suplimentele sunt sprijin colateral.',
        ],
        'produse' => [
            'eyebrow' => 'Dacă ai încercat schimbări de obicei și nu ajung',
            'titlu' => 'Trei opțiuni, <em>în ordine logică.</em>',
            'intro' => 'Începe cu prima. Dacă după 8–12 săptămâni nu vezi schimbare, treci la următoarea.',
            'items' => [
                ['slug' => 'pachet-frumusete', 'nume' => 'Pachet Frumusețe', 'pret' => '349 lei', 'opt' => 'Opțiune 01', 'category' => 'Frumusețe · păr unghii ten', 'why' => 'Combinație orientată pe păr, unghii și ten. Pentru cei care vor o singură cură coerentă, în loc de patru suplimente separate.', 'cta' => 'Vezi pachetul', 'cta_class' => 'btn-terra'],
                ['slug' => 'collagen-joint-berry-500-ml', 'nume' => 'Collagen Joint+ Berry', 'pret' => '184 lei', 'opt' => 'Opțiune 02', 'category' => 'Colagen · ten articulații', 'why' => 'Colagen hidrolizat (tipul I și III) cu vitamina C. Susține elasticitatea pielii și, în plus, sănătatea articulațiilor. Aromă naturală de fructe de pădure.', 'cta' => 'Vezi produsul', 'cta_class' => 'btn-secondary-g'],
                ['slug' => 'pachet-regenerare-celulara', 'nume' => 'Pachet Regenerare Celulară', 'pret' => '524 lei', 'opt' => 'Opțiune 03', 'category' => 'Profund · antioxidant', 'why' => 'Pentru cei care vor abordare profundă, antioxidantă, pe termen lung. Susține pielea, foliculul de păr și regenerarea celulară prin protecție împotriva stresului oxidativ.', 'cta' => 'Vezi pachetul', 'cta_class' => 'btn-secondary-g'],
            ],
        ],
        'mituri' => [
            'eyebrow' => 'Câteva lucruri pe care le auzim des',
            'titlu' => 'Mituri despre păr și ten, <em>calm onest.</em>',
            'items' => [
                ['mit' => '„Șamponul scump oprește căderea."', 'real' => 'Foliculul se hrănește din sânge, nu din șampon. Cauza căderii e internă în 80% din cazuri. Șamponul curăță scalpul, atât. Caută cauza în nutriție, somn, hormoni, stres.'],
                ['mit' => '„Colagenul din creme reface pielea."', 'real' => 'Molecula de colagen e prea mare ca să pătrundă pielea. Cremele cu colagen umectează stratul cornos, nu hrănesc dermul. Colagenul ingerat oferă aminoacizi pentru sinteza proprie.'],
                ['mit' => '„Biotina rezolvă orice problemă de păr."', 'real' => 'Doar dacă există deficit real. Excesul nu accelerează creșterea și poate falsifica analize tiroidiene (TSH, T4, troponină). Doza terapeutică e 5000 mcg/zi, nu mai mult.'],
            ],
        ],
        'faq' => [
            'eyebrow' => 'Despre păr, unghii și ten',
            'titlu' => 'Răspunsuri <em>scurte.</em>',
            'items' => [
                ['q' => 'Cât timp durează până văd diferență?', 'a' => 'Unghii: <strong>3–4 luni</strong> (cresc complet în 6 luni). Păr: <strong>3–6 luni</strong> pentru fire noi vizibile. Ten: <strong>6–8 săptămâni</strong> pentru luminozitate. Reține că schimbarea apare după ce ciclul biologic permite, nu mai repede.'],
                ['q' => 'Pot lua biotină și colagen împreună?', 'a' => 'Da, sunt complementare. Biotina susține structura cheratinei, colagenul oferă aminoacizii pentru piele și păr. Standard în protocoalele de păr-unghii-ten.'],
                ['q' => 'Colagenul marin vs bovin, care e mai bun?', 'a' => 'Ambele funcționează. Marin: absorbție ușor mai rapidă, conține predominant tipul I (piele). Bovin: tipuri I și III, mai accesibil ca preț. Verifică să fie hidrolizat (peptide), nu colagen nemodificat.'],
                ['q' => 'Trebuie să fac pauze între cure?', 'a' => 'Pentru colagen și biotină, nu sunt necesare pauze, sunt componente nutritive. Pentru pachete complexe cu plante (silimarină, antioxidanți), 1 lună pauză la fiecare 3 luni e o regulă bună.'],
            ],
        ],
    ],

];
