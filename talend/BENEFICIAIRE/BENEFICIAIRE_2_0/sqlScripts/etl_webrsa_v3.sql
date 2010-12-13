-- webrsa
ALTER USER webrsa SUPERUSER;

--------------------------- INSTRUCTION V32

--conditionsactivitesprealables
CREATE TABLE conditionsactivitesprealables
(
  id serial NOT NULL,
  ddcondactprea date NOT NULL,
  dfcondactprea date NOT NULL,
  topcondactprea type_booleannumber NOT NULL,
  nbheuacttot integer NOT NULL,
  personne_id integer NOT NULL,
  CONSTRAINT conditionsactivitesprealables_pkey PRIMARY KEY (id),
  CONSTRAINT conditionsactivitesprealables_personne_id_fkey FOREIGN KEY (personne_id)
      REFERENCES personnes (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (OIDS=FALSE);
ALTER TABLE conditionsactivitesprealables OWNER TO webrsa;

--paiementsfoyers
ALTER TABLE paiementsfoyers ADD COLUMN numdebiban VARCHAR(4) NOT NULL;

ALTER TABLE paiementsfoyers ADD COLUMN numfiniban VARCHAR(7) NOT NULL;

ALTER TABLE paiementsfoyers ADD COLUMN bic VARCHAR(11) NOT NULL;

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