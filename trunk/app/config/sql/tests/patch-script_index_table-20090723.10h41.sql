/* Index table personnes */

CREATE INDEX personnes_prenom_idx ON personnes (prenom);
CREATE INDEX personnes_foyer_id_idx ON personnes (foyer_id);
CREATE INDEX personnes_nom_idx ON personnes (nom);
CREATE INDEX personnes_nomnai_idx ON personnes (nomnai);
CREATE INDEX personnes_dtnai_idx ON personnes (dtnai);
CREATE INDEX personnes_nir_idx ON personnes (nir);

/* Index table dossiers_rsa */

CREATE INDEX dossiers_rsa_numdemrsa_idx ON dossiers_rsa (numdemrsa);
CREATE INDEX dossiers_rsa_dtdemrsa_idx ON dossiers_rsa (dtdemrsa);
CREATE INDEX dossiers_rsa_numcli_idx ON dossiers_rsa (numcli);
CREATE INDEX dossiers_rsa_matricule_idx ON dossiers_rsa (matricule);
CREATE INDEX dossiers_rsa_statudemrsa_idx ON dossiers_rsa (statudemrsa);

/* Index table adresses */

CREATE INDEX adresses_nomvoie_idx ON adresses (nomvoie);
CREATE INDEX adresses_numcomrat_idx ON adresses (numcomrat);
CREATE INDEX adresses_numcomptt_idx ON adresses (numcomptt);
CREATE INDEX adresses_codepos_idx ON adresses (codepos);
CREATE INDEX adresses_locaadr_idx ON adresses (locaadr);

/* Index table adresses_foyers */

CREATE INDEX adresses_foyers_adresse_id_idx ON adresses_foyers (adresse_id);
CREATE INDEX adresses_foyers_foyer_id_idx ON adresses_foyers (foyer_id);
CREATE INDEX adresses_foyers_rgadr_idx ON adresses_foyers (rgadr);

/* Index table prestations */

CREATE INDEX prestations_personne_id_idx ON prestations (personne_id);
CREATE INDEX prestations_natprest_idx ON prestations (natprest);
CREATE INDEX prestations_rolepers_idx ON prestations (rolepers);
CREATE INDEX prestations_topchapers_idx ON prestations (topchapers);
CREATE INDEX prestations_toppersdrodevorsa_idx ON prestations (toppersdrodevorsa);


/* Index table foyers */

CREATE INDEX foyers_dossier_rsa_id_idx ON foyers (dossier_rsa_id);
CREATE INDEX foyers_sitfam_idx ON foyers (sitfam);

/* Index table creancesalimentaires */

CREATE INDEX creancesalimentaires_etatcrealim_idx ON creancesalimentaires (etatcrealim);

/* Index table detailsdroitsrsa */

CREATE INDEX detailsdroitsrsa_dossier_rsa_id_idx ON detailsdroitsrsa (dossier_rsa_id);
CREATE INDEX detailsdroitsrsa_topfoydrodevorsa_idx ON detailsdroitsrsa (topfoydrodevorsa);

/* Index table detailscalculsdroitsrsa */

CREATE INDEX detailscalculsdroitsrsa_detaildroitrsa_id_idx ON detailscalculsdroitsrsa (detaildroitrsa_id);
CREATE INDEX detailscalculsdroitsrsa_natpf_idx ON detailscalculsdroitsrsa (natpf);

/* Index table situationsdossiersrsa */

CREATE INDEX situationsdossiersrsa_dossier_rsa_id_idx ON situationsdossiersrsa (dossier_rsa_id);
CREATE INDEX situationsdossiersrsa_etatdosrsa_idx ON situationsdossiersrsa (etatdosrsa);
CREATE INDEX situationsdossiersrsa_dtrefursa_idx ON situationsdossiersrsa (dtrefursa);
CREATE INDEX situationsdossiersrsa_moticlorsa_idx ON situationsdossiersrsa (moticlorsa);
CREATE INDEX situationsdossiersrsa_dtclorsa_idx ON situationsdossiersrsa (dtclorsa);

/* Index table suspensionsversements */

CREATE INDEX suspensionsversements_situationdossierrsa_id_idx ON suspensionsversements (situationdossierrsa_id);
CREATE INDEX suspensionsversements_ddsusversrsa_idx ON suspensionsversements (ddsusversrsa);
CREATE INDEX suspensionsversements_motisusversrsa_idx ON suspensionsversements (motisusversrsa);

/* Index table suspensionsdroits */

CREATE INDEX suspensionsdroits_situationdossierrsa_id_idx ON suspensionsdroits (situationdossierrsa_id);
CREATE INDEX suspensionsdroits_motisusdrorsa_idx ON suspensionsdroits (motisusdrorsa);
CREATE INDEX suspensionsdroits_ddsusdrorsa_idx ON suspensionsdroits (ddsusdrorsa);