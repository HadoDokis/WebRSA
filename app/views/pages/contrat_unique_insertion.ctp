<style type="text/css">

.cel1 {
    text-align: center; 
    font-weight: 800;
    background-color:#A8DBEF;
    vertical-align: middle;
}

.cel2 {
    font-weight: 800;
    background-color: #FFFF99;
}

.cel3 {
    font-weight: 800;
    text-align: center;
    border-color:white;
}

.li1 {
    list-style-type: none;
    list-style-position: outside;
}

.li2 {
    list-style-type: square;
    list-style-position: inside;
    margin-left: 30px;
}

.p {
    font-weight: 800;
    font-style: italic;
}

.noborder {
	background-color: transparent;
	border: none;
}

.align1 {
	text-align: center;
}

.align2 {
	text-align: right;
}

.table0 {
	width: 100%;
	border: none;
}

.table0 td {
	border: none;
}


.col1 {
	width: 60%;
}

.col2 {
	width: 40%;
}

.table1 {
	width: 100%;
	border: none;
}

.table1 td {
	border: none;
}


.table2 {
	width: 100%;
}

.table2 td {
	border: none;
}


.celtd1 {
	text-align: left;
	width: 40%;
/* 	background-color: #CCC; */
	vertical-align: middle;
	padding: 5px 0px 5px 0px;
}

.celtd2 {
	text-align: left;
	width: 60%;
	vertical-align: middle;
/* 	background-color: #CEE; */
	padding: 5px 0px 5px 0px;
}

.celtd3 {
	text-align: left;
	width: 40%;
/* 	background-color: #CCC; */
	vertical-align: middle;
	padding: 5px 0px 5px 0px;
}

.celtd4 {
	text-align: right;
	width: 60%;
	vertical-align: middle;
/* 	background-color: #CEE; */
	padding: 5px 0px 5px 0px;
}

.celtd5 {
	text-align: left;
	width: 50%;
	vertical-align: middle;
/* 	background-color: #CEE; */
	padding: 5px 0px 5px 0px;
}

.celtd6 {
	text-align: right;
	width: 50%;
	vertical-align: middle;
/* 	background-color: #CEE; */
	padding: 5px 0px 5px 0px;
}

.celtd7 {
	text-align: left;
	width: 50%;
/* 	background-color: #CCC; */
	vertical-align: middle;
	padding: 5px 0px 5px 0px;
}

.celtd8 {
	text-align: left;
	width: 50%;
	vertical-align: middle;
/* 	background-color: #CEE; */
	padding: 5px 0px 5px 0px;
}

.alert1 {
	font-size: 0.9em;
    font-weight: 500;
    font-style: italic;
}

.background1 {
	background-color: #FDF5E7;
}

.alert2 {
	font-size: 0.9em;
    font-weight: 600;
    font-style: normal;
}

.alert3 {
	font-size: 0.9em;
    font-weight: 600;
    font-style: italic;
}

.background2 {
	background-color: transparent;
}

.fieldset1 {
	border-color: lightsteelblue;
}

.fieldset2 {
	border-color: lightsteelblue;
	margin: 0px 4px 3px 4px;
}

.span1 {
	font-style: italic;
	font-size:0.9em;
}

</style>

<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Contrat Unique d\'Insertion';?>

