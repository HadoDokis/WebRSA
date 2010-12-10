<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Ajout de participants à la séance d\'EP';?>
<script type="text/javascript">
//<![CDATA[
    function allCheckboxes( checked ) {
        $$('input.checkbox').each( function ( checkbox ) {
            $( checkbox ).checked = checked;
        } );
        return false;
    }
//]]>
</script>
<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout participants';
    }
    else {
        $this->pageTitle = 'Édition participants';
    }
?>
    <h1><?php echo $this->pageTitle;?></h1>
    <?php
        echo $xhtml->tag(
            'ul',
            implode(
                '',
                array(
                    $xhtml->tag( 'li', $xhtml->link( 'Tout sélectionner', '#', array( 'onclick' => 'allCheckboxes( true ); return false;' ) ) ),
                    $xhtml->tag( 'li', $xhtml->link( 'Tout désélectionner', '#', array( 'onclick' => 'allCheckboxes( false ); return false;' ) ) ),
                )
            )
        );
    ?>
    <?php echo $xform->create( 'Membreep', array( 'type' => 'post', 'url' => Router::url( null, true ) ) ); ?>
        <div class="aere">
            <fieldset>
                <legend>Participants à la séance d'EP</legend>
                <?php echo $xform->input( 'Seanceep.id', array( 'label' => false, 'type' => 'hidden', 'value'=>$seance_id ) ) ;?>
                <table>
                    <thead>
                        <tr>
                            <th>Nom/Prénom</th>
                            <th>Fonction</th>
                            <th>N° Téléphone</th>
                            <th>Email</th>
                            <th>Suppléant</th>
                            <th>Sélectionner</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach( $membres as $i => $membre ) {
                            	$suppleant = '';
                            	if( isset($membre['Membreep']['suppleant_id']))
                            	{
                            		$membreSuppleant = Set::Extract($membres, "/Membreep[id={$membre['Membreep']['suppleant_id']}]");
									$suppleant = ( Set::classicExtract( $membreSuppleant[0], 'Membreep.qual' ).' '.Set::classicExtract( $membreSuppleant[0], 'Membreep.nom' ).' '.Set::classicExtract( $membreSuppleant[0], 'Membreep.prenom' ) );
                            	}
                                echo $xhtml->tableCells(
                                    array(
                                        h( Set::classicExtract( $membre, 'Membreep.qual' ).' '.Set::classicExtract( $membre, 'Membreep.nom' ).' '.Set::classicExtract( $membre, 'Membreep.prenom' ) ),
                                        h( Set::classicExtract( $membre, 'Fonctionmembreep.name' ) ),
                                        h( '?' ), // h( Set::classicExtract( $membre, 'Membreep.tel' ) ), 
                                        h( '?' ), // h( Set::classicExtract( $membre, 'Membreep.emaail' ) ),
                                        h( $suppleant ),
                                        $xform->input( 'Membreep.'.$i.'.id', array( 'value' => $membre['Membreep']['id'] ) ).
                                        $xform->input( 'Membreep.'.$i.'.checked', array( 'checked' => !empty( $membre['Seanceep'] ), 'type' => 'checkbox', 'div' => false, 'label' => false ) ),
                                    ),
                                    array( 'class' => 'odd' ),
                                    array( 'class' => 'even' )
                                );
                            }
                        ?>
                    </tbody>
                </table>
            </fieldset>
        </div>

        <?php echo $xform->submit( 'Enregistrer' );?>
    <?php echo $xform->end();?>

<div class="clearer"><hr /></div>