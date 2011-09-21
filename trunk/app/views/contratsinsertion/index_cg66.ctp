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
    <?php endif;?>
	<?php
        $block = false;
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
                        'disabled' => '( "'.$permissions->check( 'contratsinsertion', 'valider' ).'" != "1" ) || ( "#Contratinsertion.decision_ci#" == "V" ) || ( "#Contratinsertion.forme_ci#" == "S" ) || ( "#Contratinsertion.positioncer#" == "annule" ) || ( "#Contratinsertion.positioncer#" == "fincontrat" )'
                    ),
                    'Contratsinsertion::view',
                    'Contratsinsertion::edit' => array( 'disabled' => '( "'.$permissions->check( 'contratsinsertion', 'edit' ).'" != "1" ) || ( "#Contratinsertion.decision_ci#" == "V" ) || ( "#Contratinsertion.forme_ci#" == "S" ) || ( "#Contratinsertion.positioncer#" == "annule" ) || ( "#Contratinsertion.positioncer#" == "fincontrat" )' ),
                    'Contratsinsertion::notifop' => array(
                        'label' => 'Notification OP',
                        'url' => array( 'controller' => 'contratsinsertion', 'action'=>'notificationsop' ),
                        'disabled' => '( "'.$permissions->check( 'contratsinsertion', 'notificationsop' ).'" != "1" )  || ( "#Contratinsertion.positioncer#" == "annule" ) || ( "#Contratinsertion.positioncer#" == "fincontrat" )'
                    ),
                    'Contratsinsertion::print' => array(
                        'label' => 'Imprimer',
                        'url' => array( 'controller' => 'gedooos', 'action'=>'contratinsertion' ),
                        'disabled' => '( "'.$permissions->check( 'contratsinsertion', 'print' ).'" != "1" )  || ( "#Contratinsertion.positioncer#" == "annule" ) || ( "#Contratinsertion.positioncer#" == "fincontrat" )'
                    ),
                    'Contratsinsertion::cancel' => array( 'onclick' => "return confirm( 'Etes-vous sûr de vouloir annuler le CER ?' )", 'disabled' => '( "'.$permissions->check( 'contratsinsertion', 'cancel' ).'" != "1" ) ||  ( "#Contratinsertion.positioncer#" == "annule" ) || ( "#Contratinsertion.positioncer#" == "fincontrat" )' ),
                    'Contratsinsertion::filelink' => array( 'disabled' => '( "'.$permissions->check( 'contratsinsertion', 'cancel' ).'" != "1" )/* || ( "#Contratinsertion.positioncer#" == "fincontrat" )*/' )
                ),
                'add' => array( 'Contratinsertion.add' => array( 'controller'=>'contratsinsertion', 'action'=>'add', $personne_id , 'disabled' =>  $block ) ),
                'options' => array( 'Contratinsertion' => $options )
            )
        );
    ?>   

</div>
<div class="clearer"><hr /></div>
