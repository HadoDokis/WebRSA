<script type="text/javascript">
    document.observe("dom:loaded", function() {

        // Javascript pour les aides liées à l'APRE
        ['Formqualif', 'Formpermfimo', 'Actprof', 'Permisb', 'Amenaglogt', 'Acccreaentr', 'Acqmatprof', 'Locvehicinsert' ].each( function( formation ) {
            observeDisableFieldsetOnRadioValue(
                '<?php echo $this->modelClass;?>',
                'data[<?php echo $this->modelClass;?>][Natureaide]',
                $( formation ),
                formation,
                false,
                true
            );
        } );


        <?php
            echo $ajax->remoteFunction(
                array(
                    'update' => 'FormqualifCoordonnees',
                    'url' => Router::url( array( 'action' => 'ajaxtiersprestaformqualif', Set::extract( $this->data, 'Formqualif.tiersprestataireapre_id' ) ), true )
                )
            ).';';
            echo $ajax->remoteFunction(
                array(
                    'update' => 'FormpermfimoCoordonnees',
                    'url' => Router::url( array( 'action' => 'ajaxtiersprestaformpermfimo', Set::extract( $this->data, 'Formpermfimo.tiersprestataireapre_id' ) ), true )
                )
            ).';';
            echo $ajax->remoteFunction(
                array(
                    'update' => 'ActprofAdresseemployeur',
                    'url' => Router::url( array( 'action' => 'ajaxtiersprestaactprof', Set::extract( $this->data, 'Actprof.tiersprestataireapre_id' ) ), true )
                )
            ).';';
            echo $ajax->remoteFunction(
                array(
                    'update' => 'PermisbAdresseautoecole',
                    'url' => Router::url( array( 'action' => 'ajaxtiersprestapermisb', Set::extract( $this->data, 'Permisb.tiersprestataireapre_id' ) ), true )
                )
            ).';';
        ?>

    });
