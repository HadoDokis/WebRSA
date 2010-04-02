<?php echo $this->element( 'dossier_menu', array( 'id' => $dossierId) ); ?>

<div class="with_treemenu">
<h1>Ajout d'une réorientation pour <?php echo Set::classicExtract( $personne, 'Personne.qual' ).' '.Set::classicExtract( $personne, 'Personne.nom' ).' '.Set::classicExtract( $personne, 'Personne.prenom' );?></h1>
    <?php echo $javascript->link( 'dependantselect.js' ); ?>
    <script type="text/javascript">
        document.observe("dom:loaded", function() {
            dependantSelect( '<?php echo "PrecoreorientreferentStructurereferenteId";?>', '<?php echo "PrecoreorientreferentTypeorientId";?>' );
        });
    </script>

    <?php
        echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
        /*
            TODO
                Demandereorient
                    accordbenef
                Precoreorient -- Préconisation de réorientation
                    id
                    demandereorient_id
                    rolereorient (referent, equipe, conseil)
                    created (mais laisser le choix dans le formulaire)
                    typeorient_id
                    structurereferente_id
                    referent_id ?
                    accord -- null, 0, 1 ou boolean ? référent accueil, équipe, conseil
        */

        $this->data['Precoreorientreferent']['structurereferente_id'] = $this->data['Precoreorientreferent']['typeorient_id'].'_'.$this->data['Precoreorientreferent']['structurereferente_id'];

        echo $xform->create();
    ?>

    <fieldset>
        <legend>Demande de réorientation</legend>
        <?php
            echo $default->subform(
                array(
                    'Demandereorient.id' => array( 'type' => 'hidden' ),
                    'Demandereorient.personne_id' => array( 'type' => 'hidden', 'value' => $personneId ),
                    'Demandereorient.reforigine_id',
                    'Demandereorient.motifdemreorient_id',
                    'Demandereorient.commentaire',
                    'Demandereorient.accordbenef' => array( 'type' => 'checkbox' ),
                    'Demandereorient.urgent' => array( 'type' => 'checkbox' ),
//                     'Demandereorient.ep_id',
                ),
                array(
                    'options' => $options
                )
            );
        ?>
    </fieldset>

    <fieldset>
        <legend>Préconisation du référent</legend>
        <?php
        /*
            id 	demandereorient_id 	rolereorient 	typeorient_id 	structurereferente_id 	referent_id 	accord 	commentaire 	created
        */
            $step = 'referent';
            echo $default->subform(
                array(
                    "Precoreorient{$step}.id" => array( 'type' => 'hidden' ),
                    "Precoreorient{$step}.demandereorient_id" => array( 'type' => 'hidden' ),
                    "Precoreorient{$step}.rolereorient" => array( 'type' => 'hidden', 'value' => $step ),
                    "Precoreorient{$step}.typeorient_id",
                    "Precoreorient{$step}.structurereferente_id",
                    "Precoreorient{$step}.referent_id",
                    "Precoreorient{$step}.accord" => array( 'type' => 'checkbox' ),
                    "Precoreorient{$step}.commentaire",
                ),
                array(
                    'options' => $options
                )
            );
        ?>
    </fieldset>
    <?php
        echo $xform->submit( 'Enregistrer' );
        echo $xform->end();
    ?>
</div>
<div class="clearer"> <hr /></div>