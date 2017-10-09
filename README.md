# remote_auth
Remote Auth for UliCMS

## Beschreibung des Moduls
Dieses Modul ermöglicht die Nutzung von externen Authentifizierungsmethoden zur Anmeldung an UliCMS.

Während der Entwicklung des Moduls wurde HTTP Basic Authentication genutzt, jedoch funktioniert remote_auth mit jedem Anmeldeverfahren, bei dem der Login des angemeldeten Benutzers in eine Umgebungsvariable geschrieben wird.
Das enthält Verfahren wie NTLM, Proxyserver mit Passwortschutz und Single Sign-On Lösungen wie den IBM® Tivoli® Access Manager
