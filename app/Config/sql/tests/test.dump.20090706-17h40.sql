--
-- PostgreSQL database dump
--

SET client_encoding = 'UTF8';
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON SCHEMA public IS 'Standard public schema';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: accoemplois; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE accoemplois (
    id serial NOT NULL,
    code character(4),
    name character varying(100)
);


ALTER TABLE public.accoemplois OWNER TO webrsa;

--
-- Name: accoemplois_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('accoemplois', 'id'), 4, false);


--
-- Name: acos; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE acos (
    id serial NOT NULL,
    parent_id integer NOT NULL,
    model character varying(255) DEFAULT ''::character varying,
    foreign_key integer,
    alias character varying(255) DEFAULT ''::character varying,
    lft integer,
    rght integer
);


ALTER TABLE public.acos OWNER TO webrsa;

--
-- Name: acos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('acos', 'id'), 5018, true);


--
-- Name: actions; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE actions (
    id serial NOT NULL,
    typeaction_id integer NOT NULL,
    code character(2),
    libelle character varying(250)
);


ALTER TABLE public.actions OWNER TO webrsa;

--
-- Name: actions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('actions', 'id'), 34, false);


--
-- Name: actionsinsertion; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE actionsinsertion (
    id serial NOT NULL,
    contratinsertion_id integer NOT NULL,
    dd_action date,
    df_action date,
    lib_action character(1)
);


ALTER TABLE public.actionsinsertion OWNER TO webrsa;

--
-- Name: actionsinsertion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('actionsinsertion', 'id'), 1, false);


--
-- Name: activites; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE activites (
    id serial NOT NULL,
    personne_id integer NOT NULL,
    reg character(2),
    act character(3),
    paysact character(3),
    ddact date,
    dfact date,
    natcontrtra character(3),
    topcondadmeti boolean,
    hauremuscmic character(1)
);


ALTER TABLE public.activites OWNER TO webrsa;

--
-- Name: activites_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('activites', 'id'), 1, false);


--
-- Name: adresses; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE adresses (
    id serial NOT NULL,
    numvoie character varying(6),
    typevoie character varying(4),
    nomvoie character varying(25),
    complideadr character varying(38),
    compladr character varying(26),
    lieudist character varying(32),
    numcomrat character(5),
    numcomptt character(5),
    codepos character(5),
    locaadr character varying(26),
    pays character varying(3),
    canton character varying(20)
);


ALTER TABLE public.adresses OWNER TO webrsa;

--
-- Name: adresses_foyers; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE adresses_foyers (
    id serial NOT NULL,
    adresse_id integer NOT NULL,
    foyer_id integer NOT NULL,
    rgadr character(2),
    dtemm date,
    typeadr character(1)
);


ALTER TABLE public.adresses_foyers OWNER TO webrsa;

--
-- Name: adresses_foyers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('adresses_foyers', 'id'), 1, true);


--
-- Name: adresses_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('adresses', 'id'), 1, true);


--
-- Name: aidesagricoles; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE aidesagricoles (
    id serial NOT NULL,
    infoagricole_id integer NOT NULL,
    annrefaideagri character(4),
    libnataideagri character varying(30),
    mtaideagri numeric(9,2)
);


ALTER TABLE public.aidesagricoles OWNER TO webrsa;

--
-- Name: aidesagricoles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('aidesagricoles', 'id'), 1, false);


--
-- Name: aidesdirectes; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE aidesdirectes (
    id serial NOT NULL,
    actioninsertion_id integer NOT NULL,
    lib_aide character varying(32),
    typo_aide character varying(32),
    date_aide date
);


ALTER TABLE public.aidesdirectes OWNER TO webrsa;

--
-- Name: aidesdirectes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('aidesdirectes', 'id'), 1, false);


--
-- Name: allocationssoutienfamilial; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE allocationssoutienfamilial (
    id serial NOT NULL,
    personne_id integer NOT NULL,
    sitasf character(2),
    parassoasf character(1),
    ddasf date,
    dfasf date
);


ALTER TABLE public.allocationssoutienfamilial OWNER TO webrsa;

--
-- Name: allocationssoutienfamilial_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('allocationssoutienfamilial', 'id'), 1, false);


--
-- Name: aros; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE aros (
    id serial NOT NULL,
    parent_id integer,
    model character varying(255) DEFAULT ''::character varying,
    foreign_key integer,
    alias character varying(255) DEFAULT ''::character varying,
    lft integer,
    rght integer
);


ALTER TABLE public.aros OWNER TO webrsa;

--
-- Name: aros_acos; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE aros_acos (
    id serial NOT NULL,
    aro_id integer NOT NULL,
    aco_id integer NOT NULL,
    _create character(2) DEFAULT 0 NOT NULL,
    _read character(2) DEFAULT 0 NOT NULL,
    _update character(2) DEFAULT 0 NOT NULL,
    _delete character(2) DEFAULT 0 NOT NULL
);


ALTER TABLE public.aros_acos OWNER TO webrsa;

--
-- Name: aros_acos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('aros_acos', 'id'), 3046, true);


--
-- Name: aros_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('aros', 'id'), 9, true);


--
-- Name: avispcgdroitrsa; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE avispcgdroitrsa (
    id serial NOT NULL,
    dossier_rsa_id integer NOT NULL,
    avisdestpairsa character(1),
    dtavisdestpairsa date,
    nomtie character varying(64),
    typeperstie character(1)
);


ALTER TABLE public.avispcgdroitrsa OWNER TO webrsa;

--
-- Name: avispcgdroitrsa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('avispcgdroitrsa', 'id'), 1, false);


--
-- Name: avispcgpersonnes; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE avispcgpersonnes (
    id serial NOT NULL,
    personne_id integer NOT NULL,
    avisevaressnonsal character(1),
    dtsouressnonsal date,
    dtevaressnonsal date,
    mtevalressnonsal numeric(9,2),
    excl character(1),
    ddexcl date,
    dfexcl date
);


ALTER TABLE public.avispcgpersonnes OWNER TO webrsa;

--
-- Name: avispcgpersonnes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('avispcgpersonnes', 'id'), 1, false);


--
-- Name: condsadmins; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE condsadmins (
    id serial NOT NULL,
    avispcgdroitrsa_id integer NOT NULL,
    aviscondadmrsa character(1),
    moticondadmrsa character(2),
    comm1condadmrsa character varying(60),
    comm2condadmrsa character varying(60),
    dteffaviscondadmrsa date
);


ALTER TABLE public.condsadmins OWNER TO webrsa;

--
-- Name: condsadmins_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('condsadmins', 'id'), 1, false);


--
-- Name: connections; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE connections (
    id serial NOT NULL,
    user_id integer NOT NULL,
    php_sid character(32),
    created timestamp without time zone,
    modified timestamp without time zone
);


ALTER TABLE public.connections OWNER TO webrsa;

--
-- Name: connections_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('connections', 'id'), 14, true);


--
-- Name: contratsinsertion; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE contratsinsertion (
    id serial NOT NULL,
    personne_id integer NOT NULL,
    structurereferente_id integer NOT NULL,
    typocontrat_id integer NOT NULL,
    dd_ci date,
    df_ci date,
    diplomes text,
    form_compl character varying(60),
    expr_prof text,
    aut_expr_prof character varying(120),
    rg_ci integer,
    actions_prev character(1),
    obsta_renc character varying(120),
    service_soutien character varying(120),
    pers_charg_suivi character varying(50),
    objectifs_fixes text,
    engag_object text,
    sect_acti_emp character varying(20),
    emp_occupe character varying(30),
    duree_hebdo_emp character varying(20),
    nat_cont_trav character(4),
    duree_cdd character varying(20),
    duree_engag integer,
    nature_projet text,
    observ_ci text,
    decision_ci character(1),
    datevalidation_ci date,
    date_saisi_ci date,
    lieu_saisi_ci character varying(30),
    emp_trouv boolean
);


ALTER TABLE public.contratsinsertion OWNER TO webrsa;

--
-- Name: contratsinsertion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('contratsinsertion', 'id'), 1, false);


--
-- Name: creances; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE creances (
    id serial NOT NULL,
    dtimplcre date,
    natcre character(3),
    rgcre character(3),
    motiindu character(2),
    oriindu character(2),
    respindu character(2),
    ddregucre date,
    dfregucre date,
    dtdercredcretrans date,
    mtsolreelcretrans numeric(9,2),
    mtinicre numeric(9,2)
);


ALTER TABLE public.creances OWNER TO webrsa;

--
-- Name: creances_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('creances', 'id'), 1, false);


--
-- Name: creancesalimentaires; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE creancesalimentaires (
    id serial NOT NULL,
    etatcrealim character(2),
    ddcrealim date,
    dfcrealim date,
    orioblalim character(3),
    motidiscrealim character(3),
    commcrealim character varying(50),
    mtsancrealim numeric(9,2),
    topdemdisproccrealim boolean,
    engproccrealim character(1),
    verspa character(1),
    topjugpa boolean
);


ALTER TABLE public.creancesalimentaires OWNER TO webrsa;

--
-- Name: creancesalimentaires_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('creancesalimentaires', 'id'), 1, false);


--
-- Name: creancesalimentaires_personnes; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE creancesalimentaires_personnes (
    personne_id integer NOT NULL,
    creancealimentaire_id integer NOT NULL
);


ALTER TABLE public.creancesalimentaires_personnes OWNER TO webrsa;

--
-- Name: derogations; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE derogations (
    id serial NOT NULL,
    avispcgpersonne_id integer NOT NULL,
    typdero character(3),
    avisdero character(1),
    ddavisdero date,
    dfavisdero date
);


ALTER TABLE public.derogations OWNER TO webrsa;

--
-- Name: derogations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('derogations', 'id'), 1, false);


--
-- Name: detailscalculsdroitsrsa; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE detailscalculsdroitsrsa (
    id serial NOT NULL,
    detaildroitrsa_id integer NOT NULL,
    natpf character(3),
    sousnatpf character(5),
    ddnatdro date,
    dfnatdro date,
    mtrsavers numeric(9,2),
    dtderrsavers date
);


ALTER TABLE public.detailscalculsdroitsrsa OWNER TO webrsa;

--
-- Name: detailscalculsdroitsrsa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('detailscalculsdroitsrsa', 'id'), 1, false);


--
-- Name: detailsdroitsrsa; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE detailsdroitsrsa (
    id serial NOT NULL,
    dossier_rsa_id integer NOT NULL,
    topsansdomfixe boolean,
    nbenfautcha integer,
    oridemrsa character(3),
    dtoridemrsa date,
    topfoydrodevorsa boolean,
    ddelecal date,
    dfelecal date,
    mtrevminigararsa numeric(9,2),
    mtpentrsa numeric(9,2),
    mtlocalrsa numeric(9,2),
    mtrevgararsa numeric(9,2),
    mtpfrsa numeric(9,2),
    mtalrsa numeric(9,2),
    mtressmenrsa numeric(9,2),
    mtsanoblalimrsa numeric(9,2),
    mtredhosrsa numeric(9,2),
    mtredcgrsa numeric(9,2),
    mtcumintegrsa numeric(9,2),
    mtabaneursa numeric(9,2),
    mttotdrorsa numeric(9,2)
);


ALTER TABLE public.detailsdroitsrsa OWNER TO webrsa;

--
-- Name: detailsdroitsrsa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('detailsdroitsrsa', 'id'), 1, true);


--
-- Name: detailsressourcesmensuelles; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE detailsressourcesmensuelles (
    id serial NOT NULL,
    ressourcemensuelle_id integer NOT NULL,
    natress character(3),
    mtnatressmen numeric(10,2),
    abaneu character(1),
    dfpercress date,
    topprevsubsress boolean
);


ALTER TABLE public.detailsressourcesmensuelles OWNER TO webrsa;

--
-- Name: detailsressourcesmensuelles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('detailsressourcesmensuelles', 'id'), 1, false);


--
-- Name: difdisps; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE difdisps (
    id serial NOT NULL,
    code character(4),
    name character varying(100)
);


ALTER TABLE public.difdisps OWNER TO webrsa;

--
-- Name: difdisps_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('difdisps', 'id'), 6, false);


--
-- Name: diflogs; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE diflogs (
    id serial NOT NULL,
    code character(4),
    name character varying(100)
);


ALTER TABLE public.diflogs OWNER TO webrsa;

--
-- Name: diflogs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('diflogs', 'id'), 10, false);


--
-- Name: difsocs; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE difsocs (
    id serial NOT NULL,
    code character(4),
    name character varying(100)
);


ALTER TABLE public.difsocs OWNER TO webrsa;

--
-- Name: difsocs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('difsocs', 'id'), 8, false);


--
-- Name: dossiers_rsa; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE dossiers_rsa (
    id serial NOT NULL,
    numdemrsa character varying(11),
    dtdemrsa date,
    dtdemrmi date,
    numdepinsrmi character(3),
    typeinsrmi character(1),
    numcominsrmi integer,
    numagrinsrmi character(2),
    numdosinsrmi integer,
    numcli integer,
    numorg character(3),
    fonorg character(3),
    matricule character(15),
    statudemrsa character(1),
    typeparte character(4),
    ideparte character(3),
    fonorgcedmut character(3),
    numorgcedmut character(3),
    matriculeorgcedmut character(15),
    ddarrmut date,
    codeposanchab character(5),
    fonorgprenmut character(3),
    numorgprenmut character(3),
    dddepamut date,
    details_droits_rsa_id integer,
    avis_pcg_id integer,
    organisme_id integer,
    acompte_rsa_id integer
);