</script>

        <fieldset class="wide">
            <legend>Justificatif</legend>
            <?php
                echo $xform->enum( "{$this->modelClass}.justificatif", array(  'legend' => false, 'div' => false,  'required' => true, 'type' => 'radio', 'separator' => '<br />', 'options' => $options['justificatif'] ) );
            ?>
        </fieldset>
            <?php
                echo $xform->input( 'Pieceapre.Pieceapre', array( 'options' => $piecesapre, 'multiple' => 'checkbox',  'label' => 'Pièces jointes', ) );
            ?>

        <h2 class="center">Nature de la demande</h2>
        <br />
        <h3 class="center" style="font-style:italic">Liée à une Formation</h3>
        <fieldset>
            <?php
                /// Formation qualifiante
                $tmp = radioApre( $this, "{$this->modelClass}.Natureaide", 'Formqualif', 'Formations individuelles qualifiantes' );
                echo $html->tag( 'h3', $tmp );
            ?>
            <fieldset id="Formqualif" class="invisible">
                <?php
                    $FormqualifId = Set::classicExtract( $this->data, 'Formqualif.id' );
                    if( $this->action == 'edit' && !empty( $FormqualifId ) ) {
                        echo $form->input( 'Formqualif.id', array( 'type' => 'hidden' ) );
                    }
                    echo $xform->input( 'Formqualif.intituleform', array(  'required' => true, 'domain' => 'apre' ) );
                    echo $xform->enum( 'Formqualif.tiersprestataireapre_id', array( 'required' => true, 'domain' => 'apre', 'options' => $tiersFormqualif, 'empty' => true ) );
                    echo $ajax->observeField( 'FormqualifTiersprestataireapreId', array( 'update' => 'FormqualifCoordonnees', 'url' => Router::url( array( 'action' => 'ajaxtiersprestaformqualif' ), true ) ) );
                    echo $html->tag(
                        'div',
                        $html->tag( 'div', ( isset( $FormqualifCoordonnees ) ? $FormqualifCoordonnees : ' ' ), array( 'id' => 'FormqualifCoordonnees' ) ).'<br />'
                    );

                    echo $xform->input( 'Formqualif.ddform', array( 'required' => true, 'domain' => 'apre', 'type' => 'date', 'dateFormat' => 'DMY' ) );
                    echo $xform->input( 'Formqualif.dfform', array( 'required' => true, 'domain' => 'apre', 'type' => 'date', 'dateFormat' => 'DMY' ) );
                    echo $xform->input( 'Formqualif.dureeform', array( 'required' => true, 'domain' => 'apre' ) );
                    echo $xform->input( 'Formqualif.modevalidation', array( 'domain' => 'apre' ) );
                    echo $xform->input( 'Formqualif.coutform', array( 'required' => true, 'domain' => 'apre' ) );
                    echo $xform->input( 'Formqualif.cofinanceurs', array( 'domain' => 'apre' ) );
                    echo $xform->input( 'Formqualif.montantaide', array( 'required' => true, 'domain' => 'apre' ) );;
                ?>
                <?php
                    $selected = Set::extract( $this->data, '/Formqualif/Pieceformqualif/id' );
                    echo $xform->input( 'Pieceformqualif.Pieceformqualif', array( 'options' => $piecesformqualif, 'multiple' => 'checkbox', 'label' => 'Pièces jointes','selected' => $selected ) );
                ?>
            </fieldset>
        </fieldset>
        <fieldset>
            <?php
                /// Formation qualifiante Perm FIMO
                $tmp = radioApre( $this, "{$this->modelClass}.Natureaide", 'Formpermfimo', 'Formation permis de conduire Poids Lourd + FIMO' );
                echo $html->tag( 'h3', $tmp );

            ?>
            <fieldset id="Formpermfimo" class="invisible">
                <?php
                    $FormpermfimoId = Set::classicExtract( $this->data, 'Formpermfimo.id' );
                    if( $this->action == 'edit' && !empty( $FormpermfimoId ) ) {
                        echo $form->input( 'Formpermfimo.id', array( 'type' => 'hidden' ) );
                    }
                    echo $xform->input( 'Formpermfimo.intituleform', array( 'required' => true, 'domain' => 'apre' ) );
//                     echo $xform->address( 'Formpermfimo.organismeform', array( 'required' => true, 'domain' => 'apre' ) );
                    echo $xform->enum( 'Formpermfimo.tiersprestataireapre_id', array( 'required' => true, 'domain' => 'apre', 'options' => $tiersFormpermfimo, 'empty' => true ) );
                    echo $ajax->observeField( 'FormpermfimoTiersprestataireapreId', array( 'update' => 'FormpermfimoCoordonnees', 'url' => Router::url( array( 'action' => 'ajaxtiersprestaformpermfimo' ), true ) ) );
                    echo $html->tag(
                        'div',
                        $html->tag( 'div', ( isset( $FormpermfimoCoordonnees ) ? $FormpermfimoCoordonnees : ' ' ), array( 'id' => 'FormpermfimoCoordonnees' ) ).'<br />'
                    );

                    echo $xform->input( 'Formpermfimo.ddform', array( 'required' => true, 'domain' => 'apre', 'type' => 'date', 'dateFormat' => 'DMY' ) );
                    echo $xform->input( 'Formpermfimo.dfform', array( 'required' => true, 'domain' => 'apre', 'type' => 'date', 'dateFormat' => 'DMY' ) );
                    echo $xform->input( 'Formpermfimo.dureeform', array( 'required' => true, 'domain' => 'apre' ) );
                    echo $xform->input( 'Formpermfimo.modevalidation', array( 'domain' => 'apre' ) );
                    echo $xform->input( 'Formpermfimo.coutform', array( 'required' => true, 'domain' => 'apre' ) );
                    echo $xform->input( 'Formpermfimo.cofinanceurs', array( 'domain' => 'apre' ) );
                    echo $xform->input( 'Formpermfimo.montantaide', array( 'required' => true, 'domain' => 'apre' ) );;
                ?>
                <?php
                    $selected = Set::extract( $this->data, '/Formpermfimo/Pieceformpermfimo/id' );
                    echo $xform->input( 'Pieceformpermfimo.Pieceformpermfimo', array( 'options' => $piecesformpermfimo, 'multiple' => 'checkbox', 'label' => 'Pièces jointes', 'selected' => $selected ) );
                ?>
            </fieldset>
        </fieldset>
        <fieldset>
            <?php
                /// Action de professionnalisation
                $tmp = radioApre( $this, "{$this->modelClass}.Natureaide", 'Actprof', 'Action de professionnalisation des contrats aides et salariés dans les SIAE' );
                echo $html->tag( 'h3', $tmp );

            ?>
            <fieldset id="Actprof" class="invisible">
                <?php
                    $ActprofId = Set::classicExtract( $this->data, 'Actprof.id' );
                    if( $this->action == 'edit' && !empty( $ActprofId ) ) {
                        echo $form->input( 'Actprof.id', array( 'type' => 'hidden' ) );
                    }
                    echo $xform->enum( 'Actprof.tiersprestataireapre_id', array( 'required' => true, 'domain' => 'apre', 'options' => $tiersActprof, 'empty' => true ) );
                    echo $ajax->observeField( 'ActprofTiersprestataireapreId', array( 'update' => 'ActprofAdresseemployeur', 'url' => Router::url( array( 'action' => 'ajaxtiersprestaactprof' ), true ) ) );
                    echo $html->tag(
                        'div',
                        $html->tag( 'div', ( isset( $ActprofAdresseemployeur ) ? $ActprofAdresseemployeur : ' ' ), array( 'id' => 'ActprofAdresseemployeur' ) ).'<br />'
                    );
