<?php
    $domain = 'pdo';
    echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
?>

<?php  $this->pageTitle = 'Validation PDO';?>

<?php
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
                    'Propopdo.typepdo_id' => array( 'label' =>  required( __d( 'propopdo', 'Propopdo.typepdo_id', true ) ), 'type' => 'select', 'options' => $typepdo, 'empty' => true ),
                    'Propopdo.datereceptionpdo' => array( 'label' =>  ( __( 'Date de réception du dossier', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => false ),
                     //'Propopdo.choixpdo' => array( 'label' =>  ( __( 'Choix', true ) ), 'type' => 'radio', 'options' => $options['choixpdo'], 'empty' => true ),
                    'Propopdo.originepdo_id' => array( 'label' =>  required( __( 'Origine', true ) ), 'type' => 'select', 'options' => $originepdo, 'empty' => true ),
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
        <!--<legend>Prise de décision</legend>-->
        <?php
            echo $default->subform(
                array(
                    'Propopdo.user_id' => array( 'label' =>  'Gestionnaire du dossier (instructeur en charge du dossier)', 'type' => 'select', 'options' => $gestionnaire )
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
//                    'Propopdo.iscomplet' => array( 'legend' => false, 'type' => 'radio', 'options' => $options['iscomplet'] )
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );

            echo $default->subform(
                array(
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

    <!--<fieldset>
        <?php
            echo $form->input( 'Propopdo.decision', array( 'label' => 'Décision', 'type' => 'checkbox' ) );
        ?>
        <fieldset id="Decision" class="invisible">
            <?php
                echo $default->subform(
                    array(
                        'Propopdo.datedecisionpdo' => array( 'label' =>  ( __( 'Date de décision de la PDO', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => false ),
                        'Propopdo.decisionpdo_id' => array( 'label' =>  ( __( 'Décision du Conseil Général', true ) ), 'type' => 'select', 'options' => $decisionpdo, 'empty' => true ),
                        //'Propopdo.dateenvoiop' => array( 'label' =>  ( __( 'Date d\'envoi à l\'OP', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => false ),
                        //'Propopdo.motifpdo' => array( 'label' =>  ( __( 'Motif de la décision', true ) ), 'type' => 'radio', 'options' => $motifpdo, 'empty' => true )
                    ),
                    array(
                        'domain' => $domain,
                        'options' => $options
                    )
                );
            ?>
                <fieldset id="nonadmis" class="invisible">
                    <?php
                        echo $default->subform(
                            array(
                                'Propopdo.nonadmis' => array( 'label' => 'Raison non admissible', 'type' => 'select', 'options' => $options['nonadmis'], 'empty' => true  )
                            ),
                            array(
                                'domain' => $domain,
                                'options' => $options
                            )
                        );
                    ?>
                </fieldset>
            <?php
                echo $default->subform(
                    array(

                        'Propopdo.commentairepdo' => array( 'label' =>  'Observation : ', 'type' => 'textarea', 'rows' => 3 ),
                     ),
                    array(
                        'domain' => $domain,
                        'options' => $options
                    )
                );
                echo $ajax->observeField( 'PropopdoDecisionpdoId', array( 'update' => 'Etatpdo4', 'url' => Router::url( array( 'action' => 'ajaxetat4' ), true ) ) );
            ?>
        </fieldset>

    </fieldset>
    
    <fieldset>
        <?php
            echo $form->input( 'Propopdo.isvalidation', array( 'label' => 'Validation', 'type' => 'checkbox' ) );
        ?>
        <fieldset id="Isvalidation" class="invisible">
            <?php
                echo $default->subform(
                    array(
                        'Propopdo.validationdecision' => array( 'label' =>  ( __d( 'propopdo', 'Propopdo.validationdecision', true ) ), 'type' => 'radio', 'options' => $options['validationdecision'] ),
                        'Propopdo.datevalidationdecision' => array( 'label' =>  ( __d( 'propopdo', 'Propopdo.datevalidationdecision', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear' => date('Y')+5, 'minYear' => date('Y')-1, 'empty' => false )
                    ),
                    array(
                        'domain' => $domain,
                        'options' => $options
                    )
                );
                echo $ajax->observeField( 'PropopdoIsvalidation', array( 'update' => 'Etatpdo3', 'url' => Router::url( array( 'action' => 'ajaxetat3' ), true ) ) );
            ?>
        </fieldset>
    </fieldset>

    <fieldset>
        <?php
            echo $form->input( 'Propopdo.isdecisionop', array( 'label' => 'Décison de l\'OP', 'type' => 'checkbox' ) );
        ?>
        <fieldset id="Decisionop" class="invisible">
        <?php
            echo $default->subform(
                array(
                    'Propopdo.decisionop' => array( 'legend' => false, 'type' => 'radio', 'options' => $options['decisionop'] ),
                    'Propopdo.datedecisionop' => array( 'label' =>  ( __d( 'propopdo', 'Propopdo.datedecisionop', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear' => date('Y')+5, 'minYear' => date('Y')-1, 'empty' => false ),
                    'Propopdo.observationop' => array( 'label' => __d( 'propopdo', 'Propopdo.observationop', true ), 'type' => 'textarea', 'rows' => 3 )
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );
            echo $ajax->observeField( 'PropopdoIsdecisionop', array( 'update' => 'Etatpdo5', 'url' => Router::url( array( 'action' => 'ajaxetat5' ), true ) ) );
        ?>
        </fieldset>
    </fieldset>

    <fieldset>
        <?php
            echo $form->input( 'Propopdo.suivi', array( 'label' => 'Suivi', 'type' => 'checkbox' ) );
        ?>
        <fieldset id="Suivi" class="invisible">
        <?php
            echo $default->subform(
                array(
                    'Propopdo.daterevision' => array( 'label' =>  ( __( 'Date de révision', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => false ),
                    'Propopdo.dateecheance' => array( 'label' =>  ( __( 'Date d\'échéance (date à laquelle on doit reprendre une décision)', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear' => date('Y')+5, 'minYear' => date('Y')-1, 'empty' => false )
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );
            
        	//echo $ajax->observeForm( 'propopdoform', array( 'update' => 'Etatpdo6', 'url' => Router::url( array( 'action' => 'ajaxetatpdo' ), true ) ) );

        ?>
        </fieldset>
    </fieldset>-->
    
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
				        'Traitementtypepdo.name'
				    ),
				    array(
				        'actions' => array(
				            'Traitementspdos::edit',
				            'Traitementspdos::clore' => array( 'disabled' => '\'#Traitementpdo.clos#\' != 0' ),
				            'Traitementspdos::delete'
				        ),
				        'add' => array( 'Traitementpdo.add' => array( 'controller'=>'traitementspdos', 'action'=>'add', $pdo_id ) ),
				        'options' => $options
				    )
				)
		    );
		    
		    echo $html->tag(
    			'fieldset',
    			$html->tag(
    				'legend',
    				'Décisions'
    			).
				$default2->index(
				    $decisionspropospdos,
				    array(
				        'Decisionpropopdo.datedecisionpdo',
				        'Decisionpdo.libelle',
				        'Decisionpropopdo.validationdecision',
				        'Decisionpropopdo.datevalidationdecision',
				        'Decisionpropopdo.etatdossierpdo'
				    ),
				    array(
				        'actions' => array(
				            'Decisionspropospdos::edit'
				        ),
				        'add' => array( 'Decisionpropopdo.add' => array( 'controller'=>'decisionspropospdos', 'action'=>'add', $pdo_id ) ),
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
