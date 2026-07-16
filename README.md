🎶 Spitify 🎶

Digitální katalog hudby, jakožto školní ročníková práce, inspirovaný platformou Spotify.

🛠️ Použité technologie 🛠️

- **PHP 8.5.8** | Backendová logika aplikace a komunikace s databází
- **MySQL a MariaDB** | Relační databáze pro ukládání dat
- **PDO (PHP Data Objects)** | Bezpečné připojení k databázi
- **HTML5 & CSS3** | Struktura a moderní responzivní vzhled webu
- **JavaScript** | Interaktivní detaily

🚀 Rychlé zprovoznění lokálně

1.  Stáhněte si projekt k sobě do počítače
2.  Spuštěte si MySQL a PHP (Homebrew)

    **brew services start mysql**
    **brew services start php**

3.  Přejděte do složky **config/**, zkopírujte vzorový soubor **config.example.php** a pojmenujte kopii jako **config.php**.
4.  Otevřete nově vytvořený soubor **config.php** a upravte přihlašovací údaje podle vašeho lokálního prostředí.
5.  Vytvořte ve vašem databázovém nástroji prázdnou databázi s názvem **spitify**. Následně do ní importujte strukturu a data ze souboru **spitify.sql**.

    **mysql -u root spitify < databaze.sql**

6.  Přejděte do hlavní složky projektu, kde se nachází soubor **index.php** a spusťte vestavěný PHP server.

    **php -S 127.0.0.1:8000**

7.  Nyní je aplikace dostupná ve vašem prohlížeči na adrese: **127.0.0.1:8000** 🎉