<div class="treemenu">
    <h2><a href="#">Dossier RSA 01045528093</a></h2>
    <p class="etatDossier"> Droit ouvert et versable</p>
    <ul>
        <li><a href="#">Composition du foyer</a>           
			<ul>
				<li><a href="#">MR JOUIN DAMIEN</a><!-- Début "Partie du sous-menu concernant uniquement le demandeur et son conjoint" -->
					<ul>
						<li><span>Droit</span>
							<ul>
								<li>
									<a href="#">Historique du droit</a>
								</li>
								<li>
									<a href="#">Détails du droit RSA</a>
								</li>
								<li>
									<a href="#">Consultation dossier PDO</a>
								</li>
								<li>
									<a href="#">DSP CAF</a>
								</li>
								<li>
									<a href="#">Orientation</a>     
								</li>
							</ul>
						</li>
                        <li><span>Accompagnement du parcours</span>
							<ul>
								<li>
									<a href="#">Chronologie parcours</a>
								</li>
                                <li>
									<a href="#">Référent du parcours</a>                                            
								</li>
								<li>
									<a href="#">Gestion RDV</a>
								</li>
								<li><span>Contrats</span>
									<ul>
										<li>
											<a href="#">Contrat Engagement</a>                                                    
										</li>
										<li>
											<a href="#">CUI</a>
										</li>
									</ul>
								</li>
								<li><span>Offre d'insertion</span>
									<ul>
										<li>
											<a href="#">Fiche de liaison</a>
										</li>
									</ul>
								</li>
								<li><span>Aides financières</span>
									<ul>
										<li>
											<a href="#">Aides / APRE</a>
										</li>
									</ul>
								</li>
								<li><span>Saisine EP</span>
									<ul>
										<li>
											<a href="#">Fiche de saisine</a>
										</li>
									</ul>
								</li>
								<li><span>Documents scannés</span>
									<ul>
										<li>
											<a href="#">Courriers</a>
										</li>
									</ul>
								</li>
								<li>
									<a href="#">Mémo</a>
								</li>
							</ul>
						</li>
						<li><span>Situation financière</span>
							<ul>
								<li>
									<a href="#">Ressources</a>
								</li>
								<li>
									<a href="#">Liste des Indus</a>
								</li>
							</ul>
						</li>                   
					</ul>
				</li>
			</ul>
        </li>
        <!-- TODO: permissions à partir d'ici et dans les fichiers concernés -->
        <li><span>Informations foyer</span>
            <ul>
				<li>
					<a href="#">Adresses</a>
				</li>
                <li>
					<a href="#">Evènements</a>
				</li>
                <li>
					<a href="#">Modes de contact</a>
				</li>
                <li>
					<a href="#">Avis PCG droit rsa</a>
				</li>
		        <li>
					<a href="#">Informations financières</a>
				</li>
                <li>
					<a href="#">Suivi instruction du dossier</a>
				</li>
				<li>
					<a href="#">Détails du droit RSA</a>
				</li>
			</ul>
        </li>
		<li><a href="#">Informations complémentaires</a></li>        
		<li><a href="#">Synthèse du parcours d&#039;insertion</a></li>        
		<li><span>Préconisation d'orientation</span>
			<ul>
				<li>
					<a href="#">MR JOUIN DAMIEN</a>
				</li>
			</ul>
		</li>
	</ul>
</div>

<div class="with_treemenu">
    <h1>Ajout d'un contrat unique d'insertion</h1><br />

    <form id="testform" method="post" action="#"><fieldset style="display:none;"><input type="hidden" name="_method" value="POST" /></fieldset><div><input type="hidden" name="data[Contratinsertion][id]" value="" id="ContratinsertionId" /><input type="hidden" name="data[Contratinsertion][personne_id]" value="298401" id="ContratinsertionPersonneId" /><input type="hidden" name="data[Contratinsertion][rg_ci]" value="2" id="ContratinsertionRgCi" /></div>
<!--/************************************************************************/ -->

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsOnValue( 'ContratinsertionRgCi', [ 'ContratinsertionTypocontratId' ], 1, true );
    });
</script>
<!--/************************************************************************/ -->
    <script type="text/javascript" src="/mhamzaoui/webrsa/js/dependantselect.js"></script>    <script type="text/javascript">
        document.observe("dom:loaded", function() {
            dependantSelect( 'ContratinsertionReferentId', 'ContratinsertionStructurereferenteId' );
//             dependantSelect( 'ContratinsertionStructurereferenteId', 'OrientstructTypeorientId' );
        });
    </script>
