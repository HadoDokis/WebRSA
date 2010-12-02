<?php
    echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

    echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>
<div class="with_treemenu">
    <?php
        echo $xhtml->tag(
            'h1',
            $this->pageTitle = __d( 'traitementpdo', "Traitementspdos::{$this->action}", true )
        );

    ?>
    <?php
        echo $xform->create( 'Traitementpdo', array( 'id' => 'traitementpdoform' ) );
        if( Set::check( $this->data, 'Traitementpdo.id' ) ){
            echo $xform->input( 'Traitementpdo.id', array( 'type' => 'hidden' ) );
        }

//         echo $default->view(
//             $propopdo,
//             array(
//                 'Propopdo.user_id'
//             ),
//             array(
//                 'widget' => 'table',
//                 'id' => 'dossierInfosOrganisme',
//                 'options' => $gestionnaire
//             )
//         );
        echo $default->subform(
            array(
                'Traitementpdo.propopdo_id' => array( 'type' => 'hidden', 'value' => $propopdo_id ),
                'Traitementpdo.descriptionpdo_id',
                'Traitementpdo.traitementtypepdo_id'
            ),
            array(
                'options' => $options
            )
        );
        
        echo $xhtml->tag(
        	'fieldset',
        	$default->subform(
		        array(
		            'Traitementpdo.datereception' => array( 'required' => true, 'empty' => true, 'maxYear' => date('Y') + 2, 'minYear' => date('Y' ) -2 )
		        ),
		        array(
		            'options' => $options
		        )
			),
			array(
				'id'=>'dateReception',
				'class'=>'noborder invisible'
			)
        );
        
        echo $xhtml->tag(
        	'fieldset',
        	$default->subform(
		        array(
		            'Traitementpdo.datedepart' => array( 'required' => true, 'empty' => true, 'maxYear' => date('Y') + 2, 'minYear' => date('Y' ) -2 )
		        ),
		        array(
		            'options' => $options
		        )
			),
			array(
				'id'=>'dateDepart',
				'class'=>'noborder invisible'
			)
        );
        
        echo $xhtml->tag(
        	'fieldset',
        	$default->subform(
		        array(
		            'Traitementpdo.dateecheance' => array( 'required' => true, 'empty' => true, 'maxYear' => date('Y') + 2, 'minYear' => date('Y' ) -2 )
		        ),
		        array(
		            'options' => $options
		        )
			),
			array(
				'class'=>'noborder invisible'
			)
        );
        
        echo $xhtml->tag(
        	'fieldset',
        	$default->subform(
		        array(
		            'Traitementpdo.daterevision' => array( 'required' => true, 'empty' => true, 'maxYear' => date('Y') + 2, 'minYear' => date('Y' ) -2 )
		        ),
		        array(
		            'options' => $options
		        )
			),
			array(
				'class'=>'noborder invisible'
			)
        );
        
        echo $default->subform(
            array(
                'Traitementpdo.personne_id' => array( 'empty' => false, 'type' => 'select', 'options' => $listepersonnes ),
                'Traitementpdo.hascourrier' => array( 'type' => 'radio' ),
                'Traitementpdo.hasrevenu' => array( 'type' => 'radio' ),
                'Traitementpdo.hasficheanalyse' => array( 'type' => 'radio' )
            ),
            array(
                'options' => $options
            )
        );
        
        echo $xhtml->tag(
        	'fieldset',
        	$default->subform(
		        array(
		            'Traitementpdo.ficheanalyse' => array( 'type' => 'textarea' ),
		        ),
		        array(
		            'options' => $options
		        )
			),
			array(
				'id'=>'fieldsetficheanalyse',
				'class'=>'noborder invisible'
			)
        );
        
        echo $default->subform(
            array(
                'Traitementpdo.haspiecejointe' => array( 'type' => 'radio' ),
                'Traitementpdo.cloreprev' => array( 'type' => 'checkbox' )
            ),
            array(
                'options' => $options
            )
        );
        
        echo "<fieldset id='traitementprev' class='noborder invisible'><table>";
        
		echo $default2->thead(
			array(
				'Traitementpdo.descriptionpdo_id' => array( 'type'=>'string' ),
				'Traitementpdo.datereception',
				'Traitementpdo.datedepart',
				'Traitementpdo.traitementtypepdo_id' => array( 'type'=>'string' ),
				'Traitementpdo.questionclore' => array( 'type'=>'string' )
			)
		);
		
		echo "<tbody>";
        
        foreach( $traitementspdosouverts as $traitementpdoouvert ) {
        	echo $xhtml->tag(
        		'tr',
        		$xhtml->tag(
        			'td',
        			Set::classicExtract($traitementpdoouvert, 'Descriptionpdo.name')
        		).
        		$xhtml->tag(
        			'td',
        			Set::classicExtract($traitementpdoouvert, 'Traitementpdo.datereception')
        		).
        		$xhtml->tag(
        			'td',
        			Set::classicExtract($traitementpdoouvert, 'Traitementpdo.datedepart')
        		).
        		$xhtml->tag(
        			'td',
        			Set::classicExtract($traitementpdoouvert, 'Traitementtypepdo.name')
        		).
        		$xhtml->tag(
        			'td',
        			$form->input(
        				'Traitementpdo.traitmentpdoIdClore.'.Set::classicExtract($traitementpdoouvert, 'Traitementpdo.id'),
        				array(
        					'type'=>'checkbox',
        					'label'=>false
        				)
        			)
        		)
        	);
        }
        
        echo "</tbody></table></fieldset>";
        
        echo $form->end('Enregistrer');

