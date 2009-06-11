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
?>
<?php $this->pageTitle = 'Dossier RSA '.$dossier['Dossier']['numdemrsa'];?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $dossier['Dossier']['id'] ) );?>

<div class="with_treemenu">
    <h1>Dossier RSA <?php echo h( $dossier['Dossier']['numdemrsa'] );?></h1>
<!-- <?php debug( $dossier );?> -->
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
                    <h2>Personne</h2>
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
                                <th><?php __( 'adresse' );?></th>
                                <td colspan="2"><?php echo $dossier['Adresse']['numvoie'].' '.$dossier['Adresse']['typevoie'].' '.$dossier['Adresse']['nomvoie'];?></td>
                            </tr>
                            <tr class="even">
                                <th><?php __( 'numtel' );?></th>
                                <td><?php echo isset( $dossier['ModeContact']['numtel'] ) ? $dossier['ModeContact']['numtel'] : null;?></td>
                                <td><?php echo isset( $dossier['ModeContact']['numtel'] ) ? $dossier['ModeContact']['numtel'] : null;?></td>
                            </tr>
                            <tr class="odd">
                                <th><?php __( 'locaadr' );?></th>
                                <td colspan="2"><?php echo ( isset( $dossier['Adresse']['locaadr'] ) ? $dossier['Adresse']['locaadr'] : null );?></td>
                            </tr>
                            <tr class="even">
                                <th>Soumis à droits et devoirs</th>
                                <td><?php echo linkedValue( $toppersdrodevorsa, $dsp, 'DEM', 'Personne', 'toppersdrodevorsa' );?></td>
                                <td><?php echo linkedValue( $toppersdrodevorsa, $dsp, 'CJT', 'Personne', 'toppersdrodevorsa' );?></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td>
                    <h2>Orientation</h2>
                    <table>
                        <tbody>
                            <tr class="odd">
                                <th>Type d'orientation</th>
                                <td><?php echo h( isset( $dossier['Personne']['Structurereferente']['Typeorient']['lib_type_orient'] ) ? $dossier['Personne']['Structurereferente']['Typeorient']['lib_type_orient'] : null );?></td>
                            </tr>
                            <tr class="even">
                                <th>Structure référente<!--Type de structure--></th>
                                <td><?php echo h( isset( $dossier['Personne']['Structurereferente']['lib_struc'] ) ? $dossier['Personne']['Structurereferente']['lib_struc'] : null);?></td>
                            </tr>
                            <tr class="odd">
                                <th>Date de l'orientation</th>
                                <td><?php echo h(  date_short( isset( $dossier['Personne']['Orientstruct']['date_valid'] ) ) ? date_short( $dossier['Personne']['Orientstruct']['date_valid'] ) : null );?></td>
                            </tr>
                            <tr class="even">
                                <th>Statut de l'orientation</th>
                                <td><?php echo h( isset( $dossier['Personne']['Orientstruct']['statut_orient'] ) ? $dossier['Personne']['Orientstruct']['statut_orient'] : null);?></td>
                            </tr>
                            <tr class="odd">
                                <th>Référent en cours</th>
                                <td><?php echo h( isset( $dossier['Personne']['Structurereferente']['lib_struc'] ) ? $dossier['Personne']['Structurereferente']['lib_struc'] : null);?></td>
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
                        <tbody>
                            <tr class="even">
                                <th>Type de contrat</th>
                                <td><?php echo h( isset( $tc[$dossier['Personne']['Contratinsertion']['typocontrat_id']] ) ? $tc[$dossier['Personne']['Contratinsertion']['typocontrat_id']] : null );?></td>
                            </tr>
                            <tr class="odd">
                                <th>Date de début</th>
                                <td><?php echo h( date_short( isset( $dossier['Personne']['Contratinsertion']['dd_ci'] ) ) ? date_short( $dossier['Personne']['Contratinsertion']['dd_ci'] ) : null );?></td>
                            </tr>
                            <tr class="even">
                                <th>Date de fin</th>
                                <td><?php echo h( date_short( isset( $dossier['Personne']['Contratinsertion']['df_ci'] ) ) ? date_short( $dossier['Personne']['Contratinsertion']['df_ci'] ) : null );?></td>
                            </tr>
                            <tr class="odd">
                                <th>Décision</th>
                                <td><?php echo h( isset( $decision_ci[$dossier['Personne']['Contratinsertion']['decision_ci']] ) ? $decision_ci[$dossier['Personne']['Contratinsertion']['decision_ci']]  : null ) ;?></td>
                            </tr>
                            <tr class="even">
                                <th>Date de décision</th>
                                <td><?php echo h( date_short( isset( $dossier['Personne']['Contratinsertion']['datevalidation_ci'] ) ) ? date_short( $dossier['Personne']['Contratinsertion']['datevalidation_ci'] ) : null );?></td>
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
