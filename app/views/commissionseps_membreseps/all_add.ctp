<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<script type="text/javascript">
//<![CDATA[
    function allCheckboxes( checked ) {
        $$('input.checkbox').each( function ( checkbox ) {
            $( checkbox ).checked = checked;
        } );
        return false;
    }

    function flip( id ) {
		$('CommissionepMembreepSuppleant'+id).disabled = !$('CommissionepMembreepMembreepId'+id).checked;	
    }
//]]>
</script>
    <h1><?php echo $this->pageTitle='Liste des participants à l\'EP' ;?></h1>
    <?php
        ///
        echo $html->tag(
            'ul',
            implode(
                '',
                array(
                    $html->tag( 'li', $html->link( 'Tout sélectionner', '#', array( 'onclick' => 'allCheckboxes( true ); return false;' ) ) ),
                    $html->tag( 'li', $html->link( 'Tout désélectionner', '#', array( 'onclick' => 'allCheckboxes( false ); return false;' ) ) ),
                )
            )
        );
    ?>
    <?php echo $xform->create( 'CommissionepMembreep', array( 'type' => 'post', 'url' => Router::url( null, true ) ) ); ?>
            <fieldset>
                <legend>Participants à l\'EP</legend>
                <?php echo $xform->input( 'CommissionepMembreep.commissionep_id', array( 'label' => false, 'type' => 'hidden' ) ) ;?>
                <table>
                    <thead>
                        <tr>
                            <th>Nom/Prénom</th>
                            <th>Fonction</th>
                            <th>Sélectionner</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach( $membres as $i => $membre ) {
                                echo $html->tableCells(
                                    array(
                                        h( Set::classicExtract( $membre, 'Membreep.qual' ).' '.Set::classicExtract( $membre, 'Membreep.nom' ).' '.Set::classicExtract( $membre, 'Membreep.prenom' ) ),
                                        h( Set::classicExtract( $membre, 'Fonctionmembreep.name' ) ),
                                        $xform->checkbox(
                                        	'CommissionepMembreep.membreep_id.'.$i, 
                                        	array(
                                        		'value' => $membre['Membreep']['id'],
                                        		//'id' => 'CommissionepMembrep'.$membreparticipant['Participantcomite']['id'] ,
                                        		'checked' => isset( $membre['CommissionepMembreep']['membreep_id']), 
                                        		'class' => 'checkbox',
                                        		'onClick' => 'flip('.$i.')'
                                        		)
                                        ),
                                        
                                    ),
                                    array( 'class' => 'odd' ),
                                    array( 'class' => 'even' )
                                );
                            }
                        ?>
                    </tbody>
                </table>
            </fieldset>
        <?php echo $xform->submit( 'Enregistrer' );?>
    <?php echo $xform->end();?>