<!--/************************************************************************/ -->
<script type="text/javascript">
    function checkDatesToRefresh() {
        if( ( $F( 'ContratinsertionDdCiMonth' ) ) && ( $F( 'ContratinsertionDdCiYear' ) ) && ( $F( 'ContratinsertionDureeEngag' ) ) ) {
            var correspondances = new Array();
            // FIXME: voir pour les array associatives
             //$duree_engag_cg66
            correspondances[1] = 3;correspondances[2] = 6;correspondances[3] = 9;correspondances[4] = 12;correspondances[5] = 18;correspondances[6] = 24;
            setDateInterval( 'ContratinsertionDdCi', 'ContratinsertionDfCi', correspondances[$F( 'ContratinsertionDureeEngag' )], false );
        }
    }

    document.observe( "dom:loaded", function() {
        Event.observe( $( 'ContratinsertionDdCiDay' ), 'change', function() {
            checkDatesToRefresh();
        } );
        Event.observe( $( 'ContratinsertionDdCiMonth' ), 'change', function() {
            checkDatesToRefresh();
        } );
        Event.observe( $( 'ContratinsertionDdCiYear' ), 'change', function() {
            checkDatesToRefresh();
        } );

        Event.observe( $( 'ContratinsertionDureeEngag' ), 'change', function() {
            checkDatesToRefresh();
        } );

// form, radioName, fieldsetId, value, condition, toggleVisibility
            observeDisableFieldsetOnRadioValue(
                'testform',
                'data[Contratinsertion][forme_ci]',
                $( 'Contratsuite' ),
                'C',
                false,
                true
            );

        observeDisableFieldsOnRadioValue(
            'testform',
            'data[Contratinsertion][raison_ci]',
            [
                'SituationdossierrsaDtclorsaDay',
                'SituationdossierrsaDtclorsaMonth',
                'SituationdossierrsaDtclorsaYear',
                'SituationdossierrsaId',
                'SituationdossierrsaDossierRsaId',
                'ContratinsertionAvisraisonRadiationCiD',
                'ContratinsertionAvisraisonRadiationCiN'
            ],
            'R',
            true
        );

        observeDisableFieldsOnRadioValue(
            'testform',
            'data[Contratinsertion][raison_ci]',
            [
                'ContratinsertionAvisraisonSuspensionCiD',
                'ContratinsertionAvisraisonSuspensionCiN',
                'SuspensiondroitDdsusdrorsaDay',
                'SuspensiondroitDdsusdrorsaMonth',
                'SuspensiondroitDdsusdrorsaYear',
                'SuspensiondroitSituationdossierrsaId'
            ],
            'S',
            true
        );


        new Ajax.Updater('StructurereferenteRef','http://localhost/mhamzaoui/webrsa/contratsinsertion/ajaxstruct', {asynchronous:true, evalScripts:true, requestHeaders:['X-Update', 'StructurereferenteRef']});new Ajax.Updater('ReferentRef','http://localhost/mhamzaoui/webrsa/contratsinsertion/ajaxref', {asynchronous:true, evalScripts:true, requestHeaders:['X-Update', 'ReferentRef']});
    } );
</script>


<script type="text/javascript">
    document.observe( "dom:loaded", function() {
        Event.observe( $( 'ActionCode' ), 'keyup', function() {
            var value = $F( 'ActionCode' );
            if( value.length == 2 ) { // FIXME: in_array
                $$( '#ContratinsertionEngagObject option').each( function ( option ) {
                    if( $( option ).value == value ) {
                        $( option ).selected = 'selected';
                    }
                } );
            }
        } );

        //observeDisableFieldsOnBoolean( 'ContratinsertionActionsPrev', [ 'ContratinsertionObstaRenc' ], '1', false );
        observeDisableFieldsOnRadioValue(
            'testform',
            'data[Contratinsertion][actions_prev]',
            [ 'ContratinsertionObstaRenc' ],
            'N',
            true
        );

        observeDisableFieldsOnValue( 'ContratinsertionNatContTrav', [ 'ContratinsertionDureeCdd' ], 'TCT3', false );

        observeDisableFieldsOnRadioValue(
            'testform',
            'data[Contratinsertion][emp_trouv]',
            [ 'ContratinsertionSectActiEmp', 'ContratinsertionEmpOccupe', 'ContratinsertionDureeHebdoEmp', 'ContratinsertionNatContTrav', 'ContratinsertionDureeCdd' ],
            'O',
            true
        );
    } );
</script>

<strong>Convention entre le conseil général, l'employeur et le salarié</strong><br /><br />
	<div class="">
		<fieldset><legend>TYPE DE CONVENTION</legend>
			<label>La présente convention est passée entre :  </label>
			<input type="radio" name="data[ContratUniqueInsertion][type_convention]" id="ContratUniqueInsertionTypeConvention" value="CG" /><label for="ContratUniqueInsertionTypeSecteur">Le Conseil Général, l'Employeur et le Salarié</label>
			<input type="radio" name="data[ContratUniqueInsertion][type_convention]" id="ContratUniqueInsertionTypeConvention" value="ETAT"/><label for="ContratUniqueInsertionTypeSecteur">L'État, l'Employeur et le Salarié</label>
		</fieldset>
	</div>     
	<div class="">
		<fieldset><legend>TYPE DE SECTEUR</legend>
			<input type="radio" name="data[ContratUniqueInsertion][type_secteur]" id="ContratUniqueInsertionTypeSecteur" value="CIE" /><label for="ContratUniqueInsertionTypeSecteur">Secteur marchand (CIE)</label>
			<input type="radio" name="data[ContratUniqueInsertion][type_secteur]" id="ContratUniqueInsertionTypeSecteur" value="CAE"/><label for="ContratUniqueInsertionTypeSecteur">Secteur non marchand (CAE)</label>
		</fieldset>
	</div>               

