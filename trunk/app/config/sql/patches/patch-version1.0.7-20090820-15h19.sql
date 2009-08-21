--------------- Ajout du 20/08/2009 à 15h20 ------------------
ALTER TABLE structuresreferentes ALTER COLUMN num_voie TYPE VARCHAR(15);
ALTER TABLE structuresreferentes ALTER COLUMN nom_voie TYPE VARCHAR(50);

ALTER TABLE servicesinstructeurs ALTER COLUMN num_rue TYPE VARCHAR(15);


--------------- Ajout du 21/08/2009 à 14h31 ------------------

ALTER TABLE dspfs ALTER COLUMN accosocfam TYPE BOOLEAN USING CASE WHEN accosocfam='O' THEN TRUE WHEN accosocfam='N' THEN FALSE ELSE NULL END;

ALTER TABLE dspps ALTER COLUMN couvsoc TYPE BOOLEAN USING CASE WHEN couvsoc='O' THEN TRUE WHEN couvsoc='N' THEN FALSE ELSE NULL END;
ALTER TABLE dspps ALTER COLUMN creareprisentrrech TYPE BOOLEAN USING CASE WHEN creareprisentrrech='O' THEN TRUE WHEN creareprisentrrech='N' THEN FALSE ELSE NULL END;
ALTER TABLE dspps ALTER COLUMN domideract TYPE BOOLEAN USING CASE WHEN domideract='O' THEN TRUE WHEN domideract='N' THEN FALSE ELSE NULL END;
ALTER TABLE dspps ALTER COLUMN drorsarmiant TYPE BOOLEAN USING CASE WHEN drorsarmiant='O' THEN TRUE WHEN drorsarmiant='N' THEN FALSE ELSE NULL END;
ALTER TABLE dspps ALTER COLUMN drorsarmianta2 TYPE BOOLEAN USING CASE WHEN drorsarmianta2='O' THEN TRUE WHEN drorsarmianta2='N' THEN FALSE ELSE NULL END;
ALTER TABLE dspps ALTER COLUMN elopersdifdisp TYPE BOOLEAN USING CASE WHEN elopersdifdisp='O' THEN TRUE WHEN elopersdifdisp='N' THEN FALSE ELSE NULL END;
ALTER TABLE dspps ALTER COLUMN obstemploidifdisp TYPE BOOLEAN USING CASE WHEN obstemploidifdisp='O' THEN TRUE WHEN obstemploidifdisp='N' THEN FALSE ELSE NULL END;
ALTER TABLE dspps ALTER COLUMN soutdemarsoc TYPE BOOLEAN USING CASE WHEN soutdemarsoc='O' THEN TRUE WHEN soutdemarsoc='N' THEN FALSE ELSE NULL END;

/**********************************************************************************************************************/
ALTER TABLE dspfs ALTER COLUMN accosocfam TYPE BOOLEAN;
ALTER TABLE dspps ALTER COLUMN couvsoc TYPE BOOLEAN;
ALTER TABLE dspps ALTER COLUMN creareprisentrrech TYPE BOOLEAN;
ALTER TABLE dspps ALTER COLUMN domideract TYPE BOOLEAN;
ALTER TABLE dspps ALTER COLUMN drorsarmiant TYPE BOOLEAN;
ALTER TABLE dspps ALTER COLUMN drorsarmianta2 TYPE BOOLEAN;
ALTER TABLE dspps ALTER COLUMN elopersdifdisp TYPE BOOLEAN;
ALTER TABLE dspps ALTER COLUMN obstemploidifdisp TYPE BOOLEAN;
ALTER TABLE dspps ALTER COLUMN soutdemarsoc TYPE BOOLEAN;