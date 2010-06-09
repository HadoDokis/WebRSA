<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    $domain = 'bilanparcours';

    echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout d\'un bilan de parcours';
    }
    else {
        $this->pageTitle = 'Édition du bilan de parcours';
    }
?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php
        if( $this->action == 'add' ) {
            echo $form->create( 'Bilanparcours', array( 'type' => 'post', 'url' => Router::url( null, true ),  'id' => 'Bilan' ) );
        }
        else {
            echo $form->create( 'Bilanparcours', array( 'type' => 'post', 'url' => Router::url( null, true ), 'id' => 'Bilan' ) );
            echo '<div>';
            echo $form->input( 'Bilanparcours.id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }
        echo '<div>';
        echo $form->input( 'Bilanparcours.personne_id', array( 'type' => 'hidden', 'value' => $personne_id ) );
        echo '</div>';
    ?>

<?php echo $javascript->link( 'dependantselect.js' ); ?>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        dependantSelect( 'BilanparcoursReferentId', 'BilanparcoursStructurereferenteId' );
    });
</script>

    <div class="aere">
    <fieldset class="aere">
        <legend>BILAN DU PARCOURS ( Rédigé en présence du bénéficiaire )</legend>
            <?php
                echo $default->subform(
                    array(
                        'Bilanparcours.structurereferente_id',
                        'Bilanparcours.referent_id'
                    ),
                    array(
                        'options' => $options
                    )
                );
            ?>

        <fieldset>
            <legend>Situation de l'allocataire</legend>
            <table class="wide noborder">
                <tr>
                    <td class="mediumSize noborder">
                        <strong>Statut de la personne : </strong><?php echo Set::extract( $rolepers, Set::extract( $personne, 'Prestation.rolepers' ) ); ?>
                        <br />
                        <strong>Nom : </strong><?php echo Set::enum( Set::classicExtract( $personne, 'Personne.qual') , $qual ).' '.Set::classicExtract( $personne, 'Personne.nom' );?>
                        <br />
                        <strong>Prénom : </strong><?php echo Set::classicExtract( $personne, 'Personne.prenom' );?>
                        <br />
                        <strong>Date de naissance : </strong><?php echo date_short( Set::classicExtract( $personne, 'Personne.dtnai' ) );?>
                    </td>
                    <td class="mediumSize noborder">
                        <strong>N° Service instructeur : </strong><?php echo Set::classicExtract( $personne, 'Serviceinstructeur.lib_service');?>
                        <br />
                        <strong>N° demandeur : </strong><?php echo Set::classicExtract( $personne, 'Foyer.Dossier.numdemrsa' );?>
                        <br />
                        <strong>N° CAF/MSA : </strong><?php echo Set::classicExtract( $personne, 'Foyer.Dossier.matricule' );?>
                        <br />
                        <strong>Inscrit au Pôle emploi</strong>
                        <?php
                            $isPoleemploi = Set::classicExtract( $personne, 'Activite.0.act' );
                            if( $isPoleemploi == 'ANP' )
                                echo 'Oui';
                            else
                                echo 'Non';
                        ?>
                        <br />
                        <strong>N° identifiant : </strong><?php echo Set::classicExtract( $personne, 'Personne.idassedic' );?>
                    </td>
                </tr>
                <tr>
                    <td class="mediumSize noborder">
                        <strong>Adresse : </strong><br /><?php echo Set::classicExtract( $personne, 'Adresse.numvoie' ).' '.Set::enum( Set::classicExtract( $personne, 'Adresse.typevoie' ), $options['typevoie'] ).' '.Set::classicExtract( $personne, 'Adresse.nomvoie' ).'<br /> '.Set::classicExtract( $personne, 'Adresse.codepos' ).' '.Set::classicExtract( $personne, 'Adresse.locaadr' );?>
                    </td>
                    <td class="mediumSize noborder">
                        <?php if( Set::extract( $personne, 'Foyer.Modecontact.0.autorutitel' ) == 'A' ):?>
                                <strong>Numéro de téléphone 1 : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.0.numtel' );?>
                        <?php endif;?>
                        <?php if( Set::extract( $personne, 'Foyer.Modecontact.1.autorutitel' ) == 'A' ):?>
                                <br />
                                <strong>Numéro de téléphone 2 : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.1.numtel' );?>
                        <?php endif;?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="mediumSize noborder">
                    <?php if( Set::extract( $personne, 'Foyer.Modecontact.0.autorutiadrelec' ) == 'A' ):?>
                        <strong>Adresse mail : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.0.adrelec' );?> <!-- FIXME -->
                    <?php endif;?>
                    </td>
                </tr>
            </table>
        </fieldset>

            <?php
                echo $default->subform(
                    array(
                        'Bilanparcours.objinit',
                        'Bilanparcours.objatteint',
                        'Bilanparcours.objnew',
                    ),
                    array(
                        'options' => $options
                    )
                );
            ?>


