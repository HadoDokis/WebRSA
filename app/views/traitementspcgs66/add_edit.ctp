<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $xhtml->css( array( 'fileuploader' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $javascript->link( 'fileuploader.js' );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personneId ) );
?>

<div class="with_treemenu">
	<?php
		echo $xhtml->tag(
			'h1',
			$this->pageTitle = __d( 'traitementpcg66', "Traitementspcgs66::{$this->action}", true ).' '.$nompersonne
		);

		echo $xform->create( 'Traitementpcg66', array( 'id' => 'traitementpcg66form' ) );
		if( Set::check( $this->data, 'Traitementpcg66.id' ) ){
			echo $xform->input( 'Traitementpcg66.id', array( 'type' => 'hidden' ) );
		}

		echo $default2->subform(
			array(
				'Traitementpcg66.personnepcg66_id' => array( 'type' => 'hidden', 'value' => $personnepcg66Id ),
				'Traitementpcg66.personne_id' => array( 'type' => 'hidden', 'value' => $personneId ),
				'Traitementpcg66.user_id' => array( 'type' => 'hidden', 'value' => $userConnected ),
				'Traitementpcg66.clos' => array( 'type' => 'hidden', 'value' => 'N' ),
				'Traitementpcg66.annule' => array( 'type' => 'hidden', 'value' => 'N' )
			),
			array(
				'options' => $options
			)
		);
	?>

	<?php
		// Liste des motifs concernant la personne
		echo $default->subform(
			array(
				'Traitementpcg66.personnepcg66_situationpdo_id' => array( 'type' => 'select', 'options' => $listeMotifs, 'empty' => true )
			),
			array(
				'options' => $options
			)
		);
		
		// Liste des types de traitement
		echo $xform->input( 'Traitementpcg66.typetraitement', array( 'type' => 'radio', 'options' => $options['Traitementpcg66']['typetraitement'], 'legend' => 'Type de traitement', 'empty' => true ) );

	?>
    <script type="text/javascript">
    document.observe("dom:loaded", function() {

        <?php
            echo $ajax->remoteFunction(
                array(
                    'update' => 'Typecourrierpcg66Modeletypecourrierpcg66',
                    'url' => Router::url( array( 'action' => 'ajaxpiece' ), true ),
                    'with' => 'Form.serialize( $(\'traitementpcg66form\') )'
                )
            );
        ?>
    } );
    </script>
	<?php
		// Courriers
		if ( isset( $listcourrier ) && !empty( $listcourrier ) ) { ?>
		<fieldset id="filecontainer-courrier" class="noborder invisible">
			<?php
				echo $default->subform(
						array(
							'Traitementpcg66.typecourrierpcg66_id' => array( 'type' => 'select' )
						),
						array(
								'options' => $options
						)
				);
				
				echo $ajax->observeField(
					'Traitementpcg66Typecourrierpcg66Id',
					array(
						'update' => 'Typecourrierpcg66Modeletypecourrierpcg66',
						'url' => Router::url( array( 'action' => 'ajaxpiece' ), true ),
						'with' => 'Form.serialize( $(\'traitementpcg66form\') )'
					)
				);
				
				echo $xhtml->tag(
						'div',
						' ',
						array(
								'id' => 'Typecourrierpcg66Modeletypecourrierpcg66'
						)
				);
			?>
		</fieldset>
	<?php } ?>


	<script type="text/javascript">
		document.observe( "dom:loaded", function() {
			observeDisableFieldsetOnRadioValue(
				'traitementpcg66form',
				'data[Traitementpcg66][typetraitement]',
				$( 'fieldsetficheanalyse' ),
				'analyse',
				false,
				true
			);
			
			observeDisableFieldsetOnRadioValue(
				'traitementpcg66form',
				'data[Traitementpcg66][typetraitement]',
				$( 'fichecalcul' ),
				'revenu',
				false,
				true
			);
			
			observeDisableFieldsetOnRadioValue(
				'traitementpcg66form',
				'data[Traitementpcg66][typetraitement]',
				$( 'filecontainer-courrier' ),
				'courrier',
				false,
				true
			);

			
			
			//**//
			observeDisableFieldsOnRadioValue(
				'traitementpcg66form',
				'data[Traitementpcg66][typetraitement]',
				[ 'Traitementpcg66Dureeecheance' ],
				'revenu',
				false,
				true
			);
			
			observeDisableFieldsOnValue(
				'Traitementpcg66Dureeecheance',
				[
					'Traitementpcg66DateecheanceDay',
					'Traitementpcg66DateecheanceMonth',
					'Traitementpcg66DateecheanceYear'
				],
				'0',
				true
			);
			//
		} );
	</script>
