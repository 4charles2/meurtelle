![logo Application](/web/image/logo.png)

$copy meurtelle54
===========


A Symfony project created on November 9, 2018, 8:42 pm.  


Si connexion impossible avec SQL mettre le mot de passe et user dans le fichier app/config/parameter.yml ligne 7: database_password et ligne 6: database_user.  

Après avoir  lancer votre serveur.(Apache ou php et SQL)  

Pour installer le projet:  
dans le répertoire du projet (Après avoir décompresser le fichier zip)  


INSTALLATION
------------------


php composer.phar install (installe le project) Une erreur peut subvenir could not open input file : app/console ne pas en tenir compte l'installation à réussi tout de même.  


php bin/console doctrine:database:create (Créer la table)  
Attention si la table symfony existe déjà dans votre base SQL une erreur sera rencontré  
Solution changer le nom dans parameter.yml du project a la ligne 5 ou supprimer la table symfony via phpmyadmin de votre base.  


php bin/console doctrine:schema:update --force (Créer les bases de données)  


php bin/console doctrine:fixture:load (Remplie les bases de données)  
Si une erreur s'affiche dans la console Exception occured while flushing email queue: Connection could not be established with host 127.0.01 [Connection refused #61] c'est normal car des candidature sont créer avec les fixtures et un mail automatique est envoyé mais si vous n'avez pas renseigné vos informations de mail comme décrit ci-dessous sa déclenche cette erreur. 
Cela n'est pas obligatoire pour le bon fonctionnement de l'application . Ne pas sans préoccuper  


REGLAGE MAIL
-------------------

>Un mail automatique est envoyé à plusieurs moment de la vie de l'application.  
>Si vous voulez tester avec les mails actif alors renseigner  les information dans le fichier parameter.yml ligne 9 a 14.  
>Cette étape n'est **PAS OBLIGATOIRE** pour le fonctionnement de l'application {  

Exemple de réglage pour une adresse mail gmail.com (Pour que sa fonctionne il faut débloquer lAccès moins sécurisé des applications dans votre compte google ) NON RECOMANDE j'avais créer une adresse mail de test exprès pour cela.

    mailer_transport: smtp
    mailer_host: smtp.gmail.com
    mailer_port: 465
    mailer_user: USER_SANS@gmail.com
    mailer_password: PASSWORD

//Pour que le paramètre mailer_port fonctionne il faudrat également ajouter la ligne :  

    port:       "%mailer_port%" dans le fichier app/config/config.yml vers la ligne 68

}


J’espère vous avoir données toutes les informations suffisante bonne correction et bonne chance pour la suite. q;-p  

--------------------------------------------------------------------------------------------------------------------------------------

Merci à [openclassroom](https://openclassroom.com "openclassroom") pour ces tutos très constructif  
