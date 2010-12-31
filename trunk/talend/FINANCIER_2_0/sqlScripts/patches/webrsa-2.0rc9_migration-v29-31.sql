-- Scripts de migrations iRSA v. 3.2 à 5 et Cristal v. 29 à 31 --
-- Il est possible que vous ayez à commenter certaines commandes

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
