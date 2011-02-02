--
-- PostgreSQL database dump
--

-- Started on 2010-11-12 14:45:28

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--
-- TOC entry 5 (class 2615 OID 2022427)
-- Name: administration; Type: SCHEMA; Schema: -; Owner: webrsa
--

CREATE SCHEMA administration;


ALTER SCHEMA administration OWNER TO webrsa;

--
-- TOC entry 6 (class 2615 OID 2022428)
-- Name: elementaire; Type: SCHEMA; Schema: -; Owner: webrsa
--

CREATE SCHEMA elementaire;


ALTER SCHEMA elementaire OWNER TO webrsa;

--
-- TOC entry 8 (class 2615 OID 2022429)
-- Name: staging; Type: SCHEMA; Schema: -; Owner: webrsa
--

CREATE SCHEMA staging;


ALTER SCHEMA staging OWNER TO webrsa;

--
-- TOC entry 814 (class 2612 OID 2022432)
-- Name: plpgsql; Type: PROCEDURAL LANGUAGE; Schema: -; Owner: webrsa
--

CREATE PROCEDURAL LANGUAGE plpgsql;


ALTER PROCEDURAL LANGUAGE plpgsql OWNER TO webrsa;

SET search_path = elementaire, pg_catalog;

--
-- TOC entry 260 (class 1247 OID 2022434)
-- Dependencies: 6
-- Name: type_accoemploi; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_accoemploi AS ENUM (
    '1801',
    '1802',
    '1803'
);


ALTER TYPE elementaire.type_accoemploi OWNER TO webrsa;

--
-- TOC entry 262 (class 1247 OID 2022439)
-- Dependencies: 6
-- Name: type_activitebeneficiaire; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_activitebeneficiaire AS ENUM (
    'E',
    'F',
    'C',
    'P'
);


ALTER TYPE elementaire.type_activitebeneficiaire OWNER TO webrsa;

--
-- TOC entry 300 (class 1247 OID 2022445)
-- Dependencies: 6
-- Name: type_booleannumber; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_booleannumber AS ENUM (
    '0',
    '1'
);


ALTER TYPE elementaire.type_booleannumber OWNER TO webrsa;

--
-- TOC entry 302 (class 1247 OID 2022449)
-- Dependencies: 6
-- Name: type_cessderact; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_cessderact AS ENUM (
    '2701',
    '2702'
);


ALTER TYPE elementaire.type_cessderact OWNER TO webrsa;

--
-- TOC entry 304 (class 1247 OID 2022453)
-- Dependencies: 6
-- Name: type_decisioncomite; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_decisioncomite AS ENUM (
    'REF',
    'ACC',
    'AJ'
);


ALTER TYPE elementaire.type_decisioncomite OWNER TO webrsa;

--
-- TOC entry 306 (class 1247 OID 2022458)
-- Dependencies: 6
-- Name: type_demarlog; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_demarlog AS ENUM (
    '1101',
    '1102',
    '1103'
);


ALTER TYPE elementaire.type_demarlog OWNER TO webrsa;

--
-- TOC entry 308 (class 1247 OID 2022463)
-- Dependencies: 6
-- Name: type_difdisp; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_difdisp AS ENUM (
    '0501',
    '0502',
    '0503',
    '0504',
    '0505',
    '0506'
);


ALTER TYPE elementaire.type_difdisp OWNER TO webrsa;

--
-- TOC entry 310 (class 1247 OID 2022471)
-- Dependencies: 6
-- Name: type_diflog; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_diflog AS ENUM (
    '1001',
    '1002',
    '1003',
    '1004',
    '1005',
    '1006',
    '1007',
    '1008',
    '1009'
);


ALTER TYPE elementaire.type_diflog OWNER TO webrsa;

--
-- TOC entry 312 (class 1247 OID 2022482)
-- Dependencies: 6
-- Name: type_difsoc; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_difsoc AS ENUM (
    '0401',
    '0402',
    '0403',
    '0404',
    '0405',
    '0406',
    '0407'
);


ALTER TYPE elementaire.type_difsoc OWNER TO webrsa;

--
-- TOC entry 314 (class 1247 OID 2022491)
-- Dependencies: 6
-- Name: type_duractdomi; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_duractdomi AS ENUM (
    '2104',
    '2105',
    '2106',
    '2107'
);


ALTER TYPE elementaire.type_duractdomi OWNER TO webrsa;

--
-- TOC entry 316 (class 1247 OID 2022497)
-- Dependencies: 6
-- Name: type_etatdossierapre; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_etatdossierapre AS ENUM (
    'COM',
    'INC'
);


ALTER TYPE elementaire.type_etatdossierapre OWNER TO webrsa;

--
-- TOC entry 318 (class 1247 OID 2022501)
-- Dependencies: 6
-- Name: type_hispro; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_hispro AS ENUM (
    '1901',
    '1902',
    '1903',
    '1904'
);


ALTER TYPE elementaire.type_hispro OWNER TO webrsa;

--
-- TOC entry 320 (class 1247 OID 2022507)
-- Dependencies: 6
-- Name: type_inscdememploi; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_inscdememploi AS ENUM (
    '4301',
    '4302',
    '4303',
    '4304'
);


ALTER TYPE elementaire.type_inscdememploi OWNER TO webrsa;

--
-- TOC entry 322 (class 1247 OID 2022513)
-- Dependencies: 6
-- Name: type_justificatif; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_justificatif AS ENUM (
    'CREA',
    'CDT',
    'CINS'
);


ALTER TYPE elementaire.type_justificatif OWNER TO webrsa;

--
-- TOC entry 324 (class 1247 OID 2022518)
-- Dependencies: 6
-- Name: type_motimodparco; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_motimodparco AS ENUM (
    'CL',
    'EA'
);


ALTER TYPE elementaire.type_motimodparco OWNER TO webrsa;

--
-- TOC entry 326 (class 1247 OID 2022522)
-- Dependencies: 6
-- Name: type_nataccosocfam; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_nataccosocfam AS ENUM (
    '0410',
    '0411',
    '0412',
    '0413'
);


ALTER TYPE elementaire.type_nataccosocfam OWNER TO webrsa;

--
-- TOC entry 328 (class 1247 OID 2022528)
-- Dependencies: 6
-- Name: type_nataccosocindi; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_nataccosocindi AS ENUM (
    '0416',
    '0417',
    '0418',
    '0419',
    '0420'
);


ALTER TYPE elementaire.type_nataccosocindi OWNER TO webrsa;

--
-- TOC entry 330 (class 1247 OID 2022535)
-- Dependencies: 6
-- Name: type_natlog; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_natlog AS ENUM (
    '0901',
    '0902',
    '0903',
    '0904',
    '0905',
    '0906',
    '0907',
    '0908',
    '0909',
    '0910',
    '0911',
    '0912',
    '0913'
);


ALTER TYPE elementaire.type_natlog OWNER TO webrsa;

--
-- TOC entry 332 (class 1247 OID 2022550)
-- Dependencies: 6
-- Name: type_natmob; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_natmob AS ENUM (
    '2504',
    '2501',
    '2502',
    '2503'
);


ALTER TYPE elementaire.type_natmob OWNER TO webrsa;

--
-- TOC entry 334 (class 1247 OID 2022556)
-- Dependencies: 6
-- Name: type_natparco; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_natparco AS ENUM (
    'AS',
    'PP',
    'PS'
);


ALTER TYPE elementaire.type_natparco OWNER TO webrsa;

--
-- TOC entry 336 (class 1247 OID 2022561)
-- Dependencies: 6
-- Name: type_natparcocal; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_natparcocal AS ENUM (
    'AS',
    'PP',
    'PS'
);


ALTER TYPE elementaire.type_natparcocal OWNER TO webrsa;

--
-- TOC entry 338 (class 1247 OID 2022566)
-- Dependencies: 6
-- Name: type_natparcomod; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_natparcomod AS ENUM (
    'AS',
    'PP',
    'PS'
);


ALTER TYPE elementaire.type_natparcomod OWNER TO webrsa;

--
-- TOC entry 340 (class 1247 OID 2022571)
-- Dependencies: 6
-- Name: type_naturelogement; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_naturelogement AS ENUM (
    'P',
    'L',
    'H',
    'S',
    'A'
);


ALTER TYPE elementaire.type_naturelogement OWNER TO webrsa;

--
-- TOC entry 342 (class 1247 OID 2022578)
-- Dependencies: 6
-- Name: type_nivdipmaxobt; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_nivdipmaxobt AS ENUM (
    '2601',
    '2602',
    '2603',
    '2604',
    '2605',
    '2606'
);


ALTER TYPE elementaire.type_nivdipmaxobt OWNER TO webrsa;

--
-- TOC entry 344 (class 1247 OID 2022586)
-- Dependencies: 6
-- Name: type_nivetu; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_nivetu AS ENUM (
    '1201',
    '1202',
    '1203',
    '1204',
    '1205',
    '1206',
    '1207'
);


ALTER TYPE elementaire.type_nivetu OWNER TO webrsa;

--
-- TOC entry 346 (class 1247 OID 2022595)
-- Dependencies: 6
-- Name: type_no; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_no AS ENUM (
    'N',
    'O'
);


ALTER TYPE elementaire.type_no OWNER TO webrsa;

--
-- TOC entry 348 (class 1247 OID 2022599)
-- Dependencies: 6
-- Name: type_nos; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_nos AS ENUM (
    'N',
    'O',
    'S'
);


ALTER TYPE elementaire.type_nos OWNER TO webrsa;

--
-- TOC entry 350 (class 1247 OID 2022604)
-- Dependencies: 6
-- Name: type_nov; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_nov AS ENUM (
    'N',
    'O',
    'V'
);


ALTER TYPE elementaire.type_nov OWNER TO webrsa;

--
-- TOC entry 352 (class 1247 OID 2022609)
-- Dependencies: 6
-- Name: type_presence; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_presence AS ENUM (
    'PRE',
    'ABS',
    'EXC'
);


ALTER TYPE elementaire.type_presence OWNER TO webrsa;

--
-- TOC entry 354 (class 1247 OID 2022614)
-- Dependencies: 6
-- Name: type_retenu; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_retenu AS ENUM (
    'RET',
    'NRE'
);


ALTER TYPE elementaire.type_retenu OWNER TO webrsa;

--
-- TOC entry 356 (class 1247 OID 2022618)
-- Dependencies: 6
-- Name: type_sitpersdemrsa; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_sitpersdemrsa AS ENUM (
    '0101',
    '0102',
    '0103',
    '0104',
    '0105',
    '0106',
    '0107',
    '0108',
    '0109'
);


ALTER TYPE elementaire.type_sitpersdemrsa OWNER TO webrsa;

--
-- TOC entry 358 (class 1247 OID 2022629)
-- Dependencies: 6
-- Name: type_sitperssocpro; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_sitperssocpro AS ENUM (
    'AF',
    'EF',
    'RE'
);


ALTER TYPE elementaire.type_sitperssocpro OWNER TO webrsa;

--
-- TOC entry 360 (class 1247 OID 2022634)
-- Dependencies: 6
-- Name: type_statutapre; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_statutapre AS ENUM (
    'C',
    'F'
);


ALTER TYPE elementaire.type_statutapre OWNER TO webrsa;

--
-- TOC entry 362 (class 1247 OID 2022638)
-- Dependencies: 6
-- Name: type_statutdecision; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_statutdecision AS ENUM (
    'DEF',
    'UND'
);


ALTER TYPE elementaire.type_statutdecision OWNER TO webrsa;

--
-- TOC entry 364 (class 1247 OID 2022642)
-- Dependencies: 6
-- Name: type_typeaidelogement; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_typeaidelogement AS ENUM (
    'AEL',
    'AML'
);


ALTER TYPE elementaire.type_typeaidelogement OWNER TO webrsa;

--
-- TOC entry 366 (class 1247 OID 2022646)
-- Dependencies: 6
-- Name: type_typeapre; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_typeapre AS ENUM (
    'forfaitaire',
    'complementaire'
);


ALTER TYPE elementaire.type_typeapre OWNER TO webrsa;

--
-- TOC entry 368 (class 1247 OID 2022650)
-- Dependencies: 6
-- Name: type_typecontrat; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_typecontrat AS ENUM (
    'CDI',
    'CDD',
    'CON',
    'AUT'
);


ALTER TYPE elementaire.type_typecontrat OWNER TO webrsa;

--
-- TOC entry 370 (class 1247 OID 2022656)
-- Dependencies: 6
-- Name: type_typecontratact; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_typecontratact AS ENUM (
    'CI',
    'CA',
    'SA'
);


ALTER TYPE elementaire.type_typecontratact OWNER TO webrsa;

--
-- TOC entry 372 (class 1247 OID 2022661)
-- Dependencies: 6
-- Name: type_typedemandeapre; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_typedemandeapre AS ENUM (
    'FO',
    'AU'
);


ALTER TYPE elementaire.type_typedemandeapre OWNER TO webrsa;

--
-- TOC entry 374 (class 1247 OID 2022665)
-- Dependencies: 6
-- Name: type_venu; Type: TYPE; Schema: elementaire; Owner: webrsa
--

CREATE TYPE type_venu AS ENUM (
    'VEN',
    'NVE'
);


ALTER TYPE elementaire.type_venu OWNER TO webrsa;

SET search_path = staging, pg_catalog;

--
-- TOC entry 23 (class 1255 OID 2022668)
-- Dependencies: 814 8
-- Name: clean_old_dossier_beneficiaire(); Type: FUNCTION; Schema: staging; Owner: webrsa
--

CREATE FUNCTION clean_old_dossier_beneficiaire() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
declare 
	infofoyer integer;
BEGIN
select infosfoyerrsa into infofoyer from staging.b_demandersa where numdemrsa = NEW.numdemrsa;
if (infofoyer is not null)
then
	delete from staging.b_activite where infosfoyerrsa = infofoyer;
	delete from staging.b_adresse where infosfoyerrsa = infofoyer;
	delete from staging.b_asf where infosfoyerrsa = infofoyer;
	delete from staging.b_avispcgpersonnes where infosfoyerrsa = infofoyer;
	delete from staging.b_benefices where infosfoyerrsa = infofoyer;
	delete from staging.b_calculdroitrsa where infosfoyerrsa = infofoyer;
	delete from staging.b_condsadmins where infosfoyerrsa = infofoyer;
	delete from staging.b_creance where infosfoyerrsa = infofoyer;
	delete from staging.b_creancealimentaire where infosfoyerrsa = infofoyer;
	delete from staging.b_demandermi where infosfoyerrsa = infofoyer;
	delete from staging.b_demandersa where infosfoyerrsa = infofoyer;
	delete from staging.b_derogations where infosfoyerrsa = infofoyer;
	delete from staging.b_detailressourcesmensuelles where infosfoyerrsa = infofoyer;
	delete from staging.b_detailscalculsdroitrsa where infosfoyerrsa = infofoyer;
	delete from staging.b_detailsdroitrsa where infosfoyerrsa = infofoyer;
	delete from staging.b_dossiercaf where infosfoyerrsa = infofoyer;
	delete from staging.b_dossierpoleemploi where infosfoyerrsa = infofoyer;
	delete from staging.b_evenement where infosfoyerrsa = infofoyer;
	delete from staging.b_generaliteressourcesmensuelles where infosfoyerrsa = infofoyer;
	delete from staging.b_generaliteressourcestrimestre where infosfoyerrsa = infofoyer;
	delete from staging.b_grossesse where infosfoyerrsa = infofoyer;
	delete from staging.b_identification where infosfoyerrsa = infofoyer;
	delete from staging.b_liberalite where infosfoyerrsa = infofoyer;
	delete from staging.b_organisme where infosfoyerrsa = infofoyer;
	delete from staging.b_organismecedant where infosfoyerrsa = infofoyer;
	delete from staging.b_organismeprenant where infosfoyerrsa = infofoyer;
	delete from staging.b_paiementtiers where infosfoyerrsa = infofoyer;
	delete from staging.b_partenaire where infosfoyerrsa = infofoyer;
	delete from staging.b_prestations where infosfoyerrsa = infofoyer;
	delete from staging.b_rattachement where infosfoyerrsa = infofoyer;
	delete from staging.b_reducsrsa where infosfoyerrsa = infofoyer;
	delete from staging.b_sitdossiersrsa where infosfoyerrsa = infofoyer;
	delete from staging.b_situationfamille where infosfoyerrsa = infofoyer;
	delete from staging.b_suspensiondroits where infosfoyerrsa = infofoyer;
	delete from staging.b_suspensionversements where infosfoyerrsa = infofoyer;
end if;
return new;
end;
$$;


ALTER FUNCTION staging.clean_old_dossier_beneficiaire() OWNER TO webrsa;

--
-- TOC entry 26 (class 1255 OID 2149784)
-- Dependencies: 814 8
-- Name: clean_old_dossier_instruction(); Type: FUNCTION; Schema: staging; Owner: postgres
--

CREATE FUNCTION clean_old_dossier_instruction() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
declare 
	dossier integer;
BEGIN
select infodemandersa into dossier from staging.demandersa where numdemrsa = NEW.numdemrsa;
if (dossier is not null)
then
	delete from staging.adresse where infodemandersa = dossier;
	delete from staging.demandersa where infodemandersa = dossier;
	delete from staging.destinataire where infodemandersa = dossier;
	delete from staging.electiondomicile where infodemandersa = dossier;
	delete from staging.ficheliaison where infodemandersa = dossier;
	delete from staging.logement where infodemandersa = dossier;
	delete from staging.modescontacts where infodemandersa = dossier;	
	delete from staging.organisme where infodemandersa = dossier;
	delete from staging.organismecedant where infodemandersa = dossier;
	delete from staging.partenaire where infodemandersa = dossier;
	delete from staging.prestationrsa where infodemandersa = dossier;
	delete from staging.rib where infodemandersa = dossier;
	delete from staging.situationfamille where infodemandersa = dossier;
	delete from staging.suivi_instruction where infodemandersa = dossier;
	delete from staging.activite where infodemandersa = dossier;
	delete from staging.activiteeti where infodemandersa = dossier;
	delete from staging.aidesagricoles where infodemandersa = dossier;
	delete from staging.asf where infodemandersa = dossier;
	delete from staging.benefices where infodemandersa = dossier;
	delete from staging.chiffreaffaire where infodemandersa = dossier;
	delete from staging.conditionactiviteprealable where infodemandersa = dossier;
	delete from staging.commundifficultelogement where infodemandersa = dossier;
	delete from staging.communmobilite where infodemandersa = dossier;
	delete from staging.communsituationsociale where infodemandersa = dossier;
	delete from staging.creancealimentaire where infodemandersa = dossier;
	delete from staging.detailaccompagnementsocialfamilial where infodemandersa = dossier;
	delete from staging.detailaccosocindividuel where infodemandersa = dossier;
	delete from staging.detaildifficultedisponibilite where infodemandersa = dossier;
	delete from staging.detaildifficultelogement where infodemandersa = dossier;
	delete from staging.detaildifficultesituationsociale where infodemandersa = dossier;
	delete from staging.detailmobilite where infodemandersa = dossier;
	delete from staging.detailressourcesmensuelles where infodemandersa = dossier;
	delete from staging.determinationparcours where infodemandersa = dossier;
	delete from staging.disponibilteemploi where infodemandersa = dossier;
	delete from staging.dossiercaf where infodemandersa = dossier;
	delete from staging.dossierpoleemploi where infodemandersa = dossier;
	delete from staging.elementsfiscaux where infodemandersa = dossier;
	delete from staging.employes where infodemandersa = dossier;
	delete from staging.generalitedspp where infodemandersa = dossier;
	delete from staging.generaliteressourcesmensuelles where infodemandersa = dossier;
	delete from staging.generaliteressourcestrimestre where infodemandersa = dossier;
	delete from staging.grossesse where infodemandersa = dossier;
	delete from staging.identification where infodemandersa = dossier;
	delete from staging.nationalite where infodemandersa = dossier;
	delete from staging.niveauetude where infodemandersa = dossier;
	delete from staging.organismedecisionorientation where infodemandersa = dossier;
	delete from staging.organismereferentorientation where infodemandersa = dossier;
	delete from staging.prestations where infodemandersa = dossier;
	delete from staging.rattachement where infodemandersa = dossier;
	delete from staging.situationprofessionnelle where infodemandersa = dossier;
	delete from staging.suiviappuiorientation where infodemandersa = dossier;
	delete from staging.titresejour where infodemandersa = dossier;
