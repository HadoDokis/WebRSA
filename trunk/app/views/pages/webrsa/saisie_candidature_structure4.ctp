<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Candidature';?>

<?php
  $projets = array(
    '',
    'Associations accompagnement jeunes 18-25 ans en insertion',
    'Associations insertion professionnelle',
    'Associations insertion sociale',
    'Missions Locales / Espaces Dynamiques d\'Insertion',
    'Organismes de formation',
    'SIAE',
    'Collectivités'
  );
?>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        $("typeorgaodf").hide();
        $("typeorgasiae").hide();
        $("typeorgaddtefp").hide();
        $("typeorgaassoc").hide();
        $("typeorgacollec").hide();
        $("istremplin").hide();


        Event.observe( $("typeorga"), 'change', function() {
            //Si c'est une odf
            if( $("typeorga").value == "odf") {
                $("typeorgaodf").show();
            }
            else {
                $("typeorgaodf").hide();
            }

            //Si c'est une siae
            if( $("typeorga").value == "siae") {
                $("typeorgasiae").show();
            }
            else {
                $("typeorgasiae").hide();
            }

            //Si c'est autre ddtefp
            if( $("typeorga").value == "ddtefp") {
                $("typeorgaddtefp").show();
            }
            else {
                $("typeorgaddtefp").hide();
            }

            //Si c'est autre association
            if( $("typeorga").value == "assoc") {
                $("typeorgaassoc").show();
            }
            else {
                $("typeorgaassoc").hide();
            }

            //Si c'est autre collectivité
            if( $("typeorga").value == "collec") {
                $("typeorgacollec").show();
            }
            else {
                $("typeorgacollec").hide();
            }
        } );

        Event.observe( $("tremplins"), 'change', function() {
            //Si c'est une association
            if( $("tremplins").value == "oui") {
                $("istremplin").show();
            }
            else {
                $("istremplin").hide();
            }
        } );
    });
</script>

<div class="">
    <h1>Saisie d'une candidature - Structure</h1><br />
    <h2>2. Renseignements concernants les ressources humaines</h2>

        <form method="post" action="saisie_candidature_structure5">

        <fieldset>
            <div class="input date aere">
                <label>Type d'organisme</label>
                <select id="typeorga" name="statut">
                    <option value=""></option>
                    <option value="odf">Organisme de formation</option>
                    <option value="siae">SIAE</option>
                    <option value="ddtefp">DDTEFP</option>
                    <option value="assoc">Associations</option>
                    <option value="collec">Collectivités</option>
                </select>
            </div>

<!-- Organisme de Formations -->
            <fieldset id="typeorgaodf" class="invisible" >
                <div class="input text" >
                    <label>Nombre total de salariés</label>
                    <input type="text" />
                </div>
                <label style="vertical-align: top;">Nombre de salariés en CDI</label>
                <div class="input text" style="  margin-left:2em;">
                    <label>A temps plein</label>
                    <input type="text" />
                </div>
                <div class="input text" style="  margin-left:2em;">
                    <label>A temps partiel</label>
                    <input type="text" />
                </div>
                <label style="vertical-align: top;">Nombre de salariés en CDD</label>
                <div class="input text" style="  margin-left:2em;">
                    <label>A temps plein</label>
                    <input type="text" />
                </div>
                <div class="input text" style="  margin-left:2em;">
                    <label>A temps partiel</label>
                    <input type="text" />
                </div>
                <label style="vertical-align: top;">Nombre de bénévoles</label>
                <div class="input text" style="  margin-left:2em;">
                    <label>Nombre</label>
                    <input type="text" />
                </div>
                <div class="input text" style="  margin-left:2em;">
                    <label>Nombre d'heures annuelles effectuées par les bénévoles</label>
                    <input type="text" />
                </div>
                <div class="input text" >
                    <label>Cumul des cinq salaires annuels bruts les plus élevés</label>
                    <input type="text" />
                </div>
                <div class="input text" >
                    <label>Autres informations pertinentes concernant les moyens humains que vous souhaitez indiquer</label>
                    <input type="text" />
                </div>
            </fieldset>
