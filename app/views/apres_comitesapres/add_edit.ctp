<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Ajout d\'APRES au comité d\'examen';?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout APRE';
    }
    else {
        $this->pageTitle = 'Édition APRE';
    }
?>


    <h1><?php echo $this->pageTitle;?></h1>

    <?php echo $xform->create( 'ApreComiteapre', array( 'type' => 'post', 'url' => Router::url( null, true ) ) ); ?>
        <div class="aere">
            <fieldset>
                <legend>APREs à traiter durant le comité</legend>
                <?php echo $xform->input( 'Comiteapre.id', array( 'label' => false, 'type' => 'hidden' ) ) ;?>

               <!-- <?php echo $xform->input( 'Apre.Apre', array( 'label' =>  false, 'type' => 'select', 'options' => $apre, 'multiple' => 'checkbox' ) );?> -->

                <table>
                    <thead>
                        <tr>
                            <th>N° APRE</th>
                            <th>Nom/Prénom</th>
                            <th>Date de mande APRE</th>
                            <th>Sélectionner</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach( $apres as $i => $apre ) {
                                echo $html->tableCells(
                                    array(
                                        h( Set::classicExtract( $apre, 'Apre.numeroapre' ) ),
                                        h( Set::classicExtract( $apre, 'Personne.qual' ).' '.Set::classicExtract( $apre, 'Personne.nom' ).' '.Set::classicExtract( $apre, 'Personne.prenom' ) ),
                                        h( $locale->date( 'Date::short', Set::classicExtract( $apre, 'Apre.datedemandeapre' ) ) ),
                                        $xform->checkbox( 'Apre.Apre.'.$i, array( 'value' => $apre['Apre']['id'], /*'selected' => $apre['Apre']['id'],*/ 'id' => 'ApreApre'.$apre['Apre']['id'] , 'checked' => in_array( $apre['Apre']['id'], $this->data['Apre']['Apre'] ) ) ),
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