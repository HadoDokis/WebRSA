<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Recours gracieux';?>

<?php  echo $this->element( 'dossier_menu', array( 'id' => $dossier_rsa_id ) );?>


<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php if( empty( $gracieux ) ):?>
        <p class="notice">Ce dossier ne possède pas de recours gracieux.</p>

    <?php else:?>
        <!-- <p class="notice">En développement ...</p> -->
        <?php  echo $form->create( 'Indus', array( 'type' => 'post', 'url' => Router::url( null, true ) ));?>
            <h2>Généralités</h2>
                <?php echo $form->input( 'Recours.type_recours', array( 'label' => false, 'type' => 'radio', 'options' => array( 'G' => 'Gracieux', 'C' => 'Contentieux' ), 'legend' => 'Type de recours' ) ); ?>
                <?php echo $form->input( 'Recours.date_recours', array( 'label' =>  ( __( 'date_recours', true ) ), 'type' => 'date', 'dateFormat'=> 'DMY', 'maxYear'=>date('Y')+10, 'minYear'=> date('Y')-10 , 'empty' => true ) );?>
                <?php echo $form->input( 'Recours.comment', array( 'label' => 'Commentaires commission', 'type' => 'textarea', 'row' => 3 ) ); ?>

            <h2>Commission de Recours Amiable (CRA)</h2>
                <?php echo $form->input( 'Recours.date_commission', array( 'label' =>  ( __( 'date_commission', true ) ), 'type' => 'date', 'dateFormat'=> 'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true ) );?>
                <?php echo $form->input( 'Recours.typecommission', array( 'label' => __( 'typecommission', true ), 'type' => 'select', 'options' => $typecommission, 'empty' => true ) );?>
                <?php echo $form->input( 'Recours.decision', array( 'label' => __( 'decision', true ), 'type' => 'select', 'options' => $decision, 'empty' => true ) );?>
                <?php echo $form->input( 'Recours.avis', array( 'label' => __( 'avis', true ), 'type' => 'textarea', 'row' => 3 ) ); ?>

            <h2>Décision PCG</h2>
                <?php echo $form->input( 'Recours.typecommission', array( 'label' => __( 'typecommission', true ), 'type' => 'select', 'options' => $typecommission, 'empty' => true ) );?>
                <?php echo $form->input( 'Recours.date_commission', array( 'label' =>  ( __( 'date_commission', true ) ), 'type' => 'date', 'dateFormat'=> 'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true ) );?>
                <?php echo $form->input( 'Recours.decision', array( 'label' => __( 'decision', true ), 'type' => 'select', 'options' => $decision, 'empty' => true ) );?>
                <?php echo $form->input( 'Recours.motif', array( 'label' => __( 'motif', true ), 'type' => 'select', 'options' => $motif, 'empty' => true ) );?>
                <?php echo $form->input( 'Recours.avis', array( 'label' => __( 'avis', true ), 'type' => 'textarea', 'row' => 3 ) ); ?>

            <?php echo $form->submit( 'Enregistrer' );?>
        <?php echo $form->end();?>
    <?php endif;?>
</div>
<div class="clearer"><hr /></div>