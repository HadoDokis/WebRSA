<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );?>

<div class="with_treemenu">

        <?php
            echo $xhtml->tag(
                'h1',
                $this->pageTitle = __d( 'entretien', "Entretiens::{$this->action}", true )
            );

        ?>
<br />
    <div id="tabbedWrapper" class="tabs">

        <div id="entretiens">
            <h2 class="title">Entretiens</h2>
                <?php if( $permissions->check( 'entretiens', 'add' ) ):?>
                    <ul class="actionMenu">
                        <?php
                            echo '<li>'.
                                $xhtml->addLink(
                                    'Ajouter un entretien',
                                    array( 'controller' => 'entretiens', 'action' => 'add', $personne_id )
                                ).
                            ' </li>';
                        ?>
                    </ul>
                <?php endif;?>
                <?php if( isset( $entretiens ) ):?>
                    <?php if( empty( $entretiens ) ):?>
                        <?php $message = 'Aucun entretien n\'a été trouvé.';?>
                        <p class="notice"><?php echo $message;?></p>
                    <?php else:?>

                    <?php $pagination = $xpaginator->paginationBlock( 'Entretien', $this->passedArgs ); ?>
                    <?php echo $pagination;?>
                    <table id="searchResults" class="tooltips">
                        <thead>
                            <tr>
                                <th>Date de l'entretien</th>
                                <th>Structure référente</th>
                                <th>Nom du prescripteur</th>
                                <th>Type d'entretien</th>
                                <th>Objet de l'entretien</th>
                                <th>A revoir le</th>
                                <th class="action" colspan="4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach( $entretiens as $index => $entretien ):?>
                            <?php

                                echo $xhtml->tableCells(
                                        array(
                                            h( date_short(  $entretien['Entretien']['dateentretien'] ) ),
                                            h( $entretien['Structurereferente']['lib_struc'] ),
                                            h( $entretien['Referent']['nom_complet'] ),
                                            h( Set::enum( $entretien['Entretien']['typeentretien'], $options['Entretien']['typeentretien'] ) ),
                                            h( $entretien['Objetentretien']['name'] ),
                                            h( $locale->date( 'Date::miniLettre', $entretien['Entretien']['arevoirle'] ) ),
                                            $xhtml->viewLink(
                                                'Voir le contrat',
                                                array( 'controller' => 'entretiens', 'action' => 'view', $entretien['Entretien']['id'] ),
                                                $permissions->check( 'entretiens', 'index' )
                                            ),
                                            $xhtml->editLink(
                                                'Editer l\'orientation',
                                                array( 'controller' => 'entretiens', 'action' => 'edit', $entretien['Entretien']['id'] ),
                                                $permissions->check( 'entretiens', 'edit' )
                                            ),
                                            $xhtml->deleteLink(
                                                'Supprimer l\'entretien',
                                                array( 'controller' => 'entretiens', 'action' => 'delete', $entretien['Entretien']['id'] ),
                                                $permissions->check( 'entretiens', 'delete' )
                                            ),
                                            $xhtml->fileLink(
                                                'Fichiers liés',
                                                array( 'controller' => 'entretiens', 'action' => 'filelink', $entretien['Entretien']['id'] ),
                                                $permissions->check( 'entretiens', 'filelink' )
                                            )
                                        ),
                                        array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
                                        array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
                                    );
                                ?>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                    <?php endif?>
                <?php endif?>




        </div><!-- Fin de div entretiens -->

<!-- INFO : Fin de l'affichage des Entretiens -->
<? if( false ):?>
        <div id="dsporigine">
            <h2 class="title">DSP d'origine</h2>
            <?php if( !empty( $dsps ) ):?>
                <?php

                    echo $form->input(
                        'Dsp.hideempty',
                        array(
                            'type' => 'checkbox',
                            'label' => 'Cacher les questions sans réponse',
                            'onclick' => 'if( $( \'DspHideempty\' ).checked ) {
                                $$( \'.empty\' ).each( function( elmt ) { elmt.hide() } );
                            } else { $$( \'.empty\' ).each( function( elmt ) { elmt.show() } ); }'
                        )
                    );

                    echo $default->view(
                        $dsps,
                        array(
                            'Dsp.sitpersdemrsa',
                            'Dsp.topisogroouenf',
                            'Dsp.topdrorsarmiant',
                            'Dsp.drorsarmianta2',
                            'Dsp.topcouvsoc',
                            'Dsp.accosocfam',
                            'Dsp.libcooraccosocfam',
                            'Dsp.accosocindi',
                            'Dsp.libcooraccosocindi',
                            'Dsp.soutdemarsoc',
                            'Dsp.nivetu',
                            'Dsp.nivdipmaxobt',
                            'Dsp.annobtnivdipmax',
                            'Dsp.topqualipro',
                            'Dsp.libautrqualipro',
                            'Dsp.topcompeextrapro',
                            'Dsp.libcompeextrapro',
                            'Dsp.topengdemarechemploi',
                            'Dsp.hispro',
                            'Dsp.libderact',
                            'Dsp.libsecactderact',
                            'Dsp.cessderact',
                            'Dsp.topdomideract',
                            'Dsp.libactdomi',
                            'Dsp.libsecactdomi',
                            'Dsp.duractdomi',
                            'Dsp.inscdememploi',
                            'Dsp.topisogrorechemploi',
                            'Dsp.accoemploi',
                            'Dsp.libcooraccoemploi',
                            'Dsp.topprojpro',
                            'Dsp.libemploirech',
                            'Dsp.libsecactrech',
                            'Dsp.topcreareprientre',
                            'Dsp.concoformqualiemploi',
                            'Dsp.topmoyloco',
                            'Dsp.toppermicondub',
                            'Dsp.topautrpermicondu',
                            'Dsp.libautrpermicondu',
                            'Dsp.natlog',
                            'Dsp.demarlog'
                        ),
                        array(
                            'options' => $options
                        )
                    );
                ?>
                <?php else:?>
                    <ul class="actionMenu">
                        <?php
                            echo '<li>'.$xhtml->addLink(
                                'Ajouter des Dsps',
                                array( 'controller' => 'dsps', 'action' => 'add', $personne_id )
                            ).' </li>';

                        ?>
                    </ul>
                    <p class="notice">Cette personne ne possède pas encore de données socio-professionnelles.</p>
            <?php endif;?>
        </div>

<!-- INFO : Fin de l'affichage des DSP d'Origine -->

        <div id="dspcg">
            <h2 class="title">DSP CG</h2>
                <ul class="actionMenu">
                    <?php
                        echo '<li>'.$xhtml->addLink(
                            'Ajouter des Dsps',
                            array( 'controller' => 'dsps', 'action' => 'add', $personne_id )
                        ).' </li>';

                    ?>
                </ul>
                <?php if( !empty( $dsps ) ):?>
                <?php

                    echo $default->view(
                        $dsps,
                        array(
                            'Dsp.nivetu',
                            'Dsp.duractdomi'
                        ),
                        array(
                            'options' => $options
                        )
                    );

                ?>
                <?php else:?>
                    <p class="notice">Cette personne ne possède pas encore de données CG.</p>
            <?php endif;?>
        </div>
        <? endif;?>
    </div> <!-- Fin de div tabbedWrapper -->

</div>
<div class="clearer"><hr /></div>

<?php
    echo $javascript->link( 'prototype.livepipe.js' );
    echo $javascript->link( 'prototype.tabs.js' );
?>

<script type="text/javascript">
    makeTabbed( 'tabbedWrapper', 2 );
</script>