<!-- SIAE -->
            <fieldset id="typeorgasiae" class="invisible" >
                <div class="input text" >
                    <label>Nombre total de salariés</label>
                    <input type="text" />
                </div>
                <label style="vertical-align: top;">Nombre de salariés en CDI</label>
                <div class="input text" style="  margin-left:2em;">
                    <label>A temps plein</label>
                    <input type="text" />
                </div>
                <div class="input text" style="  margin-left:2em;">
                    <label>A temps partiel</label>
                    <input type="text" />
                </div>
                <label style="vertical-align: top;">Nombre de salariés en CDD</label>
                <div class="input text" style="  margin-left:2em;">
                    <label>A temps plein</label>
                    <input type="text" />
                </div>
                <div class="input text" style="  margin-left:2em;">
                    <label>A temps partiel</label>
                    <input type="text" />
                </div>
                <label style="vertical-align: top;">Nombre de bénévoles</label>
                <div class="input text" style="  margin-left:2em;">
                    <label>Nombre</label>
                    <input type="text" />
                </div>
                <div class="input text" style="  margin-left:2em;">
                    <label>Nombre d'heures annuelles effectuées par les bénévoles</label>
                    <input type="text" />
                </div>
                <div class="input text" >
                    <label>Cumul des cinq salaires annuels bruts les plus élevés</label>
                    <input type="text" />
                </div>
                <div class="input text" >
                    <label>Nombres de contrats aidés parmi le personnel d'encadrement des allocataires du RSA</label>
                    <input type="text" />
                </div>
                <div class="input text" >
                    <label>Nom / Prénom / Intitulé du poste</label>
                    <input type="text" />
                </div>
                <label style="vertical-align: top;">Existe-t-il un plan de formation pour votre personnel</label>
                <div class="input text" style="  margin-left:2em;">
                    <label>D'encadrement technique</label>
                    <select>
                        <option value=""></option>
                        <option value="oui">Oui</option>
                        <option value="non">Non</option>
                    </select>
                </div>
                <div class="input text" style="  margin-left:2em;">
                    <label>D'accompagnement social</label>
                    <select>
                        <option value=""></option>
                        <option value="oui">Oui</option>
                        <option value="non">Non</option>
                    </select>
                </div>
                <div class="input text" >
                    <label>OPCA collecteur</label>
                    <input type="text" />
                </div>
                <div class="input text" >
                    <label>Avez-vous des emplois tremplins co-financés par le Département ?</label>
                    <select id="tremplins">
                        <option value=""></option>
                        <option value="oui">Oui</option>
                        <option value="non">Non</option>
                    </select>
                </div>
                <fieldset id="istremplin" class="invisible">
                    <div class="input text" >
                        <label>Nom / Prénom / Intitulé du poste / Année de création du poste</label>
                        <input type="text" />
                    </div>
                </fieldset>
            </fieldset>
