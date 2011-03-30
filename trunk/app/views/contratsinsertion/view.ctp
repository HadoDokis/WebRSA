<?php
    $this->pageTitle =  __d( 'contratinsertion', "Contratsinsertion::{$this->action}", true );

//     echo $this->element( 'dossier_menu', array( 'id' => $dossier_id ) );
?>
<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<div class="with_treemenu">
    <?php
        echo $xhtml->tag( 'h1', $this->pageTitle );
        echo $form->create( 'Contratsinsertionview', array( 'type' => 'post', 'id' => 'contratform', 'url' => Router::url( null, true ) ) );
// debug($contratinsertion);
        $num = Set::enum( $contratinsertion['Contratinsertion']['num_contrat'], $options['num_contrat'] );
        $duree = Set::enum( $contratinsertion['Contratinsertion']['duree_engag'], $duree_engag_cg66 );
        $forme = Set::enum( $contratinsertion['Contratinsertion']['forme_ci'], $forme_ci );

        echo $default2->view(
            $contratinsertion,
            array(
                'Personne.nom_complet' => array( 'type' => 'text' ),
                'Contratinsertion.forme_ci' => array( 'type' => 'text', 'value' => $forme  ),
                'Contratinsertion.num_contrat' => array( 'type' => 'text', 'value' => $num ),
                'Typeorient.lib_type_orient',
                'Structurereferente.lib_struc',
                'Referent.nom_complet' => array( 'type' => 'text' ),
                'Contratinsertion.num_contrat' => array( 'value' => $num ),
                'Contratinsertion.rg_ci' => array( 'type' => 'text' ),
                'Contratinsertion.sitfam_ci',
                'Contratinsertion.sitpro_ci',
                'Contratinsertion.observ_benef',
                'Contratinsertion.nature_projet',
                'Contratinsertion.engag_object',
                'Contratinsertion.duree_engag' => array( 'type' => 'text', 'value' => $duree ),
                'Contratinsertion.dd_ci',
                'Contratinsertion.df_ci',
                'Contratinsertion.lieu_saisi_ci' => array( 'type' => 'text' ),
                'Contratinsertion.date_saisi_ci',
            ),
            array( 'id' => 'vueContrat' )
        );
    ?>
</div>
    <div class="submit">
        <?php

            echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
        ?>
    </div>
    <?php echo $form->end();?>
<div class="clearer"><hr /></div>