<fieldset><legend>L'EMPLOYEUR</legend>
    <table class="table0 noborder">
        <tr>
            <td class="col1">
                <fieldset class="fieldset1">
                    <table class="table1">
                            <tr>
                                <td class="celtd1">Dénomination</td>
                                <td class="celtd2"><input class="" name="#" type="text" maxlength="50" size="43" value="" id="#" /></td>
                            </tr>
                            <tr>
                                <td class="celtd1">N° de voie</td>
                                <td class="celtd2"><input class="" name="#" type="text" maxlength="10" size="6" value="" id="#" /></td>
                            </tr>
                            <tr>
                                <td class="celtd1">Type de voie</td>
                                <td class="celtd2"><select name="#" id="#"  style="width:100%">
                                    <option value=""></option>
                                    <option value="ALL">Allée</option>
                                    <option value="AV">Avenue</option>
                                    <option value="BD">Boulevard</option>
                                    <option value="CAR">Carrefour</option>
                                    <option value="CHE">Chemin</option>
                                    <option value="CLOS">Clos</option>
                                    <option value="COUR">Cour</option>
                                    <option value="CPG">Camping</option>
                                    <option value="CRS">Cours</option>
                                    <option value="CTRE">Centre</option>
                                    <option value="ESC">Escalier</option>
                                    <option value="ESP">Esplanade</option>
                                    <option value="FG">Faubourg</option>
                                    <option value="HAM">Hameau</option>
                                    <option value="HLE">Halle</option>
                                    <option value="HLM">HLM</option>
                                    <option value="IMM">Immeuble</option>
                                    <option value="IMP">Impasse</option>
                                    <option value="LD">Lieu dit</option>
                                    <option value="LOT">Lotissement</option>
                                    <option value="MET">Métro</option>
                                    <option value="PARC">Parc</option>
                                    <option value="PAS">Passage</option>
                                    <option value="PL">Place</option>
                                    <option value="PTE">Porte</option>
                                    <option value="QU">Quai</option>
                                    <option value="QUA">Quartier</option>
                                    <option value="R">Rue</option>
                                    <option value="RES">Résidence</option>
                                    <option value="RLE">Ruelle</option>
                                    <option value="ROC">Rocade</option>
                                    <option value="RPT">Rond point</option>
                                    <option value="RTE">Route</option>
                                    <option value="SEN">Sentier</option>
                                    <option value="SQ">Square</option>
                                    <option value="STA">Station</option>
                                    <option value="STDE">Stade</option>
                                    <option value="TOUR">Tour</option>
                                    <option value="TRA">Traverse</option>
                                    <option value="VAL">Val(lée)(lon)</option>
                                    <option value="VOI">Voie</option>
                                    <option value="ZA">Zone artisanale</option>
                                    <option value="ZI">Zone industrielle</option>
                                    <option value="ZONE">Zone</option>
                                    <option value="ZUP">Zone à urbaniser en priorité</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="celtd1">Nom de voie</td>
                                <td class="celtd2"><input class="" name="#" type="text" maxlength="50" size="43" value="" id="#" /></td>
                            </tr>
                            <tr>
                                <td class="celtd1">Complément adresse</td>
                                <td class="celtd2"><input class="" name="#" type="text" maxlength="50" size="43" value="" id="#" /></td>
                            </tr>
                            <tr>
                                <td class="celtd1">Code postal</td>
                                <td class="celtd2"><input class="" name="#" type="text" maxlength="5" size="10" value="" id="#" /></td>
                            </tr>
                            <tr>
                                <td class="celtd1">Commune</td>
                                <td class="celtd2"><input class="" name="#" type="text" maxlength="50" size="43" value="" id="#" /></td>
                            </tr>
                            <tr>
                                <td class="celtd1">Téléphone</td>
                                <td class="celtd2"><input class="" name="#" type="text" maxlength="10" size="15" value="" id="#" /></td>
                            </tr>
                            <tr>
                                <td class="celtd1">Adresse électronique</td>
                                <td class="celtd2"><input class="" name="#" type="text" maxlength="78" size="43" value="" id="#" /></td>
                            </tr>
                            <tr >
                                <td class="background1" colspan="2"><span class="alert1">Si l'adresse à laquelle les documents administratifs et financiers doivent être envoyés est différente de l'adresse ci-dessus, remplir la partie ci-dessous</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="celtd1">N° de voie</td>
                                <td class="celtd2"><input class="" name="#" type="text" maxlength="10" size="6" value="" id="#" /></td>
                            </tr>
                            <tr>
                                <td class="celtd1">Type de voie</td>
                                <td class="celtd2">
                                    <select name="#" id="#"  style="width:100%">
                                        <option value=""></option>
                                        <option value="ALL">Allée</option>
                                        <option value="AV">Avenue</option>
                                        <option value="BD">Boulevard</option>
                                        <option value="CAR">Carrefour</option>
                                        <option value="CHE">Chemin</option>
                                        <option value="CLOS">Clos</option>
                                        <option value="COUR">Cour</option>
                                        <option value="CPG">Camping</option>
                                        <option value="CRS">Cours</option>
                                        <option value="CTRE">Centre</option>
                                        <option value="ESC">Escalier</option>
                                        <option value="ESP">Esplanade</option>
                                        <option value="FG">Faubourg</option>
                                        <option value="HAM">Hameau</option>
                                        <option value="HLE">Halle</option>
                                        <option value="HLM">HLM</option>
                                        <option value="IMM">Immeuble</option>
                                        <option value="IMP">Impasse</option>
                                        <option value="LD">Lieu dit</option>
                                        <option value="LOT">Lotissement</option>
                                        <option value="MET">Métro</option>
                                        <option value="PARC">Parc</option>
                                        <option value="PAS">Passage</option>
                                        <option value="PL">Place</option>
                                        <option value="PTE">Porte</option>
                                        <option value="QU">Quai</option>
                                        <option value="QUA">Quartier</option>
                                        <option value="R">Rue</option>
                                        <option value="RES">Résidence</option>
                                        <option value="RLE">Ruelle</option>
                                        <option value="ROC">Rocade</option>
                                        <option value="RPT">Rond point</option>
                                        <option value="RTE">Route</option>
                                        <option value="SEN">Sentier</option>
                                        <option value="SQ">Square</option>
                                        <option value="STA">Station</option>
                                        <option value="STDE">Stade</option>
                                        <option value="TOUR">Tour</option>
                                        <option value="TRA">Traverse</option>
                                        <option value="VAL">Val(lée)(lon)</option>
                                        <option value="VOI">Voie</option>
                                        <option value="ZA">Zone artisanale</option>
                                        <option value="ZI">Zone industrielle</option>
                                        <option value="ZONE">Zone</option>
                                        <option value="ZUP">Zone à urbaniser en priorité</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="celtd1">Nom de voie</td>
                                <td class="celtd2"><input class="" name="#" type="text" maxlength="50" size="43" value="" id="#" /></td>
                            </tr>
                            <tr>
                                <td class="celtd1">Complément adresse</td>
                                <td class="celtd2"><input class="" name="#" type="text" maxlength="50" size="43" value="" id="#" /></td>
                            </tr>
                            <tr>
                                <td class="celtd1">Code postal</td>
                                <td class="celtd2"><input class="" name="#" type="text" maxlength="5" size="10" value="" id="#" /></td>
                            </tr>
                            <tr>
                                <td class="celtd1">Commune</td>
                                <td class="celtd2"><input class="" name="#" type="text" maxlength="50" size="43" value="" id="#" /></td>
                            </tr>
                            <tr>
                                <td class="celtd1">Téléphone</td>
                                <td class="celtd2"><input class="" name="#" type="text" maxlength="10" size="15" value="" id="#" /></td>
                            </tr>
                            <tr>
                                <td class="celtd1">Adresse électronique</td>
                                <td class="celtd2"><input class="" name="#" type="text" maxlength="78" size="43" value="" id="#" /></td>
                            </tr>
                    </table>
                </fieldset>
			</td>
			<td class="col2">
				<fieldset class="fieldset1">
					<table class="table2">
						<tr>
							<td class="celtd3">N° SIRET</td>
							<td class="celtd4"><input class="" name="#" type="text" maxlength="14" size="20" value="" id="#" /></td>
						</tr>
						<tr>
							<td class="celtd3">Code NAF2</td>
							<td class="celtd4"><input class="" name="#" type="text" maxlength="5" size="10" value="" id="#" /></td>
						</tr>
						<tr>
							<td class="celtd3">Identifiant convention collective</td>
							<td class="celtd4"><input class="" name="#" type="text" maxlength="4" size="6" value="" id="#" /></td>
						</tr>
						<tr >
							<td class="background1" colspan="2"><span class="alert2">(se référer au site www.travail.gouv.fr/idcc)</span>
							</td>
						</tr>
						<tr>
							<td class="celtd3">Statut de l'employeur</td>
							<td class="celtd4">
								<select name="#" id="#"  style="width:100%">
									<option value=""></option>
									<option value="10">Commune</option>
									<option value="11">EPCI</option>
									<option value="21">Département</option>
									<option value="22">Région</option>
									<option value="50">Association</option>
									<option value="60" title="Autre personne morale chargée de la gestion d'un service public (mutuelle, office public d'HLM)">Autre personne morale chargée...</option>
									<option value="70" title="Établissement public d'enseignement (lycée, collège)">Établissement public d'enseignement...</option>
									<option value="80">Établissement sanitaire public</option>
									<option value="90">Autre établissement public</option>
									<option value="98">Groupe d'employeurs</option>
									<option value="99">Autre entreprise</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="celtd3">Effectif salarié au 31 décembre</td>
							<td class="celtd4"><input class="" name="#" type="text" maxlength="5" size="6" value="" id="#" /></td>
						</tr>
						<tr>
							<td class="celtd3"></td>
							<td class="celtd4"></td>
						</tr>
						<tr>
							<td class="celtd3"></td>
							<td class="celtd4"></td>
						</tr>
						<tr>
							<td class="celtd3"></td>
							<td class="celtd4"></td>
						</tr>
						<tr>
							<td class="celtd3"></td>
							<td class="celtd4"></td>
						</tr>
						<tr >
							<td class="background1" colspan="2"><span class="alert2">Paiement par virement : </span><span class="alert3">Fournir un RIB de l'employeur</span>
							</td>
						</tr>
					</table>
				</fieldset>
				<fieldset class="fieldset1">
					<table class="table2">
						<tr>
							<td colspan="2">Organisme de recouvrement des cotisations sociales</td>
						</tr>
						<tr>
							<td colspan="2">
								<input type="radio" name="organisme" id="org" value="URS" />
									<label for="#">URSSAF</label>
								<input type="radio" name="organisme" id="org" value="MSA" />
									<label for="#">MSA</label>
								<input type="radio" name="organisme" id="org" value="AUT" />
									<label for="#">AUTRE</label>							
							</td>
						</tr>
					</table>
				</fieldset>
				<fieldset class="fieldset1">
					<table class="table2">
						<tr>
							<td colspan="2">L'employeur est-il un atelier et chantier d'insertion ?</td>
						</tr>
						<tr>
							<td colspan="2">
								<input type="radio" name="atelier" id="ate-chant" value="oui" />
									<label for="#">Oui</label>
								<input type="radio" name="atelier" id="ate-chant" value="non" />
									<label for="#">Non</label>
					
							</td>
						</tr>
						<tr><td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="1" class="celtd5">Si oui, précisez le numéro de l'annexe financière à la convention entre l'État et la structure porteuse de l'ACI</td>

							<td colspan="1" class="celtd6">
								<input class="" name="#" type="text" maxlength="3" size="2" value="" id="#" />		
								<input class="" name="#" type="text" maxlength="2" size="2" value="" id="#" />	
								<input class="" name="#" type="text" maxlength="4" size="3" value="" id="#" />				
							</td>
						</tr>
					</table>
				</fieldset>
				<fieldset class="fieldset1">
					<table class="table2">
						<tr>
							<td colspan="2">Assurance chômage : <span class="span1">(cocher la case correspondante)</span></td>
						</tr>
						<tr>
							<td colspan="2">
								<input type="radio" name="assurance-chomage" id="1" value="UNE" />
									<label for="#">l'employeur public ou privé est affilié à l'Unédic</label><br />
								<input type="radio" name="assurance-chomage" id="2" value="RIS" />
									<label for="#">l'employeur public assure lui-même ce risque</label>
							</td>
						</tr>
					</table>
				</fieldset>
			</td>		
		</tr>
	</table>
				<fieldset class="fieldset2">
					<table class="table2 noborder">
						<tr>
							<td>
								<input type="checkbox" name="CIE" value="CIE" />
									<label for="#">Si CIE, je déclare sur l'honneur être à jour des versements de me cotisations et contributions sociales, que cette embauche ne résulte pas du licenciement d'un salarié en CDI, ne pas avoir procédé à un licenciement pour motif économique au cours des 6 derniers mois.</label>
							</td>
						</tr>
					</table>
				</fieldset>
