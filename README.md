## Presentation
#
Internet a permis de révolution la manière de communiquer et avec ses évolutions est venu de nouveau besoin.

En effet pour les communautés, la facilité de communication a permis de structurer et même de regrouper des passionnées. Mais ses communautés ont un autre besoin, en effet quoi de mieux pour parler de quelque chose que de la faire en face a face. 

C’est pour cela que des meet-up ou autre évènement avec différents intervenants sont organisés partout dans le monde. 
Mais afin de favoriser le sentiment d’appartenance, et donc l’implications des membres de cette communauté dans celle-ci. 

Nous avons donc créé un site permettant de voter les conférences les plus demander afin de les inclure dans le prochain évènement.

#
### Installation
Ce projet a été développé grâce au Framework Symfony sur une semaine grâce à une équipe de 3 développeurs (Alexandre Giannetto, Enzo Borges et Alex Bellata).
 
Les utilisateurs peuvent donc voir les conférences qu’ils n’ont pas encore notées, toutes les conférences ainsi que le top 10 des conférences.


Pour les rôles admins il y a une page permettant de modifier et supprimé les conférences, une permettant de supprimer des utilisateurs ainsi qu’un bouton permettant de supprimer tout les likes et conférences afin de remettre a zéro le site web.


Voici les différentes informations nécessaires pour faire fonctionner cette application sur votre local host.
	Installer le serveur et les fichiers, symfony ainsi que la base de données de grâce a docker :

- Recuperation des fichier 
```bash
git clone https://github.com/bellatalex/ipssi-EventConfPoll
cd  ipssi-EventConfPoll
```

- Intallation de symfony et de la bdd
```bash
docker-compose up
```
- Génération de données fictives
```bash
docker-compose exec web php bin/console doctrine:fixtures:load
```

##Informations importantes
- Adresse mailhogs :
http://localhost:8025/


- Admin<br/>
  id :      admin@site.com <br/>
  pswd :    admin
       
