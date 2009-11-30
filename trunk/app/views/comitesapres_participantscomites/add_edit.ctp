<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Ajout de participant au comité d\'examen';?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout participant';
    }
    else {
        $this->pageTitle = 'Édition participant';
    }
?>


    <h1><?php echo $this->pageTitle;?></h1>

    <?php echo $xform->create( 'ComiteapreParticipantcomite', array( 'type' => 'post', 'url' => Router::url( null, true ) ) ); ?>
        <div class="aere">
            <fieldset>
                <legend>Participants au comité</legend>
                <?php echo $xform->input( 'Comiteapre.id', array( 'label' => false, 'type' => 'hidden' ) ) ;?>
               <!-- <?php /*echo $xform->input( 'Participantcomite.Participantcomite', array( 'label' =>  false, 'type' => 'select', 'options' => $participantcomite, 'multiple' => 'checkbox' ) );*/?> -->
                <table>
                    <thead>
                        <tr>
                            <th>Nom/Prénom</th>
                            <th>Fonction</th>
                            <th>Organisme</th>
                            <th>N° Téléphone</th>
                            <th>Email</th>
                            <th>Sélectionner</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            foreach( $participants as $i => $participant ) {
//                             debug($participant);
                                echo $html->tableCells(
                                    array(
                                        h( Set::classicExtract( $participant, 'Participantcomite.nom' ) ),
                                        h( Set::classicExtract( $participant, 'Participantcomite.fonction' ) ),
                                        h( Set::classicExtract( $participant, 'Participantcomite.organisme' ) ),
                                        h( Set::classicExtract( $participant, 'Participantcomite.numtel' ) ),
                                        h( Set::classicExtract( $participant, 'Participantcomite.mail' ) ),

                                        $xform->checkbox( 'Participantcomite.Participantcomite.'.$i, array( 'value' => $participant['Participantcomite']['id'], 'id' => 'ParticipantcomiteParticipantcomite'.$participant['Participantcomite']['id'] , 'checked' => in_array( $participant['Participantcomite']['id'], $this->data['Participantcomite']['Participantcomite'] ) ) ),
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