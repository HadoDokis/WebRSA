
<!-- 
Spécifiques !!!!!!!!!!!!!!
-->


        <!-- Aides aux démarches à la reprise d'emploi -->
        <fieldset>
            <?php
                /// Aides aux démarches à la reprise d'emploi
                $tmp = $form->checkbox( 'Apre.Natureaide.Locvehicinsert' );
                $tmp .= $html->tag( 'label', 'Aides aux démarches à la reprise d\'emploi', array( 'for' => 'ApreNatureaideLocvehicinsert' ) );
                echo $html->tag( 'h3', $tmp );
            ?>
        </fieldset>
<!--
        <fieldset>
            <?php
                /// Aide à la location d'un véhicule d'insertion
                $tmp = $form->checkbox( 'Apre.Natureaide.Locvehicinsert' );
                $tmp .= $html->tag( 'label', 'Aide à la location d\'un véhicule d\'insertion', array( 'for' => 'ApreNatureaideLocvehicinsert' ) );
                echo $html->tag( 'h3', $tmp );
            ?>
            <fieldset id="Locvehicinsert" class="invisible">
                <?php
                    $LocvehicinsertId = Set::classicExtract( $this->data, 'Locvehicinsert.id' );
                    if( $this->action == 'edit' && !empty( $LocvehicinsertId ) ) {
                        echo $form->input( 'Locvehicinsert.id', array( 'type' => 'hidden' ) );
                    }
                    echo $xform->input( 'Locvehicinsert.societelocation', array('required' => true,  'domain' => 'apre' ) );
                    echo $xform->input( 'Locvehicinsert.dureelocation', array( 'required' => true, 'domain' => 'apre' ) );
                    echo $xform->input( 'Locvehicinsert.montantaide', array('required' => true,  'domain' => 'apre' ) );
                ?>
                <fieldset>
                    <legend>Pièces jointes</legend>
                    <?php
                        $selected = Set::extract( $this->data, '/Locvehicinsert/Piecelocvehicinsert/id' );
                        echo $xform->input( 'Piecelocvehicinsert.Piecelocvehicinsert', array( 'options' => $pieceslocvehicinsert, 'multiple' => 'checkbox', 'label' => false, 'selected' => $selected ) ); ?>
                </fieldset>
            </fieldset>
        </fieldset>  -->

<!--
FIN SPECIFIQUE
-->