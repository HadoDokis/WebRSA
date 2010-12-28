-- *****************************************************************************
BEGIN;
-- *****************************************************************************


-- Table: administration.visionneuses

-- DROP TABLE administration.visionneuses;

CREATE TABLE administration.visionneuses
(
  id serial NOT NULL,
  flux character(15),
  nomfic character(40),
  dtdeb timestamp without time zone,
  dtfin timestamp without time zone,
  nbrejete numeric(6),
  nbinser numeric(6),
  nbmaj numeric(6),
  perscree numeric(6),
  persmaj numeric(6),
  dspcree numeric(6),
  dspmaj numeric(6)
)
WITH (OIDS=FALSE);
ALTER TABLE administration.visionneuses OWNER TO webrsa;


-- *****************************************************************************
COMMIT;
-- *****************************************************************************