ALTER TABLE public.dossiers_rsa OWNER TO webrsa;

--
-- Name: dossiers_rsa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('dossiers_rsa', 'id'), 1, true);


--
-- Name: dossierscaf; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE dossierscaf (
    id serial NOT NULL,
    personne_id integer NOT NULL,
    ddratdos date,
    dfratdos date,
    toprespdos boolean,
    numdemrsaprece character(11)
);


ALTER TABLE public.dossierscaf OWNER TO webrsa;

--
-- Name: dossierscaf_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('dossierscaf', 'id'), 1, false);


--
-- Name: dspfs; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE dspfs (
    id serial NOT NULL,
    foyer_id integer NOT NULL,
    motidemrsa character(4),
    accosocfam character(1),
    libautraccosocfam character varying(100),
    libcooraccosocfam character varying(250),
    natlog character(4),
    libautrdiflog character varying(100),
    demarlog character(4)
);


ALTER TABLE public.dspfs OWNER TO webrsa;

--
-- Name: dspfs_diflogs; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE dspfs_diflogs (
    diflog_id integer NOT NULL,
    dspf_id integer NOT NULL
);


ALTER TABLE public.dspfs_diflogs OWNER TO webrsa;

--
-- Name: dspfs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('dspfs', 'id'), 1, false);


--
-- Name: dspfs_nataccosocfams; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE dspfs_nataccosocfams (
    nataccosocfam_id integer NOT NULL,
    dspf_id integer NOT NULL
);


ALTER TABLE public.dspfs_nataccosocfams OWNER TO webrsa;

--
-- Name: dspps; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE dspps (
    id serial NOT NULL,
    personne_id integer NOT NULL,
    drorsarmiant character(1),
    drorsarmianta2 character(1),
    couvsoc character(1),
    libautrdifsoc character varying(100),
    elopersdifdisp character(1),
    obstemploidifdisp character(1),
    soutdemarsoc character(1),
    libautraccosocindi character varying(100),
    libcooraccosocindi character varying(250),
    annderdipobt date,
    rappemploiquali boolean,
    rappemploiform boolean,
    libautrqualipro character varying(100),
    permicondub boolean,
    libautrpermicondu character varying(100),
    libcompeextrapro character varying(100),
    persisogrorechemploi boolean,
    libcooraccoemploi character varying(100),
    hispro character(4),
    libderact character varying(100),
    libsecactderact character varying(100),
    dfderact date,
    domideract character(1),
    libactdomi character varying(100),
    libsecactdomi character varying(100),
    duractdomi character(4),
    libemploirech character varying(100),
    libsecactrech character varying(100),
    creareprisentrrech character(1),
    moyloco boolean,
    diplomes text,
    dipfra character(1)
);


ALTER TABLE public.dspps OWNER TO webrsa;

--
-- Name: dspps_accoemplois; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE dspps_accoemplois (
    accoemploi_id integer NOT NULL,
    dspp_id integer NOT NULL
);


ALTER TABLE public.dspps_accoemplois OWNER TO webrsa;

--
-- Name: dspps_difdisps; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE dspps_difdisps (
    difdisp_id integer NOT NULL,
    dspp_id integer NOT NULL
);


ALTER TABLE public.dspps_difdisps OWNER TO webrsa;

--
-- Name: dspps_difsocs; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE dspps_difsocs (
    difsoc_id integer NOT NULL,
    dspp_id integer NOT NULL
);


ALTER TABLE public.dspps_difsocs OWNER TO webrsa;

--
-- Name: dspps_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('dspps', 'id'), 1, false);


--
-- Name: dspps_nataccosocindis; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE dspps_nataccosocindis (
    nataccosocindi_id integer NOT NULL,
    dspp_id integer NOT NULL
);


ALTER TABLE public.dspps_nataccosocindis OWNER TO webrsa;

--
-- Name: dspps_natmobs; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE dspps_natmobs (
    natmob_id integer NOT NULL,
    dspp_id integer NOT NULL
);


ALTER TABLE public.dspps_natmobs OWNER TO webrsa;

--
-- Name: dspps_nivetus; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE dspps_nivetus (
    nivetu_id integer NOT NULL,
    dspp_id integer NOT NULL
);


ALTER TABLE public.dspps_nivetus OWNER TO webrsa;

--
-- Name: evenements; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE evenements (
    id serial NOT NULL,
    dtliq date,
    heuliq date,
    fg character varying(30)
);


ALTER TABLE public.evenements OWNER TO webrsa;

--
-- Name: evenements_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('evenements', 'id'), 1, false);


--
-- Name: foyers; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE foyers (
    id serial NOT NULL,
    dossier_rsa_id integer NOT NULL,
    sitfam character(3),
    ddsitfam date,
    typeocclog character(3),
    mtvallocterr numeric(9,2),
    mtvalloclog numeric(9,2),
    contefichliairsa text
);


ALTER TABLE public.foyers OWNER TO webrsa;

--
-- Name: foyers_creances; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE foyers_creances (
    foyer_id integer NOT NULL,
    creance_id integer NOT NULL
);


ALTER TABLE public.foyers_creances OWNER TO webrsa;

--
-- Name: foyers_evenements; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE foyers_evenements (
    foyer_id integer NOT NULL,
    evenement_id integer NOT NULL
);


ALTER TABLE public.foyers_evenements OWNER TO webrsa;

--
-- Name: foyers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('foyers', 'id'), 1, true);


--
-- Name: grossesses; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE grossesses (
    id serial NOT NULL,
    personne_id integer NOT NULL,
    ddgro date,
    dfgro date,
    dtdeclgro date,
    natfingro character(1)
);


ALTER TABLE public.grossesses OWNER TO webrsa;

--
-- Name: grossesses_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('grossesses', 'id'), 1, false);


--
-- Name: groups; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE groups (
    id serial NOT NULL,
    name character varying(50),
    parent_id integer
);


ALTER TABLE public.groups OWNER TO webrsa;

--
-- Name: groups_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('groups', 'id'), 4, false);


--
-- Name: identificationsflux; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE identificationsflux (
    id serial NOT NULL,
    applieme character(3),
    numversionapplieme character(4),
    typeflux character(1),
    natflux character(1),
    dtcreaflux date,
    heucreaflux date,
    dtref date
);


ALTER TABLE public.identificationsflux OWNER TO webrsa;

--
-- Name: identificationsflux_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('identificationsflux', 'id'), 1, false);


--
-- Name: informationseti; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE informationseti (
    id serial NOT NULL,
    personne_id integer NOT NULL,
    topcreaentre boolean,
    topaccre boolean,
    acteti character(1),
    topempl1ax boolean,
    topstag1ax boolean,
    topsansempl boolean,
    ddchiaffaeti date,
    dfchiaffaeti date,
    mtchiaffaeti numeric(9,2),
    regfiseti character(1),
    topbeneti boolean,
    regfisetia1 character(1),
    mtbenetia1 numeric(9,2),
    mtamoeti numeric(9,2),
    mtplusvalueti numeric(9,2),
    topevoreveti boolean,
    libevoreveti character varying(30),
    topressevaeti boolean
);


ALTER TABLE public.informationseti OWNER TO webrsa;

--
-- Name: informationseti_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('informationseti', 'id'), 1, false);


--
-- Name: infosagricoles; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE infosagricoles (
    id serial NOT NULL,
    personne_id integer NOT NULL,
    mtbenagri numeric(10,2),
    dtbenagri date,
    regfisagri character(1)
);


ALTER TABLE public.infosagricoles OWNER TO webrsa;

--
-- Name: infosagricoles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('infosagricoles', 'id'), 1, false);


--
-- Name: infosfinancieres; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE infosfinancieres (
    id serial NOT NULL,
    dossier_rsa_id integer NOT NULL,
    moismoucompta date,
    type_allocation character varying(25),
    natpfcre character(3),
    rgcre integer,
    numintmoucompta integer,
    typeopecompta character(3),
    sensopecompta character(2),
    mtmoucompta numeric(11,2),
    ddregu date,
    dttraimoucompta date,
    heutraimoucompta date
);


ALTER TABLE public.infosfinancieres OWNER TO webrsa;

--
-- Name: infosfinancieres_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('infosfinancieres', 'id'), 1, false);


--
-- Name: jetons; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE jetons (
    id serial NOT NULL,
    dossier_id integer NOT NULL,
    php_sid character(32),
    user_id integer NOT NULL,
    created timestamp without time zone,
    modified timestamp without time zone
);


ALTER TABLE public.jetons OWNER TO webrsa;

--
-- Name: jetons_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('jetons', 'id'), 1, true);


--
-- Name: liberalites; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE liberalites (
    id serial NOT NULL,
    avispcgpersonne_id integer NOT NULL,
    mtlibernondecl numeric(9,2),
    dtabsdeclliber date
);


ALTER TABLE public.liberalites OWNER TO webrsa;

--
-- Name: liberalites_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('liberalites', 'id'), 1, false);


--
-- Name: modescontact; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE modescontact (
    id serial NOT NULL,
    foyer_id integer NOT NULL,
    numtel character varying(11),
    numposte character varying(4),
    nattel character(1),
    matetel character(3),
    autorutitel character(1),
    adrelec character varying(78),
    autorutiadrelec character(1)
);


ALTER TABLE public.modescontact OWNER TO webrsa;

--
-- Name: modescontact_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('modescontact', 'id'), 1, false);


--
-- Name: nataccosocfams; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE nataccosocfams (
    id serial NOT NULL,
    code character(4),
    name character varying(100)
);


ALTER TABLE public.nataccosocfams OWNER TO webrsa;

--
-- Name: nataccosocfams_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('nataccosocfams', 'id'), 5, false);


--
-- Name: nataccosocindis; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE nataccosocindis (
    id serial NOT NULL,
    code character(4),
    name character varying(100)
);


ALTER TABLE public.nataccosocindis OWNER TO webrsa;

--
-- Name: nataccosocindis_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('nataccosocindis', 'id'), 7, false);


--
-- Name: natmobs; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE natmobs (
    id serial NOT NULL,
    code character(4),
    name character varying(100)
);


ALTER TABLE public.natmobs OWNER TO webrsa;

--
-- Name: natmobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('natmobs', 'id'), 4, false);


--
-- Name: nivetus; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE nivetus (
    id serial NOT NULL,
    code character(4),
    name character varying(100)
);


ALTER TABLE public.nivetus OWNER TO webrsa;

--
-- Name: nivetus_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('nivetus', 'id'), 8, false);


--
-- Name: orientsstructs; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE orientsstructs (
    id serial NOT NULL,
    personne_id integer NOT NULL,
    typeorient_id integer,
    structurereferente_id integer,
    propo_algo integer,
    valid_cg boolean,
    date_propo date,
    date_valid date,
    statut_orient character varying(15),
    date_impression date
);


ALTER TABLE public.orientsstructs OWNER TO webrsa;

--
-- Name: orientsstructs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('orientsstructs', 'id'), 2, true);


--
-- Name: orientsstructs_servicesinstructeurs; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE orientsstructs_servicesinstructeurs (
    orientstruct_id integer NOT NULL,
    serviceinstructeur_id integer NOT NULL
);


ALTER TABLE public.orientsstructs_servicesinstructeurs OWNER TO webrsa;

--
-- Name: paiementsfoyers; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE paiementsfoyers (
    id serial NOT NULL,
    foyer_id integer NOT NULL,
    topverstie boolean,
    modepai character(2),
    topribconj boolean,
    titurib character(3),
    nomprenomtiturib character varying(24),
    etaban character(5),
    guiban character(5),
    numcomptban character(11),
    clerib smallint,
    comban character varying(24)
);


ALTER TABLE public.paiementsfoyers OWNER TO webrsa;

--
-- Name: paiementsfoyers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('paiementsfoyers', 'id'), 1, false);


--
-- Name: personnes; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE personnes (
    id serial NOT NULL,
    foyer_id integer NOT NULL,
    qual character varying(3),
    nom character varying(20),
    prenom character varying(15),
    nomnai character varying(20),
    prenom2 character varying(15),
    prenom3 character varying(15),
    nomcomnai character varying(26),
    dtnai date,
    rgnai integer,
    typedtnai character(1),
    nir character(15),
    topvalec boolean,
    sexe character(1),
    nati character(1),
    dtnati date,
    pieecpres character(1),
    idassedic character varying(8)
);


ALTER TABLE public.personnes OWNER TO webrsa;

--
-- Name: personnes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('personnes', 'id'), 2, true);


--
-- Name: prestations; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE prestations (
    personne_id integer,
    natprest character(3),
    rolepers character(3),
    topchapers boolean,
    toppersdrodevorsa boolean,
    id serial NOT NULL
);


ALTER TABLE public.prestations OWNER TO webrsa;

--
-- Name: prestations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('prestations', 'id'), 2, true);


--
-- Name: prestsform; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE prestsform (
    id serial NOT NULL,
    actioninsertion_id integer NOT NULL,
    refpresta_id integer NOT NULL,
    lib_presta character varying(32),
    date_presta date
);


ALTER TABLE public.prestsform OWNER TO webrsa;