</fieldset>

<fieldset><legend>LE SALARIÉ</legend>
    <table class="table0 noborder">
        <tr>
            <td class="col1"><fieldset class="fieldset1">
				<table class="table1">
						<tr>
							<td class="celtd7">Civilité</td>
							<td class="celtd8">
								<input type="radio" name="civilite" id="#" value="m" />
									<label for="#">M.</label>
								<input type="radio" name="civilite" id="#" value="mme" />
									<label for="#">Mme</label>
								<input type="radio" name="civilite" id="#" value="mle" />
									<label for="#">Mlle</label>							
							</td>
						</tr>
						<tr>
							<td class="celtd7">Nom</td>
							<td class="celtd8"><input class="" name="nom_salarie" type="text"  maxlength="52" size="35" value="" id="#" /></td>
						</tr>
						<tr>
							<td class="celtd7">Pour les femmes mariées,<br /> nom patronymique</td>
							<td class="celtd8"><input class="" name="nom_salarie" type="text"  maxlength="52" size="35" value="" id="#" /></td>
						</tr>
						<tr>
							<td class="celtd7">Prénoms</td>
							<td class="celtd8"><input class="" name="nom_salarie" type="text"  maxlength="52" size="35" value="" id="#" /></td>
						</tr>
						<tr>
							<td class="celtd7">Né(e) le</td>
							<td class="celtd8">
								<select>
									<option value=""></option>
									<option value="01">1</option>
									<option value="02">2</option>
									<option value="03">3</option>
									<option value="04">4</option>
									<option value="05">5</option>
									<option value="06">6</option>
									<option value="07">7</option>
									<option value="08">8</option>
									<option value="09">9</option>
									<option value="10">10</option>
									<option value="11">11</option>
									<option value="12">12</option>
									<option value="13">13</option>
									<option value="14">14</option>
									<option value="15">15</option>
									<option value="16">16</option>
									<option value="17">17</option>
									<option value="18">18</option>
									<option value="19" selected="selected">19</option>
									<option value="20">20</option>
									<option value="21">21</option>
									<option value="22">22</option>
									<option value="23">23</option>
									<option value="24">24</option>
									<option value="25">25</option>
									<option value="26">26</option>
									<option value="27">27</option>
									<option value="28">28</option>
									<option value="29">29</option>
									<option value="30">30</option>
									<option value="31">31</option>
								</select>-
								<select>
									<option value=""></option>
									<option value="01">janvier</option>
									<option value="02">février</option>
									<option value="03">mars</option>
									<option value="04">avril</option>
									<option value="05" selected="selected">mai</option>
									<option value="06">juin</option>
									<option value="07">juillet</option>
									<option value="08">août</option>
									<option value="09">septembre</option>
									<option value="10">octobre</option>
									<option value="11">novembre</option>
									<option value="12">décembre</option>
								</select>-
								<select>
									<option value=""></option>
									<option value="2010" selected="selected">2010</option>
									<option value="2009">2009</option>
									<option value="2008">2008</option>
									<option value="2007">2007</option>
									<option value="2006">2006</option>
									<option value="2005">2005</option>
									<option value="2009">2004</option>
									<option value="2008">2003</option>
									<option value="2007">2002</option>
									<option value="2006">2001</option>
									<option value="2005">2000</option>
									<option value="2009">1999</option>
									<option value="2008">1998</option>
									<option value="2007">1997</option>
									<option value="2006">1996</option>
									<option value="2005">1995</option>
									<option value="2005">1994</option>
									<option value="2009">1993</option>
									<option value="2008">1992</option>
									<option value="2007">1991</option>
									<option value="2006">1990</option>
									<option value="2005">1989</option>
									<option value="2006">1988</option>
									<option value="2005">1987</option>
									<option value="2009">1986</option>
									<option value="2008">1985</option>
									<option value="2007">1984</option>
									<option value="2006">1983</option>
									<option value="2005">1982</option>
									<option value="2009">1981</option>
									<option value="2008">1980</option>
									<option value="2007">1979</option>
									<option value="2006">1978</option>
									<option value="2005">1977</option>
									<option value="2005">1976</option>
									<option value="2009">1975</option>
									<option value="2008">1974</option>
									<option value="2007">1973</option>
									<option value="2006">1972</option>
									<option value="2005">1971</option>
									<option value="2005">1970</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="celtd7">Lieu de naissance</td>
							<td class="celtd8"><input class="" name="nom_salarie" type="text"  maxlength="52" size="35" value="" id="#" /></td>
						</tr>
						<tr>
							<td class="celtd7">N° de voie</td>
							<td class="celtd8"><input class="" name="#" type="text" maxlength="10" size="6" value="" id="#" /></td>
						</tr>
						<tr>
							<td class="celtd7">Type de voie</td>
							<td class="celtd8"><select name="#" id="#"  style="width:100%">
								<option value=""></option>
								<option value="ALL">Allée</option>
								<option value="AV">Avenue</option>
								<option value="BD">Boulevard</option>
								<option value="CAR">Carrefour</option>
								<option value="CHE">Chemin</option>
								<option value="CLOS">Clos</option>
								<option value="COUR">Cour</option>
								<option value="CPG">Camping</option>
								<option value="CRS">Cours</option>
								<option value="CTRE">Centre</option>
								<option value="ESC">Escalier</option>
								<option value="ESP">Esplanade</option>
								<option value="FG">Faubourg</option>
								<option value="HAM">Hameau</option>
								<option value="HLE">Halle</option>
								<option value="HLM">HLM</option>
								<option value="IMM">Immeuble</option>
								<option value="IMP">Impasse</option>
								<option value="LD">Lieu dit</option>
								<option value="LOT">Lotissement</option>
								<option value="MET">Métro</option>
								<option value="PARC">Parc</option>
								<option value="PAS">Passage</option>
								<option value="PL">Place</option>
								<option value="PTE">Porte</option>
								<option value="QU">Quai</option>
								<option value="QUA">Quartier</option>
								<option value="R">Rue</option>
								<option value="RES">Résidence</option>
								<option value="RLE">Ruelle</option>
								<option value="ROC">Rocade</option>
								<option value="RPT">Rond point</option>
								<option value="RTE">Route</option>
								<option value="SEN">Sentier</option>
								<option value="SQ">Square</option>
								<option value="STA">Station</option>
								<option value="STDE">Stade</option>
								<option value="TOUR">Tour</option>
								<option value="TRA">Traverse</option>
								<option value="VAL">Val(lée)(lon)</option>
								<option value="VOI">Voie</option>
								<option value="ZA">Zone artisanale</option>
								<option value="ZI">Zone industrielle</option>
								<option value="ZONE">Zone</option>
								<option value="ZUP">Zone à urbaniser en priorité</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="celtd7">Nom de voie</td>
							<td class="celtd8"><input class="" name="#" type="text" maxlength="50" size="35" value="" id="#" /></td>
						</tr>
						<tr>
							<td class="celtd7">Complément adresse</td>
							<td class="celtd8"><input class="" name="#" type="text" maxlength="50" size="35" value="" id="#" /></td>
						</tr>
						<tr>
							<td class="celtd7">Code postal</td>
							<td class="celtd8"><input class="" name="#" type="text" maxlength="5" size="10" value="" id="#" /></td>
						</tr>
						<tr>
							<td class="celtd7">Commune</td>
							<td class="celtd8"><input class="" name="#" type="text" maxlength="50" size="35" value="" id="#" /></td>
						</tr>
						<tr>
							<td class="celtd7">Téléphone</td>
							<td class="celtd8"><input class="" name="#" type="text" maxlength="10" size="15" value="" id="#" /></td>
						</tr>
						<tr>
							<td class="celtd7">Adresse électronique</td>
							<td class="celtd8"><input class="" name="#" type="text" maxlength="78" size="35" value="" id="#" /></td>
						</tr>
				</table></fieldset>
			</td>		
