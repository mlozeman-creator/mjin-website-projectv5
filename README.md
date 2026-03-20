# 🎓 Educatief Webplatform & Technisch Dossier
**Project MBO Niveau 4 - Software Development**

Dit project is een geavanceerd, dynamisch blogplatform gebouwd met **PHP 8.x** en **Bootstrap 5**. Het dient als bewijsvoering voor het beheersen van moderne deployment-technieken, serverloze infrastructuren en probleemoplossend vermogen (troubleshooting) binnen een cloud-omgeving.

## 🔗 Live Project
Bekijk de website hier: **[PLAK HIER JE VERCEL URL]**

---

## 🛠️ Technische Specificaties
* **Engine:** Custom PHP routering in `api/index.php`.
* **Database:** JSON-gebaseerde flat-file database (`data/content.json`) voor laagdrempelig contentbeheer.
* **Frontend:** Mobile-first design middels Bootstrap 5 met een focus op UX/UI.
* **Hosting:** Serverless PHP-runtime op Vercel (`vercel-php@0.7.2`).
* **CI/CD:** Volledig geautomatiseerde pijplijn via GitHub Actions/Vercel Integratie.

## 🔍 Troubleshooting (Geleerde Lessen)
Tijdens de ontwikkeling zijn de volgende kritieke uitdagingen opgelost:
1.  **Deployment Errors:** Problemen met ongeldige eigenschappen in de serverconfiguratie opgelost door strikte scheiding van data en logica.
2.  **Runtime Versiebeheer:** Handmatige correctie van de PHP-builder naar versie `0.7.2` na het bereiken van de *End-of-Life* status van oudere Node.js omgevingen op Vercel.
3.  **Routing:** Implementatie van een dynamisch slugs-systeem om artikelen in te laden zonder fysieke HTML-bestanden per pagina.

## 📂 Projectstructuur
```text
├── api/
│   └── index.php       # Hoofdlogica, routering en weergave
├── data/
│   └── content.json    # Dynamische artikelen, auteurinfo en metadata
├── vercel.json         # Infrastructuur-configuratie (Runtime & Routes)
└── README.md           # Deze documentatie