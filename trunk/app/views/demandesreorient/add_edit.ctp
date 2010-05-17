<?php echo $this->element( 'dossier_menu', array( 'personne_id' => ${Inflector::variable( 'personne_id' )} ) ); ?>

<div class="with_treemenu">
	<?php
		if( $this->action == 'add' ) {
			$titleMessage = "Ajout d'une réorientation pour %s %s %s";
		}
		else {
			$titleMessage = "Modification de la réorientation de %s %s %s";
		}

		$this->pageTitle = sprintf(
			$titleMessage,
			Set::classicExtract( $personne, 'Personne.qual' ),
			Set::classicExtract( $personne, 'Personne.nom' ),
			Set::classicExtract( $personne, 'Personne.prenom' )
		);
	?>

	<h1><?php echo $this->pageTitle;?></h1>

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

	<?php
		echo $default->view(
			Set::merge( $orientstruct, $referentOrigine ),
			array(
				'Structurereferente.lib_struc',
				'Typeorient.lib_type_orient',
				'Referent.nom_complet',
				'Referent.fonction',
				'Referent.email' => array( 'type' => 'email' ),
				'Referent.numero_poste' => array( 'type' => 'phone' ),
			),
			array(
				'widget' => 'table'
			)
		);

		echo $default->view(
			$personne,
			array(
				'Personne.nom_complet',
				'Personne.dtnai',
				'Adresse.localite',
				'Dossier.matricule',
				'Personne.idassedic',
			),
			array(
				'widget' => 'table'
			)
		);
	?>

    <fieldset>
        <legend>Demande de réorientation</legend>
        <?php
			$step = 'referent';
            echo $default->subform(
                array(
                    'Demandereorient.id' => array( 'type' => 'hidden' ),
                    'Demandereorient.personne_id' => array( 'type' => 'hidden', 'value' => $personneId ),
                    'Demandereorient.reforigine_id' => array( 'type' => 'hidden', 'value' => Set::classicExtract( $referentOrigine, 'Referent.id' ) ),
                    'Demandereorient.dtprementretien', // FIXME: default aujourd'hui
                    'Demandereorient.motifdemreorient_id',
/*                    'Demandereorient.commentaire',
                    'Demandereorient.accordbenef' => array( 'type' => 'checkbox' ),
                    'Demandereorient.urgent' => array( 'type' => 'checkbox' ),*/
//                     'Demandereorient.ep_id',
                    "Precoreorient{$step}.id" => array( 'type' => 'hidden' ),
                    "Precoreorient{$step}.demandereorient_id" => array( 'type' => 'hidden' ),
                    "Demandereorient.orientstruct_id" => array( 'type' => 'hidden', 'value' => Set::classicExtract( $orientstruct, 'Orientstruct.id' ) ),
                    "Precoreorient{$step}.rolereorient" => array( 'type' => 'hidden', 'value' => $step ),
                    "Precoreorient{$step}.typeorient_id",
                    "Precoreorient{$step}.structurereferente_id" => array( 'value' => $this->data['Precoreorientreferent']['structurereferente_id'] ),
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
					"Precoreorient{$step}.dtconcertation",
                    "Precoreorient{$step}.accord" => array( 'type' => 'checkbox' ),
					'Demandereorient.accordbenef' => array( 'type' => 'checkbox' ), // FIXME
					'Demandereorient.urgent' => array( 'type' => 'checkbox' ),
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

<!--<?php debug( Set::merge( $orientstruct, $referentOrigine ) );?>
<?php debug( $this->data );?>
<?php debug( $this->viewVars );?>-->