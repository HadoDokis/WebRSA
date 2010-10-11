-- *****************************************************************************
BEGIN;
-- *****************************************************************************

-- Table: administration.rejet_historique

 DROP TABLE IF EXISTS administration.rejet_historique;

CREATE TABLE administration.rejet_historique
(
  cleinfodemandersa integer NOT NULL,
  flux character varying(20) NOT NULL DEFAULT NULL::character varying,
  etape integer,
  table_en_erreur character varying(50) DEFAULT NULL::character varying,
  log character varying(1000) DEFAULT NULL::character varying,
  numdemrsa character varying(20) DEFAULT NULL::character varying,
  matricule character varying(20) DEFAULT NULL::character varying,
  "DT_INSERT" timestamp(6) without time zone NOT NULL DEFAULT now(),
  fic character varying(40),
  balisededonnee character varying(100000),
  CONSTRAINT rejet_historique_pkey PRIMARY KEY (cleinfodemandersa, flux, "DT_INSERT")
)
WITH (OIDS=FALSE);
ALTER TABLE administration.rejet_historique OWNER TO webrsa;


-- *****************************************************************************
COMMIT;
-- *****************************************************************************