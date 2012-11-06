CETTE VERSION 2.4 EST UNE VERSION BETA ET NE DOIT PAS ETRE PASSÉE EN PRODUCTION

La version 2.4 beta est la première version de web-rsa utilisant le framework CakePHP en version 2.x.
La version est livrée avec le framework CakePHP en version 2.2.3.1.
A noter que la version minimale du framework doit être la version 2.2.3.1, version améliorée par nos soins, corrigeant une anomalie que nous avons détecté dans la version 2.2.3.
Une prochaine version 2.2.4 est déjà prévue par l'équipe CakePHP (incluant notre correctif) mais disponible uniquement à partir du 11/11/2012.

Cette version comprend :
	- l'amélioration des performances avec une meilleure utilisation du cache
	- une gestion des jetons moins bloquantes (SELECT FOR UPDATE à la place des LOCK TABLE)
	- le nouveau formulaire du CER pour le Cg93
	- le workflow complet du CER pour le Cg93
	- une vérification de l'application plus poussée
	- des tests unitaires plus complets avec l'utilisation de PHPUnit 3.6