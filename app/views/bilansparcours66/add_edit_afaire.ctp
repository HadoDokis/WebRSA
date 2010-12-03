<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    $domain = 'bilanparcours';

    echo $this->element( 'dossier_menu', array( 'personne_id' => Set::classicExtract( $personne, 'Personne.id') ) );
?>

<?php
    if( $this->action == 'add'  ) {
        if( Configure::read( 'nom_form_bilan_cg' ) == 'cg66' ) {
            $this->pageTitle = 'Ajout d\'un bilan de parcours';
        }
        else {
            $this->pageTitle = 'Ajout d\'une fiche de saisine';
        }
    }
    else {
        if( Configure::read( 'nom_form_bilan_cg' ) == 'cg66' ) {
            $this->pageTitle = 'Édition du bilan de parcours';
        }
        else {
            $this->pageTitle = 'Édition de la fiche de saisine';
        }
    }
?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php
        if( $this->action == 'add' ) {
            echo $form->create( 'Bilanparcours66', array( 'type' => 'post', 'url' => Router::url( null, true ),  'id' => 'Bilan' ) );
        }
        else {
            echo $form->create( 'Bilanparcours66', array( 'type' => 'post', 'url' => Router::url( null, true ), 'id' => 'Bilan' ) );
            echo '<div>';
            echo $form->input( 'Bilanparcours66.id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }
        echo '<div>';
        echo $form->input( 'Bilanparcours66.personne_id', array( 'type' => 'hidden', 'value' => Set::classicExtract( $personne, 'Personne.id') ) );
        echo '</div>';
    ?>

<?php echo $javascript->link( 'dependantselect.js' ); ?>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        dependantSelect( 'Bilanparcours66ReferentId', 'Bilanparcours66StructurereferenteId' );
    });
