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
    $("association").hide();
    $("entreprise").hide();
    $("autre").hide();
    $("admin").hide();
    $("datejo").hide();
    $("coop").hide();

        Event.observe( $("statutjuridique"), 'change', function() {
            //Si c'est une association
            if( $("statutjuridique").value == "assoc") {
                $("association").show();
            }
            else {
                $("association").hide();
            }

            //Si c'est une entreprise
            if( $("statutjuridique").value == "ent") {
                $("entreprise").show();
            }
            else {
                $("entreprise").hide();
            }

            //Si c'est autre chose
            if( $("statutjuridique").value == "aut") {
                $("autre").show();
            }
            else {
                $("autre").hide();
            }
        } );

        Event.observe( $("agrements"), 'change', function() {
            //Pour la notion d'agrément
            if( $("agrements").value == "oui") {
                $("admin").show();
            }
            else {
                $("admin").hide();
            }
        });

        Event.observe( $("rup"), 'change', function() {
            //Pour la notion d'agrément
            if( $("rup").value == "oui") {
                $("datejo").show();
            }
            else {
                $("datejo").hide();
            }
        });

        Event.observe( $("cooplocale"), 'change', function() {
            //Pour la notion d'agrément
            if( $("cooplocale").value == "oui") {
                $("coop").show();
            }
            else {
                $("coop").hide();
            }
        });

//         observeDisableFieldsOnValue( $("statutjuridique"), [ $("association") ], 'assoc', false );
    });
</script>

