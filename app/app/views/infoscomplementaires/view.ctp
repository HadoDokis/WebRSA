<?php $this->pageTitle = 'Dossier RSA '.$details['Dossier']['numdemrsa'];?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $details['Dossier']['id'] ) );?>

<div class="with_treemenu">
    <h1>Informations complémentaires</h1> <!--FIXME: grugeage -->
<br />
<div id="tabbedWrapper" class="tabs">
    <div id="allocataires">
        <h2 class="title">Personnes</h2>
        <table class="noborder">
            <tbody>
                <tr>
                    <td class="noborder">
                        <?php
                            echo $theme->tableDemandeurConjoint(
                                $details,
                                array(
                                    'Personne.qual' => array( 'options' => $qual ),
                                    'Personne.nom',
                                    'Personne.prenom'
                                ),
                                array(
                                    'id' => 'personnes'
                                )
                            );
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="activites">
    <h2 class="title">Activité</h2>
    <table class="noborder">
        <tbody>
            <tr>
                <td class="noborder">

                    <?php
                        echo $theme->tableDemandeurConjoint(
                            $details,
                            array(
                                'Activite.reg' => array( 'options' => $reg ),
                                'Activite.act' => array( 'options' => $act ),
                                'Activite.paysact' => array( 'options' => $paysact ),
                                'Activite.ddact',
                                'Activite.dfact',
                            )
                        );
                    ?>
                </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="titres">
    <h2 class="title">Titre de séjour</h2>
    <table class="noborder">
        <tbody>
            <tr>
                <td class="noborder">
                    <?php
                        echo $theme->tableDemandeurConjoint(
                            $details,
                            array(
                                'TitreSejour.dtentfra',
                                'TitreSejour.nattitsej',
                                'TitreSejour.menttitsej',
                                'TitreSejour.ddtitsej',
                                'TitreSejour.dftitsej',
                                'TitreSejour.numtitsej',
                                'TitreSejour.numduptitsej'
                            )
                        );
                    ?>
                </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="dossiercaf">
    <h2 class="title">Dossier CAF</h2>
    <table class="noborder">
        <tbody>
            <tr>
                <td class="noborder">
                    <?php
                        echo $theme->tableDemandeurConjoint(
                            $details,
                            array(
                                'Dossiercaf.ddratdos',
                                'Dossiercaf.dfratdos',
                                'Dossiercaf.toprespdos',
                                'Dossiercaf.numdemrsaprece',
                            )
                        );
                    ?>
                </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="allocation">
    <h2 class="title">Allocation soutien familial</h2>
    <table class="noborder">
        <tbody>
            <tr>
                <td class="noborder">
                    <?php
                        echo $theme->tableDemandeurConjoint(
                            $details,
                            array(
                                'Allocationsoutienfamilial.sitasf' => array( 'options' => $sitasf ),
                                'Allocationsoutienfamilial.parassoasf' => array( 'options' => $parassoasf ),
                                'Allocationsoutienfamilial.ddasf',
                                'Allocationsoutienfamilial.dfasf'
                            )
                        );
                    ?>
                </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="creances">
    <h2 class="title">Créances alimentaires</h2>
    <table class="noborder">
        <tbody>
            <tr>
                <td class="noborder" colspan="2">
                    <?php
                        echo $theme->tableDemandeurConjoint(
                            $details,
                            array(
                                'Creancealimentaire.etatcrealim' => array( 'options' => $etatcrealim ),
                                'Creancealimentaire.ddcrealim',
                                'Creancealimentaire.dfcrealim',
                                'Creancealimentaire.orioblalim' => array( 'options' => $orioblalim ),
                                'Creancealimentaire.motidiscrealim' => array( 'options' => $motidiscrealim ),
                                'Creancealimentaire.commcrealim',
                                'Creancealimentaire.mtsancrealim',
                                'Creancealimentaire.topdemdisproccrealim' => array( 'options' => $topdemdisproccrealim ),
                                'Creancealimentaire.engproccrealim' => array( 'options' => $engproccrealim ),
                                'Creancealimentaire.verspa' => array( 'options' => $verspa ),
                                'Creancealimentaire.topjugpa'
                            ),
                            array(
                                'id' => 'creancesalimentaires'
                            )
                        );
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</div>

</div>

<div class="clearer"><hr /></div>

<!-- *********************************************************************** -->

<?php
    echo $javascript->link( 'prototype.livepipe.js' );
    echo $javascript->link( 'prototype.tabs.js' );
?>

<script type="text/javascript">
    makeTabbed( 'tabbedWrapper', 2 );
</script>
