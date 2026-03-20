# 🚀 WEB.EDU: Modern Webonderwijs & Vibe Coding
**Project MBO Niveau 4 - Software Development**

Dit project is een dynamisch educatief platform dat laat zien hoe moderne webontwikkeling werkt. De focus ligt op de synergie tussen **PHP**, **Cloud-hosting (Vercel)** en de opkomende trend van **Vibe Coding**.

## 🔗 Live Website
Bekijk het resultaat hier: **https://mijn-website-projectv5.vercel.app/**

---

## 💡 Wat is Vibe Coding?
In dit project is gebruikgemaakt van **Vibe Coding**. Dit houdt in dat ik als ontwikkelaar de regie voer over de architectuur, de "vibe" (gebruikerservaring) en de logica, terwijl ik AI-gestuurde tools inzet om razendsnel complexe syntax en server-configuraties op te lossen. 

* **Toegepast op dit project:** Het oplossen van de Vercel PHP-runtime fouten en het bouwen van de dynamische routing is gedaan door nauwe samenwerking met AI, waardoor de focus bleef liggen op het eindproduct in plaats van op kleine typefouten.



---

## 🛠️ De Technische Stack
* **Taal:** PHP 8.x (Server-side rendering).
* **Frontend:** Bootstrap 5 met een custom "Plus Jakarta Sans" interface.
* **Database:** JSON flat-file database (`data/content.json`).
* **Hosting:** Vercel (Serverless infrastructuur).
* **Automatisering:** CI/CD koppeling tussen GitHub en Vercel.

---

## 🔍 Logboek: Problemen & Oplossingen (Troubleshooting)
Tijdens de ontwikkeling zijn de volgende obstakels overwonnen:

1.  **Configuratie-fout:** Vercel weigerde de build omdat data en instellingen in de war waren. Opgelost door `vercel.json` strikt technisch te maken.
2.  **Runtime Veroudering:** De standaard PHP-runtime werkte niet met de nieuwste Node.js versies. Opgelost door handmatig de runtime te updaten naar `vercel-php@0.7.2`.
3.  **Taal & Vibe:** De hele interface is omgebouwd van Engels naar Nederlands om beter aan te sluiten bij de doelgroep van de opdracht.



---

## 📂 Bestandsstructuur
```text
├── api/
│   └── index.php       # De 'motor' van de site (routering & logica)
├── data/
│   └── content.json    # De inhoud (artikelen, auteur, vibes)
├── vercel.json         # Server-instellingen voor de cloud
└── README.md           # Deze documentatie
