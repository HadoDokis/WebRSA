<?php
    $domain = 'cui';
    echo $this->element( 'dossier_menu', array( 'id' => $dossier_id, 'personne_id' => $personne_id ) );
    echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
?>



<script type="text/javascript">
    document.observe("dom:loaded", function() {
        //Utilisé en cas d'adresse de l'employeur différente pour les doc administratifs
        observeDisableFieldsetOnRadioValue(
            'cuiform',
            'data[Cui][isadresse2]',
            $( 'Adressebis' ),
            'O',
            false,
            true
        );

        //Utilisé si les périodes sont des périodes de professionnalisation
        observeDisableFieldsetOnRadioValue(
            'cuiform',
            'data[Cui][secteur]',
            $( 'iscie' ),
            'CIE',
            false,
            true
        );

        //Utilisé si les périodes sont des périodes de professionnalisation
        observeDisableFieldsetOnRadioValue(
            'cuiform',
            'data[Cui][secteur]',
            $( 'iscae' ),
            'CAE',
            false,
            true
        );

        //Utilisé en cas de personne inscrite à Pole Emploi
        observeDisableFieldsetOnRadioValue(
            'cuiform',
            'data[Cui][isinscritpe]',
            $( 'InscritPE' ),
            'O',
            false,
            true
        );

        //Utilisé si l'employeur est un atelier ou un chantier d'insertion
        observeDisableFieldsOnRadioValue(
            'cuiform',
            'data[Cui][atelierchantier]',
            [
                'CuiNumannexefinanciere'
            ],
            'O',
            true
        );

        //Utilisé si l'employeur est un atelier ou un chantier d'insertion
        observeDisableFieldsetOnRadioValue(
            'cuiform',
            'data[Cui][rsadept]',
            $( 'IsRsaMaj' ),
            'O',
            false,
            true
        );

        //Utilisé si le type de contrat est un CDD
        observeDisableFieldsOnRadioValue(
            'cuiform',
            'data[Cui][typecontrat]',
            [
                'CuiDatefincontratYear',
                'CuiDatefincontratMonth',
                'CuiDatefincontratDay'
            ],
            'CDD',
            true
        );

        //Utilisé si les périodes sont des périodes de professionnalisation
        observeDisableFieldsetOnRadioValue(
            'cuiform',
            'data[Cui][isperiodepro]',
            $( 'niveauqualif' ),
            'O',
            false,
            true
        );
    });
</script>

<div class="with_treemenu">
    <?php
        echo $html->tag(
            'h1',
            $this->pageTitle = __d( 'cui', "Cuis::{$this->action}", true )
        );

        echo $html->tag(
            'p',
            '<strong>CONTRAT UNIQUE D\'INSERTION</strong>'.'<br />'.' CONVENTION ENTRE LE CONSEIL GÉNÉRAL, L\'EMPLOYEUR et LE SALARIÉ'.'<br />'.' ou L\'ÉTAT, L\'EMPLOYEUR et LE SALARIÉ',
            array(
                'class' => 'remarque center'
            )
        );
    ?>
    <?php
        echo $xform->create( 'Cui', array( 'id' => 'cuiform' ) );
        if( Set::check( $this->data, 'Cui.id' ) ){
            echo $xform->input( 'Cui.id', array( 'type' => 'hidden' ) );
            echo $xform->input( 'Cui.personne_id', array( 'type' => 'hidden' ) );
        }
    ?>
    <!-- <fieldset class="prescripteur">
        <legend>Cadre réservé au prescripteur</legend>
        <?php

            /*echo $default->subform(
                array(
                    'Cui.secteur' => array( 'div' => false, 'type' => 'radio', 'options' => $options['secteur'], 'legend' => required( __d( 'cui', 'Cui.secteur', true ) ) ),
                    'Cui.numsecteur' => array( 'class' => 'aere' ),
                    'Cui.avenant' => array( 'div' => false, 'type' => 'radio', 'options' => $options['avenant'], 'legend' => required( __d( 'cui', 'Cui.avenant', true )  ), ),
                    'Cui.numconventioncollect' => array( 'class' => 'aere' ),
                    'Cui.avenantcg' => array( 'div' => false, 'type' => 'radio', 'options' => $options['avenantcg'], 'legend' => required( __d( 'cui', 'Cui.avenant', true )  ), ),
                    'Cui.datedepot' => array( 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2 ),
                    'Cui.codeprescripteur',
                    'Cui.numeroide'
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );*/
        ?>
    </fieldset> -->
    <?php
        echo $default->subform(
            array(
                'Cui.personne_id' => array( 'value' => $personne_id, 'type' => 'hidden' ),
                'Cui.convention' => array( /*'div' => false,*/ 'legend' => required( __d( 'cui', 'Cui.convention', true )  ), 'type' => 'radio', 'options' => $options['convention'] ),
                'Cui.secteur' => array( /*'div' => false,*/ 'legend' => required( __d( 'cui', 'Cui.secteur', true )  ), 'type' => 'radio', 'options' => $options['secteur'] )
            ),
            array(
                'domain' => $domain,
                'options' => $options
            )
        );
    ?>

