# 🚀 WEB.EDU ELITE v2.1 - Mark Lozeman
**Opleiding:** MBO Niveau 4 Software Development | **Vak:** Webprogrammeren
**Focus:** Serverless PHP, JSON Data-architectuur & CI/CD Versiebeheer

---

## 🌟 Project Overzicht
WEB.EDU is een educatief platform ontworpen voor MBO-studenten om complexe web-concepten te leren. Het project is gebouwd met een **"Performance First"** instelling, waarbij gebruik is gemaakt van een lichtgewicht PHP-engine zonder zware database-overhead.

### Kernfuncties:
* **Dynamische Routing:** Alle verzoeken worden afgehandeld via `api/index.php` voor een Single Page Experience.
* **Adminmode (Role-Based Access):** Een verborgen beheerschil die geactiveerd wordt via de URL-parameter `?role=admin`.
* **Flat-file JSON Database:** Inhoud wordt beheerd via `data/content.json` voor maximale snelheid en eenvoudige back-ups.
* **HTML-Content Injection:** Ondersteuning voor rijke tekstopmaak (lijsten, strong tags) direct vanuit de data-bron.

---

## 🛠️ Technische Stack
| Component | Technologie | Functie |
| :--- | :--- | :--- |
| **Backend** | PHP 8.3 (Serverless) | Business logic & Content rendering |
| **Frontend** | Bootstrap 5.3 + Custom CSS | Responsive Design & UI |
| **Database** | JSON (Flat-file) | Persistente opslag van artikelen en bio |
| **Hosting** | Vercel Edge Network | Wereldwijde distributie & SSL |
| **Versiebeheer** | Git & GitHub | Source control & CI/CD |

---

## 📦 Versiebeheer & Deployment
Dit project maakt gebruik van een moderne **CI/CD pipeline** (Continuous Integration / Continuous Deployment). Elke wijziging wordt bijgehouden met Git-tags om mijlpalen te markeren.

### Workflow Commando's:
```bash
# 1. Wijzigingen toevoegen
git add .

# 2. Commit met duidelijke omschrijving
git commit -m "feat: implementeer adminmode versiebeheer UI"

# 3. Versie-tag aanmaken
git tag -a v2.1 -m "Release versie 2.1"

# 4. Push naar GitHub (triggert automatische Vercel build)
git push origin main --tags
