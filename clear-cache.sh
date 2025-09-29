#!/bin/bash

# 🧱 Brick Framework Cache Management

echo "🧹 Brick Framework Cache Management"
echo "=================================="

# Cache-Verzeichnisse finden
echo "📁 Suche Cache-Verzeichnisse..."
CACHE_DIRS=$(find /tmp /var/folders -name "*brick*" -type d 2>/dev/null)

if [ -z "$CACHE_DIRS" ]; then
    echo "✅ Keine Cache-Verzeichnisse gefunden"
else
    echo "📂 Gefundene Cache-Verzeichnisse:"
    echo "$CACHE_DIRS"
    
    echo ""
    echo "🗑️ Lösche Cache-Verzeichnisse..."
    echo "$CACHE_DIRS" | xargs rm -rf
    echo "✅ Cache gelöscht!"
fi

echo ""
echo "🔧 Cache-Status nach Bereinigung:"
echo "===================================="

# View Engine Test
cd "$(dirname "$0")"
php debug_view.php 2>/dev/null | grep -E "(Cache|Templates|compiled)" || echo "❌ Debug-Script nicht verfügbar"

echo ""
echo "💡 Cache dauerhaft deaktivieren:"
echo "   - debug: true in HomeController setzen"
echo "   - oder Cache-Prüfung in View.php auskommentieren"