<!--**************************************** Partie EMPLOYEUR *********************************************** -->
    <fieldset>
        <legend>L'EMPLOYEUR</legend>
        <table class="noborder">
            <tr>
                <td class="cui1 noborder">
                    <fieldset>
                        <?php

                            echo $default->subform(
                                array(
                                    'Cui.nomemployeur',
                                    'Cui.numvoieemployeur',
                                    'Cui.typevoieemployeur' => array( 'empty' => true, 'options' => $options['typevoie'] ),
                                    'Cui.nomvoieemployeur',
                                    'Cui.compladremployeur',
                                    'Cui.numtelemployeur',
                                    'Cui.emailemployeur',
                                    'Cui.codepostalemployeur',
                                    'Cui.villeemployeur'
                                ),
                                array(
                                    'domain' => $domain,
                                    'options' => $options
                                )
                            );

                            echo $html->tag(
                                'p',
                                'Si l\'adresse à laquelle les documents administratifs et financiers doivent etre envoyés est différente de l\'adresse ci-dessus, remplir la partie ci-dessous',
                                array(
                                    'class' => 'remarque'
                                )
                            );

                            $error = Set::classicExtract( $this->validationErrors, 'Cui.isadresse2' );
                            $class = 'radio'.( !empty( $error ) ? ' error' : '' );
                            $thisDataAdressebis = Set::classicExtract( $this->data, 'Cui.isadresse2' );
                            if( !empty( $thisDataAdressebis ) ) {
                                $valueAdressebis = $thisDataAdressebis;
                            }
                            $input =  $form->input( 'Cui.isadresse2', array( 'type' => 'radio' , 'options' => $options['isadresse2'], 'div' => false, 'legend' => required( __d( 'cui', 'Cui.isadresse2', true )  ), 'value' => $valueAdressebis ) );
                            echo $html->tag( 'div', $input, array( 'class' => $class ) );

                        ?>
                        <fieldset id="Adressebis">
                            <?php
                                echo $default->subform(
                                    array(
                                        'Cui.numvoieemployeur2',
                                        'Cui.typevoieemployeur2' => array( 'empty' => true, 'options' => $options['typevoie'] ),
                                        'Cui.nomvoieemployeur2',
                                        'Cui.compladremployeur2',
                                        'Cui.numtelemployeur2',
                                        'Cui.emailemployeur2',
                                        'Cui.codepostalemployeur2',
                                        'Cui.villeemployeur2'
                                    ),
                                    array(
                                        'domain' => $domain,
                                        'options' => $options
                                    )
                                );

                            ?>
                        </fieldset>
                    </fieldset>
                </td>
                <td class="cui2 noborder">
                    <fieldset>
                        <?php
                            echo $default->subform(
                                array(
                                    'Cui.siret',
                                    'Cui.codenaf2',
                                    'Cui.identconvcollec',
                                    'Cui.statutemployeur' => array( 'empty' => true, 'options' => $options['statutemployeur'] ),
                                    'Cui.effectifemployeur'
                                ),
                                array(
                                    'domain' => $domain,
                                    'options' => $options
                                )
                            );
                            echo $html->tag( 'p', 'Paiement par virement: fournir un RIB de l\'employeur', array( 'class' => 'remarque center' ) );
                        ?>
                    </fieldset>
                        <?php
                            echo $default->subform(
                                array(
                                    'Cui.orgrecouvcotis' => array( /*'div' => false, */'legend' => required( __d( 'cui', 'Cui.orgrecouvcotis', true )  ), 'type' => 'radio', 'options' => $options['orgrecouvcotis'] )
                                ),
                                array(
                                    'domain' => $domain,
                                    'options' => $options
                                )
                            );
                        ?>

                </td>
            </tr>
        </table>

        <table class="noborder">
            <tr>
                <td class="cui3 noborder">

                        <?php
                            echo $default->subform(
                                array(
                                    'Cui.atelierchantier' => array( /*'div' => false,*/ 'legend' => required( __d( 'cui', 'Cui.atelierchantier', true )  ), 'type' => 'radio', 'options' => $options['atelierchantier'] ),
                                    'Cui.numannexefinanciere',
                                    'Cui.assurancechomage' => array( /*'div' => false,*/ 'separator' => '<br />', 'legend' => required( __d( 'cui', 'Cui.assurancechomage', true )  ), 'type' => 'radio', 'options' => $options['assurancechomage'] )
                                ),
                                array(
                                    'domain' => $domain,
                                    'options' => $options
                                )
                            );
                        ?>
                </td>
            </tr>
        </table>
        <fieldset id="iscie" class="invisible">
            <?php
                echo $html->tag( 'p', 'Si CIE, je déclare sur l\'honneur être à jour des versements de mes cotisations et contributions sociales, que cette embauche ne résulte pas du licenciement d\'un salarié en CDI, ne pas avoir procédé à un licenciement pour motif économique au cours des 6 derniers mois ou pour une raison autre que la faute grave' );
                echo $default->subform(
                    array(
                        'Cui.iscie' => array( 'label' => false, 'type' => 'radio', 'options' => $options['iscie']  )
                    ),
                    array(
                        'domain' => $domain,
                        'options' => $options
                    )
                );
            ?>
        </fieldset>
    </fieldset>

