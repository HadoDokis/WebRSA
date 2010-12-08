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
                'Traitementpdo.hascourrier' => array( 'type' => 'radio' )
            ),
            array(
                'options' => $options
            )
        );
        
        echo $default->subform(
            array(
                'Traitementpdo.hasrevenu' => array( 'type' => 'radio' )
            ),
            array(
                'options' => $options
            )
        );
        
        echo "<fieldset id='fichecalcul' class='noborder invisible'><table>";
        
        	echo $html->tag(
        		'tr',
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.name', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			$form->input('Fichecalcul.name', array('label'=>false, 'type'=>'select', 'options'=>$regimes, 'empty'=>true))
        		).
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.saisonnier', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			$form->input('Fichecalcul.saisonnier', array('label'=>false, 'type'=>'checkbox'))
        		)
        	);
        	
        	echo $html->tag(
        		'tr',
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.nrmrcs', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			$form->input('Fichecalcul.nrmrcs', array('label'=>false, 'type'=>'text'))
        		).
        		$html->tag(
        			'td',
        			'',
        			array(
        				'colspan' => 2
        			)
        		)
        	);
        
        	echo $html->tag(
        		'tr',
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.dtdebutactivite', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			$form->input('Fichecalcul.dtdebutactivite',
        				array(
        					'label'=>false,
        					'type'=>'date',
        					'dateFormat' => 'DMY',
        					'minYear' => date('Y') - 5,
        					'maxYear' => date('Y')
        				)
        			)
        		).
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.raisonsocial', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			$form->input('Fichecalcul.raisonsocial', array('label'=>false, 'type'=>'text'))
        		)
        	);
        	
        	echo $html->tag(
        		'tr',
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.dtdebutperiode', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			$form->input('Fichecalcul.dtdebutperiode',
        				array(
        					'label'=>false,
        					'type'=>'date',
        					'dateFormat' => 'DMY',
        					'minYear' => date('Y') - 5,
        					'maxYear' => date('Y')
        				)
        			)
        		).
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.dtfinperiode', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			$form->input('Fichecalcul.dtfinperiode',
        				array(
        					'label'=>false,
        					'type'=>'date',
        					'dateFormat' => 'DMY',
        					'minYear' => date('Y') - 5,
        					'maxYear' => date('Y') + 1
        				)
        			)
        		)
        	);
        	
        	echo $html->tag(
        		'tr',
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.nbmoisactivite', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			'',
        			array(
        				'id' => 'nbmoisactivite'
        			)
        		).
        		$html->tag(
        			'td',
        			'',
        			array(
        				'colspan' => 2
        			)
        		)
        	);
        	
        	echo $html->tag(
        		'tr',
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.forfait', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			$form->input('Fichecalcul.forfait', array('label'=>false, 'type'=>'text'))
        		).
        		$html->tag(
        			'td',
        			'',
        			array(
        				'colspan' => 2
        			)
        		),
        		array(
        			'class' => 'fagri'
        		)
        	);
        
        	echo $html->tag(
        		'tr',
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.coefannee1', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			Configure::read('fichecalcul_coefannee1').' %',
        			array(
        				'id' => 'coefannee1'
        			)
        		).
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.aidesubvreint', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			$form->input('Fichecalcul.aidesubvreint', array('label'=>false, 'type'=>'select'))
        		),
        		array(
        			'class' => 'fagri'
        		)
        	);
        
        	echo $html->tag(
        		'tr',
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.coefannee2', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			Configure::read('fichecalcul_coefannee2').' %',
        			array(
        				'id' => 'coefannee2'
        			)
        		).
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.mtaidesub', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			$form->input('Fichecalcul.mtaidesub', array('label'=>false, 'type'=>'text'))
        		),
        		array(
        			'class' => 'fagri'
        		)
        	);
        	
        	echo $html->tag(
        		'tr',
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.chaffvnt', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			$form->input('Fichecalcul.chaffvnt', array('label'=>false, 'type'=>'text'))
        		).
        		$html->tag(
        			'td',
        			'Abattement',
        			array(
        				'class' => 'microbic'
        			)
        		).
        		$html->tag(
        			'td',
        			Configure::read('fichecalcul_abattbicvnt').' %',
        			array(
        				'class' => 'microbic',
        				'id' => 'abattbicvnt'
        			)
        		).
        		$html->tag(
        			'td',
        			'',
        			array(
        				'id' => 'infoChaffvnt',
        				'colspan' => '2',
        				'class' => 'fagri ragri reel microbnc'
        			)
        		),
        		array(
        			'class' => 'ragri reel microbic'
        		)
        	);
        	
        	echo $html->tag(
        		'tr',
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.chaffsrv', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			$form->input('Fichecalcul.chaffsrv', array('label'=>false, 'type'=>'text'))
        		).
        		$html->tag(
        			'td',
        			'Abattement',
        			array(
        				'class' => 'microbic microbnc'
        			)
        		).
        		$html->tag(
        			'td',
        			Configure::read('fichecalcul_abattbicsrv').' %',
        			array(
        				'class' => 'microbic',
        				'id' => 'abattbicsrv'
        			)
        		).
        		$html->tag(
        			'td',
        			Configure::read('fichecalcul_abattbncsrv').' %',
        			array(
        				'class' => 'microbnc',
        				'id' => 'abattbncsrv'
        			)
        		).
        		$html->tag(
        			'td',
        			'',
        			array(
        				'id' => 'infoChaffsrv',
        				'colspan' => '2',
        				'class' => 'fagri ragri reel'
        			)
        		),
        		array(
        			'class' => 'ragri reel microbic microbnc'
        		)
        	);
        	
        	echo $html->tag(
        		'tr',
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.benefpriscompte', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			'',
        			array(
        				'id' => 'benefpriscompte'
        			)
        		).
        		$html->tag(
        			'td',
        			'',
        			array(
        				'colspan' => 2
        			)
        		),
        		array(
        			'class' => 'microbic microbnc'
        		)
        	);
        	
        	echo $html->tag(
        		'tr',
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.benefoudef', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			$form->input('Fichecalcul.benefoudef', array('label'=>false, 'type'=>'text'))
        		).
        		$html->tag(
        			'td',
        			'',
        			array(
        				'colspan' => 2
        			)
        		),
        		array(
        			'class' => 'ragri reel'
        		)
        	);
        	
        	echo $html->tag(
        		'tr',
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.correction', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.amortissements', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			$form->input('Fichecalcul.amortissements', array('label'=>false, 'type'=>'text')),
        			array(
        				'colspan' => 2
        			)
        		),
        		array(
        			'class' => 'ragri reel'
        		)
        	);
        	
        	echo $html->tag(
        		'tr',
        		$html->tag(
        			'td',
        			''
        		).
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.salaireexploitant', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			$form->input('Fichecalcul.salaireexploitant', array('label'=>false, 'type'=>'text')),
        			array(
        				'colspan' => 2
        			)
        		),
        		array(
        			'class' => 'ragri reel'
        		)
        	);
        	
        	echo $html->tag(
        		'tr',
        		$html->tag(
        			'td',
        			''
        		).
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.provisionsnonded', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			$form->input('Fichecalcul.provisionsnonded', array('label'=>false, 'type'=>'text')),
        			array(
        				'colspan' => 2
        			)
        		),
        		array(
        			'class' => 'ragri reel'
        		)
        	);
        	
        	echo $html->tag(
        		'tr',
        		$html->tag(
        			'td',
        			''
        		).
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.moinsvaluescession', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			$form->input('Fichecalcul.moinsvaluescession', array('label'=>false, 'type'=>'text')),
        			array(
        				'colspan' => 2
        			)
        		),
        		array(
        			'class' => 'ragri reel'
        		)
        	);
        	
        	echo $html->tag(
        		'tr',
        		$html->tag(
        			'td',
        			''
        		).
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.autrecorrection', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			$form->input('Fichecalcul.autrecorrection', array('label'=>false, 'type'=>'text')),
        			array(
        				'colspan' => 2
        			)
        		),
        		array(
        			'class' => 'ragri reel'
        		)
        	);
        	
        	echo $html->tag(
        		'tr',
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.mnttotal', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			'',
        			array(
        				'id' => 'mnttotal'
        			)
        		).
        		$html->tag(
        			'td',
        			'',
        			array(
        				'colspan' => 2
        			)
        		),
        		array(
        			'class' => 'fagri ragri reel'
        		)
        	);
        	
        	echo $html->tag(
        		'tr',
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.revenus', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			'',
        			array(
        				'id' => 'revenus'
        			)
        		).
        		$html->tag(
        			'td',
        			'',
        			array(
        				'colspan' => 2
        			)
        		)
        	);
        	
        	echo $html->tag(
        		'tr',
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.dtprisecompte', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			$form->input('Fichecalcul.dtprisecompte',
        				array(
        					'label'=>false,
        					'type'=>'date',
        					'dateFormat' => 'DMY',
        					'minYear' => date('Y') - 5,
        					'maxYear' => date('Y')
        				)
        			)
        		).
        		$html->tag(
        			'td',
        			$xform->_label('Fichecalcul.dtecheance', array('domain'=>'fichecalcul'))
        		).
        		$html->tag(
        			'td',
        			$form->input('Fichecalcul.dtecheance',
        				array(
        					'label'=>false,
        					'type'=>'date',
        					'dateFormat' => 'DMY',
        					'minYear' => date('Y') - 5,
        					'maxYear' => date('Y') + 1
        				)
        			)
        		)
        	);
        
        echo "</table></fieldset>";
        
        echo $default->subform(
            array(
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
		
		observeDisableFieldsetOnRadioValue(
			'traitementpdoform',
			'data[Traitementpdo][hasrevenu]',
			$( 'fichecalcul' ),
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
		 
		<?php foreach ($regimes as $enumname=>$enumvalue): ?>
			$$('tr.<?php echo $enumname; ?>').each(function (element) {
				element.hide();
			});
			$$('td.<?php echo $enumname; ?>').each(function (element) {
				element.hide();
			});
		<?php endforeach; ?>
			
		$('FichecalculName').observe( 'change', function (event) {
			var value = $F('FichecalculName');
			$$('#fichecalcul tr').each(function (element) {
				var classes = $( element ).classNames();
				if( classes.size() > 0 ) {
					if( $( element ).hasClassName( value ) ) {
						element.show();
					}
					else {
						element.hide();
					}
				}
			});
			$$('#fichecalcul tr td').each(function (element) {
				var classes = $( element ).classNames();
				if( classes.size() > 0 ) {
					if( $( element ).hasClassName( value ) ) {
						element.show();
					}
					else {
						element.hide();
					}
				}
			});
		});
		
		$('FichecalculDtdebutperiodeDay').observe( 'change', function (event) {
			recalculnbmoisactivite();
		});
		$('FichecalculDtdebutperiodeMonth').observe( 'change', function (event) {
			recalculnbmoisactivite();
		});
		$('FichecalculDtdebutperiodeYear').observe( 'change', function (event) {
			recalculnbmoisactivite();
		});
		$('FichecalculDtfinperiodeDay').observe( 'change', function (event) {
			recalculnbmoisactivite();
		});
		$('FichecalculDtfinperiodeMonth').observe( 'change', function (event) {
			recalculnbmoisactivite();
		});
		$('FichecalculDtfinperiodeYear').observe( 'change', function (event) {
			recalculnbmoisactivite();
		});
		recalculnbmoisactivite();
		 
		$('FichecalculForfait').observe( 'change', function (event) {
			recalculmnttotal();
		});
		$('FichecalculMtaidesub').observe( 'change', function (event) {
			recalculmnttotal();
		});
		$('FichecalculBenefoudef').observe( 'change', function (event) {
			recalculmnttotal();
		});
		$('FichecalculAmortissements').observe( 'change', function (event) {
			recalculmnttotal();
		});
		$('FichecalculSalaireexploitant').observe( 'change', function (event) {
			recalculmnttotal();
		});
		$('FichecalculProvisionsnonded').observe( 'change', function (event) {
			recalculmnttotal();
		});
		$('FichecalculMoinsvaluescession').observe( 'change', function (event) {
			recalculmnttotal();
		});
		$('FichecalculAutrecorrection').observe( 'change', function (event) {
			recalculmnttotal();
		});
		recalculmnttotal();
		
		$('FichecalculChaffvnt').observe( 'change', function (event) {
			recalculbenefpriscompte();
			// Infobulle
			var td = $('infoChaffvnt');
			td.update( '' );

			if( $F('FichecalculChaffvnt') > <?php echo Configure::read( 'fichecalcul_cavntmax' )?> ) {
				var p = new Element( 'p', { class:'notice'} ).update('Choucroute');
				td.update( p );
			}
		});
		$('FichecalculChaffsrv').observe( 'change', function (event) {
			recalculbenefpriscompte();
		});
		recalculbenefpriscompte();
		
		recalculrevenus();
	} );
	
	function recalculnbmoisactivite() {
		var nbmois = 0;
		if ($F('FichecalculDtfinperiodeYear') >= $F('FichecalculDtdebutperiodeYear')) {
			nbmois += 12 * ($F('FichecalculDtfinperiodeYear') - $F('FichecalculDtdebutperiodeYear'));
			if (
				($F('FichecalculDtfinperiodeMonth') >= $F('FichecalculDtdebutperiodeMonth'))
				||
				(
					($F('FichecalculDtfinperiodeMonth') < $F('FichecalculDtdebutperiodeMonth'))
					&&
					($F('FichecalculDtfinperiodeYear') > $F('FichecalculDtdebutperiodeYear'))
				)
			)
				nbmois += $F('FichecalculDtfinperiodeMonth') - $F('FichecalculDtdebutperiodeMonth');
			if ($F('FichecalculDtfinperiodeDay') > $F('FichecalculDtdebutperiodeDay'))
				nbmois++;
		}
		if (nbmois < 0)
			nbmois = 0;
		
		$('nbmoisactivite').innerHTML = ''+nbmois+' mois';
		recalculrevenus();
	}
	
	function recalculmnttotal() {
		var mttotal = 0;
		
		if ($F('FichecalculName')=='fagri') {
			var coefannee1 = $('coefannee1').innerHTML.split(' ');
			var valuecoefannee1 = coefannee1[0].replace(',', '.');
			valuecoefannee1 = parseFloat(valuecoefannee1)/100;
			var coefannee2 = $('coefannee2').innerHTML.split(' ');
			var valuecoefannee2 = coefannee2[0].replace(',', '.');
			valuecoefannee2 = parseFloat(valuecoefannee2)/100;
			var forfait = parseFloat($F('FichecalculForfait').replace(',', '.'));
			var mtaidesub = parseFloat($F('FichecalculMtaidesub').replace(',', '.'));
			
			if (!isNaN(valuecoefannee1) && !isNaN(valuecoefannee2) && !isNaN(forfait) && forfait!=0)
				mttotal += Math.round( ( forfait + ( ( forfait + ( forfait * valuecoefannee1 ) ) * valuecoefannee2 ) ) * 100 ) / 100;
			if (!isNaN(mtaidesub) && mtaidesub!=0)
				mttotal += Math.round( mtaidesub * 100 ) / 100;
		}
		else if ($F('FichecalculName')=='ragri' || $F('FichecalculName')=='reel') {
			var benefoudef = parseFloat($F('FichecalculBenefoudef').replace(',', '.'));
			var amortissements = parseFloat($F('FichecalculAmortissements').replace(',', '.'));
			var salaireexploitant = parseFloat($F('FichecalculSalaireexploitant').replace(',', '.'));
			var provisionsnonded = parseFloat($F('FichecalculProvisionsnonded').replace(',', '.'));
			var moinsvaluescession = parseFloat($F('FichecalculMoinsvaluescession').replace(',', '.'));
			var autrecorrection = parseFloat($F('FichecalculAutrecorrection').replace(',', '.'));
			
			if (!isNaN(benefoudef))
				mttotal += Math.round( ( benefoudef ) * 100 ) / 100;
			if (!isNaN(amortissements))
				mttotal += Math.round( ( amortissements ) * 100 ) / 100;
			if (!isNaN(salaireexploitant))
				mttotal += Math.round( ( salaireexploitant ) * 100 ) / 100;
			if (!isNaN(provisionsnonded))
				mttotal += Math.round( ( provisionsnonded ) * 100 ) / 100;
			if (!isNaN(moinsvaluescession))
				mttotal += Math.round( ( moinsvaluescession ) * 100 ) / 100;
			if (!isNaN(autrecorrection))
				mttotal += Math.round( ( autrecorrection ) * 100 ) / 100;
		}
		
		mttotal = mttotal.toString().replace('.', ',');
		$('mnttotal').innerHTML = mttotal+' €';
		recalculrevenus();
	}
	
	function recalculbenefpriscompte() {
		var benefpriscompte = 0;
		
		if ($F('FichecalculName')=='microbic') {
			var chaffvnt = parseFloat($F('FichecalculChaffvnt').replace(',', '.'));
			var chaffsrv = parseFloat($F('FichecalculChaffsrv').replace(',', '.'));
			var abattbicvnt = $('abattbicvnt').innerHTML.split(' ');
			var valueabattbicvnt = abattbicvnt[0].replace(',', '.');
			valueabattbicvnt = 1 - parseFloat(valueabattbicvnt)/100;
			var abattbicsrv = $('abattbicsrv').innerHTML.split(' ');
			var valueabattbicsrv = abattbicsrv[0].replace(',', '.');
			valueabattbicsrv = 1 - parseFloat(valueabattbicsrv)/100;
			
			if (!isNaN(chaffsrv) && !isNaN(valueabattbicsrv))
				benefpriscompte += Math.round( (chaffsrv * valueabattbicsrv ) * 100 ) / 100;
			if (!isNaN(chaffvnt) && !isNaN(valueabattbicvnt))
				benefpriscompte += Math.round( ( chaffvnt * valueabattbicvnt ) * 100 ) / 100;
		}
		else if ($F('FichecalculName')=='microbnc') {
			var chaffsrv = parseFloat($F('FichecalculChaffsrv').replace(',', '.'));
			var abattbncsrv = $('abattbncsrv').innerHTML.split(' ');
			var valueabattbncsrv = abattbncsrv[0].replace(',', '.');
			valueabattbncsrv = 1 - parseFloat(valueabattbncsrv)/100;
			
			if (!isNaN(chaffvnt) && !isNaN(valueabattbncsrv))
				benefpriscompte = Math.round( ( chaffvnt * valueabattbncsrv ) * 100 ) / 100;
		}
		
		benefpriscompte = benefpriscompte.toString().replace('.', ',');
		$('benefpriscompte').innerHTML = benefpriscompte + ' €';
		recalculrevenus();
	}
	
	function recalculrevenus() {
		var revenus = 0;
		
		if ($F('FichecalculName')=='fagri' || $F('FichecalculName')=='ragri' || $F('FichecalculName')=='reel') {
			var mnttotal = $('mnttotal').innerHTML.split(' ');
			var valuemnttotal = mnttotal[0].replace(',', '.');
			valuemnttotal = parseFloat(valuemnttotal);
			var nbmois = $('nbmoisactivite').innerHTML.split(' ');
			var valuenbmois = parseFloat(nbmois[0]);
			
			if (!isNaN(valuemnttotal) && !isNaN(valuenbmois) && valuemnttotal!=0 && valuenbmois!=0)
				revenus = Math.round( parseFloat( valuemnttotal ) / parseFloat( valuenbmois ) * 100 ) / 100;
		}
		else if ($F('FichecalculName')=='microbic' || $F('FichecalculName')=='microbnc') {
			var benefpriscompte = $('benefpriscompte').innerHTML.split(' ');
			var valuebenefpriscompte = benefpriscompte[0].replace(',', '.');
			valuebenefpriscompte = parseFloat(valuebenefpriscompte);
			var nbmois = $('nbmoisactivite').innerHTML.split(' ');
			var valuenbmois = parseFloat(nbmois[0]);
			
			if (!isNaN(valuebenefpriscompte) && !isNaN(valuenbmois) && valuebenefpriscompte!=0 && valuenbmois!=0)
				revenus = Math.round( parseFloat( valuebenefpriscompte ) / parseFloat( valuenbmois ) * 100 ) / 100;
		}
		
		revenus = revenus.toString().replace('.', ',');
		$('revenus').innerHTML = revenus + ' € par mois';
	}
	
</script>
