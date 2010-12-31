-- Tables de statistique d'intégration des flux INSTRUCTION, BENEFICIAIRE et FINANCIER
-- *** VERSIONS  ***
-- *** webrsa 2.0rc9
-- *** iRSA v. 3.3 - Cristal v. 29 min
ALTER USER webrsa SUPERUSER;

--------------------------- STATISTIQUES

-- StatIntegrationInstruction
-- DROP TABLE StatIntegrationInstruction ;
CREATE TABLE StatIntegrationInstruction ( 
	nom_fichier VARCHAR (100),
	activites INTEGER,
	adresses INTEGER,
	adresses_foyers INTEGER,
	aidesagricoles INTEGER,
	allocationssoutienfamilial INTEGER,
	conditionsactivitesprealables INTEGER,
	creancesalimentaires INTEGER,
	detailsaccosocfams INTEGER,
	detailsaccosocindis INTEGER,
	detailsdifdisps INTEGER,
	detailsdiflogs INTEGER,
	detailsdifsocs INTEGER,
	detailsnatmobs INTEGER,
	detailsressourcesmensuelles INTEGER,
	dossiers_rsa INTEGER,
	dossierscaf INTEGER,
	dsps INTEGER,
	foyers INTEGER,
	grossesses INTEGER,
	identificationsflux INTEGER,
	informationseti INTEGER,
	infosagricoles INTEGER,
	modescontact INTEGER,
	orientations INTEGER,
	paiementsfoyers INTEGER,
	parcours INTEGER,
	personnes INTEGER,
	prestations INTEGER,
	rattachements INTEGER,
	ressources INTEGER,
	ressourcesmensuelles INTEGER,
	suivisappuisorientation INTEGER,
	suivisinstruction INTEGER,
	titres_sejour INTEGER,
	transmissionsflux INTEGER
) WITH (OIDS=FALSE);
ALTER TABLE StatIntegrationInstruction OWNER TO webrsa;

-- StatIntegrationBeneficiaire
CREATE TABLE StatIntegrationBeneficiaire ( 
	nom_fichier VARCHAR (100),
	activites integer,
	adresses integer,
	adresses_foyers integer,
	allocationssoutienfamilial integer,
	anomalies integer,
	avispcgdroitrsa integer,
	avispcgpersonnes integer,
	calculsdroitsrsa integer,
	condsadmins integer,
	controlesadministratifs integer,
	creances integer,
	creancesalimentaires integer,
	derogations integer,
	detailscalculsdroitsrsa integer,
	detailsdroitsrsa integer,
	detailsressourcesmensuelles integer,
	dossiers_rsa integer,
	dossierscaf integer,
	evenements integer,
	foyers integer,
	grossesses integer,
	identificationsflux integer,
	infosagricoles integer,
	liberalites integer,
	personnes integer,
	prestations integer,
	rattachements integer,
	reducsrsa integer,
	ressources integer,
	ressourcesmensuelles integer,
	situationsdossiersrsa integer,
	suspensionsdroits integer,
	suspensionsversements integer,
	transmissionsflux integer
) WITH (OIDS=FALSE);
ALTER TABLE StatIntegrationBeneficiaire OWNER TO webrsa;

-- StatIntegrationFinancier
CREATE TABLE StatIntegrationFinancier ( 
	nom_fichier VARCHAR (100),
	anomalies INTEGER,
	dossiers_rsa INTEGER,
	identificationsflux INTEGER,
	infosfinancieres INTEGER,
	totalisationsacomptes INTEGER,
	transmissionsflux INTEGER
) WITH (OIDS=FALSE);
ALTER TABLE StatIntegrationFinancier OWNER TO webrsa;