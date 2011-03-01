<h1>
<?php
	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

	if( $this->action == 'add' ) {
		echo $this->pageTitle = 'Ajout d\'une équipe pluridisciplinaire';
	}
	else {
		echo $this->pageTitle = 'Modification d\'une équipe pluridisciplinaire';
	}
?>
</h1>

<?php
	$EpDepartement = Configure::read( 'Cg.departement' );
	if( empty( $EpDepartement ) || !in_array( $EpDepartement, array( 58, 66, 93 ) ) ) {
		echo $xhtml->tag( 'p', 'Veuillez contacter votre adminitrateur afin qu\'il ajoute le paramètre de configuration Cg.departement dans le fichier webrsa.inc', array( 'class' => 'error' ) );
	}

	echo $xform->create( null, array( 'id' => 'EpAddEditForm' ) );

	if (isset($this->data['Ep']['id']))
		echo $form->input('Ep.id', array('type'=>'hidden'));

	echo $default->subform(
		array(
// 			'Ep.identifiant' => array('required' => true),
			'Ep.name' => array('required' => true),
			'Ep.regroupementep_id' => array('required' => true, 'type' => 'select'),
		),
		array(
			'options' => $options
		)
	);

	// Le CG 93 ne souhaite pas voir ces choix: pour eux, tout se décide
	// au niveau cg, et toutes les eps traitent potentiellement de tous
	// les thèmes
	if( Configure::read( 'Cg.departement' ) == 93 ) {
		echo $default->subform(
			array(
				'Ep.saisineepreorientsr93' => array( 'type' => 'hidden', 'value' => 'cg' ),
				'Ep.nonrespectsanctionep93' => array( 'type' => 'hidden', 'value' => 'cg' ),
				'Ep.radiepoleemploiep93' => array( 'type' => 'hidden', 'value' => 'cg' ),
			)
		);
	}
	// On laisse la possibilité de choisir comme avant pour le CG 58
	elseif( Configure::read( 'Cg.departement' ) == 58 ) {
		echo $default->subform(
				array(
					'Ep.nonorientationpro58' => array( 'type' => 'hidden', 'value' => 'ep' ),
					'Ep.regressionorientationep58' => array( 'type' => 'hidden', 'value' => 'ep' ),
					'Ep.radiepoleemploiep58' => array( 'type' => 'hidden', 'value' => 'ep' ),
				)
			)
		);
	}
	// Le choix est également possible pour le CG 66
	elseif( Configure::read( 'Cg.departement' ) == 66 ) {
		echo $xhtml->tag(
			'fieldset',
			$xhtml->tag(
				'legend',
				'Thématiques 66'
			).
			$default->subform(
				array(
					'Ep.saisineepbilanparcours66' => array( 'required' => true ),
					'Ep.saisineepdpdo66' => array( 'required' => true ),
					'Ep.defautinsertionep66' => array( 'required' => true ),
				),
				array(
					'options' => $options
				)
			),
			array(
				'label'=>'Thématiques 66'
			)
		);
	}

	echo $html->tag(
		'div',
		$default->subform(
			array(
				'Zonegeographique.Zonegeographique' => array( 'required' => true, 'multiple' => 'checkbox', 'empty' => false, 'domain' => 'ep', 'id' => 'listeZonesgeographiques' )
			),
			array(
				'options' => $options
			)
		),
		array(
			'id' => 'listeZonesgeographiques'
		)
	);

	echo $form->button('Tout cocher', array('onclick' => "GereChkbox('listeZonesgeographiques','cocher');"));

	echo $form->button('Tout décocher', array('onclick' => "GereChkbox('listeZonesgeographiques','decocher');"));

	if ($this->action == 'edit') {
		echo "<fieldset><legend>Participants</legend>";
			foreach($listeFonctionsMembres as $fonction_id => $fonction) {
				echo $html->tag(
					'p',
					$fonction.' :'
				);
				$listeMembre = array();
				foreach($this->data['Membreep'] as $membre) {
					if ($membre['fonctionmembreep_id']==$fonction_id)
						$listeMembre[] = $membre;
				}
				if (!empty($listeMembre)) {
					echo "<table>";
						foreach ($listeMembre as $participant) {
							echo $html->tag(
								'tr',
								$html->tag(
									'td',
									implode( ' ', array( $participant['qual'], $participant['nom'], $participant['prenom'] ) )
								).
								$html->tag(
									'td',
									$xhtml->deleteLink('Supprimer', array('controller'=>'eps', 'action'=> 'deleteparticipant', $ep_id, $participant['id']))
								)
							);
						}
					echo "</table>";
				}

				echo $xhtml->addLink('Ajouter', array('controller'=>'eps', 'action'=> 'addparticipant', $ep_id, $fonction_id));
			}
		echo "</fieldset>";
	}

	echo $xform->end( __( 'Save', true ) );

    echo $default->button(
		'back',
        array(
        	'controller' => 'eps',
        	'action'     => 'index'
        ),
        array(
        	'id' => 'Back'
        )
	);
?>

<script type="text/javascript">
	function GereChkbox(conteneur, a_faire) {
		$( conteneur ).getElementsBySelector( 'input[type="checkbox"]' ).each( function( input ) {
			if (a_faire=='cocher') blnEtat = true;
			else if (a_faire=='decocher') blnEtat = false;

			$(input).checked = blnEtat;
		} );
	}
</script>
