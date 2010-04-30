SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

BEGIN;

ALTER TABLE users ADD COLUMN numvoie VARCHAR(6);
ALTER TABLE users ADD COLUMN typevoie VARCHAR(4);
ALTER TABLE users ADD COLUMN nomvoie VARCHAR(25);
ALTER TABLE users ADD COLUMN compladr VARCHAR(40);
ALTER TABLE users ADD COLUMN codepos VARCHAR(5);
ALTER TABLE users ADD COLUMN ville VARCHAR(50);


ALTER TABLE structuresreferentes ADD COLUMN orientation type_no DEFAULT 'O';
ALTER TABLE structuresreferentes ADD COLUMN pdo type_no DEFAULT 'O';

CREATE TYPE type_type_demande AS ENUM ( 'DOD', 'DRD' );
ALTER TABLE contratsinsertion ADD COLUMN type_demande type_type_demande;

CREATE TYPE type_num_contrat AS ENUM ( 'PRE', 'REN' );
ALTER TABLE contratsinsertion ADD COLUMN num_contrat type_num_contrat;

UPDATE contratsinsertion
    SET num_contrat = 'REN'
    WHERE numcontrat = 'Renouvellement';

UPDATE contratsinsertion
    SET num_contrat = 'PRE'
    WHERE numcontrat = 'Premier contrat';

ALTER TABLE contratsinsertion DROP COLUMN numcontrat;
-- *****************************************************************************
--      La partie suivante est à utiliser uniquement en cas de doublons
--      de pièces liées au niveau des aides de l'APRE pour le CG93
-- Ces doublons ont été constatés lors de la récupération de certaines bases
-- *****************************************************************************

-- DELETE FROM piecesaccscreaentr WHERE id > '2';
-- DELETE FROM piecesactsprofs WHERE id > '3';
-- DELETE FROM piecesamenagslogts WHERE id > '7';
-- DELETE FROM piecesformsqualifs WHERE id > '3';
-- DELETE FROM piecespermisb WHERE id > '2';
-- --------------------------------------------------------------------------------


COMMIT;