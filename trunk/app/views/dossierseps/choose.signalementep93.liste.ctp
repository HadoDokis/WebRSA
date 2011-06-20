<?php
	$duree_engag = 'duree_engag_'.Configure::read( 'nom_form_ci_cg' );
	foreach( $dossiers[$theme] as &$dossierep ) {
		$dossierep['Contratinsertion']['duree_engag'] = Set::enum( $dossierep['Contratinsertion']['duree_engag'], $$duree_engag );
	}

	echo $default2->index(
		$dossiers[$theme],
		array(
			'Dossier.numdemrsa',
			'Adresse.locaadr',
			'Personne.qual',
			'Personne.nom',
			'Personne.prenom',
			'Contratinsertion.num_contrat',
			'Contratinsertion.dd_ci',
			'Contratinsertion.duree_engag',
			'Contratinsertion.df_ci',
			'Structurereferente.lib_struc',
			'Contratinsertion.nature_projet',
			'Contratinsertion.type_demande',
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