--
-- Name: prestsform_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('prestsform', 'id'), 1, false);


--
-- Name: rattachements; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE rattachements (
    personne_id integer NOT NULL,
    rattache_id integer NOT NULL,
    typepar character(3)
);


ALTER TABLE public.rattachements OWNER TO webrsa;

--
-- Name: reducsrsa; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE reducsrsa (
    id serial NOT NULL,
    avispcgdroitrsa_id integer NOT NULL,
    mtredrsa numeric(9,2),
    ddredrsa date,
    dfredrsa date
);


ALTER TABLE public.reducsrsa OWNER TO webrsa;

--
-- Name: reducsrsa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('reducsrsa', 'id'), 1, false);


--
-- Name: referents; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE referents (
    id serial NOT NULL,
    structurereferente_id integer NOT NULL,
    nom character varying(28),
    prenom character varying(32),
    numero_poste character varying(14),
    email character varying(78),
    qual character varying(3)
);


ALTER TABLE public.referents OWNER TO webrsa;

--
-- Name: referents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('referents', 'id'), 1, true);


--
-- Name: refsprestas; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE refsprestas (
    id serial NOT NULL,
    nomrefpresta character varying(28),
    prenomrefpresta character varying(32),
    emailrefpresta character varying(78),
    numero_posterefpresta character varying(4)
);


ALTER TABLE public.refsprestas OWNER TO webrsa;

--
-- Name: refsprestas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('refsprestas', 'id'), 1, false);


--
-- Name: regroupementszonesgeo; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE regroupementszonesgeo (
    id serial NOT NULL,
    lib_rgpt character varying(50)
);


ALTER TABLE public.regroupementszonesgeo OWNER TO webrsa;

--
-- Name: regroupementszonesgeo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('regroupementszonesgeo', 'id'), 1, false);


--
-- Name: ressources; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE ressources (
    id serial NOT NULL,
    personne_id integer NOT NULL,
    topressnul boolean,
    mtpersressmenrsa numeric(10,2),
    ddress date,
    dfress date
);


ALTER TABLE public.ressources OWNER TO webrsa;

--
-- Name: ressources_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('ressources', 'id'), 2, true);


--
-- Name: ressources_ressourcesmensuelles; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE ressources_ressourcesmensuelles (
    ressourcemensuelle_id integer NOT NULL,
    ressource_id integer NOT NULL
);


ALTER TABLE public.ressources_ressourcesmensuelles OWNER TO webrsa;

--
-- Name: ressourcesmensuelles; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE ressourcesmensuelles (
    id serial NOT NULL,
    ressource_id integer NOT NULL,
    moisress date,
    nbheumentra integer,
    mtabaneu numeric(9,2)
);


ALTER TABLE public.ressourcesmensuelles OWNER TO webrsa;

--
-- Name: ressourcesmensuelles_detailsressourcesmensuelles; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE ressourcesmensuelles_detailsressourcesmensuelles (
    detailressourcemensuelle_id integer NOT NULL,
    ressourcemensuelle_id integer NOT NULL
);


ALTER TABLE public.ressourcesmensuelles_detailsressourcesmensuelles OWNER TO webrsa;

--
-- Name: ressourcesmensuelles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('ressourcesmensuelles', 'id'), 1, false);


--
-- Name: servicesinstructeurs; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE servicesinstructeurs (
    id serial NOT NULL,
    lib_service character varying(100),
    num_rue character varying(6),
    nom_rue character varying(100),
    complement_adr character varying(38),
    code_insee character(5),
    code_postal character(5),
    ville character varying(26),
    numdepins character(3),
    typeserins character(1),
    numcomins character(3),
    numagrins integer,
    type_voie character varying(6)
);


ALTER TABLE public.servicesinstructeurs OWNER TO webrsa;

--
-- Name: servicesinstructeurs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('servicesinstructeurs', 'id'), 3, false);


--
-- Name: situationsdossiersrsa; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE situationsdossiersrsa (
    id serial NOT NULL,
    dossier_rsa_id integer NOT NULL,
    etatdosrsa character(1),
    dtrefursa date,
    moticlorsa character(3),
    dtclorsa date
);


ALTER TABLE public.situationsdossiersrsa OWNER TO webrsa;

--
-- Name: situationsdossiersrsa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('situationsdossiersrsa', 'id'), 1, false);


--
-- Name: structuresreferentes; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE structuresreferentes (
    id serial NOT NULL,
    typeorient_id integer NOT NULL,
    lib_struc character varying(100) NOT NULL,
    num_voie character varying(6) NOT NULL,
    type_voie character varying(6) NOT NULL,
    nom_voie character varying(30) NOT NULL,
    code_postal character(5) NOT NULL,
    ville character varying(45) NOT NULL,
    code_insee character(5)
);


ALTER TABLE public.structuresreferentes OWNER TO webrsa;

--
-- Name: structuresreferentes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('structuresreferentes', 'id'), 6, false);


--
-- Name: structuresreferentes_zonesgeographiques; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE structuresreferentes_zonesgeographiques (
    structurereferente_id integer NOT NULL,
    zonegeographique_id integer NOT NULL
);


ALTER TABLE public.structuresreferentes_zonesgeographiques OWNER TO webrsa;

--
-- Name: suivisinstruction; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE suivisinstruction (
    id serial NOT NULL,
    dossier_rsa_id integer NOT NULL,
    etatirsa character(2),
    date_etat_instruction date,
    nomins character varying(28),
    prenomins character varying(32),
    numdepins character(3),
    typeserins character(1),
    numcomins character(3),
    numagrins integer
);


ALTER TABLE public.suivisinstruction OWNER TO webrsa;

--
-- Name: suivisinstruction_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('suivisinstruction', 'id'), 1, true);


--
-- Name: suspensionsdroits; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE suspensionsdroits (
    id serial NOT NULL,
    situationdossierrsa_id integer NOT NULL,
    motisusdrorsa character(2),
    ddsusdrorsa date
);


ALTER TABLE public.suspensionsdroits OWNER TO webrsa;

--
-- Name: suspensionsdroits_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('suspensionsdroits', 'id'), 1, false);


--
-- Name: suspensionsversements; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE suspensionsversements (
    id serial NOT NULL,
    situationdossierrsa_id integer NOT NULL,
    motisusversrsa character(2),
    ddsusversrsa date
);


ALTER TABLE public.suspensionsversements OWNER TO webrsa;

--
-- Name: suspensionsversements_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('suspensionsversements', 'id'), 1, false);


--
-- Name: titres_sejour; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE titres_sejour (
    id serial NOT NULL,
    personne_id integer NOT NULL,
    dtentfra date,
    nattitsej character(3),
    menttitsej character(2),
    ddtitsej date,
    dftitsej date,
    numtitsej character varying(10),
    numduptitsej integer
);


ALTER TABLE public.titres_sejour OWNER TO webrsa;

--
-- Name: titres_sejour_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('titres_sejour', 'id'), 1, false);


--
-- Name: totalisationsacomptes; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE totalisationsacomptes (
    id serial NOT NULL,
    identificationflux_id integer NOT NULL,
    type_totalisation character varying(30),
    mttotsoclrsa numeric(12,2),
    mttotsoclmajorsa numeric(12,2),
    mttotlocalrsa numeric(12,2),
    mttotrsa numeric(12,2)
);


ALTER TABLE public.totalisationsacomptes OWNER TO webrsa;

--
-- Name: totalisationsacomptes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('totalisationsacomptes', 'id'), 1, false);


--
-- Name: typesactions; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE typesactions (
    id serial NOT NULL,
    libelle character varying(250)
);


ALTER TABLE public.typesactions OWNER TO webrsa;

--
-- Name: typesactions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('typesactions', 'id'), 6, false);


--
-- Name: typesorients; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE typesorients (
    id serial NOT NULL,
    parentid integer,
    lib_type_orient character varying(30),
    modele_notif character varying(40)
);


ALTER TABLE public.typesorients OWNER TO webrsa;

--
-- Name: typesorients_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('typesorients', 'id'), 11, false);


--
-- Name: typoscontrats; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE typoscontrats (
    id serial NOT NULL,
    lib_typo character varying(20)
);


ALTER TABLE public.typoscontrats OWNER TO webrsa;

--
-- Name: typoscontrats_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('typoscontrats', 'id'), 4, false);


--
-- Name: users; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE users (
    id serial NOT NULL,
    group_id integer DEFAULT 0 NOT NULL,
    serviceinstructeur_id integer NOT NULL,
    username character varying(50) NOT NULL,
    "password" character varying(50) NOT NULL,
    nom character varying(50),
    prenom character varying(50),
    date_naissance date,
    date_deb_hab date,
    date_fin_hab date,
    numtel character varying(15),
    filtre_zone_geo boolean DEFAULT true
);


ALTER TABLE public.users OWNER TO webrsa;

--
-- Name: users_contratsinsertion; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE users_contratsinsertion (
    user_id integer NOT NULL,
    contratinsertion_id integer NOT NULL
);


ALTER TABLE public.users_contratsinsertion OWNER TO webrsa;

--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('users', 'id'), 7, true);


--
-- Name: users_zonesgeographiques; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE users_zonesgeographiques (
    user_id integer NOT NULL,
    zonegeographique_id integer NOT NULL,
    id serial NOT NULL
);


ALTER TABLE public.users_zonesgeographiques OWNER TO webrsa;

--
-- Name: users_zonesgeographiques_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('users_zonesgeographiques', 'id'), 6, true);


--
-- Name: zonesgeographiques; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE zonesgeographiques (
    id serial NOT NULL,
    codeinsee character(5) NOT NULL,
    libelle character varying(50) NOT NULL
);


ALTER TABLE public.zonesgeographiques OWNER TO webrsa;

--
-- Name: zonesgeographiques_id_seq; Type: SEQUENCE SET; Schema: public; Owner: webrsa
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('zonesgeographiques', 'id'), 4, false);


--
-- Name: zonesgeographiques_regroupementszonesgeo; Type: TABLE; Schema: public; Owner: webrsa; Tablespace:
--

CREATE TABLE zonesgeographiques_regroupementszonesgeo (
    zonegeographique_id integer NOT NULL,
    regroupementzonegeo_id integer NOT NULL
);


ALTER TABLE public.zonesgeographiques_regroupementszonesgeo OWNER TO webrsa;

--
-- Data for Name: accoemplois; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO accoemplois VALUES (1, '1801', 'Pas d''accompagnement');
INSERT INTO accoemplois VALUES (2, '1802', 'Pole emploi');
INSERT INTO accoemplois VALUES (3, '1803', 'Autres');


