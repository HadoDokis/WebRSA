<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    $this->pageTitle = 'APREs à valider';
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
    echo '<ul class="actionMenu"><li>'.$xhtml->link(
        $xhtml->image(
            'icons/application_form_magnify.png',
            array( 'alt' => '' )
        ).' Formulaire',
        '#',
        array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
    ).'</li></ul>';
?>

<?php echo $xform->create( 'Cohortevalidationapre66', array( 'type' => 'post', 'action' => 'apresavalider', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>


        <fieldset>
			<?php echo $xform->input( 'Search.active', array( 'type' => 'hidden', 'value' => true ) );?>

            <?php

/* echo $xform->input( 'Cohortevalidationapre66.apresavalider', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );*/?>

            <legend>Filtrer par APRE</legend>
            <?php

                echo $default2->subform(
                    array(
                        'Search.Apre.numeroapre' => array( 'label' => __d( 'apre', 'Apre.numeroapre', true ), 'type' => 'text' ),
                        'Search.Apre.referent_id' => array( 'label' => __d( 'apre', 'Apre.referent_id', true ), 'options' => $referents ),
                        'Search.Personne.nom' => array( 'label' => __d( 'personne', 'Personne.nom', true ), 'type' => 'text' ),
                        'Search.Personne.prenom' => array( 'label' => __d( 'personne', 'Personne.prenom', true ), 'type' => 'text' ),
                        'Search.Personne.nomnai' => array( 'label' => __d( 'personne', 'Personne.nomnai', true ), 'type' => 'text' ),
                        'Search.Personne.nir' => array( 'label' => __d( 'personne', 'Personne.nir', true ), 'type' => 'text', 'maxlength' => 15 ),
                        'Search.Dossier.matricule' => array( 'label' => __d( 'dossier', 'Dossier.matricule', true ), 'type' => 'text', 'maxlength' => 15 ),
                        'Search.Dossier.numdemrsa' => array( 'label' => __d( 'dossier', 'Dossier.numdemrsa', true ), 'type' => 'text', 'maxlength' => 15 ),

                    ),
                    array(
                        'options' => $options
                    )
                );
            ?>
        </fieldset>

    <div class="submit noprint">
        <?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>

<?php echo $xform->end();?>
<?php $pagination = $xpaginator->paginationBlock( 'Apre', $this->passedArgs ); ?>
<?php echo $pagination;?>
<?php if( isset( $cohortevalidationapre66 ) ):?>
    <?php if( is_array( $cohortevalidationapre66 ) && count( $cohortevalidationapre66 ) > 0  ):?>
        <?php echo $form->create( 'ValidationApre', array( 'url'=> Router::url( null, true ) ) );?>
		<?php
			foreach( Set::flatten( $this->data['Search'] ) as $filtre => $value  ) {
				echo $form->input( "Search.{$filtre}", array( 'type' => 'hidden', 'value' => $value ) );
			}
		?>
    <table id="searchResults" class="tooltips">
        <thead>
            <tr>
                <th>N° Dossier</th>
                <th>Nom de l'allocataire</th>
                <th>Commune de l'allocataire</th>
                <th>Date demande APRE</th>
                <th>Montant proposé</th>
                <th>Décision APRE</th>
                <th>Montant accordé</th>
                <th>Motif du rejet</th>
                <th>Date de la décision</th>
                <th class="action">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach( $cohortevalidationapre66 as $index => $validationapre ):?>
            <?php
// debug($validationapre);
                    $title = $validationapre['Dossier']['numdemrsa'];

                    $array1 = array(
                        h( $validationapre['Dossier']['numdemrsa'] ),
                        h( $validationapre['Personne']['nom'].' '.$validationapre['Personne']['prenom'] ),
                        h( $validationapre['Adresse']['locaadr'] ),
                        h( date_short( $validationapre['Aideapre66']['datedemande'] ) ),
                        h( $validationapre['Aideapre66']['montantpropose'] ),
                    );

                    $array2 = array(
                        $form->input( 'Apre.'.$index.'.id', array( 'label' => false, 'type' => 'hidden', 'value' => $validationapre['Apre']['id'] ) ).
                        $form->input( 'Apre.'.$index.'.personne_id', array( 'label' => false, 'type' => 'hidden', 'value' => $validationapre['Apre']['personne_id'] ) ).
                        $form->input( 'Apre.'.$index.'.dossier_id', array( 'label' => false, 'type' => 'hidden', 'value' => $validationapre['Dossier']['id'] ) ).
                        $form->input( 'Apre.'.$index.'.isdecision', array( 'label' => false, 'type' => 'hidden', 'value' => $validationapre['Apre']['isdecision'] ) ).
                        $form->input( 'Apre.'.$index.'.etatdossierapre', array( 'label' => false, 'type' => 'hidden', 'value' => $validationapre['Apre']['etatdossierapre'] ) ).
                        $form->input( 'Aideapre66.'.$index.'.id', array( 'label' => false, 'type' => 'hidden', 'value' => $validationapre['Aideapre66']['id'] ) ).
                        $form->input( 'Aideapre66.'.$index.'.apre_id', array( 'label' => false, 'type' => 'hidden', 'value' => $validationapre['Aideapre66']['apre_id'] ) ).
                        $form->input( 'Aideapre66.'.$index.'.decisionapre', array( 'label' => false, 'empty' => true, 'type' => 'select', 'options' => $optionsaideapre66['decisionapre'], 'value' => $validationapre['Aideapre66']['decisionapre'] ) ),

                        $form->input( 'Aideapre66.'.$index.'.montantaccorde', array( 'label' => false, 'type' => 'text', 'value' => $validationapre['Aideapre66']['montantaccorde'] ) ),

                        $form->input( 'Aideapre66.'.$index.'.motifrejetequipe', array( 'label' => false, 'type' => 'text', 'rows' => 2, 'value' => $validationapre['Aideapre66']['motifrejetequipe'] ) ),

                        $form->input( 'Aideapre66.'.$index.'.datemontantaccorde', array( 'label' => false, /*'empty' => true,*/  'type' => 'date', 'dateFormat' => 'DMY', 'selected' => $validationapre['Aideapre66']['proposition_datemontantaccorde'] ) ),


                        $xhtml->viewLink(
                            'Voir le contrat « '.$title.' »',
                            array( 'controller' => 'apres66', 'action' => 'index', $validationapre['Apre']['personne_id'] )
                        )/*,
                        array( $innerTable, array( 'class' => 'innerTableCell' ) )*/
                    );

                    echo $xhtml->tableCells(
                        Set::merge( $array1, $array2 ),
                        array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
                        array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
                    );
                ?>
            <?php endforeach;?>
        </tbody>
    </table>
    <?php echo $pagination;?>
    <?php echo $form->submit( 'Validation de la liste' );?>
<?php echo $form->end();?>


    <?php else:?>
        <p class="notice">Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>
<?php endif?>

<?php if( isset( $cohortevalidationapre66 ) ):?>
    <script type="text/javascript">
        <?php foreach( $cohortevalidationapre66 as $index => $validationapre ):?>


            observeDisableFieldsOnValue(
                'Aideapre66<?php echo $index;?>Decisionapre',
                [
                    'Aideapre66<?php echo $index;?>Montantaccorde'
                ],
                'REF',
                true
            );
            //Données pour le type d'activité du bénéficiare
            observeDisableFieldsOnValue(
                'Aideapre66<?php echo $index;?>Decisionapre',
                [
                    'Aideapre66<?php echo $index;?>Montantaccorde'
                ],
                'ACC',
                false
            );
            //Données pour le type d'activité du bénéficiare
            observeDisableFieldsOnValue(
                'Aideapre66<?php echo $index;?>Decisionapre',
                [
                    'Aideapre66<?php echo $index;?>Motifrejetequipe'
                ],
                'ACC',
                true
            );
            observeDisableFieldsOnValue(
                'Aideapre66<?php echo $index;?>Decisionapre',
                [
                    'Aideapre66<?php echo $index;?>Motifrejetequipe'
                ],
                'REF',
                false
            );

        <?php endforeach;?>
    </script>
<?php endif;?>