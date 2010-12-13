Projet Talend "INSTRUCTION_1_2" - Dinah BLIRANDO - CG93/DSI - 17/12/2010
==========================================================================

* Traitement d'int�gration des flux CAF de type Instruction (VIRS) dans l'application WebRSA.

Version Talend	: TOS 3.2.3
Version @RSA	: 3.3 � 5 
Version WebRSA	: 2.0 rc11 et inf�rieure


* Documentation et scripts SQL dans les r�pertoires:

+ documentations :
	- INTEGRATION_FLUX_2_0_Annexes.pdf : Liste des balises et des tables trait�es par jobs, par �tape et par flux.
	- INTEGRATION_FLUX_2_0_Architecture.pdf : Document d'architecture logique du traitement d'int�gration des flux CAF.
	- INTEGRATION_FLUX_2_0_Guide_Technique.pdf : Guide technique du d�veloppement du projet Talend.
	- INTEGRATION_FLUX_2_0_Guide_Utilisateur.pdf : Guide d'installation de configuration et d'utilisation des scripts Talend. 

+ sqlScripts :	
	- etl_staging_v2.sql : Installation de la base de donn�es STAGING_RSA.
	- etl_webrsa_v2.sql  : Mise � jour de la base webrsa de la V28 � la V31.
				Ajout des tables de statistique d'int�gration des flux dans la base de donn�es webrsa.