?>
</div>
<div class="clearer"><hr /></div>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
		observeDisableFieldsetOnRadioValue(
			'traitementpdoform',
			'data[Traitementpdo][hasficheanalyse]',
			$( 'fieldsetficheanalyse' ),
			'1',
			false,
			true
		);
		
		observeDisableFieldsOnValue(
			'TraitementpdoTraitementtypepdoId',
			[
				'TraitementpdoDateecheanceDay',
				'TraitementpdoDateecheanceMonth',
				'TraitementpdoDateecheanceYear',
				'TraitementpdoDaterevisionDay',
				'TraitementpdoDaterevisionMonth',
				'TraitementpdoDaterevisionYear'
			],
			'2',
			true
		);
		
		observeDisableFieldsetOnCheckbox(
			'TraitementpdoCloreprev',
			'traitementprev',
			false,
			true
		);
		
		<?php
			$datesreception = array();
			$datesdepart = array();
			foreach($options['Traitementpdo']['listeDescription'] as $description) {
				if ($description['Descriptionpdo']['dateactive'] == 'datedepart') {
					$datesreception[] = $description["Descriptionpdo"]["id"];
				}
				else {
					$datesdepart[] = $description["Descriptionpdo"]["id"];
				}
			}
		?>

		<?php if( !empty( $datesreception ) ): ?>
		observeDisableFieldsOnValue(
			'TraitementpdoDescriptionpdoId',
			[
				'TraitementpdoDatereceptionDay',
				'TraitementpdoDatereceptionMonth',
				'TraitementpdoDatereceptionYear'
			 ],
			 [ '<?php echo implode( "', '", $datesreception ); ?>' ],
			 true
		 );
		 <?php endif; ?>

		<?php if( !empty( $datesdepart ) ): ?>
		observeDisableFieldsOnValue(
			'TraitementpdoDescriptionpdoId',
			[
				'TraitementpdoDatedepartDay',
				'TraitementpdoDatedepartMonth',
				'TraitementpdoDatedepartYear'
			 ],
			 [ '<?php echo implode( "', '", $datesdepart ); ?>' ],
			 true
		 );
		 <?php endif; ?>

		 observeDisableFieldsOnValue(
			 'TraitementpdoDescriptionpdoId',
			 [
				 'TraitementpdoDatedepartDay',
				 'TraitementpdoDatedepartMonth',
				 'TraitementpdoDatedepartYear'
			 ],
			 [ '2', '5' ],
			 true
		 );
	} );
</script>