<!--**************************************** Partie SALARIE *********************************************** -->
<?php
    /**
    *   Fonction pour récupérer le nom du département de la personne
    *   On récupère le code postal et on récupère les 2 premiers chiffres que l'on
    *   compare avec la table Departements nouvellement créée
    */
    $codepos = Set::classicExtract( $personne, 'Adresse.codepos' );
    $depSplit = substr( $codepos, '0', 2 );
?>
    <fieldset>
        <legend>LE SALARIÉ</legend>
        <table class="wide noborder">
            <tr>
                <td class="mediumSize noborder">
                    <strong>Nom : </strong><?php echo Set::enum( Set::classicExtract( $personne, 'Personne.qual'), $qual ).' '.Set::classicExtract( $personne, 'Personne.nom' );?>
                    <br />
                    <?php if(  Set::classicExtract( $personne, 'Personne.qual') != 'MR' ):?>
                        <strong>Pour les femmes, nom patronymique : </strong><?php echo Set::classicExtract( $personne, 'Personne.nomnai' );?>
                    <?php endif;?>
                    <br />
                    <strong>Né(e) le : </strong>
                        <?php
                            echo date_short( Set::classicExtract( $personne, 'Personne.dtnai' ) ).' <strong>à</strong>  '.Set::classicExtract( $personne, 'Personne.nomcomnai' );
                        ?>
                    <br />
                    <strong>Adresse : </strong><br />
                        <?php
                            echo Set::extract( $personne, 'Adresse.numvoie' ).' '.Set::extract( $options['typevoie'], Set::extract( $personne, 'Adresse.typevoie' ) ).' '.Set::extract( $personne, 'Adresse.nomvoie' ).'<br /> '.Set::extract( $personne, 'Adresse.compladr' ).'<br /> '.Set::extract( $personne, 'Adresse.codepos' ).' '.Set::extract( $personne, 'Adresse.locaadr' );
                        ?>
                    <br />
                    <!-- Si on n'autorise pas la diffusion de l'email, on n'affiche rien -->
                    <?php if( Set::extract( $personne, 'Foyer.Modecontact.0.Modecontact.autorutiadrelec' ) == 'A' ):?>
                        <strong>Adresse électronique : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.0.Modecontact.adrelec' );?>
                    <?php endif;?>
                </td>
                <td class="mediumSize noborder">
                    <strong>Prénoms : </strong><?php echo Set::classicExtract( $personne, 'Personne.prenom' );?>
                    <br />
                    <strong>NIR : </strong><?php echo Set::classicExtract( $personne, 'Personne.nir');?>
                    <br />
                    <strong>Département : </strong><?php echo Set::extract( $depSplit, $dept );?>
                    <br />
                    <strong>Nationalité : </strong><?php echo Set::enum( Set::classicExtract( $personne, 'Personne.nati' ), $nationalite );?>
                    <br />
                    <!-- Si on n'autorise aps la diffusion du téléphone, on n'affiche rien -->
                    <?php if( Set::extract( $personne, 'Foyer.Modecontact.0.Modecontact.autorutitel' ) == 'A' ):?>
                        <strong>Numéro de téléphone 1 : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.0.Modecontact.numtel' );?>
                        <br />
                        <strong>Numéro de téléphone 2 : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.1.Modecontact.numtel' );?>
                    <?php endif;?>
                </td>
            </tr>
            <tr>
                <td class="noborder" colspan="2">
                    <strong>Si bénéficiaire RSA, n° allocataire : </strong>
                    <?php
                        echo Set::classicExtract( $personne, 'Foyer.Dossier.matricule' ).'  <strong>relève de : </strong> '.Set::classicExtract( $personne, 'Foyer.Dossier.fonorg' );
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>

