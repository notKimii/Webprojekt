<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Über uns – Cockpit Corner</title>
  
  <link rel="stylesheet" href="/Webprojekt/produkt.css">

  <style>
    /* 1. Hauptcontainer */
    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 40px 20px;
        text-align: center;
    }

    /* 2. Intro Text */
    .about-intro {
        max-width: 800px;
        margin: 0 auto 60px auto;
        line-height: 1.8;
        color: #555;
        font-size: 1.1rem;
    }

    /* 3. DAS GRID ANPASSEN -> JETZT FLEXBOX FÜR PERFEKTE MITTE */
    .product-grid {
        display: flex;              /* Flexbox statt Grid nutzen */
        justify-content: center;    /* Schiebt alle Karten in die horizontale Mitte */
        flex-wrap: wrap;            /* Erlaubt Umbruch auf kleinen Bildschirmen */
        gap: 40px;                  /* Abstand zwischen den Karten */
    }

    /* 4. Die einzelne Karte */
    .team-card {
        /* Wichtig: Feste Basisbreite, damit sie nicht zu breit oder zu schmal werden */
        flex: 0 1 350px;            
        
        padding: 40px 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05); /* Sanfter Schatten */
        border: 1px solid #f1f5f9;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        
        /* Inhalt in der Karte zentrieren */
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .team-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 30px rgba(0,0,0,0.1);
    }

    /* Bilder */
    .team-image {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
        margin-bottom: 25px;
        border: 4px solid white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    /* Texte */
    .team-card h3 {
        margin: 0 0 5px 0;
        font-size: 1.4rem;
        color: #1e293b;
    }

    .role {
        color: var(--accent-color, #3b82f6);
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
        display: block;
    }

    .bio {
        font-size: 0.95rem;
        color: #64748b;
        margin-bottom: 25px;
        line-height: 1.6;
    }

    /* Button */
    .contact-btn {
        margin-top: auto; /* Schiebt Button nach unten */
        padding: 10px 24px;
        background-color: #f8fafc;
        color: #334155;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }

    .contact-btn:hover {
        background-color: #334155;
        color: white;
        border-color: #334155;
    }
  </style>
</head>
<body>

  <?php include "../include/connectcon.php"; ?>
  <?php include "../include/headimport.php"; ?>

<main>
  <div class="container">
    <h1>Die Crew hinter Cockpit Corner ✈️</h1>
    
    <div class="about-intro">
        <p>Willkommen an Bord! Wir sind ein Team aus drei Informatik-Studenten, die dieses Projekt im Rahmen unseres Studiums entwickelt haben. Unsere Mission ist es, Piloten und Flugbegeisterten die beste Ausrüstung an einem Ort zu bieten.</p>
    </div>

    <div class="product-grid">
      
      <div class="team-card">
          <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix" alt="Florian" class="team-image">
          <h3>Florian Tittl</h3>
          <span class="role">Front/Backend & Desing/Style</span>
          <p class="bio">
            Sorgt für den perfekten Look. Sie gestaltet die Benutzeroberfläche so, dass sich jeder Pilot sofort zurechtfindet.
          </p>
          <a href="mailto:felix@example.com" class="contact-btn">✉️ Kontaktieren</a>
      </div>

      <div class="team-card">
          <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Julia" alt="Andre" class="team-image">
          <h3>Andre Reiff</h3>
          <span class="role">Front/Backend & Datenbank</span>
          <p class="bio">
            Der Architekt im Hintergrund. Er sorgt für sichere Datenbankverbindungen und blitzschnelle Abfragen im System.
          </p>
          <a href="mailto:julia@example.com" class="contact-btn">✉️ Kontaktieren</a>
      </div>

      <div class="team-card">
          <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Tim" alt="Tim" class="team-image">
          <h3>Kimberly apassa udongo reyes</h3>
          <span class="role">Front/Backend & Datenbank, Bananenzählen</span>
          <p class="bio">
            Verbindet Frontend mit Backend und Zählt alle Bananen
          </p>
          <a href="mailto:tim@example.com" class="contact-btn">✉️ Kontaktieren</a>
      </div>

    </div>

    <div style="margin-top: 80px; color: #94a3b8; font-size: 0.9rem;">
        &copy; <?php echo date("Y"); ?> Projektarbeit Webentwicklung
    </div>

  </div>
</main>

<?php include "../include/footimport.php"; ?>

</body>
</html>