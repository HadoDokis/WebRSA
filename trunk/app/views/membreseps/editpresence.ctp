<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Présence des participants à la séance d\'EP';?>
        
    <h1><?php echo $this->pageTitle;?></h1>

    <?php echo $xform->create( 'Membreep', array( 'type' => 'post', 'url' => Router::url( null, true ) ) ); ?>
        <div class="aere">
            <fieldset>
                <legend>Présence des participants à la séance d'EP</legend>
                <?php echo $xform->input( 'Seanceep.id', array( 'label' => false, 'type' => 'hidden', 'value'=>$seance_id ) ) ;
                debug($presences);
                
                ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nom/Prénom</th>
                            <th>Fonction</th>
                            <th>N° Téléphone</th>
                            <th>Email</th>
                            <th>Suppléant</th>
                            <th>Réponse</th>
                            <th>Presence</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
//                            foreach( $presences as $i => $presence ) {
//                            	$suppleant = '';
//                            	if( isset($presence['Membreep']['suppleant_id']))
//                            	{
//                            		$presencesuppleant = Set::Extract($presences, "/Membreep[id={$presence['Membreep']['suppleant_id']}]");
//									$suppleant = ( Set::classicExtract( $presencesuppleant[0], 'Membreep.qual' ).' '.Set::classicExtract( $presencesuppleant[0], 'Membreep.nom' ).' '.Set::classicExtract( $presencesuppleant[0], 'Membreep.prenom' ) );
//                            	}
//                                echo $xhtml->tableCells(
//                                    array(
//                                        h( Set::classicExtract( $presence, 'Membreep.qual' ).' '.Set::classicExtract( $presence, 'Membreep.nom' ).' '.Set::classicExtract( $presence, 'Membreep.prenom' ) ),
//                                        h( Set::classicExtract( $presence, 'Fonctionmembreep.name' ) ),
//                                        h( '?' ), // h( Set::classicExtract( $presence, 'Membreep.tel' ) ), 
//                                        h( '?' ), // h( Set::classicExtract( $presence, 'Membreep.emaail' ) ),
//                                        h( $suppleant ),
//                                        $xform->input( 'Membreep.'.$i.'.id', array( 'value' => $presence['Membreep']['id'] ) ).
//                                        $xform->input( 'Membreep.'.$i.'.checked', array( 'checked' => !empty( $presence['Seanceep'] ), 'type' => 'checkbox', 'div' => false, 'label' => false ) ),
//                                    ),
//                                    array( 'class' => 'odd' ),
//                                    array( 'class' => 'even' )
//                                );
//                            }
                        ?>
                    </tbody>
                </table>
            </fieldset>
        </div>
		<?php echo $xform->submit( 'Enregistrer' );?>
	<?php echo $xform->end();?>
<div class="clearer"><hr /></div>