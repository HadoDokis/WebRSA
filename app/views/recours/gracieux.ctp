<?php  $this->pageTitle = 'Recours gracieux';?>

<?php  echo $this->element( 'dossier_menu', array( 'id' => $dossier_rsa_id ) );?>


<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php if( empty( $recours ) ):?>
        <p class="notice">Ce dossier ne possède pas encore de détails sur les droits.</p>

    <?php else:?>
        <p class="notice">On va le créer</p>

    <?php endif;?>
</div>
<div class="clearer"><hr /></div>