<div class="">
    <h1>Saisie d'une candidature - Structure</h1><br>
    <h2>1. Renseignements administratifs et juridiques</h2>

        <form method="post" id="juridiqueform" action="saisie_candidature_structure4">

        <fieldset>
            <div class="input date aere">
                <label>Statut juridique</label>
                <select id="statutjuridique" name="statut">
                    <option value=""></option>
                    <option value="assoc">Association</option>
                    <option value="collec">Collectivité locale / Etablissement public</option>
                    <option value="orga">Organisme consulaire</option>
                    <option value="ent">Entreprise</option>
                    <option value="aut">Autre</option>
                </select>
            </div>

            <fieldset id="association" class="invisible" >
                <div class="input date">
                    <label style="vertical-align: bottom;">Date de publication de la création au Journal Officiel :</label>
                    <select>
                        <option value=""></option>
                        <option value="01">1</option>
                        <option value="02">2</option>
                        <option value="03">3</option>
                        <option value="04">4</option>
                        <option value="05">5</option>
                        <option value="06">6</option>
                        <option value="07">7</option>
                        <option value="08">8</option>
                        <option value="09">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21" selected="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option>
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                        <option value="31">31</option>
                    </select>-
                    <select>
                        <option value=""></option>
                        <option value="01">janvier</option>
                        <option value="02">février</option>
                        <option value="03">mars</option>
                        <option value="04">avril</option>
                        <option value="05">mai</option>
                        <option value="06">juin</option>
                        <option value="07">juillet</option>
                        <option value="08">août</option>
                        <option value="09">septembre</option>
                        <option value="10" selected="octobre">octobre</option>
                        <option value="11">novembre</option>
                        <option value="12">décembre</option>
                    </select>-
                    <select>
                        <option value=""></option>
                        <option value="2010" selected="2010">2010</option>
                        <option value="2009">2009</option>
                        <option value="2008">2008</option>
                        <option value="2007">2007</option>
                        <option value="2006">2006</option>
                        <option value="2005">2005</option>
                    </select>
                </div>
            </fieldset>

            <fieldset id="entreprise" class="invisible" >
                <div  class="input date">
                    <label style="vertical-align: bottom;">Préciser la forme juridique </label>
                    <select>
                        <option value=""></option>
                        <option value="eurl">EURL</option>
                        <option value="sarl">SARL</option>
                        <option value="sa">SA</option>
                        <option value="entindiv">Entreprise individuelle</option>
                        <option value="encours">En cours</option>
                    </select>
                </div>
                <div class="input date">
                    <label style="vertical-align: bottom;">Date de création</label>
                    <select>
                        <option value=""></option>
                        <option value="01">1</option>
                        <option value="02">2</option>
                        <option value="03">3</option>
                        <option value="04">4</option>
                        <option value="05">5</option>
                        <option value="06">6</option>
                        <option value="07">7</option>
                        <option value="08">8</option>
                        <option value="09">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21" selected="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option>
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                        <option value="31">31</option>
                    </select>-
                    <select>
                        <option value=""></option>
                        <option value="01">janvier</option>
                        <option value="02">février</option>
                        <option value="03">mars</option>
                        <option value="04">avril</option>
                        <option value="05">mai</option>
                        <option value="06">juin</option>
                        <option value="07">juillet</option>
                        <option value="08">août</option>
                        <option value="09">septembre</option>
                        <option value="10" selected="octobre">octobre</option>
                        <option value="11">novembre</option>
                        <option value="12">décembre</option>
                    </select>-
                    <select>
                        <option value=""></option>
                        <option value="2010" selected="2010">2010</option>
                        <option value="2009">2009</option>
                        <option value="2008">2008</option>
                        <option value="2007">2007</option>
                        <option value="2006">2006</option>
                        <option value="2005">2005</option>
                    </select>
                </div>
            </fieldset>

            <fieldset id="autre" class="invisible" >
                <div  class="input text">
                    <label style="vertical-align: bottom;">Préciser </label>
                    <input type="text">
                </div>
            </fieldset>

            <div class="input text">
                <label>N° statut juridique INSEE</label>
                <input type="text">
            </div>

            <div class="input text">
                <label>Votre structure dispose-t-elle d’agrémént(s) administratif(s) </label>
                <select id="agrements">
                    <option value=""></option>
                    <option value="oui">Oui</option>
                    <option value="non">Non</option>
                </select>
            </div>
                <fieldset id="admin" class="invisible">
                    <!--<legend>Si oui, préciser le(s)quel(s)</legend>-->
                    <div class="input text">
                        <label>Type d'agrément </label>
                        <select>
                            <option value=""></option>
                            <option value="odf">Organisme de formation</option>
                            <option value="siae">SIAE</option>
                            <option value="drass">DRASS</option>
                            <option value="fepem">FEPEM</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div class="input text">
                        <label>N° d'agrément</label>
                        <input type="text">
                    </div>
                    <div class="input text">
                        <label>Attribué par</label>
                        <input type="text">
                    </div>
                    <div class="input date">
                        <label>En date du</label>
                        <select>
                            <option value=""></option>
                            <option value="01">1</option>
                            <option value="02">2</option>
                            <option value="03">3</option>
                            <option value="04">4</option>
                            <option value="05">5</option>
                            <option value="06">6</option>
                            <option value="07">7</option>
                            <option value="08">8</option>
                            <option value="09">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21" selected="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">31</option>
                        </select>-
                        <select>
                            <option value=""></option>
                            <option value="01">janvier</option>
                            <option value="02">février</option>
                            <option value="03">mars</option>
                            <option value="04">avril</option>
                            <option value="05">mai</option>
                            <option value="06">juin</option>
                            <option value="07">juillet</option>
                            <option value="08">août</option>
                            <option value="09">septembre</option>
                            <option value="10" selected="octobre">octobre</option>
                            <option value="11">novembre</option>
                            <option value="12">décembre</option>
                        </select>-
                        <select>
                            <option value=""></option>
                            <option value="2010" selected="2010">2010</option>
                            <option value="2009">2009</option>
                            <option value="2008">2008</option>
                            <option value="2007">2007</option>
                            <option value="2006">2006</option>
                            <option value="2005">2005</option>
                        </select>
                    </div>
                </fieldset>
                <div class="input text">
                    <label>Votre structure a-t-elle l’agrément d’entreprise solidaire ? </label>
                    <select>
                        <option value=""></option>
                        <option value="oui">Oui</option>
                        <option value="non">Non</option>
                    </select>
                </div>
                <div class="input text">
                    <label>Votre association est-elle reconnue d’utilité publique ? </label>
                    <select id="rup">
                        <option value=""></option>
                        <option value="oui">Oui</option>
                        <option value="non">Non</option>
                    </select>
                </div>
                    <fieldset id="datejo" class="invisible">
                        <div class="input date">
                            <label>Date de publication au Journal Officiel</label>
                            <select>
                                <option value=""></option>
                                <option value="01">1</option>
                                <option value="02">2</option>
                                <option value="03">3</option>
                                <option value="04">4</option>
                                <option value="05">5</option>
                                <option value="06">6</option>
                                <option value="07">7</option>
                                <option value="08">8</option>
                                <option value="09">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21" selected="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                                <option value="25">25</option>
                                <option value="26">26</option>
                                <option value="27">27</option>
                                <option value="28">28</option>
                                <option value="29">29</option>
                                <option value="30">30</option>
                                <option value="31">31</option>
                            </select>-
                            <select>
                                <option value=""></option>
                                <option value="01">janvier</option>
                                <option value="02">février</option>
                                <option value="03">mars</option>
                                <option value="04">avril</option>
                                <option value="05">mai</option>
                                <option value="06">juin</option>
                                <option value="07">juillet</option>
                                <option value="08">août</option>
                                <option value="09">septembre</option>
                                <option value="10" selected="octobre">octobre</option>
                                <option value="11">novembre</option>
                                <option value="12">décembre</option>
                            </select>-
                            <select>
                                <option value=""></option>
                                <option value="2010" selected="2010">2010</option>
                                <option value="2009">2009</option>
                                <option value="2008">2008</option>
                                <option value="2007">2007</option>
                                <option value="2006">2006</option>
                                <option value="2005">2005</option>
                            </select>
                        </div>
                    </fieldset>
                <div class="input text">
                    <label>Votre structure dispose-t-elle d’un Commissaire aux Comptes ? </label>
                    <select id="rup">
                        <option value=""></option>
                        <option value="oui">Oui</option>
                        <option value="non">Non</option>
                    </select>
                </div>

                <label style="vertical-align: top; font-weight:bold;">Références bancaires ou postales</label>
                <div class="input text">
                    <label>Nom du titulaire du compte</label>
                    <input type="text">
                </div>
                <div class="input text">
                    <label>Libellé Banque</label>
                    <input type="text">
                </div>
                <div class="input text">
                    <label>Domiciliation</label>
                    <input type="text">
                </div>
                <div class="input text">
                    <label>Code guichet</label>
                    <input type="text">
                </div>
                <div class="input text">
                    <label>Code banque</label>
                    <input type="text">
                </div>
                <div class="input text">
                    <label>Numéro de compte</label>
                    <input type="text">
                </div>
                <div class="input text">
                    <label>Clé RIB</label>
                    <input type="text">
                </div>

                <label style="vertical-align: top; font-weight:bold;">Coordonnées du comptable (facultatif)</label>
                <div class="input text">
                    <label>Nom </label>
                    <input type="text">
                </div>
                <div class="input text">
                    <label>Adresse postale</label>
                    <input type="text">
                </div>
                <div class="input text">
                    <label>Téléphone</label>
                    <input type="text">
                </div>
                <div class="input text">
                    <label>Adresse électronique</label>
                    <input type="text">
                </div>


                <label style="vertical-align: top; font-weight:bold;">DDTEFP</label>
                <div class="input text">
                    <label>Adhérez-vous à un réseau ? </label>
                    <select id="reseau">
                        <option value=""></option>
                        <option value="oui">Oui</option>
                        <option value="non">Non</option>
                    </select>
                </div>
                <div class="input text">
                    <label>Votre structure a-t-elle signée une convention de coopération locale  ? </label>
                    <select id="cooplocale">
                        <option value=""></option>
                        <option value="oui">Oui</option>
                        <option value="non">Non</option>
                    </select>
                </div>
                    <fieldset id="coop" class="invisible">
                        <div class="input text">
                            <label>Joindre une copie</label>
                        </div>
                        <div class="input text">
                            <label>Le(s)quel(s)</label>
                            <input type="text">
                        </div>
                    </fieldset>
            </fieldset>


            <div class="submit">
                <a href="saisie_candidature_structure2"><input value="Précédent" type="submit"></a>
                <a href="saisie_candidature_structure4"><input value="Suivant" type="submit"></a>
            </div>
        </form></div>

<div class="clearer"><hr></div>            </div>

        </div>

    </body></html>