--
-- Data for Name: acos; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO acos VALUES (4871, 0, '', 0, 'Dossiers:index', 1, 2);
INSERT INTO acos VALUES (4872, 0, '', 0, 'Droits', 3, 6);
INSERT INTO acos VALUES (4873, 4872, '', 0, 'Droits:edit', 4, 5);
INSERT INTO acos VALUES (4875, 4874, '', 0, 'Gedooos:notification_structure', 8, 9);
INSERT INTO acos VALUES (4876, 4874, '', 0, 'Gedooos:contratinsertion', 10, 11);
INSERT INTO acos VALUES (4877, 4874, '', 0, 'Gedooos:orientstruct', 12, 13);
INSERT INTO acos VALUES (4874, 0, '', 0, 'Gedooos', 7, 16);
INSERT INTO acos VALUES (4878, 4874, '', 0, 'Gedooos:notifications_cohortes', 14, 15);
INSERT INTO acos VALUES (4880, 4879, '', 0, 'Typoscontrats:index', 18, 19);
INSERT INTO acos VALUES (4881, 4879, '', 0, 'Typoscontrats:add', 20, 21);
INSERT INTO acos VALUES (4882, 4879, '', 0, 'Typoscontrats:edit', 22, 23);
INSERT INTO acos VALUES (4879, 0, '', 0, 'Typoscontrats', 17, 26);
INSERT INTO acos VALUES (4883, 4879, '', 0, 'Typoscontrats:delete', 24, 25);
INSERT INTO acos VALUES (4885, 4884, '', 0, 'Ressources:index', 28, 29);
INSERT INTO acos VALUES (4886, 4884, '', 0, 'Ressources:view', 30, 31);
INSERT INTO acos VALUES (4887, 4884, '', 0, 'Ressources:add', 32, 33);
INSERT INTO acos VALUES (4884, 0, '', 0, 'Ressources', 27, 36);
INSERT INTO acos VALUES (4888, 4884, '', 0, 'Ressources:edit', 34, 35);
INSERT INTO acos VALUES (4890, 4889, '', 0, 'Orientsstructs:index', 38, 39);
INSERT INTO acos VALUES (4891, 4889, '', 0, 'Orientsstructs:add', 40, 41);
INSERT INTO acos VALUES (4889, 0, '', 0, 'Orientsstructs', 37, 44);
INSERT INTO acos VALUES (4892, 4889, '', 0, 'Orientsstructs:edit', 42, 43);
INSERT INTO acos VALUES (4894, 4893, '', 0, 'Informationseti:index', 46, 47);
INSERT INTO acos VALUES (4893, 0, '', 0, 'Informationseti', 45, 50);
INSERT INTO acos VALUES (4895, 4893, '', 0, 'Informationseti:view', 48, 49);
INSERT INTO acos VALUES (4897, 4896, '', 0, 'Servicesinstructeurs:index', 52, 53);
INSERT INTO acos VALUES (4898, 4896, '', 0, 'Servicesinstructeurs:add', 54, 55);
INSERT INTO acos VALUES (4899, 4896, '', 0, 'Servicesinstructeurs:edit', 56, 57);
INSERT INTO acos VALUES (4896, 0, '', 0, 'Servicesinstructeurs', 51, 60);
INSERT INTO acos VALUES (4900, 4896, '', 0, 'Servicesinstructeurs:delete', 58, 59);
INSERT INTO acos VALUES (4902, 4901, '', 0, 'Users:index', 62, 63);
INSERT INTO acos VALUES (4903, 4901, '', 0, 'Users:add', 64, 65);
INSERT INTO acos VALUES (4904, 4901, '', 0, 'Users:edit', 66, 67);
INSERT INTO acos VALUES (4901, 0, '', 0, 'Users', 61, 70);
INSERT INTO acos VALUES (4905, 4901, '', 0, 'Users:delete', 68, 69);
INSERT INTO acos VALUES (4907, 4906, '', 0, 'Aidesdirectes:add', 72, 73);
INSERT INTO acos VALUES (4906, 0, '', 0, 'Aidesdirectes', 71, 76);
INSERT INTO acos VALUES (4908, 4906, '', 0, 'Aidesdirectes:edit', 74, 75);
INSERT INTO acos VALUES (4910, 4909, '', 0, 'Typesorients:index', 78, 79);
INSERT INTO acos VALUES (4911, 4909, '', 0, 'Typesorients:add', 80, 81);
INSERT INTO acos VALUES (4912, 4909, '', 0, 'Typesorients:edit', 82, 83);
INSERT INTO acos VALUES (4909, 0, '', 0, 'Typesorients', 77, 86);
INSERT INTO acos VALUES (4913, 4909, '', 0, 'Typesorients:delete', 84, 85);
INSERT INTO acos VALUES (4914, 0, '', 0, 'Totalisationsacomptes', 87, 90);
INSERT INTO acos VALUES (4915, 4914, '', 0, 'Totalisationsacomptes:index', 88, 89);
INSERT INTO acos VALUES (4917, 4916, '', 0, 'Grossesses:index', 92, 93);
INSERT INTO acos VALUES (4916, 0, '', 0, 'Grossesses', 91, 96);
INSERT INTO acos VALUES (4918, 4916, '', 0, 'Grossesses:view', 94, 95);
INSERT INTO acos VALUES (4920, 4919, '', 0, 'Suivisinstruction:index', 98, 99);
INSERT INTO acos VALUES (4919, 0, '', 0, 'Suivisinstruction', 97, 102);
INSERT INTO acos VALUES (4921, 4919, '', 0, 'Suivisinstruction:view', 100, 101);
INSERT INTO acos VALUES (4923, 4922, '', 0, 'Parametrages:index', 104, 105);
INSERT INTO acos VALUES (4924, 4922, '', 0, 'Parametrages:view', 106, 107);
INSERT INTO acos VALUES (4922, 0, '', 0, 'Parametrages', 103, 110);
INSERT INTO acos VALUES (4925, 4922, '', 0, 'Parametrages:edit', 108, 109);
INSERT INTO acos VALUES (4927, 4926, '', 0, 'Infosfinancieres:index', 112, 113);
INSERT INTO acos VALUES (4926, 0, '', 0, 'Infosfinancieres', 111, 116);
INSERT INTO acos VALUES (4928, 4926, '', 0, 'Infosfinancieres:view', 114, 115);
INSERT INTO acos VALUES (4930, 4929, '', 0, 'Cohortes:nouvelles', 118, 119);
INSERT INTO acos VALUES (4931, 4929, '', 0, 'Cohortes:orientees', 120, 121);
INSERT INTO acos VALUES (4929, 0, '', 0, 'Cohortes', 117, 124);
INSERT INTO acos VALUES (4932, 4929, '', 0, 'Cohortes:enattente', 122, 123);
INSERT INTO acos VALUES (4934, 4933, '', 0, 'Structuresreferentes:index', 126, 127);
INSERT INTO acos VALUES (4935, 4933, '', 0, 'Structuresreferentes:add', 128, 129);
INSERT INTO acos VALUES (4936, 4933, '', 0, 'Structuresreferentes:edit', 130, 131);
INSERT INTO acos VALUES (4933, 0, '', 0, 'Structuresreferentes', 125, 134);
INSERT INTO acos VALUES (4937, 4933, '', 0, 'Structuresreferentes:delete', 132, 133);
INSERT INTO acos VALUES (4939, 4938, '', 0, 'Adressesfoyers:index', 136, 137);
INSERT INTO acos VALUES (4940, 4938, '', 0, 'Adressesfoyers:view', 138, 139);
INSERT INTO acos VALUES (4941, 4938, '', 0, 'Adressesfoyers:edit', 140, 141);
INSERT INTO acos VALUES (4938, 0, '', 0, 'Adressesfoyers', 135, 144);
INSERT INTO acos VALUES (4942, 4938, '', 0, 'Adressesfoyers:add', 142, 143);
INSERT INTO acos VALUES (4944, 4943, '', 0, 'Modescontact:index', 146, 147);
INSERT INTO acos VALUES (4945, 4943, '', 0, 'Modescontact:add', 148, 149);
INSERT INTO acos VALUES (4946, 4943, '', 0, 'Modescontact:edit', 150, 151);
INSERT INTO acos VALUES (4943, 0, '', 0, 'Modescontact', 145, 154);
INSERT INTO acos VALUES (4947, 4943, '', 0, 'Modescontact:view', 152, 153);
INSERT INTO acos VALUES (4949, 4948, '', 0, 'Situationsdossiersrsa:index', 156, 157);
INSERT INTO acos VALUES (4948, 0, '', 0, 'Situationsdossiersrsa', 155, 160);
INSERT INTO acos VALUES (4950, 4948, '', 0, 'Situationsdossiersrsa:view', 158, 159);
INSERT INTO acos VALUES (4952, 4951, '', 0, 'Groups:index', 162, 163);
INSERT INTO acos VALUES (4953, 4951, '', 0, 'Groups:add', 164, 165);
INSERT INTO acos VALUES (4954, 4951, '', 0, 'Groups:edit', 166, 167);
INSERT INTO acos VALUES (4951, 0, '', 0, 'Groups', 161, 170);
INSERT INTO acos VALUES (4955, 4951, '', 0, 'Groups:delete', 168, 169);
INSERT INTO acos VALUES (4956, 0, '', 0, 'Criteres', 171, 174);
INSERT INTO acos VALUES (4957, 4956, '', 0, 'Criteres:index', 172, 173);
INSERT INTO acos VALUES (4958, 0, '', 0, 'Dossiers', 175, 178);
INSERT INTO acos VALUES (4959, 4958, '', 0, 'Dossiers:view', 176, 177);
INSERT INTO acos VALUES (4961, 4960, '', 0, 'Ajoutdossiers:confirm', 180, 181);
INSERT INTO acos VALUES (4960, 0, '', 0, 'Ajoutdossiers', 179, 184);
INSERT INTO acos VALUES (4962, 4960, '', 0, 'Ajoutdossiers:wizard', 182, 183);
INSERT INTO acos VALUES (4964, 4963, '', 0, 'Infosagricoles:index', 186, 187);
INSERT INTO acos VALUES (4963, 0, '', 0, 'Infosagricoles', 185, 190);
INSERT INTO acos VALUES (4965, 4963, '', 0, 'Infosagricoles:view', 188, 189);
INSERT INTO acos VALUES (4967, 4966, '', 0, 'Dspps:view', 192, 193);
INSERT INTO acos VALUES (4968, 4966, '', 0, 'Dspps:add', 194, 195);
INSERT INTO acos VALUES (4966, 0, '', 0, 'Dspps', 191, 198);
INSERT INTO acos VALUES (4969, 4966, '', 0, 'Dspps:edit', 196, 197);
INSERT INTO acos VALUES (4971, 4970, '', 0, 'Contratsinsertion:index', 200, 201);
INSERT INTO acos VALUES (4972, 4970, '', 0, 'Contratsinsertion:test2', 202, 203);
INSERT INTO acos VALUES (4973, 4970, '', 0, 'Contratsinsertion:view', 204, 205);
INSERT INTO acos VALUES (4974, 4970, '', 0, 'Contratsinsertion:add', 206, 207);
INSERT INTO acos VALUES (4975, 4970, '', 0, 'Contratsinsertion:edit', 208, 209);
INSERT INTO acos VALUES (4970, 0, '', 0, 'Contratsinsertion', 199, 212);
INSERT INTO acos VALUES (4976, 4970, '', 0, 'Contratsinsertion:valider', 210, 211);
INSERT INTO acos VALUES (4978, 4977, '', 0, 'Actionsinsertion:index', 214, 215);
INSERT INTO acos VALUES (4977, 0, '', 0, 'Actionsinsertion', 213, 218);
INSERT INTO acos VALUES (4979, 4977, '', 0, 'Actionsinsertion:edit', 216, 217);
INSERT INTO acos VALUES (4981, 4980, '', 0, 'Prestsform:add', 220, 221);
INSERT INTO acos VALUES (4980, 0, '', 0, 'Prestsform', 219, 224);
INSERT INTO acos VALUES (4982, 4980, '', 0, 'Prestsform:edit', 222, 223);
INSERT INTO acos VALUES (4984, 4983, '', 0, 'Regroupementszonesgeo:index', 226, 227);
INSERT INTO acos VALUES (4985, 4983, '', 0, 'Regroupementszonesgeo:add', 228, 229);
INSERT INTO acos VALUES (4986, 4983, '', 0, 'Regroupementszonesgeo:edit', 230, 231);
INSERT INTO acos VALUES (4983, 0, '', 0, 'Regroupementszonesgeo', 225, 234);
INSERT INTO acos VALUES (4987, 4983, '', 0, 'Regroupementszonesgeo:delete', 232, 233);
INSERT INTO acos VALUES (4989, 4988, '', 0, 'Avispcgdroitrsa:index', 236, 237);
INSERT INTO acos VALUES (4988, 0, '', 0, 'Avispcgdroitrsa', 235, 240);
INSERT INTO acos VALUES (4990, 4988, '', 0, 'Avispcgdroitrsa:view', 238, 239);
INSERT INTO acos VALUES (4992, 4991, '', 0, 'Zonesgeographiques:index', 242, 243);
INSERT INTO acos VALUES (4993, 4991, '', 0, 'Zonesgeographiques:add', 244, 245);
INSERT INTO acos VALUES (4994, 4991, '', 0, 'Zonesgeographiques:edit', 246, 247);
INSERT INTO acos VALUES (4991, 0, '', 0, 'Zonesgeographiques', 241, 250);
INSERT INTO acos VALUES (4995, 4991, '', 0, 'Zonesgeographiques:delete', 248, 249);
INSERT INTO acos VALUES (4997, 4996, '', 0, 'Dossierssimplifies:view', 252, 253);
INSERT INTO acos VALUES (4998, 4996, '', 0, 'Dossierssimplifies:add', 254, 255);
INSERT INTO acos VALUES (4996, 0, '', 0, 'Dossierssimplifies', 251, 258);
INSERT INTO acos VALUES (4999, 4996, '', 0, 'Dossierssimplifies:edit', 256, 257);
INSERT INTO acos VALUES (5001, 5000, '', 0, 'Dspfs:view', 260, 261);
INSERT INTO acos VALUES (5002, 5000, '', 0, 'Dspfs:add', 262, 263);
INSERT INTO acos VALUES (5000, 0, '', 0, 'Dspfs', 259, 266);
INSERT INTO acos VALUES (5003, 5000, '', 0, 'Dspfs:edit', 264, 265);
INSERT INTO acos VALUES (5005, 5004, '', 0, 'Personnes:index', 268, 269);
INSERT INTO acos VALUES (5006, 5004, '', 0, 'Personnes:view', 270, 271);
INSERT INTO acos VALUES (5007, 5004, '', 0, 'Personnes:add', 272, 273);
INSERT INTO acos VALUES (5004, 0, '', 0, 'Personnes', 267, 276);
INSERT INTO acos VALUES (5008, 5004, '', 0, 'Personnes:edit', 274, 275);
INSERT INTO acos VALUES (5010, 5009, '', 0, 'Referents:index', 278, 279);
INSERT INTO acos VALUES (5011, 5009, '', 0, 'Referents:add', 280, 281);
INSERT INTO acos VALUES (5012, 5009, '', 0, 'Referents:edit', 282, 283);
INSERT INTO acos VALUES (5009, 0, '', 0, 'Referents', 277, 286);
INSERT INTO acos VALUES (5013, 5009, '', 0, 'Referents:delete', 284, 285);
INSERT INTO acos VALUES (5014, 0, '', 0, 'Criteresci', 287, 290);
INSERT INTO acos VALUES (5015, 5014, '', 0, 'Criteresci:index', 288, 289);
INSERT INTO acos VALUES (5017, 5016, '', 0, 'Detailsdroitsrsa:index', 292, 293);
INSERT INTO acos VALUES (5016, 0, '', 0, 'Detailsdroitsrsa', 291, 296);
INSERT INTO acos VALUES (5018, 5016, '', 0, 'Detailsdroitsrsa:view', 294, 295);


