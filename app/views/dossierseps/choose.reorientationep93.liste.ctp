<?php
	echo $default2->index(
		$dossiers[$theme],
		array(
			'Dossier.numdemrsa',
			'Dossier.matricule',
			'Personne.qual',
			'Personne.nom',
			'Personne.prenom',
			'Personne.dtnai',
			'Structurereferente.lib_struc',
			'Motifreorientep93.name',
			'Reorientationep93.accordaccueil' => array( 'type' => 'boolean' ),
			'Reorientationep93.accordallocataire' => array( 'type' => 'boolean' ),
			'Reorientationep93.urgent' => array( 'type' => 'boolean' ),
			'Reorientationep93.datedemande',
			'Passagecommissionep.chosen' => array( 'input' => 'checkbox' ),
		),
		array(
			'cohorte' => true,
			'options' => $options,
			'hidden' => array( 'Dossierep.id', 'Passagecommissionep.id' ),
			'paginate' => Inflector::classify( $theme ),
			'id' => $theme,
			'labelcohorte' => 'Enregistrer',
			'cohortehidden' => array( 'Choose.theme' => array( 'value' => $theme ) )
		)
	);
?>

<?php if( !empty( $dossiers[$theme]) ):?>
 <script type="text/javascript">
        function toutCocher<?php echo $theme;?>() {

            $$( '#<?php echo $theme;?> input[type="checkbox"]' ).each( function( checkbox ) {
                $( checkbox ).checked = true;
            });
        }

        function toutDecocher<?php echo $theme;?>() {
            $$( '#<?php echo $theme;?> input[type="checkbox"]' ).each( function( checkbox ) {
                $( checkbox ).checked = false;
            });
        }

        document.observe("dom:loaded", function() {
            Event.observe( 'toutCocher<?php echo $theme;?>', 'click', toutCocher<?php echo $theme;?> );
            Event.observe( 'toutDecocher<?php echo $theme;?>', 'click', toutDecocher<?php echo $theme;?> );
        });
    </script>
    <?php echo $form->button( 'Tout cocher', array( 'id' => 'toutCocher'.$theme ) );?>
    <?php echo $form->button( 'Tout décocher', array( 'id' => 'toutDecocher'.$theme ) );?>
<?php endif;?>