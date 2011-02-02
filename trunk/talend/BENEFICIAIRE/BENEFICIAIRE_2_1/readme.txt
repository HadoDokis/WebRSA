Projet Talend "BENEFICIAIRE_2_1" - Dinah BLIRANDO - CG93/DSI - 31/01/2011
==========================================================================

* Traitement d'int�gration des flux CAF de type B�n�ficiaire (VRSB) dans l'application WebRSA.

Version Talend	: TOS 3.2.3
Version Cristal	: 29 � 32 
Version VRSB	: 0301 � 0401
Version WebRSA	: 2.0 rc12


* Documentation et scripts SQL dans les r�pertoires :

+ documentations :
	- INTEGRATION_FLUX_2_1_Annexes.pdf : Liste des balises et des tables trait�es par jobs, par �tape et par flux.
	- INTEGRATION_FLUX_2_1_Architecture.pdf : Document d'architecture logique du traitement d'int�gration des flux CAF.
	- INTEGRATION_FLUX_2_1_Guide_Technique.pdf : Guide technique du d�veloppement du projet Talend.
	- INTEGRATION_FLUX_2_1_Guide_Utilisateur.pdf : Guide d'installation de configuration et d'utilisation des scripts Talend. 

+ sqlScripts :	
	- STAGING_RSA.SCHEMA-2.0rc9-20101209.sql : Installation de la base de donn�es STAGING_RSA.
	- webrsa.ETL.SCHEMA-2.0rc9-20101209.sql  : Ajout des tables de statistique d'int�gration des flux dans la base de donn�es webrsa.
	+ patches :
		- webrsa-2.0rc9_migration-v29-31.sql : Mise � jour de la base webrsa de la V28 � la V31.
		- webrsa-2.0rc9_migration-v32.sql : Mise � jour de la base webrsa de la V31 � la V32.
		- patch-*-STAGING_RSA : Patches � passer sur la base STAGING_RSA.
		- patch-*-WEBRSA : Patches � passer sur la base webrsa.