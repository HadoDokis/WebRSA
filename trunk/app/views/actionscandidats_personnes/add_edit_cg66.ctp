<?php
    $domain = "actioncandidat_personne_".Configure::read( 'ActioncandidatPersonne.suffixe' );
    echo $this->element( 'dossier_menu', array( 'id' => $dossierId, 'personne_id' => $personne_id ) );
    echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
?>

<script type="text/javascript">
    document.observe( "dom:loaded", function() {
        observeDisableFieldsOnRadioValue(
            'candidatureform',
            'data[ActioncandidatPersonne][rendezvouspartenaire]',
            [
//                 'ActioncandidatPersonneDaterdvpartenaireDay',
                    'RendezvousDaterdvDay',
                    'RendezvousDaterdvMonth',
                    'RendezvousDaterdvYear',
                    'RendezvousHeurerdvHour',
                    'RendezvousHeurerdvMin',
                    'RendezvousStructurereferenteId',
                    'RendezvousReferentId'
//                 'ActioncandidatPersonneDaterdvpartenaireMonth',
//                 'ActioncandidatPersonneDaterdvpartenaireYear'
            ],
            '1',
            true
        );

        observeDisableFieldsOnRadioValue(
            'candidatureform',
            'data[ActioncandidatPersonne][mobile]',
            [
                'ActioncandidatPersonneTypemobile',
                'ActioncandidatPersonneNaturemobile'
            ],
            '1',
            true
        );


        <?php
            echo $ajax->remoteFunction(
                array(
                    'update' => 'ActioncandidatPartenairePartenaireId',
                    'url' => Router::url( array( 'action' => 'ajaxpart', Set::extract( $this->data, 'ActioncandidatPersonne.actioncandidat_id' ) ), true )
                )
            );
        ?>
    } );
</script>
<!--/************************************************************************/ -->
<?php echo $javascript->link( 'dependantselect.js' ); ?>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        dependantSelect( 'RendezvousReferentId', 'RendezvousStructurereferenteId' );
    });
