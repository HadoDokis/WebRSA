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

        function accosocfam() {
            return array(
                'O' => 'Oui',
                'N' => 'Non',
                'P' => 'Pas de réponse'
            );
        }

        function act() {
            return array(
                'AAP' => 'Activité en atelier protégé',
                'ABA' => 'Chômeur-alloc de base',
                'ABS' => 'Absent du foyer',
                'ADA' => 'Chômeur aud ou pare abattement',
                'ADN' => 'Chômeur aud neutralisation',
                'AFA' => 'Aide familiale agricole',
                'AFC' => 'Cho afr - fin stage - sce.public',
                'AFD' => 'Chômeur-alloc fin droit',
                'AIN' => 'Chômeur-alloc insertion',
                'AMA' => 'Assistante maternelle agréée',
                'AMT' => 'Mi-temps suite plein temps',
                'ANI' => 'Chôm. non indemnisé + activ.',
                'ANP' => 'Inscrit à l\'anpe',
                'APP' => 'Apprenti',
                'ASP' => 'Chômeur-alloc spéciale',
                'ASS' => 'Chômeur-alloc solidarité spec.',
                'CAC' => 'CESSATION ACTIVITE POUR ENFANT',
                'CAP' => 'CHOMEUR ET ACTIVITE > 55% DU SMIC',
                'CAR' => 'DELAI DE CARENCE ASSEDIC',
                'CAT' => 'ACTIVITE CENTRE AIDE TRAVAIL',
                'CBS' => 'CAT: ABSENT DU FOYER',
                'CCV' => 'CONGE CONVENTIONNEL',
                'CDA' => 'CHOMEUR AUD ABAT. + ACTIVITE',
                'CDN' => 'CHOMEUR AUD NEUT. + ACTIVITE',
                'CEA' => 'CES MAINTIEN ABATTEMENT',
                'CEN' => 'CES MAINTIEN NEUTRALISATION',
                'CES' => 'CONTRAT EMPLOI SOLIDARITE',
                'CGP' => 'CONGE PAYE',
                'CHA' => 'CHOM. + ACTIVITE',
                'CHO' => 'CHOMEUR SANS JUSTIFICATIF',
                'CHR' => 'CHOMEUR',
                'CIA' => 'CONTRAT INSERTION/ACTIVITE /DOM',
                'CIS' => 'CONTRAT INSERT. + SALARIE/DOM',
                'CJT' => 'CONJOINT COLLABORATEUR D\'ETI',
                'CLD' => 'CAT: LONGUE MALADIE',
                'CNI' => 'CHOMAGE NON INDEMNISE',
                'CPL' => 'CHOMAGE PARTIEL',
                'CSA' => 'CES ET SALARIE (E)',
                'CSS' => 'CONGE SANS SOLDE',
                'DEG' => 'DEGAGE OBLIGATION SCOLAIRE',
                'DNL' => 'SAL. NON REM. DUREE LEGALE',
                'DSF' => 'DECL SITUATION NON FOURNIE',
                'EBO' => 'ETUDIANT BOURSIER RMI',
                'ETI' => 'ETI REGIME GENERAL',
                'ETS' => 'ETUDIANT SALARIE',
                'ETU' => 'ETUDIANT',
                'EXP' => 'ETI REGIME AGRICOLE',
                'EXS' => 'EXPL. AGRICOLE EN CES/DOM',
                'FDA' => 'FONCT. PUBL. CHOM. AUD-ABA',
                'FDN' => 'FONCT. PUBL. CHOM. AUD-NEU',
                'GSA' => 'GERANT SALARIE',
                'HAN' => 'INFIRME/HANDICAPE',
                'IAD' => 'INSTRUCTION A DOMICILE',
                'INF' => 'MALADE/HANDICAP NON SCOLAIRE',
                'INP' => 'INAPTE',
                'INT' => 'TRAVAILLEUR INTERMITTENT',
                'INV' => 'PENSION INVALIDITE',
                'JNF' => 'JUST NON FOURNI POUR APPRENTI',
                'MAL' => 'MALADE',
                'MAR' => 'ETI MARIN PECHEUR',
                'MAT' => 'CONGE MATERNITE OU PATERNITE CONGE MATER./PATER.',
                'MLD' => 'MALADIE LONGUE DUREE',
                'MMA' => 'ENFANT MAINTENU MATERNELLE',
                'MMC' => 'MAL MATERN. ET CHOMAGE ABAT.',
                'MMI' => 'MAL MATERN. ET CHOMAGE NEUT.',
                'MNE' => 'MORT NE VIABLE OU NON VIABLE',
                'MOA' => 'MEMBRE ORG COMM EN ACTIVITE',
                'MOC' => 'MEMBRE ORG COMM SANS ACTIVITE',
                'NAS' => 'INASSIDU',
                'NCH' => 'PLUS DE DROIT / NON A CHARGE',
                'NOB' => 'NON SOUMIS OBLIG. SCOLAIRE',
                'PIL' => 'STAGIAIRE PROG INSERT. LOCALE',
                'PRE' => 'Pré-retraite',
                'RAC' => 'Réduction activité (cat)',
                'RAT' => 'Rentier accident du travail',
                'RET' => 'Retraité(e)',
                'RMA' => 'Titulaire contrat cirma/cav',
                'RSA' => 'Retraité(e) militaire <60ans',
                'SAB' => 'Congé sabbatique',
                'SAC' => 'Cessation activité bénef aah',
                'SAL' => 'Salarié(e)',
                'SAV' => 'Sans activité motif cdaph (ex-cotorep)',
                'SCI' => 'Malade/handicapé scolarisé',
                'SCO' => 'Scolaire',
                'SFP' => 'Stage form. professionnelle',
                'SIN' => 'Activité inconnue',
                'SNA' => 'Service national actif',
                'SNR' => 'Stage non remunéré et rmi',
                'SSA' => 'Sans activité',
                'SUR' => 'Benef. rente de survivant at',
                'TSA' => 'Travailleur saisonnier',
                'VRP' => 'Voyageur représentant placier'
            );
        }

