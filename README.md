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
1. Führen Sie **bevor** sie remote_auth installieren die Konfiguration durch.
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
2. Installieren Sie remote_auth in dem Sie unter dem Menüpunkt "Pakete" > "Paket hochladen" das SimpleInstall Package hochladen und die Installation des Pakets bestätigen


## Troubleshooting
Falls Sie sich aufgrund einer Fehlkonfiguration nicht mehr einloggen können, löschen Sie entweder den "remote_auth" Ordner unter "modules" oder führen Sie in der Datenbank folgendes SQL aus.

    update {prefix}modules set enabled = 0 where name = 'remote_auth';
