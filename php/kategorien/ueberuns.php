<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Über uns – Cockpit Corner</title>
  
  <link rel="stylesheet" href="/Webprojekt/produkt.css">

  <style>
    /* ===========================
       ABOUT PAGE - PASSEND ZUM MAIN DESIGN
       =========================== */

    /* Hero Section für About Page */
    .about-hero {
        position: relative;
        background: linear-gradient(135deg, var(--primary-color, #0f172a) 0%, var(--primary-light, #1e293b) 50%, #334155 100%);
        padding: 100px 0 80px;
        text-align: center;
        overflow: hidden;
    }

    .about-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: 
            radial-gradient(circle at 20% 30%, rgba(59, 130, 246, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(139, 92, 246, 0.15) 0%, transparent 50%);
        animation: heroBackground 20s ease-in-out infinite;
    }

    .about-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(rgba(59, 130, 246, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(59, 130, 246, 0.03) 1px, transparent 1px);
        background-size: 50px 50px;
        opacity: 0.5;
    }

    @keyframes heroBackground {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.8; transform: scale(1.1); }
    }

    .about-hero-content {
        position: relative;
        z-index: 2;
        max-width: 800px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .about-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.3);
        padding: 10px 20px;
        border-radius: 50px;
        backdrop-filter: blur(10px);
        margin-bottom: 24px;
        animation: fadeInDown 0.6s ease-out;
    }

    .about-badge span {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .about-hero h1 {
        color: white;
        font-size: clamp(2.25rem, 5vw, 3.5rem);
        font-weight: 800;
        line-height: 1.15;
        margin: 0 0 20px 0;
        letter-spacing: -0.02em;
        animation: fadeInUp 0.6s ease-out 0.1s both;
    }

    .about-hero-intro {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.15rem;
        line-height: 1.8;
        max-width: 650px;
        margin: 0 auto;
        animation: fadeInUp 0.6s ease-out 0.2s both;
    }

    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Main Content Area */
    .about-section {
        padding: 80px 0;
        background: var(--body-bg, #ffffff);
    }

    .about-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 24px;
    }

    .section-header {
        text-align: center;
        margin-bottom: 60px;
    }

    .section-header h2 {
        font-size: clamp(1.75rem, 4vw, 2.5rem);
        color: var(--primary-color, #0f172a);
        font-weight: 700;
        margin-bottom: 16px;
        letter-spacing: -0.015em;
    }

    .section-header p {
        color: var(--text-light, #64748b);
        font-size: 1.05rem;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.7;
    }

    /* Team Grid */
    .team-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 32px;
        max-width: 1100px;
        margin: 0 auto;
    }

    /* Team Card - Premium Style */
    .team-card {
        position: relative;
        background: white;
        border-radius: var(--border-radius-lg, 16px);
        padding: 40px 28px 32px;
        text-align: center;
        border: 1px solid var(--border-color, #e2e8f0);
        box-shadow: var(--shadow-md, 0 4px 6px -1px rgba(0, 0, 0, 0.1));
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    .team-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--accent-color, #3b82f6), #8b5cf6);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .team-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl, 0 20px 25px -5px rgba(0, 0, 0, 0.1));
        border-color: transparent;
    }

    .team-card:hover::before {
        opacity: 1;
    }

    /* Team Image */
    .team-image-wrapper {
        position: relative;
        width: 130px;
        height: 130px;
        margin: 0 auto 24px;
    }

    .team-image-wrapper::before {
        content: '';
        position: absolute;
        inset: -4px;
        background: linear-gradient(135deg, var(--accent-color, #3b82f6), #8b5cf6);
        border-radius: 50%;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .team-card:hover .team-image-wrapper::before {
        opacity: 1;
    }

    .team-image {
        position: relative;
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid white;
        box-shadow: var(--shadow-lg, 0 10px 15px -3px rgba(0, 0, 0, 0.1));
        background: var(--light-color, #f8fafc);
    }

    /* Team Info */
    .team-card h3 {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--primary-color, #0f172a);
        margin: 0 0 8px 0;
    }

    .team-role {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1));
        color: var(--accent-color, #3b82f6);
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 6px 14px;
        border-radius: 50px;
        margin-bottom: 16px;
    }

    .team-bio {
        font-size: 0.95rem;
        color: var(--text-light, #64748b);
        line-height: 1.7;
        margin-bottom: 24px;
    }

    /* Contact Button */
    .team-contact-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 24px;
        background: var(--light-color, #f8fafc);
        color: var(--text-color, #1e293b);
        text-decoration: none;
        border-radius: var(--border-radius, 12px);
        font-weight: 600;
        font-size: 0.9rem;
        border: 2px solid var(--border-color, #e2e8f0);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .team-contact-btn:hover {
        background: linear-gradient(135deg, var(--accent-color, #3b82f6), var(--accent-hover, #2563eb));
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md, 0 4px 6px -1px rgba(0, 0, 0, 0.1));
    }

    /* Skills/Stats Section */
    .team-skills {
        display: flex;
        justify-content: center;
        gap: 16px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color, #e2e8f0);
    }

    .skill-tag {
        font-size: 0.75rem;
        color: var(--text-light, #64748b);
        background: var(--light-color, #f8fafc);
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 500;
    }

    /* Mission Section */
    .mission-section {
        background: linear-gradient(135deg, var(--light-color, #f8fafc), #e0e7ff);
        padding: 80px 0;
        position: relative;
        overflow: hidden;
    }

    .mission-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 50% 50%, rgba(59, 130, 246, 0.08), transparent 60%);
    }

    .mission-content {
        position: relative;
        z-index: 1;
        max-width: 800px;
        margin: 0 auto;
        text-align: center;
        padding: 0 24px;
    }

    .mission-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--accent-color, #3b82f6), #8b5cf6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        font-size: 2.5rem;
        box-shadow: var(--shadow-lg, 0 10px 15px -3px rgba(0, 0, 0, 0.1));
    }

    .mission-content h2 {
        font-size: clamp(1.75rem, 4vw, 2.25rem);
        color: var(--primary-color, #0f172a);
        margin-bottom: 20px;
    }

    .mission-content p {
        color: var(--text-light, #64748b);
        font-size: 1.1rem;
        line-height: 1.8;
    }

    /* Footer Credit */
    .about-footer {
        text-align: center;
        padding: 40px 0;
        color: var(--text-light, #94a3b8);
        font-size: 0.9rem;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .team-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }
        
        .about-hero {
            padding: 80px 0 60px;
        }
    }

    @media (max-width: 768px) {
        .team-grid {
            grid-template-columns: 1fr;
            max-width: 400px;
        }
        
        .about-hero h1 {
            font-size: 2rem;
        }
        
        .about-section {
            padding: 60px 0;
        }
        
        .mission-section {
            padding: 60px 0;
        }
    }

    @media (max-width: 480px) {
        .team-card {
            padding: 32px 20px 28px;
        }
        
        .team-image-wrapper {
            width: 110px;
            height: 110px;
        }
        
        .team-skills {
            flex-wrap: wrap;
        }
    }
  </style>
</head>
<body>

  <?php include "../include/connectcon.php"; ?>
  <?php include "../include/headimport.php"; ?>

<main>
    <!-- Hero Section -->
    <section class="about-hero">
        <div class="about-hero-content">
            <div class="about-badge">
                <span>✈️ Unser Team</span>
            </div>
            <h1>Die Crew hinter Cockpit Corner</h1>
            <p class="about-hero-intro">
                Willkommen an Bord! Wir sind ein Team aus drei Informatik-Studenten, die dieses Projekt im Rahmen unseres Studiums entwickelt haben. Unsere Mission ist es, Piloten und Flugbegeisterten die beste Ausrüstung an einem Ort zu bieten.
            </p>
        </div>
    </section>

    <!-- Team Section -->
    <section class="about-section">
        <div class="about-container">
            <div class="section-header">
                <h2>Lerne das Team kennen</h2>
                <p>Drei Köpfe, eine Vision – wir bringen Leidenschaft für Luftfahrt und Technologie zusammen.</p>
            </div>

            <div class="team-grid">
                
                <!-- Team Member 1 -->
                <div class="team-card">
                    <div class="team-image-wrapper">
                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix" alt="Florian Tittl" class="team-image">
                    </div>
                    <h3>Florian Tittl</h3>
                    <span class="team-role">Frontend & Design</span>
                    <p class="team-bio">
                        Sorgt für den perfekten Look. Er gestaltet die Benutzeroberfläche so, dass sich jeder Pilot sofort zurechtfindet.
                    </p>
                    <a href="mailto:florian@example.com" class="team-contact-btn">
                        ✉️ Kontaktieren
                    </a>
                    <div class="team-skills">
                        <span class="skill-tag">HTML/CSS</span>
                        <span class="skill-tag">UI/UX</span>
                        <span class="skill-tag">PHP</span>
                    </div>
                </div>

                <!-- Team Member 2 -->
                <div class="team-card">
                    <div class="team-image-wrapper">
                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Julia" alt="Andre Reiff" class="team-image">
                    </div>
                    <h3>Andre Reiff</h3>
                    <span class="team-role">Backend & Datenbank</span>
                    <p class="team-bio">
                        Der Architekt im Hintergrund. Er sorgt für sichere Datenbankverbindungen und blitzschnelle Abfragen im System.
                    </p>
                    <a href="mailto:andre@example.com" class="team-contact-btn">
                        ✉️ Kontaktieren
                    </a>
                    <div class="team-skills">
                        <span class="skill-tag">MySQL</span>
                        <span class="skill-tag">PHP</span>
                        <span class="skill-tag">API</span>
                    </div>
                </div>

                <!-- Team Member 3 -->
                <div class="team-card">
                    <div class="team-image-wrapper">
                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Tim" alt="Kimberly" class="team-image">
                    </div>
                    <h3>Kimberly Apassa Udongo Reyes</h3>
                    <span class="team-role">Fullstack & QA</span>
                    <p class="team-bio">
                        Verbindet Frontend mit Backend und sorgt dafür, dass alles reibungslos zusammenarbeitet.
                    </p>
                    <a href="mailto:kimberly@example.com" class="team-contact-btn">
                        ✉️ Kontaktieren
                    </a>
                    <div class="team-skills">
                        <span class="skill-tag">JavaScript</span>
                        <span class="skill-tag">Testing</span>
                        <span class="skill-tag">Git</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="mission-section">
        <div class="mission-content">
            <div class="mission-icon">🎯</div>
            <h2>Unsere Mission</h2>
            <p>
                Als Informatik-Studenten kombinieren wir technisches Know-how mit einer Leidenschaft für die Luftfahrt. 
                Unser Ziel ist es, einen modernen Online-Shop zu entwickeln, der Piloten und Flugbegeisterten 
                eine erstklassige Einkaufserfahrung bietet – von der Produktsuche bis zum Checkout.
            </p>
        </div>
    </section>

    <!-- Footer Credit -->
    <div class="about-footer">
        &copy; <?php echo date("Y"); ?> Projektarbeit Webentwicklung – Mit ❤️ entwickelt
    </div>
</main>

<?php include "../include/footimport.php"; ?>

</body>
</html>