</script>

    <div class="aere">
    <fieldset class="aere">
        <legend>BILAN DU PARCOURS ( Rédigé en présence du bénéficiaire )</legend>
            <?php
                echo $default->subform(
                    array(
                        'Bilanparcours66.structurereferente_id',
                        'Bilanparcours66.referent_id'
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
                        <strong>Adresse : </strong><br /><?php echo Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.numvoie' ).' '.Set::enum( Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.typevoie' ), $options['typevoie'] ).' '.Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.nomvoie' ).'<br /> '.Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.codepos' ).' '.Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.locaadr' );?>
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
                        'Bilanparcours66.objinit' => array('type' => 'textarea'),
                        'Bilanparcours66.objatteint' => array('type' => 'textarea'),
                        'Bilanparcours66.objnew' => array('type' => 'textarea'),
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
                'data[Bilanparcours66][proposition]',
                $( proposition ),
                proposition,
                false,
                true
            );
        } );

        // Partie en cas de changment ou non du référent
        observeDisableFieldsetOnRadioValue(
            'Bilan',
            'data[Bilanparcours66][changementrefsansep]',
            $( 'NvReferent' ),
            'O',
            false,
            true
        );

        observeDisableFieldsetOnRadioValue(
            'Bilan',
            'data[Bilanparcours66][changementrefsansep]',
            $( 'Contratreconduit' ),
            'N',
            false,
            true
        );

        // Partie en cas de maintien ou  de réorientation
        observeDisableFieldsetOnRadioValue(
            'Bilan',
            'data[Bilanparcours66][choixparcours]',
            $( 'Maintien' ),
            'maintien',
            false,
            true
        );

        observeDisableFieldsetOnRadioValue(
            'Bilan',
            'data[Bilanparcours66][choixparcours]',
            $( 'Reorientation' ),
            'reorientation',
            false,
            true
        );

        observeDisableFieldsetOnRadioValue(
            'Bilan',
            'data[Bilanparcours66][changementrefparcours]',
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
                $tmp = radioBilan( $this, 'Bilanparcours66.proposition', 'traitement', 'Traitement de l\'orientation du dossier sans passage en EP Locale' );
                echo $xhtml->tag( 'h3', $tmp );
            ?>
            <fieldset id="traitement" class="invisible">
                <?php
                    echo $default->subform(
                        array(
                            'Bilanparcours66.maintienorientsansep' => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours66.maintienorientsansep', true )  ), 'type' => 'radio', 'options' => $options['maintienorientsansep'] ),
                            'Bilanparcours66.changementrefsansep' => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours66.changementrefsansep', true )  ), 'type' => 'radio', 'options' => $options['changementrefsansep'] )
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
                                'Bilanparcours66.nvsansep_referent_id'
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
                        echo $xform->input( 'Bilanparcours66.datedebreconduction', array( 'div' => false, 'label' => 'Du ', 'dateFormat' => 'DMY', 'maxYear' => date('Y') + 2, 'minYear' => date('Y') - 2 ) ).' Au '.$xform->input( 'Bilanparcours66.datefinreconduction', array( 'div' => false, 'label' => false, 'dateFormat' => 'DMY', 'maxYear' => date('Y') + 2, 'minYear' => date('Y') - 2 ) );
                    ?>
                </fieldset>
                <?php
                    echo $default->subform(
                        array(
                            'Bilanparcours66.accordprojet' => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours66.accordprojet', true )  ), 'type' => 'radio', 'options' => $options['accordprojet'] )
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
                $tmp = radioBilan( $this, 'Bilanparcours66.proposition', 'parcours', '"Commission Parcours": Examen du dossier avec passage en EP Locale' );
                echo $xhtml->tag( 'h3', $tmp );
            ?>
            <fieldset id="parcours" class="invisible">
                <?php
                    echo $default->subform(
                        array(
                            'Bilanparcours66.choixparcours' => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours66.choixparcours', true )  ), 'type' => 'radio', 'options' => $options['choixparcours'] )
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
                                'Bilanparcours66.maintienorientparcours' => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours66.maintienorientparcours', true )  ), 'type' => 'radio', 'options' => $options['maintienorientparcours'] ),
                                'Bilanparcours66.changementrefparcours' => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours66.changementrefparcours', true )  ), 'type' => 'radio', 'options' => $options['changementrefparcours'] )
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
                                    'Bilanparcours66.nvparcours_referent_id'
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
                                'Bilanparcours66.reorientation' => array( 'legend' => false, 'type' => 'radio', 'options' => $options['reorientation'] )
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
                $tmp = radioBilan( $this, 'Bilanparcours66.proposition', 'audition', '"Commission Audition": Examen du dossier par la commission EP Locale' );
                echo $xhtml->tag( 'h3', $tmp );
            ?>
            <fieldset id="audition" class="invisible">
                <?php
                    echo $default->subform(
                        array(
                            'Bilanparcours66.examenaudition' => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours66.examenaudition', true )  ), 'type' => 'radio', 'options' => $options['examenaudition'] )
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
                        'Bilanparcours66.infoscomplementaires'
                    ),
                    array(
                        'options' => $options
                    )
                );
            ?>
            <?php
                echo $default->subform(
                    array(
                        'Bilanparcours66.observbenef',
                        'Bilanparcours66.datebilan' => array( 'dateFormat' => 'DMY', 'maxYear' => date('Y') + 2, 'minYear' => date('Y') - 2, 'empty' => false ),
                    ),
                    array(
                        'options' => $options
                    )
                );
            ?>
    </fieldset>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'Bilanparcours66Avisparcours', $( 'Bilanparcours66Infoscompleplocale' ).up( 'fieldset' ), false );
        observeDisableFieldsetOnCheckbox( 'Bilanparcours66Aviscoordonnateur', $( 'Bilanparcours66DateaviscoordonnateurDay' ).up( 'fieldset' ), false );
        observeDisableFieldsetOnCheckbox( 'Bilanparcours66Aviscga', $( 'Bilanparcours66DateaviscgaDay' ).up( 'fieldset' ), false );


        observeDisableFieldsetOnRadioValue(
            'Bilan',
            'data[Bilanparcours66][typeeplocale]',
            $( 'Epaudition' ),
            'audition',
            false,
            true
        );

        observeDisableFieldsetOnRadioValue(
            'Bilan',
            'data[Bilanparcours66][typeeplocale]',
            $( 'Epparcours' ),
            'parcours',
            false,
            true
        );

        observeDisableFieldsetOnRadioValue(
            'Bilan',
            'data[Bilanparcours66][typeeplocale]',
            $( 'Cga' ),
            'audition',
            false,
            true
        );

        observeDisableFieldsetOnRadioValue(
            'Bilan',
            'data[Bilanparcours66][typeeplocale]',
            $( 'Coordonnateur' ),
            'parcours',
            false,
            true
        );

        observeDisableFieldsetOnRadioValue(
            'Bilan',
            'data[Bilanparcours66][decisioncga]',
            $( 'motivationcga' ),
            'DEM',
            false,
            true
        );

        observeDisableFieldsetOnRadioValue(
            'Bilan',
            'data[Bilanparcours66][decisioncoordonnateur]',
            $( 'motivationcoordonnateur' ),
            'DEM',
            false,
            true
        );
    });
</script>
    <fieldset>
        <?php
            echo $xform->input( 'Bilanparcours66.avisparcours', array( 'label' => 'AVIS DE L\'EP LOCALE', 'type' => 'checkbox' ) );
        ?>
        <fieldset id="Avisparcours" class="invisible">
            <?php
                echo $default->subform(
                    array(
                        'Bilanparcours66.typeeplocale'=> array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours66.typeeplocale', true )  ), 'type' => 'radio', 'options' => $options['typeeplocale'] )
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
                            'Bilanparcours66.maintienorientavisep' => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours66.maintienorientavisep', true )  ), 'type' => 'radio', 'options' => $options['maintienorientavisep'] ),
                            'Bilanparcours66.changementrefeplocale'  => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours66.changementrefeplocale', true )  ), 'type' => 'radio', 'options' => $options['changementrefeplocale'] ),
                            'Bilanparcours66.reorientationeplocale'  => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours66.reorientationeplocale', true )  ), 'type' => 'radio', 'options' => $options['reorientationeplocale'] ),
                            'Bilanparcours66.dateaviseplocale'=> array( 'dateFormat' => 'DMY', 'maxYear' => date('Y') + 2, 'minYear' => date('Y') - 2, 'empty' => false )
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
                            'Bilanparcours66.decisioncommission'  => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours66.decisioncommission', true )  ), 'type' => 'radio', 'options' => $options['decisioncommission'] ),
                            'Bilanparcours66.dateavisaudition'=> array( 'dateFormat' => 'DMY', 'maxYear' => date('Y') + 2, 'minYear' => date('Y') - 2, 'empty' => false )
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
                        'Bilanparcours66.autreaviscommission' => array( 'type' => 'checkbox' ),
                        'Bilanparcours66.infoscompleplocale'
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
            echo $xform->input( 'Bilanparcours66.aviscoordonnateur', array( 'label' => 'DÉCISION DU COORDONNATEUR', 'type' => 'checkbox' ) );
        ?>
        <fieldset id="Aviscoordonnateur" class="invisible">
            <?php
                echo $default->subform(
                    array(
                        'Bilanparcours66.decisioncoordonnateur'  => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours66.decisioncoordonnateur', true )  ), 'type' => 'radio', 'options' => $options['decisioncoordonnateur'] ),
                        'Bilanparcours66.dateaviscoordonnateur'=> array( 'dateFormat' => 'DMY', 'maxYear' => date('Y') + 2, 'minYear' => date('Y') - 2, 'empty' => false ),
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
                            'Bilanparcours66.motivationavis'
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
            echo $xform->input( 'Bilanparcours66.aviscga', array( 'label' => 'DÉCISION DE LA CGA', 'type' => 'checkbox' ) );
        ?>
        <fieldset id="Aviscga" class="invisible">
            <?php
                echo $default->subform(
                    array(
                        'Bilanparcours66.decisioncga'  => array( 'legend' => required( __d( 'bilanparcours', 'Bilanparcours66.decisioncga', true )  ), 'type' => 'radio', 'options' => $options['decisioncga'] ),
                        'Bilanparcours66.dateaviscga'=> array( 'dateFormat' => 'DMY', 'maxYear' => date('Y') + 2, 'minYear' => date('Y') - 2, 'empty' => false )
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
                            'Bilanparcours66.motivationaviscga'
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
