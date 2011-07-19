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
	echo $xform->create( null, array( 'id' => 'EpAddEditForm' ) );

	if (isset($this->data['Ep']['id']))
		echo $form->input('Ep.id', array('type'=>'hidden'));

	if( Configure::read( 'Cg.departement' ) == 93 ){
		echo $default->subform(
			array(
	// 			'Ep.identifiant' => array('required' => true),
				'Ep.name' => array('required' => true),
				'Ep.adressemail',
				'Ep.regroupementep_id' => array('required' => true, 'type' => 'select'),
			),
			array(
				'options' => $options
			)
		);
	}
	else{
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


    $i = 0;
    if ( isset( $this->validationErrors['Ep']['Membreep.Membreep'] ) && !empty( $this->validationErrors['Ep']['Membreep.Membreep'] ) ) {
		echo "<p class='error'>".$this->validationErrors['Ep']['Membreep.Membreep']."</p>";
    }
    foreach( $fonctionsParticipants as $fonction ) {
        $i++;
        $params = array(
            'required' => true, 'fieldset' => false, 'domain' => 'ep', 'div' => false, 'label' => false, 'type' => 'select', 'multiple' => 'checkbox', 'empty' => false,  'id' => 'listeParticipants', 'options' => Set::combine( $fonction, 'Membreep.{n}.id', 'Membreep.{n}.name' )  );
        if( $i != 1 ) {
            $params['hiddeninput'] = false;
        }
        echo "<fieldset><legend>{$fonction['Fonctionmembreep']['name']}</legend>";
        echo $html->tag(
            'div',
            $default->subform(
                array(
                    'Membreep.Membreep' => $params
                ),
                array(
                    'options' => $options
                )
            ),
            array(
                'id' => 'listeParticipants'
            )
        );

        echo '</fieldset>';
    }



/*	if ($this->action == 'edit') {

	echo "<fieldset><legend>Participants</legend>";

		foreach( $listeFonctionsMembres as $fonction_id => $fonction ) {
			echo $html->tag(
				'p',
				$fonction.' :'
			);
			$listeMembre = array();
			if ( isset( $this->data['Membreep'] ) ) {
				foreach( $this->data['Membreep'] as $membre ) {
					if ( $membre['fonctionmembreep_id'] == $fonction_id ) {
						$listeMembre[] = $membre;
					}
				}
			}
			elseif ( isset( $this->data[0]['Membreep'] ) ) {
				foreach( $this->data as $membre ) {
					if ( $membre['Membreep']['fonctionmembreep_id'] == $fonction_id ) {
						$listeMembre[] = $membre['Membreep'];
					}
				}
			}
			if ( !empty( $listeMembre ) ) {
				echo "<table>";
					foreach ( $listeMembre as $participant ) {
						echo $html->tag(
							'tr',
							$html->tag(
								'td',
								implode( ' ', array( $participant['qual'], $participant['nom'], $participant['prenom'] ) )
							).
							$html->tag(
								'td',
								$xhtml->deleteLink( 'Supprimer', array( 'controller' => 'eps', 'action'=> 'deleteparticipant', $ep_id, $participant['id'] ) )
							)
						);
					}
				echo "</table>";
			}

			echo $xhtml->addLink( 'Ajouter', array( 'controller' => 'eps', 'action'=> 'addparticipant', $ep_id, $fonction_id ) );
		}

	echo "</fieldset>";

	}
*/
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