<script type="text/javascript">
    document.observe("dom:loaded", function() {

        // Javascript pour les aides liées à l'APRE
        ['traitement', 'parcours', 'audition' ].each( function( proposition ) {
            observeDisableFieldsetOnRadioValue(
                'Bilan',
                'data[Bilanparcours][proposition]',
                $( proposition ),
                proposition,
                false,
                true
            );
        } );

        // Partie en cas de changment ou non du référent
        observeDisableFieldsetOnRadioValue(
            'Bilan',
            'data[Bilanparcours][changementrefsansep]',
            $( 'NvReferent' ),
            'O',
            false,
            true
        );

        observeDisableFieldsetOnRadioValue(
            'Bilan',
            'data[Bilanparcours][changementrefsansep]',
            $( 'Contratreconduit' ),
            'N',
            false,
            true
        );

        // Partie en cas de maintien ou  de réorientation
        observeDisableFieldsetOnRadioValue(
            'Bilan',
            'data[Bilanparcours][choixparcours]',
            $( 'Maintien' ),
            'maintien',
            false,
            true
        );

        observeDisableFieldsetOnRadioValue(
            'Bilan',
            'data[Bilanparcours][choixparcours]',
            $( 'Reorientation' ),
            'reorientation',
            false,
            true
        );

        observeDisableFieldsetOnRadioValue(
            'Bilan',
            'data[Bilanparcours][changementrefparcours]',
            $( 'NvparcoursReferent' ),
            'O',
            false,
            true
        );

    });
</script>

<?php
   function radioBilan( $view, $path, $value, $label ) {
        $name = 'data['.implode( '][', explode( '.', $path ) ).']';
        $storedValue = Set::classicExtract( $view->data, $path );
        $checked = ( ( $storedValue == $value ) ? 'checked="checked"' : '' );
        return "<label><input type=\"radio\" name=\"{$name}\" value=\"{$value}\" {$checked} />{$label}</label>";
    }
