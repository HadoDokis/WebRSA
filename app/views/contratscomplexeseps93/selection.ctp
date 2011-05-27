<h1><?php echo $this->pageTitle = __d( 'contratcomplexeep93', "{$this->name}::{$this->action}", true );?></h1>

<?php
// debug($contratsinsertion);
	echo $default2->index(
		$contratsinsertion,
		array(
			'Contratinsertion.chosen' => array( 'input' => 'checkbox', 'type' => 'boolean', 'domain' => 'contratcomplexeep93', 'sort' => false ),
			'Personne.nom',
			'Personne.prenom',
			'Personne.dtnai',
			'Contratinsertion.dd_ci',
			'Contratinsertion.df_ci',
		),
		array(
			'cohorte' => true,
			'hidden' => array(
				'Personne.id',
				'Contratinsertion.id'
			),
			'paginate' => 'Contratinsertion',
			'domain' => 'contratcomplexeep93',
			'labelcohorte' => 'Enregistrer'
		)
	);

// 	debug( $contratsinsertion );
?>
<?php if( !empty( $contratsinsertion) ):?>

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
<?php endif;?>