--
-- Data for Name: actions; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO actions VALUES (1, 1, '1P', 'Soutien, suivi social, accompagnement personnel');
INSERT INTO actions VALUES (2, 1, '1F', 'Soutien, suivi social, accompagnement familial');
INSERT INTO actions VALUES (3, 1, '02', 'Aide au retour d''enfants placés');
INSERT INTO actions VALUES (4, 1, '03', 'Soutien éducatif lié aux enfants');
INSERT INTO actions VALUES (5, 1, '04', 'Aide pour la garde des enfants');
INSERT INTO actions VALUES (6, 1, '05', 'Aide financière liée au logement');
INSERT INTO actions VALUES (7, 1, '06', 'Autre aide liée au logement');
INSERT INTO actions VALUES (8, 1, '07', 'Prise en charge financière des frais de formation (y compris stage de conduite automobile)');
INSERT INTO actions VALUES (9, 1, '10', 'Autre facilité offerte');
INSERT INTO actions VALUES (10, 2, '21', 'Démarche liée à la santé');
INSERT INTO actions VALUES (11, 2, '22', 'Alphabétisation, lutte contre l''illétrisme');
INSERT INTO actions VALUES (12, 2, '23', 'Organisation quotidienne');
INSERT INTO actions VALUES (13, 2, '24', 'Démarches administratives (COTOREP, demande d''AAH, de retraite, etc...)');
INSERT INTO actions VALUES (14, 2, '26', 'Bilan social');
INSERT INTO actions VALUES (15, 2, '29', 'Autre action visant à l''autonomie sociale');
INSERT INTO actions VALUES (16, 3, '31', 'Recherche d''un logement');
INSERT INTO actions VALUES (17, 3, '33', 'Demande d''intervention d''un organisme ou d''un fonds d''aide');
INSERT INTO actions VALUES (18, 4, '41', 'Aide ou suivi pour une recherche de stage ou de formation');
INSERT INTO actions VALUES (19, 4, '42', 'Activité en atelier de réinsertion (centre d''hébergement et de réadaptation sociale)');
INSERT INTO actions VALUES (20, 4, '43', 'Chantier école');
INSERT INTO actions VALUES (21, 4, '44', 'Stage de conduite automobile (véhicules légers)');
INSERT INTO actions VALUES (22, 4, '45', 'Stage de formation générale, préparation aux concours, poursuite d''études, etc...');
INSERT INTO actions VALUES (23, 4, '46', 'Stage de formation professionnelle (stage d''insertion et de formation à l''emploi, permis poids lourd, crédit-formation individuel, etc...)');
INSERT INTO actions VALUES (24, 4, '48', 'Bilan professionnel et orientation (évaluation du niveau de compétences professionnelles, module d''orientation approfondie, session d''oientation approfondie, évaluation en milieu de travail, VAE, etc...)');
INSERT INTO actions VALUES (25, 5, '51', 'Aide ou suivi pour une recherche d''emploi');
INSERT INTO actions VALUES (26, 5, '52', 'Contrat initiative emploi');
INSERT INTO actions VALUES (27, 5, '53', 'Contrat de qualification, contrat d''apprentissage');
INSERT INTO actions VALUES (28, 5, '54', 'Emploi dans une association intermédiaire ou une entreprise d''insertion');
INSERT INTO actions VALUES (29, 5, '55', 'Création d''entreprise');
INSERT INTO actions VALUES (30, 5, '56', 'Contrats aidés, Contrat d''Avenir, CIRMA');
INSERT INTO actions VALUES (31, 5, '57', 'Emploi consolidé: CDI');
INSERT INTO actions VALUES (32, 5, '58', 'Emploi familial, service de proximité');
INSERT INTO actions VALUES (33, 5, '59', 'Autre forme d''emploi: CDD, CNE');


--
-- Data for Name: actionsinsertion; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: activites; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: adresses; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO adresses VALUES (1, '30', 'R', 'du pilier droit', '', '', '', '     ', '34080', '34000', 'Montpellier', 'FRA', '');


--
-- Data for Name: adresses_foyers; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO adresses_foyers VALUES (1, 1, 1, '01', NULL, 'D');


--
-- Data for Name: aidesagricoles; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: aidesdirectes; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: allocationssoutienfamilial; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: aros; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO aros VALUES (2, 1, '', 4, 'Utilisateur:cg66', 2, 3);
INSERT INTO aros VALUES (3, 1, '', 5, 'Utilisateur:cg93', 4, 5);
INSERT INTO aros VALUES (4, 1, '', 6, 'Utilisateur:webrsa', 6, 7);
INSERT INTO aros VALUES (1, NULL, '', 0, 'Group:Administrateurs', 1, 12);
INSERT INTO aros VALUES (5, 1, '', 0, 'Group:Sous_Administrateurs', 8, 11);
INSERT INTO aros VALUES (6, 5, '', 3, 'Utilisateur:cg58', 9, 10);
INSERT INTO aros VALUES (8, 7, '', 1, 'Utilisateur:cg23', 14, 15);
INSERT INTO aros VALUES (7, NULL, '', 0, 'Group:Utilisateurs', 13, 18);
INSERT INTO aros VALUES (9, 7, '', 2, 'Utilisateur:cg54', 16, 17);


