<?php
	$themes = array(
		'demandesreorient' => Set::classicExtract( $seanceep, 'Seanceep.demandesreorient' )
	);
?>

<h1>Ordre du jour</h1>

<?php echo $xform->create();?>

<div id="tabbedWrapper" class="tabs">
	<?php foreach( array_keys( $themes ) as $theme ):?>
		<?php if( !empty( $themes[$theme] ) && ( $themes[$theme] != 'nontraite' ) ):?>
			<?php $modelTheme = Inflector::classify( $theme );?>
			<div id="<?php echo $theme;?>">
				<h2 class="title"><?php echo __d( Inflector::underscore( $modelTheme ), Inflector::camelize( $theme ).'::ordre', true );?></h2><!-- FIXME -->
				<?php
					$i = 0;
					$total = 0;
					$cells = array();

					foreach( $this->data[$modelTheme] as $i => $dossier ) {
						$cells[] = array(
							$dossier['locaadr'],
							$xform->input( "{$modelTheme}.{$i}.numcomptt", array( 'type' => 'hidden' ) ).
							$xform->input( "{$modelTheme}.{$i}.limit", array( 'type' => 'text', 'div' => false, 'label' => false ) )
						);
						$i++;
						$total += $dossier['limit'];
					}

					echo $html->tag(
						'table',
						$html->tableCells( $cells )."<tr><th>Total</th><td id=\"cellTotal{$modelTheme}\">{$total}</td></tr>",
						array( 'id' => "table{$modelTheme}" )
					);
					// FIXME
					echo $xform->input( "Seanceep.nombretotal", array( 'type' => 'text', 'value' => $total, 'id' => "SeanceepNombretotal{$modelTheme}" ) );
				?>
			</div>
		<?php endif;?>
	<?php endforeach;?>
</div>
<?php
	echo $xform->submit( 'Enregistrer' );
	echo $xform->end();
?>

<script type="text/javascript">
	var originalTotal = new Array();
	var originalValues = new Array();
// 	var chosenValues = new Array();

	/*
	*
	*/

	function getTotal( path ) {
		var total = 0;
		var inputs = $$( path );
		$( inputs ).each( function ( input ) {
			total += parseInt( $F( input ) );
		} );
		return total;
	}

	/*
	*
	*/

	function findValue( object, name ) {
		var retVal = undefined;
		object.each( function ( item ) {
			if( item.key == name ) {
				if( item.value != NaN ) {
					retVal = parseInt( item.value );
				}
			}
		} );
		return retVal;
	}

	/*
	*
	*/

	function findKey( object, name ) {
		var retVal = NaN;
		var i = 0;
		object.each( function ( item ) {
			if( item.key == name ) {
				if( item.value != NaN ) {
					retVal = i;
				}
			}
			i++;
		} );
		return retVal;
	}

	/*
	*
	*/

	function recompute( theme, path, oldTotal, newTotal ) {
		oldTotal = originalTotal[theme]; // FIXME
		var total = 0;
		var inputs = $$( path );


		$( inputs ).each( function ( input ) {
			var nombre = Math.round( findValue( originalValues[theme], input.name ) / oldTotal * newTotal );
			if( nombre >= 0 ) {
				$( input ).value = nombre;
				total += nombre;
			}
		} );

		if( total > newTotal ) {
			originalValues[theme].each( function ( item ) {
				if( total > newTotal ) {
					var inputsTmp = document.getElementsByName( item.key );
					if( inputsTmp.length == 1 ) {
						if( $F( inputsTmp[0] ) > 0 ) {
							inputsTmp[0].value = ( parseInt( inputsTmp[0].value ) - 1 );
							total--;
						}
					}
				}
			} );
		}

		if( total < newTotal ) {
			originalValues[theme].each( function ( item ) {
				if( total < newTotal ) {
					var inputsTmp = document.getElementsByName( item.key );
					if( inputsTmp.length == 1 ) {
						var remaining = originalValues.length;
						var value = Math.round( ( newTotal - total ) / Math.max( 0, remaining ) );
						inputsTmp[0].value = ( parseInt( inputsTmp[0].value ) + value );
						total += value;
					}
				}
			} );
		}

		return total;
	}

	/*
	*
	*/

	function preparePropositions( theme ) {
		var totalId = 'SeanceepNombretotal' + theme;
		var tableInputs = 'table#table' + theme +' input[type=text]';

		originalTotal[theme] = 0;
		originalValues[theme] = new Array();

		// On stocke les anciennes valeurs pour garder les proportions lors des calculs
		var i = 0;
		$$( tableInputs ).each( function ( input ) {
			var value = parseInt( $F( input ) );
			var key = $( input ).name;
			originalTotal[theme] += value;
			originalValues[theme][i] = { 'key': key, 'value': value };
			i++;
		} );

		// Tri des valeurs originales
		originalValues[theme].sort( function(a, b) {
			return ( a.value <= b.value );
		} );

		// On observe le champ contenant le total
		$( totalId ).observe( 'blur', function( event ) {
			var name = $( totalId ).name;
			var newTotal = $F( totalId );
			var oldTotal = getTotal( tableInputs );

			$( 'cellTotal' + theme ).update( recompute( theme, tableInputs, oldTotal, newTotal ) );
		} );

		// Effectue le recalcul des dossiers à traiter par localisation lorsqu'on change une valeur
		$$( tableInputs ).each( function ( input ) {
			$( input ).observe( 'blur', function( event ) {
				var name = $( totalId ).name;
				var oldTotal = $F( totalId );
				var newTotal = getTotal( tableInputs );

				var t = getTotal( tableInputs );
				$( 'cellTotal' + theme ).update( t );
				$( totalId ).value = t;
			} );
		} );
	}
</script>

<?php
    echo $javascript->link( 'prototype.livepipe.js' );
    echo $javascript->link( 'prototype.tabs.js' );
?>

<script type="text/javascript">
	<?php foreach( array_keys( $themes ) as $theme ):?>
		<?php if( !empty( $themes[$theme] ) && ( $themes[$theme] != 'nontraite' ) ):?>
			<?php $modelTheme = Inflector::classify( $theme );?>
			preparePropositions( '<?php echo $modelTheme;?>' );
		<?php endif;?>
	<?php endforeach;?>

	makeTabbed( 'tabbedWrapper', 2 );
</script>