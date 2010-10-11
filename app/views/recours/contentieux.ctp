<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Recours contentieux';?>

<?php  echo $this->element( 'dossier_menu', array( 'id' => $dossier_id ) );?>


<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php if( empty( $contentieux ) ):?>
        <p class="notice">Ce dossier ne possède pas de recours contentieux.</p>

    <?php else:?>
        <!-- <p class="notice">En développement ...</p> -->
        <?php  echo $form->create( 'Indus', array( 'type' => 'post', 'url' => Router::url( null, true ) ));?>
            <h2>Généralités</h2>
                <?php echo $form->input( 'Recours.type_recours', array( 'label' => false, 'type' => 'radio', 'options' => array( 'G' => 'Gracieux', 'C' => 'Contentieux' ), 'legend' => 'Type de recours' ) ); ?>
                <?php echo $form->input( 'Recours.date_recours', array( 'label' =>  ( __( 'date_recours', true ) ), 'type' => 'date', 'dateFormat'=> 'DMY', 'maxYear'=>date('Y')+10, 'minYear'=> date('Y')-10 , 'empty' => true ) );?>
                <?php echo $form->input( 'Recours.comment', array( 'label' => 'Commentaires contentieux', 'type' => 'textarea' ) ); ?>

            <h2>Décision tribunal administratif</h2>
                <?php echo $form->input( 'Recours.date_commission', array( 'label' =>  ( __( 'date_commission', true ) ), 'type' => 'date', 'dateFormat'=> 'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true ) );?>
                <?php echo $form->input( 'Recours.decision', array( 'label' => __( 'decision', true ), 'type' => 'select', 'options' => $decisionrecours, 'empty' => true ) );?>
                <?php echo $form->input( 'Recours.motif', array( 'label' => __( 'motif', true ), 'type' => 'select', 'options' => $motifrecours, 'empty' => true ) );?>
                <?php echo $form->input( 'Recours.avis', array( 'label' => 'Avis tribunal administratif', 'type' => 'textarea' ) ); ?>
        <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>


<hr />


    <h2>Liste des pièces</h2>
    <table>
        <thead>
            <tr>
                <th>Type de la pièce</th>
                <th>Date d'enregistrement</th>
                <th class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                echo $html->tableCells(
                    array(
                        h( $contentieux['Infofinanciere']['id'] ),
                        h( $contentieux['Infofinanciere']['id'] ),
                        $html->viewLink(
                            'Voir le document',
                            array( 'controller' => 'recours', 'action' => 'contentieux', $contentieux['Infofinanciere']['id'] )
                        ),
                    )
                );
            ?>
        </tbody>
    </table>

    <?php endif;?>

</div>
<div class="clearer"><hr /></div>