<h1><?php echo $this->pageTitle = __d( 'nonrespectsanctionep93', "{$this->name}::{$this->action}", true );?></h1>

<?php
// debug($personnes);
	echo $default2->index(
		$personnes,
		array(
			'Historiqueetatpe.chosen' => array( 'input' => 'checkbox', 'type' => 'boolean', 'domain' => 'nonrespectsanctionep93', 'sort' => false ),
			'Personne.nom' => array( 'sort' => false ),
			'Personne.prenom',
			'Personne.dtnai',
			'Adresse.locaadr',
			'Historiqueetatpe.date',
			'Typeorient.lib_type_orient',
			'Contratinsertion.present' => array( 'type' => 'boolean' )
		),
		array(
			'cohorte' => true,
			'hidden' => array(
				'Personne.id',
				'Historiqueetatpe.id'
			),
			'paginate' => 'Personne',
			'domain' => 'nonrespectsanctionep93'
		)
	);
?>
    <script type="text/javascript">
        function toutCocher() {

            $$( 'input[type="checkbox"]' ).each( function( checkbox ) {
                $( checkbox ).checked = true;
            });
        }

        function toutDecocher() {
            $$( 'input[type="checkbox"]' ).each( function( checkbox ) {
                $( checkbox ).checked = false;
            });
        }

        document.observe("dom:loaded", function() {
            Event.observe( 'toutCocher', 'click', toutCocher );
            Event.observe( 'toutDecocher', 'click', toutDecocher );
        });
    </script>
    <?php echo $form->button( 'Tout cocher', array( 'id' => 'toutCocher' ) );?>
    <?php echo $form->button( 'Tout décocher', array( 'id' => 'toutDecocher' ) );?>