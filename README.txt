Tout les codes en java sont disponibles dans les fichiers java/client.jar et java/serveur.jar
(Le main de client.jar permet de lancer le client avec la commande java -jar client.jar
et le main de serveur.jar permet de lancer le client avec la commande java -jar serveur.jar)

client.jar peut prendre en paramètres -ip "adresse ip serveur"  ou -n "nom dns de la machine" et éventuellement le port avec -p "num port"
serveur.jar eut prendre en paramètres -p "num port"

Le serveur se connecte directement à la base de données.
Le dossier java contient aussi directement le code du client et du serveur TCP ainsi que la classe (SQLDataBase.java) qui permet la connexion à la base de données et les méthodes
utilisées par le serveur.


Les codes php et css du site sont disponible dans le dossier php.
Cependant pour faire fonctionner le site, il faut rajouter des informations de connexion dans le fichier connexion.php
(le mot de passe a été enlevé).

Le rendu contient également le fichier contenant les scripts de création de la page de données (CREATE + INSERT) et le rapport.