?>

        <fieldset>
            <?php
                /// Traitement de l'orientation sans passage en EP locale
                $tmp = radioBilan( $this, 'Bilanparcours.proposition', 'traitement', 'Traitement de l\'orientation du dossier sans passage en EP Locale' );
                echo $html->tag( 'h3', $tmp );
            ?>
            <fieldset id="traitement" class="invisible">
                <?php
                    echo $default->subform(
                        array(
                            'Bilanparcours.maintienorientsansep' => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours.maintienorientsansep', true )  ), 'type' => 'radio', 'options' => $options['maintienorientsansep'] ),
                            'Bilanparcours.changementrefsansep' => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours.changementrefsansep', true )  ), 'type' => 'radio', 'options' => $options['changementrefsansep'] )
                        ),
                        array(
                            'options' => $options,
                            'domain' => $domain
                        )
                    );
                ?>
                <fieldset id="NvReferent">
                    <?php
                        echo $default->subform(
                            array(
                                'Bilanparcours.nvsansep_referent_id'
                            ),
                            array(
                                'options' => $options,
                                'domain' => $domain
                            )
                        );
                    ?>
                </fieldset>
                <fieldset id="Contratreconduit">
                    <legend>Reconduction du contrat librement débattu</legend>
                    <?php
                        echo $xform->input( 'Bilanparcours.datedebreconduction', array( 'div' => false, 'label' => 'Du ', 'dateFormat' => 'DMY', 'maxYear' => date('Y') + 2, 'minYear' => date('Y') - 2 ) ).' Au '.$xform->input( 'Bilanparcours.datefinreconduction', array( 'div' => false, 'label' => false, 'dateFormat' => 'DMY', 'maxYear' => date('Y') + 2, 'minYear' => date('Y') - 2 ) );
                    ?>
                </fieldset>
                <?php
                    echo $default->subform(
                        array(
                            'Bilanparcours.accordprojet' => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours.accordprojet', true )  ), 'type' => 'radio', 'options' => $options['accordprojet'] )
                        ),
                        array(
                            'options' => $options,
                            'domain' => $domain
                        )
                    );
                ?>
            </fieldset>
        </fieldset>
         <fieldset>
            <?php
                /// "Commission Parcours": Examen du dossier avec passage en EP Locale
                $tmp = radioBilan( $this, 'Bilanparcours.proposition', 'parcours', '"Commission Parcours": Examen du dossier avec passage en EP Locale' );
                echo $html->tag( 'h3', $tmp );
            ?>
            <fieldset id="parcours" class="invisible">
                <?php
                    echo $default->subform(
                        array(
                            'Bilanparcours.choixparcours' => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours.choixparcours', true )  ), 'type' => 'radio', 'options' => $options['choixparcours'] )
                        ),
                        array(
                            'options' => $options
                        )
                    );
                ?>
                <fieldset id="Maintien" class="invisible">
                    <?php
                         echo $default->subform(
                            array(
                                'Bilanparcours.maintienorientparcours' => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours.maintienorientparcours', true )  ), 'type' => 'radio', 'options' => $options['maintienorientparcours'] ),
                                'Bilanparcours.changementrefparcours' => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours.changementrefparcours', true )  ), 'type' => 'radio', 'options' => $options['changementrefparcours'] )
                            ),
                            array(
                                'options' => $options,
                                'domain' => $domain
                            )
                        );
                    ?>
                    <fieldset id="NvparcoursReferent">
                        <?php
                            echo $default->subform(
                                array(
                                    'Bilanparcours.nvparcours_referent_id'
                                ),
                                array(
                                    'options' => $options,
                                    'domain' => $domain
                                )
                            );
                        ?>
                    </fieldset>
                </fieldset>
                <fieldset id="Reorientation">
                    <?php
                        echo $default->subform(
                            array(
                                'Bilanparcours.reorientation' => array( 'legend' => false, 'type' => 'radio', 'options' => $options['reorientation'] )
                            ),
                            array(
                                'options' => $options,
                                'domain' => $domain
                            )
                        );
                    ?>
                </fieldset>
            </fieldset>
        </fieldset>
         <fieldset>
            <?php
                /// "Commission Audition": Examen du dossier par la commission EP Locale
                $tmp = radioBilan( $this, 'Bilanparcours.proposition', 'audition', '"Commission Audition": Examen du dossier par la commission EP Locale' );
                echo $html->tag( 'h3', $tmp );
            ?>
            <fieldset id="audition" class="invisible">
                <?php
                    echo $default->subform(
                        array(
                            'Bilanparcours.examenaudition' => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours.examenaudition', true )  ), 'type' => 'radio', 'options' => $options['examenaudition'] )
                        ),
                        array(
                            'options' => $options
                        )
                    );
                ?>
            </fieldset>
        </fieldset>

            <?php
                echo $default->subform(
                    array(
                        'Bilanparcours.infoscomplementaires'
                    ),
                    array(
                        'options' => $options
                    )
                );
            ?>
            <?php
                echo $default->subform(
                    array(
                        'Bilanparcours.observbenef',
                        'Bilanparcours.datebilan' => array( 'dateFormat' => 'DMY', 'maxYear' => date('Y') + 2, 'minYear' => date('Y') - 2, 'empty' => false ),
                    ),
                    array(
                        'options' => $options
                    )
                );
            ?>
    </fieldset>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'BilanparcoursAvisparcours', $( 'BilanparcoursInfoscompleplocale' ).up( 'fieldset' ), false );
        observeDisableFieldsetOnCheckbox( 'BilanparcoursAviscoordonnateur', $( 'BilanparcoursDateaviscoordonnateurDay' ).up( 'fieldset' ), false );
        observeDisableFieldsetOnCheckbox( 'BilanparcoursAviscga', $( 'BilanparcoursDateaviscgaDay' ).up( 'fieldset' ), false );


        observeDisableFieldsetOnRadioValue(
            'Bilan',
            'data[Bilanparcours][typeeplocale]',
            $( 'Epaudition' ),
            'audition',
            false,
            true
        );

        observeDisableFieldsetOnRadioValue(
            'Bilan',
            'data[Bilanparcours][typeeplocale]',
            $( 'Epparcours' ),
            'parcours',
            false,
            true
        );

        observeDisableFieldsetOnRadioValue(
            'Bilan',
            'data[Bilanparcours][typeeplocale]',
            $( 'Cga' ),
            'audition',
            false,
            true
        );

        observeDisableFieldsetOnRadioValue(
            'Bilan',
            'data[Bilanparcours][typeeplocale]',
            $( 'Coordonnateur' ),
            'parcours',
            false,
            true
        );

        observeDisableFieldsetOnRadioValue(
            'Bilan',
            'data[Bilanparcours][decisioncga]',
            $( 'motivationcga' ),
            'DEM',
            false,
            true
        );

        observeDisableFieldsetOnRadioValue(
            'Bilan',
            'data[Bilanparcours][decisioncoordonnateur]',
            $( 'motivationcoordonnateur' ),
            'DEM',
            false,
            true
        );
    });
