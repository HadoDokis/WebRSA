********************************************************************************
* Webrsa version poc_optim1.                                                   *
********************************************************************************

Cette version est une version "spéciale optimisations"; il s'agit d'une sorte de preuve de concept portant sur le scénario de tests de performances réalisé par le CG93 et la société Octo avec l'outil Loadrunner vers le mi-août 2010.

Suite à une première vague d'optimisations (avec la sortie de Webrsa version 2.0rc11 le 27/08/2010), il a été constaté qu'environ 120 modèles étaient chargés à chaque appel de page (ce qui inclut la page de connexion) et que la fonction la plus utilisée était la fonction de chargement de modèles (Controller::loadModel).

Des pistes ont été fournies et certaines d'entre elles ont dès à présent été explorées:
	* au niveau du nombre de modèles
		- utilisation de LazyModel (http://bakery.cakephp.org/articles/view/optimizing-model-loading-with-lazymodel) (FAIT)
		- utilisation de Controller::persistModel (http://book.cakephp.org/view/814/persistModel) (testé, n'améliore pas / plante l'apllication)
		- réduire le nombre de modèles utilisés par chaque contrôleur (FAIT/EN COURS)
	* au niveau de la base de données (base, requêtes, connection)
		- optimiser la gestion des ACL (indexes sur les tables aros, acos, ...) (FAIT)
		- utiliser des connexions persistantes à la base données (A TESTER)
		- grouper les tables en relation 1-1 (dossiers_rsa/foyers, adresses_foyers/adresses, ...) (A TESTER)
		- éviter les recherches LIKE/ILIKE avec de % quand ce n'est pas nécessaire (ex. detailscalculsdroitsrsa.natpf dans recherche par dossier/allocataire) (A FAIRE)
		- optimiser les sous-requêtes ou s'en passer quand c'est possible (ex. dernière adresse) (A FAIRE)
	* au niveau du code PHP
		- utiliser Containable (FAIT/EN COURS)
		- alléger le code (FAIT/EN COURS)
		- utiliser le cache dès que possible (FAIT/EN COURS)
			* cacher le menu du haut dans la session (FAIT)
			* cacher les résultats de certaines requêtes "statiques" (FAIT/EN COURS)
		- faire passer certaines vérifications (pdftk, paramétrage des utilisateurs, ...) dans une page dédiée à l'administrateur plutôt que dans le AppController (FAIT)
		- vérifier les ressources utilisées par le système de jetons (essayer Configure::write( 'Jetons.disabled', true ) dans webrsa.inc) (FAIT)
	* au niveau du serveur Apache
		- ajouter un processeur (gain d'un peu moins de 100%) (si on atted 300 utilisateurs au final en bi-processeur, il en faut environ 150 en processeur simple ?)

--------------------------------------------------------------------------------

Rappel des pistes déjà suivies pour la version 2.0rc11
	* au niveau du code PHP
		- allègement du code
		- Utilisation du système de cache d'OPCODE ( APC ):
			sudo aptitude install php-apc
			éditer /etc/php5/apache2/php.ini et ajouter à la fin
				extension=apc.so
				apc.enabled="1"
				apc.enable_cli="1"
				apc.lazy_functions="1"
				apc.lazy_classes="1"
			/etc/init.d/apache2 restart;/etc/init.d/postgresql-8.3 restart

--------------------------------------------------------------------------------
- Modifications de la base de données                                          -
--------------------------------------------------------------------------------

Il est vivement conseillé de passer les patches suivants sur une copie de la base Webrsa.

Nous avons pris le parti de renommer certaines tables et certains champs de la base de données afin de coller aux conventions CakePHP. Nous pensons que celà éviterait à CakePHP de devoir lire certains modèles ou de prendre des initiatives qui ne l'aident pas.

Certaines tables (anciennes tables dspps, dspfs, ... - tables en béta concernant les EPs) ont été supprimées.

Certaines données seront par-ailleurs supprimées (les adresses_foyers multiples, de rang 01 pour un même foyer)

Patches à passer:
	1°) app/config/sql/beta/resolution/beta.resolution.adresses.sql
	2°) app/config/sql/patches/2.x/patch-2.0rc12-aros_acos.sql
	3°) app/config/sql/patches/2.x/patch-2.0rc12-optim1.sql
Scripts à passer:
	cake/console/cake postgresql all

Il est bien entendu que si les améliorations de performance sont au rendez-vous, ces scripts de nettoyage / suppression seront revus avec les CGs afin de s'assurer que ce qui est supprimé représentait de toutes façons de mauvaises données.

D'autre part, toujours dans le cas où l'on choisirait de continuer dans cette voie, avec les renommages de tables et de champs, il faudra revoir les jobs Talend qui alimentent la base à partir des flux.

// -----------------------------------------------------------------------------

Changements apportés:
	* Base de données (patch-2.0rc12-optim1.sql)
		- les tables qui ne sont plus utilisées ont été supprimées (dspps, dspfs, ...)
		- les tables (béta, et amenées à énormément évoluer) liées aux équipes pluridisciplinaires (EPs) ont été supprimées
		- mise en meilleure conformité du nommage des tables et des champs par-rapport à CakePHP (dossiers_rsa -> dossiers, dossier_rsa_id -> dossier_id, ...)
		- ajout de contraintes et nettoyage brutal de certaines données (adresses_foyers multiples)
		- patch-2.0rc12-aros_acos.sql afin de nettoyer les aros/acos, ajout d'indexes
	* Code PHP
		- Nettoyage de l'AppController: les vérifications qui ne sont pas obligatoires lorsqu'un utilisateur se connecte ont été bougées dans un autre contrôleur à destination unique de l'administrateur (présence de certains binaires, droits sur certains dossiers, paramétrages webrsa.inc, paramétrages utilisateurs, ...)
		- le code (béta, et amené à énormément évoluer) liées aux équipes pluridisciplinaires (EPs) a été supprimé
		- les inflections ont été simplifiées et ne concernent actuellement (pratiquement) plus que les tables présentes dans la base. Il faudra donc compléter les inflections à l'avenir.
		- Tous les modèles ont été regénérés avec cake/bake
			a°) seuls les modèles utilisés par le scénario ont été mis à jour avec les règles de validation, utilisation de behaviors et fonctions qui se trouvaient dans trunk
			b°) les modèles non liés à une table de la base de données ont été temporairement supprimés (sauf le modèle Option)
		- utilisation du plugin LazyModel de Phally (il semblerait que ça ne nous concerne pas)
			Q:  ACL breaks on CakePHP 1.2, is this a bug?
			A:  Yes, but not one of this plugin. It has to do with the PHP4 compatibility of CakePHP.
		- seules les contrôleurs et les vues liées au scénario ont été mis à jour
		- plus d'informations dans le pied de page (temps, mem utilisée / mem allouée, nombre de modèles utilisés)

-- -----------------------------------------------------------------------------
-- Notes Adullact
-- -----------------------------------------------------------------------------

TODO:
	* continuer à mettre les requêtes "statiques" en cache (Typeorient::listOptions)
	* normalement, les connections persistantes
		- posent un problème au niveau des locks de table (lock jusqu'au timeout)
		- pourraient saturer le système ?
		- pourraient poser des problèmes lorsqu'une transaction ne se finit pas et qu'une autre transaction voudrait commencer
INFO:
	* ajout des contraintes sur les champs des tables = manière de blinder l'appli, cassera peut-être à cause du code, donc on pourra plus facilement repérer le code qui rentre de mauvaises valeurs