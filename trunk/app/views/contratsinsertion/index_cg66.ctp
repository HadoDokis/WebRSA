<?php
    $this->pageTitle = 'CER';
    $domain = 'contratinsertion';

    echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
    echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );

?>

<?php 

?>
<div class="with_treemenu">
    <h1><?php  echo $this->pageTitle;?></h1>
        <?php if( empty( $orientstruct ) ) :?>
            <p class="error">Cette personne ne possède pas d'orientation. Impossible de créer un CER.</p>
        <?php else:?>
            <?php if( empty( $persreferent ) ) :?>
                <p class="error">Aucun référent n'est lié au parcours de cette personne.</p>
            <?php endif;?>
            <?php if( empty( $contratsinsertion ) ):?>
                <p class="notice">Cette personne ne possède pas encore de CER.</p>
            <?php endif;?>

    <!-- <?php /*if( !empty( $contratsinsertion ) ): */?> -->
    <?php /*if( !empty( $contratsinsertion ) ):*/?>
    <?php
        $block = true;
        $options['forme_ci'] = $forme_ci;
        $options['decision_ci'] = $decision_ci;

        echo $default2->index(
            $contratsinsertion,
            array(
                'Contratinsertion.forme_ci',
                'Contratinsertion.num_contrat'/* => array( 'type' => 'string', 'options' => $options['num_contrat'] )*/,
                'Contratinsertion.dd_ci',
                'Contratinsertion.df_ci',
                'Contratinsertion.date_saisi_ci',
                'Contratinsertion.decision_ci',
                'Contratinsertion.datevalidation_ci',
                'Contratinsertion.positioncer'
            ),
            array(
                'actions' => array(
                    'Contratsinsertion::valider' => array(
                        'disabled' => '( "'.$permissions->check( 'contratsinsertion', 'valider' ).'" != "1" ) || ( "#Contratinsertion.decision_ci#" == "V" ) || ( "#Contratinsertion.forme_ci#" == "S" ) || ( "#Contratinsertion.positioncer#" == "annule" )'
                    ),
                    'Contratsinsertion::view',
                    'Contratsinsertion::edit' => array( 'disabled' => '( "'.$permissions->check( 'contratsinsertion', 'edit' ).'" != "1" ) || ( "#Contratinsertion.decision_ci#" == "V" ) || ( "#Contratinsertion.forme_ci#" == "S" ) || ( "#Contratinsertion.positioncer#" == "annule" )' ),
                    'Contratsinsertion::notifop' => array(
                        'label' => 'Notification OP',
                        'url' => array( 'controller' => 'contratsinsertion', 'action'=>'notificationsop' ),
                        'disabled' => '( "'.$permissions->check( 'contratsinsertion', 'notificationsop' ).'" != "1" )  || ( "#Contratinsertion.positioncer#" == "annule" )'
                    ),
                    'Contratsinsertion::print' => array(
                        'label' => 'Imprimer',
                        'url' => array( 'controller' => 'gedooos', 'action'=>'contratinsertion' ),
                        'disabled' => '( "'.$permissions->check( 'contratsinsertion', 'print' ).'" != "1" )  || ( "#Contratinsertion.positioncer#" == "annule" )'
                    ),
                    'Contratsinsertion::cancel' => array( 'onclick' => "return confirm( 'Etes-vous sûr de vouloir annuler le CER ?' )", 'disabled' => '( "'.$permissions->check( 'contratsinsertion', 'cancel' ).'" != "1" ) ||  ( "#Contratinsertion.positioncer#" == "annule" )' ),
                ),
                'add' => array( 'Contratinsertion.add' => array( 'controller'=>'contratsinsertion', 'action'=>'add', $personne_id /*, 'disabled' =>  $block*/ ) ),
                'options' => array( 'Contratinsertion' => $options )
            )
        );
    ?>
        <!-- <table class="tooltips">
            <thead>
                <tr>
                    <th>Type Contrat</th> 
                    <th>Rang contrat</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Date de signature</th>
                    <th>Décision</th>
                    <th>Position du CER</th>
                    <th colspan="6" class="action">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php /*foreach( $contratsinsertion as $index => $contratinsertion ):?>
                    <?php
                        //   Règle de blocage du bouton modifier si le contrat est validé et que 
                        //   la date du jour est comprise dans les .... (voir webrsa.inc)

                        $dateValidation = Set::classicExtract( $contratinsertion, 'Contratinsertion.datevalidation_ci' );
                        $positioncer = Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' );


                        $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                                <tr>
                                    <th>Type de demande</th>
                                    <td>'.Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.type_demande' ), $options['type_demande'] ).'</td>
                                </tr>
                            </tbody>
                        </table>';

                        $isValid = Set::classicExtract( $contratinsertion, 'Contratinsertion.decision_ci' );
                        $block = true;
                        
                        $isSimple = Set::classicExtract( $contratinsertion, 'Contratinsertion.forme_ci' );
                        if( $isValid == 'V' && ( mktime() >= ( strtotime( $dateValidation ) + 3600 * Configure::read( 'Periode.modifiablecer.nbheure' ) ) ) ){
                            $block = false;
                        }

                        
                        //   Règle de blocage du bouton valider si le contrat est simple
                        $blockValid = true;
                        if( $isValid == 'V' ){
                            $blockValid = false;
                        }

                        // Règle de blocage du bouton valider si le contrat est simple
                        $blockImpression = false;
                        if( $isSimple == 'S' ){
                            $blockImpression = true;
                        }

                        $blockCancel = true;
                        if( $positioncer == 'annule' ){
                            $blockCancel = false;
                        }


                        echo $xhtml->tableCells(
                            array(
                                h( Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.forme_ci' ), $forme_ci ) ),
                                h( Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.num_contrat' ),  $options['num_contrat'] ) ),
                                h( date_short( isset( $contratinsertion['Contratinsertion']['dd_ci'] ) ) ? date_short( $contratinsertion['Contratinsertion']['dd_ci']  ) : null ),
                                h( date_short( isset( $contratinsertion['Contratinsertion']['df_ci'] ) ) ? date_short( $contratinsertion['Contratinsertion']['df_ci'] ) : null ),
                                h( date_short( isset( $contratinsertion['Contratinsertion']['date_saisi_ci'] ) ) ? date_short( $contratinsertion['Contratinsertion']['dd_ci'] ) : null ),
                                h( Set::enum( Set::extract( $contratinsertion, 'Contratinsertion.decision_ci' ), $decision_ci ).' '.$locale->date( 'Date::short', Set::extract( $contratinsertion, 'Contratinsertion.datevalidation_ci' ) ) ),
                                h( Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ),  $options['positioncer'] ) ),
                                $xhtml->validateLink(
                                    'Valider le CER ',
                                    array( 'controller' => 'contratsinsertion', 'action' => 'valider', $contratinsertion['Contratinsertion']['id'] ),
                                    ( $blockValid && $blockCancel )
                                ),
                                $xhtml->viewLink(
                                    'Voir le CER',
                                    array( 'controller' => 'contratsinsertion', 'action' => 'view', $contratinsertion['Contratinsertion']['id']),
                                    $permissions->check( 'contratsinsertion', 'view' )
                                ),
                                $xhtml->editLink(
                                    'Éditer le CER ',
                                    array( 'controller' => 'contratsinsertion', 'action' => 'edit', $contratinsertion['Contratinsertion']['id'] ),
                                    ( $block && $blockCancel ),
                                    $permissions->check( 'contratsinsertion', 'edit' )
                                ),
                                $xhtml->notificationsCer66Link(
                                    'Notifier à l\'organisme payeur',
                                    array( 'controller' => 'contratsinsertion', 'action' => 'notificationsop', $contratinsertion['Contratinsertion']['id'] ),
                                    ( $blockImpression && $blockCancel ),
                                    $permissions->check( 'contratsinsertion', 'notificationsop' )
                                ),
                                $xhtml->printLink(
                                    'Imprimer le CER',
                                    array( 'controller' => 'gedooos', 'action' => 'contratinsertion', $contratinsertion['Contratinsertion']['id'] ),
                                    $blockCancel,
                                    $permissions->check( 'gedooos', 'contratinsertion' )
                                ),
                                $xhtml->cancelLink(
                                    'Annuler le CER ',
                                    array( 'controller' => 'contratsinsertion', 'action' => 'cancel', $contratinsertion['Contratinsertion']['id'] ),
                                    $blockCancel,
                                    $permissions->check( 'contratsinsertion', 'cancel' )
                                ),
                                array( $innerTable, array( 'class' => 'innerTableCell noprint' ) ),

                            ),
                            array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
                            array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
                        );
                    ?>
                <?php endforeach;*/?>
            </tbody>
        </table> -->
    <?php /* endif;*/?>
    
    <?php endif;?>

</div>
<div class="clearer"><hr /></div>
