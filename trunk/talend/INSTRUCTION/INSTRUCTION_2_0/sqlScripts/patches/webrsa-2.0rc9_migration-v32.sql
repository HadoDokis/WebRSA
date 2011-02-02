-- Scripts de migrations iRSA v. 5 à 6 et Cristal v. 31 à 32 --
-- Il est possible que vous ayez à commenter certaines commandes
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

ALTER TABLE paiementsfoyers ALTER COLUMN clerib TYPE character varying(2);