</script>
    <fieldset>
        <?php
            echo $xform->input( 'Bilanparcours.avisparcours', array( 'label' => 'AVIS DE L\'EP LOCALE', 'type' => 'checkbox' ) );
        ?>
        <fieldset id="Avisparcours" class="invisible">
            <?php
                echo $default->subform(
                    array(
                        'Bilanparcours.typeeplocale'=> array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours.typeeplocale', true )  ), 'type' => 'radio', 'options' => $options['typeeplocale'] )
                    ),
                    array(
                        'domain' => $domain,
                        'options' => $options
                    )
                );
            ?>
            <fieldset id="Epparcours" class="invisible">
                <?php
                    echo $default->subform(
                        array(
                            'Bilanparcours.maintienorientavisep' => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours.maintienorientavisep', true )  ), 'type' => 'radio', 'options' => $options['maintienorientavisep'] ),
                            'Bilanparcours.changementrefeplocale'  => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours.changementrefeplocale', true )  ), 'type' => 'radio', 'options' => $options['changementrefeplocale'] ),
                            'Bilanparcours.reorientationeplocale'  => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours.reorientationeplocale', true )  ), 'type' => 'radio', 'options' => $options['reorientationeplocale'] ),
                            'Bilanparcours.dateaviseplocale'=> array( 'dateFormat' => 'DMY', 'maxYear' => date('Y') + 2, 'minYear' => date('Y') - 2, 'empty' => false )
                        ),
                        array(
                            'domain' => $domain,
                            'options' => $options
                        )
                    );
                ?>
            </fieldset>

            <fieldset id="Epaudition" class="invisible aere">
                <?php
                    echo $default->subform(
                        array(
                            'Bilanparcours.decisioncommission'  => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours.decisioncommission', true )  ), 'type' => 'radio', 'options' => $options['decisioncommission'] ),
                            'Bilanparcours.dateavisaudition'=> array( 'dateFormat' => 'DMY', 'maxYear' => date('Y') + 2, 'minYear' => date('Y') - 2, 'empty' => false )
                        ),
                        array(
                            'domain' => $domain,
                            'options' => $options
                        )
                    );
                ?>
            </fieldset>
            <?php
                echo $default->subform(
                    array(
                        'Bilanparcours.autreaviscommission' => array( 'type' => 'checkbox' ),
                        'Bilanparcours.infoscompleplocale'
                    ),
                    array(
                        'domain' => $domain,
                        'options' => $options
                    )
                );
            ?>
        </fieldset>
    </fieldset>

    <fieldset id="Coordonnateur">
        <?php
            echo $xform->input( 'Bilanparcours.aviscoordonnateur', array( 'label' => 'DÉCISION DU COORDONNATEUR', 'type' => 'checkbox' ) );
        ?>
        <fieldset id="Aviscoordonnateur" class="invisible">
            <?php
                echo $default->subform(
                    array(
                        'Bilanparcours.decisioncoordonnateur'  => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours.decisioncoordonnateur', true )  ), 'type' => 'radio', 'options' => $options['decisioncoordonnateur'] ),
                        'Bilanparcours.dateaviscoordonnateur'=> array( 'dateFormat' => 'DMY', 'maxYear' => date('Y') + 2, 'minYear' => date('Y') - 2, 'empty' => false ),
                    ),
                    array(
                        'domain' => $domain,
                        'options' => $options
                    )
                );
            ?>
            <fieldset id="motivationcoordonnateur" class="invisible">
                <?php
                    echo $default->subform(
                        array(
                            'Bilanparcours.motivationavis'
                        ),
                        array(
                            'domain' => $domain,
                            'options' => $options
                        )
                    );
                ?>
            </fieldset>
        </fieldset>
    </fieldset>

    <fieldset id="Cga">
        <?php
            echo $xform->input( 'Bilanparcours.aviscga', array( 'label' => 'DÉCISION DE LA CGA', 'type' => 'checkbox' ) );
        ?>
        <fieldset id="Aviscga" class="invisible">
            <?php
                echo $default->subform(
                    array(
                        'Bilanparcours.decisioncga'  => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours.decisioncga', true )  ), 'type' => 'radio', 'options' => $options['decisioncga'] ),
                        'Bilanparcours.dateaviscga'=> array( 'dateFormat' => 'DMY', 'maxYear' => date('Y') + 2, 'minYear' => date('Y') - 2, 'empty' => false )
                    ),
                    array(
                        'domain' => $domain,
                        'options' => $options
                    )
                );
            ?>
            <fieldset id="motivationcga" class="invisible">
                <?php
                    echo $default->subform(
                        array(
                            'Bilanparcours.motivationaviscga'
                        ),
                        array(
                            'domain' => $domain,
                            'options' => $options
                        )
                    );
                ?>
            </fieldset>
        </fieldset>
    </fieldset>

    </div>
    <div class="submit">
        <?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
        <?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
    </div>
    <?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>