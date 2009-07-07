<?php
    function thead( $pct = 10 ) {
        return '<thead>
                <tr>
                    <th>&nbsp;</th>
                    <th style="width: '.$pct.'%;">Demandeur</th>
                    <th style="width: '.$pct.'%;">Conjoint</th>
                </tr>
            </thead>';
    }

    function value( $dsp, $personne, $table, $field ) {
        return ( ( isset( $dsp[$personne][$table] ) && isset( $dsp[$personne][$table][$field] ) ) ? ( $dsp[$personne][$table][$field] ) : null );
    }

    function linkedValue( $links, $dossier, $personne, $table, $field ) {
        $value = ( ( isset( $dossier[$personne][$table] ) && isset( $dossier[$personne][$table][$field] ) ) ? ( $dossier[$personne][$table][$field] ) : null );
        return ( isset( $links[$value] ) ? $links[$value] : null );
    }

    /////  Récupération données du Contratinsertion pour le DEM et le CJT
    $DT = Set::extract( 'DEM.Contratinsertion.0.typocontrat_id', $dsp);
    $CT = Set::extract( 'CJT.Contratinsertion.0.typocontrat_id', $dsp);

    $deciD = Set::extract( 'DEM.Contratinsertion.0.decision_ci', $dsp);
    $deciC = Set::extract( 'CJT.Contratinsertion.0.decision_ci', $dsp);

//     debug($role);
//      debug( ( !empty( $role ) ) ? $role : null );
// debug( Set::extract( 'DEM.Orientstruct', $dsp));
?>
<?php $this->pageTitle = 'Dossier RSA '.$dossier['Dossier']['numdemrsa'];?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $dossier['Dossier']['id'] ) );?>

<div class="with_treemenu">
    <h1>Dossier RSA <?php echo h( $dossier['Dossier']['numdemrsa'] );?></h1>

