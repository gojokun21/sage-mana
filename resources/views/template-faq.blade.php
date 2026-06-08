{{--
  Template Name: FAQ Template
  Redesign după mockup `preferinte/Pagina FAQ.html`.
  Toate selectoarele CSS sunt scoped sub `.faq-page` (vezi resources/css/faq.css,
  livrat prin faq-bundle.css din App\page_bundles()). Căutarea live e în
  resources/js/faq.js (lazy-load pe `.faq-page` din app.js).

  Întrebările folosesc <details>/<summary> native (acordeon fără JS).
  Conținutul e static (Romanian) — învelit în @verbatim ca să nu intre în
  conflict cu sintaxa Blade (ex: `@` din adrese email, blocurile JSON-LD).
--}}

@extends('layouts.app')

@section('content')
@verbatim
<div class="faq-page">

  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
      {"@type":"Question","name":"Cum aleg suplimentul potrivit pentru mine?","acceptedAnswer":{"@type":"Answer","text":"Avem un test scurt de 6 întrebări care propune produse în funcție de obiectivul tău. Apasă „Deschide testul” pentru a începe."}},
      {"@type":"Question","name":"Care e diferența între un sirop și o tinctură?","acceptedAnswer":{"@type":"Answer","text":"Siropul e suspensie cu zahăr sau miere, ideal pentru copii. Tinctura e extract hidroalcoolic 1:3, concentrație mai mare, recomandată adulților."}},
      {"@type":"Question","name":"Suplimentele Mâna Naturii sunt notificate la ANSVSA?","acceptedAnswer":{"@type":"Answer","text":"Da, fiecare produs are număr de notificare emis de Ministerul Agriculturii. Numărul apare pe etichetă și în pagina produsului."}},
      {"@type":"Question","name":"De ce folosiți extract hidroalcoolic 1:3 și nu pulberi?","acceptedAnswer":{"@type":"Answer","text":"Raportul 1:3 (o parte plantă uscată, trei părți solvent) păstrează mai bine principiile active termolabile față de pulberi. Alcoolul etilic 28–30% v/v funcționează și ca prezervant natural."}},
      {"@type":"Question","name":"Pot lua mai multe suplimente în același timp?","acceptedAnswer":{"@type":"Answer","text":"De regulă da, dacă nu sunt redundante. Pentru combinații personalizate, recomandăm să consulți medicul de familie."}},
      {"@type":"Question","name":"Suplimentele interacționează cu medicamente?","acceptedAnswer":{"@type":"Answer","text":"Da, unele plante pot interacționa. Exemplu concret: Sunătoarea (din Neuro BALANCE) interacționează cu antidepresive, anticoagulante și anticoncepționale. Citește eticheta și întreabă medicul."}},
      {"@type":"Question","name":"Pot lua suplimente în sarcină sau alăptare?","acceptedAnswer":{"@type":"Answer","text":"Nu recomandăm suplimentele noastre în sarcină, alăptare sau la copii sub 12 ani, decât cu acordul medicului."}},
      {"@type":"Question","name":"De la ce vârstă se pot administra copiilor?","acceptedAnswer":{"@type":"Answer","text":"Siropurile pentru copii sunt formulate de la 3 ani. Tincturile cu alcool nu se administrează copiilor."}},
      {"@type":"Question","name":"Ce fac dacă apare o reacție adversă?","acceptedAnswer":{"@type":"Answer","text":"Oprește administrarea, contactează medicul. Ne anunți și pe noi la suport@mananaturii.ro pentru a urmări siguranța lotului."}},
      {"@type":"Question","name":"Cât alcool conțin tincturile?","acceptedAnswer":{"@type":"Answer","text":"Concentrație alcoolică 28–30% v/v, solvent alcool etilic. Doza zilnică recomandată (25–30 picături de 3 ori pe zi) aduce aproximativ 0,6–0,9 ml alcool pe zi."}},
      {"@type":"Question","name":"Cât durează livrarea?","acceptedAnswer":{"@type":"Answer","text":"1–3 zile lucrătoare prin curier, în toată țara."}},
      {"@type":"Question","name":"Care e costul transportului?","acceptedAnswer":{"@type":"Answer","text":"19,90 lei standard, gratuit la comenzi peste 250 lei."}},
      {"@type":"Question","name":"Pot plăti ramburs?","acceptedAnswer":{"@type":"Answer","text":"Da, ramburs la curier. Recomandăm însă plata online (mai rapidă, fără taxe suplimentare)."}},
      {"@type":"Question","name":"Ce procesatori de plată folosiți?","acceptedAnswer":{"@type":"Answer","text":"Stripe (card Visa, Mastercard), Apple Pay, Google Pay, ramburs la curier și transfer bancar (ordin de plată)."}},
      {"@type":"Question","name":"Pot modifica comanda după ce am plasat-o?","acceptedAnswer":{"@type":"Answer","text":"Da, dacă ne contactezi pe WhatsApp (+40 749 492 794) înainte ca pachetul să fie predat curierului (de regulă în 2–4 ore lucrătoare)."}},
      {"@type":"Question","name":"Pot returna un supliment desigilat?","acceptedAnswer":{"@type":"Answer","text":"Nu, conform OUG 34/2014 art. 16 lit. e, produsele sigilate care nu pot fi returnate din motive de igienă sau protecția sănătății sunt exceptate de la dreptul de retragere odată desigilate. Returul e posibil doar pentru produse sigilate."}},
      {"@type":"Question","name":"Cât timp am la dispoziție să returnez?","acceptedAnswer":{"@type":"Answer","text":"14 zile calendaristice de la primirea coletului, conform legii."}},
      {"@type":"Question","name":"Cine plătește transportul de retur?","acceptedAnswer":{"@type":"Answer","text":"Clientul plătește transportul, exceptând cazurile când produsul e defect sau livrat greșit (atunci suportăm noi)."}},
      {"@type":"Question","name":"Când primesc banii înapoi?","acceptedAnswer":{"@type":"Answer","text":"În maxim 14 zile de la primirea coletului returnat, pe aceeași metodă de plată."}},
      {"@type":"Question","name":"Ce fac dacă produsul a sosit deteriorat sau cu termen scurt?","acceptedAnswer":{"@type":"Answer","text":"Trimite-ne 2–3 poze pe WhatsApp sau email în 48 ore de la primire. Înlocuim sau rambursăm integral, transport inclus."}},
      {"@type":"Question","name":"Cum funcționează pachetele economice?","acceptedAnswer":{"@type":"Answer","text":"Cumperi 3–4 produse complementare la un preț mai mic decât suma individuală. Economia reală e afișată transparent în lei și procent."}},
      {"@type":"Question","name":"Pot anula un pachet după prima comandă?","acceptedAnswer":{"@type":"Answer","text":"Pachetele sunt one-time, nu sunt abonamente. Nu ai nimic de anulat."}},
      {"@type":"Question","name":"Există abonament lunar?","acceptedAnswer":{"@type":"Answer","text":"Nu. Am ales să nu folosim abonamente automate. Plătești doar când hotărăști tu."}},
      {"@type":"Question","name":"Ce includ pachetele Bronze, Silver, Gold?","acceptedAnswer":{"@type":"Answer","text":"Bronze (1–2 produse, sub 200 lei). Silver (3 produse, 200–400 lei). Gold (4+ produse, peste 400 lei). Detalii pe Pagina Pachete."}},
      {"@type":"Question","name":"Pot personaliza un pachet?","acceptedAnswer":{"@type":"Answer","text":"Da, prin testul interactiv sau scriindu-ne pe WhatsApp."}},
      {"@type":"Question","name":"Trebuie să-mi fac cont pentru a comanda?","acceptedAnswer":{"@type":"Answer","text":"Nu, comanda ca invitat e disponibilă. Contul ajută doar dacă vrei istoric, facturi, adrese salvate."}},
      {"@type":"Question","name":"Cum verific statusul comenzii?","acceptedAnswer":{"@type":"Answer","text":"Email automat la fiecare schimbare de status (confirmat, expediat, livrat) plus link AWB."}},
      {"@type":"Question","name":"Cum descarc factura?","acceptedAnswer":{"@type":"Answer","text":"Din emailul de confirmare sau din contul tău, secțiunea Comenzile mele."}},
      {"@type":"Question","name":"Am uitat parola, ce fac?","acceptedAnswer":{"@type":"Answer","text":"Click pe „Resetare parolă” la login. Primești link pe email în câteva minute."}},
      {"@type":"Question","name":"Cum șterg contul?","acceptedAnswer":{"@type":"Answer","text":"Scrie-ne la suport@mananaturii.ro din adresa contului. Îți confirmăm ștergerea în 7 zile (GDPR)."}},
      {"@type":"Question","name":"Cine produce suplimentele Mâna Naturii?","acceptedAnswer":{"@type":"Answer","text":"Producător Vivens Genetica, partener cu peste 15 ani experiență în suplimente naturale (vezi Pagina Despre)."}},
      {"@type":"Question","name":"Plantele sunt din România?","acceptedAnswer":{"@type":"Answer","text":"Majoritatea, din culturi controlate și recoltări din flora spontană. Câteva ingrediente exotice (curcuma, nuca neagră) sunt import certificat."}},
      {"@type":"Question","name":"Folosiți coloranți, conservanți, arome artificiale?","acceptedAnswer":{"@type":"Answer","text":"Nu. Tincturile folosesc doar plante și alcool etilic. Siropurile folosesc miere sau zahăr din trestie."}},
      {"@type":"Question","name":"Aveți teste de laborator pe loturi?","acceptedAnswer":{"@type":"Answer","text":"Da, fiecare lot are buletin de analiză (microbiologic, metale grele, identitate plantă). Disponibil la cerere."}},
      {"@type":"Question","name":"De ce nu apar reduceri de Black Friday?","acceptedAnswer":{"@type":"Answer","text":"Prețul nostru e calculat onest tot anul. Nu credem în reduceri artificiale de 50% care ascund prețuri umflate în rest."}},
      {"@type":"Question","name":"Cum vă contactez rapid?","acceptedAnswer":{"@type":"Answer","text":"WhatsApp +40 749 492 794 (răspuns sub 1 oră în program). Email suport@mananaturii.ro (sub 24 ore lucrătoare)."}},
      {"@type":"Question","name":"Aveți consiliere medicală?","acceptedAnswer":{"@type":"Answer","text":"Nu suntem medici. Pentru sfat medical, consultă medicul de familie. Putem răspunde la întrebări despre compoziție, mod de utilizare, ingrediente."}},
      {"@type":"Question","name":"Care e programul de lucru?","acceptedAnswer":{"@type":"Answer","text":"Luni–Vineri 09:00–18:00, Sâmbătă 10:00–14:00, Duminică închis."}},
      {"@type":"Question","name":"Pot vizita un sediu fizic?","acceptedAnswer":{"@type":"Answer","text":"Activăm exclusiv online. Pentru sesiuni dedicate, programare prealabilă pe email."}},
      {"@type":"Question","name":"Cât durează răspunsul pe email?","acceptedAnswer":{"@type":"Answer","text":"Sub 24 ore lucrătoare. WhatsApp e mai rapid pentru întrebări urgente."}}
    ]
  }
  </script>

  <nav class="breadcrumb" aria-label="Breadcrumb">
    <a href="/">Acasă</a><span class="sep" aria-hidden="true">›</span><span class="here">FAQ</span>
  </nav>

  <!-- HERO -->
  <section class="faq-hero">
    <div class="faq-hero-inner">
      <div class="eyebrow">Ajutor și suport</div>
      <h1>Întrebări <em>frecvente.</em></h1>
      <p class="sub">Răspunsuri clare la ce ne întreabă clienții cel mai des.</p>

      <div class="search-box" id="searchBox">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input id="faqSearch" type="search" placeholder="Caută în 40 de întrebări (ex: retur, livrare, sarcină, alcool...)" />
        <button class="clear-btn" id="clearBtn" type="button" aria-label="Șterge căutarea">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
      </div>
      <p class="search-info" id="searchInfo"><strong id="matchCount">0</strong> rezultate găsite</p>
    </div>
  </section>

  <!-- NAV CHIPS -->
  <nav class="nav-chips" aria-label="Navigare rapidă FAQ">
    <div class="nav-chips-inner">
      <a class="nav-chip" href="#despre-produse"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>Despre produse <span class="count">(5)</span></a>
      <a class="nav-chip" href="#siguranta"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>Siguranță <span class="count">(5)</span></a>
      <a class="nav-chip" href="#comanda-livrare"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="8" width="15" height="10" rx="1"/><path d="M18 11h2l3 3v4h-5"/><circle cx="7" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/></svg>Comandă, livrare <span class="count">(5)</span></a>
      <a class="nav-chip" href="#retur-garantie"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>Retur, garanție <span class="count">(5)</span></a>
      <a class="nav-chip" href="#pachete"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h7v7H3zM14 3h7v7h-7zM3 14h7v7H3zM14 14h7v7h-7z"/></svg>Pachete <span class="count">(5)</span></a>
      <a class="nav-chip" href="#cont"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Cont <span class="count">(5)</span></a>
      <a class="nav-chip" href="#brand-calitate"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L4 6v6c0 5.55 3.84 10.74 8 12 4.16-1.26 8-6.45 8-12V6l-8-4z"/></svg>Brand, calitate <span class="count">(5)</span></a>
      <a class="nav-chip" href="#contact"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.37 1.9.72 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.35 1.85.59 2.81.72A2 2 0 0 1 22 16.92z"/></svg>Contact <span class="count">(5)</span></a>
    </div>
  </nav>

  <!-- DESPRE PRODUSE -->
  <section class="faq-section" id="despre-produse">
    <div class="faq-section-inner">
      <div class="faq-section-head">
        <div class="ico-wrap"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg></div>
        <div class="copy">
          <span class="eyebrow">Secțiunea 1</span>
          <h2>Despre <em>produse.</em></h2>
        </div>
        <span class="count-badge">5 întrebări</span>
      </div>
      <div class="faq-list">
        <details class="faq-item" data-q="cum aleg suplimentul potrivit pentru mine">
          <summary><span class="q-text">Cum aleg suplimentul potrivit pentru mine?</span><span class="toggle">+</span></summary>
          <div class="answer">Avem un test scurt de 6 întrebări care propune produse în funcție de obiectivul tău. <a class="inline-cta" href="/suplimente-alimentare/">Vezi catalogul <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 6 6 6-6 6"/></svg></a></div>
        </details>
        <details class="faq-item" data-q="care e diferenta intre un sirop si o tinctura">
          <summary><span class="q-text">Care e diferența între un sirop și o tinctură?</span><span class="toggle">+</span></summary>
          <div class="answer"><strong>Siropul</strong> e suspensie cu zahăr sau miere, ideal pentru copii. <strong>Tinctura</strong> e extract hidroalcoolic 1:3, concentrație mai mare, recomandată adulților.</div>
        </details>
        <details class="faq-item" data-q="suplimentele mana naturii sunt notificate la ansvsa">
          <summary><span class="q-text">Suplimentele Mâna Naturii sunt notificate la ANSVSA?</span><span class="toggle">+</span></summary>
          <div class="answer">Da, fiecare produs are <strong>număr de notificare</strong> emis de Ministerul Agriculturii. Numărul apare pe etichetă și în pagina produsului.</div>
        </details>
        <details class="faq-item" data-q="de ce folositi extract hidroalcoolic si nu pulberi">
          <summary><span class="q-text">De ce folosiți extract hidroalcoolic 1:3 și nu pulberi?</span><span class="toggle">+</span></summary>
          <div class="answer">Raportul <strong>1:3</strong> (o parte plantă uscată, trei părți solvent) păstrează mai bine principiile active termolabile față de pulberi. Alcoolul etilic 28–30% v/v funcționează și ca prezervant natural.</div>
        </details>
        <details class="faq-item" data-q="pot lua mai multe suplimente in acelasi timp">
          <summary><span class="q-text">Pot lua mai multe suplimente în același timp?</span><span class="toggle">+</span></summary>
          <div class="answer">De regulă da, dacă nu sunt redundante. Pentru combinații personalizate, recomandăm să consulți medicul de familie.</div>
        </details>
      </div>
    </div>
  </section>

  <!-- SIGURANTA -->
  <section class="faq-section alt" id="siguranta">
    <div class="faq-section-inner">
      <div class="faq-section-head">
        <div class="ico-wrap"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
        <div class="copy">
          <span class="eyebrow">Secțiunea 2</span>
          <h2>Siguranță, <em>contraindicații.</em></h2>
        </div>
        <span class="count-badge">5 întrebări</span>
      </div>
      <div class="faq-list">
        <details class="faq-item" data-q="suplimentele interactioneaza cu medicamente">
          <summary><span class="q-text">Suplimentele interacționează cu medicamente?</span><span class="toggle">+</span></summary>
          <div class="answer">Da, unele plante pot interacționa. Exemplu concret: <strong>Sunătoarea</strong> (din Neuro BALANCE) interacționează cu antidepresive, anticoagulante și anticoncepționale. Citește eticheta și întreabă medicul.</div>
        </details>
        <details class="faq-item" data-q="pot lua suplimente in sarcina sau alaptare">
          <summary><span class="q-text">Pot lua suplimente în sarcină sau alăptare?</span><span class="toggle">+</span></summary>
          <div class="answer">Nu recomandăm suplimentele noastre în <strong>sarcină, alăptare sau la copii sub 12 ani</strong>, decât cu acordul medicului.</div>
        </details>
        <details class="faq-item" data-q="de la ce varsta se pot administra copiilor">
          <summary><span class="q-text">De la ce vârstă se pot administra copiilor?</span><span class="toggle">+</span></summary>
          <div class="answer">Siropurile pentru copii sunt formulate <strong>de la 3 ani</strong>. Tincturile cu alcool nu se administrează copiilor.</div>
        </details>
        <details class="faq-item" data-q="ce fac daca apare o reactie adversa">
          <summary><span class="q-text">Ce fac dacă apare o reacție adversă?</span><span class="toggle">+</span></summary>
          <div class="answer">Oprește administrarea, contactează medicul. Ne anunți și pe noi la <strong>suport@mananaturii.ro</strong> pentru a urmări siguranța lotului.</div>
        </details>
        <details class="faq-item" data-q="cat alcool contin tincturile">
          <summary><span class="q-text">Cât alcool conțin tincturile?</span><span class="toggle">+</span></summary>
          <div class="answer">Concentrație alcoolică <strong>28–30% v/v</strong>, solvent alcool etilic. Doza zilnică recomandată (25–30 picături de 3 ori pe zi) aduce aproximativ <strong>0,6–0,9 ml alcool pe zi</strong>.</div>
        </details>
      </div>
    </div>
  </section>

  <!-- COMANDA LIVRARE -->
  <section class="faq-section" id="comanda-livrare">
    <div class="faq-section-inner">
      <div class="faq-section-head">
        <div class="ico-wrap"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="8" width="15" height="10" rx="1"/><path d="M18 11h2l3 3v4h-5"/><circle cx="7" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/></svg></div>
        <div class="copy">
          <span class="eyebrow">Secțiunea 3</span>
          <h2>Comandă <em>și livrare.</em></h2>
        </div>
        <span class="count-badge">5 întrebări</span>
      </div>
      <div class="faq-list">
        <details class="faq-item" data-q="cat dureaza livrarea">
          <summary><span class="q-text">Cât durează livrarea?</span><span class="toggle">+</span></summary>
          <div class="answer"><strong>1–3 zile lucrătoare</strong> prin curier, în toată țara.</div>
        </details>
        <details class="faq-item" data-q="care e costul transportului">
          <summary><span class="q-text">Care e costul transportului?</span><span class="toggle">+</span></summary>
          <div class="answer"><strong>19,90 lei</strong> standard, <strong>gratuit la comenzi peste 250 lei</strong>.</div>
        </details>
        <details class="faq-item" data-q="pot plati ramburs">
          <summary><span class="q-text">Pot plăti ramburs?</span><span class="toggle">+</span></summary>
          <div class="answer">Da, ramburs la curier. Recomandăm însă plata online (mai rapidă, fără taxe suplimentare).</div>
        </details>
        <details class="faq-item" data-q="ce procesatori de plata folositi">
          <summary><span class="q-text">Ce procesatori de plată folosiți?</span><span class="toggle">+</span></summary>
          <div class="answer"><strong>Stripe</strong> (card Visa, Mastercard), <strong>Apple Pay</strong>, <strong>Google Pay</strong>, ramburs la curier și transfer bancar (ordin de plată).</div>
        </details>
        <details class="faq-item" data-q="pot modifica comanda dupa ce am plasat o">
          <summary><span class="q-text">Pot modifica comanda după ce am plasat-o?</span><span class="toggle">+</span></summary>
          <div class="answer">Da, dacă ne contactezi pe <strong>WhatsApp (+40 749 492 794)</strong> înainte ca pachetul să fie predat curierului (de regulă în 2–4 ore lucrătoare).</div>
        </details>
      </div>
    </div>
  </section>

  <!-- RETUR GARANTIE -->
  <section class="faq-section alt" id="retur-garantie">
    <div class="faq-section-inner">
      <div class="faq-section-head">
        <div class="ico-wrap"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg></div>
        <div class="copy">
          <span class="eyebrow">Secțiunea 4</span>
          <h2>Retur <em>și garanție.</em></h2>
        </div>
        <span class="count-badge">5 întrebări</span>
      </div>
      <div class="faq-list">
        <details class="faq-item" data-q="pot returna un supliment desigilat">
          <summary><span class="q-text">Pot returna un supliment desigilat?</span><span class="toggle">+</span></summary>
          <div class="answer"><strong>Nu</strong>, conform <strong>OUG 34/2014 art. 16 lit. e</strong>, produsele sigilate care nu pot fi returnate din motive de igienă sau protecția sănătății sunt exceptate de la dreptul de retragere odată desigilate. Returul e posibil doar pentru produse sigilate.</div>
        </details>
        <details class="faq-item" data-q="cat timp am la dispozitie sa returnez">
          <summary><span class="q-text">Cât timp am la dispoziție să returnez?</span><span class="toggle">+</span></summary>
          <div class="answer"><strong>14 zile calendaristice</strong> de la primirea coletului, conform legii.</div>
        </details>
        <details class="faq-item" data-q="cine plateste transportul de retur">
          <summary><span class="q-text">Cine plătește transportul de retur?</span><span class="toggle">+</span></summary>
          <div class="answer"><strong>Clientul plătește transportul</strong>, exceptând cazurile când produsul e defect sau livrat greșit (atunci suportăm noi).</div>
        </details>
        <details class="faq-item" data-q="cand primesc banii inapoi">
          <summary><span class="q-text">Când primesc banii înapoi?</span><span class="toggle">+</span></summary>
          <div class="answer">În maxim <strong>14 zile</strong> de la primirea coletului returnat, pe aceeași metodă de plată.</div>
        </details>
        <details class="faq-item" data-q="ce fac daca produsul a sosit deteriorat sau cu termen scurt">
          <summary><span class="q-text">Ce fac dacă produsul a sosit deteriorat sau cu termen scurt?</span><span class="toggle">+</span></summary>
          <div class="answer">Trimite-ne <strong>2–3 poze</strong> pe WhatsApp sau email în <strong>48 ore</strong> de la primire. Înlocuim sau rambursăm integral, transport inclus.</div>
        </details>
      </div>
    </div>
  </section>

  <!-- PACHETE -->
  <section class="faq-section" id="pachete">
    <div class="faq-section-inner">
      <div class="faq-section-head">
        <div class="ico-wrap"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 3h7v7H3zM14 3h7v7h-7zM3 14h7v7H3zM14 14h7v7h-7z"/></svg></div>
        <div class="copy">
          <span class="eyebrow">Secțiunea 5</span>
          <h2>Pachete <em>și combinații.</em></h2>
        </div>
        <span class="count-badge">5 întrebări</span>
      </div>
      <div class="faq-list">
        <details class="faq-item" data-q="cum functioneaza pachetele economice">
          <summary><span class="q-text">Cum funcționează pachetele economice?</span><span class="toggle">+</span></summary>
          <div class="answer">Cumperi <strong>3–4 produse complementare</strong> la un preț mai mic decât suma individuală. Economia reală e afișată transparent în lei și procent.</div>
        </details>
        <details class="faq-item" data-q="pot anula un pachet dupa prima comanda">
          <summary><span class="q-text">Pot anula un pachet după prima comandă?</span><span class="toggle">+</span></summary>
          <div class="answer">Pachetele sunt <strong>one-time</strong>, nu sunt abonamente. Nu ai nimic de anulat.</div>
        </details>
        <details class="faq-item" data-q="exista abonament lunar">
          <summary><span class="q-text">Există abonament lunar?</span><span class="toggle">+</span></summary>
          <div class="answer"><strong>Nu</strong>. Am ales să nu folosim abonamente automate. Plătești doar când hotărăști tu.</div>
        </details>
        <details class="faq-item" data-q="ce includ pachetele bronze silver gold">
          <summary><span class="q-text">Ce includ pachetele Bronze, Silver, Gold?</span><span class="toggle">+</span></summary>
          <div class="answer"><strong>Bronze</strong> (1–2 produse, sub 200 lei). <strong>Silver</strong> (3 produse, 200–400 lei). <strong>Gold</strong> (4+ produse, peste 400 lei). <a class="inline-cta" href="/pachete/">Detalii pe Pagina Pachete <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 6 6 6-6 6"/></svg></a></div>
        </details>
        <details class="faq-item" data-q="pot personaliza un pachet">
          <summary><span class="q-text">Pot personaliza un pachet?</span><span class="toggle">+</span></summary>
          <div class="answer">Da, prin testul interactiv sau scriindu-ne pe WhatsApp.</div>
        </details>
      </div>
    </div>
  </section>

  <!-- CONT -->
  <section class="faq-section alt" id="cont">
    <div class="faq-section-inner">
      <div class="faq-section-head">
        <div class="ico-wrap"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
        <div class="copy">
          <span class="eyebrow">Secțiunea 6</span>
          <h2>Cont <em>și comenzi.</em></h2>
        </div>
        <span class="count-badge">5 întrebări</span>
      </div>
      <div class="faq-list">
        <details class="faq-item" data-q="trebuie sa mi fac cont pentru a comanda">
          <summary><span class="q-text">Trebuie să-mi fac cont pentru a comanda?</span><span class="toggle">+</span></summary>
          <div class="answer"><strong>Nu</strong>, comanda ca invitat e disponibilă. Contul ajută doar dacă vrei istoric, facturi, adrese salvate.</div>
        </details>
        <details class="faq-item" data-q="cum verific statusul comenzii">
          <summary><span class="q-text">Cum verific statusul comenzii?</span><span class="toggle">+</span></summary>
          <div class="answer">Email automat la fiecare schimbare de status (<strong>confirmat, expediat, livrat</strong>) plus link AWB.</div>
        </details>
        <details class="faq-item" data-q="cum descarc factura">
          <summary><span class="q-text">Cum descarc factura?</span><span class="toggle">+</span></summary>
          <div class="answer">Din emailul de confirmare sau din contul tău, secțiunea <strong>Comenzile mele</strong>.</div>
        </details>
        <details class="faq-item" data-q="am uitat parola ce fac">
          <summary><span class="q-text">Am uitat parola, ce fac?</span><span class="toggle">+</span></summary>
          <div class="answer">Click pe „Resetare parolă” la login. Primești link pe email în câteva minute.</div>
        </details>
        <details class="faq-item" data-q="cum sterg contul">
          <summary><span class="q-text">Cum șterg contul?</span><span class="toggle">+</span></summary>
          <div class="answer">Scrie-ne la <strong>suport@mananaturii.ro</strong> din adresa contului. Îți confirmăm ștergerea în <strong>7 zile</strong> (GDPR).</div>
        </details>
      </div>
    </div>
  </section>

  <!-- BRAND CALITATE -->
  <section class="faq-section" id="brand-calitate">
    <div class="faq-section-inner">
      <div class="faq-section-head">
        <div class="ico-wrap"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2L4 6v6c0 5.55 3.84 10.74 8 12 4.16-1.26 8-6.45 8-12V6l-8-4z"/></svg></div>
        <div class="copy">
          <span class="eyebrow">Secțiunea 7</span>
          <h2>Brand <em>și calitate.</em></h2>
        </div>
        <span class="count-badge">5 întrebări</span>
      </div>
      <div class="faq-list">
        <details class="faq-item" data-q="cine produce suplimentele mana naturii">
          <summary><span class="q-text">Cine produce suplimentele Mâna Naturii?</span><span class="toggle">+</span></summary>
          <div class="answer">Producător <strong>Vivens Genetica</strong>, partener cu peste 15 ani experiență în suplimente naturale. <a class="inline-cta" href="/despre-noi/">Vezi Pagina Despre <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 6 6 6-6 6"/></svg></a></div>
        </details>
        <details class="faq-item" data-q="plantele sunt din romania">
          <summary><span class="q-text">Plantele sunt din România?</span><span class="toggle">+</span></summary>
          <div class="answer">Majoritatea, din culturi controlate și recoltări din flora spontană. Câteva ingrediente exotice (<strong>curcuma, nuca neagră</strong>) sunt import certificat.</div>
        </details>
        <details class="faq-item" data-q="folositi coloranti conservanti arome artificiale">
          <summary><span class="q-text">Folosiți coloranți, conservanți, arome artificiale?</span><span class="toggle">+</span></summary>
          <div class="answer"><strong>Nu</strong>. Tincturile folosesc doar plante și alcool etilic. Siropurile folosesc miere sau zahăr din trestie.</div>
        </details>
        <details class="faq-item" data-q="aveti teste de laborator pe loturi">
          <summary><span class="q-text">Aveți teste de laborator pe loturi?</span><span class="toggle">+</span></summary>
          <div class="answer">Da, fiecare lot are <strong>buletin de analiză</strong> (microbiologic, metale grele, identitate plantă). Disponibil la cerere.</div>
        </details>
        <details class="faq-item" data-q="de ce nu apar reduceri de black friday">
          <summary><span class="q-text">De ce nu apar reduceri de Black Friday?</span><span class="toggle">+</span></summary>
          <div class="answer">Prețul nostru e calculat <strong>onest tot anul</strong>. Nu credem în reduceri artificiale de 50% care ascund prețuri umflate în rest.</div>
        </details>
      </div>
    </div>
  </section>

  <!-- CONTACT -->
  <section class="faq-section alt" id="contact">
    <div class="faq-section-inner">
      <div class="faq-section-head">
        <div class="ico-wrap"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.37 1.9.72 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.35 1.85.59 2.81.72A2 2 0 0 1 22 16.92z"/></svg></div>
        <div class="copy">
          <span class="eyebrow">Secțiunea 8</span>
          <h2>Contact <em>și suport.</em></h2>
        </div>
        <span class="count-badge">5 întrebări</span>
      </div>
      <div class="faq-list">
        <details class="faq-item" data-q="cum va contactez rapid">
          <summary><span class="q-text">Cum vă contactez rapid?</span><span class="toggle">+</span></summary>
          <div class="answer"><strong>WhatsApp +40 749 492 794</strong> (răspuns sub 1 oră în program). <strong>Email suport@mananaturii.ro</strong> (sub 24 ore lucrătoare).</div>
        </details>
        <details class="faq-item" data-q="aveti consiliere medicala">
          <summary><span class="q-text">Aveți consiliere medicală?</span><span class="toggle">+</span></summary>
          <div class="answer"><strong>Nu suntem medici</strong>. Pentru sfat medical, consultă medicul de familie. Putem răspunde la întrebări despre compoziție, mod de utilizare, ingrediente.</div>
        </details>
        <details class="faq-item" data-q="care e programul de lucru">
          <summary><span class="q-text">Care e programul de lucru?</span><span class="toggle">+</span></summary>
          <div class="answer"><strong>Luni–Vineri</strong> 09:00–18:00. <strong>Sâmbătă</strong> 10:00–14:00. <strong>Duminică</strong> închis.</div>
        </details>
        <details class="faq-item" data-q="pot vizita un sediu fizic">
          <summary><span class="q-text">Pot vizita un sediu fizic?</span><span class="toggle">+</span></summary>
          <div class="answer">Activăm exclusiv online. Pentru sesiuni dedicate, programare prealabilă pe email.</div>
        </details>
        <details class="faq-item" data-q="cat dureaza raspunsul pe email">
          <summary><span class="q-text">Cât durează răspunsul pe email?</span><span class="toggle">+</span></summary>
          <div class="answer">Sub <strong>24 ore lucrătoare</strong>. WhatsApp e mai rapid pentru întrebări urgente.</div>
        </details>
      </div>
    </div>
  </section>

  <!-- NO RESULTS -->
  <section class="no-results" id="noResults">
    <div class="no-results-inner">
      <div class="ico"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/><path d="M8 11h6"/></svg></div>
      <h3>Niciun rezultat <em>pentru căutarea ta.</em></h3>
      <p>Încearcă alte cuvinte cheie, sau <strong>scrie-ne direct</strong>. Răspundem în 24 ore lucrătoare.</p>
    </div>
  </section>

  <!-- CTA FINAL -->
  <section class="cta-final">
    <div class="cta-final-inner">
      <h2>Răspunsul tău <em>nu e aici?</em></h2>
      <p>Suntem oameni reali, răspundem personal. Cel mai rapid pe WhatsApp, sub 1 oră în program. <a href="/contact/">Vezi toate canalele de contact</a>.</p>
      <div class="cta-buttons">
        <a class="primary" href="https://wa.me/40749492794" target="_blank" rel="noopener">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.5 14.4c-.3-.2-1.8-.9-2.1-1s-.5-.2-.7.2c-.2.3-.8 1-1 1.2-.2.2-.4.2-.7.1-.3-.2-1.3-.5-2.4-1.5-.9-.8-1.5-1.8-1.7-2.1z"/></svg>
          WhatsApp +40 749 492 794
        </a>
        <a class="outline" href="mailto:suport@mananaturii.ro">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="m22 6-10 7L2 6"/></svg>
          Trimite email
        </a>
      </div>
    </div>
  </section>

</div>
@endverbatim
@endsection