end if;
return new;
end;
$$;


ALTER FUNCTION staging.clean_old_dossier_instruction() OWNER TO postgres;

--
-- TOC entry 24 (class 1255 OID 2022670)
-- Dependencies: 814 8
-- Name: clean_old_personne_beneficiaire(); Type: FUNCTION; Schema: staging; Owner: webrsa
--

CREATE FUNCTION clean_old_personne_beneficiaire() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
declare 
	dossier integer;
	personne integer;
BEGIN
select infosfoyerrsa, clepersonne into dossier, personne from staging.b_identification 
where infosfoyerrsa = NEW.infosfoyerrsa
AND nomnai = NEW.nomnai
AND prenom = NEW.prenom
AND dtnai = NEW.dtnai
AND coalesce(rgnai, '') = coalesce(NEW.rgnai,'');

if (dossier is not null AND personne is not null)
then
	delete from staging.b_activite where infosfoyerrsa = dossier and clepersonne = personne;
	delete from staging.b_asf where infosfoyerrsa = dossier and clepersonne = personne;
	delete from staging.b_avispcgpersonnes where infosfoyerrsa = dossier and clepersonne = personne;
	delete from staging.b_benefices where infosfoyerrsa = dossier and clepersonne = personne;
	delete from staging.b_calculdroitrsa where infosfoyerrsa = dossier and clepersonne = personne;
	delete from staging.b_creancealimentaire where infosfoyerrsa = dossier and clepersonne = personne;
	delete from staging.b_derogations where infosfoyerrsa = dossier and clepersonne = personne;
	delete from staging.b_detailressourcesmensuelles where infosfoyerrsa = dossier and clepersonne = personne;
	delete from staging.b_dossiercaf where infosfoyerrsa = dossier and clepersonne = personne;
	delete from staging.b_dossierpoleemploi where infosfoyerrsa = dossier and clepersonne = personne;
	delete from staging.b_generaliteressourcesmensuelles where infosfoyerrsa = dossier and clepersonne = personne;
	delete from staging.b_generaliteressourcestrimestre where infosfoyerrsa = dossier and clepersonne = personne;
	delete from staging.b_grossesse where infosfoyerrsa = dossier and clepersonne = personne;
	delete from staging.b_identification where infosfoyerrsa = dossier and clepersonne = personne;
	delete from staging.b_liberalite where infosfoyerrsa = dossier and clepersonne = personne;
	delete from staging.b_prestations where infosfoyerrsa = dossier and clepersonne = personne;
	delete from staging.b_rattachement where infosfoyerrsa = dossier and clepersonne = personne;
end if;
return new;
end;
$$;


ALTER FUNCTION staging.clean_old_personne_beneficiaire() OWNER TO webrsa;

--
-- TOC entry 25 (class 1255 OID 2149785)
-- Dependencies: 814 8
-- Name: clean_old_personne_instruction(); Type: FUNCTION; Schema: staging; Owner: postgres
--

CREATE FUNCTION clean_old_personne_instruction() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE 
	dossier INTEGER;
	personne INTEGER;
BEGIN
	SELECT infodemandersa, clepersonne INTO dossier, personne FROM staging.identification 
	WHERE infodemandersa = NEW.infodemandersa
		AND COALESCE(nomnai,'') = COALESCE(NEW.nomnai,'')
		AND COALESCE(prenom,'') = COALESCE(NEW.prenom,'')
		AND COALESCE(dtnai,'') = COALESCE(NEW.dtnai,'')
		AND COALESCE(rgnai,'') = COALESCE(NEW.rgnai,'');

	IF (dossier IS NOT NULL AND personne IS NOT NULL)
	THEN
		DELETE FROM staging.activite WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.activiteeti WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.aidesagricoles WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.asf WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.benefices WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.chiffreaffaire WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.commundifficultelogement WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.communmobilite WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.communsituationsociale WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.conditionactiviteprealable WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.creancealimentaire WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.detailaccompagnementsocialfamilial WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.detailaccosocindividuel WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.detaildifficultedisponibilite WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.detaildifficultelogement WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.detaildifficultesituationsociale WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.detailmobilite WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.detailressourcesmensuelles WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.determinationparcours WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.disponibilteemploi WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.dossiercaf WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.dossierpoleemploi WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.elementsfiscaux WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.employes WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.generalitedspp WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.generaliteressourcesmensuelles WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.generaliteressourcestrimestre WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.grossesse WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.identification WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.nationalite WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.niveauetude WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.organismedecisionorientation WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.organismereferentorientation WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.prestations WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.rattachement WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.situationprofessionnelle WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.suiviappuiorientation WHERE infodemandersa = dossier AND clepersonne = personne;
		DELETE FROM staging.titresejour WHERE infodemandersa = dossier AND clepersonne = personne;
	END IF;
	RETURN new;
END;
$$;


ALTER FUNCTION staging.clean_old_personne_instruction() OWNER TO postgres;

SET search_path = administration, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 1986 (class 1259 OID 2022672)
-- Dependencies: 5
-- Name: b_pcgpersid; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_pcgpersid (
    cleinfosfoyerrsa integer NOT NULL,
    personne_id integer,
    pcgpersid integer
);


ALTER TABLE administration.b_pcgpersid OWNER TO webrsa;

--
-- TOC entry 1987 (class 1259 OID 2022675)
-- Dependencies: 5
-- Name: b_refadresseid; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_refadresseid (
    cleinfosfoyerrsa integer NOT NULL,
    cle integer,
    adresseid integer
);


ALTER TABLE administration.b_refadresseid OWNER TO webrsa;

--
-- TOC entry 1988 (class 1259 OID 2022678)
-- Dependencies: 5
-- Name: b_refdetdroitrsa; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_refdetdroitrsa (
    cleinfosfoyerrsa integer NOT NULL,
    detdroitrsa_id integer
);


ALTER TABLE administration.b_refdetdroitrsa OWNER TO webrsa;

--
-- TOC entry 1989 (class 1259 OID 2022681)
-- Dependencies: 5
-- Name: b_refdossierrsaid; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_refdossierrsaid (
    cleinfosfoyerrsa integer NOT NULL,
    cledossierrsa integer
);


ALTER TABLE administration.b_refdossierrsaid OWNER TO webrsa;

--
-- TOC entry 1990 (class 1259 OID 2022684)
-- Dependencies: 5
-- Name: b_reffoyersid; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_reffoyersid (
    cleinfodemandersa integer,
    dossierrsa_id integer NOT NULL,
    foyers_id integer
);


ALTER TABLE administration.b_reffoyersid OWNER TO webrsa;

--
-- TOC entry 1991 (class 1259 OID 2022687)
-- Dependencies: 5
-- Name: b_refpcgdroitrsa; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_refpcgdroitrsa (
    cleinfosfoyerrsa integer NOT NULL,
    pcgdroitrsa_id integer
);


ALTER TABLE administration.b_refpcgdroitrsa OWNER TO webrsa;

--
-- TOC entry 1992 (class 1259 OID 2022690)
-- Dependencies: 5
-- Name: b_refpersid; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_refpersid (
    clepersonne_id integer,
    cleinfodemandersa integer,
    nomnai character varying(50),
    prenom character varying(50),
    dtnai date,
    clepersonne_source integer
);


ALTER TABLE administration.b_refpersid OWNER TO webrsa;

--
-- TOC entry 1993 (class 1259 OID 2022693)
-- Dependencies: 5
-- Name: b_refressmensid; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_refressmensid (
    cleinfosfoyerrsa integer NOT NULL,
    clepersonne integer,
    personneid integer,
    ressourcesid integer,
    ressmensid integer
);


ALTER TABLE administration.b_refressmensid OWNER TO webrsa;

--
-- TOC entry 1994 (class 1259 OID 2022696)
-- Dependencies: 5
-- Name: b_refressourceid; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_refressourceid (
    cleinfosfoyerrsa integer NOT NULL,
    clepersonne integer,
    personneid integer,
    ressourcesid integer
);


ALTER TABLE administration.b_refressourceid OWNER TO webrsa;

--
-- TOC entry 1995 (class 1259 OID 2022699)
-- Dependencies: 5
-- Name: b_refsitdossierrsa; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_refsitdossierrsa (
    cleinfosfoyerrsa integer NOT NULL,
    sitdossrsa_id integer
);


ALTER TABLE administration.b_refsitdossierrsa OWNER TO webrsa;

--
-- TOC entry 1996 (class 1259 OID 2022702)
-- Dependencies: 5
-- Name: donneeentete; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE donneeentete (
    flux character varying(20) NOT NULL,
    balisededonnee character varying(100000)
);


ALTER TABLE administration.donneeentete OWNER TO webrsa;

--
-- TOC entry 1997 (class 1259 OID 2022708)
-- Dependencies: 5
-- Name: donneepied; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE donneepied (
    flux character varying(20) NOT NULL,
    balisededonnee character varying(100000)
);


ALTER TABLE administration.donneepied OWNER TO webrsa;

--
-- TOC entry 1998 (class 1259 OID 2022714)
-- Dependencies: 5
-- Name: donneereference; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE donneereference (
    cledemandersa integer NOT NULL,
    flux character varying(20) NOT NULL,
    numdemrsa character varying(20),
    matricule character varying(20)
);


ALTER TABLE administration.donneereference OWNER TO webrsa;

--
-- TOC entry 1999 (class 1259 OID 2022717)
-- Dependencies: 5
-- Name: donneetampon; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE donneetampon (
    cledemandersa integer NOT NULL,
    flux character varying(20) NOT NULL,
    balisededonnee character varying(100000)
);


ALTER TABLE administration.donneetampon OWNER TO webrsa;

--
-- TOC entry 2000 (class 1259 OID 2022723)
-- Dependencies: 5
-- Name: f_refdossierrsaid; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE f_refdossierrsaid (
    cleinfosfoyerrsa integer NOT NULL,
    cledossierrsa integer
);


ALTER TABLE administration.f_refdossierrsaid OWNER TO webrsa;

--
-- TOC entry 2001 (class 1259 OID 2022726)
-- Dependencies: 5
-- Name: nomfichier; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE nomfichier (
    flux character varying(20) NOT NULL,
    nom character varying(100)
);


ALTER TABLE administration.nomfichier OWNER TO webrsa;

--
-- TOC entry 2002 (class 1259 OID 2022729)
-- Dependencies: 5
-- Name: refadressesid; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE refadressesid (
    infodemandersa integer,
    foyers_id integer,
    adresses_id integer
);


ALTER TABLE administration.refadressesid OWNER TO webrsa;

--
-- TOC entry 2003 (class 1259 OID 2022732)
-- Dependencies: 5
-- Name: refdossierrsaid; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE refdossierrsaid (
    cleinfodemandersa integer NOT NULL,
    cledossierrsa integer
);


ALTER TABLE administration.refdossierrsaid OWNER TO webrsa;

--
-- TOC entry 2004 (class 1259 OID 2022735)
-- Dependencies: 5
-- Name: refdspid; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE refdspid (
    dsp_id integer,
    personne_id integer,
    infodemandersa integer,
    clepersonne integer
);


ALTER TABLE administration.refdspid OWNER TO webrsa;

--
-- TOC entry 2005 (class 1259 OID 2022738)
-- Dependencies: 5
-- Name: reffoyersid; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE reffoyersid (
    cledossierrsa integer NOT NULL,
    clefoyers integer,
    cleinfodemandersa integer
);


ALTER TABLE administration.reffoyersid OWNER TO webrsa;

--
-- TOC entry 2006 (class 1259 OID 2022741)
-- Dependencies: 5
-- Name: refinfoagricolesid; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE refinfoagricolesid (
    infosagricoles_id integer,
    personnes_id integer,
    infodemandersa integer,
    clepersonne integer
);


ALTER TABLE administration.refinfoagricolesid OWNER TO webrsa;

--
-- TOC entry 2007 (class 1259 OID 2022744)
-- Dependencies: 5
-- Name: refpersid; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE refpersid (
    clepersonne_id integer,
    cleinfodemandersa integer,
    nomnai character varying(50),
    prenom character varying(50),
    dtnai date,
    clepersonne_source integer
);


ALTER TABLE administration.refpersid OWNER TO webrsa;

--
-- TOC entry 2008 (class 1259 OID 2022747)
-- Dependencies: 5
-- Name: refresmensuellesid; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE refresmensuellesid (
    resmensuelles_id integer,
    ressources_id integer,
    infodemandersa integer,
    clepersonne integer,
    cleresmensuelles integer
);


ALTER TABLE administration.refresmensuellesid OWNER TO webrsa;

--
-- TOC entry 2009 (class 1259 OID 2022750)
-- Dependencies: 5
-- Name: refressourcesid; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE refressourcesid (
    ressources_id integer,
    personne_id integer,
    infodemandersa integer,
    clepersonne integer
);


ALTER TABLE administration.refressourcesid OWNER TO webrsa;

--
-- TOC entry 2010 (class 1259 OID 2022753)
-- Dependencies: 5
-- Name: rejet; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE rejet (
    cleinfodemandersa integer NOT NULL,
    flux character varying(20),
    etape integer,
    table_en_erreur character varying(50),
    log character varying(1000)
);


ALTER TABLE administration.rejet OWNER TO webrsa;

--
-- TOC entry 2011 (class 1259 OID 2022759)
-- Dependencies: 2466 2467 2468 2469 2470 2471 5
-- Name: rejet_historique; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE rejet_historique (
    cleinfodemandersa integer NOT NULL,
    flux character varying(20) DEFAULT NULL::character varying NOT NULL,
    etape integer,
    table_en_erreur character varying(50) DEFAULT NULL::character varying,
    log character varying(1000) DEFAULT NULL::character varying,
    numdemrsa character varying(20) DEFAULT NULL::character varying,
    matricule character varying(20) DEFAULT NULL::character varying,
    "DT_INSERT" timestamp(6) without time zone DEFAULT now() NOT NULL,
    fic character varying(40),
    balisededonnee character varying(100000)
);


ALTER TABLE administration.rejet_historique OWNER TO webrsa;

--
-- TOC entry 2012 (class 1259 OID 2022771)
-- Dependencies: 5
-- Name: statintegrationbeneficiaire; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE statintegrationbeneficiaire (
    nom_fichier character varying(100),
    date_debut timestamp(6) without time zone,
    date_fin timestamp(6) without time zone,
    elem_activites integer,
    elem_adresses integer,
    elem_adresses_foyers integer,
    elem_allocationssoutienfamilial integer,
    elem_anomalies integer,
    elem_avispcgdroitrsa integer,
    elem_avispcgpersonnes integer,
    elem_calculsdroitsrsa integer,
    elem_condsadmins integer,
    elem_controlesadministratifs integer,
    elem_creances integer,
    elem_creancesalimentaires integer,
    elem_derogations integer,
    elem_detailscalculsdroitsrsa integer,
    elem_detailsdroitsrsa integer,
    elem_detailsressourcesmensuelles integer,
    elem_dossiers_rsa integer,
    elem_dossierscaf integer,
    elem_evenements integer,
    elem_foyers integer,
    elem_grossesses integer,
    elem_identificationsflux integer,
    elem_infosagricoles integer,
    elem_liberalites integer,
    elem_personnes integer,
    elem_prestations integer,
    elem_rattachements integer,
    elem_reducsrsa integer,
    elem_ressources integer,
    elem_ressourcesmensuelles integer,
    elem_situationsdossiersrsa integer,
    elem_suspensionsdroits integer,
    elem_suspensionsversements integer,
    elem_transmissionsflux integer,
    webav_activites integer,
    webav_adresses integer,
    webav_adresses_foyers integer,
    webav_allocationssoutienfamilial integer,
    webav_anomalies integer,
    webav_avispcgdroitrsa integer,
    webav_avispcgpersonnes integer,
    webav_calculsdroitsrsa integer,
    webav_condsadmins integer,
    webav_controlesadministratifs integer,
    webav_creances integer,
    webav_creancesalimentaires integer,
    webav_derogations integer,
    webav_detailscalculsdroitsrsa integer,
    webav_detailsdroitsrsa integer,
    webav_detailsressourcesmensuelles integer,
    webav_dossiers_rsa integer,
    webav_dossierscaf integer,
    webav_evenements integer,
    webav_foyers integer,
    webav_grossesses integer,
    webav_identificationsflux integer,
    webav_infosagricoles integer,
    webav_liberalites integer,
    webav_personnes integer,
    webav_prestations integer,
    webav_rattachements integer,
    webav_reducsrsa integer,
    webav_ressources integer,
    webav_ressourcesmensuelles integer,
    webav_situationsdossiersrsa integer,
    webav_suspensionsdroits integer,
    webav_suspensionsversements integer,
    webav_transmissionsflux integer,
    webap_activites integer,
    webap_adresses integer,
    webap_adresses_foyers integer,
    webap_allocationssoutienfamilial integer,
    webap_anomalies integer,
    webap_avispcgdroitrsa integer,
    webap_avispcgpersonnes integer,
    webap_calculsdroitsrsa integer,
    webap_condsadmins integer,
    webap_controlesadministratifs integer,
    webap_creances integer,
    webap_creancesalimentaires integer,
    webap_derogations integer,
    webap_detailscalculsdroitsrsa integer,
    webap_detailsdroitsrsa integer,
    webap_detailsressourcesmensuelles integer,
    webap_dossiers_rsa integer,
    webap_dossierscaf integer,
    webap_evenements integer,
    webap_foyers integer,
    webap_grossesses integer,
    webap_identificationsflux integer,
    webap_infosagricoles integer,
    webap_liberalites integer,
    webap_personnes integer,
    webap_prestations integer,
    webap_rattachements integer,
    webap_reducsrsa integer,
    webap_ressources integer,
    webap_ressourcesmensuelles integer,
    webap_situationsdossiersrsa integer,
    webap_suspensionsdroits integer,
    webap_suspensionsversements integer,
    webap_transmissionsflux integer,
    flux_activites integer,
    flux_adresses integer,
    flux_adresses_foyers integer,
    flux_allocationssoutienfamilial integer,
    flux_anomalies integer,
    flux_avispcgdroitrsa integer,
    flux_avispcgpersonnes integer,
    flux_calculsdroitsrsa integer,
    flux_condsadmins integer,
    flux_controlesadministratifs integer,
    flux_creances integer,
    flux_creancesalimentaires integer,
    flux_derogations integer,
    flux_detailscalculsdroitsrsa integer,
    flux_detailsdroitsrsa integer,
    flux_detailsressourcesmensuelles integer,
    flux_dossiers_rsa integer,
    flux_dossierscaf integer,
    flux_evenements integer,
    flux_foyers integer,
    flux_grossesses integer,
    flux_identificationsflux integer,
    flux_infosagricoles integer,
    flux_liberalites integer,
    flux_personnes integer,
    flux_prestations integer,
    flux_rattachements integer,
    flux_reducsrsa integer,
    flux_ressources integer,
    flux_ressourcesmensuelles integer,
    flux_situationsdossiersrsa integer,
    flux_suspensionsdroits integer,
    flux_suspensionsversements integer,
    flux_transmissionsflux integer
);


ALTER TABLE administration.statintegrationbeneficiaire OWNER TO webrsa;

