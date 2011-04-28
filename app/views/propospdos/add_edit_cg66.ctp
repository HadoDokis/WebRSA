<?php
    if( Configure::read( 'debug' ) > 0 ) {
        echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
        echo $xhtml->css( array( 'fileuploader' ), 'stylesheet', array( 'media' => 'all' ), false );
        echo $javascript->link( 'fileuploader.js' );
    }

	$this->pageTitle = 'Validation PDO';
	$domain = 'pdo';

	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );

?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		//Utilisé en cas de motif de PDO non admissible
//         observeDisableFieldsOnValue( 'PropopdoMotifpdo', [ 'PropopdoNonadmis' ], 'N', false );

		observeDisableFieldsetOnCheckbox( 'PropopdoDecision', $( 'PropopdoDecisionpdoId' ).up( 'fieldset' ), false );
//        observeDisableFieldsetOnCheckbox( 'PropopdoSuivi', $( 'PropopdoDaterevisionDay' ).up( 'fieldset' ), false );
//         observeDisableFieldsetOnCheckbox( 'PropopdoAutres', $( 'PropopdoCommentairepdo' ).up( 'fieldset' ), false );
		observeDisableFieldsetOnCheckbox( 'PropopdoIsvalidation', $( 'PropopdoDatevalidationdecisionDay' ).up( 'fieldset' ), false );
		observeDisableFieldsetOnCheckbox( 'PropopdoIsdecisionop', $( 'PropopdoObservationop' ).up( 'fieldset' ), false );


//         observeDisableFieldsetOnCheckbox( 'PropopdoMotifpdo', $( 'PropopdoNonadmis' ).up( 'fieldset' ), false );
		/*observeDisableFieldsetOnRadioValue(
			'propopdoform',
			'data[Propopdo][motifpdo]',
			$( 'nonadmis' ),
			'N',
			false,
			true
		);*/
	});
</script>

<?php
	if( $this->action == 'add' ) {
		$this->pageTitle = 'Ajout d\'un dossier';
	}
	else {
		$this->pageTitle = 'Édition du dossier';
	}
?>

<script type="text/javascript">

	function checkDatesToRefresh() {
		if( ( $F( 'PropopdoDaterevisionMonth' ) ) && ( $F( 'PropopdoDaterevisionYear' ) ) ) {
			setDateInterval2( 'PropopdoDaterevision', 'PropopdoDateecheance', 4, false );

		}
	}

	document.observe("dom:loaded", function() {
		setDateInterval2( 'PropopdoDaterevision', 'PropopdoDateecheance', 4, false );

		Event.observe( $( 'PropopdoDaterevisionMonth' ), 'change', function() {
			checkDatesToRefresh();
		} );
		Event.observe( $( 'PropopdoDaterevisionYear' ), 'change', function() {
			checkDatesToRefresh();
		} );

		observeDisableFieldsetOnRadioValue(
			'propopdoform',
			'data[Propopdo][iscomplet]',
			$( 'FicheCalcul' ),
			'COM',
			false,
			true
		);
	});


</script>

