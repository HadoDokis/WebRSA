<?php
	echo $default2->index(
		$dossiers[$theme],
		array(
			'Passagecommissionep.chosen' => array( 'input' => 'checkbox' ),
			'Personne.qual',
			'Personne.nom',
			'Personne.prenom',
			'Personne.dtnai',
			'Adresse.locaadr',
			'Dossierep.created',/*,
			'Dossierep.themeep'*/
		),
		array(
			'cohorte' => true,
			'options' => $options,
			'hidden' => array( 'Dossierep.id', 'Passagecommissionep.id' ),
			'paginate' => Inflector::classify( $theme ),
			'actions' => array( 'Personnes::view' ),
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