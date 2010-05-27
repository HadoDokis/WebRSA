<?php
    $domain = 'cui';
    echo $this->element( 'dossier_menu', array( 'id' => $dossier_id, 'personne_id' => $personne_id ) );
    echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
?>



<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnRadioValue(
            'cuiform',
            'data[Cui][isadresse2]',
            $( 'Adressebis' ),
            'O',
            false,
            true
        );

        observeDisableFieldsOnRadioValue(
            'cuiform',
            'data[Cui][atelierchantier]',
            [
                'CuiNumannexefinanciere'
            ],
            'O',
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
                                    'Cui.statutemployeur',
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
                                    'Cui.assurancechomage' => array( /*'div' => false,*/ 'separator' => '<br />', 'legend' => required( __d( 'cui', 'Cui.assurancechomage', true )  ), 'type' => 'radio', 'options' => $options['assurancechomage'] ),
                                    'Cui.iscie' => array( 'div' => false, 'label' => required( __d( 'cui', 'Cui.iscie', true )  ), 'type' => 'checkbox' )
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
                        'Cui.niveauformation' => array( 'legend' => required( __d( 'cui', 'Cui.niveauformation', true )  ), 'type' => 'radio', 'options' => $options['niveauformation'] ),
                        'Cui.dureesansemploi' => array( 'legend' => required( __d( 'cui', 'Cui.dureesansemploi', true )  ), 'type' => 'radio', 'options' => $options['dureesansemploi'] ),
                        'Cui.dureeinscritpe' => array( 'separator' => '<br />', 'legend' => required( __d( 'cui', 'Cui.dureeinscritpe', true )  ), 'type' => 'radio', 'options' => $options['dureeinscritpe'] ),
                        'Cui.ass' => array( 'label' => required( __d( 'cui', 'Cui.iscie', true )  ), 'type' => 'checkbox' )
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