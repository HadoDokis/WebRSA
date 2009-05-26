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
    <table>
        <tbody>
            <tr class="odd">
                <th>Nom</th>
                <td><?php echo h( $dossier['Foyer']['Personne'][0]['nom'] );?></td>
            </tr>
            <tr class="even">
                <th>Prénom</th>
                <td><?php echo h( $dossier['Foyer']['Personne'][0]['prenom'] );?></td>
            </tr>
            <tr class="odd">
                <th>Adresse</th>
                <td><?php echo h( $adresse['Adresse']['numvoie'] ).' '.h( $adresse['Adresse']['typevoie'].' '.$adresse['Adresse']['nomvoie'] );?></td>
            </tr>
            <tr class="even">
                <th>N° de téléphone</th>
                <td><?php echo h( isset( $dossier['Foyer']['ModeContact'][0]['numtel'] ) ? $dossier['Foyer']['ModeContact'][0]['numtel'] : null );?></td>
            </tr> 
            <tr class="odd">
                <th>N° CAF</th>
                <td><?php echo h( $dossier['Dossier']['matricule'] );?></td>
            </tr>
        </tbody>
    </table>
    <h2>Orientation</h2>
    <table>
        <tbody>
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
    <h2>Contrat d'insertion</h2>
    <table>
        <tbody>
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
                <td><?php echo h( date_short( $contrat['Contratinsertion']['datevalidation_ci'] ) );?></td>
            </tr>
            <tr class="odd">
                <th>Décision</th>
                <td><?php echo h( $decision_ci[$contrat['Contratinsertion']['decision_ci']].' '.date_short( $contrat['Contratinsertion']['datevalidation_ci'] ) );?></td>
            </tr>
        </tbody>
    </table>
    <h2>Informations CAF</h2>
    <table>
        <tbody>
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
                <td><?php echo h( isset($natpf[$detaildroitrsa['Detailcalculdroitrsa'][0]['natpf']] ) ? $natpf[$detaildroitrsa['Detailcalculdroitrsa'][0]['natpf']] : null );?></td>
            </tr>
            <tr class="even">
                <th>Soumis à droit et devoir</th>
                <td><?php echo h( $dossier['Foyer']['Personne'][0]['toppersdrodevorsa'] ? 'Oui' : 'Non' );?></td>
            </tr>
            <tr class="odd">
                <th>Montant INDUS</th>
                <td><?php echo h( $infofinance['Infofinanciere']['mtmoucompta']  );?></td>
            </tr>
            <tr class="even">
                <th>Motif</th>
                <td><?php echo h( $creance['Creance']['motiindu']  );?></td>
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
