Projet Talend "BENEFICIAIRE_2_1" - Dinah BLIRANDO - CG93/DSI - 31/01/2011
==========================================================================

* Traitement d'intégration des flux CAF de type Bénéficiaire (VRSB) dans l'application WebRSA.

Version Talend	: TOS 3.2.3
Version Cristal	: 29 à 32 
Version VRSB	: 0301 à 0401
Version WebRSA	: 2.0 rc12


* Documentation et scripts SQL dans les répertoires :

+ documentations :
	- INTEGRATION_FLUX_2_1_Annexes.pdf : Liste des balises et des tables traitées par jobs, par étape et par flux.
	- INTEGRATION_FLUX_2_1_Architecture.pdf : Document d'architecture logique du traitement d'intégration des flux CAF.
	- INTEGRATION_FLUX_2_1_Guide_Technique.pdf : Guide technique du développement du projet Talend.
	- INTEGRATION_FLUX_2_1_Guide_Utilisateur.pdf : Guide d'installation de configuration et d'utilisation des scripts Talend. 

+ sqlScripts :	
	- STAGING_RSA.SCHEMA-2.0rc9-20101209.sql : Installation de la base de données STAGING_RSA.
	- webrsa.ETL.SCHEMA-2.0rc9-20101209.sql  : Ajout des tables de statistique d'intégration des flux dans la base de données webrsa.
	+ patches :
		- webrsa-2.0rc9_migration-v29-31.sql : Mise à jour de la base webrsa de la V28 à la V31.
		- webrsa-2.0rc9_migration-v32.sql : Mise à jour de la base webrsa de la V31 à la V32.
		- patch-*-STAGING_RSA : Patches à passer sur la base STAGING_RSA.
		- patch-*-WEBRSA : Patches à passer sur la base webrsa.