<!--********************* Situation SALARIE avant la signature de la convention ********************** -->

    <fieldset>
        <legend>SITUATION DU SALARIE AVANT LA SIGNATURE DE LA CONVENTION </legend>
            <?php
                echo $default->subform(
                    array(
                        'Cui.niveauformation'  => array( 'empty' => true, 'options' => $options['niveauformation'] ),
                        'Cui.dureesansemploi' => array( 'legend' => required( __d( 'cui', 'Cui.dureesansemploi', true )  ), 'type' => 'radio', 'options' => $options['dureesansemploi'] )
                    ),
                    array(
                        'domain' => $domain,
                        'options' => $options
                    )
                );

                $error = Set::classicExtract( $this->validationErrors, 'Cui.isisncritpe' );
                $class = 'radio'.( !empty( $error ) ? ' error' : '' );
                $thisDataInscritPE = Set::classicExtract( $this->data, 'Cui.isisncritpe' );
                if( !empty( $thisDataInscritPE ) ) {
                    $valueInscritPE = $thisDataInscritPE;
                }

                $input =  $form->input( 'Cui.isinscritpe', array( 'type' => 'radio' , 'options' => $options['isinscritpe'], /*'div' => false,*/ 'legend' => required( __d( 'cui', 'Cui.isinscritpe', true )  ), 'value' => $valueInscritPE ) );
                echo $html->tag( 'div', $input, array( 'class' => $class ) );
            ?>
            <fieldset id="InscritPE" class="invisible">
                <?php
                    echo $default->subform(
                        array(
                            'Cui.dureeinscritpe' => array( 'legend' => required( __d( 'cui', 'Cui.dureeinscritpe', true )  ), 'type' => 'radio', 'options' => $options['dureeinscritpe'] ),
                        ),
                        array(
                            'domain' => $domain,
                            'options' => $options
                        )
                    );
                ?>

            </fieldset>
                <?php  echo $html->tag( 'p', 'Le salarié est-il bénéficiaire' ); ?>
                <table class="noborder">
                    <tr>
                        <td class="cui4 noborder">
                            <?php
                                echo $default->subform(
                                    array(
                                        'Cui.ass' => array( 'label' => required( __d( 'cui', 'Cui.ass', true )  ), 'type' => 'radio', 'options' => $options['ass'] ),
                                        'Cui.aah' => array( 'label' => required( __d( 'cui', 'Cui.aah', true )  ), 'type' => 'radio', 'options' => $options['aah'] )
                                    ),
                                    array(
                                        'domain' => $domain,
                                        'options' => $options
                                    )
                                );
                            ?>

                        </td>
                        <td class="cui4 noborder">
                            <?php
                                echo $default->subform(
                                    array(
                                        'Cui.rsadept' => array( 'label' => required( __d( 'cui', 'Cui.rsadept', true )  ), 'type' => 'radio', 'options' => $options['rsadept'] ),
                                        'Cui.ata' => array( 'label' => required( __d( 'cui', 'Cui.ata', true )  ), 'type' => 'radio', 'options' => $options['ata'] )
                                    ),
                                    array(
                                        'domain' => $domain,
                                        'options' => $options
                                    )
                                );
                            ?>
                        </td>
                        <td class="cui4 noborder">
                            <fieldset class="invisible" id="IsRsaMaj">
                                <?php
                                    echo $default->subform(
                                        array(
                                            'Cui.rsadeptmaj' => array( 'label' => required( __d( 'cui', 'Cui.rsadeptmaj', true ) ), 'type' => 'radio', 'options' => $options['rsadeptmaj'] )
                                        ),
                                        array(
                                            'domain' => $domain,
                                            'options' => $options
                                        )
                                    );
                                ?>
                            </fieldset>
                        </td>

                    </tr>
                </table>

                <fieldset id="IsBeneficiaire">
                    <?php
                        echo $default->subform(
                            array(
                                'Cui.dureebenefaide' => array( 'label' => required( __d( 'cui', 'Cui.dureebenefaide', true )  ), 'type' => 'radio', 'options' => $options['dureebenefaide'] )
                            ),
                            array(
                                'domain' => $domain,
                                'options' => $options
                            )
                        );
                        echo $html->tag( 'p', '( Pour les bénéficiaires du RSA, y compris la période antérieure au 01/06/2009 en RMI ou API )', array( 'class' => 'remarque' ) );
                    ?>
                </fieldset>
                <?php
                    echo $default->subform(
                        array(
                            'Cui.handicap' => array( 'label' => required( __d( 'cui', 'Cui.handicap', true )  ), 'type' => 'radio', 'options' => $options['handicap'] )
                        ),
                        array(
                            'domain' => $domain,
                            'options' => $options
                        )
                    );
                ?>
    </fieldset>