--
-- TOC entry 2013 (class 1259 OID 2022774)
-- Dependencies: 5
-- Name: statintegrationfinancier; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE statintegrationfinancier (
    nom_fichier character varying(100),
    date_debut timestamp(6) without time zone,
    date_fin timestamp(6) without time zone,
    elem_anomalies integer,
    elem_dossiers_rsa integer,
    elem_identificationsflux integer,
    elem_infosfinancieres integer,
    elem_totalisationsacomptes integer,
    elem_transmissionsflux integer,
    webav_anomalies integer,
    webav_dossiers_rsa integer,
    webav_identificationsflux integer,
    webav_infosfinancieres integer,
    webav_totalisationsacomptes integer,
    webav_transmissionsflux integer,
    webap_anomalies integer,
    webap_dossiers_rsa integer,
    webap_identificationsflux integer,
    webap_infosfinancieres integer,
    webap_totalisationsacomptes integer,
    webap_transmissionsflux integer,
    flux_anomalies integer,
    flux_dossiers_rsa integer,
    flux_identificationsflux integer,
    flux_infosfinancieres integer,
    flux_totalisationsacomptes integer,
    flux_transmissionsflux integer
);


ALTER TABLE administration.statintegrationfinancier OWNER TO webrsa;

--
-- TOC entry 2198 (class 1259 OID 2071529)
-- Dependencies: 5
-- Name: statintegrationinstruction; Type: TABLE; Schema: administration; Owner: webrsa; Tablespace: 
--

CREATE TABLE statintegrationinstruction (
    nom_fichier character varying(100),
    date_debut timestamp(6) without time zone,
    date_fin timestamp(6) without time zone,
    elem_activites integer,
    elem_adresses integer,
    elem_adresses_foyers integer,
    elem_aidesagricoles integer,
    elem_allocationssoutienfamilial integer,
    elem_conditionsactivitesprealables integer,
    elem_creancesalimentaires integer,
    elem_detailsaccosocfams integer,
    elem_detailsaccosocindis integer,
    elem_detailsdifdisps integer,
    elem_detailsdiflogs integer,
    elem_detailsdifsocs integer,
    elem_detailsnatmobs integer,
    elem_detailsressourcesmensuelles integer,
    elem_dossiers_rsa integer,
    elem_dossierscaf integer,
    elem_dsps integer,
    elem_foyers integer,
    elem_grossesses integer,
    elem_identificationsflux integer,
    elem_informationseti integer,
    elem_infosagricoles integer,
    elem_modescontact integer,
    elem_orientations integer,
    elem_paiementsfoyers integer,
    elem_parcours integer,
    elem_personnes integer,
    elem_prestations integer,
    elem_rattachements integer,
    elem_ressources integer,
    elem_ressourcesmensuelles integer,
    elem_suivisappuisorientation integer,
    elem_suivisinstruction integer,
    elem_titres_sejour integer,
    elem_transmissionsflux integer,
    webav_activites integer,
    webav_adresses integer,
    webav_adresses_foyers integer,
    webav_aidesagricoles integer,
    webav_allocationssoutienfamilial integer,
    webav_conditionsactivitesprealables integer,
    webav_creancesalimentaires integer,
    webav_detailsaccosocfams integer,
    webav_detailsaccosocindis integer,
    webav_detailsdifdisps integer,
    webav_detailsdiflogs integer,
    webav_detailsdifsocs integer,
    webav_detailsnatmobs integer,
    webav_detailsressourcesmensuelles integer,
    webav_dossiers_rsa integer,
    webav_dossierscaf integer,
    webav_dsps integer,
    webav_foyers integer,
    webav_grossesses integer,
    webav_identificationsflux integer,
    webav_informationseti integer,
    webav_infosagricoles integer,
    webav_modescontact integer,
    webav_orientations integer,
    webav_paiementsfoyers integer,
    webav_parcours integer,
    webav_personnes integer,
    webav_prestations integer,
    webav_rattachements integer,
    webav_ressources integer,
    webav_ressourcesmensuelles integer,
    webav_suivisappuisorientation integer,
    webav_suivisinstruction integer,
    webav_titres_sejour integer,
    webav_transmissionsflux integer,
    webap_activites integer,
    webap_adresses integer,
    webap_adresses_foyers integer,
    webap_aidesagricoles integer,
    webap_allocationssoutienfamilial integer,
    webap_conditionsactivitesprealables integer,
    webap_creancesalimentaires integer,
    webap_detailsaccosocfams integer,
    webap_detailsaccosocindis integer,
    webap_detailsdifdisps integer,
    webap_detailsdiflogs integer,
    webap_detailsdifsocs integer,
    webap_detailsnatmobs integer,
    webap_detailsressourcesmensuelles integer,
    webap_dossiers_rsa integer,
    webap_dossierscaf integer,
    webap_dsps integer,
    webap_foyers integer,
    webap_grossesses integer,
    webap_identificationsflux integer,
    webap_informationseti integer,
    webap_infosagricoles integer,
    webap_modescontact integer,
    webap_orientations integer,
    webap_paiementsfoyers integer,
    webap_parcours integer,
    webap_personnes integer,
    webap_prestations integer,
    webap_rattachements integer,
    webap_ressources integer,
    webap_ressourcesmensuelles integer,
    webap_suivisappuisorientation integer,
    webap_suivisinstruction integer,
    webap_titres_sejour integer,
    webap_transmissionsflux integer,
    flux_activites integer,
    flux_adresses integer,
    flux_adresses_foyers integer,
    flux_aidesagricoles integer,
    flux_allocationssoutienfamilial integer,
    flux_conditionsactivitesprealables integer,
    flux_creancesalimentaires integer,
    flux_detailsaccosocfams integer,
    flux_detailsaccosocindis integer,
    flux_detailsdifdisps integer,
    flux_detailsdiflogs integer,
    flux_detailsdifsocs integer,
    flux_detailsnatmobs integer,
    flux_detailsressourcesmensuelles integer,
    flux_dossiers_rsa integer,
    flux_dossierscaf integer,
    flux_dsps integer,
    flux_foyers integer,
    flux_grossesses integer,
    flux_identificationsflux integer,
    flux_informationseti integer,
    flux_infosagricoles integer,
    flux_modescontact integer,
    flux_orientations integer,
    flux_paiementsfoyers integer,
    flux_parcours integer,
    flux_personnes integer,
    flux_prestations integer,
    flux_rattachements integer,
    flux_ressources integer,
    flux_ressourcesmensuelles integer,
    flux_suivisappuisorientation integer,
    flux_suivisinstruction integer,
    flux_titres_sejour integer,
    flux_transmissionsflux integer
);


ALTER TABLE administration.statintegrationinstruction OWNER TO webrsa;

--
-- TOC entry 2014 (class 1259 OID 2022780)
-- Dependencies: 5
-- Name: visionneuses; Type: TABLE; Schema: administration; Owner: postgres; Tablespace: 
--

CREATE TABLE visionneuses (
    id integer NOT NULL,
    flux character(15),
    nomfic character(40),
    dtdeb timestamp without time zone,
    dtfin timestamp without time zone,
    nbrejete numeric(6,0),
    nbinser numeric(6,0),
    nbmaj numeric(6,0),
    dspcree numeric(6,0),
    dspmaj numeric(6,0),
    persmaj numeric,
    perscree numeric
);


ALTER TABLE administration.visionneuses OWNER TO postgres;

--
-- TOC entry 2015 (class 1259 OID 2022786)
-- Dependencies: 2014 5
-- Name: visionneuses_id_seq; Type: SEQUENCE; Schema: administration; Owner: postgres
--

CREATE SEQUENCE visionneuses_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE administration.visionneuses_id_seq OWNER TO postgres;

--
-- TOC entry 2744 (class 0 OID 0)
-- Dependencies: 2015
-- Name: visionneuses_id_seq; Type: SEQUENCE OWNED BY; Schema: administration; Owner: postgres
--

ALTER SEQUENCE visionneuses_id_seq OWNED BY visionneuses.id;


SET search_path = elementaire, pg_catalog;

--
-- TOC entry 2016 (class 1259 OID 2022788)
-- Dependencies: 6
-- Name: activites; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE activites (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    reg character(2),
    act character(3),
    paysact character(3),
    ddact date,
    dfact date,
    natcontrtra character(3),
    topcondadmeti boolean,
    hauremuscmic character(1)
);


ALTER TABLE elementaire.activites OWNER TO webrsa;

--
-- TOC entry 2017 (class 1259 OID 2022791)
-- Dependencies: 6 300
-- Name: adresses; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE adresses (
    infodemandersa integer NOT NULL,
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
    canton character varying(20),
    typeres character(1),
    topresetr type_booleannumber
);


ALTER TABLE elementaire.adresses OWNER TO webrsa;

--
-- TOC entry 2018 (class 1259 OID 2022794)
-- Dependencies: 6
-- Name: adresses_foyers; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE adresses_foyers (
    infodemandersa integer NOT NULL,
    rgadr character(2),
    dtemm date,
    typeadr character(1)
);


ALTER TABLE elementaire.adresses_foyers OWNER TO webrsa;

--
-- TOC entry 2019 (class 1259 OID 2022797)
-- Dependencies: 6
-- Name: aidesagricoles; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE aidesagricoles (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    annrefaideagri character(4),
    libnataideagri character varying(30),
    mtaideagri numeric(9,2)
);


ALTER TABLE elementaire.aidesagricoles OWNER TO webrsa;

--
-- TOC entry 2020 (class 1259 OID 2022800)
-- Dependencies: 6 300 300 300
-- Name: allocationssoutienfamilial; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE allocationssoutienfamilial (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    sitasf character(2),
    parassoasf character(1),
    ddasf date,
    dfasf date,
    topasf type_booleannumber,
    topdemasf type_booleannumber,
    topenfreconn type_booleannumber
);


ALTER TABLE elementaire.allocationssoutienfamilial OWNER TO webrsa;

--
-- TOC entry 2021 (class 1259 OID 2022803)
-- Dependencies: 6
-- Name: b_activites; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_activites (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    reg character(2),
    act character(3),
    ddact date,
    dfact date,
    natcontrtra character(3),
    topcondadmeti boolean
);


ALTER TABLE elementaire.b_activites OWNER TO webrsa;

--
-- TOC entry 2022 (class 1259 OID 2022806)
-- Dependencies: 6
-- Name: b_adresses; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_adresses (
    infosfoyerrsa integer NOT NULL,
    cle integer NOT NULL,
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
    pays character varying(3)
);


ALTER TABLE elementaire.b_adresses OWNER TO webrsa;

--
-- TOC entry 2023 (class 1259 OID 2022809)
-- Dependencies: 6
-- Name: b_adresses_foyers; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_adresses_foyers (
    infosfoyerrsa integer NOT NULL,
    cle integer NOT NULL,
    rgadr character(2),
    dtemm date,
    typeadr character(1)
);


ALTER TABLE elementaire.b_adresses_foyers OWNER TO webrsa;

--
-- TOC entry 2024 (class 1259 OID 2022812)
-- Dependencies: 6
-- Name: b_allocationssoutienfamilial; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_allocationssoutienfamilial (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    sitasf character(2),
    parassoasf character(1),
    ddasf date,
    dfasf date
);


ALTER TABLE elementaire.b_allocationssoutienfamilial OWNER TO webrsa;

--
-- TOC entry 2025 (class 1259 OID 2022815)
-- Dependencies: 6
-- Name: b_anomalies; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_anomalies (
    infosfoyerrsa integer NOT NULL,
    cle integer NOT NULL,
    libano character varying(50)
);


ALTER TABLE elementaire.b_anomalies OWNER TO webrsa;

--
-- TOC entry 2026 (class 1259 OID 2022818)
-- Dependencies: 6
-- Name: b_avispcgdroitrsa; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_avispcgdroitrsa (
    infosfoyerrsa integer NOT NULL,
    avisdestpairsa character(1),
    dtavisdestpairsa date,
    nomtie character varying(64),
    typeperstie character(1)
);


ALTER TABLE elementaire.b_avispcgdroitrsa OWNER TO webrsa;

--
-- TOC entry 2027 (class 1259 OID 2022821)
-- Dependencies: 6
-- Name: b_avispcgpersonnes; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_avispcgpersonnes (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    avisevaressnonsal character(1),
    dtsouressnonsal date,
    dtevaressnonsal date,
    mtevalressnonsal numeric(9,2),
    excl character(1),
    ddexcl date,
    dfexcl date
);


ALTER TABLE elementaire.b_avispcgpersonnes OWNER TO webrsa;

--
-- TOC entry 2028 (class 1259 OID 2022824)
-- Dependencies: 6 300
-- Name: b_calculsdroitsrsa; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_calculsdroitsrsa (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    mtpersressmenrsa numeric(9,2),
    mtpersabaneursa numeric(9,2),
    toppersdrodevorsa type_booleannumber
);


ALTER TABLE elementaire.b_calculsdroitsrsa OWNER TO webrsa;

--
-- TOC entry 2029 (class 1259 OID 2022827)
-- Dependencies: 6
-- Name: b_condsadmins; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_condsadmins (
    infosfoyerrsa integer NOT NULL,
    cle integer NOT NULL,
    aviscondadmrsa character(1),
    moticondadmrsa character(2),
    comm1condadmrsa character varying(60),
    comm2condadmrsa character varying(60),
    dteffaviscondadmrsa date
);


ALTER TABLE elementaire.b_condsadmins OWNER TO webrsa;

--
-- TOC entry 2030 (class 1259 OID 2022830)
-- Dependencies: 6
-- Name: b_controlesadministratifs; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_controlesadministratifs (
    infosfoyerrsa integer NOT NULL,
    cle integer NOT NULL,
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
    mtraprsacgcontro numeric(11,2)
);


ALTER TABLE elementaire.b_controlesadministratifs OWNER TO webrsa;