</script>
<div class="with_treemenu">
    <?php
        echo $xhtml->tag(
            'h1',
            $this->pageTitle = __d( $domain, "ActionscandidatsPersonnes::{$this->action}", true )
        );
    ?>
    <?php
        echo $xform->create( 'ActioncandidatPersonne', array( 'id' => 'candidatureform' ) );
        if( Set::check( $this->data, 'ActioncandidatPersonne.id' ) ){
            echo $xform->input( 'ActioncandidatPersonne.id', array( 'type' => 'hidden' ) );
        }
    ?>
    <fieldset>
        <legend>Informations de candidature</legend>
        <?php

            echo $default->subform(
                array(
                    'ActioncandidatPersonne.personne_id' => array( 'value' => $personneId, 'type' => 'hidden' ),
                    'ActioncandidatPersonne.actioncandidat_id',
                    'ActioncandidatPersonne.referent_id' => array( 'value' => $referentId ),
//                     'ActioncandidatPersonne.ddaction' => array( 'dateFormat' => 'DMY', 'domain' => 'actioncandidat_personne' ),
//                     'ActioncandidatPersonne.dfaction' => array( 'dateFormat' => 'DMY', 'domain' => 'actioncandidat_personne' )
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );

            echo $ajax->observeField( 'ActioncandidatPersonneActioncandidatId', array( 'update' => 'ActioncandidatPartenairePartenaireId', 'url' => Router::url( array( 'action' => 'ajaxpart' ), true ) ) );

            echo $xhtml->tag(
                'div',
                '<b>Partenaire</b>',
                array(
                    'id' => 'ActioncandidatPartenairePartenaireId'
                )
            );

        ?>
    </fieldset>
    <fieldset>
        <legend>Informations du candidat</legend>
        <?php
            echo $default->view(
                $personne,
                array(
                    'Personne.qual',
                    'Personne.nom',
                    'Personne.prenom'
                ),
                array(
                    'widget' => 'dl',
                    'class' => 'allocataire infos',
                    'options' => $options
                )
            );

            echo $default->view(
                $personne,
                array(
                    'Personne.dtnai',
                    'Foyer.Modecontact.numtel' => array( 'label' => 'N° de téléphone' ),
                    'Foyer.Dossier.matricule' => array( 'label' => 'N° CAF' )
                ),
                array(
                    'widget' => 'dl',
                    'class' => 'allocataire infos'
                )
            );

            echo $xhtml->tag(
                'dl',
                $xhtml->tag( 'dt', 'Adresse' ).
                $xhtml->tag(
                    'dd',
                    $default->format( $personne, 'Adresse.numvoie' ).' '.$default->format( $personne, 'Adresse.typevoie', array( 'options' => $options ) ).' '.$default->format( $personne, 'Adresse.nomvoie' ).'<br />'.$default->format( $personne, 'Adresse.codepos' ).' '.$default->format( $personne, 'Adresse.locaadr' )
                ),
                array(
                    'class' => 'allocataire infos'
                )
            );

        ?>
    </fieldset>
    <fieldset>
        <legend>Motif de la demande</legend>
            <?php
                echo $default->subform(
                    array(
                        'ActioncandidatPersonne.motifdemande' => array( 'label' => false )
                    ),
                    array(
                        'domain' => $domain
                    )
                );
            ?>
    </fieldset>
    <fieldset>
        <legend>Mobilité</legend>
        <?php
            echo $default->subform(
                array(
                    'ActioncandidatPersonne.mobile' => array( 'type' => 'radio' , 'legend' => 'Etes-vous mobile ?', 'div' => false, 'options' => array( '0' => 'Non', '1' => 'Oui' ) ),
                    'ActioncandidatPersonne.naturemobile' => array( 'label' => 'Nature de la mobilité', 'empty' => true ),
                    'ActioncandidatPersonne.typemobile'=> array( 'label' => 'Type de mobilité ' ),
                    'ActioncandidatPersonne.rendezvouspartenaire' => array( 'type' => 'radio' , 'legend' => 'Rendez-vous', 'div' => false, 'options' => array( '0' => 'Non', '1' => 'Oui' ) ),
                    
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );

            echo $default->subform(
                array(
                    'ActioncandidatPersonne.rendezvous_id' => array( 'type' => 'hidden' ),
                    'Rendezvous.id' => array( 'type' => 'hidden' ),
                    'Rendezvous.personne_id' => array( 'value' => $personneId, 'type' => 'hidden' ),
                    'Rendezvous.daterdv' => array( 'label' =>  'Rendez-vous fixé le ', 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2, 'empty' => true ),
                    'Rendezvous.heurerdv' => array( 'label' => 'A ', 'type' => 'time', 'timeFormat' => '24', 'minuteInterval' => 5,  'empty' => true, 'hourRange' => array( 8, 19 ) )
                ),
                array(
                    'options' => $options,
                    'domain' => $domain
                )
            );


            echo $xform->input( 'Rendezvous.structurereferente_id', array( 'label' =>  required( __( 'Nom de l\'organisme', true ) ), 'type' => 'select', 'options' => $structs, 'empty' => true ) );

            echo $xform->input( 'Rendezvous.referent_id', array( 'label' =>  ( 'Nom de l\'agent / du référent' ), 'type' => 'select', 'options' => $referents, 'empty' => true ) );


            ///Ajax pour les données du référent et de l'organisme auquel il est lié
            echo $ajax->observeField( 'RendezvousReferentId', array( 'update' => 'StructureData', 'url' => Router::url( array( 'action' => 'ajaxreffonct' ), true ) ) );

            echo $xhtml->tag(
                'div',
                '<b></b>',
                array(
                    'id' => 'StructureData'
                )
            );

            echo $default->subform(
                array(
                    'ActioncandidatPersonne.enattente' => array( 'type' => 'radio', 'div' => false, 'legend' => 'Candidature en attente', 'options' => array( 'N' => 'Non', 'O' => 'Oui' ) )
                ),
                array(
                    'options' => $options,
                    'domain' => $domain
                )
            );

        ?>
    </fieldset>
    <fieldset class="loici">
        <p>
            <strong>Engagement:</strong><br />
            <em>Je m’engage à me rendre disponible afin d’être présent à la prestation ou au rendez vous qui me sera fixé. En cas de force majeure, je m’engage à prévenir le conseiller d’insertion ou l’assistante sociale chargé de mon suivi.<br />
            Nous vous rappelons que dans le cas où vous ne donneriez pas suite à ce rendez vous sans motif valable, vous seriez convoqué(e) par l'Equipe Pluridisciplinaire Locale (Commission Audition), pour non respect de vos obligations dans le cadre de votre contrat.<br />
            </em>
        </p>
        <?php
            echo $default->subform(
                array(
                    'ActioncandidatPersonne.datesignature' => array( 'dateFormat' => 'DMY', 'empty' => false )
                ),
                array(
                    'domain' => $domain
                )
            );
        ?>
    </fieldset>

    <?php if( $this->action == 'edit' ):?>

        <p class="center"><em><strong>A remplir par le partenaire :</strong></em></p>
        <fieldset class="partenaire bilan">
            <?php
                echo $xhtml->tag(
                    'dl',
                    'Bilan d\'accueil : '
                );

                echo $default->subform(
                    array(
                        'ActioncandidatPersonne.bilanvenu' => array( 'type' => 'radio', 'separator' => '<br />',  'legend' => false ),
                        'ActioncandidatPersonne.bilanretenu' => array( 'type' => 'radio', 'separator' => '<br />', 'legend' => false ),
                    ),
                    array(
                        'domain' => $domain,
                        'options' => $options
                    )
                );

                echo $default->subform(
                    array(
                        'ActioncandidatPersonne.infocomplementaire',
                        'ActioncandidatPersonne.datebilan' => array( 'dateFormat' => 'DMY', 'empty' => false )
                    ),
                    array(
                        'domain' => $domain,
                        'options' => $options
                    )
                );

    //             debug($options);
            ?>
        </fieldset>
    <?php endif;?>
    <div class="submit">
        <?php
            echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
            echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
        ?>
    </div>
    <?php echo $xform->end();?>
</div>
<div class="clearer"><hr /></div>