<!--********************* Le contrat de travail ********************** -->

    <fieldset>
        <legend>LE CONTRAT DE TRAVAIL</legend>
        <?php
            echo $default->subform(
                array(
                    'Cui.typecontrat' => array( 'label' => required( __d( 'cui', 'Cui.typecontrat', true )  ), 'type' => 'radio', 'options' => $options['typecontrat'] )
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );
        ?>
        <table class="cui3 noborder">
            <tr>
                <td class=" noborder">
                    <?php
                        echo $default->subform(
                            array(
                                'Cui.dateembauche' => array( 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2 ),
                            ),
                            array(
                                'domain' => $domain,
                                'options' => $options
                            )
                        );
                    ?>
                </td>
                <td class=" noborder">
                    <?php
                        echo $default->subform(
                            array(
                                'Cui.datefincontrat' => array( 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2 )
                            ),
                            array(
                                'domain' => $domain,
                                'options' => $options
                            )
                        );
                    ?>
                </td>
            </tr>
        </table>
        <?php
            echo $default->subform(
                array(
                    'Cui.codeemploi',
                    'Cui.salairebrut'
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );
        ?>
        <table class="cui3 noborder">
            <tr>
                <td class=" noborder">
                    <?php
                        echo $default->subform(
                            array(
                                 'Cui.dureehebdosalarie' => array( 'label' =>  required( __d( 'cui', 'Cui.dureehebdosalarie', true ) ), 'type' => 'time', 'timeFormat' => '24','minuteInterval'=> 5,  'empty' => true, 'hourRange' => array( 8, 19 ) ),
                            ),
                            array(
                                'domain' => $domain,
                                'options' => $options
                            )
                        );
                    ?>
                </td>
                <td class=" noborder">
                    <?php
                        echo $default->subform(
                            array(
                                'Cui.modulation' => array( 'label' => required( __d( 'cui', 'Cui.modulation', true )  ), 'type' => 'radio', 'options' => $options['modulation'] )
                            ),
                            array(
                                'domain' => $domain,
                                'options' => $options
                            )
                        );
                    ?>
                </td>
            </tr>
        </table>
        <?php
            echo $default->subform(
                array(
                    'Cui.dureecollectivehebdo' => array( 'label' =>  required( __d( 'cui', 'Cui.dureehebdosalarie', true ) ), 'type' => 'time', 'timeFormat' => '24','minuteInterval'=> 5,  'empty' => true, 'hourRange' => array( 8, 19 ) )
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );

            echo $html->tag( 'p', 'Lieu d\'exécution du contrat s\'il eest différent de l\'adresse de l\'employeur :' );
            echo $default->subform(
                array(
                    'Cui.numlieucontrat',
                    'Cui.typevoielieucontrat' => array( 'empty' => true, 'options' => $options['typevoie'] ),
                    'Cui.nomvoielieucontrat',
                    'Cui.codepostallieucontrat',
                    'Cui.villelieucontrat'
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );
        ?>

    </fieldset>

<!--********************* Les actions d'accompagnement et de formation prévues ********************** -->
    <fieldset>
        <legend>LES ACTIONS D'ACCOMPAGNEMENT ET DE FORMATION PRÉVUES</legend>
        <?php
            echo $default->subform(
               array(
                    'Cui.qualtuteur' => array( 'empty' => true, 'options' => $qual ),
                    'Cui.nomtuteur',
                    'Cui.prenomtuteur',
                    'Cui.fonctiontuteur',
                    'Cui.structurereferente_id',
                    'Cui.referent_id' => array( 'options' => $referents, 'empty' => true ),
                    'Cui.isaas' => array( 'label' => __d( 'cui', 'Cui.isaas', true ), 'type' => 'radio', 'options' => $options['isaas'] )
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );
        ?>
        <table class="cui5 noborder">
            <tr>
                <td class="noborder">
                    <?php
                        echo $html->tag(
                            'p',
                            'Actions d\'accompagnement professionnel',
                            array(
                                'class' => 'center'
                            )
                        );
                    ?>
                </td>
                <td class="noborder">
                    <?php
                        echo $html->tag(
                            'p',
                            'Actions de formation',
                            array(
                                'class' => 'center'
                            )
                        );
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="noborder">
                    <?php
                        echo $html->tag(
                            'div',
                            'Indiquez 1, 2 ou 3 dans la case selon que l\'action est mobilisée à l\'initiative de: 1 l\'employeur, 2 le salarié, 3 le prescripteur',
                            array(
                                'class' => 'remarque aere'
                            )
                        );
                    ?>
                </td>
            </tr>
            <tr>
                <td class="cui5 noborder">
                    <?php
                        echo $html->tag( 'p', 'Type d\'actions : ' );
                        echo $default->subform(
                            array(
                                'Cui.remobilisation' => array( 'type' => 'select', 'empty' => true, 'options' => $options['remobilisation'] ),
                                'Cui.aidereprise' => array( 'type' => 'select', 'empty' => true, 'options' => $options['aidereprise'] ),
                                'Cui.elaboprojetpro' => array( 'type' => 'select', 'empty' => true, 'options' => $options['elaboprojetpro'] ),
                                'Cui.evaluation' => array( 'type' => 'select', 'empty' => true, 'options' => $options['evaluation'] ),
                                'Cui.aiderechemploi' => array( 'type' => 'select', 'empty' => true, 'options' => $options['aiderechemploi'] ),
                                'Cui.autre' => array( 'type' => 'text' )/*,
                                'Cui.precisionautre' => array( 'type' => 'text' )*/
                            ),
                            array(
                                'domain' => $domain,
                                'options' => $options
                            )
                        );
                    ?>
                </td>
                <td class="cui5 noborder">
                    <?php
                        echo $html->tag( 'p', 'Type d\'actions : ' );
                        echo $default->subform(
                            array(
                                'Cui.adaptation' => array( 'type' => 'select', 'empty' => true, 'options' => $options['adaptation'] ),
                                'Cui.remiseniveau' => array( 'type' => 'select', 'empty' => true, 'options' => $options['remiseniveau'] ),
                                'Cui.prequalification' => array( 'type' => 'select', 'empty' => true, 'options' => $options['prequalification'] ),
                                'Cui.nouvellecompetence' => array( 'type' => 'select', 'empty' => true, 'options' => $options['nouvellecompetence'] ),
                                'Cui.formqualif' => array( 'type' => 'select', 'empty' => true, 'options' => $options['formqualif'] ),
                                'Cui.formation' => array( 'type' => 'radio', 'label' => __d( 'cui', 'Cui.formation', true ), 'options' => $options['formation'] ),
                                'Cui.isperiodepro' => array( 'type' => 'radio', 'label' => __d( 'cui', 'Cui.isperiodepro', true ), 'options' => $options['isperiodepro'] )
                            ),
                            array(
                                'domain' => $domain,
                                'options' => $options
                            )
                        );
                    ?>
                    <fieldset id="niveauqualif" class="invisible">
                        <?php
                            echo $default->subform(
                                array(
                                    'Cui.niveauqualif' => array( 'options' => $options['niveauformation'], 'empty' => true )
                                ),
                                array(
                                    'domain' => $domain,
                                    'options' => $options
                                )
                            );
                        ?>
                    </fieldset>
                    <?php
                        echo $html->tag( 'p', 'Une ou plusieurs de ces actions s\'inscrivent elles dans le cadre de la validation des acquis de l\'expérience ?' );
                        echo $default->subform(
                            array(
                                'Cui.validacquis' => array( 'type' => 'radio', 'legend' => false, 'options' => $options['validacquis'] )
                            ),
                            array(
                                'domain' => $domain,
                                'options' => $options
                            )
                        );
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset id="iscae" class="invisible">
        <?php
            echo $default->subform(
                array(
                    'Cui.iscae' => array( 'type' => 'radio', 'legend' => __d( 'cui', 'Cui.iscae', true ), 'options' => $options['iscae'] )
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );
        ?>
    </fieldset>



    <div class="submit">
        <?php
            echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
            echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
        ?>
    </div>
    <?php echo $xform->end();?>
</div>

<div class="clearer"><hr /></div>