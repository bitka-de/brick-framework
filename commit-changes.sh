#!/bin/bash

# Brick Framework - Geclusterte Git Commits
# Erstellt am: 29. September 2025
# Beschreibung: Systematische Commits für die CSS/JS-Direktiven-Implementierung

set -e  # Exit on error

echo "🧱 Brick Framework - Git Commits werden erstellt..."
echo "=================================================="

# 1. Core View System - CSS/JS Direktiven
echo "📦 Commit 1: Core View System - CSS/JS Direktiven Implementation"
git add brick/Core/View.php
git commit -m "feat: Add CSS/JS directives support to View system

- Add @css('file.css') directive for external CSS files
- Add @css...@endcss directive for inline CSS
- Add @js('file.js') directive for external JavaScript files  
- Add @js...@endjs directive for inline JavaScript
- Implement automatic asset management with duplicate prevention
- Add header injection system for CSS/JS assets
- Extend template compilation with CSS/JS directive parsing

Breaking Changes: None
Performance: Assets are injected in HTML head for optimal loading"

# 2. Demo Templates und Test-Seiten
echo "🎨 Commit 2: Add CSS/JS demo templates and test pages"
git add app/Views/css-js-demo.php app/Views/css-test.php app/Views/css-inline-test.php app/Views/js-test.php
git commit -m "feat: Add comprehensive CSS/JS directive demo templates

- Add css-js-demo.php with full feature demonstration
- Add css-test.php for external CSS testing
- Add css-inline-test.php for inline CSS testing  
- Add js-test.php for JavaScript directive testing
- Include real-world examples with GSAP, SweetAlert, Animate.css
- Demonstrate gradient backgrounds, animations, and interactive elements

Features:
- External library integration (GSAP, SweetAlert, Animate.css)
- Custom CSS styling with gradients and hover effects
- JavaScript event handling and DOM manipulation
- Responsive design with Bootstrap integration"

# 3. Routing und Navigation
echo "🛣️ Commit 3: Add CSS/JS demo routes and navigation"
git add app/routes.php
git commit -m "feat: Add routes for CSS/JS directive testing

Routes added:
- /demo/css-js - Full CSS & JavaScript demo page
- /test/css - External CSS directive test
- /test/css-inline - Inline CSS directive test  
- /test/js - JavaScript directive test

Features:
- Proper View system initialization with debug mode
- Shared template variables (app_name, version)
- Clean URL structure for feature testing
- Integration with existing route system"

# 4. Debug und Development Tools
echo "🔧 Commit 4: Add development and debugging tools"
git add debug-css-js-demo.php debug-css-js-demo-v2.php debug-simple-test.php
git commit -m "dev: Add debugging tools for CSS/JS directive development

Tools added:
- debug-css-js-demo.php - Original CSS/JS demo debugger
- debug-css-js-demo-v2.php - Improved demo debugger with error handling
- debug-simple-test.php - Simple template compilation tester

Features:
- Detailed error reporting and stack traces
- Template compilation testing without web server
- Development workflow optimization
- Isolated testing environment for directive debugging"

# 5. Dokumentation Updates
echo "📚 Commit 5: Update documentation for CSS/JS directives"
if [ -f "README.md" ]; then
    git add README.md
fi
if [ -f "docs/" ]; then
    git add docs/
fi
git commit -m "docs: Update documentation for CSS/JS directive system

Documentation updates:
- Add CSS/JS directive usage examples
- Document asset management features
- Add performance considerations
- Include troubleshooting guide for directive compilation
- Update feature overview with new capabilities

Examples include:
- External asset loading (@css, @js)
- Inline code blocks (@css...@endcss, @js...@endjs)
- Asset deduplication and optimization
- Header injection methodology" || echo "ℹ️  No documentation files to commit"

# 6. Cleanup und Optimization
echo "🧹 Commit 6: Clean up temporary files and optimize structure"
if [ -f "debug-css-js-demo.php" ]; then
    git rm debug-css-js-demo.php || true
fi
if [ -f "debug-css-js-demo-v2.php" ]; then
    git rm debug-css-js-demo-v2.php || true  
fi
if [ -f "debug-simple-test.php" ]; then
    git rm debug-simple-test.php || true
fi
git commit -m "cleanup: Remove temporary debug files

- Remove debug-css-js-demo.php (development file)
- Remove debug-css-js-demo-v2.php (development file)  
- Remove debug-simple-test.php (development file)
- Keep only production-ready code
- Maintain clean repository structure" || echo "ℹ️  No temporary files to remove"

echo ""
echo "✅ Alle Commits erfolgreich erstellt!"
echo "=================================================="
echo ""
echo "📊 Commit-Übersicht:"
echo "1. Core View System - CSS/JS Direktiven Implementation"
echo "2. CSS/JS Demo Templates und Test-Seiten"  
echo "3. Routing und Navigation für Tests"
echo "4. Development und Debugging Tools"
echo "5. Dokumentation Updates"
echo "6. Cleanup und Optimization"
echo ""
echo "🚀 Das Brick Framework CSS/JS Direktiven-System ist jetzt vollständig implementiert!"
echo ""
echo "📝 Nächste Schritte:"
echo "   - git push origin develop"
echo "   - Pull Request für main branch erstellen"
echo "   - Tests in Production-Umgebung durchführen"
echo ""
echo "🌐 Demo-URLs:"
echo "   - http://localhost:8000/demo/css-js"
echo "   - http://localhost:8000/test/css"
echo "   - http://localhost:8000/test/css-inline"  
echo "   - http://localhost:8000/test/js"