<div id="resumeDossier">
    <table>
        <tbody>
            <tr class="odd">
                <th>Numéro de dossier</th>
                <td><?php echo h( $dossier['Dossier']['numdemrsa'] );?></td>
            </tr>
            <tr class="even">
                <th>Date de demande</th>
                <td><?php echo h( date_short( $dossier['Dossier']['dtdemrsa'] ) );?></td>
            </tr>
            <tr class="odd">
                <th>État du dossier</th>
                <td><?php echo h( isset( $etatdosrsa[$dossier['Situationdossierrsa']['etatdosrsa']] ) ? $etatdosrsa[$dossier['Situationdossierrsa']['etatdosrsa']] : null );?></td>
            </tr>
            <tr class="even">
                <th>Service instructeur</th>
                <td><?php echo h( isset( $typeserins[$dossier['Suiviinstruction']['typeserins']] ) ? $typeserins[$dossier['Suiviinstruction']['typeserins']] : null );?></td>
            </tr>
        </tbody>
    </table>

    <table id="ficheDossier">
        <tbody>
            <tr>
                <td>
                    <h2>Personnes</h2>
                    <table>
                        <?php echo thead( 10 );?>
                        <tbody>
                            <tr class="odd">
                                <th><?php __( 'nom' );?></th>
                                <td><?php echo value( $dsp, 'DEM', 'Personne', 'nom' ) ;?></td>
                                <td><?php echo value( $dsp, 'CJT', 'Personne', 'nom' ) ;?></td>
                            </tr>
                            <tr class="even">
                                <th><?php __( 'prenom' );?></th>
                                <td><?php echo value( $dsp, 'DEM', 'Personne', 'prenom' ) ;?></td>
                                <td><?php echo value( $dsp, 'CJT', 'Personne', 'prenom' ) ;?></td>
                            </tr>
                            <tr class="odd">
                                <th><?php __( 'sitfam' );?></th>
                                <td colspan="2"><?php echo ( isset( $sitfam[$dossier['Foyer']['sitfam']] ) ?  $sitfam[$dossier['Foyer']['sitfam']] : null );?></td>
                            </tr>
                            <tr class="even">
                                <th><?php __( 'adresse' );?></th>
                                <td colspan="2">
                                    <?php echo $dossier['Adresse']['numvoie'].' '.( isset( $typevoie[$dossier['Adresse']['typevoie']] ) ? $typevoie[$dossier['Adresse']['typevoie']] : null ).' '. $dossier['Adresse']['nomvoie'];?>
                                </td>
                            </tr>
                            <!-- <tr class="even">
                                <th><?php __( 'numtel' );?></th>
                                <td><?php echo isset( $dossier['ModeContact']['numtel'] ) ? $dossier['ModeContact']['numtel'] : null;?></td>
                                <td><?php echo isset( $dossier['ModeContact']['numtel'] ) ? $dossier['ModeContact']['numtel'] : null;?></td>
                            </tr>-->
                            <tr class="odd">
                                <th><?php __( 'locaadr' );?></th>
                                <td colspan="2"><?php echo ( isset( $dossier['Adresse']['locaadr'] ) ? $dossier['Adresse']['locaadr'] : null );?></td>
                            </tr>
                            <tr class="even">
                                <th>Soumis à droits et devoirs</th>
                                <td><?php echo linkedValue( $toppersdrodevorsa, $dsp, 'DEM', 'Prestation', 'toppersdrodevorsa' );?></td>
                                <td><?php echo linkedValue( $toppersdrodevorsa, $dsp, 'CJT', 'Prestation', 'toppersdrodevorsa' );?></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td>
                    <h2>Orientation</h2>
                    <table>
                    <?php echo thead( 10 );?>
                        <tbody>
                            <tr class="odd">
                                <th>Type d'orientation</th>
                                <td><?php echo Set::extract( 'DEM.Orientstruct.Typeorient.lib_type_orient', $dsp );?></td>
                                <td><?php echo Set::extract( 'CJT.Orientstruct.Typeorient.lib_type_orient', $dsp );?></td>
                            </tr>
                            <tr class="even">
                                <th>Structure référente<!--Type de structure--></th>
                                <td><?php echo Set::extract( 'DEM.Orientstruct.Structurereferente.lib_struc', $dsp );?></td>
                                <td><?php echo Set::extract( 'CJT.Orientstruct.Structurereferente.lib_struc', $dsp );?></td>
                            </tr>
                            <tr class="odd">
                                <th>Date de l'orientation</th>
                                <td><?php echo date_short( value( $dsp, 'DEM', 'Orientstruct', 'date_valid' ) );?></td>
                                <td><?php echo date_short( value( $dsp, 'CJT', 'Orientstruct', 'date_valid' ) );?></td>
                            </tr>
                            <tr class="even">
                                <th>Statut de l'orientation</th>
                                <td><?php if( Set::extract( 'DEM.Orientstruct', $dsp) != null ):?>
                                    <?php echo value( $dsp, 'DEM', 'Orientstruct', 'statut_orient' );?>
                                <?php endif;?></td>
                                <td><?php if( Set::extract( 'CJT.Orientstruct', $dsp) != null ):?>
                                    <?php echo value( $dsp, 'CJT', 'Orientstruct', 'statut_orient' );?>
                                <?php endif;?></td>
                            </tr>
                            <tr class="odd">
                                <th>Référent en cours</th>
                                <td><?php echo Set::extract( 'DEM.Orientstruct.Structurereferente.lib_struc', $dsp );?></td>
                                <td><?php echo Set::extract( 'CJT.Orientstruct.Structurereferente.lib_struc', $dsp );?></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <h2>Informations CAF</h2>
                    <table >
                        <tbody>
                            <tr class="even">
                                <th>Numéro CAF</th>
                                <td><?php echo h( $dossier['Dossier']['matricule'] );?></td>
                            </tr>
                            <tr class="odd">
                                <th>Date d'ouverture des droits</th>
                                <td><?php echo h( date_short( $dossier['Dossier']['dtdemrsa'] ) );?></td>
                            </tr>
                            <tr class="even">
                                <th>Date de fin de droits</th>
                                <td><?php echo h( date_short( $dossier['Situationdossierrsa']['dtclorsa'] ) );?></td>
                            </tr>
                            <tr class="odd">
                                <th>Motif de fin de droits</th>
                                <td><?php echo h( $dossier['Situationdossierrsa']['moticlorsa'] );?></td>
                            </tr>
                            <tr class="even">
                                <th>Numéro de demande RSA</th>
                                <td><?php echo h( isset( $dossier['Dossier']['numdemrsa'] ) ? $dossier['Dossier']['numdemrsa'] : null );?></td>
                            </tr>
                            <tr class="odd">
                                <th>DSP</th>
                                <td><?php echo h( isset( $dossier['Dspp']['id'] ) ? 'Oui' : 'Non');?></td>
                            </tr>
                            <tr class="even">
                                <th>Montant RSA</th>
                                <td><?php echo h( isset( $dossier['Detaildroitrsa']['Detailcalculdroitrsa']['mtrsavers'] ) ? $dossier['Detaildroitrsa']['Detailcalculdroitrsa']['mtrsavers'] : null );?></td>
                            </tr>
                            <tr class="odd">
                                <th>Date dernier montant</th>
                                <td><?php echo h( date_short( isset( $dossier['Detaildroitrsa']['Detailcalculdroitrsa']['dtderrsavers'] ) ) ? date_short( $dossier['Detaildroitrsa']['Detailcalculdroitrsa']['dtderrsavers'] ) : null);?></td>
                            </tr>
                            <tr class="even">
                                <th>Motif</th>
                                <td><?php echo h( isset( $natpf[$dossier['Detaildroitrsa']['Detailcalculdroitrsa']['natpf']] ) ? $natpf[$dossier['Detaildroitrsa']['Detailcalculdroitrsa']['natpf']] : null );?></td>
                            </tr>
                            <tr class="odd">
                                <th>Montant INDUS</th>
                                <td><?php echo h( isset( $dossier['Infofinanciere']['mtmoucompta'] ) ? $dossier['Infofinanciere']['mtmoucompta'] : null  );?></td>
                            </tr>
                            <tr class="even">
                                <th>Motif</th>
                                <td><?php echo h( isset( $dossier['Creance']['motiindu'] ) ? $dossier['Creance']['motiindu'] : null );?></td>
                            </tr>
                            <tr class="odd">
                                <th>Début du traitement CAF</th>
                                <td><?php echo h(  date_short( isset( $dossier['Dossiercaf']['ddratdos'] ) ) ? date_short( $dossier['Dossiercaf']['ddratdos'] ) : null  );?></td>
                            </tr>
                            <tr class="even">
                                <th>Fin du traitement CAF</th>
                                <td><?php echo h( isset( $dossier['Creance']['motiindu'] ) ? $dossier['Creance']['motiindu'] : null );?></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td>
                    <h2>Contrat d'insertion</h2>
                    <table>
                    <?php echo thead( 10 );?>
                        <tbody>
                            <tr class="even">
                                <th>Type de contrat</th>
                                <td><?php echo h( ( !empty( $DT ) && isset( $tc[$DT] ) ) ? $tc[$DT] : null );?></td>
                                <td><?php echo h( ( !empty( $CT ) && isset( $tc[$CT] ) ) ? $tc[$CT] : null );?></td>
                            </tr>
                            <tr class="odd">
                                <th>Date de début</th>
                                <td><?php echo date_short( Set::extract( 'DEM.Contratinsertion.0.dd_ci', $dsp) );?></td>
                                <td><?php echo date_short( Set::extract( 'CJT.Contratinsertion.0.dd_ci', $dsp) );?></td>
                            </tr>
                            <tr class="even">
                                <th>Date de fin</th>
                                <td><?php echo date_short( Set::extract( 'DEM.Contratinsertion.0.df_ci', $dsp) );?></td>
                                <td><?php echo date_short( Set::extract( 'CJT.Contratinsertion.0.df_ci', $dsp) );?></td>
                            </tr>
                            <tr class="odd">
                                <th>Décision</th>
                                <td>
                                    <?php if(  Set::extract( 'DEM.Contratinsertion', $dsp) != null ):?>
                                        <?php echo ( !empty( $deciD )  ) ? $decision_ci[$deciD] : $decision_ci[''] ;?>
                                    <?php endif;?>
                                </td>
                                <td>
                                    <?php if( Set::extract( 'CJT.Contratinsertion', $dsp) != null ):?>
                                        <?php echo ( !empty( $deciC )  ) ? $decision_ci[$deciC] : $decision_ci[''] ;?>
                                    <?php endif;?>
                                </td>
                            </tr>
                            <tr class="even">
                                <th>Date de décision</th>
                                <td><?php echo date_short( Set::extract( 'DEM.Contratinsertion.0.datevalidation_ci', $dsp) );?></td>
                                <td><?php echo date_short( Set::extract( 'CJT.Contratinsertion.0.datevalidation_ci', $dsp) );?></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</div>
<div class="clearer"><hr /></div>
