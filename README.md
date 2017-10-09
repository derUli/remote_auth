# remote_auth
Remote Auth for UliCMS

## Beschreibung des Moduls
Dieses Modul ermöglicht die Nutzung von externen Authentifizierungsmethoden zur Anmeldung an UliCMS.

Während der Entwicklung des Moduls wurde HTTP Basic Authentication genutzt, jedoch funktioniert remote_auth mit jedem Anmeldeverfahren, bei dem der Login des angemeldeten Benutzers in eine Umgebungsvariable geschrieben wird.
Das enthält Verfahren wie NTLM, Proxyserver mit Passwortschutz und Single Sign-On Lösungen wie den IBM® Tivoli® Access Manager

## Funktionsweise
Die Anmeldung wird entweder vom Webserver auf dem UliCMS läuft oder durch einen vorgeschalteten Proxy-Server durchgeführt. Dabei kann die Anmeldung entweder über einen Dialog des Browsers oder über ein Web-basiertes Anmeldeformular erfolgen.
Wenn die Anmeldung erfolgreich war, befindet sich der Name des angemeldeten Benutzers in einer Umgebungsvariable. Diese wird ausgelesen. Sofern der Benutzer noch nicht existiert, wird dieser im System angelegt. Anschließend wird der Benutzer eingeloggt.

## Installation
Diese Anleitung geht davon aus, dass bereits ein Authentifizierungsverfahren vollständig konfiguriert ist.
1. Wenn Sie PHP als Apache Modul betreiben können Sie diesen Schritt überspringen.
Wenn Sie den Apache Webserver nutzen und PHP per CGI oder FastCGI ausführen, fügen Sie folgende Zeile in die Datei .htaccess ein.

       RewriteRule .* - [E=REMOTE_USER:%{HTTP:Authorization},L]

2. Führen Sie bitte **bevor** sie remote_auth installieren die Konfiguration durch.
Kopieren Sie dafür das folgende Snippet in die Konfigurationsdatei cms-config.php ein und passen es es so wie gewünscht an.

    var $remote_auth_config = array (
    		"env_vars" => array (
    				"REMOTE_USER",
    				"REDIRECT_REMOTE_USER",
    				"HTTP_IV_USER"
    		),
    		"login_url" => "http://loginserver.de/login?return=http%3A%2F%2Flocalhost%2Fulicms%2Fadmin%2F",
    		"logout_url" => "http://loginserver.de/logout",
    		"remove_realm" => true,
    		"mail_suffix" => "@firma.de",
    		"create_user" => true ,
    		"default_lastname" => "Nachname",
    		"default_firstname" => "Vorname",
    		"hide_logout_link" => false
    );

Im nächsten Abschnitt folgt eine Erklärung der einzelnen Parameter
3. Installieren Sie remote_auth in dem Sie unter dem Menüpunkt "Pakete" > "Paket hochladen" das SimpleInstall Package hochladen und die Installation des Pakets bestätigen

### Konfiguration
Im folgenden eine Erklärung der Konfigurationsparameter

**env_vars** Eine Liste der Umgebungsvariablen aus denen der Login ausgelesen wird.
**login_url**  Wenn der Benutzer nicht eingeloggt ist, wird dieser zu einem Anmeldeformular weitergeleitet. Wenn Sie dies auskommentieren, wird stattdessen das interne Anmeldeformular von UliCMS gezeigt
**logout_url** Diese URL wird beim Klick auf "Logout" aufgerufen. Wenn Sie dies auskommentieren, wird nach dem Abmelden auf login_url bzw. das reguläre Anmeldeformularer weitergeleitet.
**remove_realm** Wenn der Benutzername nach dem Schema username@domain aufgebaut ist, wird alles ab dem @-Zeichen entfernt.
**mail_suffix** Diese Zeichenkette wird beim Anlegen eines neuen Benutzers an den Benutzernamen angehängt, um eine E-Mail Adresse zu generieren.
**create_user**
Legt fest, ob ein Benutzer erstellt werden soll, wenn dieser noch nicht existiert.
**default_lastname** Nachname für automatisch erzeugte Benutzer. Der Anwender kann dies nach dem Login in seinem Profil ändern.
**default_firstname** Nachname für automatisch erzeugte Benutzer. Der Anwender kann dies nach dem Login in seinem Profil ändern.
**hide_logout_link** Gibt an, ob der Logout-Link im Menü entfernt werden soll. Dies macht bei Authentifizierungsverfahren Sinn, die keine standardisierte Logout-Funktion enthalten (z.B. HTTP Basic und HTTP Digest).


## Troubleshooting
Falls Sie sich aufgrund einer Fehlkonfiguration nicht mehr einloggen können, löschen Sie entweder den "remote_auth" Ordner unter "modules" oder führen Sie in der Datenbank folgendes SQL aus.

    update {prefix}modules set enabled = 0 where name = 'remote_auth';
