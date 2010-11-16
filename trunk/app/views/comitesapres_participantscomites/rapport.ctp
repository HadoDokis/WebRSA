<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Présence des participants au comité d\'examen';?>

    <h1><?php echo $this->pageTitle;?></h1>

    <?php echo $xform->create( 'ComiteapreParticipantcomite', array( 'type' => 'post', 'url' => Router::url( null, true ) ) ); ?>
        <div class="aere">
            <fieldset>
                <legend>Participants au comité</legend>
                <table>
                    <thead>
                        <tr>
                            <th>Nom/Prénom</th>
                            <th>Fonction</th>
                            <th>Organisme</th>
                            <th>N° Téléphone</th>
                            <th>Email</th>
                            <th>Présence</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach( $participants as $index => $participant ) {
                                $participantcomite_id = Set::classicExtract( $participant, 'ComiteapreParticipantcomite.participantcomite_id');
                                $comiteapre_id = Set::classicExtract( $participant, 'ComiteapreParticipantcomite.comiteapre_id');
                                $comiteapreparticipantcomite_id = Set::classicExtract( $participant, 'ComiteapreParticipantcomite.id');
                                $valuePresence = Set::classicExtract( $this->data, "$index.ComiteapreParticipantcomite.presence" );

// debug($options['presence']);
                                echo $xhtml->tableCells(
                                    array(
                                        h( Set::classicExtract( $participant, 'Participantcomite.nom' ) ),
                                        h( Set::classicExtract( $participant, 'Participantcomite.fonction' ) ),
                                        h( Set::classicExtract( $participant, 'Participantcomite.organisme' ) ),
                                        h( Set::classicExtract( $participant, 'Participantcomite.numtel' ) ),
                                        h( Set::classicExtract( $participant, 'Participantcomite.mail' ) ),

                                        $xform->enum( 'ComiteapreParticipantcomite.'.$index.'.presence', array( 'legend' => false, 'type' => 'radio', 'separator' => '<br />', 'options' => $options['presence'], 'value' => ( !empty( $valuePresence ) ? $valuePresence : 'PRE' ) ) ).
                                        $xform->input( 'ComiteapreParticipantcomite.'.$index.'.id', array( 'label' => false, 'div' => false, 'value' => $comiteapreparticipantcomite_id, 'type' => 'hidden' ) ).
                                        $xform->input( 'ComiteapreParticipantcomite.'.$index.'.participantcomite_id', array( 'label' => false, 'div' => false, 'value' => $participantcomite_id, 'type' => 'hidden' ) ).
                                        $xform->input( 'ComiteapreParticipantcomite.'.$index.'.comiteapre_id', array( 'label' => false, 'type' => 'hidden', 'value' => $comiteapre_id ) )
                                        //$xform->input( 'Comiteapre.'.$index.'.id', array( 'label' => false, 'type' => 'hidden', 'value' => Set::extract( $participant, 'Comiteapre.id' ) ) ).
                                        //$xform->input( 'Participantcomite.'.$index.'.id', array( 'label' => false, 'type' => 'hidden', 'value' => Set::extract( $participant, 'Participantcomite.id' ) ) ),

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