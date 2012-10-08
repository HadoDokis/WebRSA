<?php
	echo '<legend>'.__d( 'historiqueemploi', 'Historiqueemplois::index', true ).'</legend>';

	echo '<ul class="actions"><li class="add"><a href="#" id="AddHistoriqueemploi">Ajouter</a></li></ul>';

	if( empty( $this->data['Historiqueemploi'] ) ) {
		echo '<p class="notice">'.__d( 'historiqueemploi', 'Historiqueemplois::index::empty', true ).'</p>';
	}
	else {
		echo '<table id="Historiqueemplois">
				<thead>
					<tr>
						<th scope="col">'.__d( 'historiqueemploi', 'Historiqueemploi.emploi', true ).'</th>
						<th scope="col">'.__d( 'historiqueemploi', 'Historiqueemploi.datedebut', true ).'</th>
						<th scope="col">'.__d( 'historiqueemploi', 'Historiqueemploi.datefin', true ).'</th>
						<th scope="col">'.__d( 'historiqueemploi', 'Historiqueemploi.created', true ).'</th>
						<th scope="col">Actions</th>
					</tr>
				</thead>
				<tbody>';

		foreach( $this->data['Historiqueemploi'] as $i => $historiqueemploi ) {
			echo $html->tableCells(
				array(
					$xform->input( "Historiqueemploi.{$i}.id", array( 'type' => 'hidden' ) )
					.$xform->input( "Historiqueemploi.{$i}.personne_id", array( 'type' => 'hidden', 'value' => $personne_id ) )
					.$xform->input( "Historiqueemploi.{$i}.emploi", array( 'div' => false, 'label' => false, 'empty' => true, 'options' => $options['Historiqueemploi']['emploi'] ) ),
					$xform->input( "Historiqueemploi.{$i}.datedebut", array( 'div' => false, 'label' => false, 'empty' => true, 'dateFormat' => 'DMY' ) ),
					$xform->input( "Historiqueemploi.{$i}.datefin", array( 'div' => false, 'label' => false, 'empty' => true, 'dateFormat' => 'DMY' ) ),
					$locale->date( __( 'Date::short', true ), Set::classicExtract( $historiqueemploi, 'created' ) ),
					$default2->button( 'delete', '#', array( 'label' => 'Supprimer' ) )
				)
			);
		}

		echo '</tbody></table>';
	}
?>
<script type="text/javascript">
//<![CDATA[
	// Lien Ajouter
	$( 'AddHistoriqueemploi' ).observe( 'click', function( event ) {
		var inputs = $( 'Historiqueemplois' ).getElementsBySelector( 'input', 'select' );
		new Ajax.Updater(
			'Historiqueemplois',
			'<?php echo Router::url( array( 'action' => 'historiqueemplois', $personne_id, 'add' ), true );?>',
			{
				asynchronous: true,
				evalScripts: true,
				parameters: Form.serializeElements( inputs ),
				requestHeaders:['X-Update', 'Historiqueemplois']
			}
		);
		Event.stop( event );
	} );

	// Liens "Supprimer"
	$$( 'table#Historiqueemplois a.delete' ).each( function( link, index ) { // FIXME: les indexes "bougent"
		$( link ).observe( 'click', function( event ) {
			var inputs = $( 'Historiqueemplois' ).getElementsBySelector( 'input', 'select' );
			new Ajax.Updater(
				'Historiqueemplois',
				'<?php echo Router::url( array( 'action' => 'historiqueemplois', $personne_id, 'delete' ), true );?>' + '/' + index,
				{
					asynchronous: true,
					evalScripts: true,
					parameters: Form.serializeElements( inputs ),
					requestHeaders:['X-Update', 'Historiqueemplois']
				}
			);
			Event.stop( event );
		} );
	} );
//]]>
</script>
<?php Configure::write( 'debug', 0 ); ?>