<?php
		// Début fiche de calcul
		echo "<fieldset id='fichecalcul' class='noborder invisible'><table>";

			echo $default->subform(
				array(
					'Traitementpcg66.nbmoisactivite' => array( 'type' => 'hidden' ),
					'Traitementpcg66.mnttotalpriscompte' => array( 'type' => 'hidden' ),
					'Traitementpcg66.revenus' => array( 'type' => 'hidden' ),
					'Traitementpcg66.benefpriscompte' => array( 'type' => 'hidden' )
				),
				array(
					'options' => $options
				)
			);

			echo $html->tag(
				'tr',
				$html->tag(
					'td',
					$xform->_label('Traitementpcg66.regime', array('domain'=>'traitementpcg66', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpcg66.regime', array('label'=>false, 'type'=>'select', 'options'=>$options['Traitementpcg66']['regime'], 'empty'=>true))
				).
				$html->tag(
					'td',
					$xform->_label('Traitementpcg66.saisonnier', array('domain'=>'traitementpcg66'))
				).
				$html->tag(
					'td',
					$form->input('Traitementpcg66.saisonnier', array('label'=>false, 'type'=>'checkbox'))
				)
			);

			echo $html->tag(
				'tr',
				$html->tag(
					'td',
					$xform->_label('Traitementpcg66.nrmrcs', array('domain'=>'traitementpcg66', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpcg66.nrmrcs', array('label'=>false, 'type'=>'text'))
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
					$xform->_label('Traitementpcg66.dtdebutactivite', array('domain'=>'traitementpcg66', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpcg66.dtdebutactivite',
						array(
							'label'=>false,
							'type'=>'date',
							'empty'=>true,
							'dateFormat' => 'DMY',
							'minYear' => date('Y') - 4,
							'maxYear' => date('Y')
						)
					)
				).
				$html->tag(
					'td',
					$xform->_label('Traitementpcg66.raisonsocial', array('domain'=>'traitementpcg66'))
				).
				$html->tag(
					'td',
					$form->input('Traitementpcg66.raisonsocial', array('label'=>false, 'type'=>'text'))
				)
			);

			//Date de début de prise en compte = au 01-01-(n-1)
			$datedebutperiode = Set::check( $this->data, 'Traitementpcg66.dtdebutperiode' ) ? Set::extract( $this->data, 'Traitementpcg66.dtdebutperiode' ) : date("Y-01-01", strtotime("-1 year") );
			//Date de fin de prise en compte = au 31-12-(n-1)
			$datefinperiode = Set::check( $this->data, 'Traitementpcg66.datefinperiode' ) ? Set::extract( $this->data, 'Traitementpcg66.datefinperiode' ) : date("Y-12-31", strtotime("-1 year") );

			echo $html->tag(
				'tr',
				$html->tag(
					'td',
					$xform->_label('Traitementpcg66.dtdebutperiode', array('domain'=>'traitementpcg66', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpcg66.dtdebutperiode',
						array(
							'label'=>false,
							'type'=>'date',
							'dateFormat' => 'DMY',
							'minYear' => date('Y') - 4,
							'maxYear' => date('Y'),
							'empty' => true,
							'selected' => $datedebutperiode
						)
					)
				).
				$html->tag(
					'td',
					$xform->_label('Traitementpcg66.datefinperiode', array('domain'=>'traitementpcg66', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpcg66.datefinperiode',
						array(
							'label'=>false,
							'type'=>'date',
							'selected' => $datefinperiode,
							'dateFormat' => 'DMY',
							'minYear' => date('Y') - 4,
							'maxYear' => date('Y') + 4
						)
					)
				)
			);

			echo $html->tag(
				'tr',
				$html->tag(
					'td',
					$xform->_label('Traitementpcg66.nbmoisactivite', array('domain'=>'traitementpcg66'))
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
					$xform->_label('Traitementpcg66.forfait', array('domain'=>'traitementpcg66'))
				).
				$html->tag(
					'td',
					$form->input('Traitementpcg66.forfait', array('label'=>false, 'type'=>'text'))
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
					$xform->_label('Traitementpcg66.coefannee1', array('domain'=>'traitementpcg66'))
				).
				$html->tag(
					'td',
					Configure::read('Traitementpcg66.fichecalcul_coefannee1').' %',
					array(
						'id' => 'coefannee1'
					)
				).
				$html->tag(
					'td',
					$xform->_label('Traitementpcg66.aidesubvreint', array('domain'=>'traitementpcg66'))
				).
				$html->tag(
					'td',
					$default->subform(
						array(
							'Traitementpcg66.aidesubvreint' => array( 'type' => 'select', 'label' => false, 'empty' => true )
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
					$xform->_label('Traitementpcg66.coefannee2', array('domain'=>'traitementpcg66'))
				).
				$html->tag(
					'td',
					Configure::read('Traitementpcg66.fichecalcul_coefannee2').' %',
					array(
						'id' => 'coefannee2'
					)
				).
				$html->tag(
					'td',
					$xform->_label('Traitementpcg66.mtaidesub', array('domain'=>'traitementpcg66'))
				).
				$html->tag(
					'td',
					$form->input('Traitementpcg66.mtaidesub', array('label'=>false, 'type'=>'text'))
				),
				array(
					'class' => 'fagri'
				)
			);

			echo $html->tag(
				'tr',
				$html->tag(
					'td',
					$xform->_label('Traitementpcg66.chaffvnt', array('domain'=>'traitementpcg66', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpcg66.chaffvnt', array('label'=>false, 'type'=>'text')).
					$html->tag(
						'p',
						'Attention CA dépassant '.Configure::read('Traitementpcg66.fichecalcul_cavntmax').' €',
						array(
							'class' => 'notice',
							'id' => 'infoChaffvnt'
						)
					)
				).
				$html->tag(
					'td',
					$xform->_label('Traitementpcg66.abattement', array('domain'=>'traitementpcg66')),
					array(
						'class' => 'microbic microbicauto'
					)
				).
				$html->tag(
					'td',
					Configure::read('Traitementpcg66.fichecalcul_abattbicvnt').' %',
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
					$xform->_label('Traitementpcg66.chaffsrv', array('domain'=>'traitementpcg66', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpcg66.chaffsrv', array('label'=>false, 'type'=>'text')).
					$html->tag(
						'p',
						'Attention CA dépassant '.Configure::read('Traitementpcg66.fichecalcul_casrvmax').' €',
						array(
							'class' => 'notice',
							'id' => 'infoChaffsrv'
						)
					)
				).
				$html->tag(
					'td',
					$xform->_label('Traitementpcg66.abattement', array('domain'=>'traitementpcg66')),
					array(
						'class' => 'microbic microbicauto microbnc'
					)
				).
				$html->tag(
					'td',
					Configure::read('Traitementpcg66.fichecalcul_abattbicsrv').' %',
					array(
						'class' => 'microbic microbicauto',
						'id' => 'abattbicsrv'
					)
				).
				$html->tag(
					'td',
					Configure::read('Traitementpcg66.fichecalcul_abattbncsrv').' %',
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
					$xform->_label('Traitementpcg66.benefpriscompte', array('domain'=>'traitementpcg66', 'required'=>true))
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
					$xform->_label('Traitementpcg66.benefoudef', array('domain'=>'traitementpcg66', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpcg66.benefoudef', array('label'=>false, 'type'=>'text'))
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
					$xform->_label('Traitementpcg66.correction', array('domain'=>'traitementpcg66'))
				).
				$html->tag(
					'td',
					$xform->_label('Traitementpcg66.ammortissements', array('domain'=>'traitementpcg66', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpcg66.ammortissements', array('label'=>false, 'type'=>'text')),
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
					$xform->_label('Traitementpcg66.salaireexploitant', array('domain'=>'traitementpcg66'))
				).
				$html->tag(
					'td',
					$form->input('Traitementpcg66.salaireexploitant', array('label'=>false, 'type'=>'text')),
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
					$xform->_label('Traitementpcg66.provisionsnonded', array('domain'=>'traitementpcg66'))
				).
				$html->tag(
					'td',
					$form->input('Traitementpcg66.provisionsnonded', array('label'=>false, 'type'=>'text')),
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
					$xform->_label('Traitementpcg66.moinsvaluescession', array('domain'=>'traitementpcg66'))
				).
				$html->tag(
					'td',
					$form->input('Traitementpcg66.moinsvaluescession', array('label'=>false, 'type'=>'text')),
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
					$xform->_label('Traitementpcg66.autrecorrection', array('domain'=>'traitementpcg66'))
				).
				$html->tag(
					'td',
					$form->input('Traitementpcg66.autrecorrection', array('label'=>false, 'type'=>'text')),
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
					$xform->_label('Traitementpcg66.mnttotal', array('domain'=>'traitementpcg66'))
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
					$xform->_label('Traitementpcg66.revenus', array('domain'=>'traitementpcg66'))
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

			//Date de début de prise en compte = date de demande RSA
			$datepriseencompte = Set::check( $this->data, 'Traitementpcg66.dtdebutprisecompte' ) ? Set::extract( $this->data, 'Traitementpcg66.dtdebutprisecompte' ) : $dtdemrsa;
			echo $html->tag(
				'tr',
				$html->tag(
					'td',
					$xform->_label('Traitementpcg66.dtdebutprisecompte', array('domain'=>'traitementpcg66', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpcg66.dtdebutprisecompte',
						array(
							'label'=>false,
							'type'=>'date',
							'selected' => $datepriseencompte,
							'dateFormat' => 'DMY',
							'minYear' => date('Y') - 4,
							'maxYear' => date('Y') + 1
						)
					),
					array(
						'colspan' => 1
					)
				).
				$html->tag(
					'td',
					$xform->_label('Traitementpcg66.datefinprisecompte', array('domain'=>'traitementpcg66', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpcg66.datefinprisecompte',
						array(
							'label'=>false,
							'type'=>'date',
							'empty'=>true,
							'dateFormat' => 'DMY',
							'minYear' => date('Y') - 4,
							'maxYear' => date('Y') + 4
						)
					),
					array(
						'colspan' => 1
					)
				)
			);
			echo $html->tag(
				'tr',
				$html->tag(
					'td',
					$xform->_label('Traitementpcg66.dureefinprisecompte', array('domain'=>'traitementpcg66', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpcg66.dureefinprisecompte', array('label'=>false, 'type'=>'select', 'options'=>$options['Traitementpcg66']['dureefinprisecompte'], 'empty'=>true)),
					array(
						'colspan' => 1
					)
				).
				$html->tag(
					'td',
					$xform->_label('Traitementpcg66.daterevision', array('domain'=>'traitementpcg66', 'required'=>true))
				).
				$html->tag(
					'td',
					$form->input('Traitementpcg66.daterevision',
						array(
							'label'=>false,
							'type'=>'date',
							'empty'=>true,
							'dateFormat' => 'DMY',
							'minYear' => date('Y') - 4,
							'maxYear' => date('Y') + 4
						)
					),
					array(
						'colspan' => 1
					)
				)
			);

		echo "</table></fieldset>";

		// Fiche d'analyse
		echo $xhtml->tag(
			'fieldset',
			$default->subform(
				array(
					'Traitementpcg66.ficheanalyse' => array( 'type' => 'textarea' ),
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
		// Fin fiche de calcul
	?>

	<fieldset>
		<legend><?php echo required( $default2->label( 'Traitementpcg66.haspiecejointe' ) );?></legend>

		<?php echo $form->input( 'Traitementpcg66.haspiecejointe', array( 'type' => 'radio', 'options' => $options['Traitementpcg66']['haspiecejointe'], 'legend' => false, 'fieldset' => false ) );?>
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
				'traitementpcg66form',
				'data[Traitementpcg66][haspiecejointe]',
				$( 'filecontainer-piecejointe' ),
				'1',
				false,
				true
			);
		} );
	</script>

	<?php
		echo $default->subform(
			array(
				'Traitementpcg66.descriptionpdo_id' => array( 'type' => 'select' )
			),
			array(
				'options' => $options
			)
		);

		echo $xhtml->tag(
			'fieldset',
			$default->subform(
				array(
					'Traitementpcg66.datedepart' => array(  'maxYear' => date('Y') + 2, 'minYear' => date('Y' ) -2 )
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
					'Traitementpcg66.datereception' => array(  'empty' => false, 'maxYear' => date('Y') + 2, 'minYear' => date('Y' ) -2 )
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
					'Traitementpcg66.dureeecheance' => array( 'required' => true ),
					'Traitementpcg66.dateecheance' => array( 'required' => true, 'empty' => true, 'maxYear' => date('Y') + 2, 'minYear' => date('Y' ) -2 )
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
				'Traitementpcg66.descriptionpdo_id' => array( 'type'=>'string' ),
				'Traitementpcg66.datereception',
				'Traitementpcg66.datedepart',
				'Traitementpcg66.dateecheance',
				'Traitementpcg66.questionclore' => array( 'type'=>'string' )
			)
		);
		echo "<tbody>";

		foreach( $traitementspcgsouverts as $traitementpcgouvert ) {
			echo $xhtml->tag(
				'tr',
				$xhtml->tag(
					'td',
					Set::classicExtract($traitementpcgouvert, 'Descriptionpdo.name')
				).
				$xhtml->tag(
					'td',
					$locale->date( 'Date::short', Set::classicExtract($traitementpcgouvert, 'Traitementpcg66.datereception') )
				).
				$xhtml->tag(
					'td',
					$locale->date( 'Date::short', Set::classicExtract($traitementpcgouvert, 'Traitementpcg66.datedepart') )
				).
				$xhtml->tag(
					'td',
					$locale->date( 'Date::short', Set::classicExtract($traitementpcgouvert, 'Traitementpcg66.dateecheance') )
				).
				$xhtml->tag(
					'td',
					$form->input(
						'Traitementpcg66.traitmentpdoIdClore.'.Set::classicExtract($traitementpcgouvert, 'Traitementpcg66.id'),
						array(
							'type' => 'radio',
							'legend' => false,
							'options' => $options['Traitementpcg66']['clos']
						)
					)
				)
			);
		}

		echo "</tbody></table>";

		echo "<div class='submit'>";
			echo $form->submit( 'Enregistrer', array( 'div'=>false ) );
			echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div'=>false ) );
		echo "</div>";

		echo $form->end();

?>
</div>
<div class="clearer"><hr /></div>

<script type="text/javascript">
	function checkDatesToExpiration( dateDonnee, dateAChanger, operateur ) {
		var duree = $F( 'Traitementpcg66Duree'+dateDonnee ).split( '.' );
		var month = duree[0];
		var day = duree[1];
		if ( $F( 'Traitementpcg66Date'+dateDonnee+'Day' ) != "" && $F( 'Traitementpcg66Date'+dateDonnee+'Month' ) != "" && $F( 'Traitementpcg66Date'+dateDonnee+'Year' ) != "" && $F( 'Traitementpcg66Duree'+dateDonnee ) != "" ) {
			var dateDepart = new Date( $F( 'Traitementpcg66Date'+dateDonnee+'Year' ), $F( 'Traitementpcg66Date'+dateDonnee+'Month' )-1, $F( 'Traitementpcg66Date'+dateDonnee+'Day' ) );
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
			$( 'Traitementpcg66Date'+dateAChanger+'Day' ).value = ( newday < 10 ) ? '0' + newday : newday;
			$( 'Traitementpcg66Date'+dateAChanger+'Month' ).value = ( newmonth < 10 ) ? '0' + newmonth : newmonth;
			$( 'Traitementpcg66Date'+dateAChanger+'Year' ).value = newyear;
		}
	}

	function checkDatesEcheance( dateDonnee, dateAChanger, operateur ) {
		var duree = $F( 'Traitementpcg66Dureeecheance' ).split( '.' );
		var month = duree[0];
		var day = duree[1];
		if ( $F( 'Traitementpcg66Date'+dateDonnee+'Day' ) != "" && $F( 'Traitementpcg66Date'+dateDonnee+'Month' ) != "" && $F( 'Traitementpcg66Date'+dateDonnee+'Year' ) != "" && $F( 'Traitementpcg66Dureeecheance' ) != "" ) {
			var dateDepart = new Date( $F( 'Traitementpcg66Date'+dateDonnee+'Year' ), $F( 'Traitementpcg66Date'+dateDonnee+'Month' )-1, $F( 'Traitementpcg66Date'+dateDonnee+'Day' ) );
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
			$( 'Traitementpcg66DateecheanceDay' ).value = ( newday < 10 ) ? '0' + newday : newday;
			$( 'Traitementpcg66DateecheanceMonth' ).value = ( newmonth < 10 ) ? '0' + newmonth : newmonth;
			$( 'Traitementpcg66DateecheanceYear' ).value = newyear;
		}
	}

	document.observe("dom:loaded", function() {
		//Calcul automatique de la date de révision selon la date de fin de prise en compte
		[ 'Traitementpcg66DatefinprisecompteDay', 'Traitementpcg66DatefinprisecompteMonth', 'Traitementpcg66DatefinprisecompteYear', 'Traitementpcg66Dureefinprisecompte' ].each( function( id ) {
			$( id ).observe( 'change', function() {
				checkDatesToExpiration( 'finprisecompte', 'revision', '-' );
			});
		});

		//Calcul automatique de la date d'échéance selon la date de fin de prise en compte
		[ 'Traitementpcg66DatefinprisecompteDay', 'Traitementpcg66DatefinprisecompteMonth', 'Traitementpcg66DatefinprisecompteYear', 'Traitementpcg66Dureeecheance' ].each( function( id ) {
			$( id ).observe( 'change', function() {
				checkDatesEcheance( 'finprisecompte', 'echeance', '+' );
			});
		});

		<?php
			$datesreception = array();
			$datesdepart = array();
			foreach($options['Traitementpcg66']['listeDescription'] as $description) {
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
				'Traitementpcg66DescriptionpdoId',
				[
					'Traitementpcg66DatereceptionDay',
					'Traitementpcg66DatereceptionMonth',
					'Traitementpcg66DatereceptionYear'
				],
				[ '<?php echo implode( "', '", $datesreception ); ?>' ],
				true
			);
		<?php endif; ?>

		<?php if( !empty( $datesdepart ) ): ?>
			observeDisableFieldsOnValue(
				'Traitementpcg66DescriptionpdoId',
				[
					'Traitementpcg66DatedepartDay',
					'Traitementpcg66DatedepartMonth',
					'Traitementpcg66DatedepartYear'
				],
				[ '<?php echo implode( "', '", $datesdepart ); ?>' ],
				true
			);
		<?php endif; ?>

		var descriptionspdos = <?php echo php_associative_array_to_js( $descriptionspdos );?>;
		$( 'Traitementpcg66DescriptionpdoId' ).observe( 'change', function (event) {
			var descriptionpdoId = $F( 'Traitementpcg66DescriptionpdoId' );
			if ( descriptionpdoId != null ) {
				$( 'Traitementpcg66Dureeecheance' ).setValue( descriptionspdos[descriptionpdoId] );
				fireEvent( $( 'Traitementpcg66Dureeecheance' ),'change');
			}
		});

		//Calcul automatique de la date d'échéance selon la date de fin de prise en compte
		[ 'Traitementpcg66DatedepartDay', 'Traitementpcg66DatedepartMonth', 'Traitementpcg66DatedepartYear', 'Traitementpcg66Dureeecheance' ].each( function( id ) {
			$( id ).observe( 'change', function() {
				checkDatesEcheance( 'depart', 'echeance', '+' );
			});
		});

		<?php foreach ( $options['Traitementpcg66']['regime'] as $enumname => $enumvalue ): ?>
			$$('tr.<?php echo $enumname; ?>').each(function (element) {
				element.hide();
			});
			$$('td.<?php echo $enumname; ?>').each(function (element) {
				element.hide();
			});
		<?php endforeach; ?>

		$('Traitementpcg66Regime').observe( 'change', function (event) {
			loadFiche();
		});
		loadFiche();

		[ $('Traitementpcg66DtdebutperiodeDay'), $('Traitementpcg66DtdebutperiodeMonth'), $('Traitementpcg66DtdebutperiodeYear'), $('Traitementpcg66DatefinperiodeDay'), $('Traitementpcg66DatefinperiodeMonth'), $('Traitementpcg66DatefinperiodeYear') ].each( function(element) {
			element.observe( 'change', function (event) {
				recalculnbmoisactivite();
			});
		});

		[ $('Traitementpcg66Forfait'), $('Traitementpcg66Mtaidesub'), $('Traitementpcg66Benefoudef'), $('Traitementpcg66Ammortissements'), $('Traitementpcg66Salaireexploitant'), $('Traitementpcg66Provisionsnonded'), $('Traitementpcg66Moinsvaluescession'), $('Traitementpcg66Autrecorrection'),  ].each( function (element) {
			element.observe( 'change', function (event) {
				recalculmnttotal();
			});
		});

		$('Traitementpcg66Chaffvnt').observe( 'change', function (event) {
			recalculbenefpriscompte();
			// Infobulle
			infobulle('vnt');
		});
		$('Traitementpcg66Chaffsrv').observe( 'change', function (event) {
			recalculbenefpriscompte();
			// Infobulle
			infobulle('srv');
		});
	} );

	function loadFiche() {
		var value = $F('Traitementpcg66Regime');
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
		if ($F('Traitementpcg66DatefinperiodeYear') >= $F('Traitementpcg66DtdebutperiodeYear')) {
			nbmois += 12 * ($F('Traitementpcg66DatefinperiodeYear') - $F('Traitementpcg66DtdebutperiodeYear'));
			if (
				($F('Traitementpcg66DatefinperiodeMonth') >= $F('Traitementpcg66DtdebutperiodeMonth'))
				||
				(
					($F('Traitementpcg66DatefinperiodeMonth') < $F('Traitementpcg66DtdebutperiodeMonth'))
					&&
					($F('Traitementpcg66DatefinperiodeYear') > $F('Traitementpcg66DtdebutperiodeYear'))
				)
			)
				nbmois += $F('Traitementpcg66DatefinperiodeMonth') - $F('Traitementpcg66DtdebutperiodeMonth');
			if ($F('Traitementpcg66DatefinperiodeDay') > $F('Traitementpcg66DtdebutperiodeDay'))
				nbmois++;
		}
		if (nbmois < 0)
			nbmois = 0;

		$('nbmoisactivite').innerHTML = ''+nbmois+' mois';
		$('Traitementpcg66Nbmoisactivite').setValue(nbmois);
		recalculrevenus();
	}

	function recalculmnttotal() {
		var mttotal = 0;

		if ($F('Traitementpcg66Regime')=='fagri') {
			var coefannee1 = $('coefannee1').innerHTML.split(' ');
			var valuecoefannee1 = coefannee1[0].replace(',', '.');
			valuecoefannee1 = parseFloat(valuecoefannee1)/100;
			var coefannee2 = $('coefannee2').innerHTML.split(' ');
			var valuecoefannee2 = coefannee2[0].replace(',', '.');
			valuecoefannee2 = parseFloat(valuecoefannee2)/100;
			var forfait = parseFloat($F('Traitementpcg66Forfait').replace(',', '.'));
			var mtaidesub = parseFloat($F('Traitementpcg66Mtaidesub').replace(',', '.'));

			if (!isNaN(valuecoefannee1) && !isNaN(valuecoefannee2) && !isNaN(forfait) && forfait!=0)
				mttotal += Math.round( ( forfait + ( ( forfait + ( forfait * valuecoefannee1 ) ) * valuecoefannee2 ) ) * 100 ) / 100;
			if (!isNaN(mtaidesub) && mtaidesub!=0)
				mttotal += Math.round( mtaidesub * 100 ) / 100;
		}
		else if ($F('Traitementpcg66Regime')=='ragri' || $F('Traitementpcg66Regime')=='reel') {
			var benefoudef = parseFloat($F('Traitementpcg66Benefoudef').replace(',', '.'));
			var ammortissements = parseFloat($F('Traitementpcg66Ammortissements').replace(',', '.'));
			var salaireexploitant = parseFloat($F('Traitementpcg66Salaireexploitant').replace(',', '.'));
			var provisionsnonded = parseFloat($F('Traitementpcg66Provisionsnonded').replace(',', '.'));
			var moinsvaluescession = parseFloat($F('Traitementpcg66Moinsvaluescession').replace(',', '.'));
			var autrecorrection = parseFloat($F('Traitementpcg66Autrecorrection').replace(',', '.'));

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

		$('Traitementpcg66Mnttotalpriscompte').setValue(mttotal);
		mttotal = mttotal.toString().replace('.', ',');
		$('mnttotal').innerHTML = mttotal+' €';
		recalculrevenus();
	}

	function recalculbenefpriscompte() {
		var benefpriscompte = 0;

		if ($F('Traitementpcg66Regime')=='microbic' || $F('Traitementpcg66Regime')=='microbicauto') {
			var chaffvnt = parseFloat($F('Traitementpcg66Chaffvnt').replace(',', '.'));
			var chaffsrv = parseFloat($F('Traitementpcg66Chaffsrv').replace(',', '.'));
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
		else if ($F('Traitementpcg66Regime')=='microbnc') {
			var chaffsrv = parseFloat($F('Traitementpcg66Chaffsrv').replace(',', '.'));
			var abattbncsrv = $('abattbncsrv').innerHTML.split(' ');
			var valueabattbncsrv = abattbncsrv[0].replace(',', '.');
			valueabattbncsrv = 1 - parseFloat(valueabattbncsrv)/100;

			if (!isNaN(chaffsrv) && !isNaN(valueabattbncsrv))
				benefpriscompte = Math.round( ( chaffsrv * valueabattbncsrv ) * 100 ) / 100;
		}

		$('Traitementpcg66Benefpriscompte').setValue(benefpriscompte);
		benefpriscompte = benefpriscompte.toString().replace('.', ',');
		$('benefpriscompte').innerHTML = benefpriscompte + ' €';
		recalculrevenus();
	}

	function infobulle(champ) {
		var p = $('infoChaff'+champ);
		if ($F('Traitementpcg66Regime')=='reel' || $F('Traitementpcg66Regime')=='microbic' || $F('Traitementpcg66Regime')=='microbicauto' || $F('Traitementpcg66Regime')=='microbnc') {
			var valuemax = 0;
			if (champ=='srv')
				valuemax = <?php echo Configure::read( 'Traitementpcg66.fichecalcul_casrvmax' ) ?>;
			else if (champ=='vnt')
				valuemax = <?php echo Configure::read( 'Traitementpcg66.fichecalcul_cavntmax' ) ?>;
			if( $F('Traitementpcg66Chaff'+champ) > valuemax )
				p.show();
			else
				p.hide();
		}
		else
			p.hide();
	}

	function recalculrevenus() {
		var revenus = 0;

		if ($F('Traitementpcg66Regime')=='fagri' || $F('Traitementpcg66Regime')=='ragri' || $F('Traitementpcg66Regime')=='reel') {
			var mnttotal = $('mnttotal').innerHTML.split(' ');
			var valuemnttotal = mnttotal[0].replace(',', '.');
			valuemnttotal = parseFloat(valuemnttotal);
			var nbmois = $('nbmoisactivite').innerHTML.split(' ');
			var valuenbmois = parseFloat(nbmois[0]);

			if (!isNaN(valuemnttotal) && !isNaN(valuenbmois) && valuemnttotal!=0 && valuenbmois!=0)
				revenus = Math.round( parseFloat( valuemnttotal ) / parseFloat( valuenbmois ) * 100 ) / 100;
		}
		else if ($F('Traitementpcg66Regime')=='microbic' || $F('Traitementpcg66Regime')=='microbicauto' || $F('Traitementpcg66Regime')=='microbnc') {
			var benefpriscompte = $('benefpriscompte').innerHTML.split(' ');
			var valuebenefpriscompte = benefpriscompte[0].replace(',', '.');
			valuebenefpriscompte = parseFloat(valuebenefpriscompte);
			var nbmois = $('nbmoisactivite').innerHTML.split(' ');
			var valuenbmois = parseFloat(nbmois[0]);

			if (!isNaN(valuebenefpriscompte) && !isNaN(valuenbmois) && valuebenefpriscompte!=0 && valuenbmois!=0)
				revenus = Math.round( parseFloat( valuebenefpriscompte ) / parseFloat( valuenbmois ) * 100 ) / 100;
		}

		$('Traitementpcg66Revenus').setValue(revenus);
		revenus = revenus.toString().replace('.', ',');
		$('revenus').innerHTML = revenus + ' € par mois';
	}
</script>