//         function accoemploi() {
//             return array(
//                 '1801' => 'Pas d\'accompagnement',
//                 '1802' => 'Pôle-emploi',
//                 '1803' => 'Autres'
//             );
//         }

        function acteti() {
            return array(
                'C' => 'Commerçant',
                'A' => 'Artisan',
                'L' => 'Profession libérale',
                'E' => 'Entrepreneur'
            );
        }



        function applieme() {
            return array(
                'CRI' => 'Cristal Cnaf',
                'AGO' => 'Agora Ccmsa',
                'NRI' => '@IRMI Cnaf',
                'NRS' => '@RSA Cnaf',
                'IOD' => 'IODAS GFI',
                'GEN' => 'GENESIS SIRUS-BULL',
                'IAS' => 'IAS JVS implicit',
                'PER' => 'Peceaveal INFODB',
                '54' => ' Logiciel du CG 54'
            );
        }

        function autorutiadrelec() {
            return array(
                'A' => 'Accord d\'utilisation',
                'I' => 'Inconnu',
                'R' => 'Refus d\'utilisation'
            );
        }

        function autorutitel() {
            return array(
                'A' => 'Accord d\'utilisation',
                'I' => 'Inconnu',
                'R' => 'Refus d\'utilisation'
            );
        }


        function aviscondadmrsa() {
            return array(
                'D' => 'Avis demandé au CG',
                'A' => 'Accord du CG',
                'R' => 'Refus du CG'
            );
        }

        function avisdero() {
            return array(
                'D' => 'Avis demandé au CG',
                'O' => 'Accord du CG',
                'N' => 'Refus du CG',
                'A' => 'Ajourné'
            );
        }

        function avisdestpairsa() {
            return array(
                'D' => 'Avis demandé au CG',
                'A' => 'Accord du CG',
                'R' => 'Refus du CG'
            );
        }

        function aviseqpluri() {
            return array(
                'R' => 'Réorientation',
                'M' => 'Maintien de l\'orientation'
            );
        }

        function avisraison_ci() {
            return array(
                'D' => 'Défaut de conclusion',
                'N' => 'Non respect du contrat'
            );
        }

        function categorie(){
            return array(
                '1' => 'Personnes sans emploi, immédiatement disponibles, tenues d\'accomplir des actes positifs de recherche d\'emploi, à la recherche d\'un emploi en CDI à plein temps.',
                '2' => 'Personnes sans emploi, tenues d\'accomplir des actes positifs de recherche d\'emploi, à la recherche d\'un emploi en CDI à temps partiel.',
                '3' => 'Personnes sans emploi, tenues d\'accomplir des actes positifs de recherche d\'emploi, à la recherche d\'un emploi en CDD, temporaire ou saisonnier, y compris de très courte durée.',
                '4' => 'Personnes sans emploi, non immédiatement disponibles, à la recherche d\'un emploi ( arrêt maladie de plus de 15 jours, formation de plus de 40 heures...).',
                '5' => 'Personnes pourvues d\'un emploi, à la recherche d\'un autre emploi ( salarié en préavis effectué ou non, CAE, bénévoles...).',
                '6' => 'Personnes non immédiatement disponibles, à la recherche d\'un autre emploi en CDI à temps plein, tenues d\'accomplir des actes positifs de recherche d\'emploi.',
                '7' => 'Personnes non immédiatement disponibles, à la recherche d\'un emploi en CDI à temps partiel, tenues d\'accomplir des actes positifs de recherche d\'emploi.',
                '8' => 'Personnes non immédiatement disponibles, à la recherche d\'un autre emploi en CDI, temporaire ou saisonnier, y compris de très courte durée, tenues d\'accomplir des actes positifs de recherche d\'emploi.',
            );
        }

        function commission() { ///FIXME: ajout pour les PDO mais à voir
            return array(
                'V' => 'Commission de validation',
                'D' => 'Commission de décision',
                'P' => 'Commission pluridisciplinaire'
            );
        }

        function couvsoc() {
            return array(
                'O' => 'Oui',
                'N' => 'Non',
                'P' => 'Pas de réponse'
            );
        }

        function creareprisentrrech() {
            return array(
                'O' => 'Oui',
                'N' => 'Non',
                'P' => 'Pas de réponse'
            );
        }

        function decisionpdo() {
            return array(
                'P' => 'En attente d\'ouverture',
                'I' => 'Instruction en cours',
                'O' => 'Droit ouvert',
                'R' => 'Rejeté',
                'A' => 'Radié',
                'S' => 'Suspendu'
            );
        }

        function decisionrecours() {
            return array(
                'P' => 'Pas de décision',
                'A' => 'Accord',
                'R' => 'Refus',
                'J' => 'Ajourné'
            );
        }

        function decision_ci() {
            return array(
                'E' => 'En attente de décision',
                'V' => 'Validation à compter du',
                'A' => 'Ajournement',
                'R' => 'Rejet'
            );
        }

        function demarlog() {
            return array(
                '1101' => 'Accès à un logement',
                '1102' => 'Maintien dans le logement',
                '1103' => 'Aucune'
            );
        }

        function dipfra(){
            return array(
                'F' => 'Français',
                'E' => 'Etranger'
            );
        }


        function dif(){
            return array(
                '<=' => '<=',
                '=>' => '=>',
                '<' => '<',
                '>' => '>'
            );
        }




        function domideract(){
            return array(
                'O' => 'Oui',
                'N' => 'Non',
                'P' => 'Pas de réponse'
            );
        }

        function drorsarmiant(){
            return array(
                'O' => 'Oui',
                'N' => 'Non',
                'P' => 'Pas de réponse'
            );
        }

        function drorsarmianta2(){
            return array(
                'O' => 'Oui',
                'N' => 'Non',
                'P' => 'Pas de réponse'
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

        function duree_engag_cg66() {
            return array(
                '1' => '3 mois',
                '2' => '6 mois',
                '3' => '9 mois',
                '4' => '12 mois'
            );
        }

        function duree_engag_cg93() {
            return array(
                '1' => '3 mois',
                '2' => '6 mois',
                '3' => '9 mois',
                '4' => '12 mois',
                '5' => '18 mois',
                '6' => '24 mois'
            );
        }

        function duree_cdd(){
            return array(
                'DT1' => 'Temps plein',
                'DT2' => 'Temps partiel',
                'DT3' => 'Mi-temps'
            );
        }

        function duree_hebdo_emp(){
            return array(
                'DHT1' => 'Moins de 35h',
                'DHT2' => '35h',
                'DHT3' => 'Entre 35h et 48h'
            );
        }

        function elopersdifdisp(){
            return array(
                'O' => 'Oui',
                'N' => 'Non',
                'P' => 'Pas de réponse'
            );
        }

        function engproccrealim(){
            return array(
                'O' => 'Procédure engagée',
                'N' => 'Pas de procédure engagée',
                'R' => 'Refus d\'engagement de procédure'
            );
        }

        function emp_occupe(){
            return array(
                '10' => 'Agriculteurs (salariés de leur exploitation)',
                '21' => 'Artisans (salariés de leur entreprise)',
                '22' => 'Commerçants et assimilés (salariés de leur entreprise)',
                '23' => 'Chefs d\'entreprise de 10 salariés ou plus (salariés de leur entreprise)',
                '31' => 'Professions libérales (exercées sous statut de salarié)',
                '33' => 'Cadres de la fonction publique',
                '34' => 'Professeurs, professions scientifiques',
                '35' => 'Professions de l\'information, des arts et des spectacles',
                '37' => 'Cadres administratifs et commerciaux d\'entreprises',
                '38' => 'Ingénieurs et cadres techniques d\'entreprises',
                '42' => 'Professeurs des écoles, instituteurs et professions assimilées',
                '43' => 'Professions intermédiaires de la santé et du travail social',
                '44' => 'Clergé, religieux',
                '45' => 'Professions intermédiaires administratives de la fonction publique',
                '46' => 'Professions intermédiaires administratives et commerciales des entreprises',
                '47' => 'Techniciens (sauf techniciens tertiaires)',
                '48' => 'Contremaîtres, agents de maîtrise (maîtrise administrative exclue)',
                '52' => 'Employés civils et agents de service de la fonction publique',
                '53' => 'Agents de surveillance',
                '54' => 'Employés administratifs d\'entreprise',
                '55' => 'Employés de commerce',
                '56' => 'Personnels des services directs aux particuliers',
                '62' => 'Ouvriers qualifiés de type industriel',
                '63' => 'Ouvriers qualifiés de type artisanal',
                '64' => 'Chauffeurs',
                '65' => 'Ouvriers qualifiés de la manutention, du magasinage et du transport',
                '67' => 'Ouvriers non qualifiés de type industriel',
                '68' => 'Ouvriers non qualifiés de type artisanal',
                '69' => 'Ouvriers agricoles et assimilés'
            );
        }

        function etatcrealim() {
            return array(
                'SA' => 'Sanction appliquée',
                'DD' => 'Dispense demande',
                'AT' => 'Attente décision allocataire',
                'DS' => 'Dispense avec sanction',
                'SF' => 'Présence d\'ASF',
                'PS' => 'Pas de sanction',
                'DA' => 'Dispense accord',
                'PE' => 'Procédure engagée',
                'DR' => 'Dispense refus',
                'RM' => 'Ex-RMI',
                'MS' => 'Maintien sanction',
                'SI' => 'Sanction immédiate',
                'RE' => 'Refus engagement',
                'TR' => 'Tiers recueillant',
                'AA' => 'Aucune décision allocataire'
            );
        }

        function etatdosrsa() {
            return array(
                'Z' => 'Non défini',
                '0'  => 'Nouvelle demande en attente de décision CG pour ouverture du droit',
                '1'  => 'Droit refusé',
                '2'  => 'Droit ouvert et versable',
                '3'  => 'Droit ouvert et suspendu (le montant du droit est calculable, mais l\'existence du droit est remis en cause)',
                '4'  => 'Droit ouvert mais versement suspendu (le montant du droit n\'est pas calculable)',
                '5'  => 'Droit clos',
                '6'  => 'Droit clos sur mois antérieur ayant eu des créances transferées ou une régularisation dans le mois de référence pour une période antérieure.'

            );
        }

        function suiirsa() {
            return array(
				'01' => 'Données administratives',
				'11' => 'Données socio-profesionnelles du demandeur',
				'12' => 'Données socio-profesionnelles du conjoint',
				'21' => 'Données parcours du demandeur',
				'22' => 'Données parcours du conjoint',
				'31' => 'Données orientation du demandeur',
				'32' => 'Données orientation du conjoint'
            );
        }

        function fg() {
            return array(
                'SUS' => 'suspension',
                'DESALL' => 'désignation allocataire',
                'SITPRO' => 'situation professionnelle',
                'INTGRO' => 'interruption grossesse',
                'ETACIV' => 'état civil',
                'SITENFAUT' => 'situation enfant/aut',
                'RESTRIRSA' => 'ressources trimestrielles RSA',
                'SITFAM' => 'situation famille',
                'DECDEMPCG' => 'décision du Président du CG',
                'CARRSA' => 'caractéristiques RSA',
                'PROPCG' => 'proposition au Président CG',
                'HOSPLA'  => 'hospitalisation placement',
                'CIRMA' => 'Cirma ou Cav',
                'SUIRMA' => 'Suivi de Cirma ou de Cav',
                'RECPEN' => 'récépissé pension',
                'TITPEN'  => 'titre de pension',
                'REA' => 'réaffiliation (Fait générateur générique)',
                'DERPRE'  => 'Dérogation du Président du CG',
                'ABANEURES'  => 'abattement ou neuratisation de ressource',
                'DEMRSA'  => 'demande de RSA (Fait générateur générique)',
                'CREALI' => 'créance alimentaire',
                'ASF'  => 'demande ASF',
                'EXCPRE' => 'exclusion Prestation',
                'ADR' => 'Adresse',
                'RAD' => 'Radiation du dossier',
                'MUT' => 'Mutation du dossier'
            );
        }

        function fonction_pers() {
            return array(
                'ADM' => 'Administrateur',
                'VAL' => 'Validateur',
                'AGE' => 'Agent simple'
            );
        }


        function formeci() {
            return array(
                'S' => 'Simple',
                'C' => 'Complexe'
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
                'A' => 'Aide',
                'P' => 'Prestation'
            );
        }


        function lib_struc() {
            return array(
                '1' => 'Pole emploi',
                '2' => 'Assedic'
            );
        }

        function matetel() {
            return array(
                'FAX' => 'Fax seul',
                'TEL' => 'Téléphone seul',
                'TFA' => 'Téléphone/Fax'
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

        function motidiscrealim() {
            return array(
                'AVA' => 'Avantage en nature autre que le logement',
                'LOG' => 'Logement fourni par les parents',
                'PAM' => 'Pension à l\'amiable',
                'PHE' => 'Parent hors d\'état ou décédé',
                'AUT' => 'Autre motif de dispense'
            );

        }
//         function motideccg() { ///FIXME: ajout pour les PDO mais à voir
//             return array(
//                 'E' => 'En attente de justificatif',
//                 'A' => 'Admissible',
//                 'N' => 'Non admissible'
//             );
//         }

        function motifpdo() { ///FIXME: ajout pour les PDO mais à voir
            return array(
                'E' => 'En attente de justificatif',
                'A' => 'Admissible',
                'N' => 'Non admissible'
            );
        }

        function motidempdo() { ///FIXME: ajout pour les PDO mais à voir
            return array(
                'C' => 'Changement de situation',
                'P' => 'Perte d\'emploi'
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
                '02' => 'Moins de 25 ans et personne à charge',
                '03' => 'Activité non conforme',
                '04' => 'Titre de sejour non valide',
                '05' => 'RSA inférieur au seuil',
                '06' => 'Déclaration Trimestrielle Ressources non fournie',
                '09' => 'Résidence non conforme',
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

        function nat_cont_trav(){
            return array(
                'TCT1' => 'Travailleur indépendant',
                'TCT2' => 'CDI',
                'TCT3' => 'CDD',
                'TCT4' => 'Contrat de travail temporaire (Intérim)',
                'TCT5' => 'Contrat de professionnalisation',
                'TCT6' => 'Contrat d\'apprentissage',
                'TCT7' => 'Contrat Initiative Emploi (CIE)',
                'TCT8' => 'Contrat d\'Accompagnement dans l\'Emploi (CAE)',
                'TCT9' => 'Chèque Emploi Service Universel (CESU)'
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
                '0904' => 'Logement d\'urgence : CHRS (Centre d\'Hébergement et de Réinsertion Sociale)',
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

        function natpfcre( $type = null ) {
            $natindu = array(
                'totsocl' => array(
                    'RSD' => 'Rsa socle',
                    'INK' => 'Indu sur rsa socle ',
                    'ITK' => 'Indu sur rsa socle  transféré ou reçu d\'une autre Caf ou Msa ',
                    'ISK' => 'Indu sur rsa socle subrogé',
                    'ACD' => 'ACD',
                    'ASD' => 'Acompte sur droit rsa. (le droit est constaté et ouvert)'
                ),
                'soclmaj' => array(
                    'RSI' => 'Rsa socle majoration parent isolé',
                    'INL' => 'Indu sur rsa socle majoré',
                    'ITL' => 'Indu sur rsa socle majoré transféré ou reçu d\'une autre Caf ou Msa '
                ),
                'localrsa' => array(
                    'RSB' => 'Rsa socle local',
                    'RCB' => 'Rsa activité local',
                    'INM' => 'Indu sur rsa socle local ou rSa activite local',
                    'ITM' => 'Indu sur rsa socle local ou rSa activite local transféré ou reçu d\'une autre Caf ou Msa '
                ),
                'indutotsocl' => array(
                    'INK' => 'Indu sur rsa socle ',
                    'ITK' => 'Indu sur rsa socle  transféré ou reçu d\'une autre Caf ou Msa ',
                    'ISK' => 'Indu sur rsa socle subrogé',
                ),
                'alloccompta' => array(
                    'RSD' => 'Rsa socle',
                    'RSI' => 'Rsa socle majoration parent isolé',
                    'RSB' => 'Rsa socle local',
                    'RCB' => 'Rsa activité local',
                    'ASD' => 'Acompte sur droit rsa. (le droit est constaté et ouvert)',
                    'VSD' => 'Avance sur droit rsa (suite absence DTRSa ou dans l\'attente de l\'ouverture du droit)',
                    'INK' => 'Indu sur rsa socle ',
                    'INL' => 'Indu sur rsa socle majoré',
                    'INM' => 'Indu sur rsa socle local ou rSa activite local',
                    'ITK' => 'Indu sur rsa socle  transféré ou reçu d\'une autre Caf ou Msa ',
                    'ITL' => 'Indu sur rsa socle majoré transféré ou reçu d\'une autre Caf ou Msa ',
                    'ITM' => 'Indu sur rsa socle local ou rSa activite local transféré ou reçu d\'une autre Caf ou Msa ',
                    'ISK' => 'Indu sur rsa socle subrogé',
                ),
                'indutransferecg' => array(
                    'INK' => 'Indu sur rsa socle ',
                    'INL' => 'Indu sur rsa socle majoré',
                    'INM' => 'Indu sur rsa socle local ou rSa activite local',
                    'ITK' => 'Indu sur rsa socle  transféré ou reçu d\'une autre Caf ou Msa ',
                    'ITL' => 'Indu sur rsa socle majoré transféré ou reçu d\'une autre Caf ou Msa ',
                    'ITM' => 'Indu sur rsa socle local ou rSa activite local transféré ou reçu d\'une autre Caf ou Msa ',
                ),
                'annulationfaible' => array(
                    'INK' => 'Indu sur rsa socle ',
                    'INL' => 'Indu sur rsa socle majoré',
                    'INM' => 'Indu sur rsa socle local ou rSa activite local',
                    'ITK' => 'Indu sur rsa socle  transféré ou reçu d\'une autre Caf ou Msa ',
                    'ITL' => 'Indu sur rsa socle majoré transféré ou reçu d\'une autre Caf ou Msa ',
                    'ITM' => 'Indu sur rsa socle local ou rSa activite local transféré ou reçu d\'une autre Caf ou Msa ',
                    'ISK' => 'Indu sur rsa socle subrogé',
                    'INN' => 'Indu RCD RCI',
                    'ITN' => 'Indu RCD RCI transféré',
                    'INP' => 'Indu RSU RCU',
                    'ITP' => 'Indu RSU RCU transféré'
                ),
                'autreannulation' => array(
                    'INK' => 'Indu sur rsa socle ',
                    'INL' => 'Indu sur rsa socle majoré',
                    'INM' => 'Indu sur rsa socle local ou rSa activite local',
                    'ITK' => 'Indu sur rsa socle  transféré ou reçu d\'une autre Caf ou Msa ',
                    'ITL' => 'Indu sur rsa socle majoré transféré ou reçu d\'une autre Caf ou Msa ',
                    'ITM' => 'Indu sur rsa socle local ou rSa activite local transféré ou reçu d\'une autre Caf ou Msa ',
                    'ISK' => 'Indu sur rsa socle subrogé',
                )
            );

            switch( $type ){
                case 'totalloccompta':
                case 'soclmaj':
                case 'localrsa':
                case 'alloccompta':
                case 'indutransferecg':
                case 'annulationfaible':
                case 'autreannulation':
                    return $natindu[$type];
                default:
                    $result = array();
                    $keys = array_keys( $natindu );
                    foreach( $keys as $key ) {
                        $result = Set::merge( $result, $natindu[$key] );
                    }
                    return $result;
            }

            return array(
/*AllocCompta*/
                'RSD' => 'Rsa socle',
                'RSI' => 'Rsa socle majoration parent isolé',
                'RSB' => 'Rsa socle local',
                'RCB' => 'Rsa activité local',
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
                'ISK' => 'Indu sur rsa socle subrogé',
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
                '000' => 'Ressources nulles',
                '001' => 'Salaires sans abattement supplementaire frais p',
                '002' => 'Frais professionnels reels deductibles',
                '003' => 'Salaires avec abattement supplementaire frais p',
                '004' => 'Abattement supplementaire pour frais profession',
                '005' => 'Salaires percus a l\'etranger',
                '006' => 'Revenus exceptionnels d\'activite salarie',
                '007' => 'Cav / cirma',
                '009' => 'Chomage partiel (technique)',
                '010' => 'Allocations de chomage',
                '011' => 'Indem. maladie/maternite/pater.',
                '012' => 'Accident travail/maladie prof.',
                '013' => 'Indemnites maternite/partenite/adoption',
                '014' => 'Autres ijss (maladie, at, mp)',
                '020' => 'Pre-retraite',
                '021' => 'Pension d\'invalidite',
                '022' => 'Pension de vieillesse imposable',
                '023' => 'Contrat d\'epargne - handicape',
                '024' => 'Rente viagere a titre gratuit',
                '025' => 'Allocation de veuvage',
                '026' => 'Pensions alimentaires recues',
                '027' => 'Rente viagere onereux - tiers',
                '028' => 'Pension vieill. non imposable',
                '029' => 'Majoration pension/retraite non imposable',
                '030' => 'Revenu des professions non salariees',
                '031' => 'Revenu profes non salar. non fixe ou inconnu',
                '032' => 'Forfait agricole',
                '033' => 'Forfait agricole non fixe',
                '034' => 'Revenu eti non cga ni micro-bic',
                '040' => 'Revenus fonciers et immobiliers',
                '041' => 'Autres revenus imposables',
                '042' => 'Ressources de l\'ex-conjoint (pinna)',
                '043' => 'Revenus soumis a prelevement liberatoire',
                '050' => 'Eval forf (salaires) ttes prest',
                '051' => 'Eval forf (eti) ttes prest',
                '052' => 'Evaluation forfaitaire (cat)',
                '053' => 'Evaluat. forfait. (salaires) / apl',
                '054' => 'Evaluation forfaitaire eti/ apl',
                '055' => 'Evaluation forfaitaire (esat g.r. 01/2007)',
                '060' => 'Pension alimentaire versee',
                '061' => 'Frais de garde',
                '062' => 'Deficit profes. annee de ref.',
                '063' => 'Deficit foncier',
                '064' => 'Csg deductible revenus patrim.',
                '065' => 'Cotisations volontaires ss',
                '066' => 'Frais de tutelle deductibles',
                '070' => 'Rente accident de travail  a titre personnel',
                '071' => 'Pension militaire invalidite',
                '072' => 'Pension victime de guerre',
                '080' => 'Salarie o.d (x 12)',
                '082' => 'Salarie autre renouvellement (x 12)',
                '083' => 'Salarie 1er renouvellement (x 12)',
                '085' => 'Eti od (profession non salariee)',
                '087' => 'Eti autre renouvellement (profess. non salariee)',
                '088' => 'Eti 1er renouvellement (profess. non salariee)',
                '200' => 'Revenus d\'activite d\'insertion (hors cre, ces)',
                '201' => 'Remuneration stage formation',
                '203' => 'Secours ou aides financieres reguliers',
                '204' => 'Indemnites representatives de frais',
                '205' => 'Revenu eti/marin pecheur/exploit agricole-rmi',
                '206' => 'Pf versees par un autre organisme',
                '207' => 'Nombre de repas rmi',
                '211' => 'Abattement / neutralisation rmi en montant',
                '212' => 'Bourse d\'etudes',
                '213' => 'Nombre asf fictives rmi',
                '214' => 'Montant asf fictive rmi',
                '215' => 'Revenus d\'activite d\'insertion (cre, ces)',
                '216' => 'Nombre d\'heures travaillees',
                '300' => 'Montant revenu sans pf pour api',
                '301' => 'Montant pf caf cedante - api',
                '302' => 'Mt forfait caf cedante - api',
                '303' => 'Montant allocation veuvage pour api',
                '305' => 'Avantages fictifs (p.a.,...)',
                '306' => 'Revenu createur d\'entreprise',
                '400' => 'Mont. (proport.) mensuel pension',
                '402' => 'Garantie de ressources',
                '403' => 'Salaire direct (en pourcentage smic)',
                '404' => 'Complement de remuneration',
                '405' => 'Salaire direct (en euros)',
                '406' => 'Maintien avi (oheix)',
                '407' => 'Maintien garantie de ressources (oheix)',
                '408' => 'Maintien salaire oheix (pourcent.)',
                '409' => 'Maintien cplt remun. (oheix)',
                '410' => 'Maintien salaire oheix (euros)',
                '500' => 'Montant pf etrangeres percues',
                '600' => 'Revenu d\'activite aged',
                '602' => 'Revenu trimestriel aged',
                '777' => 'Autres revenus pour le rso',
                '888' => 'Ressources effacees sur demande allocataire',
                '999' => 'Refus declarer ressources superieures plafond'
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

        function nattel(){
            return array(
                'D' => 'Domicile',
                'T' => 'Travail'
            );
        }

        function natureAidesApres() {
            return array(
                'Formqualif'     => 'Formations individuelles qualifiantes',
                'Formpermfimo'   => 'Formation Permis Poids Lourd + FIMO',
                'Actprof'        => 'Action de professionnalisation des contrats aides et SIAE',
                'Permisb'        => 'Permis de conduire B',
                'Amenaglogt'     => 'Aide à l\'installation',
                'Acccreaentr'    => 'Accompagnement à la création d\'entreprise',
                'Acqmatprof'     => 'Acquisition de matériels professionnels',
                'Locvehicinsert' => 'Aide à la location d\'un véhicule d\'insertion'
            );
        }

        function numorg() {
            return array(
               '011' => 'CAF DE BOURG EN BRESSE',
               '021' => 'CAF DE SAINT QUENTIN',
               '022' => 'CAF DE SOISSONS',
               '031' => 'CAF DE MOULINS',
               '041' => 'CAF DE DIGNE-LES-BAINS',
               '051' => 'CAF DE GAP',
               '061' => 'CAF DE NICE',
               '071' => 'CAF D\'ANNONAY',
               '072' => 'CAF D\'AUBENAS',
               '081' => 'CAF DE CHARLEVILLE MEZIERES',
               '091' => 'CAF DE FOIX',
               '101' => 'CAF DE TROYES',
               '111' => 'CAF DE CARCASSONNE',
               '121' => 'CAF DE RODEZ',
               '131' => 'CAF DE MARSEILLE',
               '141' => 'CAF DE CAEN',
               '151' => 'CAF D\'AURILLAC',
               '161' => 'CAF D\'ANGOULEME',
               '171' => 'CAF DE LA ROCHELLE',
               '172' => 'CAISSE MARITIME D\'AF PECHE MARITIME',
               '181' => 'CAF DE BOURGES',
               '191' => 'CAF DE BRIVE',
               '201' => 'CAF D\'AJACCIO',
               '202' => 'CAF DE BASTIA',
               '211' => 'CAF DE DIJON',
               '221' => 'CAF DE SAINT BRIEUC',
               '231' => 'CAF DE GUERET',
               '241' => 'CAF DE PERIGUEUX',
               '251' => 'CAF DE BESANCON',
               '252' => 'CAF DE MONTBELIARD',
               '261' => 'CAF DE VALENCE',
               '271' => 'CAF D\'EVREUX',
               '281' => 'CAF DE CHARTRES',
               '291' => 'CAF DE BREST',
               '292' => 'CAF DE QUIMPER',
               '301' => 'CAF DE NIMES',
               '311' => 'CAF DE TOULOUSE',
               '321' => 'CAF D\'AUCH',
               '331' => 'CAF DE BORDEAUX',
               '341' => 'CAF DE BEZIERS',
               '342' => 'CAF DE MONTPELLIER',
               '351' => 'CAF DE RENNES',
               '361' => 'CAF DE CHATEAUROUX',
               '371' => 'CAF DE TOURS',
               '381' => 'CAF DE GRENOBLE',
               '382' => 'CAF DE VIENNE',
               '391' => 'CAF DE SAINT CLAUDE',
               '401' => 'CAF DE MONT DE MARSAN',
               '411' => 'CAF DE BLOIS',
               '421' => 'CAF DE ROANNE',
               '422' => 'CAF DE SAINT ETIENNE',
               '431' => 'CAF DU PUY',
               '441' => 'CAF DE NANTES',
               '451' => 'CAF D\'ORLEANS',
               '461' => 'CAF DE CAHORS',
               '471' => 'CAF D\'AGEN',
               '481' => 'CAF DE MENDE',
               '491' => 'CAF D\'ANGERS',
               '492' => 'CAF DE CHOLET',
               '501' => 'CAF D\'AVRANCHES',
               '511' => 'CAF DE REIMS',
               '521' => 'CAF DE CHAUMONT',
               '531' => 'CAF DE LAVAL',
               '541' => 'CAF DE NANCY',
               '551' => 'CAF DE BAR LE DUC',
               '561' => 'CAF DE VANNES',
               '571' => 'CAF DE METZ',
               '581' => 'CAF DE NEVERS',
               '591' => 'CAF D\'ARMENTIERES',
               '592' => 'CAF DE CAMBRAI',
               '593' => 'CAF DE DOUAI',
               '594' => 'CAF DE DUNKERQUE',
               '595' => 'CAF DE LILLE',
               '596' => 'CAF DE MAUBEUGE',
               '597' => 'CAF DE ROUBAIX',
               '599' => 'CAF DE VALENCIENNES',
               '601' => 'CAF DE BEAUVAIS',
               '602' => 'CAF DE CREIL',
               '611' => 'CAF D\'ALENCON',
               '621' => 'CAF D\'ARRAS',
               '622' => 'CAF DE CALAIS',
               '631' => 'CAF DE CLERMONT FERRAND',
               '641' => 'CAF DE BAYONNE',
               '642' => 'CAF DE PAU',
               '651' => 'CAF DE TARBES',
               '661' => 'CAF DE PERPIGNAN',
               '671' => 'CAF DE STRASBOURG',
               '681' => 'CAF DE MULHOUSE',
               '691' => 'CAF DE LYON',
               '692' => 'CAF DE VILLEFRANCHE SUR SAONE',
               '701' => 'CAF DE VESOUL',
               '711' => 'CAF DE MACON',
               '721' => 'CAF DU MANS',
               '731' => 'CAF DE CHAMBERY',
               '741' => 'CAF D\'ANNECY',
               '751' => 'CAF DE PARIS',
               '752' => 'CAF de PARIS - NAVIG. INTERIEURE',
               '754' => 'CAF de PARIS - MARINS DU COMMERCE',
               '761' => 'CAF DE DIEPPE',
               '762' => 'CAF D\'ELBEUF',
               '763' => 'CAF DU HAVRE',
               '764' => 'CAF DE ROUEN',
               '771' => 'CAF DE MELUN',
               '781' => 'CAF DE ST QUENTIN EN YVELINES',
               '791' => 'CAF DE NIORT',
               '801' => 'CAF D\'AMIENS',
               '811' => 'CAF D\'ALBI',
               '821' => 'CAF DE MONTAUBAN',
               '831' => 'CAF DE TOULON',
               '841' => 'CAF D\'AVIGNON',
               '851' => 'CAF DE LA ROCHE SUR YON',
               '861' => 'CAF DE POITIERS',
               '871' => 'CAF DE LIMOGES',
               '881' => 'CAF D\'EPINAL',
               '891' => 'CAF D\'AUXERRE',
               '901' => 'CAF DE BELFORT',
               '911' => 'CAF D\'EVRY',
               '921' => 'CAF DE NANTERRE',
               '931' => 'CAF DE ROSNY SOUS BOIS',
               '941' => 'CAF DE CRETEIL',
               '951' => 'CAF DE CERGY PONTOISE',
               '971' => 'CAF DE POINTE A PITRE',
               '972' => 'CAF DU LAMENTIN',
               '973' => 'CAF DE CAYENNE',
               '974' => 'CAF DE SAINT DENIS-DE-LA-REUNION',
               '976' => 'MAYOTTE'
            );
        }

        function obstemploidifdisp(){
            return array(
                'O' => 'Oui',
                'N' => 'Non',
                'P' => 'Pas de réponse'
            );
        }

        function oridemrsa() {
            return array(
                'DEM' => 'Le droit au Rsa fait suite à une demande de RSA',
                'RMI' => 'Le droit au rSa est issu de la conversion d\'un droit RMI',
                'API' => 'Le droit au rSa est issu de la conversion d\'un droit API'
            );
        }

        function orioblalim() {
            return array(
                'CJT' => 'Obligation ex-conjoint',
                'PAR' => 'Obligation parent(s)'
            );
        }

        function parassoasf() {
            return array(
                'P' => 'Père',
                'M' => 'Mère'
            );
        }

        function pays() {
            return array(
                'FRA' => 'France',
                'HOR' => 'Hors de France'
            );
        }

        function paysact() {
            return array(
                'FRA' => 'France',
                'LUX' => 'Luxembourg',
                'CEE' => 'Communauté Européenne (sauf France, et  Luxembourg)',
                'ACE' => 'Assimilé à la Communauté Européenne',
                'CNV' => 'Pays avec convention sauf CEE',
                'AUT' => 'Autres pays'
            );
        }

        function pieecpres() {
            return array(
                'E' => 'Pièce d\'état civil',
                'P' => 'Certificat de perte'
            );
        }

        function printed() {
            return array(
                '' => 'Imprimé/Non imprimé',
                'I' => 'Imprimé',
                'N' => 'Non imprimé'
            );
        }

        function qual() {
            return array(
                'MME' => 'Madame',
                'MLE' => 'Mademoiselle',
                'MR' => 'Monsieur'
            );
        }

        ///FIXME: voir si on peut mieux faire, vu que pour les mois de 30 jours ou Fevrier ça pose problème
        function quinzaine() {
            return array(
                '1' => 'Première quinzaine',
                '2' => 'Deuxième quinzaine'
            );
        }

        function raison_ci() {
            return array(
                'S' => 'Suspension',
                'R' => 'Radiation'
            );
        }

        function reg() {
            return array(
                'AA' => 'ARTISTE/AUTEUR/COMPOSITEUR',
                'AD' => 'PF DUES PAR ADMINIS. (NON RETRAITE)',
                'AG' => 'AGRICOLE',
                'AL' => 'PF DUES PAR ADMINIS. SAUF AL',
                'AM' => 'ADMINIS. DROIT PF CAF (NON RETRAITE)',
                'CL' => 'COLLECT. LOCALE/HOPIT (NON RETRAITE)',
                'EF' => 'EDF - GDF',
                'EN' => 'EDUCATION NATIONALE',
                'FP' => 'FONCTION PUBLIC HORS EDUC. NAT.',
                'FT' => 'FRANCE TELECOM',
                'GE' => 'GENERAL',
                'MC' => 'MARIN DE COMMERCE',
                'MI' => 'MINES - REGIME MINIER',
                'MO' => 'MINES - REGIME GENERAL',
                'NI'  => 'NAVIGATION INTERIEURE',
                'PM' => 'PECHE',
                'PT'  => 'LA POSTE',
                'RE' => 'RETRAITE ETAB. INDUSTRIEL ETAT',
                'RL'  => 'RETRAITE COLLECT. LOCALE/HOPIT.',
                'RP' => 'PERSONNEL RATP',
                'SN'  => 'S.N.C.F.'
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

        function rolepers( ) {
            return array(
                'DEM' => 'Demandeur du RSA',
                'CJT' => 'Conjoint du demandeur',
                'ENF' => 'Enfant',
                'AUT' => 'Autre personne'
            );
        }

        function sect_acti_emp() {
            return array(
                'A' => 'Agriculture, sylviculture et pêche',
                'B' => 'Industries extractives',
                'C' => 'Industrie manufacturière',
                'D' => 'Production et distribution d\'électricité, de gaz, de vapeur et d\'air conditionné',
                'E' => 'Production et distribution d\'eau ; assainissement, gestion des déchets et dépollution',
                'F' => 'Construction',
                'G' => 'Commerce ; réparation d\'automobiles et de motocycles',
                'H' => 'Transports et entreposage',
                'I' => 'Hébergement et restauration',
                'J' => 'Information et communication',
                'K' => 'Activités financières et d\'assurance',
                'L' => 'Activités immobilières',
                'M' => 'Activités spécialisées, scientifiques et techniques',
                'N' => 'Activités de services administratifs et de soutien',
                'O' => 'Administration publique',
                'P' => 'Enseignement',
                'Q' => 'Santé humaine et action sociale',
                'R' => 'Arts, spectacles et activités récréatives',
                'S' => 'Autres activités de services',
                'T' => 'Activités des ménages en tant qu\'employeurs; activités indifférenciées des ménages en tant que producteurs de biens et services pour usage propre',
                'U' => 'Activités extra-territoriales'
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

        function sitasf(){
            return array(
                'DC' => 'HORS D\'ETAT',
                'NR' => 'ENFANT NON RECONNU',
                'HB' => 'HORS D\'ETAT',
                'OE' => 'ABANDON - SANS JUGEMENT',
                'PA' => 'ABANDON - JUGEMENT SANS PENSION',
                'TP' => 'ABANDON - PENSION FIXEE',
                'AS' => 'SITUATION NON DROIT',
                'AD' => 'ALLOCATION ADOPTION',
                'RS' => 'ASF SUITE A RSA'
            );
       }


        function sitfam(){
            return array(
                'ABA' => 'Disparu (jugement d\'absence)',
                'CEL' => 'Célibataire',
                'DIV' => 'Divorcé(e)',
                'ISO' => 'Isolement après vie maritale ou PACS',
                'MAR' => 'Mariage',
                'PAC' => 'PACS',
                'RPA' => 'Reprise vie commune sur PACS',
                'RVC' => 'Reprise vie maritale',
                'RVM' => 'Reprise mariage',
                'SEF' => 'Séparation de fait',
                'SEL' => 'Séparation légale',
                'VEU' => 'Veuvage',
                'VIM' => 'Vie maritale'
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

        function soutdemarsoc() {
            return array(
                'O' => 'Oui',
                'N' => 'Non',
                'P' => 'Pas de réponse'
            );
        }

       function statut_contrat_insertion() {
            return array(
                '1' => 'Validé',
                '2' => 'En attente',
                '3' => 'A valider',
                '4' => 'Rejeté',
                '5' => 'Afficher tout'
            );
        }


        function statut_orient() {
            return array(
                'Non orienté' => 'Non orienté',
                'Orienté' => 'Orienté',
                'En attente' => 'En attente'
            );
        }


        function statutrdv() {
            return array(
                'P' => 'Prévu',
                'T' => 'Honoré',
                'A' => 'Annulé',
                'R' => 'Reporté'
            );
        }

        function statutrelance() {
            return array(
                'R' => 'Relancé',
                'E' => 'En attente'
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

        function topdemdisproccrealim() {
            return array(
                '1' => 'Demande de dispense',
                '0' => 'Pas de demande de dispense'
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

        function topjugpa() {
            return array(
                '1' => 'Jugement fixant une pension alimentaire',
                '0' => 'Pas de jugement fixant une pension alimentaire'
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

        function type_allocation() {
            return array(
                'AllocationsComptabilisees' => 'Allocations comptabilisées',
                'IndusConstates' => 'Indu constaté',
                'IndusTransferesCG' => 'Indu transféré au CG',
                'RemisesIndus' => 'Remise d\'indu',
                'AnnulationsFaibleMontant' => 'Annulation pour faible montant',
                'AutresAnnulations' => 'Autre annulation'
            );
        }

        function typedero() {
            return array(
                'AGE' => 'Dérogation sur les conditions d\'age',
                'ACT' => 'Dérogation sur les conditions d\'activité',
                'RES' => 'Dérogation sur les conditions de résidence',
                'NAT' => 'Dérogation sur les conditions de nationnalité'
            );
        }

        function typeadr() {
            return array(
                'D' => 'Définitive',
                'P' => 'Provisoire',
                'R' => 'Retour foyer principal'
            );
        }


        function typedtnai() {
            return array(
                'J' => 'Jour inconnu',
                'N' => 'Jour et mois connus',
                'O' => 'Jour et mois inconnus'
            );
        }

        function typenotifpdo() {
            return array(
                'RE' => 'Ressortissant européen',
                'AN' => 'Activité non salariée',
                'AA' => 'Activité non salariée agricole',
                'CN' => 'Création activité non salariée',
                'CA' => 'Création activité non salariée agricole',
                'SN' => 'Stagiaire non rémunéré',
                'AS' => 'Accord stagiaire',
                'RS' => 'Renseignements étudiants',
                'AE' => 'Accord étudiant, élève',
                'DR' => 'Décision de réduction',
                'RN' => 'Radiation pour éléments non déclarés',
                'RD' => 'Radiation pour défaut d\'insertion'
            );
        }

        function typeocclog() {
            return array(
                'ACC' => 'Proprietaire avec charges de remboursement',
                'BAL' => 'Forfait logement a appliquer',
                'HCG' => 'Hébergement collectif a titre gratuit',
                'HCO' => 'Hébergement collectif a titre onereux',
                'HGP' => 'Hébergement à titre gratuit par des particuliers',
                'HOP' => 'Hébergement onereux par des particuliers',
                'HOT' => 'Hotel',
                'LOC' => 'Locataire ou sous locataire',
                'OLI' => 'Occupation logement inconnue',
                'PRO' => 'Proprietaire sans charges de remboursement',
                'SRG' => 'Sans resid. stable avec forfait logement',
                'SRO' => 'Sans resid. stable sans forfait logement'
            );
        }

        function typepdo(){
            return array(
                'N' => 'Non défini',
                'C' => 'PDO de contrôle',
                'M' => 'PDO de maintien',
                'O' => 'PDO d\'ouverture'
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

        function typepar(){
            return array(
                'ADP' => 'Adoption simple',
                'ASC' => 'Ascendant',
                'AUT' => 'Autre',
                'BFI' => 'Gendre ou bru',
                'COL' => 'Coll degré 4',
                'DES' => 'Descendant',
                'FRE' => 'Frère ou soeur',
                'LEG' => 'Légitime',
                'NAT' => 'Naturel',
                'NEV' => 'Neveu ou nièce',
                'ONC' => 'Oncle ou tante',
                'REA' => 'Recueilli en vue adoption',
                'REC' => 'Recueilli'
            );
        }

        function typeparte() {
            return array(
                'CG' => 'Conseil Général', // Code identification partenaire = n° de département sur 3 positions
                'CCAS' => 'Centre Communal d\'Action Sociale', // Code identification partenaire = N° de commune Insee sur 5 positions
                'CIAS' => 'Centre Intercommunal d\'Action Sociale', // Code identification partenaire = N° de commune Insee du siège de l'intercommunalité sur 5 positions
                'PE' => 'Pole Emploi', // Code identification partenaire = a préciser avec PE
                'MDPH' => 'Maison Départementale Pour le Handicap' //  Code identification partenaire = n° de département sur 3 positions
            );
        }


        function typeperstie() {
            return array(
                'P' => 'S\'il s\'agit d\'un tiers personne physique',
                'M' => 'S\'il s\'agit d\'un tiers personne morale'
            );
        }

        function typeres() {
            return array(
                'E' => 'Election de domicile',
                'S' => 'Stable'
            );
        }

        function typeserins() {
            return array(
                '' => 'Hors département',
                'A' => 'Organisme agréé',
                'C' => 'Centre Communal d\'Action Sociale',
                'F' => 'Caisse d\'Allocation Familiale',
                'G' => 'Mutualité Sociale Agricole',
                'I' => 'Internaute',
                'P' => 'Pôle emploi',
                'S' => 'Service Social Départemental',
                'T' => 'Centre Intercommunal d\'Action Sociale'
            );
        }

        function type_totalisation() {
            return array(
                'TotalAllocationsComptabilisees' => 'Total des allocations comptabilisees',
                'TotalIndusConstates' => 'Total des indus constates',
                'TotalIndusTransferesCG' => 'Total des indus transferés au CG',
                'TotalRemisesIndus' => 'Total des remises des indus',
                'TotalAnnulationsIndus' => 'Total des annulations des indus',
                'MontantTotalAcompte' => 'Montant total de l\'acompte'
            );
        }

        function typevoie(){
            return array(
                'ABE' => 'Abbaye',
                'ACH' => 'Ancien chemin',
                'AGL' => 'Agglomération',
                'AIRE' => 'Aire',
                'ALL' => 'Allée',
                'ANSE' => 'Anse',
                'ARC' => 'Arcade',
                'ART' => 'Ancienne route',
                'AUT' => 'Autoroute',
                'AV' => 'Avenue',
                'BAST' => 'Bastion',
                'BCH' => 'Bas chemin',
                'BCLE' => 'Boucle',
                'BD' => 'Boulevard',
                'BEGI' => 'Béguinage',
                'BER' => 'Berge',
                'BOIS' => 'Bois',
                'BRE' => 'Barriere',
                'BRG' => 'Bourg',
                'BSTD' => 'Bastide',
                'BUT' => 'Butte',
                'CALE' => 'Cale',
                'CAMP' => 'Camp',
                'CAR' => 'Carrefour',
                'CARE' => 'Carriere',
                'CARR' => 'Carre',
                'CAU' => 'Carreau',
                'CAV' => 'Cavée',
                'CGNE' => 'Campagne',
                'CHE' => 'Chemin',
                'CHEM' => 'Cheminement',
                'CHEZ' => 'Chez',
                'CHI' => 'Charmille',
                'CHL' => 'Chalet',
                'CHP' => 'Chapelle',
                'CHS' => 'Chaussée',
                'CHT' => 'Château',
                'CHV' => 'Chemin vicinal',
                'CITE' => 'Cité',
                'CLOI' => 'Cloître',
                'CLOS' => 'Clos',
                'COL' => 'Col',
                'COLI' => 'Colline',
                'COR' => 'Corniche',
                'COTE' => 'Côte(au)',
                'COTT' => 'Cottage',
                'COUR' => 'Cour',
                'CPG' => 'Camping',
                'CRS' => 'Cours',
                'CST' => 'Castel',
                'CTR' => 'Contour',
                'CTRE' => 'Centre',
                'DARS' => 'Darse',
                'DEG' => 'Degré',
                'DIG' => 'Digue',
                'DOM' => 'Domaine',
                'DSC' => 'Descente',
                'ECL' => 'Ecluse',
                'EGL' => 'Eglise',
                'EN' => 'Enceinte',
                'ENC' => 'Enclos',
                'ENV' => 'Enclave',
                'ESC' => 'Escalier',
                'ESP' => 'Esplanade',
                'ESPA' => 'Espace',
                'ETNG' => 'Etang',
                'FG' => 'Faubourg',
                'FON' => 'Fontaine',
                'FORM' => 'Forum',
                'FORT' => 'Fort',
                'FOS' => 'Fosse',
                'FOYR' => 'Foyer',
                'FRM' => 'Ferme',
                'GAL' => 'Galerie',
                'GARE' => 'Gare',
                'GARN' => 'Garenne',
                'GBD' => 'Grand boulevard',
                'GDEN' => 'Grand ensemble',
                'GPE' => 'Groupe',
                'GPT' => 'Groupement',
                'GR' => 'Grand(e) rue',
                'GRI' => 'Grille',
                'GRIM' => 'Grimpette',
                'HAM' => 'Hameau',
                'HCH' => 'Haut chemin',
                'HIP' => 'Hippodrome',
                'HLE' => 'Halle',
                'HLM' => 'HLM',
                'ILE' => 'Ile',
                'IMM' => 'Immeuble',
                'IMP' => 'Impasse',
                'JARD' => 'Jardin',
                'JTE' => 'Jetée',
                'LD' => 'Lieu dit',
                'LEVE' => 'Levée',
                'LOT' => 'Lotissement',
                'MAIL' => 'Mail',
                'MAN' => 'Manoir',
                'MAR' => 'Marche',
                'MAS' => 'Mas',
                'MET' => 'Métro',
                'MF' => 'Maison forestiere',
                'MLN' => 'Moulin',
                'MTE' => 'Montée',
                'MUS' => 'Musée',
                'NTE' => 'Nouvelle route',
                'PAE' => 'Petite avenue',
                'PAL' => 'Palais',
                'PARC' => 'Parc',
                'PAS' => 'Passage',
                'PASS' => 'Passe',
                'PAT' => 'Patio',
                'PAV' => 'Pavillon',
                'PCH' => 'Porche - petit chemin',
                'PERI' => 'Périphérique',
                'PIM' => 'Petite impasse',
                'PKG' => 'Parking',
                'PL' => 'Place',
                'PLAG' => 'Plage',
                'PLAN' => 'Plan',
                'PLCI' => 'Placis',
                'PLE' => 'Passerelle',
                'PLN' => 'Plaine',
                'PLT' => 'Plateau(x)',
                'PN' => 'Passage à niveau',
                'PNT' => 'Pointe',
                'PONT' => 'Pont(s)',
                'PORQ' => 'Portique',
                'PORT' => 'Port',
                'POT' => 'Poterne',
                'POUR' => 'Pourtour',
                'PRE' => 'Pré',
                'PROM' => 'Promenade',
                'PRQ' => 'Presqu\'île',
                'PRT' => 'Petite route',
                'PRV' => 'Parvis',
                'PSTY' => 'Peristyle',
                'PTA' => 'Petite allée',
                'PTE' => 'Porte',
                'PTR' => 'Petite rue',
                'QU' => 'Quai',
                'QUA' => 'Quartier',
                'R' => 'Rue',
                'RAC' => 'Raccourci',
                'RAID' => 'Raidillon',
                'REM' => 'Rempart',
                'RES' => 'Résidence',
                'RLE' => 'Ruelle',
                'ROC' => 'Rocade',
                'ROQT' => 'Roquet',
                'RPE' => 'Rampe',
                'RPT' => 'Rond point',
                'RTD' => 'Rotonde',
                'RTE' => 'Route',
                'SEN' => 'Sentier',
                'SQ' => 'Square',
                'STA' => 'Station',
                'STDE' => 'Stade',
                'TOUR' => 'Tour',
                'TPL' => 'Terre plein',
                'TRA' => 'Traverse',
                'TRN' => 'Terrain',
                'TRT' => 'Tertre(s)',
                'TSSE' => 'Terrasse(s)',
                'VAL' => 'Val(lée)(lon)',
                'VCHE' => 'Vieux chemin',
                'VEN' => 'Venelle',
                'VGE' => 'Village',
                'VIA' => 'Via',
                'VLA' => 'Villa',
                'VOI' => 'Voie',
                'VTE' => 'Vieille route',
                'ZA' => 'Zone artisanale',
                'ZAC' => 'Zone d\'aménagement concerte',
                'ZAD' => 'Zone d\'aménagement différé',
                'ZI' => 'Zone industrielle',
                'ZONE' => 'Zone',
                'ZUP' => 'Zone à urbaniser en priorité'
            );
        }

        function typo_aide() {
            return array(
                '1' => 'Insertion sociale',
                '2' => 'Insertion professionnelle',
                '3' => 'Reprise d\'activités'
            );
        }

        function verspa() {
            return array(
                'N' => 'Pas de versement d\'une PA',
                'O' => 'Versement d\'une PA',
                'P' => 'Versement partiel d\'une PA'
            );
        }


/********************TEST pour les Recours
*********************/

        function motifrecours(){
            return array(
                'N' => 'Non admissible',
                'A' => 'Admissible',
                'P' => 'Pièces manquantes'
            );
        }
/*        function decision(){
        return array(
            '' => 'pas de décision',
            '1' => 'Validé',
            '2' => 'Refusé',
            '3' => 'Ajourné'
        );
        }
        function typecommission(){
            return array(
                'V' => 'Commission de validation',
                'R' => 'Commission de recours',
                'E' => 'Commission d\'évaluation'
            );
        }*/
    }
?>