<td class="col2">
				<fieldset class="fieldset1">
					<table class="table2">
						<tr>
							<td class="celtd7">NIR</td>
							<td class="celtd8"><input class="" name="#" type="text" maxlength="10" size="15" value="" id="#" /></td>
						</tr>
						<tr>
							<td class="celtd7">Dépt.</td>
							<td class="celtd8"><input class="" name="#" type="text" maxlength="10" size="15" value="" id="#" /></td>
						</tr>
						<tr>
							<td>Nationalité</td>

							<td>
								<input type="radio" name="nationalite" id="1" value="FR" />
									<label for="#">France</label><br />
								<input type="radio" name="nationalite" id="2" value="UE" />
									<label for="#">Union européenne</label><br />
								<input type="radio" name="nationalite" id="3" value="HORSUE" />
									<label for="#">Hors Union européenne</label>
							</td>
						<tr>
							<td class="celtd3">Si bénéficiaire RSA,<br /> n° allocataire</td>
							<td class="celtd4"><input class="" name="#" type="text" maxlength="13" size="15" value="" id="#" /></td>
						</tr>
						<tr>
							<td>Relevé de</td>
							<td>
								<input type="radio" name="releve" id="1" value="CAF" />
									<label for="#">CAF</label>
								<input type="radio" name="releve" id="2" value="MSA" />
									<label for="#">MSA</label>
							</td>
						<tr>
					</table>
				</fieldset>
		</tr>
	</table>
				
</fieldset>

<fieldset class="cnilci background1">
    <p class="align1">
        <em>En cas de non exécution de la présente convention, les sommes déjà versées font l'objet d'un ordre de reversement.<br />
			L'employeur et le salarié déclarent avoir pris connaissance des conditions générales jointes.</em>
    </p>
</fieldset>
    <div class="submit">
        <input type="submit" value="Enregistrer" />        <input type="submit" name="Cancel" value="Annuler" />    </div>
    </form></div>

<div class="clearer"><hr /></div>