--
-- TOC entry 2031 (class 1259 OID 2022833)
-- Dependencies: 6
-- Name: b_creances; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_creances (
    infosfoyerrsa integer NOT NULL,
    cle integer NOT NULL,
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


ALTER TABLE elementaire.b_creances OWNER TO webrsa;

--
-- TOC entry 2032 (class 1259 OID 2022836)
-- Dependencies: 6
-- Name: b_creancesalimentaires; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_creancesalimentaires (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    etatcrealim character(2),
    ddcrealim date,
    dfcrealim date,
    orioblalim character(3),
    motidiscrealim character(3),
    commcrealim character varying(50),
    mtsancrealim numeric(9,2)
);


ALTER TABLE elementaire.b_creancesalimentaires OWNER TO webrsa;

--
-- TOC entry 2033 (class 1259 OID 2022839)
-- Dependencies: 6
-- Name: b_derogations; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_derogations (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    typedero character(3),
    avisdero character(1),
    ddavisdero date,
    dfavisdero date
);


ALTER TABLE elementaire.b_derogations OWNER TO webrsa;

--
-- TOC entry 2034 (class 1259 OID 2022842)
-- Dependencies: 6
-- Name: b_detailscalculsdroitsrsa; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_detailscalculsdroitsrsa (
    infosfoyerrsa integer NOT NULL,
    cle integer NOT NULL,
    natpf character(3),
    sousnatpf character(5),
    ddnatdro date,
    dfnatdro date,
    mtrsavers numeric(9,2),
    dtderrsavers date
);


ALTER TABLE elementaire.b_detailscalculsdroitsrsa OWNER TO webrsa;

--
-- TOC entry 2035 (class 1259 OID 2022845)
-- Dependencies: 6
-- Name: b_detailsdroitsrsa; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_detailsdroitsrsa (
    infosfoyerrsa integer NOT NULL,
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


ALTER TABLE elementaire.b_detailsdroitsrsa OWNER TO webrsa;

--
-- TOC entry 2036 (class 1259 OID 2022848)
-- Dependencies: 6
-- Name: b_detailsressourcesmensuelles; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_detailsressourcesmensuelles (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    cleressourcesmensuelles integer NOT NULL,
    cle integer NOT NULL,
    natress character(3),
    mtnatressmen numeric(10,2),
    abaneu character(1)
);


ALTER TABLE elementaire.b_detailsressourcesmensuelles OWNER TO webrsa;

--
-- TOC entry 2037 (class 1259 OID 2022851)
-- Dependencies: 6
-- Name: b_dossiers_rsa; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_dossiers_rsa (
    infosfoyerrsa integer NOT NULL,
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
    ddarrmut date,
    typeparte character(4),
    ideparte character(3),
    fonorgcedmut character(3),
    numorgcedmut character(3),
    matriculeorgcedmut character(15),
    fonorgprenmut character(3),
    numorgprenmut character(3),
    dddepamut date
);


ALTER TABLE elementaire.b_dossiers_rsa OWNER TO webrsa;

--
-- TOC entry 2038 (class 1259 OID 2022854)
-- Dependencies: 2473 6
-- Name: b_dossierscaf; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_dossierscaf (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    ddratdos date,
    dfratdos date,
    toprespdos boolean,
    numdemrsaprece character varying(11) DEFAULT NULL::character varying
);


ALTER TABLE elementaire.b_dossierscaf OWNER TO webrsa;

--
-- TOC entry 2039 (class 1259 OID 2022858)
-- Dependencies: 6
-- Name: b_evenements; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_evenements (
    infosfoyerrsa integer NOT NULL,
    cle integer NOT NULL,
    dtliq date,
    heuliq time without time zone,
    fg character varying(30)
);


ALTER TABLE elementaire.b_evenements OWNER TO webrsa;

--
-- TOC entry 2040 (class 1259 OID 2022861)
-- Dependencies: 6
-- Name: b_foyers; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_foyers (
    infosfoyerrsa integer NOT NULL,
    sitfam character(3),
    ddsitfam date
);


ALTER TABLE elementaire.b_foyers OWNER TO webrsa;

--
-- TOC entry 2041 (class 1259 OID 2022864)
-- Dependencies: 6
-- Name: b_grossesses; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_grossesses (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    ddgro date,
    dfgro date,
    dtdeclgro date,
    natfingro character(1)
);


ALTER TABLE elementaire.b_grossesses OWNER TO webrsa;

--
-- TOC entry 2042 (class 1259 OID 2022867)
-- Dependencies: 6
-- Name: b_identificationsflux_beneficiaire; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_identificationsflux_beneficiaire (
    applieme character(3),
    numversionapplieme character(4),
    typeflux character(1),
    natflux character(1),
    dtcreaflux date,
    heucreaflux time without time zone,
    dtref date
);


ALTER TABLE elementaire.b_identificationsflux_beneficiaire OWNER TO webrsa;

--
-- TOC entry 2043 (class 1259 OID 2022870)
-- Dependencies: 6
-- Name: b_infosagricoles; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_infosagricoles (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    mtbenagri numeric(10,2),
    dtbenagri date,
    regfisagri character(1)
);


ALTER TABLE elementaire.b_infosagricoles OWNER TO webrsa;

--
-- TOC entry 2044 (class 1259 OID 2022873)
-- Dependencies: 6
-- Name: b_liberalites; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_liberalites (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    mtlibernondecl numeric(9,2),
    dtabsdeclliber date
);


ALTER TABLE elementaire.b_liberalites OWNER TO webrsa;

--
-- TOC entry 2045 (class 1259 OID 2022876)
-- Dependencies: 6
-- Name: b_personnes; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_personnes (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    qual character varying(3),
    nom character varying(50),
    prenom character varying(50),
    nomnai character varying(50),
    prenom2 character varying(50),
    prenom3 character varying(50),
    nomcomnai character varying(26),
    dtnai date,
    rgnai integer,
    typedtnai character(1),
    nir character(15),
    topvalec boolean,
    sexe character(1),
    idassedic character varying(8)
);


ALTER TABLE elementaire.b_personnes OWNER TO webrsa;

--
-- TOC entry 2046 (class 1259 OID 2022879)
-- Dependencies: 6
-- Name: b_prestations; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_prestations (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    natprest character(3),
    rolepers character(3),
    topchapers boolean
);


ALTER TABLE elementaire.b_prestations OWNER TO webrsa;

--
-- TOC entry 2047 (class 1259 OID 2022882)
-- Dependencies: 6
-- Name: b_rattachements; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_rattachements (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    typepar character(3),
    nomnai character varying(28),
    prenom character varying(32),
    dtnai date,
    nir character(15)
);


ALTER TABLE elementaire.b_rattachements OWNER TO webrsa;

--
-- TOC entry 2048 (class 1259 OID 2022885)
-- Dependencies: 6
-- Name: b_reducsrsa; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_reducsrsa (
    infosfoyerrsa integer NOT NULL,
    cle integer NOT NULL,
    mtredrsa numeric(9,2),
    ddredrsa date,
    dfredrsa date
);


ALTER TABLE elementaire.b_reducsrsa OWNER TO webrsa;

--
-- TOC entry 2049 (class 1259 OID 2022888)
-- Dependencies: 6
-- Name: b_ressources; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_ressources (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    topressnul boolean,
    ddress date,
    dfress date
);


ALTER TABLE elementaire.b_ressources OWNER TO webrsa;

--
-- TOC entry 2050 (class 1259 OID 2022891)
-- Dependencies: 6
-- Name: b_ressourcesmensuelles; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_ressourcesmensuelles (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    moisress date,
    nbheumentra integer
);


ALTER TABLE elementaire.b_ressourcesmensuelles OWNER TO webrsa;

--
-- TOC entry 2051 (class 1259 OID 2022894)
-- Dependencies: 6
-- Name: b_situationsdossiersrsa; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_situationsdossiersrsa (
    infosfoyerrsa integer NOT NULL,
    etatdosrsa character(1),
    dtrefursa date,
    moticlorsa character(3),
    dtclorsa date,
    motirefursa character(3)
);


ALTER TABLE elementaire.b_situationsdossiersrsa OWNER TO webrsa;

--
-- TOC entry 2052 (class 1259 OID 2022897)
-- Dependencies: 6
-- Name: b_suspensionsdroits; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_suspensionsdroits (
    infosfoyerrsa integer NOT NULL,
    cle integer NOT NULL,
    motisusdrorsa character(2),
    ddsusdrorsa date,
    natgroupfsus character(3)
);


ALTER TABLE elementaire.b_suspensionsdroits OWNER TO webrsa;

--
-- TOC entry 2053 (class 1259 OID 2022900)
-- Dependencies: 6
-- Name: b_suspensionsversements; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_suspensionsversements (
    infosfoyerrsa integer NOT NULL,
    cle integer NOT NULL,
    motisusversrsa character(2),
    ddsusversrsa date
);


ALTER TABLE elementaire.b_suspensionsversements OWNER TO webrsa;

--
-- TOC entry 2054 (class 1259 OID 2022903)
-- Dependencies: 6
-- Name: b_transmissionsflux_beneficiaire; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_transmissionsflux_beneficiaire (
    nbtotdosrsatransm character varying(8),
    nbtotdosrsatransmano integer
);


ALTER TABLE elementaire.b_transmissionsflux_beneficiaire OWNER TO webrsa;

--
-- TOC entry 2199 (class 1259 OID 2146924)
-- Dependencies: 6 300
-- Name: conditionsactivitesprealables; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE conditionsactivitesprealables (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    ddcondactprea date,
    dfcondactprea date,
    topcondactprea type_booleannumber NOT NULL,
    nbheuacttot integer
);


ALTER TABLE elementaire.conditionsactivitesprealables OWNER TO webrsa;

--
-- TOC entry 2055 (class 1259 OID 2022906)
-- Dependencies: 6
-- Name: creancesalimentaires; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE creancesalimentaires (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
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


ALTER TABLE elementaire.creancesalimentaires OWNER TO webrsa;

--
-- TOC entry 2056 (class 1259 OID 2022909)
-- Dependencies: 2474 6 326
-- Name: detailsaccosocfams; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE detailsaccosocfams (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    nataccosocfam type_nataccosocfam NOT NULL,
    libautraccosocfam character varying(100) DEFAULT NULL::character varying
);


ALTER TABLE elementaire.detailsaccosocfams OWNER TO webrsa;

--
-- TOC entry 2057 (class 1259 OID 2022913)
-- Dependencies: 2475 6 328
-- Name: detailsaccosocindis; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE detailsaccosocindis (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    nataccosocindi type_nataccosocindi NOT NULL,
    libautraccosocindi character varying(100) DEFAULT NULL::character varying
);


ALTER TABLE elementaire.detailsaccosocindis OWNER TO webrsa;

--
-- TOC entry 2058 (class 1259 OID 2022917)
-- Dependencies: 6 308
-- Name: detailsdifdisps; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE detailsdifdisps (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    difdisp type_difdisp NOT NULL
);


ALTER TABLE elementaire.detailsdifdisps OWNER TO webrsa;

--
-- TOC entry 2059 (class 1259 OID 2022920)
-- Dependencies: 2476 6 310
-- Name: detailsdiflogs; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE detailsdiflogs (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    diflog type_diflog NOT NULL,
    libautrdiflog character varying(100) DEFAULT NULL::character varying
);


ALTER TABLE elementaire.detailsdiflogs OWNER TO webrsa;

--
-- TOC entry 2060 (class 1259 OID 2022924)
-- Dependencies: 2477 6 312
-- Name: detailsdifsocs; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE detailsdifsocs (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    difsoc type_difsoc NOT NULL,
    libautrdifsoc character varying(100) DEFAULT NULL::character varying
);


ALTER TABLE elementaire.detailsdifsocs OWNER TO webrsa;

--
-- TOC entry 2061 (class 1259 OID 2022928)
-- Dependencies: 6 332
-- Name: detailsnatmobs; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE detailsnatmobs (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    dsp_id integer NOT NULL,
    natmob type_natmob NOT NULL
);


ALTER TABLE elementaire.detailsnatmobs OWNER TO webrsa;

--
-- TOC entry 2062 (class 1259 OID 2022931)
-- Dependencies: 6
-- Name: detailsressourcesmensuelles; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE detailsressourcesmensuelles (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    cleressourcesmensuelles integer NOT NULL,
    cle integer NOT NULL,
    natress character(3),
    mtnatressmen numeric(10,2),
    abaneu character(1),
    dfpercress date,
    topprevsubsress boolean
);


ALTER TABLE elementaire.detailsressourcesmensuelles OWNER TO webrsa;

--
-- TOC entry 2063 (class 1259 OID 2022934)
-- Dependencies: 6
-- Name: dossiers_rsa; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE dossiers_rsa (
    infodemandersa integer NOT NULL,
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


ALTER TABLE elementaire.dossiers_rsa OWNER TO webrsa;

--
-- TOC entry 2064 (class 1259 OID 2022937)
-- Dependencies: 2478 6
-- Name: dossierscaf; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE dossierscaf (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    ddratdos date,
    dfratdos date,
    toprespdos boolean,
    numdemrsaprece character varying(11) DEFAULT NULL::character varying
);


ALTER TABLE elementaire.dossierscaf OWNER TO webrsa;

--
-- TOC entry 2065 (class 1259 OID 2022941)
-- Dependencies: 2479 2480 2481 2482 2483 2484 2485 2486 2487 2488 2489 2490 2491 6 356 300 300 348 300 350 350 350 344 342 300 300 300 318 302 300 314 320 300 260 300 300 348 300 300 300 330 306
-- Name: dsps; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE dsps (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    sitpersdemrsa type_sitpersdemrsa,
    topisogroouenf type_booleannumber,
    topdrorsarmiant type_booleannumber,
    drorsarmianta2 type_nos,
    topcouvsoc type_booleannumber,
    accosocfam type_nov,
    libcooraccosocfam character varying(250) DEFAULT NULL::character varying,
    accosocindi type_nov,
    libcooraccosocindi character varying(250) DEFAULT NULL::character varying,
    soutdemarsoc type_nov,
    nivetu type_nivetu,
    nivdipmaxobt type_nivdipmaxobt,
    annobtnivdipmax character(4) DEFAULT NULL::bpchar,
    topqualipro type_booleannumber,
    libautrqualipro character varying(100) DEFAULT NULL::character varying,
    topcompeextrapro type_booleannumber,
    libcompeextrapro character varying(100) DEFAULT NULL::character varying,
    topengdemarechemploi type_booleannumber,
    hispro type_hispro,
    libderact character varying(100) DEFAULT NULL::character varying,
    libsecactderact character varying(100) DEFAULT NULL::character varying,
    cessderact type_cessderact,
    topdomideract type_booleannumber,
    libactdomi character varying(100) DEFAULT NULL::character varying,
    libsecactdomi character varying(100) DEFAULT NULL::character varying,
    duractdomi type_duractdomi,
    inscdememploi type_inscdememploi,
    topisogrorechemploi type_booleannumber,
    accoemploi type_accoemploi,
    libcooraccoemploi character varying(100) DEFAULT NULL::character varying,
    topprojpro type_booleannumber,
    libemploirech character varying(250) DEFAULT NULL::character varying,
    libsecactrech character varying(250) DEFAULT NULL::character varying,
    topcreareprientre type_booleannumber,
    concoformqualiemploi type_nos,
    topmoyloco type_booleannumber,
    toppermicondub type_booleannumber,
    topautrpermicondu type_booleannumber,
    libautrpermicondu character varying(100) DEFAULT NULL::character varying,
    natlog type_natlog,
    demarlog type_demarlog
);


ALTER TABLE elementaire.dsps OWNER TO webrsa;

--
-- TOC entry 2066 (class 1259 OID 2022960)
-- Dependencies: 6
-- Name: f_adresses; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE f_adresses (
    infosfinancieresfoyerrsa integer NOT NULL,
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
    pays character varying(3)
);


ALTER TABLE elementaire.f_adresses OWNER TO webrsa;

--
-- TOC entry 2067 (class 1259 OID 2022963)
-- Dependencies: 6
-- Name: f_adresses_foyers; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE f_adresses_foyers (
    infosfinancieresfoyerrsa integer NOT NULL,
    rgadr character(2),
    dtemm date,
    typeadr character(1)
);


ALTER TABLE elementaire.f_adresses_foyers OWNER TO webrsa;

--
-- TOC entry 2068 (class 1259 OID 2022966)
-- Dependencies: 6
-- Name: f_anomalies; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE f_anomalies (
    infosfinancieresfoyerrsa integer NOT NULL,
    cle integer NOT NULL,
    libano character varying(50)
);


ALTER TABLE elementaire.f_anomalies OWNER TO webrsa;

--
-- TOC entry 2069 (class 1259 OID 2022969)
-- Dependencies: 6
-- Name: f_dossiers_rsa; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE f_dossiers_rsa (
    infosfinancieresfoyerrsa integer NOT NULL,
    fonorg character(3),
    numorg character(3),
    matricule character(15),
    typeparte character(4),
    ideparte character(3),
    dtdemrsa date,
    numdemrsa character varying(11)
);


ALTER TABLE elementaire.f_dossiers_rsa OWNER TO webrsa;

--
-- TOC entry 2070 (class 1259 OID 2022972)
-- Dependencies: 6
-- Name: f_dossierscaf; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE f_dossierscaf (
    infosfinancieresfoyerrsa integer NOT NULL,
    toprespdos boolean,
    ddratdos date,
    dfratdos date
);


ALTER TABLE elementaire.f_dossierscaf OWNER TO webrsa;

--
-- TOC entry 2071 (class 1259 OID 2022975)
-- Dependencies: 6
-- Name: f_identificationsflux_financier; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE f_identificationsflux_financier (
    applieme character(3),
    numversionapplieme character(4),
    typeflux character(1),
    natflux character(1),
    dtcreaflux date,
    heucreaflux time without time zone,
    dtref date
);


ALTER TABLE elementaire.f_identificationsflux_financier OWNER TO webrsa;

--
-- TOC entry 2072 (class 1259 OID 2022978)
-- Dependencies: 6
-- Name: f_infosfinancieres; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE f_infosfinancieres (
    infosfinancieresfoyerrsa integer NOT NULL,
    cle integer,
    moismoucompta date,
    type_allocation character varying(25),
    natpfcre character(3),
    rgcre integer,
    numintmoucompta integer,
    typeopecompta character(3),
    sensopecompta character(2),
    mtmoucompta numeric(11,2),
    dttraimoucompta date,
    heutraimoucompta time without time zone,
    ddregu date
);


ALTER TABLE elementaire.f_infosfinancieres OWNER TO webrsa;

--
-- TOC entry 2073 (class 1259 OID 2022981)
-- Dependencies: 6
-- Name: f_personnes; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE f_personnes (
    infosfinancieresfoyerrsa integer NOT NULL,
    qual character varying(3),
    nom character varying(50),
    nomnai character varying(50),
    prenom character varying(50),
    prenom2 character varying(50),
    prenom3 character varying(50),
    nomcomnai character varying(26),
    dtnai date,
    rgnai integer,
    typedtnai character(1),
    nir character(15),
    topvalec boolean,
    sexe character(1)
);


ALTER TABLE elementaire.f_personnes OWNER TO webrsa;

--
-- TOC entry 2074 (class 1259 OID 2022984)
-- Dependencies: 6
-- Name: f_totalisationsacomptes; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE f_totalisationsacomptes (
    type_totalisation character varying(30),
    mttotsoclrsa numeric(12,2),
    mttotsoclmajorsa numeric(12,2),
    mttotlocalrsa numeric(12,2),
    mttotrsa numeric(12,2)
);


ALTER TABLE elementaire.f_totalisationsacomptes OWNER TO webrsa;

--
-- TOC entry 2075 (class 1259 OID 2022987)
-- Dependencies: 6
-- Name: f_transmissionsflux_financier; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE f_transmissionsflux_financier (
    nbtotdosrsatransm integer,
    nbtotdosrsatransmano integer
);


ALTER TABLE elementaire.f_transmissionsflux_financier OWNER TO webrsa;

--
-- TOC entry 2076 (class 1259 OID 2022990)
-- Dependencies: 2492 6
-- Name: foyers; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE foyers (
    infodemandersa integer NOT NULL,
    sitfam character(3),
    ddsitfam date,
    typeocclog character(3),
    mtvallocterr numeric(9,2),
    mtvalloclog numeric(9,2),
    contefichliairsa text,
    mtestrsa numeric(9,2) DEFAULT NULL::numeric,
    raisoctieelectdom character varying(30)
);


ALTER TABLE elementaire.foyers OWNER TO webrsa;

--
-- TOC entry 2077 (class 1259 OID 2022997)
-- Dependencies: 6
-- Name: grossesses; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE grossesses (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    ddgro date,
    dfgro date,
    dtdeclgro date,
    natfingro character(1)
);


ALTER TABLE elementaire.grossesses OWNER TO webrsa;

--
-- TOC entry 2078 (class 1259 OID 2023000)
-- Dependencies: 6
-- Name: identificationsflux_instruction; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE identificationsflux_instruction (
    applieme character(3),
    numversionapplieme character(4),
    typeflux character(1),
    natflux character(1),
    dtcreaflux date,
    heucreaflux time without time zone,
    dtref date
);


ALTER TABLE elementaire.identificationsflux_instruction OWNER TO webrsa;

--
-- TOC entry 2079 (class 1259 OID 2023003)
-- Dependencies: 6
-- Name: informationseti; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE informationseti (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
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


ALTER TABLE elementaire.informationseti OWNER TO webrsa;

--
-- TOC entry 2080 (class 1259 OID 2023006)
-- Dependencies: 6
-- Name: infosagricoles; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE infosagricoles (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    mtbenagri numeric(10,2),
    dtbenagri date,
    regfisagri character(1)
);


ALTER TABLE elementaire.infosagricoles OWNER TO webrsa;

--
-- TOC entry 2081 (class 1259 OID 2023009)
-- Dependencies: 6
-- Name: modescontact; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE modescontact (
    infodemandersa integer NOT NULL,
    clemodescontacts integer NOT NULL,
    numtel character varying(11),
    numposte character varying(4),
    nattel character(1),
    matetel character(3),
    autorutitel character(1),
    adrelec character varying(78),
    autorutiadrelec character(1)
);


ALTER TABLE elementaire.modescontact OWNER TO webrsa;

--
-- TOC entry 2082 (class 1259 OID 2023012)
-- Dependencies: 2493 2494 2495 2496 2497 2498 2499 2500 2501 2502 2503 2504 6
-- Name: orientations; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE orientations (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    raisocorgorie character varying(60) DEFAULT NULL::character varying,
    numvoie character varying(6) DEFAULT NULL::character varying,
    typevoie character varying(4) DEFAULT NULL::character varying,
    nomvoie character varying(25) DEFAULT NULL::character varying,
    complideadr character varying(38) DEFAULT NULL::character varying,
    compladr character varying(26) DEFAULT NULL::character varying,
    lieudist character varying(32) DEFAULT NULL::character varying,
    codepos character varying(5) DEFAULT NULL::character varying,
    locaadr character varying(26) DEFAULT NULL::character varying,
    numtelorgorie character varying(10) DEFAULT NULL::character varying,
    dtrvorgorie date,
    hrrvorgorie time without time zone,
    libadrrvorgorie character varying(160) DEFAULT NULL::character varying,
    numtelrvorgorie character varying(10) DEFAULT NULL::character varying
);


ALTER TABLE elementaire.orientations OWNER TO webrsa;

--
-- TOC entry 2083 (class 1259 OID 2023027)
-- Dependencies: 6
-- Name: paiementsfoyers; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE paiementsfoyers (
    infodemandersa integer NOT NULL,
    topverstie boolean,
    modepai character(2),
    topribconj boolean,
    titurib character(3),
    nomprenomtiturib character varying(24),
    etaban character(5),
    guiban character(5),
    numcomptban character(11),
    clerib smallint,
    comban character varying(24),
    numdebiban character(4),
    numfiniban character(7),
    bic character(11)
);


ALTER TABLE elementaire.paiementsfoyers OWNER TO webrsa;

--
-- TOC entry 2084 (class 1259 OID 2023030)
-- Dependencies: 2505 2506 2507 2508 2509 2510 2511 2512 2513 2514 2515 2516 6 334 334 300 324
-- Name: parcours; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE parcours (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    natparcocal type_natparco,
    natparcomod type_natparco,
    toprefuparco type_booleannumber,
    motimodparco type_motimodparco,
    raisocorgdeciorie character varying(60) DEFAULT NULL::character varying,
    numvoie character varying(6) DEFAULT NULL::character varying,
    typevoie character varying(4) DEFAULT NULL::character varying,
    nomvoie character varying(25) DEFAULT NULL::character varying,
    complideadr character varying(38) DEFAULT NULL::character varying,
    compladr character varying(26) DEFAULT NULL::character varying,
    lieudist character varying(32) DEFAULT NULL::character varying,
    codepos character varying(5) DEFAULT NULL::character varying,
    locaadr character varying(26) DEFAULT NULL::character varying,
    numtelorgdeciorie character varying(10) DEFAULT NULL::character varying,
    dtrvorgdeciorie date,
    hrrvorgdeciorie time without time zone,
    libadrrvorgdeciorie character varying(160) DEFAULT NULL::character varying,
    numtelrvorgdeciorie character varying(10) DEFAULT NULL::character varying
);


ALTER TABLE elementaire.parcours OWNER TO webrsa;

--
-- TOC entry 2085 (class 1259 OID 2023045)
-- Dependencies: 6
-- Name: personnes; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE personnes (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    qual character varying(3),
    nom character varying(50),
    prenom character varying(50),
    nomnai character varying(50),
    prenom2 character varying(50),
    prenom3 character varying(50),
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
    idassedic character varying(8),
    numagenpoleemploi character(3),
    dtinscpoleemploi date
);


ALTER TABLE elementaire.personnes OWNER TO webrsa;

--
-- TOC entry 2086 (class 1259 OID 2023048)
-- Dependencies: 6
-- Name: prestations; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE prestations (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    natprest character(3),
    rolepers character(3)
);


ALTER TABLE elementaire.prestations OWNER TO webrsa;

--
-- TOC entry 2087 (class 1259 OID 2023051)
-- Dependencies: 6
-- Name: rattachements; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE rattachements (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    typepar character(3),
    nomnai character varying(28),
    prenom character varying(32),
    dtnai date,
    nir character(15)
);


ALTER TABLE elementaire.rattachements OWNER TO webrsa;

--
-- TOC entry 2088 (class 1259 OID 2023054)
-- Dependencies: 6
-- Name: ressources; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE ressources (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    topressnul boolean,
    mtpersressmenrsa numeric(10,2),
    ddress date,
    dfress date
);


ALTER TABLE elementaire.ressources OWNER TO webrsa;

--
-- TOC entry 2089 (class 1259 OID 2023057)
-- Dependencies: 6
-- Name: ressourcesmensuelles; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE ressourcesmensuelles (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    moisress date,
    nbheumentra integer,
    mtabaneu numeric(9,2)
);


ALTER TABLE elementaire.ressourcesmensuelles OWNER TO webrsa;

--
-- TOC entry 2090 (class 1259 OID 2023060)
-- Dependencies: 6 300 300 358
-- Name: suivisappuisorientation; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE suivisappuisorientation (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    topoblsocpro type_booleannumber,
    topsouhsocpro type_booleannumber,
    sitperssocpro type_sitperssocpro,
    dtenrsocpro date,
    dtenrparco date,
    dtenrorie date
);


ALTER TABLE elementaire.suivisappuisorientation OWNER TO webrsa;

--
-- TOC entry 2091 (class 1259 OID 2023063)
-- Dependencies: 2517 6
-- Name: suivisinstruction; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE suivisinstruction (
    infodemandersa integer NOT NULL,
    cleidentificationrsa integer NOT NULL,
    date_etat_instruction date,
    nomins character varying(28),
    prenomins character varying(32),
    numdepins character(3),
    typeserins character(1),
    numcomins character(3),
    numagrins integer,
    suiirsa character(2) DEFAULT NULL::bpchar
);


ALTER TABLE elementaire.suivisinstruction OWNER TO webrsa;

--
-- TOC entry 2092 (class 1259 OID 2023067)
-- Dependencies: 6
-- Name: titres_sejour; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE titres_sejour (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    dtentfra date,
    nattitsej character(3),
    menttitsej character(2),
    ddtitsej date,
    dftitsej date,
    numtitsej character varying(10),
    numduptitsej integer
);


ALTER TABLE elementaire.titres_sejour OWNER TO webrsa;

--
-- TOC entry 2093 (class 1259 OID 2023070)
-- Dependencies: 6
-- Name: transmissionsflux_instruction; Type: TABLE; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE TABLE transmissionsflux_instruction (
    nbtotdemrsatransm integer
);


ALTER TABLE elementaire.transmissionsflux_instruction OWNER TO webrsa;

SET search_path = staging, pg_catalog;

--
-- TOC entry 2094 (class 1259 OID 2023073)
-- Dependencies: 8
-- Name: activite; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE activite (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    reg character varying(2),
    act character varying(3),
    paysact character varying(3),
    ddact character varying(10),
    dfact character varying(10),
    natcontrtra character varying(3),
    hauremusmic character varying(1)
);


ALTER TABLE staging.activite OWNER TO webrsa;

--
-- TOC entry 2095 (class 1259 OID 2023076)
-- Dependencies: 8
-- Name: activiteeti; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE activiteeti (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    topcreaentre character varying(1),
    topaccre character varying(1),
    acteti character varying(1)
);


ALTER TABLE staging.activiteeti OWNER TO webrsa;

--
-- TOC entry 2096 (class 1259 OID 2023079)
-- Dependencies: 8
-- Name: adresse; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE adresse (
    infodemandersa integer NOT NULL,
    rgadr character varying(2),
    dtemm character varying(10),
    numvoie character varying(6),
    typevoie character varying(4),
    nomvoie character varying(25),
    complideadr character varying(38),
    compladr character varying(26),
    lieudist character varying(32),
    numcomrat character varying(5),
    numcomptt character varying(5),
    codepos character varying(5),
    locaadr character varying(26),
    pays character varying(3),
    typeadr character varying(1),
    typeres character varying(1),
    topresetr character varying(1)
);


ALTER TABLE staging.adresse OWNER TO webrsa;

--
-- TOC entry 2097 (class 1259 OID 2023082)
-- Dependencies: 8
-- Name: aidesagricoles; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE aidesagricoles (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    annrefaideagri character varying(4),
    libnataideagri character varying(30),
    mtaideagri character varying(9)
);


ALTER TABLE staging.aidesagricoles OWNER TO webrsa;

--
-- TOC entry 2098 (class 1259 OID 2023085)
-- Dependencies: 8
-- Name: asf; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE asf (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    sitasf character varying(2),
    topasf character varying(1),
    topdemasf character varying(1),
    topenfreconn character varying(1)
);


ALTER TABLE staging.asf OWNER TO webrsa;

--
-- TOC entry 2099 (class 1259 OID 2023088)
-- Dependencies: 8
-- Name: b_activite; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_activite (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    reg character varying(2),
    act character varying(3),
    ddact character varying(10),
    dfact character varying(10),
    natcontrtra character varying(3),
    topcondadmeti character varying(1)
);


ALTER TABLE staging.b_activite OWNER TO webrsa;

--
-- TOC entry 2100 (class 1259 OID 2023091)
-- Dependencies: 8
-- Name: b_adresse; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_adresse (
    infosfoyerrsa integer NOT NULL,
    cle integer NOT NULL,
    rgadr character varying(2),
    dtemm character varying(10),
    numvoie character varying(6),
    typevoie character varying(4),
    nomvoie character varying(25),
    complideadr character varying(38),
    compladr character varying(32),
    lieudist character varying(32),
    numcomrat character varying(5),
    numcomptt character varying(5),
    codepos character varying(5),
    locaadr character varying(26),
    pays character varying(3),
    typeadr character varying(1)
);


ALTER TABLE staging.b_adresse OWNER TO webrsa;

--
-- TOC entry 2101 (class 1259 OID 2023094)
-- Dependencies: 8
-- Name: b_anomalies; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_anomalies (
    infosfoyerrsa integer NOT NULL,
    cle integer NOT NULL,
    libano character varying(50)
);


ALTER TABLE staging.b_anomalies OWNER TO webrsa;

--
-- TOC entry 2102 (class 1259 OID 2023097)
-- Dependencies: 8
-- Name: b_asf; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_asf (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    sitasf character varying(2),
    parassoasf character varying(1),
    ddasf character varying(10),
    dfasf character varying(10)
);


ALTER TABLE staging.b_asf OWNER TO webrsa;

--
-- TOC entry 2103 (class 1259 OID 2023100)
-- Dependencies: 8
-- Name: b_avispcgpersonnes; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_avispcgpersonnes (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    avisevaressnonsal character varying(1),
    dtsouressnonsal character varying(10),
    dtevaressnonsal character varying(10),
    mtevalressnonsal character varying(12),
    excl character varying(1),
    ddexcl character varying(10),
    dfexcl character varying(10)
);


ALTER TABLE staging.b_avispcgpersonnes OWNER TO webrsa;

--
-- TOC entry 2104 (class 1259 OID 2023103)
-- Dependencies: 8
-- Name: b_benefices; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_benefices (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    mtbenagri character varying(12),
    regfisagri character varying(1),
    dtbenagri character varying(10)
);


ALTER TABLE staging.b_benefices OWNER TO webrsa;

--
-- TOC entry 2105 (class 1259 OID 2023106)
-- Dependencies: 8
-- Name: b_calculdroitrsa; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_calculdroitrsa (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    toppersdrodevorsa character varying(1),
    mtpersressmenrsa character varying(12),
    mtpersabaneursa character varying(12)
);


ALTER TABLE staging.b_calculdroitrsa OWNER TO webrsa;

--
-- TOC entry 2106 (class 1259 OID 2023109)
-- Dependencies: 8
-- Name: b_condsadmins; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_condsadmins (
    infosfoyerrsa integer NOT NULL,
    cle integer NOT NULL,
    aviscondadmrsa character varying(1),
    moticondadmrsa character varying(2),
    comm1condadmrsa character varying(60),
    comm2condadmrsa character varying(60),
    dteffaviscondadmrsa character varying(10)
);


ALTER TABLE staging.b_condsadmins OWNER TO webrsa;

--
-- TOC entry 2107 (class 1259 OID 2023112)
-- Dependencies: 8
-- Name: b_controlesadministratifs; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_controlesadministratifs (
    infosfoyerrsa integer NOT NULL,
    cle integer NOT NULL,
    dteffcibcontro character varying(10),
    cibcontro character(3),
    cibcontromsa character(3),
    dtdeteccontro character varying(10),
    dtclocontro character varying(10),
    libcibcontro character varying(45),
    famcibcontro character(2),
    natcibcontro character(3),
    commacontro character(3),
    typecontro character(2),
    typeimpaccontro character(1),
    mtindursacgcontro character varying(15),
    mtraprsacgcontro character varying(15)
);


ALTER TABLE staging.b_controlesadministratifs OWNER TO webrsa;

--
-- TOC entry 2108 (class 1259 OID 2023115)
-- Dependencies: 8
-- Name: b_creance; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_creance (
    infosfoyerrsa integer NOT NULL,
    cle integer NOT NULL,
    dtimplcre character varying(10),
    natcre character varying(3),
    rgcre character varying(3),
    motiindu character varying(2),
    oriindu character varying(2),
    respindu character varying(2),
    ddregucre character varying(10),
    dfregucre character varying(10),
    dtdercredcretrans character varying(10),
    mtsolreelcretrans character varying(14),
    mtinicre character varying(14)
);


ALTER TABLE staging.b_creance OWNER TO webrsa;

--
-- TOC entry 2109 (class 1259 OID 2023118)
-- Dependencies: 8
-- Name: b_creancealimentaire; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_creancealimentaire (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    etatcrealim character varying(2),
    ddcrealim character varying(10),
    dfcrealim character varying(10),
    orioblalim character varying(3),
    motidiscrealim character varying(3),
    commcrealim character varying(50),
    mtsancrealim character varying(12)
);


ALTER TABLE staging.b_creancealimentaire OWNER TO webrsa;

--
-- TOC entry 2110 (class 1259 OID 2023121)
-- Dependencies: 8
-- Name: b_demandermi; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_demandermi (
    infosfoyerrsa integer NOT NULL,
    dtdemrmi character varying(10),
    numdepinsrmi character varying(3),
    typeinsrmi character varying(1),
    numcominsrmi character varying(3),
    numagrinsrmi character varying(2),
    numdosinsrmi character varying(5),
    numcli character varying(3)
);


ALTER TABLE staging.b_demandermi OWNER TO webrsa;

--
-- TOC entry 2111 (class 1259 OID 2023124)
-- Dependencies: 8
-- Name: b_demandersa; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_demandersa (
    infosfoyerrsa integer NOT NULL,
    dtdemrsa character varying(10),
    numdemrsa character varying(11)
);


ALTER TABLE staging.b_demandersa OWNER TO webrsa;

--
-- TOC entry 2112 (class 1259 OID 2023127)
-- Dependencies: 8
-- Name: b_derogations; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_derogations (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    typedero character varying(3),
    avisdero character varying(1),
    ddavisdero character varying(10),
    dfavisdero character varying(10)
);


ALTER TABLE staging.b_derogations OWNER TO webrsa;

--
-- TOC entry 2113 (class 1259 OID 2023130)
-- Dependencies: 8
-- Name: b_detailressourcesmensuelles; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_detailressourcesmensuelles (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    cleressourcesmensuelles integer NOT NULL,
    cle integer NOT NULL,
    natress character varying(3),
    mtnatressmen character varying(12),
    abaneu character varying(1)
);


ALTER TABLE staging.b_detailressourcesmensuelles OWNER TO webrsa;

--
-- TOC entry 2114 (class 1259 OID 2023133)
-- Dependencies: 8
-- Name: b_detailscalculsdroitrsa; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_detailscalculsdroitrsa (
    infosfoyerrsa integer NOT NULL,
    cle integer NOT NULL,
    natpf character varying(3),
    sousnatpf character varying(5),
    ddnatdro character varying(10),
    dfnatdro character varying(10),
    mtrsavers character varying(12),
    dtderrsavers character varying(10)
);


ALTER TABLE staging.b_detailscalculsdroitrsa OWNER TO webrsa;

--
-- TOC entry 2115 (class 1259 OID 2023136)
-- Dependencies: 8
-- Name: b_detailsdroitrsa; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_detailsdroitrsa (
    infosfoyerrsa integer NOT NULL,
    topsansdomfixe character varying(1),
    nbenfautcha character varying(2),
    oridemrsa character varying(3),
    dtoridemrsa character varying(10),
    topfoydrodevorsa character varying(1),
    ddelecal character varying(10),
    dfelecal character varying(10),
    mtrevminigararsa character varying(12),
    mtpentrsa character varying(12),
    mtlocalrsa character varying(12),
    mtrevgararsa character varying(12),
    mtpfrsa character varying(12),
    mtalrsa character varying(12),
    mtressmenrsa character varying(12),
    mtsanoblalimrsa character varying(12),
    mtredhosrsa character varying(12),
    mtredcgrsa character varying(12),
    mtcumintegrsa character varying(12),
    mtabaneursa character varying(12),
    mttotdrorsa character varying(12)
);


ALTER TABLE staging.b_detailsdroitrsa OWNER TO webrsa;

--
-- TOC entry 2116 (class 1259 OID 2023139)
-- Dependencies: 8
-- Name: b_dossiercaf; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_dossiercaf (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    toprespdos character varying(1),
    ddratdos character varying(10),
    dfratdos character varying(10),
    numdemrsaprece character varying(11)
);


ALTER TABLE staging.b_dossiercaf OWNER TO webrsa;

--
-- TOC entry 2117 (class 1259 OID 2023142)
-- Dependencies: 8
-- Name: b_dossierpoleemploi; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_dossierpoleemploi (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    idassedic character varying(8)
);


ALTER TABLE staging.b_dossierpoleemploi OWNER TO webrsa;

--
-- TOC entry 2118 (class 1259 OID 2023145)
-- Dependencies: 8
-- Name: b_evenement; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_evenement (
    infosfoyerrsa integer NOT NULL,
    cle integer NOT NULL,
    dtliq character varying(10),
    heuliq character varying(13),
    fg character varying(9)
);


ALTER TABLE staging.b_evenement OWNER TO webrsa;

--
-- TOC entry 2119 (class 1259 OID 2023148)
-- Dependencies: 8
-- Name: b_generaliteressourcesmensuelles; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_generaliteressourcesmensuelles (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    moisress character varying(10),
    nbheumentra character varying(3)
);


ALTER TABLE staging.b_generaliteressourcesmensuelles OWNER TO webrsa;

--
-- TOC entry 2120 (class 1259 OID 2023151)
-- Dependencies: 8
-- Name: b_generaliteressourcestrimestre; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_generaliteressourcestrimestre (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    topressnul character varying(1),
    ddress character varying(10),
    dfress character varying(10)
);


ALTER TABLE staging.b_generaliteressourcestrimestre OWNER TO webrsa;

--
-- TOC entry 2121 (class 1259 OID 2023154)
-- Dependencies: 8
-- Name: b_grossesse; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_grossesse (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    ddgro character varying(10),
    dfgro character varying(10),
    dtdeclgro character varying(10),
    natfingro character varying(1)
);


ALTER TABLE staging.b_grossesse OWNER TO webrsa;

--
-- TOC entry 2122 (class 1259 OID 2023157)
-- Dependencies: 8
-- Name: b_identification; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_identification (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    qual character varying(3),
    nom character varying(28),
    nomnai character varying(28),
    prenom character varying(32),
    prenom2 character varying(15),
    prenom3 character varying(15),
    nomcomnai character varying(26),
    dtnai character varying(10),
    rgnai character varying(1),
    typedtnai character varying(1),
    nir character varying(15),
    topvaliec character varying(1),
    sexe character varying(1)
);


ALTER TABLE staging.b_identification OWNER TO webrsa;

--
-- TOC entry 2123 (class 1259 OID 2023160)
-- Dependencies: 8
-- Name: b_identificationflux_beneficiaire; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_identificationflux_beneficiaire (
    applieme character varying(3),
    numversionapplieme character varying(4),
    typeflux character varying(1),
    natflux character varying(1),
    dtcreaflux character varying(10),
    heucreaflux character varying(13),
    dtref character varying(10)
);


ALTER TABLE staging.b_identificationflux_beneficiaire OWNER TO webrsa;

--
-- TOC entry 2124 (class 1259 OID 2023163)
-- Dependencies: 8
-- Name: b_liberalite; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_liberalite (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    mtlibernondecl character varying(12),
    dtabsdeclliber character varying(10)
);


ALTER TABLE staging.b_liberalite OWNER TO webrsa;

--
-- TOC entry 2125 (class 1259 OID 2023166)
-- Dependencies: 8
-- Name: b_organisme; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_organisme (
    infosfoyerrsa integer NOT NULL,
    fonorg character varying(3),
    numorg character varying(3),
    matricule character varying(15)
);


ALTER TABLE staging.b_organisme OWNER TO webrsa;

--
-- TOC entry 2126 (class 1259 OID 2023169)
-- Dependencies: 8
-- Name: b_organismecedant; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_organismecedant (
    infosfoyerrsa integer NOT NULL,
    fonorgcedmut character varying(3),
    numorgcedmut character varying(3),
    matriculeorgcedmut character varying(15),
    ddarrmut character varying(10)
);


ALTER TABLE staging.b_organismecedant OWNER TO webrsa;

--
-- TOC entry 2127 (class 1259 OID 2023172)
-- Dependencies: 8
-- Name: b_organismeprenant; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_organismeprenant (
    infosfoyerrsa integer NOT NULL,
    fonorgprenmut character varying(3),
    numorgprenmut character varying(3),
    dddepamut character varying(10)
);


ALTER TABLE staging.b_organismeprenant OWNER TO webrsa;

--
-- TOC entry 2128 (class 1259 OID 2023175)
-- Dependencies: 8
-- Name: b_paiementtiers; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_paiementtiers (
    infosfoyerrsa integer NOT NULL,
    avisdestpairsa character varying(1),
    dtavisdestpairsa character varying(10),
    nomtie character varying(64),
    typeperstie character varying(1)
);


ALTER TABLE staging.b_paiementtiers OWNER TO webrsa;

--
-- TOC entry 2129 (class 1259 OID 2023178)
-- Dependencies: 8
-- Name: b_partenaire; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_partenaire (
    infosfoyerrsa integer NOT NULL,
    typeparte character varying(4),
    ideparte character varying(3)
);


ALTER TABLE staging.b_partenaire OWNER TO webrsa;

--
-- TOC entry 2130 (class 1259 OID 2023181)
-- Dependencies: 8
-- Name: b_prestations; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_prestations (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    natprest character varying(3),
    rolepers character varying(3),
    topchapers character varying(1)
);


ALTER TABLE staging.b_prestations OWNER TO webrsa;

--
-- TOC entry 2131 (class 1259 OID 2023184)
-- Dependencies: 8
-- Name: b_rattachement; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_rattachement (
    infosfoyerrsa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    nomnai character varying(28),
    prenom character varying(32),
    dtnai character varying(10),
    nir character varying(15),
    typepar character varying(3)
);


ALTER TABLE staging.b_rattachement OWNER TO webrsa;

--
-- TOC entry 2132 (class 1259 OID 2023187)
-- Dependencies: 8
-- Name: b_reducsrsa; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_reducsrsa (
    infosfoyerrsa integer NOT NULL,
    cle integer NOT NULL,
    mtredrsa character varying(12),
    ddredrsa character varying(10),
    dfredrsa character varying(10)
);


ALTER TABLE staging.b_reducsrsa OWNER TO webrsa;

--
-- TOC entry 2133 (class 1259 OID 2023190)
-- Dependencies: 8
-- Name: b_sitdossiersrsa; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_sitdossiersrsa (
    infosfoyerrsa integer NOT NULL,
    etatdosrsa character varying(1),
    dtrefursa character varying(10),
    moticlorsa character varying(3),
    dtclorsa character varying(10),
    moticlorsa_ant character varying(3),
    dtclorsa_ant character varying(10),
    motirefursa character(3)
);


ALTER TABLE staging.b_sitdossiersrsa OWNER TO webrsa;

--
-- TOC entry 2134 (class 1259 OID 2023193)
-- Dependencies: 8
-- Name: b_situationfamille; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_situationfamille (
    infosfoyerrsa integer NOT NULL,
    sitfam character varying(3),
    ddsitfam character varying(10)
);


ALTER TABLE staging.b_situationfamille OWNER TO webrsa;

--
-- TOC entry 2135 (class 1259 OID 2023196)
-- Dependencies: 8
-- Name: b_suspensiondroits; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_suspensiondroits (
    infosfoyerrsa integer NOT NULL,
    cle integer NOT NULL,
    motisusdrorsa character varying(2),
    ddsusdrorsa character varying(10),
    natgroupfsus character(3)
);


ALTER TABLE staging.b_suspensiondroits OWNER TO webrsa;

--
-- TOC entry 2136 (class 1259 OID 2023199)
-- Dependencies: 8
-- Name: b_suspensionversements; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_suspensionversements (
    infosfoyerrsa integer NOT NULL,
    cle integer NOT NULL,
    motisusversrsa character varying(2),
    ddsusversrsa character varying(10)
);


ALTER TABLE staging.b_suspensionversements OWNER TO webrsa;

--
-- TOC entry 2137 (class 1259 OID 2023202)
-- Dependencies: 8
-- Name: b_transmissionflux_beneficiaire; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE b_transmissionflux_beneficiaire (
    nbtotdosrsatransm character varying(8),
    nbtotdosrsatransmano character varying(8)
);


ALTER TABLE staging.b_transmissionflux_beneficiaire OWNER TO webrsa;

--
-- TOC entry 2138 (class 1259 OID 2023205)
-- Dependencies: 8
-- Name: benefices; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE benefices (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    mtbenagri character varying(10),
    dtbenagri character varying(10),
    regfisagri character varying(1)
);


ALTER TABLE staging.benefices OWNER TO webrsa;

--
-- TOC entry 2139 (class 1259 OID 2023208)
-- Dependencies: 8
-- Name: chiffreaffaire; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE chiffreaffaire (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    ddchiaffaeti character varying(10),
    dfchiaffaeti character varying(10),
    mtchiaffaeti character varying(11)
);


ALTER TABLE staging.chiffreaffaire OWNER TO webrsa;

--
-- TOC entry 2140 (class 1259 OID 2023211)
-- Dependencies: 8
-- Name: commundifficultelogement; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE commundifficultelogement (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    natlog character varying(4),
    demarlog character varying(4)
);


ALTER TABLE staging.commundifficultelogement OWNER TO webrsa;

--
-- TOC entry 2141 (class 1259 OID 2023214)
-- Dependencies: 8
-- Name: communmobilite; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE communmobilite (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    topmoyloco character varying(1),
    toppermicondub character varying(1),
    topautrpermicondu character varying(1),
    libautrpermicondu character varying(100)
);


ALTER TABLE staging.communmobilite OWNER TO webrsa;

--
-- TOC entry 2142 (class 1259 OID 2023217)
-- Dependencies: 8
-- Name: communsituationsociale; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE communsituationsociale (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    accosocfam character varying(1),
    libcooraccosocfam character varying(250),
    accosocindi character varying(1),
    libcooraccosocindi character varying(250),
    soutdemarsoc character varying(1)
);


ALTER TABLE staging.communsituationsociale OWNER TO webrsa;

--
-- TOC entry 2197 (class 1259 OID 2050086)
-- Dependencies: 8
-- Name: conditionactiviteprealable; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE conditionactiviteprealable (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    ddcondactprea character varying(10),
    dfcondactprea character varying(10),
    topcondactprea character varying(10),
    nbheuacttot character varying(10)
);


ALTER TABLE staging.conditionactiviteprealable OWNER TO webrsa;

--
-- TOC entry 2143 (class 1259 OID 2023223)
-- Dependencies: 8
-- Name: creancealimentaire; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE creancealimentaire (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    topdemdisproccrealim character varying(1),
    engproccrealim character varying(1),
    etatcrealim character varying(2),
    ddcrealim character varying(10),
    dfcrealim character varying(10),
    orioblalim character varying(3),
    motidiscrealim character varying(3),
    commcrealim character varying(50),
    mtsancrealim character varying(10),
    verspa character varying(1),
    topjugpa character varying(1)
);


ALTER TABLE staging.creancealimentaire OWNER TO webrsa;

--
-- TOC entry 2144 (class 1259 OID 2023226)
-- Dependencies: 8
-- Name: demandersa; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE demandersa (
    infodemandersa integer NOT NULL,
    dtdemrsa character varying(10),
    numdemrsa character varying(11)
);


ALTER TABLE staging.demandersa OWNER TO webrsa;

--
-- TOC entry 2145 (class 1259 OID 2023229)
-- Dependencies: 8
-- Name: destinataire; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE destinataire (
    infodemandersa integer NOT NULL,
    topverstie character varying(1),
    modepai character varying(2),
    topribconj character varying(1)
);


ALTER TABLE staging.destinataire OWNER TO webrsa;

--
-- TOC entry 2146 (class 1259 OID 2023232)
-- Dependencies: 8
-- Name: detailaccompagnementsocialfamilial; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE detailaccompagnementsocialfamilial (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    nataccosocfam character varying(4),
    libautraccosocfam character varying(100)
);


ALTER TABLE staging.detailaccompagnementsocialfamilial OWNER TO webrsa;

--
-- TOC entry 2147 (class 1259 OID 2023235)
-- Dependencies: 8
-- Name: detailaccosocindividuel; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE detailaccosocindividuel (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    nataccosocindi character varying(4),
    libautraccosocindi character varying(100)
);


ALTER TABLE staging.detailaccosocindividuel OWNER TO webrsa;

--
-- TOC entry 2148 (class 1259 OID 2023238)
-- Dependencies: 8
-- Name: detaildifficultedisponibilite; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE detaildifficultedisponibilite (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    difdisp character varying(4)
);


ALTER TABLE staging.detaildifficultedisponibilite OWNER TO webrsa;

--
-- TOC entry 2149 (class 1259 OID 2023241)
-- Dependencies: 8
-- Name: detaildifficultelogement; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE detaildifficultelogement (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    diflog character varying(4),
    libautrdiflog character varying(100)
);


ALTER TABLE staging.detaildifficultelogement OWNER TO webrsa;

--
-- TOC entry 2150 (class 1259 OID 2023244)
-- Dependencies: 8
-- Name: detaildifficultesituationsociale; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE detaildifficultesituationsociale (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    difsoc character varying(4),
    libautrdifsoc character varying(100)
);


ALTER TABLE staging.detaildifficultesituationsociale OWNER TO webrsa;

--
-- TOC entry 2151 (class 1259 OID 2023247)
-- Dependencies: 8
-- Name: detailmobilite; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE detailmobilite (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    natmob character varying(4)
);


ALTER TABLE staging.detailmobilite OWNER TO webrsa;

--
-- TOC entry 2152 (class 1259 OID 2023250)
-- Dependencies: 8
-- Name: detailressourcesmensuelles; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE detailressourcesmensuelles (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    cleressourcesmensuelles integer NOT NULL,
    cle integer NOT NULL,
    natress character varying(10),
    mtnatressmen character varying(9),
    dfpercress character varying(10),
    toprevsubsress character varying(10)
);


ALTER TABLE staging.detailressourcesmensuelles OWNER TO webrsa;

--
-- TOC entry 2153 (class 1259 OID 2023253)
-- Dependencies: 8
-- Name: determinationparcours; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE determinationparcours (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    natparcocal character varying(2),
    natparcomod character varying(2),
    toprefuparco character varying(1),
    motimodparco character varying(2)
);


ALTER TABLE staging.determinationparcours OWNER TO webrsa;

--
-- TOC entry 2154 (class 1259 OID 2023256)
-- Dependencies: 8
-- Name: disponibilteemploi; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE disponibilteemploi (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    topengdemarechemploi character varying(1)
);


ALTER TABLE staging.disponibilteemploi OWNER TO webrsa;

--
-- TOC entry 2155 (class 1259 OID 2023259)
-- Dependencies: 8
-- Name: dossiercaf; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE dossiercaf (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    ddratdos character varying(10),
    dfratdos character varying(10)
);


ALTER TABLE staging.dossiercaf OWNER TO webrsa;

--
-- TOC entry 2156 (class 1259 OID 2023262)
-- Dependencies: 8
-- Name: dossierpoleemploi; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE dossierpoleemploi (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    idassedic character varying(8),
    numagenpoleemploi character(3),
    dtinscpoleemploi character varying(10)
);


ALTER TABLE staging.dossierpoleemploi OWNER TO webrsa;

--
-- TOC entry 2157 (class 1259 OID 2023265)
-- Dependencies: 8
-- Name: electiondomicile; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE electiondomicile (
    infodemandersa integer NOT NULL,
    raisoctieelectdom character varying(30)
);


ALTER TABLE staging.electiondomicile OWNER TO webrsa;

--
-- TOC entry 2158 (class 1259 OID 2023268)
-- Dependencies: 8
-- Name: elementsfiscaux; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE elementsfiscaux (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    regfiseti character varying(1),
    topbeneti character varying(1),
    regfisetia1 character varying(1),
    mtbenetia1 character varying(9),
    mtamoeti character varying(9),
    mtplusvaluet character varying(9),
    topevoreveti character varying(1),
    libevoreveti character varying(30),
    topressevaeti character varying(1)
);


ALTER TABLE staging.elementsfiscaux OWNER TO webrsa;

--
-- TOC entry 2159 (class 1259 OID 2023271)
-- Dependencies: 8
-- Name: employes; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE employes (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    topempl1ax character varying(1),
    topstag1ax character varying(1),
    topsansempl character varying(1)
);


ALTER TABLE staging.employes OWNER TO webrsa;

--
-- TOC entry 2160 (class 1259 OID 2023274)
-- Dependencies: 8
-- Name: f_adresse; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE f_adresse (
    infosfinancieresfoyerrsa integer NOT NULL,
    rgadr character varying(2),
    dtemm character varying(10),
    numvoie character varying(6),
    typevoie character varying(4),
    nomvoie character varying(25),
    complideadr character varying(38),
    compladr character varying(32),
    lieudist character varying(32),
    numcomrat character varying(5),
    numcomptt character varying(5),
    codepos character varying(5),
    locaadr character varying(26),
    pays character varying(3),
    typeadr character varying(1)
);


ALTER TABLE staging.f_adresse OWNER TO webrsa;

--
-- TOC entry 2161 (class 1259 OID 2023277)
-- Dependencies: 8
-- Name: f_anomalie; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE f_anomalie (
    infosfinancieresfoyerrsa integer NOT NULL,
    cle integer NOT NULL,
    libano character varying(50)
);


ALTER TABLE staging.f_anomalie OWNER TO webrsa;

--
-- TOC entry 2162 (class 1259 OID 2023280)
-- Dependencies: 8
-- Name: f_demandersa; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE f_demandersa (
    infosfinancieresfoyerrsa integer NOT NULL,
    dtdemrsa character varying(10),
    numdemrsa character varying(11)
);


ALTER TABLE staging.f_demandersa OWNER TO webrsa;

--
-- TOC entry 2163 (class 1259 OID 2023283)
-- Dependencies: 8
-- Name: f_detailaccomptersa; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE f_detailaccomptersa (
    infosfinancieresfoyerrsa integer NOT NULL,
    type_allocation character varying(50) NOT NULL,
    cle integer NOT NULL,
    moismoucompta character varying(10),
    natpfcre character varying(3),
    rgcre character varying(3),
    numintmoucompta character varying(7),
    typeopecompta character varying(3),
    sensopecompta character varying(2),
    mtmoucompta character varying(14),
    ddregu character varying(10),
    dttraimoucompta character varying(10),
    heutraimoucompta character varying(13)
);


ALTER TABLE staging.f_detailaccomptersa OWNER TO webrsa;

--
-- TOC entry 2164 (class 1259 OID 2023286)
-- Dependencies: 8
-- Name: f_dossiercaf; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE f_dossiercaf (
    infosfinancieresfoyerrsa integer NOT NULL,
    toprespdos character varying(1),
    ddratdos character varying(10),
    dfratdos character varying(10)
);


ALTER TABLE staging.f_dossiercaf OWNER TO webrsa;

--
-- TOC entry 2165 (class 1259 OID 2023289)
-- Dependencies: 8
-- Name: f_identification; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE f_identification (
    infosfinancieresfoyerrsa integer NOT NULL,
    qual character varying(3),
    nom character varying(28),
    nomnai character varying(28),
    prenom character varying(32),
    prenom2 character varying(15),
    prenom3 character varying(15),
    nomcomnai character varying(26),
    dtnai character varying(10),
    rgnai character varying(1),
    typedtnai character varying(1),
    nir character varying(15),
    topvaliec character varying(1),
    sexe character varying(1)
);


ALTER TABLE staging.f_identification OWNER TO webrsa;

--
-- TOC entry 2166 (class 1259 OID 2023292)
-- Dependencies: 8
-- Name: f_identificationflux_financier; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE f_identificationflux_financier (
    applieme character varying(3),
    numversionapplieme character varying(4),
    typeflux character varying(1),
    natflux character varying(1),
    dtcreaflux character varying(10),
    heucreaflux character varying(13),
    dtref character varying(10)
);


ALTER TABLE staging.f_identificationflux_financier OWNER TO webrsa;

--
-- TOC entry 2167 (class 1259 OID 2023295)
-- Dependencies: 8
-- Name: f_organisme; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE f_organisme (
    infosfinancieresfoyerrsa integer NOT NULL,
    fonorg character varying(3),
    numorg character varying(3),
    matricule character varying(15)
);


ALTER TABLE staging.f_organisme OWNER TO webrsa;

--
-- TOC entry 2168 (class 1259 OID 2023298)
-- Dependencies: 8
-- Name: f_partenaire; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE f_partenaire (
    infosfinancieresfoyerrsa integer NOT NULL,
    typeparte character varying(4),
    ideparte character varying(3)
);


ALTER TABLE staging.f_partenaire OWNER TO webrsa;

--
-- TOC entry 2169 (class 1259 OID 2023301)
-- Dependencies: 8
-- Name: f_totalisationacompte; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE f_totalisationacompte (
    type_allocation character varying(50) NOT NULL,
    mttotsoclrsa character varying(14),
    mttotsoclmajorsa character varying(14),
    mttotlocalrsa character varying(14),
    mttotrsa character varying(14)
);


ALTER TABLE staging.f_totalisationacompte OWNER TO webrsa;

--
-- TOC entry 2170 (class 1259 OID 2023304)
-- Dependencies: 8
-- Name: f_transmissionflux_financier; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE f_transmissionflux_financier (
    nbtotdosrsatransm character varying(8),
    nbtotdosrsatransmano character varying(8)
);


ALTER TABLE staging.f_transmissionflux_financier OWNER TO webrsa;

--
-- TOC entry 2171 (class 1259 OID 2023307)
-- Dependencies: 8
-- Name: ficheliaison; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE ficheliaison (
    infodemandersa integer NOT NULL,
    contefichliairsa character varying(640)
);


ALTER TABLE staging.ficheliaison OWNER TO webrsa;

--
-- TOC entry 2172 (class 1259 OID 2023313)
-- Dependencies: 8
-- Name: generalitedspp; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE generalitedspp (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    sitpersdemrsa character varying(4),
    topisogroouenf character varying(1),
    topdrorsarmiant character varying(1),
    drorsarmianta2 character varying(1),
    topcouvsoc character varying(1)
);


ALTER TABLE staging.generalitedspp OWNER TO webrsa;

--
-- TOC entry 2173 (class 1259 OID 2023316)
-- Dependencies: 8
-- Name: generaliteressourcesmensuelles; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE generaliteressourcesmensuelles (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    moisress character varying(10),
    nbheumentra character varying(3)
);


ALTER TABLE staging.generaliteressourcesmensuelles OWNER TO webrsa;

--
-- TOC entry 2174 (class 1259 OID 2023319)
-- Dependencies: 8
-- Name: generaliteressourcestrimestre; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE generaliteressourcestrimestre (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    topressnul character varying(10),
    ddress character varying(10),
    dfress character varying(10)
);


ALTER TABLE staging.generaliteressourcestrimestre OWNER TO webrsa;

--
-- TOC entry 2175 (class 1259 OID 2023322)
-- Dependencies: 8
-- Name: grossesse; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE grossesse (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    ddgro character varying(10),
    dtdeclgro character varying(10)
);


ALTER TABLE staging.grossesse OWNER TO webrsa;

--
-- TOC entry 2176 (class 1259 OID 2023325)
-- Dependencies: 8
-- Name: identification; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE identification (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    qual character varying(3),
    nom character varying(28),
    nomnai character varying(28),
    prenom character varying(32),
    prenom2 character varying(15),
    prenom3 character varying(15),
    nomcomnai character varying(26),
    dtnai character varying(10),
    rgnai character varying(1),
    typedtnai character varying(1),
    nir character varying(15),
    sexe character varying(1)
);


ALTER TABLE staging.identification OWNER TO webrsa;

--
-- TOC entry 2177 (class 1259 OID 2023328)
-- Dependencies: 8
-- Name: identificationflux_instruction; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE identificationflux_instruction (
    applieme character varying(3),
    numversionapplieme character varying(4),
    typeflux character varying(1),
    natflux character varying(1),
    dtcreaflux character varying(10),
    heucreaflux character varying(13)
);


ALTER TABLE staging.identificationflux_instruction OWNER TO webrsa;

--
-- TOC entry 2178 (class 1259 OID 2023331)
-- Dependencies: 8
-- Name: logement; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE logement (
    infodemandersa integer NOT NULL,
    typeocclog character varying(3),
    mtvallocterr character varying(9),
    mtvalloclog character varying(9)
);


ALTER TABLE staging.logement OWNER TO webrsa;

--
-- TOC entry 2179 (class 1259 OID 2023334)
-- Dependencies: 8
-- Name: modescontacts; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE modescontacts (
    infodemandersa integer NOT NULL,
    clemodescontacts integer NOT NULL,
    numtel character varying(11),
    numposte character varying(4),
    nattel character varying(1),
    matetel character varying(3),
    autorutitel character varying(1),
    adrelec character varying(78),
    autorutiadrelec character varying(1)
);


ALTER TABLE staging.modescontacts OWNER TO webrsa;

--
-- TOC entry 2180 (class 1259 OID 2023337)
-- Dependencies: 8
-- Name: nationalite; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE nationalite (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    nati character varying(1),
    dtnati character varying(10),
    pieecpres character varying(1)
);


ALTER TABLE staging.nationalite OWNER TO webrsa;

--
-- TOC entry 2181 (class 1259 OID 2023340)
-- Dependencies: 8
-- Name: niveauetude; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE niveauetude (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    nivetu character varying(4),
    nivdipmaxobt character varying(4),
    annobtnivdipmax character varying(4),
    topqualipro character varying(1),
    libautrqualipro character varying(100),
    topcompeextrapro character varying(1),
    libcompeextrapro character varying(100)
);


ALTER TABLE staging.niveauetude OWNER TO webrsa;

--
-- TOC entry 2182 (class 1259 OID 2023343)
-- Dependencies: 8
-- Name: organisme; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE organisme (
    infodemandersa integer NOT NULL,
    fonorg character varying(3),
    numorg character varying(3),
    matricule character varying(15),
    statudemrsa character varying(1)
);


ALTER TABLE staging.organisme OWNER TO webrsa;

--
-- TOC entry 2183 (class 1259 OID 2023346)
-- Dependencies: 8
-- Name: organismecedant; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE organismecedant (
    infodemandersa integer NOT NULL,
    fonorgcedmut character varying(3),
    numorgcedmut character varying(3),
    matriculeorgcedmut character varying(15),
    codeposanchab character varying(15)
);


ALTER TABLE staging.organismecedant OWNER TO webrsa;

--
-- TOC entry 2184 (class 1259 OID 2023349)
-- Dependencies: 8
-- Name: organismedecisionorientation; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE organismedecisionorientation (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    raisocorgdecior character varying(60),
    numvoie character varying(6),
    typevoie character varying(4),
    nomvoie character varying(25),
    complideadr character varying(38),
    compladr character varying(26),
    lieudist character varying(32),
    codepos character varying(5),
    locaadr character varying(26),
    numtelorgdeciorie character varying(10),
    dtrvorgdeciorie character varying(10),
    hrrvorgdeciorie character varying(13),
    libadrrvorgdeciorie character varying(160),
    numtelrvorgdeciorie character varying(10)
);


ALTER TABLE staging.organismedecisionorientation OWNER TO webrsa;

--
-- TOC entry 2185 (class 1259 OID 2023352)
-- Dependencies: 8
-- Name: organismereferentorientation; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE organismereferentorientation (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    raisocorgdecior character varying(60),
    numvoie character varying(6),
    typevoie character varying(4),
    nomvoie character varying(25),
    complideadr character varying(38),
    compladr character varying(26),
    lieudist character varying(32),
    codepos character varying(5),
    locaadr character varying(26),
    numtelorgorie character varying(10),
    dtrvorgorie character varying(10),
    hrrvorgorie character varying(13),
    libadrrvorgorie character varying(160),
    numtelrvorgorie character varying(10)
);


ALTER TABLE staging.organismereferentorientation OWNER TO webrsa;

--
-- TOC entry 2186 (class 1259 OID 2023355)
-- Dependencies: 8
-- Name: partenaire; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE partenaire (
    infodemandersa integer NOT NULL,
    typeparte character varying(4),
    ideparte character varying(3)
);


ALTER TABLE staging.partenaire OWNER TO webrsa;

--
-- TOC entry 2187 (class 1259 OID 2023358)
-- Dependencies: 8
-- Name: prestationrsa; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE prestationrsa (
    infodemandersa integer NOT NULL,
    mtestrsa character varying(9)
);


ALTER TABLE staging.prestationrsa OWNER TO webrsa;

--
-- TOC entry 2188 (class 1259 OID 2023361)
-- Dependencies: 8
-- Name: prestations; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE prestations (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    natprest character varying(3),
    rolepers character varying(3)
);


ALTER TABLE staging.prestations OWNER TO webrsa;

--
-- TOC entry 2189 (class 1259 OID 2023364)
-- Dependencies: 8
-- Name: rattachement; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE rattachement (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    cle integer NOT NULL,
    nomnai character varying(28),
    prenom character varying(32),
    dtnai character varying(10),
    nir character varying(15),
    typepar character varying(3)
);


ALTER TABLE staging.rattachement OWNER TO webrsa;

--
-- TOC entry 2190 (class 1259 OID 2023367)
-- Dependencies: 8
-- Name: rib; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE rib (
    infodemandersa integer NOT NULL,
    titurib character varying(3),
    nomprenomtiturib character varying(24),
    etaban character varying(5),
    guiban character varying(5),
    numcomptban character varying(11),
    clerib character varying(3),
    comban character varying(24),
    numdebiban character(4),
    numfiniban character(7),
    bic character(11)
);


ALTER TABLE staging.rib OWNER TO webrsa;

--
-- TOC entry 2191 (class 1259 OID 2023370)
-- Dependencies: 8
-- Name: situationfamille; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE situationfamille (
    infodemandersa integer NOT NULL,
    sitfam character varying(3),
    ddsitfam character varying(10)
);


ALTER TABLE staging.situationfamille OWNER TO webrsa;

--
-- TOC entry 2192 (class 1259 OID 2023373)
-- Dependencies: 8
-- Name: situationprofessionnelle; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE situationprofessionnelle (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    hispro character varying(4),
    libderact character varying(100),
    libsecactderact character varying(100),
    cessderact character varying(4),
    topdomideract character varying(1),
    libactdomi character varying(100),
    libsecactdomi character varying(100),
    duractdomi character varying(4),
    inscdememploi character varying(4),
    topisogrorechemploi character varying(1),
    accoemploi character varying(4),
    libcooraccoemploi character varying(100),
    topprojpro character varying(1),
    libemploirech character varying(250),
    libsecactrech character varying(250),
    topcreareprientre character varying(1),
    concoformqualiemploi character varying(1)
);


ALTER TABLE staging.situationprofessionnelle OWNER TO webrsa;

--
-- TOC entry 2193 (class 1259 OID 2023379)
-- Dependencies: 8
-- Name: suivi_instruction; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE suivi_instruction (
    infodemandersa integer NOT NULL,
    cleidentificationrsa integer NOT NULL,
    suiirsa character varying(10),
    nomins character varying(28),
    prenomins character varying(32),
    numdepins character varying(3),
    typeserins character varying(1),
    numcomins character varying(3),
    numagrins character varying(4)
);


ALTER TABLE staging.suivi_instruction OWNER TO webrsa;

--
-- TOC entry 2194 (class 1259 OID 2023382)
-- Dependencies: 8
-- Name: suiviappuiorientation; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE suiviappuiorientation (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    topoblsocpro character varying(10),
    topsouhsocpro character varying(10),
    sitperssocpro character varying(10),
    dtenrsocpro character varying(10),
    dtenrparco character varying(10),
    dtenrorie character varying(10)
);


ALTER TABLE staging.suiviappuiorientation OWNER TO webrsa;

--
-- TOC entry 2195 (class 1259 OID 2023385)
-- Dependencies: 8
-- Name: titresejour; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE titresejour (
    infodemandersa integer NOT NULL,
    clepersonne integer NOT NULL,
    dtentfra character varying(10),
    nattitsej character varying(3),
    menttitsej character varying(2),
    ddtitsej character varying(10),
    dftitsej character varying(10),
    numtitsej character varying(10),
    numduptitsej character varying(3)
);


ALTER TABLE staging.titresejour OWNER TO webrsa;

--
-- TOC entry 2196 (class 1259 OID 2023388)
-- Dependencies: 8
-- Name: transmissionflux_instruction; Type: TABLE; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE TABLE transmissionflux_instruction (
    nbtotdemrsatransm character varying(8)
);


ALTER TABLE staging.transmissionflux_instruction OWNER TO webrsa;

SET search_path = administration, pg_catalog;

--
-- TOC entry 2472 (class 2604 OID 2023391)
-- Dependencies: 2015 2014
-- Name: id; Type: DEFAULT; Schema: administration; Owner: postgres
--

ALTER TABLE visionneuses ALTER COLUMN id SET DEFAULT nextval('visionneuses_id_seq'::regclass);


--
-- TOC entry 2519 (class 2606 OID 2023393)
-- Dependencies: 1996 1996
-- Name: donneeentete_pkey; Type: CONSTRAINT; Schema: administration; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY donneeentete
    ADD CONSTRAINT donneeentete_pkey PRIMARY KEY (flux);


--
-- TOC entry 2521 (class 2606 OID 2023395)
-- Dependencies: 1997 1997
-- Name: donneepied_pkey; Type: CONSTRAINT; Schema: administration; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY donneepied
    ADD CONSTRAINT donneepied_pkey PRIMARY KEY (flux);


--
-- TOC entry 2523 (class 2606 OID 2023397)
-- Dependencies: 1998 1998 1998
-- Name: donneereference_pkey; Type: CONSTRAINT; Schema: administration; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY donneereference
    ADD CONSTRAINT donneereference_pkey PRIMARY KEY (cledemandersa, flux);


--
-- TOC entry 2525 (class 2606 OID 2023399)
-- Dependencies: 1999 1999 1999
-- Name: donneetampon_pkey; Type: CONSTRAINT; Schema: administration; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY donneetampon
    ADD CONSTRAINT donneetampon_pkey PRIMARY KEY (cledemandersa, flux);


--
-- TOC entry 2527 (class 2606 OID 2023401)
-- Dependencies: 2001 2001
-- Name: nomfichier_pkey; Type: CONSTRAINT; Schema: administration; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY nomfichier
    ADD CONSTRAINT nomfichier_pkey PRIMARY KEY (flux);


--
-- TOC entry 2529 (class 2606 OID 2023403)
-- Dependencies: 2011 2011 2011 2011
-- Name: rejet_historique_pkey; Type: CONSTRAINT; Schema: administration; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY rejet_historique
    ADD CONSTRAINT rejet_historique_pkey PRIMARY KEY (cleinfodemandersa, flux, "DT_INSERT");


SET search_path = elementaire, pg_catalog;

--
-- TOC entry 2531 (class 2606 OID 2023405)
-- Dependencies: 2025 2025 2025
-- Name: b_anomalies_pkey; Type: CONSTRAINT; Schema: elementaire; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_anomalies
    ADD CONSTRAINT b_anomalies_pkey PRIMARY KEY (infosfoyerrsa, cle);


--
-- TOC entry 2533 (class 2606 OID 2023407)
-- Dependencies: 2030 2030 2030
-- Name: b_controlesadministratifs_pkey; Type: CONSTRAINT; Schema: elementaire; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_controlesadministratifs
    ADD CONSTRAINT b_controlesadministratifs_pkey PRIMARY KEY (infosfoyerrsa, cle);


SET search_path = staging, pg_catalog;

--
-- TOC entry 2541 (class 2606 OID 2023409)
-- Dependencies: 2094 2094 2094
-- Name: activite_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY activite
    ADD CONSTRAINT activite_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2543 (class 2606 OID 2023411)
-- Dependencies: 2095 2095 2095
-- Name: activiteeti_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY activiteeti
    ADD CONSTRAINT activiteeti_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2545 (class 2606 OID 2023413)
-- Dependencies: 2096 2096
-- Name: adresse_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY adresse
    ADD CONSTRAINT adresse_pkey PRIMARY KEY (infodemandersa);


--
-- TOC entry 2547 (class 2606 OID 2023415)
-- Dependencies: 2097 2097 2097 2097
-- Name: aidesagricoles_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY aidesagricoles
    ADD CONSTRAINT aidesagricoles_pkey PRIMARY KEY (infodemandersa, clepersonne, cle);


--
-- TOC entry 2549 (class 2606 OID 2023417)
-- Dependencies: 2098 2098 2098
-- Name: asf_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY asf
    ADD CONSTRAINT asf_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2551 (class 2606 OID 2023419)
-- Dependencies: 2099 2099 2099 2099
-- Name: b_activite_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_activite
    ADD CONSTRAINT b_activite_pkey PRIMARY KEY (infosfoyerrsa, clepersonne, cle);


--
-- TOC entry 2553 (class 2606 OID 2023421)
-- Dependencies: 2100 2100 2100
-- Name: b_adresse_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_adresse
    ADD CONSTRAINT b_adresse_pkey PRIMARY KEY (infosfoyerrsa, cle);


--
-- TOC entry 2555 (class 2606 OID 2023423)
-- Dependencies: 2101 2101 2101
-- Name: b_anomalies_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_anomalies
    ADD CONSTRAINT b_anomalies_pkey PRIMARY KEY (infosfoyerrsa, cle);


--
-- TOC entry 2557 (class 2606 OID 2023425)
-- Dependencies: 2102 2102 2102 2102
-- Name: b_asf_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_asf
    ADD CONSTRAINT b_asf_pkey PRIMARY KEY (infosfoyerrsa, clepersonne, cle);


--
-- TOC entry 2559 (class 2606 OID 2023427)
-- Dependencies: 2103 2103 2103
-- Name: b_avispcgpersonnes_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_avispcgpersonnes
    ADD CONSTRAINT b_avispcgpersonnes_pkey PRIMARY KEY (infosfoyerrsa, clepersonne);


--
-- TOC entry 2561 (class 2606 OID 2023429)
-- Dependencies: 2104 2104 2104
-- Name: b_benefices_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_benefices
    ADD CONSTRAINT b_benefices_pkey PRIMARY KEY (infosfoyerrsa, clepersonne);


--
-- TOC entry 2563 (class 2606 OID 2023431)
-- Dependencies: 2105 2105 2105
-- Name: b_calculdroitrsa_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_calculdroitrsa
    ADD CONSTRAINT b_calculdroitrsa_pkey PRIMARY KEY (infosfoyerrsa, clepersonne);


--
-- TOC entry 2565 (class 2606 OID 2023433)
-- Dependencies: 2106 2106 2106
-- Name: b_condsadmins_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_condsadmins
    ADD CONSTRAINT b_condsadmins_pkey PRIMARY KEY (infosfoyerrsa, cle);


--
-- TOC entry 2567 (class 2606 OID 2023435)
-- Dependencies: 2107 2107 2107
-- Name: b_controlesadministratifs_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_controlesadministratifs
    ADD CONSTRAINT b_controlesadministratifs_pkey PRIMARY KEY (infosfoyerrsa, cle);


--
-- TOC entry 2569 (class 2606 OID 2023437)
-- Dependencies: 2108 2108 2108
-- Name: b_creance_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_creance
    ADD CONSTRAINT b_creance_pkey PRIMARY KEY (infosfoyerrsa, cle);


--
-- TOC entry 2571 (class 2606 OID 2023439)
-- Dependencies: 2109 2109 2109 2109
-- Name: b_creancealimentaire_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_creancealimentaire
    ADD CONSTRAINT b_creancealimentaire_pkey PRIMARY KEY (infosfoyerrsa, clepersonne, cle);


--
-- TOC entry 2573 (class 2606 OID 2023441)
-- Dependencies: 2110 2110
-- Name: b_demandermi_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_demandermi
    ADD CONSTRAINT b_demandermi_pkey PRIMARY KEY (infosfoyerrsa);


--
-- TOC entry 2576 (class 2606 OID 2023443)
-- Dependencies: 2111 2111
-- Name: b_demandersa_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_demandersa
    ADD CONSTRAINT b_demandersa_pkey PRIMARY KEY (infosfoyerrsa);


--
-- TOC entry 2578 (class 2606 OID 2023445)
-- Dependencies: 2112 2112 2112 2112
-- Name: b_derogations_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_derogations
    ADD CONSTRAINT b_derogations_pkey PRIMARY KEY (infosfoyerrsa, clepersonne, cle);


--
-- TOC entry 2580 (class 2606 OID 2023447)
-- Dependencies: 2113 2113 2113 2113 2113
-- Name: b_detailressourcesmensuelles_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_detailressourcesmensuelles
    ADD CONSTRAINT b_detailressourcesmensuelles_pkey PRIMARY KEY (infosfoyerrsa, clepersonne, cleressourcesmensuelles, cle);


--
-- TOC entry 2582 (class 2606 OID 2023449)
-- Dependencies: 2114 2114 2114
-- Name: b_detailscalculsdroitrsa_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_detailscalculsdroitrsa
    ADD CONSTRAINT b_detailscalculsdroitrsa_pkey PRIMARY KEY (infosfoyerrsa, cle);


--
-- TOC entry 2584 (class 2606 OID 2023451)
-- Dependencies: 2115 2115
-- Name: b_detailsdroitrsa_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_detailsdroitrsa
    ADD CONSTRAINT b_detailsdroitrsa_pkey PRIMARY KEY (infosfoyerrsa);


--
-- TOC entry 2586 (class 2606 OID 2023453)
-- Dependencies: 2116 2116 2116
-- Name: b_dossiercaf_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_dossiercaf
    ADD CONSTRAINT b_dossiercaf_pkey PRIMARY KEY (infosfoyerrsa, clepersonne);


--
-- TOC entry 2588 (class 2606 OID 2023455)
-- Dependencies: 2117 2117 2117
-- Name: b_dossierpoleemploi_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_dossierpoleemploi
    ADD CONSTRAINT b_dossierpoleemploi_pkey PRIMARY KEY (infosfoyerrsa, clepersonne);


--
-- TOC entry 2590 (class 2606 OID 2023457)
-- Dependencies: 2118 2118 2118
-- Name: b_evenement_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_evenement
    ADD CONSTRAINT b_evenement_pkey PRIMARY KEY (infosfoyerrsa, cle);


--
-- TOC entry 2592 (class 2606 OID 2023459)
-- Dependencies: 2119 2119 2119 2119
-- Name: b_generaliteressourcesmensuelles_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_generaliteressourcesmensuelles
    ADD CONSTRAINT b_generaliteressourcesmensuelles_pkey PRIMARY KEY (infosfoyerrsa, clepersonne, cle);


--
-- TOC entry 2594 (class 2606 OID 2023461)
-- Dependencies: 2120 2120 2120
-- Name: b_generaliteressourcestrimestre_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_generaliteressourcestrimestre
    ADD CONSTRAINT b_generaliteressourcestrimestre_pkey PRIMARY KEY (infosfoyerrsa, clepersonne);


--
-- TOC entry 2596 (class 2606 OID 2023463)
-- Dependencies: 2121 2121 2121
-- Name: b_grossesse_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_grossesse
    ADD CONSTRAINT b_grossesse_pkey PRIMARY KEY (infosfoyerrsa, clepersonne);


--
-- TOC entry 2598 (class 2606 OID 2023465)
-- Dependencies: 2122 2122 2122
-- Name: b_identification_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_identification
    ADD CONSTRAINT b_identification_pkey PRIMARY KEY (infosfoyerrsa, clepersonne);


--
-- TOC entry 2600 (class 2606 OID 2023467)
-- Dependencies: 2124 2124 2124 2124
-- Name: b_liberalite_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_liberalite
    ADD CONSTRAINT b_liberalite_pkey PRIMARY KEY (infosfoyerrsa, clepersonne, cle);


--
-- TOC entry 2602 (class 2606 OID 2023469)
-- Dependencies: 2125 2125
-- Name: b_organisme_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_organisme
    ADD CONSTRAINT b_organisme_pkey PRIMARY KEY (infosfoyerrsa);


--
-- TOC entry 2604 (class 2606 OID 2023471)
-- Dependencies: 2126 2126
-- Name: b_organismecedant_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_organismecedant
    ADD CONSTRAINT b_organismecedant_pkey PRIMARY KEY (infosfoyerrsa);


--
-- TOC entry 2606 (class 2606 OID 2023473)
-- Dependencies: 2127 2127
-- Name: b_organismeprenant_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_organismeprenant
    ADD CONSTRAINT b_organismeprenant_pkey PRIMARY KEY (infosfoyerrsa);


--
-- TOC entry 2608 (class 2606 OID 2023475)
-- Dependencies: 2128 2128
-- Name: b_paiementtiers_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_paiementtiers
    ADD CONSTRAINT b_paiementtiers_pkey PRIMARY KEY (infosfoyerrsa);


--
-- TOC entry 2610 (class 2606 OID 2023477)
-- Dependencies: 2129 2129
-- Name: b_partenaire_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_partenaire
    ADD CONSTRAINT b_partenaire_pkey PRIMARY KEY (infosfoyerrsa);


--
-- TOC entry 2612 (class 2606 OID 2023479)
-- Dependencies: 2130 2130 2130 2130
-- Name: b_prestations_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_prestations
    ADD CONSTRAINT b_prestations_pkey PRIMARY KEY (infosfoyerrsa, clepersonne, cle);


--
-- TOC entry 2614 (class 2606 OID 2023481)
-- Dependencies: 2131 2131 2131 2131
-- Name: b_rattachement_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_rattachement
    ADD CONSTRAINT b_rattachement_pkey PRIMARY KEY (infosfoyerrsa, clepersonne, cle);


--
-- TOC entry 2616 (class 2606 OID 2023483)
-- Dependencies: 2132 2132 2132
-- Name: b_reducsrsa_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_reducsrsa
    ADD CONSTRAINT b_reducsrsa_pkey PRIMARY KEY (infosfoyerrsa, cle);


--
-- TOC entry 2618 (class 2606 OID 2023485)
-- Dependencies: 2133 2133
-- Name: b_sitdossiersrsa_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_sitdossiersrsa
    ADD CONSTRAINT b_sitdossiersrsa_pkey PRIMARY KEY (infosfoyerrsa);


--
-- TOC entry 2620 (class 2606 OID 2023487)
-- Dependencies: 2134 2134
-- Name: b_situationfamille_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_situationfamille
    ADD CONSTRAINT b_situationfamille_pkey PRIMARY KEY (infosfoyerrsa);


--
-- TOC entry 2622 (class 2606 OID 2023489)
-- Dependencies: 2135 2135 2135
-- Name: b_suspensiondroits_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_suspensiondroits
    ADD CONSTRAINT b_suspensiondroits_pkey PRIMARY KEY (infosfoyerrsa, cle);


--
-- TOC entry 2624 (class 2606 OID 2023491)
-- Dependencies: 2136 2136 2136
-- Name: b_suspensionversements_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY b_suspensionversements
    ADD CONSTRAINT b_suspensionversements_pkey PRIMARY KEY (infosfoyerrsa, cle);


--
-- TOC entry 2626 (class 2606 OID 2023493)
-- Dependencies: 2138 2138 2138
-- Name: benefices_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY benefices
    ADD CONSTRAINT benefices_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2628 (class 2606 OID 2023495)
-- Dependencies: 2139 2139 2139
-- Name: chiffreaffaire_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY chiffreaffaire
    ADD CONSTRAINT chiffreaffaire_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2630 (class 2606 OID 2023497)
-- Dependencies: 2140 2140 2140
-- Name: commundifficultelogement_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY commundifficultelogement
    ADD CONSTRAINT commundifficultelogement_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2632 (class 2606 OID 2023499)
-- Dependencies: 2141 2141 2141
-- Name: communmobilite_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY communmobilite
    ADD CONSTRAINT communmobilite_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2634 (class 2606 OID 2023501)
-- Dependencies: 2142 2142 2142
-- Name: communsituationsociale_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY communsituationsociale
    ADD CONSTRAINT communsituationsociale_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2636 (class 2606 OID 2023503)
-- Dependencies: 2143 2143 2143
-- Name: creancealimentaire_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY creancealimentaire
    ADD CONSTRAINT creancealimentaire_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2639 (class 2606 OID 2023505)
-- Dependencies: 2144 2144
-- Name: demandersa_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY demandersa
    ADD CONSTRAINT demandersa_pkey PRIMARY KEY (infodemandersa);


--
-- TOC entry 2641 (class 2606 OID 2023507)
-- Dependencies: 2145 2145
-- Name: destinataire_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY destinataire
    ADD CONSTRAINT destinataire_pkey PRIMARY KEY (infodemandersa);


--
-- TOC entry 2643 (class 2606 OID 2023509)
-- Dependencies: 2146 2146 2146 2146
-- Name: detailaccompagnementsocialfamilial_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY detailaccompagnementsocialfamilial
    ADD CONSTRAINT detailaccompagnementsocialfamilial_pkey PRIMARY KEY (infodemandersa, clepersonne, cle);


--
-- TOC entry 2645 (class 2606 OID 2023511)
-- Dependencies: 2147 2147 2147 2147
-- Name: detailaccosocindividuel_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY detailaccosocindividuel
    ADD CONSTRAINT detailaccosocindividuel_pkey PRIMARY KEY (infodemandersa, clepersonne, cle);


--
-- TOC entry 2647 (class 2606 OID 2023513)
-- Dependencies: 2148 2148 2148 2148
-- Name: detaildifficultedisponibilite_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY detaildifficultedisponibilite
    ADD CONSTRAINT detaildifficultedisponibilite_pkey PRIMARY KEY (infodemandersa, clepersonne, cle);


--
-- TOC entry 2649 (class 2606 OID 2023515)
-- Dependencies: 2149 2149 2149 2149
-- Name: detaildifficultelogement_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY detaildifficultelogement
    ADD CONSTRAINT detaildifficultelogement_pkey PRIMARY KEY (infodemandersa, clepersonne, cle);


--
-- TOC entry 2651 (class 2606 OID 2023517)
-- Dependencies: 2150 2150 2150 2150
-- Name: detaildifficultesituationsociale_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY detaildifficultesituationsociale
    ADD CONSTRAINT detaildifficultesituationsociale_pkey PRIMARY KEY (infodemandersa, clepersonne, cle);


--
-- TOC entry 2653 (class 2606 OID 2023519)
-- Dependencies: 2151 2151 2151 2151
-- Name: detailmobilite_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY detailmobilite
    ADD CONSTRAINT detailmobilite_pkey PRIMARY KEY (infodemandersa, clepersonne, cle);


--
-- TOC entry 2655 (class 2606 OID 2023521)
-- Dependencies: 2152 2152 2152 2152 2152
-- Name: detailressourcesmensuelles_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY detailressourcesmensuelles
    ADD CONSTRAINT detailressourcesmensuelles_pkey PRIMARY KEY (infodemandersa, clepersonne, cleressourcesmensuelles, cle);


--
-- TOC entry 2657 (class 2606 OID 2023523)
-- Dependencies: 2153 2153 2153
-- Name: determinationparcours_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY determinationparcours
    ADD CONSTRAINT determinationparcours_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2659 (class 2606 OID 2023525)
-- Dependencies: 2154 2154 2154
-- Name: disponibilteemploi_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY disponibilteemploi
    ADD CONSTRAINT disponibilteemploi_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2661 (class 2606 OID 2023527)
-- Dependencies: 2155 2155 2155
-- Name: dossiercaf_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY dossiercaf
    ADD CONSTRAINT dossiercaf_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2663 (class 2606 OID 2023529)
-- Dependencies: 2156 2156 2156
-- Name: dossierpoleemploi_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY dossierpoleemploi
    ADD CONSTRAINT dossierpoleemploi_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2665 (class 2606 OID 2023531)
-- Dependencies: 2157 2157
-- Name: electiondomicile_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY electiondomicile
    ADD CONSTRAINT electiondomicile_pkey PRIMARY KEY (infodemandersa);


--
-- TOC entry 2667 (class 2606 OID 2023533)
-- Dependencies: 2158 2158 2158
-- Name: elementsfiscaux_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY elementsfiscaux
    ADD CONSTRAINT elementsfiscaux_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2669 (class 2606 OID 2023535)
-- Dependencies: 2159 2159 2159
-- Name: employes_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY employes
    ADD CONSTRAINT employes_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2671 (class 2606 OID 2023537)
-- Dependencies: 2160 2160
-- Name: f_adresse_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY f_adresse
    ADD CONSTRAINT f_adresse_pkey PRIMARY KEY (infosfinancieresfoyerrsa);


--
-- TOC entry 2673 (class 2606 OID 2023539)
-- Dependencies: 2162 2162
-- Name: f_demandersa_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY f_demandersa
    ADD CONSTRAINT f_demandersa_pkey PRIMARY KEY (infosfinancieresfoyerrsa);


--
-- TOC entry 2675 (class 2606 OID 2023541)
-- Dependencies: 2163 2163 2163 2163
-- Name: f_detailaccomptersa_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY f_detailaccomptersa
    ADD CONSTRAINT f_detailaccomptersa_pkey PRIMARY KEY (infosfinancieresfoyerrsa, type_allocation, cle);


--
-- TOC entry 2677 (class 2606 OID 2023543)
-- Dependencies: 2164 2164
-- Name: f_dossiercaf_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY f_dossiercaf
    ADD CONSTRAINT f_dossiercaf_pkey PRIMARY KEY (infosfinancieresfoyerrsa);


--
-- TOC entry 2679 (class 2606 OID 2023545)
-- Dependencies: 2165 2165
-- Name: f_identification_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY f_identification
    ADD CONSTRAINT f_identification_pkey PRIMARY KEY (infosfinancieresfoyerrsa);


--
-- TOC entry 2681 (class 2606 OID 2023547)
-- Dependencies: 2167 2167
-- Name: f_organisme_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY f_organisme
    ADD CONSTRAINT f_organisme_pkey PRIMARY KEY (infosfinancieresfoyerrsa);


--
-- TOC entry 2683 (class 2606 OID 2023549)
-- Dependencies: 2168 2168
-- Name: f_partenaire_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY f_partenaire
    ADD CONSTRAINT f_partenaire_pkey PRIMARY KEY (infosfinancieresfoyerrsa);


--
-- TOC entry 2685 (class 2606 OID 2023551)
-- Dependencies: 2169 2169
-- Name: f_totalisationacompte_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY f_totalisationacompte
    ADD CONSTRAINT f_totalisationacompte_pkey PRIMARY KEY (type_allocation);


--
-- TOC entry 2687 (class 2606 OID 2023553)
-- Dependencies: 2171 2171
-- Name: ficheliaison_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY ficheliaison
    ADD CONSTRAINT ficheliaison_pkey PRIMARY KEY (infodemandersa);


--
-- TOC entry 2689 (class 2606 OID 2023555)
-- Dependencies: 2172 2172 2172
-- Name: generalitedspp_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY generalitedspp
    ADD CONSTRAINT generalitedspp_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2691 (class 2606 OID 2023557)
-- Dependencies: 2173 2173 2173 2173
-- Name: generaliteressourcesmensuelles_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY generaliteressourcesmensuelles
    ADD CONSTRAINT generaliteressourcesmensuelles_pkey PRIMARY KEY (infodemandersa, clepersonne, cle);


--
-- TOC entry 2693 (class 2606 OID 2023559)
-- Dependencies: 2174 2174 2174
-- Name: generaliteressourcestrimestre_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY generaliteressourcestrimestre
    ADD CONSTRAINT generaliteressourcestrimestre_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2695 (class 2606 OID 2023561)
-- Dependencies: 2175 2175 2175
-- Name: grossesse_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY grossesse
    ADD CONSTRAINT grossesse_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2698 (class 2606 OID 2023563)
-- Dependencies: 2176 2176 2176
-- Name: identification_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY identification
    ADD CONSTRAINT identification_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2700 (class 2606 OID 2023565)
-- Dependencies: 2178 2178
-- Name: logement_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY logement
    ADD CONSTRAINT logement_pkey PRIMARY KEY (infodemandersa);


--
-- TOC entry 2702 (class 2606 OID 2023567)
-- Dependencies: 2179 2179 2179
-- Name: modescontacts_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY modescontacts
    ADD CONSTRAINT modescontacts_pkey PRIMARY KEY (infodemandersa, clemodescontacts);


--
-- TOC entry 2704 (class 2606 OID 2023569)
-- Dependencies: 2180 2180 2180
-- Name: nationalite_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY nationalite
    ADD CONSTRAINT nationalite_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2706 (class 2606 OID 2023571)
-- Dependencies: 2181 2181 2181
-- Name: niveauetude_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY niveauetude
    ADD CONSTRAINT niveauetude_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2708 (class 2606 OID 2023573)
-- Dependencies: 2182 2182
-- Name: organisme_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY organisme
    ADD CONSTRAINT organisme_pkey PRIMARY KEY (infodemandersa);


--
-- TOC entry 2710 (class 2606 OID 2023575)
-- Dependencies: 2183 2183
-- Name: organismecedant_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY organismecedant
    ADD CONSTRAINT organismecedant_pkey PRIMARY KEY (infodemandersa);


--
-- TOC entry 2712 (class 2606 OID 2023577)
-- Dependencies: 2184 2184 2184
-- Name: organismedecisionorientation_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY organismedecisionorientation
    ADD CONSTRAINT organismedecisionorientation_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2714 (class 2606 OID 2023579)
-- Dependencies: 2185 2185 2185
-- Name: organismereferentorientation_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY organismereferentorientation
    ADD CONSTRAINT organismereferentorientation_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2716 (class 2606 OID 2023581)
-- Dependencies: 2186 2186
-- Name: partenaire_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY partenaire
    ADD CONSTRAINT partenaire_pkey PRIMARY KEY (infodemandersa);


--
-- TOC entry 2718 (class 2606 OID 2023583)
-- Dependencies: 2187 2187
-- Name: prestationrsa_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY prestationrsa
    ADD CONSTRAINT prestationrsa_pkey PRIMARY KEY (infodemandersa);


--
-- TOC entry 2720 (class 2606 OID 2023585)
-- Dependencies: 2188 2188 2188
-- Name: prestations_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY prestations
    ADD CONSTRAINT prestations_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2722 (class 2606 OID 2023587)
-- Dependencies: 2189 2189 2189 2189
-- Name: rattachement_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY rattachement
    ADD CONSTRAINT rattachement_pkey PRIMARY KEY (infodemandersa, clepersonne, cle);


--
-- TOC entry 2724 (class 2606 OID 2023589)
-- Dependencies: 2190 2190
-- Name: rib_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY rib
    ADD CONSTRAINT rib_pkey PRIMARY KEY (infodemandersa);


--
-- TOC entry 2726 (class 2606 OID 2023591)
-- Dependencies: 2191 2191
-- Name: situationfamille_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY situationfamille
    ADD CONSTRAINT situationfamille_pkey PRIMARY KEY (infodemandersa);


--
-- TOC entry 2728 (class 2606 OID 2023593)
-- Dependencies: 2192 2192 2192
-- Name: situationprofessionnelle_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY situationprofessionnelle
    ADD CONSTRAINT situationprofessionnelle_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2730 (class 2606 OID 2023595)
-- Dependencies: 2193 2193 2193
-- Name: suivi_instruction_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY suivi_instruction
    ADD CONSTRAINT suivi_instruction_pkey PRIMARY KEY (infodemandersa, cleidentificationrsa);


--
-- TOC entry 2732 (class 2606 OID 2023597)
-- Dependencies: 2194 2194 2194
-- Name: suiviappuiorientation_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY suiviappuiorientation
    ADD CONSTRAINT suiviappuiorientation_pkey PRIMARY KEY (infodemandersa, clepersonne);


--
-- TOC entry 2734 (class 2606 OID 2023599)
-- Dependencies: 2195 2195 2195
-- Name: titresejour_pkey; Type: CONSTRAINT; Schema: staging; Owner: webrsa; Tablespace: 
--

ALTER TABLE ONLY titresejour
    ADD CONSTRAINT titresejour_pkey PRIMARY KEY (infodemandersa, clepersonne);


SET search_path = elementaire, pg_catalog;

--
-- TOC entry 2535 (class 1259 OID 2023600)
-- Dependencies: 2067
-- Name: f_adresses_foyers_idx_infosfinancieresfoyerrsa; Type: INDEX; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE INDEX f_adresses_foyers_idx_infosfinancieresfoyerrsa ON f_adresses_foyers USING hash (infosfinancieresfoyerrsa);


--
-- TOC entry 2534 (class 1259 OID 2023601)
-- Dependencies: 2066
-- Name: f_adresses_idx_infosfinancieresfoyerrsa; Type: INDEX; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE INDEX f_adresses_idx_infosfinancieresfoyerrsa ON f_adresses USING hash (infosfinancieresfoyerrsa);


--
-- TOC entry 2536 (class 1259 OID 2023602)
-- Dependencies: 2069
-- Name: f_dossiers_rsa_idx_infosfinancieresfoyerrsa; Type: INDEX; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE INDEX f_dossiers_rsa_idx_infosfinancieresfoyerrsa ON f_dossiers_rsa USING hash (infosfinancieresfoyerrsa);


--
-- TOC entry 2537 (class 1259 OID 2023603)
-- Dependencies: 2070
-- Name: f_dossierscaf_idx_infosfinancieresfoyerrsa; Type: INDEX; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE INDEX f_dossierscaf_idx_infosfinancieresfoyerrsa ON f_dossierscaf USING hash (infosfinancieresfoyerrsa);


--
-- TOC entry 2538 (class 1259 OID 2023604)
-- Dependencies: 2072
-- Name: f_infosfinancieres_idx_infosfinancieresfoyerrsa; Type: INDEX; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE INDEX f_infosfinancieres_idx_infosfinancieresfoyerrsa ON f_infosfinancieres USING hash (infosfinancieresfoyerrsa);


--
-- TOC entry 2539 (class 1259 OID 2023605)
-- Dependencies: 2073
-- Name: f_personnes_idx_infosfinancieresfoyerrsa; Type: INDEX; Schema: elementaire; Owner: webrsa; Tablespace: 
--

CREATE INDEX f_personnes_idx_infosfinancieresfoyerrsa ON f_personnes USING hash (infosfinancieresfoyerrsa);


SET search_path = staging, pg_catalog;

--
-- TOC entry 2574 (class 1259 OID 2023606)
-- Dependencies: 2111
-- Name: b_demandersa_numdemrsa_idx; Type: INDEX; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE INDEX b_demandersa_numdemrsa_idx ON b_demandersa USING btree (numdemrsa);


--
-- TOC entry 2637 (class 1259 OID 2169496)
-- Dependencies: 2144
-- Name: demandersa_numdemrsa_idx; Type: INDEX; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE INDEX demandersa_numdemrsa_idx ON demandersa USING btree (numdemrsa);


--
-- TOC entry 2696 (class 1259 OID 2169498)
-- Dependencies: 2176 2176 2176 2176 2176
-- Name: identification_idx; Type: INDEX; Schema: staging; Owner: webrsa; Tablespace: 
--

CREATE INDEX identification_idx ON identification USING btree (infodemandersa, nomnai, prenom, dtnai, rgnai);


--
-- TOC entry 2735 (class 2620 OID 2023608)
-- Dependencies: 2111 23
-- Name: b_demandersa_i_clean; Type: TRIGGER; Schema: staging; Owner: webrsa
--

CREATE TRIGGER b_demandersa_i_clean
    BEFORE INSERT ON b_demandersa
    FOR EACH ROW
    EXECUTE PROCEDURE clean_old_dossier_beneficiaire();


--
-- TOC entry 2736 (class 2620 OID 2023609)
-- Dependencies: 2122 24
-- Name: b_identification_i_clean; Type: TRIGGER; Schema: staging; Owner: webrsa
--

CREATE TRIGGER b_identification_i_clean
    BEFORE INSERT ON b_identification
    FOR EACH ROW
    EXECUTE PROCEDURE clean_old_personne_beneficiaire();


--
-- TOC entry 2737 (class 2620 OID 2169497)
-- Dependencies: 2144 26
-- Name: demandersa_i_clean; Type: TRIGGER; Schema: staging; Owner: webrsa
--

CREATE TRIGGER demandersa_i_clean
    BEFORE INSERT ON demandersa
    FOR EACH ROW
    EXECUTE PROCEDURE clean_old_dossier_instruction();


--
-- TOC entry 2738 (class 2620 OID 2169499)
-- Dependencies: 2176 25
-- Name: identification_i_clean; Type: TRIGGER; Schema: staging; Owner: webrsa
--

CREATE TRIGGER identification_i_clean
    BEFORE INSERT ON identification
    FOR EACH ROW
    EXECUTE PROCEDURE clean_old_personne_instruction();


--
-- TOC entry 2743 (class 0 OID 0)
-- Dependencies: 9
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2010-11-12 14:45:29

--
-- PostgreSQL database dump complete
--

