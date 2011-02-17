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

    function linkedValue( $links, $details, $personne, $table, $field ) {
        $value = ( ( isset( $details[$personne][$table] ) && isset( $details[$personne][$table][$field] ) ) ? ( $details[$personne][$table][$field] ) : null );
        return ( isset( $links[$value] ) ? $links[$value] : null );
    }

    function value( $array, $index ) {
        $keys = array_keys( $array );
        $index = ( ( $index == null ) ? '' : $index );
        if( @in_array( $index, $keys ) && isset( $array[$index] ) ) {
            return $array[$index];
        }
        else {
            return null;
        }
    }

    /////  Récupération données du Contratinsertion pour le DEM et le CJT
    $DT = Set::extract( 'DEM.Contratinsertion.num_contrat', $details);
    $CT = Set::extract( 'CJT.Contratinsertion.num_contrat', $details);

    $deciD = Set::extract( 'DEM.Contratinsertion.decision_ci', $details);
    $deciC = Set::extract( 'CJT.Contratinsertion.decision_ci', $details);

?>
<?php $this->pageTitle = 'Dossier RSA '.$details['Dossier']['numdemrsa'];?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $details['Dossier']['id'] ) );?>

<div class="with_treemenu">
    <h1>&nbsp;</h1> <!--FIXME: grugeage -->
