# 🚀 WEB.EDU ELITE v3.2 - Mark Lozeman
**Opleiding:** MBO Niveau 4 Software Development | **Vak:** Webprogrammeren
**Status:** Final Release (v3.2) | **Focus:** Serverless PHP & Edge Computing

---

## 🌟 Projectomschrijving
WEB.EDU is een geavanceerd educatief platform dat fungeert als een 'Knowledge Base' voor moderne webtechnologieën. Het project is niet alleen een blog, maar een demonstratie van een volledige **Stateless Cloud Architectuur**. Het scheidt data (JSON) strikt van logica (PHP) en presentatie (Bootstrap 5).

### Kernfunctionaliteiten:
* **Dynamic Slug-based Routing:** Alle verzoeken worden afgehandeld via `api/index.php` met een regex-gebaseerde routering.
* **Adminmode (RBAC):** Een 'Role-Based Access Control' systeem geactiveerd via de URL-parameter `?role=admin`.
* **Live Metrics Console:** Een interactief dashboard dat real-time statistieken berekent uit de JSON-payload.
* **Sanitized HTML Injection:** Veilige weergave van complexe educatieve content met 10 gespecialiseerde modules.

---

## 🛠️ Technische Architectuur & Stack
| Component | Technologie | Functie |
| :--- | :--- | :--- |
| **Backend** | PHP 8.3 (Serverless) | Business Logic & Data Processing |
| **Frontend** | Bootstrap 5.3 + Google Fonts | Responsive UI & Visual Hierarchy |
| **Data Layer** | JSON Flat-file | High-speed, NoSQL-style data storage |
| **Infrastructure** | Vercel Edge Network | Global CDN, SSL Termination & CI/CD |
| **Security** | TLS 1.3 & XSS Filtering | End-to-end encryptie en input validatie |

---

## 📦 Versiebeheer & CI/CD Workflow
Dit project volgt een professionele **DevOps workflow**. Elke commit aan de `main` branch triggert een automatische build en deployment naar de Vercel Edge-nodes.

### Deployment Commando's:
```bash
# 1. Stage wijzigingen
git add .

# 2. Commit met Semantic Versioning
git commit -m "feat: upgrade admin console naar v3.2 elite"

# 3. Release tagging
git tag -a v3.2 -m "Elite Production Release"

# 4. Cloud Deployment
git push origin main --tags