<!-- DDTEFP -->
            <fieldset id="typeorgaddtefp" class="invisible" >
                <table>
                    <thead>
                        <tr>
                            <th colspan="5" style="background-color:#4F4F4F;">ANNEE N-1</th>
                        </tr>
                        <tr>
                            <th>Nom/Prénom</th>
                            <th>Fonctions</th>
                            <th>Nature du contrat (CDD, CDI, contrats aidés)</th>
                            <th>ETP</th>
                            <th>Montant du salaire mensuel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Partie Gestion - administration-->
                        <tr>
                            <td colspan="5" style="background-color:#BFBFBF;">Gestion - Administration</td>
                        </tr>
                        <tr>
                            <td><?php echo $form->input( 'Nom', array( 'label' => false, 'div' => false, 'type' => 'text', 'value' => 'AUZOLAT Arnaud' ) );?></td>
                            <td><?php echo $form->input( 'Fonction', array( 'label' => false, 'div' => false, 'type' => 'text', 'value' => 'Chef de Projet' ) );?></td>
                            <td><?php echo $form->input( 'Nature', array( 'label' => false, 'div' => false, 'type' => 'text', 'value' => 'CDI' ) );?></td>
                            <td><?php echo $form->input( 'ETP', array( 'label' => false, 'div' => false, 'type' => 'text', 'value' => '1000€' ) );?></td>
                            <td><?php echo $form->input( 'Montant', array( 'label' => false, 'div' => false, 'type' => 'text', 'value' => '1000€' ) );?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->input( 'Nom2', array( 'label' => false, 'div' => false, 'type' => 'text', 'value' => 'BUFFIN Christian' ) );?></td>
                            <td><?php echo $form->input( 'Prenom2', array( 'label' => false, 'div' => false, 'type' => 'text', 'value' => 'Développeur' ) );?></td>
                            <td><?php echo $form->input( 'Nature2', array( 'label' => false, 'div' => false, 'type' => 'text', 'value' => 'CDI' ) );?></td>
                            <td><?php echo $form->input( 'ETP2', array( 'label' => false, 'div' => false, 'type' => 'text', 'value' => '1000€' ) );?></td>
                            <td><?php echo $form->input( 'Montant2', array( 'label' => false, 'div' => false, 'type' => 'text', 'value' => '1000€' ) );?></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Nombre total de personnes</td>
                            <td colspan="4"><?php echo $form->input( 'Nombre3', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                        </tr>
                        <!-- Partie Accompagnement social-->
                        <tr>
                            <td colspan="5" style="background-color:#BFBFBF;">Accompagnement social, professionnel et formation</td>
                        </tr>
                        <tr>
                            <td><?php echo $form->input( 'Nom3', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'Fonction3', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'Nature3', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'ETP3', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'Montant3', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->input( 'Nom4', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'Fonction4', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'Nature4', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'ETP4', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'Montant4', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Nombre total de personnes</td>
                            <td colspan="4"><?php echo $form->input( 'Nombre4', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                        </tr>
                        <!-- Partie Encadrement technique et ouvrier de production -->
                        <tr>
                            <td colspan="5" style="background-color:#BFBFBF;">Encadrement technique et ouvrier de production</td>
                        </tr>
                        <tr>
                            <td><?php echo $form->input( 'Nom5', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'Fonction5', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'Nature5', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'ETP5', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'Montant5', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->input( 'Nom6', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'Fonction6', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'Nature6', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'ETP6', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'Montant6', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Nombre total de personnes</td>
                            <td colspan="4"><?php echo $form->input( 'Nombre6', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Total de salariés permanents</td>
                            <td colspan="4"><?php echo $form->input( 'Nombre7', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <thead>
                        <tr>
                            <th colspan="5" style="background-color:#4F4F4F;">ANNEE EN COURS</th>
                        </tr>
                        <tr>
                            <th>Nom/Prénom</th>
                            <th>Fonctions</th>
                            <th>Nature du contrat (CDD, CDI, contrats aidés)</th>
                            <th>ETP</th>
                            <th>Montant du salaire mensuel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Partie Gestion - administration-->
                        <tr>
                            <td colspan="5" style="background-color:#BFBFBF;">Gestion - Administration</td>
                        </tr>
                        <tr>
                            <td><?php echo $form->input( 'Nom7', array( 'label' => false, 'div' => false, 'type' => 'text', 'value' => 'AUZOLAT Arnaud' ) );?></td>
                            <td><?php echo $form->input( 'Fonction7', array( 'label' => false, 'div' => false, 'type' => 'text', 'value' => 'Chef de rojet' ) );?></td>
                            <td><?php echo $form->input( 'Nature7', array( 'label' => false, 'div' => false, 'type' => 'text', 'value' => 'CDI' ) );?></td>
                            <td><?php echo $form->input( 'ETP7', array( 'label' => false, 'div' => false, 'type' => 'text', 'value' => '1000€' ) );?></td>
                            <td><?php echo $form->input( 'Montant7', array( 'label' => false, 'div' => false, 'type' => 'text', 'value' => '1000€' ) );?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->input( 'Nom8', array( 'label' => false, 'div' => false, 'type' => 'text', 'value' => 'BUFFIN Christian' ) );?></td>
                            <td><?php echo $form->input( 'Fonction8', array( 'label' => false, 'div' => false, 'type' => 'text', 'value' => 'Développeur' ) );?></td>
                            <td><?php echo $form->input( 'Nature8', array( 'label' => false, 'div' => false, 'type' => 'text', 'value' => 'CDI' ) );?></td>
                            <td><?php echo $form->input( 'ETP8', array( 'label' => false, 'div' => false, 'type' => 'text', 'value' => '1000€' ) );?></td>
                            <td><?php echo $form->input( 'Montant8', array( 'label' => false, 'div' => false, 'type' => 'text', 'value' => '1000€' ) );?></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Nombre total de personnes</td>
                            <td colspan="4"><?php echo $form->input( 'Nombre9', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                        </tr>
                        <!-- Partie Accompagnement social-->
                        <tr>
                            <td colspan="5" style="background-color:#BFBFBF;">Accompagnement social, professionnel et formation</td>
                        </tr>
                        <tr>
                            <td><?php echo $form->input( 'Nom9', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'Fonction9', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'Nature9', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'ETP9', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'Montant9', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->input( 'Nom10', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'Fonction10', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'Nature10', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'ETP10', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'Montant10', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Nombre total de personnes</td>
                            <td colspan="4"><?php echo $form->input( 'Nombre10', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                        </tr>
                        <!-- Partie Encadrement technique et ouvrier de production -->
                        <tr>
                            <td colspan="5" style="background-color:#BFBFBF;">Encadrement technique et ouvrier de production</td>
                        </tr>
                        <tr>
                            <td><?php echo $form->input( 'Nom11', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'Fonction11', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'Nature11', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'ETP11', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'Montant11', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->input( 'Nom12', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'Fonction12', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'Nature12', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'ETP12', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                            <td><?php echo $form->input( 'Montant12', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Nombre total de personnes</td>
                            <td colspan="4"><?php echo $form->input( 'Nombre12', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Total de salariés permanent</td>
                            <td colspan="4"><?php echo $form->input( 'Nombre13', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
                        </tr>
                    </tbody>
                </table>
                <br />
                <div class="input text" >
                    <label style="vertical-align: top;">Formation des salariés permanents et des bénévoles (fournir le plan de formation de l’année 2008 et 2009)</label>
                    <textarea cols="150" rows="3"></textarea>
                </div>
                <div class="input text" >
                    <label style="vertical-align: top;">Valorisation du bénévolat (pour les associations non assujetties à l’impôt sur les sociétés), ETP par fonction</label>
                    <table>
                        <thead>
                            <tr>
                                <th>Fonction</th>
                                <th>ETP</th>
                            </tr>
                        </thead>
                        <tbody>
                             <tr>
                                <td style="width:600px"><?php echo $form->input( 'Fonction14', array( 'label' => false, 'div' => false, 'type' => 'text', 'style' => 'width:600px' ) );?></td>
                                <td style="width:200px"><?php echo $form->input( 'ETP14', array( 'label' => false, 'div' => false, 'type' => 'text', 'style' => 'width:200px' ) );?></td>
                            </tr>
                            <tr>
                                <td style="width:600px"><?php echo $form->input( 'Fonction15', array( 'label' => false, 'div' => false, 'type' => 'text', 'style' => 'width:600px' ) );?></td>
                                <td style="width:200px"><?php echo $form->input( 'ETP15', array( 'label' => false, 'div' => false, 'type' => 'text', 'style' => 'width:200px' ) );?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </fieldset>
<!-- Associations -->
            <fieldset id="typeorgaassoc" class="invisible" >
                <label style="vertical-align: top;">Nombre d'adhérents de l'association au 31 Décembre de l'année écoulée</label>
                <div class="input text" style="  margin-left:2em;">
                    <label>Dont hommes </label>
                    <input type="text" />
                </div>
                <div class="input text" style="  margin-left:2em;">
                    <label>Dont femmes</label>
                    <input type="text" />
                </div>

                <label style="vertical-align: top; font-weight:bold;">Moyens humains de l'association<br /></label><label><em>Bénévole: personne contribuant régulièrement à l'activité de l'association, de manière non rémunérée</em>  </label>
                <div class="input text" >
                    <label>Nombre de bénévoles</label>
                    <input type="text" />
                </div>
                <div class="input text" >
                    <label>Nombre de volontaires</label>
                    <input type="text" />
                </div>
                <div class="input text" >
                    <label>Nombre total de salariés</label>
                    <input type="text" />
                </div>
                <div class="input text" >
                    <label>Nombre de salariés en équivalent temps plein travaillé (ETPT)</label>
                    <input type="text" />
                </div>
                <div class="input text" >
                    <label>Cumul des cinq salaires annuels bruts les plus élevés</label>
                    <input type="text" />
                </div>
            </fieldset>
<!-- Collectivités -->
            <fieldset id="typeorgacollec" class="invisible" >
                <div class="input " >
                    <label style="vertical-align: top; width:100%">Moyens humains et matériels à la date de la demande <em>(préciser le nombre de salarés, bénévoles... et toutes informations pertinentes)</em></label>
                    <textarea cols="150" rows="3"></textarea>
                </div>
            </fieldset>


        </fieldset>

            <div class="submit">
                <a href="saisie_candidature_structure3"><input value="Précédent" type="submit" /></a>
                <a href="saisie_candidature_structure5"><input value="Suivant" type="submit" /></a>
            </div>
        </form></div>

<div class="clearer"><hr /></div>