--
-- Data for Name: aros_acos; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO aros_acos VALUES (2969, 1, 4871, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2970, 1, 4872, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2971, 1, 4874, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2972, 1, 4879, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2973, 1, 4884, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2974, 1, 4889, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2975, 1, 4893, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2976, 1, 4896, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2977, 1, 4901, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2978, 1, 4906, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2979, 1, 4909, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2980, 1, 4914, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2981, 1, 4916, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2982, 1, 4919, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2983, 1, 4922, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2984, 1, 4926, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2985, 1, 4929, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2986, 1, 4933, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2987, 1, 4938, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2988, 1, 4943, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2989, 1, 4948, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2990, 1, 4951, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2991, 1, 4956, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2992, 1, 4958, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2993, 1, 4960, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2994, 1, 4963, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2995, 1, 4966, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2996, 1, 4970, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2997, 1, 4977, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2998, 1, 4980, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (2999, 1, 4983, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (3000, 1, 4988, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (3001, 1, 4991, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (3002, 1, 4996, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (3003, 1, 5000, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (3004, 1, 5004, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (3005, 1, 5009, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (3006, 1, 5014, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (3007, 1, 5016, '1 ', '1 ', '1 ', '1 ');
INSERT INTO aros_acos VALUES (3008, 7, 4871, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3009, 7, 4872, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3010, 7, 4874, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3011, 7, 4879, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3012, 7, 4884, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3013, 7, 4889, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3014, 7, 4893, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3015, 7, 4896, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3016, 7, 4901, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3017, 7, 4906, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3018, 7, 4909, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3019, 7, 4914, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3020, 7, 4916, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3021, 7, 4919, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3022, 7, 4922, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3023, 7, 4926, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3024, 7, 4929, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3025, 7, 4933, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3026, 7, 4938, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3027, 7, 4943, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3028, 7, 4948, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3029, 7, 4951, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3030, 7, 4956, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3031, 7, 4958, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3032, 7, 4960, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3033, 7, 4963, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3034, 7, 4966, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3035, 7, 4970, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3036, 7, 4977, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3037, 7, 4980, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3038, 7, 4983, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3039, 7, 4988, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3040, 7, 4991, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3041, 7, 4996, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3042, 7, 5000, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3043, 7, 5004, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3044, 7, 5009, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3045, 7, 5014, '-1', '-1', '-1', '-1');
INSERT INTO aros_acos VALUES (3046, 7, 5016, '-1', '-1', '-1', '-1');


--
-- Data for Name: avispcgdroitrsa; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: avispcgpersonnes; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: condsadmins; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: connections; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO connections VALUES (11, 5, '66d2fdf0ea47bdfbdb485cfb3337c2f8', '2009-07-06 17:42:05', '2009-07-06 17:42:05');
INSERT INTO connections VALUES (14, 4, 'a6c39d7572187bcedac272b572937f89', '2009-07-06 17:53:10', '2009-07-06 17:53:10');


--
-- Data for Name: contratsinsertion; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: creances; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: creancesalimentaires; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: creancesalimentaires_personnes; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: derogations; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: detailscalculsdroitsrsa; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: detailsdroitsrsa; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO detailsdroitsrsa VALUES (1, 1, NULL, NULL, 'DEM', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);


--
-- Data for Name: detailsressourcesmensuelles; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: difdisps; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO difdisps VALUES (1, '0501', 'Aucune difficulté');
INSERT INTO difdisps VALUES (2, '0502', 'La garde d''enfant de moins de 6 ans');
INSERT INTO difdisps VALUES (3, '0503', 'La garde d''enfant(s) de plus de 6 ans');
INSERT INTO difdisps VALUES (4, '0504', 'La garde d''enfant(s) ou de proche(s) invalide(s)');
INSERT INTO difdisps VALUES (5, '0505', 'La charge de proche(s) dépendant(s)');


--
-- Data for Name: diflogs; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO diflogs VALUES (1, '1001', 'Pas de difficultés');
INSERT INTO diflogs VALUES (2, '1002', 'Impayés de loyer ou de remboursement');
INSERT INTO diflogs VALUES (3, '1003', 'Problèmes financiers');
INSERT INTO diflogs VALUES (4, '1004', 'Qualité du logement (insalubrité, indécence)');
INSERT INTO diflogs VALUES (5, '1005', 'Qualité de l''environnement (isolement, absence de transport collectif)');
INSERT INTO diflogs VALUES (6, '1006', 'Fin de bail, expulsion');
INSERT INTO diflogs VALUES (7, '1007', 'Conditions de logement (surpeuplement)');
INSERT INTO diflogs VALUES (8, '1008', 'Eloignement entre le lieu de résidence et le lieu de travail');
INSERT INTO diflogs VALUES (9, '1009', 'Autres');


--
-- Data for Name: difsocs; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO difsocs VALUES (1, '0401', 'Aucune difficulté');
INSERT INTO difsocs VALUES (2, '0402', 'Santé');
INSERT INTO difsocs VALUES (3, '0403', 'Reconnaissance de la qualité du travailleur handicapé');
INSERT INTO difsocs VALUES (4, '0404', 'Lecture, écriture ou compréhension du fançais');
INSERT INTO difsocs VALUES (5, '0405', 'Démarches et formalités administratives');
INSERT INTO difsocs VALUES (6, '0406', 'Endettement');
INSERT INTO difsocs VALUES (7, '0407', 'Autres');


--
-- Data for Name: dossiers_rsa; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO dossiers_rsa VALUES (1, '11111111111', '2009-07-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);


--
-- Data for Name: dossierscaf; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: dspfs; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: dspfs_diflogs; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: dspfs_nataccosocfams; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: dspps; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: dspps_accoemplois; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: dspps_difdisps; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: dspps_difsocs; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: dspps_nataccosocindis; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: dspps_natmobs; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: dspps_nivetus; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: evenements; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: foyers; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO foyers VALUES (1, 1, NULL, NULL, NULL, NULL, NULL, NULL);


--
-- Data for Name: foyers_creances; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: foyers_evenements; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: grossesses; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: groups; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO groups VALUES (1, 'Administrateurs', 0);
INSERT INTO groups VALUES (2, 'Utilisateurs', 0);
INSERT INTO groups VALUES (3, 'Sous_Administrateurs', 1);


--
-- Data for Name: identificationsflux; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: informationseti; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: infosagricoles; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: infosfinancieres; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: jetons; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO jetons VALUES (1, 1, '66d2fdf0ea47bdfbdb485cfb3337c2f8', 5, '2009-07-06 17:43:52', '2009-07-06 17:43:52');


--
-- Data for Name: liberalites; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: modescontact; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: nataccosocfams; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO nataccosocfams VALUES (1, '0410', 'Logement');
INSERT INTO nataccosocfams VALUES (2, '0411', 'Endettement');
INSERT INTO nataccosocfams VALUES (3, '0412', 'Familiale');
INSERT INTO nataccosocfams VALUES (4, '0413', 'Autres');


--
-- Data for Name: nataccosocindis; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO nataccosocindis VALUES (1, '0415', 'Pas d''accompagnement individuel');
INSERT INTO nataccosocindis VALUES (2, '0416', 'Santé');
INSERT INTO nataccosocindis VALUES (3, '0417', 'Emploi');
INSERT INTO nataccosocindis VALUES (4, '0418', 'Insertion professionnelle');
INSERT INTO nataccosocindis VALUES (5, '0419', 'Formation');
INSERT INTO nataccosocindis VALUES (6, '0420', 'Autres');


--
-- Data for Name: natmobs; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO natmobs VALUES (1, '2501', 'Sur la commune');
INSERT INTO natmobs VALUES (2, '2502', 'Sur le département');
INSERT INTO natmobs VALUES (3, '2503', 'Sur un autre département');


--
-- Data for Name: nivetus; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO nivetus VALUES (1, '1201', 'Niveau I/II: enseignement supérieur');
INSERT INTO nivetus VALUES (2, '1202', 'Niveau III: BAC + 2');
INSERT INTO nivetus VALUES (3, '1203', 'Niveau IV: BAC ou équivalent');
INSERT INTO nivetus VALUES (4, '1204', 'Niveau V: CAP/BEP');
INSERT INTO nivetus VALUES (5, '1205', 'Niveau Vbis: fin de scolarité obligatoire');
INSERT INTO nivetus VALUES (6, '1206', 'Niveau VI: pas de niveau');
INSERT INTO nivetus VALUES (7, '1207', 'Niveau VII: jamais scolarisé');


--
-- Data for Name: orientsstructs; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO orientsstructs VALUES (1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'Non orienté', NULL);
INSERT INTO orientsstructs VALUES (2, 2, NULL, NULL, NULL, NULL, NULL, NULL, 'Non orienté', NULL);


--
-- Data for Name: orientsstructs_servicesinstructeurs; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: paiementsfoyers; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: personnes; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO personnes VALUES (1, 1, 'MR', 'auzolat', 'arnaud', NULL, '', '', '', '1983-01-01', 1, 'J', '181093018910516', false, '1', 'F', NULL, 'E', NULL);
INSERT INTO personnes VALUES (2, 1, 'MLE', 'auzolat', 'lea', NULL, '', '', '', '1982-12-31', 2, 'O', '213548464646846', false, '2', 'F', NULL, 'E', NULL);


--
-- Data for Name: prestations; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO prestations VALUES (1, 'RSA', 'DEM', false, true, 1);
INSERT INTO prestations VALUES (2, 'RSA', 'CJT', false, true, 2);


--
-- Data for Name: prestsform; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: rattachements; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: reducsrsa; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: referents; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO referents VALUES (1, 2, 'Buffin', 'Christian', '0467676767', 'c.buffin@adullact.org', 'MR');


--
-- Data for Name: refsprestas; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: regroupementszonesgeo; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: ressources; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO ressources VALUES (1, 1, true, 0.00, '2009-01-01', '2009-03-31');
INSERT INTO ressources VALUES (2, 2, true, 0.00, '2008-06-01', '2008-08-31');


--
-- Data for Name: ressources_ressourcesmensuelles; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: ressourcesmensuelles; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: ressourcesmensuelles_detailsressourcesmensuelles; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: servicesinstructeurs; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO servicesinstructeurs VALUES (1, 'Service 1', '16', 'collines', '', '30900', '30000', 'Nimes', '001', 'A', '030', 2, 'CHE');
INSERT INTO servicesinstructeurs VALUES (2, 'Service 2', '775', 'moulin', '', '34080', '34000', 'Lattes', '002', 'G', '003', 3, 'R');


--
-- Data for Name: situationsdossiersrsa; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: structuresreferentes; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO structuresreferentes VALUES (1, 1, 'Pole emploi Mont Sud', '125', 'Avenue', 'Alco', '34090', 'Montpellier', '34095');
INSERT INTO structuresreferentes VALUES (5, 6, 'Organisme ACAL Vauvert', '48', 'AGL', 'Georges Freche', '30600', 'Vauvert', '30610');
INSERT INTO structuresreferentes VALUES (4, 4, 'Conseil Général de l''Hérault', '10', 'rue', 'Georges Freche', '34000', 'Montpellier', '34005');
INSERT INTO structuresreferentes VALUES (2, 1, 'Assedic Nimes', '44', 'chemin', 'Parrot', '30000', 'Nimes', '30009');
INSERT INTO structuresreferentes VALUES (3, 6, 'MSA du Gard', '48', 'R', 'Paul Condorcet', '30900', 'Nimes', '30000');


--
-- Data for Name: structuresreferentes_zonesgeographiques; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO structuresreferentes_zonesgeographiques VALUES (3, 1);
INSERT INTO structuresreferentes_zonesgeographiques VALUES (3, 2);
INSERT INTO structuresreferentes_zonesgeographiques VALUES (3, 3);


--
-- Data for Name: suivisinstruction; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO suivisinstruction VALUES (1, 1, '03', '2009-07-06', 'Dubois', 'Florent', '001', 'A', '030', 2);


--
-- Data for Name: suspensionsdroits; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: suspensionsversements; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: titres_sejour; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: totalisationsacomptes; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: typesactions; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO typesactions VALUES (1, 'Facilités offertes');
INSERT INTO typesactions VALUES (2, 'Autonomie sociale');
INSERT INTO typesactions VALUES (3, 'Logement');
INSERT INTO typesactions VALUES (4, 'Insertion professionnelle (stage, prestation, formation');
INSERT INTO typesactions VALUES (5, 'Emploi');


--
-- Data for Name: typesorients; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO typesorients VALUES (4, NULL, 'Socioprofessionnelle', 'notif_orientation_cg66_mod1');
INSERT INTO typesorients VALUES (6, NULL, 'Social', 'notif_orientation_cg66_mod2');
INSERT INTO typesorients VALUES (1, NULL, 'Emploi', 'notif_orientation_cg66_mod3');


--
-- Data for Name: typoscontrats; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO typoscontrats VALUES (1, 'Premier contrat');
INSERT INTO typoscontrats VALUES (2, 'Renouvellement');
INSERT INTO typoscontrats VALUES (3, 'Redéfinition');


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO users VALUES (7, 1, 1, 'cg11', '56a706302049f3573fa3f6895b9db2077766834b', 'Valles', 'Sylvie', NULL, '2009-07-01', '2019-12-31', '0404040404', true);
INSERT INTO users VALUES (5, 1, 2, 'cg93', 'ac860f0d3f51874b31260b406dc2dc549f4c6cde', 'RASOA', 'James', '1964-01-01', '2009-06-01', '2009-12-31', '0143939777', false);
INSERT INTO users VALUES (6, 1, 1, 'webrsa', '83a98ed2a57ad9734eb0a1694293d03c74ae8a57', 'Auzolat', 'Arnaud', NULL, '2009-01-01', '2019-01-01', '0606060606', false);
INSERT INTO users VALUES (1, 2, 2, 'cg23', 'e711d517faf274f83262f0cdd616651e7590927e', 'Cazier', 'Laurent', NULL, '2009-01-01', '2010-01-01', '050505050505', false);
INSERT INTO users VALUES (3, 3, 1, 'cg58', '5054b94efbf033a5fe624e0dfe14c8c0273fe320', 'Capelle', 'Philippe', '1967-01-01', '2009-06-01', '2009-12-31', '03.86.60.69.43', false);
INSERT INTO users VALUES (2, 2, 2, 'cg54', '13bdf5c43c14722e3e2d62bfc0ff0102c9955cda', 'Dupont', 'Albert', NULL, '2009-05-01', '2019-12-01', '0101010101', false);
INSERT INTO users VALUES (4, 1, 1, 'cg66', 'c41d80854d210d5f7512ab216b53b2f2b8e742dc', 'Dubois', 'Florent', NULL, '2009-01-01', '2019-01-01', '0468686868', true);


--
-- Data for Name: users_contratsinsertion; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Data for Name: users_zonesgeographiques; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO users_zonesgeographiques VALUES (7, 1, 1);
INSERT INTO users_zonesgeographiques VALUES (7, 2, 2);
INSERT INTO users_zonesgeographiques VALUES (7, 3, 3);
INSERT INTO users_zonesgeographiques VALUES (4, 1, 4);
INSERT INTO users_zonesgeographiques VALUES (4, 2, 5);
INSERT INTO users_zonesgeographiques VALUES (4, 3, 6);


--
-- Data for Name: zonesgeographiques; Type: TABLE DATA; Schema: public; Owner: webrsa
--

INSERT INTO zonesgeographiques VALUES (1, '34090', 'Pole Montpellier-Nord');
INSERT INTO zonesgeographiques VALUES (2, '34070', 'Pole Montpellier Sud-Est');
INSERT INTO zonesgeographiques VALUES (3, '34080', 'Pole Montpellier Ouest');


--
-- Data for Name: zonesgeographiques_regroupementszonesgeo; Type: TABLE DATA; Schema: public; Owner: webrsa
--



--
-- Name: accoemplois_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY accoemplois
    ADD CONSTRAINT accoemplois_pkey PRIMARY KEY (id);


--
-- Name: acos_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY acos
    ADD CONSTRAINT acos_pkey PRIMARY KEY (id);


--
-- Name: actions_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_pkey PRIMARY KEY (id);


--
-- Name: actionsinsertion_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY actionsinsertion
    ADD CONSTRAINT actionsinsertion_pkey PRIMARY KEY (id);


--
-- Name: activites_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY activites
    ADD CONSTRAINT activites_pkey PRIMARY KEY (id);


--
-- Name: adresses_foyers_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY adresses_foyers
    ADD CONSTRAINT adresses_foyers_pkey PRIMARY KEY (id);


--
-- Name: adresses_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY adresses
    ADD CONSTRAINT adresses_pkey PRIMARY KEY (id);


--
-- Name: aidesagricoles_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY aidesagricoles
    ADD CONSTRAINT aidesagricoles_pkey PRIMARY KEY (id);


--
-- Name: aidesdirectes_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY aidesdirectes
    ADD CONSTRAINT aidesdirectes_pkey PRIMARY KEY (id);


--
-- Name: allocationssoutienfamilial_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY allocationssoutienfamilial
    ADD CONSTRAINT allocationssoutienfamilial_pkey PRIMARY KEY (id);


--
-- Name: aros_acos_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY aros_acos
    ADD CONSTRAINT aros_acos_pkey PRIMARY KEY (id);


--
-- Name: aros_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY aros
    ADD CONSTRAINT aros_pkey PRIMARY KEY (id);


--
-- Name: avispcgdroitrsa_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY avispcgdroitrsa
    ADD CONSTRAINT avispcgdroitrsa_pkey PRIMARY KEY (id);


--
-- Name: avispcgpersonnes_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY avispcgpersonnes
    ADD CONSTRAINT avispcgpersonnes_pkey PRIMARY KEY (id);


--
-- Name: condsadmins_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY condsadmins
    ADD CONSTRAINT condsadmins_pkey PRIMARY KEY (id);


--
-- Name: connections_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY connections
    ADD CONSTRAINT connections_pkey PRIMARY KEY (id);


--
-- Name: contratsinsertion_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY contratsinsertion
    ADD CONSTRAINT contratsinsertion_pkey PRIMARY KEY (id);


--
-- Name: creances_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY creances
    ADD CONSTRAINT creances_pkey PRIMARY KEY (id);


--
-- Name: creancesalimentaires_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY creancesalimentaires
    ADD CONSTRAINT creancesalimentaires_pkey PRIMARY KEY (id);


--
-- Name: derogations_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY derogations
    ADD CONSTRAINT derogations_pkey PRIMARY KEY (id);


--
-- Name: detailscalculsdroitsrsa_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY detailscalculsdroitsrsa
    ADD CONSTRAINT detailscalculsdroitsrsa_pkey PRIMARY KEY (id);


--
-- Name: detailsdroitsrsa_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY detailsdroitsrsa
    ADD CONSTRAINT detailsdroitsrsa_pkey PRIMARY KEY (id);


--
-- Name: detailsressourcesmensuelles_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY detailsressourcesmensuelles
    ADD CONSTRAINT detailsressourcesmensuelles_pkey PRIMARY KEY (id);


--
-- Name: difdisps_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY difdisps
    ADD CONSTRAINT difdisps_pkey PRIMARY KEY (id);


--
-- Name: diflogs_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY diflogs
    ADD CONSTRAINT diflogs_pkey PRIMARY KEY (id);


--
-- Name: difsocs_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY difsocs
    ADD CONSTRAINT difsocs_pkey PRIMARY KEY (id);


--
-- Name: dossiers_rsa_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY dossiers_rsa
    ADD CONSTRAINT dossiers_rsa_pkey PRIMARY KEY (id);


--
-- Name: dossierscaf_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY dossierscaf
    ADD CONSTRAINT dossierscaf_pkey PRIMARY KEY (id);


--
-- Name: dspfs_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY dspfs
    ADD CONSTRAINT dspfs_pkey PRIMARY KEY (id);


--
-- Name: dspps_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY dspps
    ADD CONSTRAINT dspps_pkey PRIMARY KEY (id);


--
-- Name: evenements_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY evenements
    ADD CONSTRAINT evenements_pkey PRIMARY KEY (id);


--
-- Name: foyers_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY foyers
    ADD CONSTRAINT foyers_pkey PRIMARY KEY (id);


--
-- Name: grossesses_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY grossesses
    ADD CONSTRAINT grossesses_pkey PRIMARY KEY (id);


--
-- Name: groups_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_pkey PRIMARY KEY (id);


--
-- Name: identificationsflux_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY identificationsflux
    ADD CONSTRAINT identificationsflux_pkey PRIMARY KEY (id);


--
-- Name: informationseti_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY informationseti
    ADD CONSTRAINT informationseti_pkey PRIMARY KEY (id);


--
-- Name: infosagricoles_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY infosagricoles
    ADD CONSTRAINT infosagricoles_pkey PRIMARY KEY (id);


--
-- Name: infosfinancieres_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY infosfinancieres
    ADD CONSTRAINT infosfinancieres_pkey PRIMARY KEY (id);


--
-- Name: jetons_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY jetons
    ADD CONSTRAINT jetons_pkey PRIMARY KEY (id);


--
-- Name: liberalites_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY liberalites
    ADD CONSTRAINT liberalites_pkey PRIMARY KEY (id);


--
-- Name: modescontact_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY modescontact
    ADD CONSTRAINT modescontact_pkey PRIMARY KEY (id);


--
-- Name: nataccosocfams_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY nataccosocfams
    ADD CONSTRAINT nataccosocfams_pkey PRIMARY KEY (id);


--
-- Name: nataccosocindis_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY nataccosocindis
    ADD CONSTRAINT nataccosocindis_pkey PRIMARY KEY (id);


--
-- Name: natmobs_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY natmobs
    ADD CONSTRAINT natmobs_pkey PRIMARY KEY (id);


--
-- Name: nivetus_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY nivetus
    ADD CONSTRAINT nivetus_pkey PRIMARY KEY (id);


--
-- Name: orientsstructs_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY orientsstructs
    ADD CONSTRAINT orientsstructs_pkey PRIMARY KEY (id);


--
-- Name: orientsstructs_servicesinstructeurs_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY orientsstructs_servicesinstructeurs
    ADD CONSTRAINT orientsstructs_servicesinstructeurs_pkey PRIMARY KEY (orientstruct_id, serviceinstructeur_id);


--
-- Name: paiementsfoyers_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY paiementsfoyers
    ADD CONSTRAINT paiementsfoyers_pkey PRIMARY KEY (id);


--
-- Name: personnes_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY personnes
    ADD CONSTRAINT personnes_pkey PRIMARY KEY (id);


--
-- Name: prestations_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY prestations
    ADD CONSTRAINT prestations_pkey PRIMARY KEY (id);


--
-- Name: prestsform_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY prestsform
    ADD CONSTRAINT prestsform_pkey PRIMARY KEY (id);


--
-- Name: rattachements_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY rattachements
    ADD CONSTRAINT rattachements_pkey PRIMARY KEY (personne_id, rattache_id);


--
-- Name: reducsrsa_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY reducsrsa
    ADD CONSTRAINT reducsrsa_pkey PRIMARY KEY (id);


--
-- Name: referents_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY referents
    ADD CONSTRAINT referents_pkey PRIMARY KEY (id);


--
-- Name: refsprestas_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY refsprestas
    ADD CONSTRAINT refsprestas_pkey PRIMARY KEY (id);


--
-- Name: regroupementszonesgeo_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY regroupementszonesgeo
    ADD CONSTRAINT regroupementszonesgeo_pkey PRIMARY KEY (id);


--
-- Name: ressources_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY ressources
    ADD CONSTRAINT ressources_pkey PRIMARY KEY (id);


--
-- Name: ressourcesmensuelles_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY ressourcesmensuelles
    ADD CONSTRAINT ressourcesmensuelles_pkey PRIMARY KEY (id);


--
-- Name: servicesinstructeurs_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY servicesinstructeurs
    ADD CONSTRAINT servicesinstructeurs_pkey PRIMARY KEY (id);


--
-- Name: situationsdossiersrsa_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY situationsdossiersrsa
    ADD CONSTRAINT situationsdossiersrsa_pkey PRIMARY KEY (id);


--
-- Name: structuresreferentes_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY structuresreferentes
    ADD CONSTRAINT structuresreferentes_pkey PRIMARY KEY (id);


--
-- Name: structuresreferentes_zonesgeographiques_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY structuresreferentes_zonesgeographiques
    ADD CONSTRAINT structuresreferentes_zonesgeographiques_pkey PRIMARY KEY (structurereferente_id, zonegeographique_id);


--
-- Name: suivisinstruction_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY suivisinstruction
    ADD CONSTRAINT suivisinstruction_pkey PRIMARY KEY (id);


--
-- Name: suspensionsdroits_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY suspensionsdroits
    ADD CONSTRAINT suspensionsdroits_pkey PRIMARY KEY (id);


--
-- Name: suspensionsversements_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY suspensionsversements
    ADD CONSTRAINT suspensionsversements_pkey PRIMARY KEY (id);


--
-- Name: titres_sejour_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY titres_sejour
    ADD CONSTRAINT titres_sejour_pkey PRIMARY KEY (id);


--
-- Name: totalisationsacomptes_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY totalisationsacomptes
    ADD CONSTRAINT totalisationsacomptes_pkey PRIMARY KEY (id);


--
-- Name: typesactions_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY typesactions
    ADD CONSTRAINT typesactions_pkey PRIMARY KEY (id);


--
-- Name: typesorients_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY typesorients
    ADD CONSTRAINT typesorients_pkey PRIMARY KEY (id);


--
-- Name: typoscontrats_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY typoscontrats
    ADD CONSTRAINT typoscontrats_pkey PRIMARY KEY (id);


--
-- Name: users_contratsinsertion_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY users_contratsinsertion
    ADD CONSTRAINT users_contratsinsertion_pkey PRIMARY KEY (user_id, contratinsertion_id);


--
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: users_username_key; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_username_key UNIQUE (username);


--
-- Name: users_zonesgeographiques_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY users_zonesgeographiques
    ADD CONSTRAINT users_zonesgeographiques_pkey PRIMARY KEY (id);


--
-- Name: zonesgeographiques_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY zonesgeographiques
    ADD CONSTRAINT zonesgeographiques_pkey PRIMARY KEY (id);


--
-- Name: zonesgeographiques_regroupementszonesgeo_pkey; Type: CONSTRAINT; Schema: public; Owner: webrsa; Tablespace:
--

ALTER TABLE ONLY zonesgeographiques_regroupementszonesgeo
    ADD CONSTRAINT zonesgeographiques_regroupementszonesgeo_pkey PRIMARY KEY (zonegeographique_id, regroupementzonegeo_id);


--
-- Name: actions_typeaction_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_typeaction_id_fkey FOREIGN KEY (typeaction_id) REFERENCES typesactions(id);


--
-- Name: actionsinsertion_contratinsertion_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY actionsinsertion
    ADD CONSTRAINT actionsinsertion_contratinsertion_id_fkey FOREIGN KEY (contratinsertion_id) REFERENCES contratsinsertion(id);


--
-- Name: activites_personne_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY activites
    ADD CONSTRAINT activites_personne_id_fkey FOREIGN KEY (personne_id) REFERENCES personnes(id);


--
-- Name: adresses_foyers_adresse_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY adresses_foyers
    ADD CONSTRAINT adresses_foyers_adresse_id_fkey FOREIGN KEY (adresse_id) REFERENCES adresses(id);


--
-- Name: adresses_foyers_foyer_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY adresses_foyers
    ADD CONSTRAINT adresses_foyers_foyer_id_fkey FOREIGN KEY (foyer_id) REFERENCES foyers(id);


--
-- Name: aidesagricoles_infoagricole_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY aidesagricoles
    ADD CONSTRAINT aidesagricoles_infoagricole_id_fkey FOREIGN KEY (infoagricole_id) REFERENCES infosagricoles(id);


--
-- Name: aidesdirectes_actioninsertion_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY aidesdirectes
    ADD CONSTRAINT aidesdirectes_actioninsertion_id_fkey FOREIGN KEY (actioninsertion_id) REFERENCES actionsinsertion(id);


--
-- Name: allocationssoutienfamilial_personne_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY allocationssoutienfamilial
    ADD CONSTRAINT allocationssoutienfamilial_personne_id_fkey FOREIGN KEY (personne_id) REFERENCES personnes(id);


--
-- Name: avispcgdroitrsa_dossier_rsa_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY avispcgdroitrsa
    ADD CONSTRAINT avispcgdroitrsa_dossier_rsa_id_fkey FOREIGN KEY (dossier_rsa_id) REFERENCES dossiers_rsa(id);


--
-- Name: avispcgpersonnes_personne_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY avispcgpersonnes
    ADD CONSTRAINT avispcgpersonnes_personne_id_fkey FOREIGN KEY (personne_id) REFERENCES personnes(id);


--
-- Name: condsadmins_avispcgdroitrsa_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY condsadmins
    ADD CONSTRAINT condsadmins_avispcgdroitrsa_id_fkey FOREIGN KEY (avispcgdroitrsa_id) REFERENCES avispcgdroitrsa(id);


--
-- Name: connections_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY connections
    ADD CONSTRAINT connections_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- Name: contratsinsertion_personne_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY contratsinsertion
    ADD CONSTRAINT contratsinsertion_personne_id_fkey FOREIGN KEY (personne_id) REFERENCES personnes(id);


--
-- Name: contratsinsertion_structurereferente_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY contratsinsertion
    ADD CONSTRAINT contratsinsertion_structurereferente_id_fkey FOREIGN KEY (structurereferente_id) REFERENCES structuresreferentes(id);


--
-- Name: contratsinsertion_typocontrat_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY contratsinsertion
    ADD CONSTRAINT contratsinsertion_typocontrat_id_fkey FOREIGN KEY (typocontrat_id) REFERENCES typoscontrats(id);


--
-- Name: creancesalimentaires_personnes_creancealimentaire_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY creancesalimentaires_personnes
    ADD CONSTRAINT creancesalimentaires_personnes_creancealimentaire_id_fkey FOREIGN KEY (creancealimentaire_id) REFERENCES creancesalimentaires(id);


--
-- Name: creancesalimentaires_personnes_personne_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY creancesalimentaires_personnes
    ADD CONSTRAINT creancesalimentaires_personnes_personne_id_fkey FOREIGN KEY (personne_id) REFERENCES personnes(id);


--
-- Name: derogations_avispcgpersonne_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY derogations
    ADD CONSTRAINT derogations_avispcgpersonne_id_fkey FOREIGN KEY (avispcgpersonne_id) REFERENCES avispcgpersonnes(id);


--
-- Name: detailscalculsdroitsrsa_detaildroitrsa_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY detailscalculsdroitsrsa
    ADD CONSTRAINT detailscalculsdroitsrsa_detaildroitrsa_id_fkey FOREIGN KEY (detaildroitrsa_id) REFERENCES detailsdroitsrsa(id);


--
-- Name: detailsdroitsrsa_dossier_rsa_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY detailsdroitsrsa
    ADD CONSTRAINT detailsdroitsrsa_dossier_rsa_id_fkey FOREIGN KEY (dossier_rsa_id) REFERENCES dossiers_rsa(id);


--
-- Name: detailsressourcesmensuelles_ressourcemensuelle_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY detailsressourcesmensuelles
    ADD CONSTRAINT detailsressourcesmensuelles_ressourcemensuelle_id_fkey FOREIGN KEY (ressourcemensuelle_id) REFERENCES ressourcesmensuelles(id);


--
-- Name: dossierscaf_personne_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY dossierscaf
    ADD CONSTRAINT dossierscaf_personne_id_fkey FOREIGN KEY (personne_id) REFERENCES personnes(id);


--
-- Name: dspfs_diflogs_diflog_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY dspfs_diflogs
    ADD CONSTRAINT dspfs_diflogs_diflog_id_fkey FOREIGN KEY (diflog_id) REFERENCES diflogs(id);


--
-- Name: dspfs_diflogs_dspf_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY dspfs_diflogs
    ADD CONSTRAINT dspfs_diflogs_dspf_id_fkey FOREIGN KEY (dspf_id) REFERENCES dspfs(id);


--
-- Name: dspfs_foyer_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY dspfs
    ADD CONSTRAINT dspfs_foyer_id_fkey FOREIGN KEY (foyer_id) REFERENCES foyers(id);


--
-- Name: dspfs_nataccosocfams_dspf_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY dspfs_nataccosocfams
    ADD CONSTRAINT dspfs_nataccosocfams_dspf_id_fkey FOREIGN KEY (dspf_id) REFERENCES dspfs(id);


--
-- Name: dspfs_nataccosocfams_nataccosocfam_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY dspfs_nataccosocfams
    ADD CONSTRAINT dspfs_nataccosocfams_nataccosocfam_id_fkey FOREIGN KEY (nataccosocfam_id) REFERENCES nataccosocfams(id);


--
-- Name: dspps_accoemplois_accoemploi_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY dspps_accoemplois
    ADD CONSTRAINT dspps_accoemplois_accoemploi_id_fkey FOREIGN KEY (accoemploi_id) REFERENCES accoemplois(id);


--
-- Name: dspps_accoemplois_dspp_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY dspps_accoemplois
    ADD CONSTRAINT dspps_accoemplois_dspp_id_fkey FOREIGN KEY (dspp_id) REFERENCES dspps(id);


--
-- Name: dspps_difdisps_difdisp_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY dspps_difdisps
    ADD CONSTRAINT dspps_difdisps_difdisp_id_fkey FOREIGN KEY (difdisp_id) REFERENCES difdisps(id);


--
-- Name: dspps_difdisps_dspp_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY dspps_difdisps
    ADD CONSTRAINT dspps_difdisps_dspp_id_fkey FOREIGN KEY (dspp_id) REFERENCES dspps(id);


--
-- Name: dspps_difsocs_difsoc_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY dspps_difsocs
    ADD CONSTRAINT dspps_difsocs_difsoc_id_fkey FOREIGN KEY (difsoc_id) REFERENCES difsocs(id);


--
-- Name: dspps_difsocs_dspp_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY dspps_difsocs
    ADD CONSTRAINT dspps_difsocs_dspp_id_fkey FOREIGN KEY (dspp_id) REFERENCES dspps(id);


--
-- Name: dspps_nataccosocindis_dspp_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY dspps_nataccosocindis
    ADD CONSTRAINT dspps_nataccosocindis_dspp_id_fkey FOREIGN KEY (dspp_id) REFERENCES dspps(id);


--
-- Name: dspps_nataccosocindis_nataccosocindi_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY dspps_nataccosocindis
    ADD CONSTRAINT dspps_nataccosocindis_nataccosocindi_id_fkey FOREIGN KEY (nataccosocindi_id) REFERENCES nataccosocindis(id);


--
-- Name: dspps_natmobs_dspp_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY dspps_natmobs
    ADD CONSTRAINT dspps_natmobs_dspp_id_fkey FOREIGN KEY (dspp_id) REFERENCES dspps(id);


--
-- Name: dspps_natmobs_natmob_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY dspps_natmobs
    ADD CONSTRAINT dspps_natmobs_natmob_id_fkey FOREIGN KEY (natmob_id) REFERENCES natmobs(id);


--
-- Name: dspps_nivetus_dspp_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY dspps_nivetus
    ADD CONSTRAINT dspps_nivetus_dspp_id_fkey FOREIGN KEY (dspp_id) REFERENCES dspps(id);


--
-- Name: dspps_nivetus_nivetu_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY dspps_nivetus
    ADD CONSTRAINT dspps_nivetus_nivetu_id_fkey FOREIGN KEY (nivetu_id) REFERENCES nivetus(id);


--
-- Name: dspps_personne_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY dspps
    ADD CONSTRAINT dspps_personne_id_fkey FOREIGN KEY (personne_id) REFERENCES personnes(id);


--
-- Name: foyers_creances_creance_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY foyers_creances
    ADD CONSTRAINT foyers_creances_creance_id_fkey FOREIGN KEY (creance_id) REFERENCES creances(id);


--
-- Name: foyers_creances_foyer_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY foyers_creances
    ADD CONSTRAINT foyers_creances_foyer_id_fkey FOREIGN KEY (foyer_id) REFERENCES foyers(id);


--
-- Name: foyers_dossier_rsa_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY foyers
    ADD CONSTRAINT foyers_dossier_rsa_id_fkey FOREIGN KEY (dossier_rsa_id) REFERENCES dossiers_rsa(id);


--
-- Name: foyers_evenements_evenement_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY foyers_evenements
    ADD CONSTRAINT foyers_evenements_evenement_id_fkey FOREIGN KEY (evenement_id) REFERENCES evenements(id);


--
-- Name: foyers_evenements_foyer_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY foyers_evenements
    ADD CONSTRAINT foyers_evenements_foyer_id_fkey FOREIGN KEY (foyer_id) REFERENCES foyers(id);


--
-- Name: grossesses_personne_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY grossesses
    ADD CONSTRAINT grossesses_personne_id_fkey FOREIGN KEY (personne_id) REFERENCES personnes(id);


--
-- Name: informationseti_personne_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY informationseti
    ADD CONSTRAINT informationseti_personne_id_fkey FOREIGN KEY (personne_id) REFERENCES personnes(id);


--
-- Name: infosagricoles_personne_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY infosagricoles
    ADD CONSTRAINT infosagricoles_personne_id_fkey FOREIGN KEY (personne_id) REFERENCES personnes(id);


--
-- Name: infosfinancieres_dossier_rsa_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY infosfinancieres
    ADD CONSTRAINT infosfinancieres_dossier_rsa_id_fkey FOREIGN KEY (dossier_rsa_id) REFERENCES dossiers_rsa(id);


--
-- Name: jetons_dossier_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY jetons
    ADD CONSTRAINT jetons_dossier_id_fkey FOREIGN KEY (dossier_id) REFERENCES dossiers_rsa(id);


--
-- Name: jetons_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY jetons
    ADD CONSTRAINT jetons_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- Name: liberalites_avispcgpersonne_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY liberalites
    ADD CONSTRAINT liberalites_avispcgpersonne_id_fkey FOREIGN KEY (avispcgpersonne_id) REFERENCES avispcgpersonnes(id);


--
-- Name: modescontact_foyer_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY modescontact
    ADD CONSTRAINT modescontact_foyer_id_fkey FOREIGN KEY (foyer_id) REFERENCES foyers(id);


--
-- Name: orientsstructs_personne_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY orientsstructs
    ADD CONSTRAINT orientsstructs_personne_id_fkey FOREIGN KEY (personne_id) REFERENCES personnes(id);


--
-- Name: orientsstructs_propo_algo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY orientsstructs
    ADD CONSTRAINT orientsstructs_propo_algo_fkey FOREIGN KEY (propo_algo) REFERENCES typesorients(id);


--
-- Name: orientsstructs_servicesinstructeurs_orientstruct_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY orientsstructs_servicesinstructeurs
    ADD CONSTRAINT orientsstructs_servicesinstructeurs_orientstruct_id_fkey FOREIGN KEY (orientstruct_id) REFERENCES orientsstructs(id);


--
-- Name: orientsstructs_servicesinstructeurs_serviceinstructeur_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY orientsstructs_servicesinstructeurs
    ADD CONSTRAINT orientsstructs_servicesinstructeurs_serviceinstructeur_id_fkey FOREIGN KEY (serviceinstructeur_id) REFERENCES servicesinstructeurs(id);


--
-- Name: orientsstructs_structurereferente_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY orientsstructs
    ADD CONSTRAINT orientsstructs_structurereferente_id_fkey FOREIGN KEY (structurereferente_id) REFERENCES structuresreferentes(id);


--
-- Name: orientsstructs_typeorient_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY orientsstructs
    ADD CONSTRAINT orientsstructs_typeorient_id_fkey FOREIGN KEY (typeorient_id) REFERENCES typesorients(id);


--
-- Name: paiementsfoyers_foyer_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY paiementsfoyers
    ADD CONSTRAINT paiementsfoyers_foyer_id_fkey FOREIGN KEY (foyer_id) REFERENCES foyers(id);


--
-- Name: personnes_foyer_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY personnes
    ADD CONSTRAINT personnes_foyer_id_fkey FOREIGN KEY (foyer_id) REFERENCES foyers(id);


--
-- Name: prestsform_actioninsertion_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY prestsform
    ADD CONSTRAINT prestsform_actioninsertion_id_fkey FOREIGN KEY (actioninsertion_id) REFERENCES actionsinsertion(id);


--
-- Name: prestsform_refpresta_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY prestsform
    ADD CONSTRAINT prestsform_refpresta_id_fkey FOREIGN KEY (refpresta_id) REFERENCES refsprestas(id);


--
-- Name: rattachements_personne_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY rattachements
    ADD CONSTRAINT rattachements_personne_id_fkey FOREIGN KEY (personne_id) REFERENCES personnes(id);


--
-- Name: rattachements_rattache_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY rattachements
    ADD CONSTRAINT rattachements_rattache_id_fkey FOREIGN KEY (rattache_id) REFERENCES personnes(id);


--
-- Name: reducsrsa_avispcgdroitrsa_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY reducsrsa
    ADD CONSTRAINT reducsrsa_avispcgdroitrsa_id_fkey FOREIGN KEY (avispcgdroitrsa_id) REFERENCES avispcgdroitrsa(id);


--
-- Name: referents_structurereferente_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY referents
    ADD CONSTRAINT referents_structurereferente_id_fkey FOREIGN KEY (structurereferente_id) REFERENCES structuresreferentes(id);


--
-- Name: ressources_personne_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY ressources
    ADD CONSTRAINT ressources_personne_id_fkey FOREIGN KEY (personne_id) REFERENCES personnes(id);


--
-- Name: ressources_ressourcesmensuelles_ressource_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY ressources_ressourcesmensuelles
    ADD CONSTRAINT ressources_ressourcesmensuelles_ressource_id_fkey FOREIGN KEY (ressource_id) REFERENCES ressources(id);


--
-- Name: ressources_ressourcesmensuelles_ressourcemensuelle_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY ressources_ressourcesmensuelles
    ADD CONSTRAINT ressources_ressourcesmensuelles_ressourcemensuelle_id_fkey FOREIGN KEY (ressourcemensuelle_id) REFERENCES ressourcesmensuelles(id);


--
-- Name: ressourcesmensuelles_detailsre_detailressourcemensuelle_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY ressourcesmensuelles_detailsressourcesmensuelles
    ADD CONSTRAINT ressourcesmensuelles_detailsre_detailressourcemensuelle_id_fkey FOREIGN KEY (detailressourcemensuelle_id) REFERENCES detailsressourcesmensuelles(id);


--
-- Name: ressourcesmensuelles_detailsressourc_ressourcemensuelle_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY ressourcesmensuelles_detailsressourcesmensuelles
    ADD CONSTRAINT ressourcesmensuelles_detailsressourc_ressourcemensuelle_id_fkey FOREIGN KEY (ressourcemensuelle_id) REFERENCES ressourcesmensuelles(id);


--
-- Name: ressourcesmensuelles_ressource_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY ressourcesmensuelles
    ADD CONSTRAINT ressourcesmensuelles_ressource_id_fkey FOREIGN KEY (ressource_id) REFERENCES ressources(id);


--
-- Name: situationsdossiersrsa_dossier_rsa_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY situationsdossiersrsa
    ADD CONSTRAINT situationsdossiersrsa_dossier_rsa_id_fkey FOREIGN KEY (dossier_rsa_id) REFERENCES dossiers_rsa(id);


--
-- Name: structuresreferentes_typeorient_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY structuresreferentes
    ADD CONSTRAINT structuresreferentes_typeorient_id_fkey FOREIGN KEY (typeorient_id) REFERENCES typesorients(id);


--
-- Name: structuresreferentes_zonesgeographiq_structurereferente_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY structuresreferentes_zonesgeographiques
    ADD CONSTRAINT structuresreferentes_zonesgeographiq_structurereferente_id_fkey FOREIGN KEY (structurereferente_id) REFERENCES structuresreferentes(id);


--
-- Name: structuresreferentes_zonesgeographique_zonegeographique_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY structuresreferentes_zonesgeographiques
    ADD CONSTRAINT structuresreferentes_zonesgeographique_zonegeographique_id_fkey FOREIGN KEY (zonegeographique_id) REFERENCES zonesgeographiques(id);


--
-- Name: suivisinstruction_dossier_rsa_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY suivisinstruction
    ADD CONSTRAINT suivisinstruction_dossier_rsa_id_fkey FOREIGN KEY (dossier_rsa_id) REFERENCES dossiers_rsa(id);


--
-- Name: suspensionsdroits_situationdossierrsa_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY suspensionsdroits
    ADD CONSTRAINT suspensionsdroits_situationdossierrsa_id_fkey FOREIGN KEY (situationdossierrsa_id) REFERENCES situationsdossiersrsa(id);


--
-- Name: suspensionsversements_situationdossierrsa_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY suspensionsversements
    ADD CONSTRAINT suspensionsversements_situationdossierrsa_id_fkey FOREIGN KEY (situationdossierrsa_id) REFERENCES situationsdossiersrsa(id);


--
-- Name: titres_sejour_personne_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY titres_sejour
    ADD CONSTRAINT titres_sejour_personne_id_fkey FOREIGN KEY (personne_id) REFERENCES personnes(id);


--
-- Name: totalisationsacomptes_identificationflux_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY totalisationsacomptes
    ADD CONSTRAINT totalisationsacomptes_identificationflux_id_fkey FOREIGN KEY (identificationflux_id) REFERENCES identificationsflux(id);


--
-- Name: users_contratsinsertion_contratinsertion_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY users_contratsinsertion
    ADD CONSTRAINT users_contratsinsertion_contratinsertion_id_fkey FOREIGN KEY (contratinsertion_id) REFERENCES contratsinsertion(id);


--
-- Name: users_contratsinsertion_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY users_contratsinsertion
    ADD CONSTRAINT users_contratsinsertion_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- Name: users_group_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_group_id_fkey FOREIGN KEY (group_id) REFERENCES groups(id);


--
-- Name: users_serviceinstructeur_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_serviceinstructeur_id_fkey FOREIGN KEY (serviceinstructeur_id) REFERENCES servicesinstructeurs(id);


--
-- Name: users_zonesgeographiques_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY users_zonesgeographiques
    ADD CONSTRAINT users_zonesgeographiques_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- Name: users_zonesgeographiques_zonegeographique_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY users_zonesgeographiques
    ADD CONSTRAINT users_zonesgeographiques_zonegeographique_id_fkey FOREIGN KEY (zonegeographique_id) REFERENCES zonesgeographiques(id);


--
-- Name: zonesgeographiques_regroupementszon_regroupementzonegeo_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY zonesgeographiques_regroupementszonesgeo
    ADD CONSTRAINT zonesgeographiques_regroupementszon_regroupementzonegeo_id_fkey FOREIGN KEY (regroupementzonegeo_id) REFERENCES regroupementszonesgeo(id);


--
-- Name: zonesgeographiques_regroupementszonesg_zonegeographique_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: webrsa
--

ALTER TABLE ONLY zonesgeographiques_regroupementszonesgeo
    ADD CONSTRAINT zonesgeographiques_regroupementszonesg_zonegeographique_id_fkey FOREIGN KEY (zonegeographique_id) REFERENCES zonesgeographiques(id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

