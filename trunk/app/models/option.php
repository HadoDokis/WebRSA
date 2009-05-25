<?php
    class Option extends AppModel
    {
        var $name = 'Option';
        var $useTable = false;

        function abaneu() {
            return array(
                'A' => 'Abattement',
                'N' => 'Neutralisation'
            );
        }

        function accoemploi() {
            return array(
                '1801' => 'Pas d\'accompagnement',
                '1802' => 'Pôle-emploi',
                '1803' => 'Autres'
            );
        }

        function acteti() {
            return array(
                'C' => 'Commerçant',
                'A' => 'Artisan',
                'L' => 'Profession libérale',
                'E' => 'Entrepreneur'
            );
        }

        function aviscondadmrsa() {
            return array(
                'D' => 'Avis demandé au CG',
                'A' => 'Accord du CG',
                'R' => 'Refus du CG'
            );
        }

        function avisdestpairsa() {
            return array(
                'D' => 'Avis demandé au CG',
                'A' => 'Accord du CG',
                'R' => 'refus du CG'
            );
        }

        function decision_ci() {
            return array(
                '' => 'En attente',
                'v' => 'Validation à compter du',
                'a' => 'Ajournement',
                'r' => 'Rejet'
            );
        }

        function demarlog() {
            return array(
                '1101' => 'Accès à un logement',
                '1102' => 'Maintien dans le logement',
                '1103' => 'Aucune'
            );
        }

        function duractdomi() {
            return array(
                '2104' => 'Moins d\'un an',
                '2105' => 'De 1 à 3 ans',
                '2106' => 'De 4 à 6 ans',
                '2107' => 'Plus de 6 ans'
            );
        }

        function etatdosrsa() {
            return array(
                '0' => 'Nouvelle demande en attente de décision CG pour ouverture du droit',
                '1' => 'Droit refusé',
                '2' => 'Droit ouvert et versable',
                '3' => 'Droit ouvert et suspendu (le montant du droit est calculable, mais l\'existence du droit est remis en cause)',
                '4' => 'Droit ouvert mais versement suspendu (le montant du droit n\'est pas calculable)',
                '5' => 'Droit clos',
                '6' => 'Droit clos sur mois antérieur ayant eu des créances transferées ou une régularisation dans le mois de référence pour une période antérieure.'

            );
        }

        function etatirsa() {
            return array(
                '03' => 'Instruction administrative confirmée',
                '05' => ' Recueil des données socio-profesionnelles validé partiellement',
                '06' => 'Recueil des données socio-profesionnelles validé en totalité'
            );
        }

        function hispro() {
            return array(
                '1901' => 'Vous avez toujours travaillé',
                '1902' => 'Vous travaillez par intermittence',
                '1903' => 'Vous avez déjà exercé une activité professionnelle',
                '1904' => 'Vous n\'avez jamais travaillé'
            );
        }

        function lib_action() {
            return array(
                'aide' => 'Aide',
                'prest' => 'Prestation'
            );
        }


        function lib_struc() {
            return array(
                '1' => 'Pole emploi',
                '2' => 'Assedic'
            );
        }

        function moticlorsa() {
            return array(
                'PCG' => 'Cloture suite décision du Président du Conseil général',
                'ECH' => 'Cloture suite à échéance (4 mois sans droits) ',
                'EFF' => 'Cloture suite à l\'annulation de la bascule RMI/API',
                'MUT' => 'Cloture suite à mutation du dossier dans un autre organisme',
                'RGD' => 'Cloture pour regroupement de dossier'
            );

        }

        function motidemrsa() {
            return array(
                '0101' => 'Fin de droits ASSEDIC',
                '0102' => 'Fin de droits AAH',
                '0103' => 'Fin d\'indemnités journalières (maternité)',
                '0104' => 'Fin d\'indemnités journalières (maladie et accidents du travail)',
                '0105' => 'Attente de pension vieillesse ou invalidité, ou d\'allocation handicap',
                '0106' => 'Personne isolée avec grossesse ou enfants à charge de moins de 6 ans',
                '0107' => 'Faibles ressources',
                '0108' => 'Cessation d\'activité',
                '0109' => 'Fin d\'études',
            );
        }

        function motisusdrorsa() {
            return array(
                'DA' => 'Suspension Dossier => Situation de famille',
                'DB' => 'Suspension Dossier => Ressources',
                'DC' => 'Suspension Dossier => Enquête administrative',
                'DD' => 'Suspension Dossier => Enquête sociale',
                'DE' => 'Suspension Dossier => Abs imprimé campagne contrôle',
                'DF' => 'Suspension Dossier => Absence avis changement CAF',
                'DG' => 'Suspension Dossier => Décès Madame',
                'DH' => 'Suspension Dossier => Décès Monsieur',
                'DI' => 'Suspension Dossier => Autre motif',
                'DJ' => 'Suspension Dossier => Présence paiemt réimp/arrêté',
                'DK' => 'Suspension Dossier => Abs réponse contrôle ASSEDIC',
                'DL' => 'Suspension Dossier => N\'habite plus adresse indiquée',
                'DM' => 'Suspension Dossier => Résidence inconnue',
                'DN' => 'Suspension Dossier => Diverg. droits SS susp anc.mod',
                'DO' => 'Suspension Dossier => Diverg. droits AV susp anc.mod',
                'DP' => 'Suspension Dossier => Contrôlee ASF hors d\'état',
                'GF' => 'Suspension Groupe Prestation => Situation de famille',
                'GR' => 'Suspension Groupe Prestation => Contrôle activité ressources',
                'GA' => 'Suspension Groupe Prestation => Enquête administrative',
                'GS' => 'Suspension Groupe Prestation => Enquête sociale',
                'GC' => 'Suspension Groupe Prestation => Abs. imprimé campagne contrôle',
                'GI' => 'Suspension Groupe Prestation => Imprimé chang. CAF non fourni',
                'GX' => 'Suspension Groupe Prestation => Autre motif',
                'GE' => 'Suspension Groupe Prestation => Forfait ETI non fourni',
                'GJ' => 'Suspension Groupe Prestation => RSA=> suspension PCG',
                'GK' => 'Suspension Groupe Prestation => RSA=> contrat insertion',
                'GL' => 'Suspension Groupe Prestation => RSA=> action non engagée'
            );
        }

        function motisusversrsa() {
            return array(
                '01' => 'Ressources trop élévées',
                '02' => 'MOINS DE 25 ANS ET PERSONNE A CHARGE',
                '03' => 'ACTIVITE NON CONFORME',
                '04' => 'TITRE DE SEJOUR NON VALIDE',
                '05' => 'RSA inférieur au seuil',
                '06' => 'Déclaration Trimestrielle Ressources non fournie',
                '09' => 'RESIDENCE NON CONFORME',
                '31' => 'Prestation exclue affil partielle',
                '34' => 'Régime non conforme',
                '35' => 'Demande avantage vielliesse absent ou tardif',
                '36' => 'Titre de séjour absent',
                '85' => 'Pas d\'allocataire (si allocataire décédé par exemple)',
                '97' => 'Bénéficiaires AAH réduite',
                'AB' => 'Allocataire absent du foyer',
                'CV' => 'Attente décision PCG (le droit reste théorique jusqu\'au retour)',
                'CG' => 'Application Sanction'
            );
        }

/*
        function motisusdrorsa*/
        function nationalite() {
            return array(
                'A' => 'Autre nationalité',
                'C' => 'Ressortissant CEE ou Suisse',
                'F' => 'Française'
            );
        }

        function natlog() {
            return array(
                '0901' => 'Logement autonome : habitat individuel',
                '0902' => 'Logement autonome : habitat collectif',
                '0903' => 'Logement d\'urgence : foyer d\'urgence',
                '0904' => 'Logement d\'urgence : CHRS (Centree d\'Hébergement Réinsertion Social)',
                '0905' => 'Logement d\'urgence : hôtel social',
                '0906' => 'Autre logement d\'urgence',
                '0907' => 'Logement temporaire : appartement relais',
                '0908' => 'Logement temporaire : bail glissant',
                '0909' => 'Logement temporaire : par parent ou tiers',
                '0910' => 'Logement temporaire : caravane, bateau,...',
                '0911' => 'Logement temporaire : résidence sociale',
                '0912' => 'Logement temporaire : sans hébergement',
                '0913' => 'Logement temporaire : autre situation'
            );
        }

        function natpfcre() {
            return array(
/*AllocCompta*/
                'RSD' => 'rsa socle',
                'RSI' => 'rsa socle majoration parent isolé',
                'RSB' => 'rsa socle local',
                'RCB' => 'rsa activité local',
                'ASD' => 'Acompte sur droit rsa. (le droit est constaté et ouvert)',
                'VSD' => 'Avance sur droit rsa (suite absence DTRSa ou dans l\'attente de l\'ouverture du droit)',
    /*Indusconstates*/ /*Remises indus*/    /* Autres annulations*/
            /*IndustransférésCG*/   /* Annulation faible montant*/
                'INK' => 'Indu sur rsa socle ',
                'INL' => 'Indu sur rsa socle majoré',
                'INM' => 'Indu sur rsa socle local ou rSa activite local',
                'ITK' => 'Indu sur rsa socle  transféré ou reçu d\'une autre Caf ou Msa ',
                'ITL' => 'Indu sur rsa socle majoré transféré ou reçu d\'une autre Caf ou Msa ',
                'ITM' => 'Indu sur rsa socle local ou rSa activite local transféré ou reçu d\'une autre Caf ou Msa ',
            /*IndustransférésCG*/
                'ISK' => 'Indu sur rSa socle subrogé',
    /*Indusconstates*/ /*Remises indus*/    /* Autres annulations*/
/*AllocCompta*/
                'INN' => 'Indu RCD RCI',
                'ITN' => 'Indu RCD RCI transféré',
                'INP' => 'Indu RSU RCU',
                'ITP' => 'Indu RSU RCU transféré'
                                    /* Annulation faible montant*/
            );
        }

        function natress() {
            return array(
                '000' => 'ressources nulles',
                '001' => 'salaires sans abattement supplementaire frais p',
                '002' => 'frais professionnels reels deductibles',
                '003' => 'salaires avec abattement supplementaire frais p',
                '004' => 'abattement supplementaire pour frais profession',
                '005' => 'salaires percus a l\'etranger',
                '006' => 'revenus exceptionnels d\'activite salarie',
                '007' => 'cav / cirma',
                '009' => 'chomage partiel (technique)',
                '010' => 'allocations de chomage',
                '011' => 'indem. maladie/maternite/pater.',
                '012' => 'accident travail/maladie prof.',
                '013' => 'indemnites maternite/partenite/adoption',
                '014' => 'autres ijss (maladie, at, mp)',
                '020' => 'pre-retraite',
                '021' => 'pension d\'invalidite',
                '022' => 'pension de vieillesse imposable',
                '023' => 'contrat d\'epargne - handicape',
                '024' => 'rente viagere a titre gratuit',
                '025' => 'allocation de veuvage',
                '026' => 'pensions alimentaires recues',
                '027' => 'rente viagere onereux - tiers',
                '028' => 'pension vieill. non imposable',
                '029' => 'majoration pension/retraite non imposable',
                '030' => 'revenu des professions non salariees',
                '031' => 'revenu profes non salar. non fixe ou inconnu',
                '032' => 'forfait agricole',
                '033' => 'forfait agricole non fixe',
                '034' => 'revenu eti non cga ni micro-bic',
                '040' => 'revenus fonciers et immobiliers',
                '041' => 'autres revenus imposables',
                '042' => 'ressources de l\'ex-conjoint (pinna)',
                '043' => 'revenus soumis a prelevement liberatoire',
                '050' => 'eval forf (salaires) ttes prest',
                '051' => 'eval forf (eti) ttes prest',
                '052' => 'evaluation forfaitaire (cat)',
                '053' => 'evaluat. forfait. (salaires) / apl',
                '054' => 'evaluation forfaitaire eti/ apl',
                '055' => 'evaluation forfaitaire (esat g.r. 01/2007)',
                '060' => 'pension alimentaire versee',
                '061' => 'frais de garde',
                '062' => 'deficit profes. annee de ref.',
                '063' => 'deficit foncier',
                '064' => 'csg deductible revenus patrim.',
                '065' => 'cotisations volontaires ss',
                '066' => 'frais de tutelle deductibles',
                '070' => 'rente accident de travail  a titre personnel',
                '071' => 'pension militaire invalidite',
                '072' => 'pension victime de guerre',
                '080' => 'salarie o.d (x 12)',
                '082' => 'salarie autre renouvellement (x 12)',
                '083' => 'salarie 1er renouvellement (x 12)',
                '085' => 'eti od (profession non salariee)',
                '087' => 'eti autre renouvellement (profess. non salariee)',
                '088' => 'eti 1er renouvellement (profess. non salariee)',
                '200' => 'revenus d\'activite d\'insertion (hors cre, ces)',
                '201' => 'remuneration stage formation',
                '203' =>'secours ou aides financieres reguliers',
                '204' => 'indemnites representatives de frais',
                '205' => 'revenu eti/marin pecheur/exploit agricole-rmi',
                '206' => 'pf versees par un autre organisme',
                '207' => 'nombre de repas rmi',
                '211' => 'abattement / neutralisation rmi en montant',
                '212' => 'bourse d\'etudes',
                '213' => 'nombre asf fictives rmi',
                '214' => 'montant asf fictive rmi',
                '215' => 'revenus d\'activite d\'insertion (cre, ces)',
                '216' => 'nombre d\'heures travaillees',
                '300' => 'montant revenu sans pf pour api',
                '301' => 'montant pf caf cedante - api',
                '302' => 'mt forfait caf cedante - api',
                '303' => 'montant allocation veuvage pour api',
                '305' => 'avantages fictifs (p.a.,...)',
                '306' => 'revenu createur d\'entreprise',
                '400' => 'mont. (proport.) mensuel pension',
                '402' => 'garantie de ressources',
                '403' => 'salaire direct (en pourcentage smic)',
                '404' => 'complement de remuneration',
                '405' => 'salaire direct (en euros)',
                '406' => 'maintien avi (oheix)',
                '407' => 'maintien garantie de ressources (oheix)',
                '408' => 'maintien salaire oheix (pourcent.)',
                '409' => 'maintien cplt remun. (oheix)',
                '410' => 'maintien salaire oheix (euros)',
                '500' => 'montant pf etrangeres percues',
                '600' => 'revenu d\'activite aged',
                '602' => 'revenu trimestriel aged',
                '777' => 'autres revenus pour le rso',
                '888' => 'ressources effacees sur demande allocataire',
                '999' => 'refus declarer ressources superieures plafond'
            );
        }



        function natfingro() {
            return array(
                'D' => 'Départ de madame du foyer',
                'I' => 'Interruption de grossesse',
                'M' => 'Enfant mort -né sans déclaration à l\'état civil',
                'N' => 'Naissance',
                'R' => 'Dossier radié sans connaissance des suites de la grossesse',
                'F' => 'Fin de grossesse non justifiée'
            );
        }

        function natpf() {
            return array(
                'RSD' => 'RSA Socle (Financement sur fonds Conseil général)',
                'RSI' => 'RSA Socle majoré (Financement sur fonds Conseil général)',
                'RSU' => 'RSA Socle Etat Contrat aidé  (Financement sur fonds Etat)',
                'RSB' => 'RSA Socle Local (Financement sur fonds Conseil général)',
                'RCD' => 'RSA Activité (Financement sur fonds Etat)',
                'RCI' => 'RSA Activité majoré (Financement sur fonds Etat)',
                'RCU' => 'RSA Activité Etat Contrat aidé (Financement sur fonds Etat)',
                'RCB' => 'RSA Activité Local (Financement sur fonds Conseil général)'
            );
        }
	
        function oridemrsa() {
            return array(
                'RMI' => 'Le droit au rSa est issu de la conversion d\'un droit RMI',
                'API' => 'Le droit au rSa est issu de la conversion d\'un droit API',
                'DEM' => 'Le droit au Rsa fait suite à une demande de RSA'
            );
        }




        function pays() {
            return array(
                'FRA' => 'France',
                'HOR' => 'Hors de France'
            );
        }

        function pieecpres() {
            return array(
                'E' => 'Pièce d\'état civil',
                'P' => 'Certificat de perte'
            );
        }

        function qual() {
            return array(
                'MME' => 'Madame',
                'MLE' => 'Mademoiselle',
                'MR' => 'Monsieur'
            );
        }

        function regfisagri() {
            return array(
                'F' => 'Montant forfaitaire',
                'R' => 'Montant réél'
            );
        }

        function regfiseti() {
            return array(
                'R' => 'Réel',
                'S' => 'Simple',
                'M' => 'Micro'
            );
        }

        function regfisetia1() {
            return array(
                'R' => 'Réel',
                'S' => 'Simple',
                'M' => 'Micro'
            );
        }

        function rgadr() {
            return array(
                '01' => 'Dernière adresse',
                '02' => 'Avant-dernière adresse',
                '03' => 'Avant-avant-dernière adresse'
            );
        }

        function rolepers() {
            return array(
                'DEM' => 'Demandeur du RSA',
                'CJT' => 'Conjoint du demandeur',
                'ENF' => 'Enfant',
                'AUT' => 'Autre personne'
            );
        }

        function sensopecompta() {
            return array(
                'AJ' => 'Ajout du montant dans l\'acompte',
                'DE' => 'Déduction du montant dans l\'acompte'
            );
        }

        function sexe() {
            return array(
                '1' => 'Homme',
                '2' => 'Femme'
            );
        }

        function sousnatpf() {
            return array(
                'RSDN1' => 'RSA Socle -25 avec enfants à charge ou grossesse',
                'RSDN2' => 'RSA Socle +25 ans',
                'RSIN1' => 'RSA Socle majoré',
                'RSUN1' => 'RSA Socle Etat Contrat aidé majoré',
                'RSUN2' => 'RSA Socle Etat Contrat aidé - 25 ans',
                'RSUN3' => 'RSA Socle Etat Contrat aidé + 25 ans',
                'RSBN1' => 'RSA Socle Local majoré',
                'RSBN2' => 'RSA Socle Local -25 ans',
                'RSBN3' => 'RSA Socle Local + 25 ans',
                'RCDN1' => 'RSA Activité -25 avec enfants à charge ou grossesse',
                'RCDN2' => 'RSA Activité +25 ans',
                'RCIN1' => 'RSA Activité majoré',
                'RCUN1' => 'RSA Activité Etat Contrat aidé N1',
                'RCUN2' => 'RSA Activité Etat Contrat aidé N2',
                'RCUN3' => 'RSA Activité Etat Contrat aidé N3',
                'RCBN1' => 'RSA Activité Local majoré',
                'RCBN2' => 'RSA Activité Local -25 ans',
                'RCBN3' => 'RSA Activité Local + 25 ans'
            );
        }

        function topaccre() {
            return array(
        '1' => 'Bénéficiaire de l`ACCRE',
        '0' => 'Non bénéficiaire de l`ACCRE'
            );
        }

        function topbeneti() {
            return array(
        '1' => 'Présence d\'un bénéfice',
        '0' => 'Pas de bénéfices'
            );
        }

        function topcreaentre() {
            return array(
        '1' => 'Créateur d\'entreprise',
        '0' => 'Non créateur d\'entreprise'
            );
        }

        function topempl1ax() {
            return array(
        '1' => 'Emploie 1 ou plusieurs salariés',
        '0' => 'N\'emploie pas 1 ou plusieurs salariés'
            );
        }

        function topevoreveti() {
            return array(
        '1' => 'Evolution des revenus',
        '0' => 'Pas d\'évolution des revenus'
            );
        }

        function topfoydrodevorsa() {
            return array(
                '1' => 'le foyer est soumis à Droits et devoirs (le montant des ressources d\'acitivtés (MTRESSMENRSA) pris en compte pour le rSa est inférieur  au montant du revenu minimum garanti  rSa (MTREVMINGARASA)',
                '0' =>  'le foyer n\'est pas soumis à Droits et devoirs (le montant des ressources d\'acitivtés (MTRESSMENRSA) pris en compte pour le rSa est supérieur ou égale au montant du revenu minimum garanti  rSa (MTREVMINGARASA)'
            );
        }

        function toppersdrodevorsa() {
            return array(
                '' => 'Non défini',
                '1' => 'Oui',
                '0' => 'Non'
            );
        }


        function topressevaeti() {
            return array(
                '1' => 'Ressources à évaluer',
                '0' => 'Pas de ressources à évaluer'
            );
        }
 
        function topsansdomfixe() {
            return array(
                '0' => 'Domicile fixe',
                '1' => 'Sans domicile fixe'
            );
        }
 
        function topsansempl() {
            return array(
        '1' => 'Sans employés',
        '0' => 'Avec employés'
            );
        }
 
        function topstag1ax() {
            return array(
        '1' => 'Emploie 1 ou plusieurs stagiaires',
        '0' => 'N\'emploie pas 1 ou plusieurs stagiaires'
            );
        }


        function typeadr() {
            return array(
                'D' => 'Définitive',
                'P' => 'Provisoire'
            );
        }


        function type_allocation() {
            return array(
                'AllocationComptabilisee' => 'Allocation comptabilisée',
                'IndusConstates' => 'Indus constatés',
                'IndusTransferesCG' => 'Indus transférés au CG',
                'RemisesIndus' => 'Remises des indus',
                'AnnulationsFaibleMontant' => 'Annulations pour faible montant',
                'AutresAnnulations' => 'Autres annulations'
            );
        }

        function type_ci() {
            return array(
                'pre' => 'Premier contrat',
                'ren' => 'Renouvellement',
                'red' => 'Redéfinition'
            );
        }

        function typeopecompta() {
            return array(
/*AllocCompta*/
                'PME' => 'Pour le paiement mensuel',
                'PRA' => 'Pour le paiement de rappel sur mois antérieur',
                'RAI' => 'Pour réajustement  suite à annulation d\'indus',
                'RMU' => 'Pour réajustement suite à mutation du dossier',
                'RTR' => 'Pour réajustement suite à transformation d\'avances ou d\'acomptes en indus',
/*AllocCompta*/
/*Indus constatés*/
                'CIC' => 'Implantation de créance', 
                'CAI' => 'Implantation de créance suite à une opération comptable de réajustement. Une opération de type RAI a été effectuée sur un autre dossier allocataire.',
                'CDC' => 'Implantation d\'un  débit complémentaire (augmentation de la créance)',
/*Indus constatés*/
/*Indus transférés*/
                'CCP' => 'Transfert  de la créance au Conseil général',
/*Indus transférés*/
/*Remises indus*/
                'CRC' => 'Remise de la créance par le Conseil général',
                'CRG' => 'Remise de la créance par la Caf',
/*Remises indus*/
/*Annulation faible*/
                'CAF' => 'Annulation de faible montant  inférieur au seuil réglementaire',
                'CFC' => 'Annulation de faible montant selon seuil fixé par le Conseil général (supérieur au seuil réglementaire)',
/*Annulation faible*/
/*Autre annulations*/
                'CEX' => 'Annulation exceptionelle',
                'CES' => 'Annulation suite à surendettement',
                'CRN' => 'Annulation suite à renouvellement ou revalorisation (publication tardive des baremes, seuils, …)'
/*Autre annulations*/
            );
        }

        function typedtnai() {
            return array(
                'J' => 'Jour inconnu',
                'N' => 'Jour et mois connus',
                'O' => 'Jour et mois inconnus'
            );
        }

        function typeperstie() {
            return array(
                'P' => 'S\'il s\'agit d\'un tiers personne physique',
                'M' => 'S\'il s\'agit d\'un tiers personne morale'
            );
        }

        function type_totalisation() {
            return array(
                'TotalAllocationsComptabilisees' => 'Total des allocations comptabilisees',
                'TotalIndusConstates' => 'Total des indus constates',
                'TotalIndusTransferésCG' => 'Total des indus transferés au CG',
                'TotalRemisesIndus' => 'Total des remises des indus',
                'TotalAnnulationsIndus' => 'Total des annulations des indus',
                'MontantTotalAcompte' => 'Montant total de l\'acompte'
            );
        }

        function typo_aide() {
            return array(
                '1' => 'Insertion sociale',
                '2' => 'Insertion professionnelle',
                '3' => 'Reprise d\'activités'
            );
        }
    }
?>
