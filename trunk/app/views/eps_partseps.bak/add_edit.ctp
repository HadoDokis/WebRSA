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
        $this->pageTitle = 'Ajout participant';
    }
    else {
        $this->pageTitle = 'Édition participant';
    }
?>


    <h1><?php echo $this->pageTitle;?></h1>
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
    <?php echo $xform->create( 'EpPartep', array( 'type' => 'post', 'url' => Router::url( null, true ) ) ); ?>
        <div class="aere">
            <fieldset>
                <legend>Participants à l'équipe</legend>
                <?php
                    //echo $xform->input( 'Ep.id', array( 'label' => false, 'type' => 'hidden' ) );
                ?>

                <table>
                    <thead>
                        <tr>
                            <th>Nom/Prénom</th>
                            <th>N° Téléphone</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Sélectionner</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            foreach( $participants as $i => $participant ) {
                                echo $html->tableCells(
                                    array(
                                        h( Set::classicExtract( $participant, 'Partep.qual' ).' '.Set::classicExtract( $participant, 'Partep.nom' ).' '.Set::classicExtract( $participant, 'Partep.prenom' ) ),
                                        h( Set::classicExtract( $participant, 'Partep.tel' ) ),
                                        h( Set::classicExtract( $participant, 'Partep.email' ) ),

                                        ( ( $this->action == 'edit' ) ? $xform->input( "EpPartep.{$i}.id", array( 'type' => 'hidden' ) ) : '' ).
                                        $xform->enum( "EpPartep.{$i}.ep_id", array( 'type' => 'hidden', 'value' => $this->params['pass'][0] ) ).
                                        $xform->enum( "EpPartep.{$i}.rolepartep_id", array(  'empty' => true,'label' => false, /*'type' => 'radio', 'separator' => '<br />',*/ 'options' => $roles ) ),
//                                         $xform->enum( 'Rolepartep.Rolepartep.'.$i, array(  'legend' => false, 'type' => 'radio', 'separator' => '<br />', 'options' => $roles ) ),
//                                         $xform->enum( 'Rolepartep.Rolepartep.'.$i, array(  'label' => false, 'type' => 'select', 'options' => $roles, 'empty' => true ) ),

                                        $xform->input( "EpPartep.{$i}.partep_id", array( 'type' => 'hidden', 'value' => $participant['Partep']['id'] ) ).
                                        $xform->checkbox(
                                            "EpPartep.{$i}.presencepre",
                                            array(
                                                'id' => 'PartepPartep'.$participant['Partep']['id'],
                                                //'checked' => in_array( $participant['Partep']['id'], $pePe ),
                                                'class' => 'checkbox'
                                            )
                                        )
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
    <?php echo $default->button(
            'back',
            array(
                'controller' => 'eps',
                'action'     => 'ordre',
                $this->params['pass'][0]
            ),
            array(
                'id' => 'Back'
            )
        );
    ?>
<?php
// 	echo $default->form(
// 		array(
// 			'EpPartep.ep_id' => array( 'type' => 'hidden', 'value' => $epId ),
// 			'EpPartep.partep_id',
// 			'EpPartep.rolepartep_id',
// 			'EpPartep.presencepre' => array( 'type' => 'select', 'options' => array( '0' => 'Non', '1' => 'Oui' ) ),
// 			'EpPartep.presenceeff' => array( 'type' => 'select', 'options' => array( 'absent' => 'Absent', 'present' => 'Présent', 'remplace' => 'Remplacé', 'excuse' => 'Excusé' ) )
// // 			'EpPartep.parteprempl_id' => array( 'options' => $options['EpPartep']['partep_id'], 'empty' => true ),
// 		),
// 		array(
// 			'options' => $options
// 		)
// 	);
// debug( $options );
?>