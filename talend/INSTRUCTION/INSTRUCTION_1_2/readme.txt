Projet Talend "INSTRUCTION_1_2" - Dinah BLIRANDO - CG93/DSI - 17/12/2010
==========================================================================

* Traitement d'intégration des flux CAF de type Instruction (VIRS) dans l'application WebRSA.

Version Talend	: TOS 3.2.3
Version @RSA	: 3.3 à 5 
Version WebRSA	: 2.0 rc11 et inférieure


* Documentation et scripts SQL dans les répertoires:

+ documentations :
	- INTEGRATION_FLUX_2_0_Annexes.pdf : Liste des balises et des tables traitées par jobs, par étape et par flux.
	- INTEGRATION_FLUX_2_0_Architecture.pdf : Document d'architecture logique du traitement d'intégration des flux CAF.
	- INTEGRATION_FLUX_2_0_Guide_Technique.pdf : Guide technique du développement du projet Talend.
	- INTEGRATION_FLUX_2_0_Guide_Utilisateur.pdf : Guide d'installation de configuration et d'utilisation des scripts Talend. 

+ sqlScripts :	
	- etl_staging_v2.sql : Installation de la base de données STAGING_RSA.
	- etl_webrsa_v2.sql  : Mise à jour de la base webrsa de la V28 à la V31.
				Ajout des tables de statistique d'intégration des flux dans la base de données webrsa.