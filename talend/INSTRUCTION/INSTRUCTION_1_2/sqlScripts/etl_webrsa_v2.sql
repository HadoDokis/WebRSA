-- webrsa
ALTER USER webrsa SUPERUSER;

--------------------------- INSTRUCTION V30-31

-- personnes 
ALTER TABLE personnes
ADD COLUMN numagenpoleemploi character(3);

ALTER TABLE personnes
ADD COLUMN dtinscpoleemploi date;


---------------------------- BENEFICIAIRE V29

-- Table: transmissionsflux
ALTER TABLE transmissionsflux
ADD COLUMN nbtotdosrsatransm integer;

---------------------------- BENEFICIAIRE V30-31

-- Table: controlesadministratifs

CREATE TABLE controlesadministratifs
(
  id serial NOT NULL,
  dteffcibcontro date,
  cibcontro character(3),
  cibcontromsa character(3),
  dtdeteccontro date,
  dtclocontro date,
  libcibcontro character varying(45),
  famcibcontro character(2),
  natcibcontro character(3),
  commacontro character(3),
  typecontro character(2),
  typeimpaccontro character(1),
  mtindursacgcontro numeric(11,2),
  mtraprsacgcontro numeric(11,2),
  foyer_id integer NOT NULL,
  CONSTRAINT controlesadministratifs_pkey PRIMARY KEY (id),
  CONSTRAINT controlesadministratifs_foyer_id_fkey FOREIGN KEY (foyer_id)
      REFERENCES foyers (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (OIDS=FALSE);
ALTER TABLE controlesadministratifs OWNER TO webrsa;

CREATE INDEX controlesadministratifs_foyer_id_idx
  ON controlesadministratifs
  USING btree
  (foyer_id);

-- situationsdossiersrsa
ALTER TABLE situationsdossiersrsa
ADD COLUMN motirefursa character(3);

-- suspensionsdroits
ALTER TABLE suspensionsdroits
ADD COLUMN natgroupfsus character(3); 


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