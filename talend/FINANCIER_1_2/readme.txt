Projet Talend "FINANCIER_1_2" - Dinah BLIRANDO - CG93/DSI - 30/12/2010
==========================================================================

* Traitement d'intégration des flux CAF de type Financier (VRSF) dans l'application WebRSA.

Version Talend	: TOS 3.2.3
Version Cristal	: 27 
Version VRSF	: 0101
Version WebRSA	: 2.0 rc9


* Documentation et scripts SQL dans les répertoires :

+ documentations :
	- INTEGRATION_FLUX_2_0_Annexes.pdf : Liste des balises et des tables traitées par jobs, par étape et par flux.
	- INTEGRATION_FLUX_2_0_Architecture.pdf : Document d'architecture logique du traitement d'intégration des flux CAF.
	- INTEGRATION_FLUX_2_0_Guide_Technique.pdf : Guide technique du développement du projet Talend.
	- INTEGRATION_FLUX_2_0_Guide_Utilisateur.pdf : Guide d'installation de configuration et d'utilisation des scripts Talend. 

+ sqlScripts :	
	- STAGING_RSA.SCHEMA-2.0rc9-20101209.sql : Installation de la base de données STAGING_RSA.
	- webrsa.ETL.SCHEMA-2.0rc9-20101209.sql  : Ajout des tables de statistique d'intégration des flux dans la base de données webrsa.