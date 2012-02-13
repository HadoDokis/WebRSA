<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $xhtml->css( array( 'fileuploader' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $javascript->link( 'fileuploader.js' );
	}
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

		echo $default->subform(
			array(
				'Traitementpdo.propopdo_id' => array( 'type' => 'hidden', 'value' => $propopdo_id ),
				'Traitementpdo.descriptionpdo_id' => array( 'type' => 'select' ),
				'Traitementpdo.traitementtypepdo_id' => array( 'type' => 'select' )
			),
			array(
				'options' => $options
			)
		);

		echo $xhtml->tag(
			'fieldset',
			$default->subform(
				array(
					'Traitementpdo.datereception' => array( 'required' => true, 'empty' => false, 'maxYear' => date('Y') + 2, 'minYear' => date('Y' ) -2 )
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
	?>

	<?php
		echo $xhtml->tag(
			'fieldset',
			$default->subform(
				array(
					'Traitementpdo.datedepart' => array( 'required' => true, 'maxYear' => date('Y') + 2, 'minYear' => date('Y' ) -2 )
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
	?>

<script type="text/javascript">

	function checkDatesToExpiration( dateDonnee, dateAChanger, operateur ) {
		var duree = $F( 'TraitementpdoDuree'+dateDonnee ).split( '.' );
		var month = duree[0];
		var day = duree[1];
		if ( $F( 'TraitementpdoDate'+dateDonnee+'Day' ) != "" && $F( 'TraitementpdoDate'+dateDonnee+'Month' ) != "" && $F( 'TraitementpdoDate'+dateDonnee+'Year' ) != "" && $F( 'TraitementpdoDuree'+dateDonnee ) != "" ) {
			var dateDepart = new Date( $F( 'TraitementpdoDate'+dateDonnee+'Year' ), $F( 'TraitementpdoDate'+dateDonnee+'Month' )-1, $F( 'TraitementpdoDate'+dateDonnee+'Day' ) );
			if ( day != undefined ) {
				if ( operateur == '+' ) {
					dateDepart.setDate( 15 + dateDepart.getDate() );
				}
				else {
					dateDepart.setDate( 15 - dateDepart.getDate() );
				}
			}
			if ( operateur == '+' ) {
				month = parseInt( dateDepart.getMonth() ) + parseInt( month );
			}
			else {
				month = parseInt( dateDepart.getMonth() ) - parseInt( month );
			}
			dateDepart.setMonth( month );
			var newday = dateDepart.getDate();
			var newmonth = dateDepart.getMonth()+1;
			var newyear = dateDepart.getFullYear();
			$( 'TraitementpdoDate'+dateAChanger+'Day' ).value = ( newday < 10 ) ? '0' + newday : newday;
			$( 'TraitementpdoDate'+dateAChanger+'Month' ).value = ( newmonth < 10 ) ? '0' + newmonth : newmonth;
			$( 'TraitementpdoDate'+dateAChanger+'Year' ).value = newyear;
		}
	}

	document.observe( "dom:loaded", function() {
		[ 'TraitementpdoDatedepartDay', 'TraitementpdoDatedepartMonth', 'TraitementpdoDatedepartYear', 'TraitementpdoDureedepart' ].each( function( id ) {
			$( id ).observe( 'change', function() {
				checkDatesToExpiration( 'depart', 'echeance', '+' );
			});
		});
	});

</script>

	<?php
		echo $default->subform(
			array(
				'Traitementpdo.personne_id' => array( 'empty' => true, 'type' => 'select', 'options' => $listepersonnes )
			),
			array(
				'options' => $options
			)
		);

		echo $ajax->observeField( 'TraitementpdoPersonneId', array( 'update' => 'statutPersonne', 'url' => Router::url( array( 'action' => 'ajaxstatutpersonne' ), true ) ) );

		?><fieldset id="statutPersonne" class="invisible"></fieldset><?php
?>

<fieldset>
	<legend><?php echo required( $default2->label( 'Traitementpdo.hascourrier' ) );?></legend>

	<?php echo $xform->input( 'Traitementpdo.hascourrier', array( 'type' => 'radio', 'options' => $options['Traitementpdo']['hascourrier'], 'legend' => false, 'fieldset' => false ) );?>
	<fieldset id="filecontainer-courrier" class="noborder invisible">
		<?php
			$key = 0;
			foreach( $listcourrier as $i => $list ){
				$key++;
				echo $xform->input( "Courrierpdo.{$i}.id", array( 'type' => 'hidden', 'value' => $list['Courrierpdo']['id'] ) );
				echo $xform->input( "Courrierpdo.{$i}.checked", array( 'type' => 'checkbox', 'label' => $list['Courrierpdo']['name'] ) );

				echo '<fieldset class="invisible" id="ZoneCommentaire'.$i.'">';
				foreach( $list['Textareacourrierpdo'] as $j => $textarea ){
					$key++;
					echo $xform->input( "Contenutextareacourrierpdo.{$key}.contenu", array( 'label' => $textarea['name'], 'type' => 'textarea' ) );
					echo $xform->input( "Contenutextareacourrierpdo.{$key}.textareacourrierpdo_id", array( 'type' => 'hidden', 'value' => $textarea['id'] ) );
				}
				echo '</fieldset>';
			}
		?>

	</fieldset>
</fieldset>

<script type="text/javascript">

	document.observe( "dom:loaded", function() {
		<?php foreach( $listcourrier as $i => $list ):?>
			observeDisableFieldsetOnCheckbox(
				'Courrierpdo<?php echo $i;?>Checked',
				'ZoneCommentaire<?php echo $i;?>',
				false,
				true
			);

		observeDisableFieldsOnRadioValue(
			'traitementpdoform',
			'data[Traitementpdo][hascourrier]',
			[ 'Courrierpdo<?php echo $i;?>Checked' ],
			'1',
			true,
			true
		);

		<?php endforeach; ?>

	} );

</script>
<?php
		echo $default->subform(
			array(
				'Traitementpdo.hasrevenu' => array( 'type' => 'radio' )
			),
			array(
				'options' => $options
			)
		);

		echo "<fieldset id='fichecalcul' class='noborder invisible'><table>";

			echo $default->subform(
				array(
					'Traitementpdo.nbmoisactivite' => array( 'type' => 'hidden' ),
					'Traitementpdo.mnttotalpriscompte' => array( 'type' => 'hidden' ),
					'Traitementpdo.revenus' => array( 'type' => 'hidden' ),
					'Traitementpdo.benefpriscompte' => array( 'type' => 'hidden' )
				),
				array(
					'options' => $options
				)
			);

			echo $html->tag(
				'tr',
				$html->tag(
					'td',
					$xform->_label('Traitementpdo.regime', array('domain'=>'traitementpdo', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpdo.regime', array('label'=>false, 'type'=>'select', 'options'=>$options['Traitementpdo']['regime'], 'empty'=>true))
				).
				$html->tag(
					'td',
					$xform->_label('Traitementpdo.saisonnier', array('domain'=>'traitementpdo'))
				).
				$html->tag(
					'td',
					$form->input('Traitementpdo.saisonnier', array('label'=>false, 'type'=>'checkbox'))
				)
			);

			echo $html->tag(
				'tr',
				$html->tag(
					'td',
					$xform->_label('Traitementpdo.nrmrcs', array('domain'=>'traitementpdo', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpdo.nrmrcs', array('label'=>false, 'type'=>'text'))
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
					$xform->_label('Traitementpdo.dtdebutactivite', array('domain'=>'traitementpdo', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpdo.dtdebutactivite',
						array(
							'label'=>false,
							'type'=>'date',
							'empty'=>true,
							'dateFormat' => 'DMY',
							'minYear' => date('Y') - 5,
							'maxYear' => date('Y')
						)
					)
				).
				$html->tag(
					'td',
					$xform->_label('Traitementpdo.raisonsocial', array('domain'=>'traitementpdo', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpdo.raisonsocial', array('label'=>false, 'type'=>'text'))
				)
			);

			echo $html->tag(
				'tr',
				$html->tag(
					'td',
					$xform->_label('Traitementpdo.dtdebutperiode', array('domain'=>'traitementpdo', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpdo.dtdebutperiode',
						array(
							'label'=>false,
							'type'=>'date',
							'empty'=>true,
							'dateFormat' => 'DMY',
							'minYear' => date('Y') - 5,
							'maxYear' => date('Y')
						)
					)
				).
				$html->tag(
					'td',
					$xform->_label('Traitementpdo.datefinperiode', array('domain'=>'traitementpdo', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpdo.datefinperiode',
						array(
							'label'=>false,
							'type'=>'date',
							'empty'=>true,
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
					$xform->_label('Traitementpdo.nbmoisactivite', array('domain'=>'traitementpdo'))
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
					$xform->_label('Traitementpdo.forfait', array('domain'=>'traitementpdo'))
				).
				$html->tag(
					'td',
					$form->input('Traitementpdo.forfait', array('label'=>false, 'type'=>'text'))
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
					$xform->_label('Traitementpdo.coefannee1', array('domain'=>'traitementpdo'))
				).
				$html->tag(
					'td',
					Configure::read('Traitementpdo.fichecalcul_coefannee1').' %',
					array(
						'id' => 'coefannee1'
					)
				).
				$html->tag(
					'td',
					$xform->_label('Traitementpdo.aidesubvreint', array('domain'=>'traitementpdo'))
				).
				$html->tag(
					'td',
					$default->subform(
						array(
							'Traitementpdo.aidesubvreint' => array( 'type' => 'select', 'label' => false, 'empty' => true )
						),
						array(
							'options' => $options
						)
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
					$xform->_label('Traitementpdo.coefannee2', array('domain'=>'traitementpdo'))
				).
				$html->tag(
					'td',
					Configure::read('Traitementpdo.fichecalcul_coefannee2').' %',
					array(
						'id' => 'coefannee2'
					)
				).
				$html->tag(
					'td',
					$xform->_label('Traitementpdo.mtaidesub', array('domain'=>'traitementpdo'))
				).
				$html->tag(
					'td',
					$form->input('Traitementpdo.mtaidesub', array('label'=>false, 'type'=>'text'))
				),
				array(
					'class' => 'fagri'
				)
			);

			echo $html->tag(
				'tr',
				$html->tag(
					'td',
					$xform->_label('Traitementpdo.chaffvnt', array('domain'=>'traitementpdo', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpdo.chaffvnt', array('label'=>false, 'type'=>'text')).
					$html->tag(
						'p',
						'Attention CA dépassant '.Configure::read('Traitementpdo.fichecalcul_cavntmax').' €',
						array(
							'class' => 'notice',
							'id' => 'infoChaffvnt'
						)
					)
				).
				$html->tag(
					'td',
					$xform->_label('Traitementpdo.abattement', array('domain'=>'traitementpdo')),
					array(
						'class' => 'microbic microbicauto'
					)
				).
				$html->tag(
					'td',
					Configure::read('Traitementpdo.fichecalcul_abattbicvnt').' %',
					array(
						'class' => 'microbic microbicauto',
						'id' => 'abattbicvnt'
					)
				).
				$html->tag(
					'td',
					'',
					array(
						'colspan' => '2',
						'class' => 'fagri ragri reel microbnc'
					)
				),
				array(
					'class' => 'ragri reel microbic microbicauto'
				)
			);

			echo $html->tag(
				'tr',
				$html->tag(
					'td',
					$xform->_label('Traitementpdo.chaffsrv', array('domain'=>'traitementpdo', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpdo.chaffsrv', array('label'=>false, 'type'=>'text')).
					$html->tag(
						'p',
						'Attention CA dépassant '.Configure::read('Traitementpdo.fichecalcul_casrvmax').' €',
						array(
							'class' => 'notice',
							'id' => 'infoChaffsrv'
						)
					)
				).
				$html->tag(
					'td',
					$xform->_label('Traitementpdo.abattement', array('domain'=>'traitementpdo')),
					array(
						'class' => 'microbic microbicauto microbnc'
					)
				).
				$html->tag(
					'td',
					Configure::read('Traitementpdo.fichecalcul_abattbicsrv').' %',
					array(
						'class' => 'microbic microbicauto',
						'id' => 'abattbicsrv'
					)
				).
				$html->tag(
					'td',
					Configure::read('Traitementpdo.fichecalcul_abattbncsrv').' %',
					array(
						'class' => 'microbnc',
						'id' => 'abattbncsrv'
					)
				).
				$html->tag(
					'td',
					'',
					array(
						'colspan' => '2',
						'class' => 'fagri ragri reel'
					)
				),
				array(
					'class' => 'ragri reel microbic microbicauto microbnc'
				)
			);

			echo $html->tag(
				'tr',
				$html->tag(
					'td',
					$xform->_label('Traitementpdo.benefpriscompte', array('domain'=>'traitementpdo', 'required'=>true))
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
					'class' => 'microbic microbicauto microbnc'
				)
			);

			echo $html->tag(
				'tr',
				$html->tag(
					'td',
					$xform->_label('Traitementpdo.benefoudef', array('domain'=>'traitementpdo', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpdo.benefoudef', array('label'=>false, 'type'=>'text'))
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
					$xform->_label('Traitementpdo.correction', array('domain'=>'traitementpdo'))
				).
				$html->tag(
					'td',
					$xform->_label('Traitementpdo.ammortissements', array('domain'=>'traitementpdo', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpdo.ammortissements', array('label'=>false, 'type'=>'text')),
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
					$xform->_label('Traitementpdo.salaireexploitant', array('domain'=>'traitementpdo'))
				).
				$html->tag(
					'td',
					$form->input('Traitementpdo.salaireexploitant', array('label'=>false, 'type'=>'text')),
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
					$xform->_label('Traitementpdo.provisionsnonded', array('domain'=>'traitementpdo'))
				).
				$html->tag(
					'td',
					$form->input('Traitementpdo.provisionsnonded', array('label'=>false, 'type'=>'text')),
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
					$xform->_label('Traitementpdo.moinsvaluescession', array('domain'=>'traitementpdo'))
				).
				$html->tag(
					'td',
					$form->input('Traitementpdo.moinsvaluescession', array('label'=>false, 'type'=>'text')),
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
					$xform->_label('Traitementpdo.autrecorrection', array('domain'=>'traitementpdo', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpdo.autrecorrection', array('label'=>false, 'type'=>'text')),
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
					$xform->_label('Traitementpdo.mnttotal', array('domain'=>'traitementpdo'))
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
					$xform->_label('Traitementpdo.revenus', array('domain'=>'traitementpdo'))
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
					$xform->_label('Traitementpdo.dtprisecompte', array('domain'=>'traitementpdo', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpdo.dtprisecompte',
						array(
							'label'=>false,
							'type'=>'date',
							'empty'=>true,
							'dateFormat' => 'DMY',
							'minYear' => date('Y') - 5,
							'maxYear' => date('Y')
						)
					),
					array(
						'colspan' => 1
					)
				).
				$html->tag(
					'td',
					$xform->_label('Traitementpdo.dureefinperiode', array('domain'=>'traitementpdo', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpdo.dureefinperiode', array('label'=>false, 'type'=>'select', 'options'=>$options['Traitementpdo']['dureefinperiode'], 'empty'=>true)),
					array(
						'colspan' => 1
					)
				)
			);
			echo $html->tag(
				'tr',
				$html->tag(
					'td',
					$xform->_label('Traitementpdo.daterevision', array('domain'=>'traitementpdo', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpdo.daterevision',
						array(
							'label'=>false,
							'type'=>'date',
							'empty'=>true,
							'dateFormat' => 'DMY',
							'minYear' => date('Y') - 2,
							'maxYear' => date('Y') + 2
						)
					),
					array(
						'colspan' => 3
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
?>
<script type="text/javascript">

	document.observe( "dom:loaded", function() {
		[ 'TraitementpdoDatefinperiodeDay', 'TraitementpdoDatefinperiodeMonth', 'TraitementpdoDatefinperiodeYear', 'TraitementpdoDureefinperiode' ].each( function( id ) {
			$( id ).observe( 'change', function() {
				checkDatesToExpiration( 'finperiode', 'revision', '-' );
			});
		});
	});

</script>
<fieldset>
	<legend><?php echo required( $default2->label( 'Traitementpdo.haspiecejointe' ) );?></legend>
	<?php echo $form->input( 'Traitementpdo.haspiecejointe', array( 'type' => 'radio', 'options' => $options['Traitementpdo']['haspiecejointe'], 'legend' => false, 'fieldset' => false ) );?>
	<fieldset id="filecontainer-piecejointe" class="noborder invisible">
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
			'traitementpdoform',
			'data[Traitementpdo][haspiecejointe]',
			$( 'filecontainer-piecejointe' ),
			'1',
			false,
			true
		);
	} );
</script>

	<?php
		echo $xhtml->tag(
			'fieldset',
			$default->subform(
				array(
					'Traitementpdo.dureedepart' => array( 'required' => true ),
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

		echo "<table>";
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
					$locale->date( 'Date::short', Set::classicExtract($traitementpdoouvert, 'Traitementpdo.datereception') )
				).
				$xhtml->tag(
					'td',
					$locale->date( 'Date::short', Set::classicExtract($traitementpdoouvert, 'Traitementpdo.datedepart') )
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
							'type'=>'radio',
							'legend'=>false,
							'options' => $cloture
						)
					)
				)
			);
		}

		echo "</tbody></table>";

		echo "<div class='submit'>";
			$disabled = ( isset( $this->data['Traitementpdo']['clos'] ) && $this->data['Traitementpdo']['clos'] == 1 ) ? 'disabled' : 'enabled';
			echo $form->submit( 'Enregistrer', array( 'disabled'=>$disabled, 'div'=>false ) );
			echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div'=>false ) );
		echo "</div>";

		echo $form->end();

?>
</div>
<div class="clearer"><hr /></div>

<script type="text/javascript">
	document.observe("dom:loaded", function() {

		<?php echo $ajax->remoteFunction(
			array(
				'url' => Router::url(
					array(
						'action' => 'ajaxstatutpersonne',
						@$this->data['Traitementpdo']['personne_id']
					),
					true
				),
				'update' => 'statutPersonne'
			)
		); ?>;

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
				'TraitementpdoDureedepart'
			],
			'<?php echo Configure::read('traitementClosId') ?>',
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

		<?php foreach ($options['Traitementpdo']['regime'] as $enumname=>$enumvalue): ?>
			$$('tr.<?php echo $enumname; ?>').each(function (element) {
				element.hide();
			});
			$$('td.<?php echo $enumname; ?>').each(function (element) {
				element.hide();
			});
		<?php endforeach; ?>

		$('TraitementpdoRegime').observe( 'change', function (event) {
			loadFiche();
		});
		loadFiche();

		[ $('TraitementpdoDtdebutperiodeDay'), $('TraitementpdoDtdebutperiodeMonth'), $('TraitementpdoDtdebutperiodeYear'), $('TraitementpdoDatefinperiodeDay'), $('TraitementpdoDatefinperiodeMonth'), $('TraitementpdoDatefinperiodeYear') ].each( function(element) {
			element.observe( 'change', function (event) {
				recalculnbmoisactivite();
			});
		});

		[ $('TraitementpdoForfait'), $('TraitementpdoMtaidesub'), $('TraitementpdoBenefoudef'), $('TraitementpdoAmmortissements'), $('TraitementpdoSalaireexploitant'), $('TraitementpdoProvisionsnonded'), $('TraitementpdoMoinsvaluescession'), $('TraitementpdoAutrecorrection'),  ].each( function (element) {
			element.observe( 'change', function (event) {
				recalculmnttotal();
			});
		});

		$('TraitementpdoChaffvnt').observe( 'change', function (event) {
			recalculbenefpriscompte();
			// Infobulle
			infobulle('vnt');
		});
		$('TraitementpdoChaffsrv').observe( 'change', function (event) {
			recalculbenefpriscompte();
			// Infobulle
			infobulle('srv');
		});
	} );

	function loadFiche() {
		var value = $F('TraitementpdoRegime');
		$$('#fichecalcul tr').each(function (element) {
			var classes = $( element ).classNames();
			if( classes.size() > 0 ) {
				if( $( element ).hasClassName( value ) ) {
					element.show();

					// Réactiver les champs
					$( element ).getElementsBySelector( 'select', 'input' ).each( function( input ) {
						input.disabled = '';
					} );
				}
				else {
					element.hide();

					// Désactiver les champs
					$( element ).getElementsBySelector( 'select', 'input' ).each( function( input ) {
						input.disabled = 'disabled';
					} );
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
		recalculnbmoisactivite();
		recalculmnttotal();
		recalculbenefpriscompte();
		infobulle('vnt');
		infobulle('srv');
	}

	function recalculnbmoisactivite() {
		var nbmois = 0;
		if ($F('TraitementpdoDatefinperiodeYear') >= $F('TraitementpdoDtdebutperiodeYear')) {
			nbmois += 12 * ($F('TraitementpdoDatefinperiodeYear') - $F('TraitementpdoDtdebutperiodeYear'));
			if (
				($F('TraitementpdoDatefinperiodeMonth') >= $F('TraitementpdoDtdebutperiodeMonth'))
				||
				(
					($F('TraitementpdoDatefinperiodeMonth') < $F('TraitementpdoDtdebutperiodeMonth'))
					&&
					($F('TraitementpdoDatefinperiodeYear') > $F('TraitementpdoDtdebutperiodeYear'))
				)
			)
				nbmois += $F('TraitementpdoDatefinperiodeMonth') - $F('TraitementpdoDtdebutperiodeMonth');
			if ($F('TraitementpdoDatefinperiodeDay') > $F('TraitementpdoDtdebutperiodeDay'))
				nbmois++;
		}
		if (nbmois < 0)
			nbmois = 0;

		$('nbmoisactivite').innerHTML = ''+nbmois+' mois';
		$('TraitementpdoNbmoisactivite').setValue(nbmois);
		recalculrevenus();
	}

	function recalculmnttotal() {
		var mttotal = 0;

		if ($F('TraitementpdoRegime')=='fagri') {
			var coefannee1 = $('coefannee1').innerHTML.split(' ');
			var valuecoefannee1 = coefannee1[0].replace(',', '.');
			valuecoefannee1 = parseFloat(valuecoefannee1)/100;
			var coefannee2 = $('coefannee2').innerHTML.split(' ');
			var valuecoefannee2 = coefannee2[0].replace(',', '.');
			valuecoefannee2 = parseFloat(valuecoefannee2)/100;
			var forfait = parseFloat($F('TraitementpdoForfait').replace(',', '.'));
			var mtaidesub = parseFloat($F('TraitementpdoMtaidesub').replace(',', '.'));

			if (!isNaN(valuecoefannee1) && !isNaN(valuecoefannee2) && !isNaN(forfait) && forfait!=0)
				mttotal += Math.round( ( forfait + ( ( forfait + ( forfait * valuecoefannee1 ) ) * valuecoefannee2 ) ) * 100 ) / 100;
			if (!isNaN(mtaidesub) && mtaidesub!=0)
				mttotal += Math.round( mtaidesub * 100 ) / 100;
		}
		else if ($F('TraitementpdoRegime')=='ragri' || $F('TraitementpdoRegime')=='reel') {
			var benefoudef = parseFloat($F('TraitementpdoBenefoudef').replace(',', '.'));
			var ammortissements = parseFloat($F('TraitementpdoAmmortissements').replace(',', '.'));
			var salaireexploitant = parseFloat($F('TraitementpdoSalaireexploitant').replace(',', '.'));
			var provisionsnonded = parseFloat($F('TraitementpdoProvisionsnonded').replace(',', '.'));
			var moinsvaluescession = parseFloat($F('TraitementpdoMoinsvaluescession').replace(',', '.'));
			var autrecorrection = parseFloat($F('TraitementpdoAutrecorrection').replace(',', '.'));

			if (!isNaN(benefoudef))
				mttotal += Math.round( ( benefoudef ) * 100 ) / 100;
			if (!isNaN(ammortissements))
				mttotal += Math.round( ( ammortissements ) * 100 ) / 100;
			if (!isNaN(salaireexploitant))
				mttotal += Math.round( ( salaireexploitant ) * 100 ) / 100;
			if (!isNaN(provisionsnonded))
				mttotal += Math.round( ( provisionsnonded ) * 100 ) / 100;
			if (!isNaN(moinsvaluescession))
				mttotal += Math.round( ( moinsvaluescession ) * 100 ) / 100;
			if (!isNaN(autrecorrection))
				mttotal += Math.round( ( autrecorrection ) * 100 ) / 100;
		}

		$('TraitementpdoMnttotalpriscompte').setValue(mttotal);
		mttotal = mttotal.toString().replace('.', ',');
		$('mnttotal').innerHTML = mttotal+' €';
		recalculrevenus();
	}

	function recalculbenefpriscompte() {
		var benefpriscompte = 0;

		if ($F('TraitementpdoRegime')=='microbic' || $F('TraitementpdoRegime')=='microbicauto') {
			var chaffvnt = parseFloat($F('TraitementpdoChaffvnt').replace(',', '.'));
			var chaffsrv = parseFloat($F('TraitementpdoChaffsrv').replace(',', '.'));
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
		else if ($F('TraitementpdoRegime')=='microbnc') {
			var chaffsrv = parseFloat($F('TraitementpdoChaffsrv').replace(',', '.'));
			var abattbncsrv = $('abattbncsrv').innerHTML.split(' ');
			var valueabattbncsrv = abattbncsrv[0].replace(',', '.');
			valueabattbncsrv = 1 - parseFloat(valueabattbncsrv)/100;

			if (!isNaN(chaffsrv) && !isNaN(valueabattbncsrv))
				benefpriscompte = Math.round( ( chaffsrv * valueabattbncsrv ) * 100 ) / 100;
		}

		$('TraitementpdoBenefpriscompte').setValue(benefpriscompte);
		benefpriscompte = benefpriscompte.toString().replace('.', ',');
		$('benefpriscompte').innerHTML = benefpriscompte + ' €';
		recalculrevenus();
	}

	function infobulle(champ) {
		var p = $('infoChaff'+champ);
		if ($F('TraitementpdoRegime')=='reel' || $F('TraitementpdoRegime')=='microbic' || $F('TraitementpdoRegime')=='microbicauto' || $F('TraitementpdoRegime')=='microbnc') {
			var valuemax = 0;
			if (champ=='srv')
				valuemax = <?php echo Configure::read( 'Traitementpdo.fichecalcul_casrvmax' ) ?>;
			else if (champ=='vnt')
				valuemax = <?php echo Configure::read( 'Traitementpdo.fichecalcul_cavntmax' ) ?>;
			if( $F('TraitementpdoChaff'+champ) > valuemax )
				p.show();
			else
				p.hide();
		}
		else
			p.hide();
	}

	function recalculrevenus() {
		var revenus = 0;

		if ($F('TraitementpdoRegime')=='fagri' || $F('TraitementpdoRegime')=='ragri' || $F('TraitementpdoRegime')=='reel') {
			var mnttotal = $('mnttotal').innerHTML.split(' ');
			var valuemnttotal = mnttotal[0].replace(',', '.');
			valuemnttotal = parseFloat(valuemnttotal);
			var nbmois = $('nbmoisactivite').innerHTML.split(' ');
			var valuenbmois = parseFloat(nbmois[0]);

			if (!isNaN(valuemnttotal) && !isNaN(valuenbmois) && valuemnttotal!=0 && valuenbmois!=0)
				revenus = Math.round( parseFloat( valuemnttotal ) / parseFloat( valuenbmois ) * 100 ) / 100;
		}
		else if ($F('TraitementpdoRegime')=='microbic' || $F('TraitementpdoRegime')=='microbicauto' || $F('TraitementpdoRegime')=='microbnc') {
			var benefpriscompte = $('benefpriscompte').innerHTML.split(' ');
			var valuebenefpriscompte = benefpriscompte[0].replace(',', '.');
			valuebenefpriscompte = parseFloat(valuebenefpriscompte);
			var nbmois = $('nbmoisactivite').innerHTML.split(' ');
			var valuenbmois = parseFloat(nbmois[0]);

			if (!isNaN(valuebenefpriscompte) && !isNaN(valuenbmois) && valuebenefpriscompte!=0 && valuenbmois!=0)
				revenus = Math.round( parseFloat( valuebenefpriscompte ) / parseFloat( valuenbmois ) * 100 ) / 100;
		}

		$('TraitementpdoRevenus').setValue(revenus);
		revenus = revenus.toString().replace('.', ',');
		$('revenus').innerHTML = revenus + ' € par mois';
	}
</script>