//                     echo $xform->input( 'Actprof.nomemployeur', array( 'required' => true, 'domain' => 'apre' ) );
//                     echo $xform->address( 'Actprof.adresseemployeur', array( 'required' => true, 'domain' => 'apre' ) );
                    echo $xform->enum( 'Actprof.typecontratact', array( 'required' => true, 'div' => false, 'legend' => 'Type de contrat', 'type' => 'radio', 'options' => $optionsacts['typecontratact'] ) );
                    echo $xform->input( 'Actprof.ddconvention', array( 'required' => true, 'domain' => 'apre', 'dateFormat' => 'DMY' ) );
                    echo $xform->input( 'Actprof.dfconvention', array( 'required' => true, 'domain' => 'apre', 'dateFormat' => 'DMY' ) );
                    echo $xform->input( 'Actprof.intituleformation', array( 'required' => true, 'domain' => 'apre' ) );
                    echo $xform->input( 'Actprof.ddform', array( 'required' => true, 'domain' => 'apre', 'dateFormat' => 'DMY' ) );
                    echo $xform->input( 'Actprof.dfform', array( 'required' => true, 'domain' => 'apre', 'dateFormat' => 'DMY' ) );
                    echo $xform->input( 'Actprof.dureeform', array( 'required' => true, 'domain' => 'apre' ) );
                    echo $xform->input( 'Actprof.modevalidation', array( 'domain' => 'apre' ) );;
                    echo $xform->input( 'Actprof.coutform', array('required' => true,  'domain' => 'apre' ) );
                    echo $xform->input( 'Actprof.cofinanceurs', array( 'domain' => 'apre' ) );
                    echo $xform->input( 'Actprof.montantaide', array( 'required' => true, 'domain' => 'apre' ) );
                ?>
                <?php
                    $selected = Set::extract( $this->data, '/Actprof/Pieceactprof/id' );
                    echo $xform->input( 'Pieceactprof.Pieceactprof', array( 'options' => $piecesactprof, 'multiple' => 'checkbox', 'label' => 'Pièces jointes', 'selected' => $selected ) );
                ?>
            </fieldset>
        </fieldset>
        <fieldset>
            <?php
                /// Permis B
                $tmp = radioApre( $this, "{$this->modelClass}.Natureaide", 'Permisb', 'Permis de conduire B' );
                echo $html->tag( 'h3', $tmp );

            ?>
            <fieldset id="Permisb" class="invisible">
                <?php
                    $PermisbId = Set::classicExtract( $this->data, 'Permisb.id' );
                    if( $this->action == 'edit' && !empty( $PermisbId ) ) {
                        echo $form->input( 'Permisb.id', array( 'type' => 'hidden' ) );
                    }

                    echo $xform->enum( 'Permisb.tiersprestataireapre_id', array( 'required' => true, 'domain' => 'apre', 'options' => $tiersPermisb, 'empty' => true ) );
                    echo $ajax->observeField( 'PermisbTiersprestataireapreId', array( 'update' => 'PermisbAdresseautoecole', 'url' => Router::url( array( 'action' => 'ajaxtiersprestapermisb' ), true ) ) );
                    echo $html->tag(
                        'div',
                        $html->tag( 'div', ( isset( $PermisbAdresseautoecole ) ? $PermisbAdresseautoecole : ' ' ), array( 'id' => 'PermisbAdresseautoecole' ) ).'<br />'
                    );