<div id="resumeDossier">
    <table  id="ficheDossier">
        <tbody>
            <tr>
                <td>
                    <h1>Dossier RSA <?php echo h( $details['Dossier']['numdemrsa'] );?></h1>
                    <table>
                        <tbody>
                            <tr class="odd">
                                <th>Numéro de dossier</th>
                                <td><?php echo h( $details['Dossier']['numdemrsa'] );?></td>
                            </tr>
                            <tr class="even">
                                <th>Date de demande</th>
                                <td><?php echo h( date_short( $details['Dossier']['dtdemrsa'] ) );?></td>
                            </tr>
                            <tr class="odd">
                                <th>État du dossier</th>
                                <td><?php echo h( value( $etatdosrsa, Set::extract( 'Situationdossierrsa.etatdosrsa', $details ) ) );?></td>
                            </tr>
                            <tr class="even">
                                <th>Service instructeur</th>
                                <td><?php echo h( value( $typeserins, Set::extract( 'Suiviinstruction.typeserins', $details ) ) );?></td>
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
                                <td><?php echo Set::classicExtract( $details, 'DEM.Orientstruct.derniere.Typeorient.lib_type_orient' );?></td>
                                <td><?php echo Set::classicExtract( $details, 'CJT.Orientstruct.derniere.Typeorient.lib_type_orient' );?></td>
							</tr>
                            <tr class="even">
                                <th>Structure référente<!--Type de structure--></th>
                                <td><?php echo Set::classicExtract( $details, 'DEM.Orientstruct.derniere.Structurereferente.lib_struc' );?></td>
                                <td><?php echo Set::classicExtract( $details, 'CJT.Orientstruct.derniere.Structurereferente.lib_struc' );?></td>
                            </tr>
                            <tr class="odd">
                                <th>Date de l'orientation</th>
                                <td><?php echo date_short( Set::classicExtract( $details, 'DEM.Orientstruct.derniere.Orientstruct.date_valid' ) );?></td>
                                <td><?php echo date_short( Set::classicExtract( $details, 'CJT.Orientstruct.derniere.Orientstruct.date_valid' ) );?></td>
                            </tr>
                            <tr class="even">
                                <th>Statut de l'orientation</th>
                                <td><?php echo Set::classicExtract( $details, 'DEM.Orientstruct.derniere.Orientstruct.statut_orient' );?></td>
                                <td><?php echo Set::classicExtract( $details, 'CJT.Orientstruct.derniere.Orientstruct.statut_orient' );?></td>
                            <tr class="odd">
                                <th>Référent en cours</th>
                                <?php foreach( array( 'DEM', 'CJT' ) as $rolepers ):?>
                                <td><?php
									$referent = Set::extract( "{$rolepers}.Referent", $details );
									echo implode( ' ', array( Set::classicExtract( $referent, 'qual' ), Set::classicExtract( $referent, 'nom' ), Set::classicExtract( $referent, 'prenom' ) ) );
								?></td>
                                <?php endforeach;?>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <h2>Personnes</h2>
                    <table>
                        <?php echo thead( 10 );?>
                        <tbody>
                            <tr class="odd">
                                <th><?php __d( 'personne', 'Personne.nom' );?></th>
                                <td><?php echo Set::extract( 'DEM.Personne.nom', $details );?></td>
                                <td><?php echo Set::extract( 'CJT.Personne.nom', $details );?></td>
                            </tr>
                            <tr class="even">
                                <th><?php __d( 'personne', 'Personne.prenom' );?></th>
                                <td><?php echo Set::extract( 'DEM.Personne.prenom', $details );?></td>
                                <td><?php echo Set::extract( 'CJT.Personne.prenom', $details );?></td>
                            </tr>
                            <tr class="odd">
                                <th><?php __d( 'foyer', 'Foyer.sitfam' );?></th>
                                <td colspan="2"><?php echo ( isset( $sitfam[$details['Foyer']['sitfam']] ) ?  $sitfam[$details['Foyer']['sitfam']] : null );?></td>
                            </tr>
                            <tr class="even">
                                <th><?php __( 'adresse' );?></th>
                                <td colspan="2">
                                    <?php echo $details['Adresse']['numvoie'].' '.( isset( $typevoie[$details['Adresse']['typevoie']] ) ? $typevoie[$details['Adresse']['typevoie']] : null ).' '. $details['Adresse']['nomvoie'];?>
                                </td>
                            </tr>
                            <tr class="odd">
                                <th><?php __d( 'adresse', 'Adresse.locaadr' );?></th>
                                <td colspan="2"><?php echo ( isset( $details['Adresse']['locaadr'] ) ? $details['Adresse']['locaadr'] : null );?></td>
                            </tr>
                            <tr class="even">
                                <th>Soumis à droits et devoirs</th>
                                <td><?php echo value( $toppersdrodevorsa, Set::extract( 'DEM.Calculdroitrsa.toppersdrodevorsa', $details ) );?></td>
                                <td><?php echo value( $toppersdrodevorsa, Set::extract( 'CJT.Calculdroitrsa.toppersdrodevorsa', $details ) );?></td>
                            </tr>
                            <tr class="odd">
                                <th>DSP</th>
                                <td><?php echo h( isset( $details['DEM']['Dsp']['id'] ) ? 'Oui' : 'Non');?></td>
                                <td><?php echo h( isset( $details['CJT']['Dsp']['id'] ) ? 'Oui' : 'Non');?></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td>
                    <h2>Contrat d'Engagement Réciproque</h2>
                    <table>
                    <?php echo thead( 10 );?>
                        <tbody>
                            <tr class="even">
                                <th>Type de contrat</th>
                                <td><?php echo Set::enum( Set::classicExtract( $details, 'DEM.Contratinsertion.num_contrat' ), $numcontrat['num_contrat'] );?></td>
                                <td><?php echo Set::enum( Set::classicExtract( $details, 'CJT.Contratinsertion.num_contrat' ), $numcontrat['num_contrat'] );?></td>
                            </tr>
                            <tr class="odd">
                                <th>Date de début</th>
                                <td><?php echo date_short( Set::extract( 'DEM.Contratinsertion.dd_ci', $details) );?></td>
                                <td><?php echo date_short( Set::extract( 'CJT.Contratinsertion.dd_ci', $details) );?></td>
                            </tr>
                            <tr class="even">
                                <th>Date de fin</th>
                                <td><?php echo date_short( Set::extract( 'DEM.Contratinsertion.df_ci', $details) );?></td>
                                <td><?php echo date_short( Set::extract( 'CJT.Contratinsertion.df_ci', $details) );?></td>
                            </tr>
                            <tr class="odd">
                                <th>Décision</th>
                                <td>
                                    <?php if(  Set::extract( 'DEM.Contratinsertion', $details) != null ):?>
                                        <?php echo ( !empty( $deciD )  ) ? $decision_ci[$deciD] : $decision_ci[''] ;?>
                                    <?php endif;?>
                                </td>
                                <td>
                                    <?php if( Set::extract( 'CJT.Contratinsertion', $details) != null ):?>
                                        <?php echo ( !empty( $deciC )  ) ? $decision_ci[$deciC] : $decision_ci[''] ;?>
                                    <?php endif;?>
                                </td>
                            </tr>
                            <tr class="even">
                                <th>Date de décision</th>
                                <td><?php echo date_short( Set::extract( 'DEM.Contratinsertion.datevalidation_ci', $details) );?></td>
                                <td><?php echo date_short( Set::extract( 'CJT.Contratinsertion.datevalidation_ci', $details) );?></td>
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
                                <td><?php echo Set::extract( 'Dossier.matricule', $details );;?></td>
                            </tr>
                            <tr class="even">
                                <th>Statut du demandeur</th>
                                <td><?php echo value( $statudemrsa, Set::extract( 'Dossier.statudemrsa', $details ) );?></td>
                            </tr>
                            <tr class="odd">
                                <th>Date d'ouverture des droits</th>
                                <td><?php echo h( date_short( Set::extract( 'Dossier.dtdemrsa', $details ) ) );?></td>
                            </tr>
                            <tr class="even">
                                <th>Date de fin de droits</th>
                                <td><?php echo h( date_short( Set::extract( 'Situationdossierrsa.dtclorsa', $details ) ) );?></td>
                            </tr>
                            <tr class="odd">
                                <th>Motif de fin de droits</th>
                                <td><?php echo h( value( $moticlorsa, Set::extract( 'Situationdossierrsa.moticlorsa', $details ) ) );?></td>
                            </tr>
                            <tr class="even">
                                <th>Numéro de demande RSA</th>
                                <td><?php echo Set::extract( 'Dossier.numdemrsa', $details );?></td>
                            </tr>
                            <tr class="even">
                                <th>Montant RSA</th>
                                <td><?php echo $locale->money( Set::extract( 'Detailcalculdroitrsa.0.mtrsavers', $details ) ); ?></td>
                            </tr>
                            <tr class="odd">
                                <th>Date dernier montant</th>
                                <td><?php echo date_short( Set::extract( 'Detailcalculdroitrsa.0.dtderrsavers', $details ) );?></td>
                            </tr>
                            <tr class="even">
                                <th>Motif</th>
                                <td><?php echo value( $natpf, Set::extract( 'Detailcalculdroitrsa.0.natpf', $details ) );?></td>
                            </tr>
                            <tr class="odd">
                                <th>Montant INDUS</th>
                                <td><?php echo $locale->money( Set::extract( 'Infofinanciere.mtmoucompta', $details ) );?></td>
                            </tr>
                            <tr class="even">
                                <th>Motif</th>
                                <td><?php echo h( Set::extract( 'Creance.motiindu', $details ) );/*FIXME: traduction, manque dans Option*/?></td>
                            </tr>
                            <tr class="odd">
                                <th>Début du traitement CAF</th>
                                <td><?php echo $locale->date( 'Date::short', Set::extract( 'DEM.Dossiercaf.ddratdos', $details ) );?></td>
                            </tr>
                            <tr class="even">
                                <th>Fin du traitement CAF</th>
                                <td><?php echo h(  date_short( Set::extract( 'DEM.Dossiercaf.dfratdos', $details ) ) );?></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td>
                    <h2>Contrat Unique d'Insertion</h2>
                    <table>
                    <?php echo thead( 10 );?>
                        <tbody>
                            <tr class="even">
                                <th>Convention</th>
                                <td><?php echo Set::enum( Set::classicExtract( $details, 'DEM.Cui.convention' ), $enumcui['convention'] );?></td>
                                <td><?php echo Set::enum( Set::classicExtract( $details, 'CJT.Cui.convention' ), $enumcui['convention'] );?></td>
                            </tr>
                            <tr class="odd">
                                <th>Secteur</th>
                                <td><?php echo Set::enum( Set::classicExtract( $details, 'DEM.Cui.secteur' ), $enumcui['secteur'] );?></td>
                                <td><?php echo Set::enum( Set::classicExtract( $details, 'CJT.Cui.secteur' ), $enumcui['secteur'] );?></td>
                            </tr>
                            <tr class="even">
                                <th>Date du contrat</th>
                                <td><?php echo date_short( Set::extract( 'DEM.Cui.datecontrat', $details) );?></td>
                                <td><?php echo date_short( Set::extract( 'CJT.Cui.datecontrat', $details) );?></td>
                            </tr>
                            <tr class="odd">
                                <th>Décision</th>
                                <td><?php echo Set::enum( Set::classicExtract( $details, 'DEM.Cui.decisioncui' ), $enumcui['decisioncui'] );?></td>
                                <td><?php echo Set::enum( Set::classicExtract( $details, 'CJT.Cui.decisioncui' ), $enumcui['decisioncui'] );?></td>
                            </tr>
                            <tr class="even">
                                <th>Date de décision</th>
                                <td><?php echo date_short( Set::extract( 'DEM.Cui.datevalidationcui', $details) );?></td>
                                <td><?php echo date_short( Set::extract( 'CJT.Cui.datevalidationcui', $details) );?></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <h2>Dernière Information Pôle Emploi</h2>
                    <table>
                    <?php echo thead( 10 );?>
                        <tbody>
                            <tr class="even">
                                <th>Identifiant pôle-emploi</th>
                                <td><?php echo Set::extract( 'DEM.Informationpe.0.identifiantpe', $details);?></td>
                                <td><?php echo Set::extract( 'CJT.Informationpe.0.identifiantpe', $details);?></td>
                            </tr>
                            <tr class="odd">
                                <th>Etat actuel Pôle Emploi</th>
                                <td><?php echo Set::enum( Set::extract( 'DEM.Informationpe.0.etat', $details ), $etatpe['etat'] );?></td>
                                <td><?php echo Set::enum( Set::extract( 'CJT.Informationpe.0.etat', $details ), $etatpe['etat'] );?></td>
                            </tr>
                            <tr class="even">
                                <th>Dernière date</th>
                                <td><?php echo date_short( Set::extract( 'DEM.Informationpe.0.date', $details) );?></td>
                                <td><?php echo date_short( Set::extract( 'CJT.Informationpe.0.date', $details) );?></td>
                            </tr>
                            <tr class="odd">
                                <th>Code état</th>
                                <td><?php echo Set::extract( 'DEM.Informationpe.0.code', $details);?></td>
                                <td><?php echo Set::extract( 'CJT.Informationpe.0.code', $details);?></td>
                            </tr>
                            <tr class="even">
                                <th>Motif</th>
                                <td><?php echo Set::extract( 'DEM.Informationpe.0.motif', $details);?></td>
                                <td><?php echo Set::extract( 'CJT.Informationpe.0.motif', $details);?></td>
                            </tr>
                            <!--<tr class="even">
                                <th>Catégorie</th>
                                <td><?php /*echo isset( $categorie[Set::classicExtract( $details,  'DEM.Infopoleemploi.categoriepe' )] ) ? $categorie[Set::classicExtract( $details,  'DEM.Infopoleemploi.categoriepe' )] : '';?></td>
                                <td><?php echo isset( $categorie[Set::classicExtract( $details,  'CJT.Infopoleemploi.categoriepe' )] ) ? $categorie[Set::classicExtract( $details,  'CJT.Infopoleemploi.categoriepe' )] : '';?></td>
                            </tr>
                            <tr class="odd">
                                <th>Date de cessation</th>
                                <td><?php echo date_short( Set::extract( 'DEM.Infopoleemploi.datecessation', $details) );?></td>
                                <td><?php echo date_short( Set::extract( 'CJT.Infopoleemploi.datecessation', $details) );?></td>
                            </tr>
                            <tr class="even">
                                <th>Motif de cessation</th>
                                <td><?php echo Set::extract( 'DEM.Infopoleemploi.motifcessation', $details);?></td>
                                <td><?php echo Set::extract( 'CJT.Infopoleemploi.motifcessation', $details);?></td>
                            </tr>
                            <tr class="odd">
                                <th>Date de radiation</th>
                                <td><?php echo date_short( Set::extract( 'DEM.Infopoleemploi.dateradiation', $details) );?></td>
                                <td><?php echo date_short( Set::extract( 'CJT.Infopoleemploi.dateradiation', $details) );?></td>
                            </tr>
                            <tr class="even">
                                <th>Motif de radiation</th>
                                <td><?php echo Set::extract( 'DEM.Infopoleemploi.motifradiation', $details);?></td>
                                <td><?php echo Set::extract( 'CJT.Infopoleemploi.motifradiation', $details);*/?></td>
                            </tr>-->
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</div>
<div class="clearer"><hr /></div>