<html>
    <body>
        <?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
        <?php $this->pageTitle = 'Appel à projets';?>
        <div class="">
            <h1>Appel à projet</h1><br>

            <form method="post" action="candidature_appel_a_projet2"> 

                <fieldset>
                    <legend>Appels à projet / Appel à demandes de subventions</legend>
                        <div class="input date">
                            <label>Date de l'appel à projets / demande de subvention</label>
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
                                <option value="10" selected>octobre</option>
                                <option value="11">novembre</option>
                                <option value="12">décembre</option>
                            </select>-
                            <select>
                                <option value=""></option>
                                <option value="2010" selected>2010</option>
                                <option value="2009">2009</option>
                                <option value="2008">2008</option>
                                <option value="2007">2007</option>
                                <option value="2006">2006</option>
                                <option value="2005">2005</option>
                            </select>
                        </div>

                        <div class="input date" >
                            <label>Type de structure concernée</label>
                            <select class="nomorga" id="organisme">
                                <option value=""></option>
                                <option value="1" selected="1">Organisme de formations</option>
                                <option value="2">SIAE - CG</option>
                                <option value="3">SIAE - DDTEFP</option>
                                <option value="4">Associations : Insertion sociale</option>
                                <option value="4">Associations : Insertion professionnelle</option>
                                <option value="4">Associations : Missions locales / Espaces dynamiques d'insertion</option>
                                <option value="4">Collectivités</option>
                            </select>
                        </div>

                        <div class="input text">
                        <label style="vertical-align: top;">Intitulé de l'appel / demande de subvention</label>
                            <input typetype="text" >
                        </div>

                        <div class="input text">
                        <label style="vertical-align: top;">Description / Intitulé opération</label>
                            <input typetype="text" maxlength="10">
                        </div>

                        <div class="input text">
                        <label style="vertical-align: top;">Référence</label>
                            <input typetype="text" maxlength="10">
                        </div>

                        <div class="input date ">
                        <label style="vertical-align: top;">Date de recevabilité des candidatures</label>
                            Du
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
                                <option value="10" selected>10</option>
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
                                <option value="21">21</option>
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
                                <option value="10">octobre</option>
                                <option value="11" selected>novembre</option>
                                <option value="12">décembre</option>
                            </select>-
                            <select>
                                <option value=""></option>
                                <option value="2010" selected>2010</option>
                                <option value="2009">2009</option>
                                <option value="2008">2008</option>
                                <option value="2007">2007</option>
                                <option value="2006">2006</option>
                                <option value="2005">2005</option>
                            </select>
                            Au
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
                                <option value="20" selected>20</option>
                                <option value="21">21</option>
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
                                <option value="10">octobre</option>
                                <option value="11" selected>novembre</option>
                                <option value="12">décembre</option>
                            </select>-
                            <select>
                                <option value=""></option>
                                <option value="2010" selected>2010</option>
                                <option value="2009">2009</option>
                                <option value="2008">2008</option>
                                <option value="2007">2007</option>
                                <option value="2006">2006</option>
                                <option value="2005">2005</option>
                            </select>
                            max.
                        </div>
                        <div class="input text">
                            <label style="vertical-align: top;">Localisation du bureau et heures d'ouverture :</label>
                            Bureau N° <input typetype="text" style="width:40px" value="236">
                            <label></label>
                            de
                            <select>
                                <option value=""></option>
                                <option value="8">8</option>
                                <option value="9" selected="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                            </select>
                            h
                            <select>
                                <option value=""></option>
                                <option value="00" selected>00</option>
                                <option value="05">05</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="30">30</option>
                                <option value="45">45</option>
                            </select>
                            à
                            <select>
                                <option value=""></option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11" selected>11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                            </select>
                            h
                            <select>
                                <option value=""></option>
                                <option value="8">00</option>
                                <option value="8">05</option>
                                <option value="9">10</option>
                                <option value="10">15</option>
                                <option value="11" selected>30</option>
                                <option value="12">45</option>
                            </select>
                            et
                            <label class="aere"></label>
                            de
                            <select>
                                <option value=""></option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13" selected>13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                            </select>
                            h
                            <select>
                                <option value=""></option>
                                <option value="00" selected>00</option>
                                <option value="05">05</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="30">30</option>
                                <option value="45">45</option>
                            </select>
                            à
                            <select>
                                <option value=""></option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16" selected>16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                            </select>
                            h
                            <select>
                                <option value=""></option>
                                <option value="00" selected="00">00</option>
                                <option value="8">05</option>
                                <option value="9">10</option>
                                <option value="10">15</option>
                                <option value="11" >30</option>
                                <option value="12">45</option>
                            </select>
                        </div>

                    </fieldset>

                    <div class="submit"> 
                        <input value="Suivant" type="submit">
                    </div>
                </form>
            </div>
        <div class="clearer"><hr></div>
    </body>
</html>
<script type="text/javascript">
//     $('organisme').change('click', alert('woot'));
</script>