//                     echo $xform->input( 'Permisb.nomautoecole', array( 'required' => true, 'domain' => 'apre' ) );
//                     echo $xform->address( 'Permisb.adresseautoecole', array( 'required' => true, 'domain' => 'apre' ) );
                    echo $xform->input( 'Permisb.code',
                        array( 'div' => false, 'label' => 'Code', 'type' => 'checkbox' )
                    );
                    echo $xform->input( 'Permisb.conduite',
                        array( 'div' => false, 'label' => 'Conduite', 'type' => 'checkbox' )
                    );
                    echo $xform->input( 'Permisb.dureeform', array( 'required' => true, 'domain' => 'apre' ) );
                    echo $xform->input( 'Permisb.montantaide', array( 'required' => true, 'domain' => 'apre', 'maxlength' => 4 ) );
                ?>
                <?php
                    $selected = Set::extract( $this->data, '/Permisb/Piecepermisb/id' );
                    echo $xform->input( 'Piecepermisb.Piecepermisb', array( 'options' => $piecespermisb, 'multiple' => 'checkbox', 'label' => 'Pièces jointes', 'selected' => $selected ) );
                ?>
            </fieldset>
        </fieldset>
        <br />
        <h3 class="center" style="font-style:italic">Hors Formation</h3>
        <fieldset>
            <?php
                /// Amenagement logement

                $tmp = radioApre( $this, "{$this->modelClass}.Natureaide", 'Amenaglogt', 'Aide à l\'installation' );
                echo $html->tag( 'h3', $tmp );
            ?>
            <fieldset id="Amenaglogt" class="invisible">
                <?php
                    $AmenaglogtId = Set::classicExtract( $this->data, 'Amenaglogt.id' );
                    if( $this->action == 'edit' && !empty( $AmenaglogtId ) ) {
                        echo $form->input( 'Amenaglogt.id', array( 'type' => 'hidden' ) );
                    }
                ?>
                <div class="demi">
                    <?php echo $form->input( 'Amenaglogt.typeaidelogement', array( 'label' => 'Type d\'aide au logement : ' , 'type' => 'radio', 'div' => false, 'separator' => '</div><div class="demi">', 'options' => $optionslogts['typeaidelogement'], 'legend' => false ) );?>
                </div>

                <?php
                    echo $xform->address( 'Amenaglogt.besoins', array( 'domain' => 'apre' ) );
                    echo $xform->input( 'Amenaglogt.montantaide', array( 'required' => true, 'domain' => 'apre' ) );
                ?>

                <?php
                    $selected = Set::extract( $this->data, '/Amenaglogt/Pieceamenaglogt/id' );
                    echo $xform->input( 'Pieceamenaglogt.Pieceamenaglogt', array( 'options' => $piecesamenaglogt, 'multiple' => 'checkbox', 'label' => 'Pièces jointes', 'selected' => $selected ) );
                ?>
            </fieldset>
        </fieldset>
        <fieldset>
            <?php
                /// Accompagnement à la création d'entreprise

                $tmp = radioApre( $this, "{$this->modelClass}.Natureaide", 'Acccreaentr', 'Accompagnement à la création d\'entreprise' );
                echo $html->tag( 'h3', $tmp );
            ?>
            <fieldset id="Acccreaentr" class="invisible">
                <?php
                    $AcccreaentrId = Set::classicExtract( $this->data, 'Acccreaentr.id' );
                    if( $this->action == 'edit' && !empty( $AcccreaentrId ) ) {
                        echo $form->input( 'Acccreaentr.id', array( 'type' => 'hidden' ) );
                    }
                    echo $xform->enum( 'Acccreaentr.nacre', array( 'required' => true, 'legend' => 'Dispositif Nacre', 'div' => false, 'type' => 'radio', 'options' => $optionscrea['nacre'] ) );
                    echo $xform->enum( 'Acccreaentr.microcredit', array( 'required' => true, 'legend' => 'Dispositif Micro-crédit', 'div' => false, 'type' => 'radio', 'options' => $optionscrea['microcredit'] ) );
                    echo $xform->address( 'Acccreaentr.projet', array( 'domain' => 'apre' ) );
                    echo $xform->input( 'Acccreaentr.montantaide', array( 'required' => true, 'domain' => 'apre' ) );
                ?>
                <?php
                    $selected = Set::extract( $this->data, '/Acccreaentr/Pieceacccreaentr/id' );
                    echo $xform->input( 'Pieceacccreaentr.Pieceacccreaentr', array( 'options' => $piecesacccreaentr, 'multiple' => 'checkbox', 'label' => 'Pièces jointes', 'selected' => $selected ) );
                ?>
            </fieldset>
        </fieldset>
        <fieldset>
            <?php
                /// Acquisition de matériels professionnels

                $tmp = radioApre( $this, "{$this->modelClass}.Natureaide", 'Acqmatprof', 'Acquisition de matériels professionnels' );
                echo $html->tag( 'h3', $tmp );
            ?>
            <fieldset id="Acqmatprof" class="invisible">
                <?php
                    $AcqmatprofId = Set::classicExtract( $this->data, 'Acqmatprof.id' );
                    if( $this->action == 'edit' && !empty( $AcqmatprofId ) ) {
                        echo $form->input( 'Acqmatprof.id', array( 'type' => 'hidden' ) );
                    }
                    echo $xform->address( 'Acqmatprof.besoins', array( 'domain' => 'apre' ) );
                    echo $xform->input( 'Acqmatprof.montantaide', array( 'required' => true, 'domain' => 'apre' ) );
                ?>

                <?php
                    $selected = Set::extract( $this->data, '/Acqmatprof/Pieceacqmatprof/id' );
                    echo $xform->input( 'Pieceacqmatprof.Pieceacqmatprof', array( 'options' => $piecesacqmatprof, 'multiple' => 'checkbox', 'label' => 'Pièces jointes', 'selected' => $selected ) );
                ?>
            </fieldset>
        </fieldset>
        <fieldset>
            <?php
                /// Aide à la location d'un véhicule d'insertion

                $tmp = radioApre( $this, "{$this->modelClass}.Natureaide", 'Locvehicinsert', 'Aide à la location d\'un véhicule d\'insertion' );
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
                <?php
                    $selected = Set::extract( $this->data, '/Locvehicinsert/Piecelocvehicinsert/id' );
                    echo $xform->input( 'Piecelocvehicinsert.Piecelocvehicinsert', array( 'options' => $pieceslocvehicinsert, 'multiple' => 'checkbox', 'label' => 'Pièces jointes', 'selected' => $selected ) );
                ?>
            </fieldset>
        </fieldset>