<div class="with_treemenu">
	<h1><?php echo $this->pageTitle;?></h1>

	<?php

		echo $xform->create( 'Propopdo', array( 'id' => 'propopdoform' ) );
		if( $this->action == 'add' ) {
		}
		else {
			echo '<div>';
			echo $xform->input( 'Propopdo.id', array( 'type' => 'hidden' ) );
			echo '</div>';
		}
		echo '<div>';
		echo $xform->input( 'Propopdo.personne_id', array( 'type' => 'hidden', 'value' => $personne_id ) );

		echo '</div>';
	?>

	<div class="aere">

	<fieldset>

		<?php
			echo $default->subform(
				array(
					'Propopdo.etatdossierpdo' => array( 'type' => 'hidden' )
				),
				array(
					'domain' => $domain,
					'options' => $options
				)
			);
		?>

		<!--<legend>Arrivée de la PDO</legend> -->
		<?php
			echo $default->subform(
				array(
					'Propopdo.typepdo_id' => array( 'label' => ( __d( 'propopdo', 'Propopdo.typepdo_id', true ) ), 'type' => 'select', 'options' => $typepdo, 'empty' => true ),
					'Propopdo.datereceptionpdo' => array( 'label' =>  ( __( 'Date de réception du dossier', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => false ),
// 					'Propopdo.choixpdo' => array( 'label' =>  ( __( 'Choix', true ) ), 'type' => 'radio', 'options' => $options['choixpdo'], 'empty' => true ),
					'Propopdo.originepdo_id' => array( 'label' =>  ( __( 'Origine', true ) ), 'type' => 'select', 'options' => $originepdo, 'empty' => true ),
					'Propopdo.orgpayeur' => array( 'label' =>  __d( 'propopdo', 'Propopdo.orgpayeur', true ), 'type' => 'select', 'options' => $orgpayeur, 'empty' => true ),
					'Propopdo.serviceinstructeur_id' => array( 'label' =>  ( __d( 'propopdo', 'Propopdo.serviceinstructeur_id', true ) ), 'type' => 'select', 'options' => $serviceinstructeur, 'empty' => true )
				),
				array(
					'domain' => $domain,
					'options' => $options
				)
			);

			//echo $ajax->observeField( 'PropopdoTypepdoId', array( 'update' => 'Etatpdo', 'url' => Router::url( array( 'action' => 'ajaxetatpdo' ), true ) ) );
		?>
	</fieldset>
<fieldset>
    <legend><?php echo required( $default2->label( 'Propopdo.haspiece' ) );?></legend>

    <?php echo $form->input( 'Propopdo.haspiece', array( 'type' => 'radio', 'options' => $options['haspiece'], 'legend' => false, 'fieldset' => false ) );?>
    <fieldset id="filecontainer-piece" class="noborder invisible">
        <?php
            echo $fileuploader->create(
                $fichiers,
                Router::url( array( 'action' => 'ajaxfileupload' ), true )
            );
        ?>
    </fieldset>
</fieldset>

<script type="text/javascript">
    document.observe( "dom:loaded", function() {
        observeDisableFieldsetOnRadioValue(
            'propopdoform',
            'data[Propopdo][haspiece]',
            $( 'filecontainer-piece' ),
            '1',
            false,
            true
        );
    } );
</script>


	<fieldset>
		<!--<legend>Prise de décision</legend>-->
		<?php
			echo $default->subform(
				array(
					'Propopdo.user_id' => array( 'label' =>  ( __d( 'propopdo', 'Propopdo.user_id', true ) ), 'type' => 'select', 'options' => $gestionnaire )
				),
				array(
					'domain' => $domain,
					'options' => $options
				)
			);
		?>
		<?php
			//echo $ajax->observeField( 'PropopdoUserId', array( 'update' => 'Etatpdo', 'url' => Router::url( array( 'action' => 'ajaxetatpdo' ), true ) ) );
		?>
	</fieldset>

	<fieldset>
		<!--<legend>Prise de décision</legend>-->
		<table class="noborder" id="infosPdo">
			<tr>
				<td class="mediumSize noborder">
					<?php
						echo $xform->input( 'Situationpdo.Situationpdo', array( 'type' => 'select', 'label' => 'Motif de la décision', 'multiple' => 'checkbox' , 'options' => $situationlist ) );
						/*for($i=0;$i<count($situationlist);$i++)
							echo $ajax->observeField( 'SituationpdoSituationpdo'.$i, array( 'update' => 'Etatpdo', 'url' => Router::url( array( 'action' => 'ajaxetatpdo' ), true ) ) );*/
					?>
				</td>
				<td class="mediumSize noborder">
					<?php
						echo $xform->input( 'Statutpdo.Statutpdo', array( 'type' => 'select', 'label' => 'Statut de la personne', 'multiple' => 'checkbox' , 'options' => $statutlist ) );
						/*for($i=0;$i<count($statutlist);$i++)
							echo $ajax->observeField( 'StatutpdoStatutpdo'.$i, array( 'update' => 'Etatpdo', 'url' => Router::url( array( 'action' => 'ajaxetatpdo' ), true ) ) );*/
					?>
				</td>
			</tr>
		</table>
		<?php

			echo $xhtml->tag(
				'p',
				'Catégories : '
			);

			echo $default->subform(
				array(
					'Propopdo.categoriegeneral' => array( 'label' => __d( 'propopdo', 'Propopdo.categoriegeneral', true ), 'type' => 'select', 'empty' => true, 'options' => $categoriegeneral ),
					'Propopdo.categoriedetail' => array( 'label' => __d( 'propopdo', 'Propopdo.categoriedetail', true ), 'type' => 'select', 'empty' => true, 'options' => $categoriedetail ),
					'Propopdo.iscomplet' => array( 'legend' => false, 'type' => 'radio', 'options' => $options['iscomplet'] )
				),
				array(
					'domain' => $domain,
					'options' => $options
				)
			);

			/// FIXME: à corriger car pas bon
			//echo $ajax->observeField( 'PropopdoIscompletCOM', array( 'update' => 'Etatpdo', 'url' => Router::url( array( 'action' => 'ajaxetatpdo' ), true ) ) );
			//echo $ajax->observeField( 'PropopdoIscompletINC', array( 'update' => 'Etatpdo', 'url' => Router::url( array( 'action' => 'ajaxetatpdo' ), true ) ) );

		?>
	</fieldset>

	<?php
		if ($this->action=='edit') {

			echo $html->tag(
				'fieldset',
				$html->tag(
					'legend',
					'Traitements'
				).
				$default2->index(
					$traitementspdos,
					array(
						'Descriptionpdo.name',
						'Traitementpdo.datereception',
						'Traitementpdo.datedepart',
						'Traitementtypepdo.name',
						'Traitementpdo.hascourrier' => array( 'label' => 'Courrier ?', 'type' => 'boolean' ),
						'Traitementpdo.hasrevenu' => array( 'label' => 'Fiche de calcul ?', 'type' => 'boolean' ),
						'Traitementpdo.haspiecejointe' => array( 'label' => 'Pièce jointe ?', 'type' => 'boolean' ),
						'Traitementpdo.hasficheanalyse' => array( 'label' => 'Fiche d\'analyse ?', 'type' => 'boolean' ),
					),
					array(
						'actions' => array(
							'Traitementspdos::view',
// 							'Traitementspdos::edit' => array( 'disabled' => ( '\'#Traitementpdo.clos#\' != 0' ) ),
							'Traitementspdos::clore' => array( 'disabled' => ( '\'#Traitementpdo.clos#\' != 0' ) ),
							'Traitementspdos::delete' => array( 'disabled' => '( "'.$permissions->check( 'traitementspdos', 'delete' ).'" != "1" )' )
						),
						'add' => array( 'Traitementpdo.add' => array( 'controller'=>'traitementspdos', 'action'=>'add', $pdo_id ) ),
						'options' => $options
					)
				)
			);
			$block = true;
			foreach( $decisionspropospdos as $i => $decision ){
				if( isset( $decision ) ){
					$block = false;
				}
				else{
					$block = true;
				}
			}
// debug($decisionspropospdos);
			echo $html->tag(
				'fieldset',
				$html->tag(
					'legend',
					'Propositions de décision'
				).
				$default2->index(
					$decisionspropospdos,
					array(
						'Decisionpropopdo.datedecisionpdo',
						'Decisionpdo.libelle',
						'Decisionpropopdo.avistechnique' => array( 'type' => 'boolean' ),
						'Decisionpropopdo.dateavistechnique',
						'Decisionpropopdo.validationdecision' => array( 'type' => 'boolean' ),
						'Decisionpropopdo.datevalidationdecision',
					),
					array(
						'actions' => array(
							'Decisionspropospdos::view',
							'Decisionspropospdos::edit' => array( 'disabled' => ( '\'#Decisionpropopdo.id#\' != '.$lastDecisionId.' || \''.$etatdossierpdo.'\' == \'attpj\' || \''.$etatdossierpdo.'\' == \'dossiertraite\' || \'#Decisionpropopdo.validationdecision#\' != NULL' ) ),
							'Decisionspropospdos::print' => array( 'label' => 'Imprimer', 'url' => array( 'controller' => 'decisionspropospdos', 'action'=>'decisionproposition' ), 'disabled' => ( '\'#Decisionpropopdo.validationdecision#\' == NULL || ( "'.$permissions->check( 'decisionspropospdos', 'print' ).'" != "1" )' ) ),
							'Decisionspropospdos::delete' => array( 'disabled' => '( "'.$permissions->check( 'decisionspropospdos', 'delete' ).'" != "1" )' )
						),
						'add' => array( 'Decisionpropopdo.add' => array( 'controller'=>'decisionspropospdos', 'action'=>'add', $pdo_id, 'disabled' => $ajoutDecision ) ),
						'options' => $options
					)
				)
			);

		}
	?>

	<fieldset id="Etatpdo" class="invisible"></fieldset>

	</div>
	<div class="submit">
		<?php echo $form->submit( 'Enregistrer', array( 'div' => false ) ); ?>
		<?php echo $form->button( 'Retour', array( 'type' => 'button', 'onclick'=>"location.replace('".Router::url( '/propospdos/index/'.$personne_id, true )."')" ) ); ?>
	</div>

	<?php echo $xform->end();?>
</div>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		[ $('PropopdoTypepdoId'), $('PropopdoUserId'), $('PropopdoIscompletCOM'), $('PropopdoIscompletINC') ].each(function(field) {
			field.observe('change', function(element, value) {
				fieldUpdater();
			});
		});

		fieldUpdater();
	});

	function fieldUpdater() {
		new Ajax.Updater(
			'Etatpdo',
			'<?php echo Router::url( array( "action" => "ajaxetatpdo" ), true ) ?>',
			{
				asynchronous:true,
				evalScripts:true,
				parameters:
				{
					'typepdo_id' : $F('PropopdoTypepdoId'),
					'user_id' : $F('PropopdoUserId'),
					'complet' : $F('PropopdoIscompletCOM'),
					'incomplet' : $F('PropopdoIscompletINC'),
					'propopdo_id' : <?php if (isset($this->data['Propopdo']['id'])) { ?> $F('PropopdoId') <?php } else { echo 0; } ?>
				},
				requestHeaders:['X-Update', 'Etatpdo']
			}
		);
	}
</script>

<div class="clearer"><hr /></div>