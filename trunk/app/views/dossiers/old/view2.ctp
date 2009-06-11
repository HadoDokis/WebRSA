<?php $this->pageTitle = 'Dossier RSA '.$dossier['Dossier']['numdemrsa'];?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $dossier['Dossier']['id'] ) );?>

<div class="with_treemenu">
    <h1>Dossier RSA <?php echo h( $dossier['Dossier']['numdemrsa'] );?></h1>

<div id="ficheDossier">
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
                <td><?php echo h( $dossier['Situationdossierrsa']['etatdosrsa'] );?></td>
            </tr>
        </tbody>
    </table>


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

    function value( $dataDos, $personne, $table, $field ) {
        return ( ( isset( $dataDos[$personne][$table] ) && isset( $dataDos[$personne][$table][$field] ) ) ? ( $dataDos[$personne][$table][$field] ) : null );
    }

    function linkedValue( $links, $dataDos, $personne, $table, $field ) {
        $value = ( ( isset( $dataDos[$personne][$table] ) && isset( $dataDos[$personne][$table][$field] ) ) ? ( $dataDos[$personne][$table][$field] ) : null );
        return ( isset( $links[$value] ) ? $links[$value] : null );
    }
?>

    <table>
        <?php echo thead( 20 );?>
        <tbody>
            <tr class="odd">
                <th>Nom</th>
                <td><?php echo value( $dataDos, 'DEM', 'Personne', 'nom'  );?></td>
                <td><?php echo value( $dataDos, 'CJT', 'Personne', 'nom'  );?></td>
            </tr>
            <tr class="even">
                <th>Prénom</th>
                <td><?php echo value( $dataDos, 'DEM', 'Personne', 'prenom'  );?></td>
                <td><?php echo value( $dataDos, 'CJT', 'Personne', 'prenom'  );?></td>
            </tr>
            <tr class="odd">
                <th>Adresse</th>
                <td><?php echo linkedValue( $adresse['Adresse'], $dataDos, 'DEM', 'Adresse', 'numvoie'  ).' '.value( $adresse['Adresse'], $dataDos, 'DEM', 'Adresse', 'typevoie'  ).' '.value( $adresse['Adresse'], $dataDos, 'DEM', 'Adresse', 'nomvoie'  );?></td>
                <td><?php echo value( $adresse, $dataDos, 'CJT', 'Adresse', 'numvoie'  ).' '.value( $adresse, $dataDos, 'CJT', 'Adresse', 'typevoie'  ).' '.value( $adresse, $dataDos, 'CJT', 'Adresse', 'nomvoie'  );?></td>
<!--                <td><?php echo h( $adresse['Adresse']['numvoie'] ).' '.h( $adresse['Adresse']['typevoie'].' '.$adresse['Adresse']['nomvoie'] );?></td>-->

            </tr>
            <tr class="even">
                <th>N° de téléphone</th>
                <td><?php echo h( $dossier['Foyer']['ModeContact'][0]['numtel'] );?></td>
            </tr> 
            <tr class="odd">
                <th>N° CAF</th>
                <td><?php echo h( $dossier['Dossier']['matricule'] );?></td>
            </tr>
        </tbody>
    </table>
    <table>
        <tbody>
            <h2>Orientation</h2>
            <tr class="odd">
                <th>Type d'orientation</th>
                <td><?php echo h( $orient['Structurereferente']['Typeorient']['lib_type_orient'] );?></td>
            </tr>
            <tr class="even">
                <th>Type de structure</th>
                <td><?php echo h( $orient['Structurereferente']['lib_struc'] );?></td>
            </tr>
            <tr class="odd">
                <th>Date de l'orientation</th>
                <td><?php echo h( date_short( $orient['Orientstruct']['date_valid'] ) );?></td>
            </tr>
        </tbody>
    </table>
    <table>
        <tbody>
            <h2>Contrat d'insertion</h2>
            <tr class="odd">
                <th>Type de contrat</th>
                <td><?php echo h( $contrat['Typocontrat']['lib_typo'] );?></td>
            </tr>
            <tr class="even">
                <th>Date de début</th>
                <td><?php echo h( date_short( $contrat['Contratinsertion']['dd_ci'] ) );?></td>
            </tr>
            <tr class="odd">
                <th>Date de fin</th>
                <td><?php echo h( date_short( $contrat['Contratinsertion']['df_ci'] ) );?></td>
            </tr>
            <tr class="even">
                <th>Date de décision</th>
                <td><?php echo h( date_short( $contrat['Contratinsertion']['lib_struc'] ) );?></td>
            </tr>
            <tr class="odd">
                <th>Décision</th>
                <td><?php echo h( $contrat['Contratinsertion']['decision_ci'] );?></td>
            </tr>
        </tbody>
    </table>
    <table>
        <tbody>
            <h2>Informations CAF</h2>
           <!-- <tr class="odd">
                <th>Date de mise à jour</th>
                <td><?php echo h( $contrat['Typocontrat']['lib_typo'] );?></td>
            </tr> -->
            <tr class="even">
                <th>Date de demande RSA</th>
                <td><?php echo h( date_short( $dossier['Dossier']['dtdemrsa'] ) );?></td>
            </tr>
            <tr class="odd">
                <th>Numéro de demande RSA</th>
                <td><?php echo h( $dossier['Dossier']['numdemrsa'] );?></td>
            </tr>
            <tr class="even">
                <th>DSP</th>
                <td><?php echo h( $dossier['Dossier'] ? 'Oui' : 'Non');?></td>
            </tr>
            <tr class="odd">
                <th>Montant RSA</th>
                <td><?php echo h( $detaildroitrsa['Detailcalculdroitrsa'][0]['mtrsavers'] );?></td>
            </tr>
            <tr class="even">
                <th>Date dernier montant</th>
                <td><?php echo h( date_short( $detaildroitrsa['Detailcalculdroitrsa'][0]['dtderrsavers'] ));?></td>
            </tr>
            <tr class="odd">
                <th>Motif</th>
                <td><?php echo h( $natpf[$detaildroitrsa['Detailcalculdroitrsa'][0]['natpf']]  );?></td>
            </tr>
            <tr class="even">
                <th>Soumis à droit et devoir</th>
                <td><?php echo h( $dossier['Foyer']['Personne'][0]['toppersdrodevorsa'] ? 'Oui' : 'Non' );?></td>
            </tr>
            <tr class="odd">
                <th>Montant INDUS</th>
                <td><?php echo h( $contrat['Contratinsertion']['decision_ci']  );?></td>
            </tr>
            <tr class="even">
                <th>Motif</th>
                <td><?php echo h( $contrat['Structurereferente']['lib_struc']  );?></td>
            </tr>
            <tr class="odd">
                <th>Dossier PDO</th>
                <td><?php echo h( $contrat['Contratinsertion']['decision_ci']  );?></td>
            </tr>
            <tr class="even">
                <th>Motif</th>
                <td><?php echo h( $contrat['Structurereferente']['lib_struc']  );?></td>
            </tr>
            <tr class="odd">
                <th>Date de décision</th>
                <td><?php echo h( $contrat['Contratinsertion']['decision_ci']  );?></td>
            </tr>
            <tr class="even">
                <th>Décision</th>
                <td><?php echo h( date_short( $contrat['Structurereferente']['lib_struc'] ) );?></td>
            </tr>
        </tbody>
    </table>
</div>
</div>
<div class="clearer"><hr /></div>