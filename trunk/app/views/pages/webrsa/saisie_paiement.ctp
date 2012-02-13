<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php $this->pageTitle = 'Saisie des déclenchements de paiement';?>


    <div class="">
        <h1>Déclenchement du paiement de la convention N° xxx</h1><br>

        <form method="post" action="suivi_paiement">


        <fieldset>
            <legend>Durée de la convention : </legend>
                <div class="input date">
                <label>Date de début de la convention</label>
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
                        <option value="11" selected="11">11</option>
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
                        <option value="01" selected="janvier">janvier</option>
                        <option value="02">février</option>
                        <option value="03">mars</option>
                        <option value="04">avril</option>
                        <option value="05">mai</option>
                        <option value="06">juin</option>
                        <option value="07">juillet</option>
                        <option value="08">août</option>
                        <option value="09">septembre</option>
                        <option value="10">octobre</option>
                        <option value="11">novembre</option>
                        <option value="12">décembre</option>
                    </select>-
                    <select>
                        <option value=""></option>
                        <option value="2011" selected="2011" >2011</option>
                        <option value="2010" >2010</option>
                        <option value="2009">2009</option>
                        <option value="2008">2008</option>
                        <option value="2007">2007</option>
                        <option value="2006">2006</option>
                        <option value="2005">2005</option>
                    </select>

                </div>
                <div class="input date">
                    <label>Date de fin de la convention</label>
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
                        <option value="10" selected="10">10</option>
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
                        <option value="06" selected="juin">juin</option>
                        <option value="07">juillet</option>
                        <option value="08">août</option>
                        <option value="09">septembre</option>
                        <option value="10">octobre</option>
                        <option value="11">novembre</option>
                        <option value="12">décembre</option>
                    </select>-
                    <select>
                        <option value=""></option>
                        <option value="2011" selected="2011">2011</option>
                        <option value="2010">2010</option>
                        <option value="2009">2009</option>
                        <option value="2008">2008</option>
                        <option value="2007">2007</option>
                        <option value="2006">2006</option>
                        <option value="2005">2005</option>
                    </select>

                </div>
        </fieldset>

        <fieldset>
            <legend>Versements</legend>

            <div class="input text">
                <label style="vertical-align: top;">Nombre de versements : </label>
                <select>
                    <option value=""></option>
                    <option value="1">1</option>
                    <option value="2" selected="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>

            <!-- % 1er versement -->
            <div class="input aere text ">
                <label style="vertical-align: top;">Pourcentage du 1er versement </label>
                <input type="text" style="width:30px" value="70">
            </div>
            <label style="vertical-align: top;">Date du 1er versement :</label>
            <div class="input text" style="margin-left:2em;">
                    <label>- A la signature de la convention</label>
                        <label><input type="checkbox" value="1" /></label>
                    <label>- A la date suivante</label>
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
                            <option value="10" selected="10">10</option>
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
                            <option value="06" selected="juin">juin</option>
                            <option value="07">juillet</option>
                            <option value="08">août</option>
                            <option value="09">septembre</option>
                            <option value="10">octobre</option>
                            <option value="11">novembre</option>
                            <option value="12">décembre</option>
                        </select>-
                        <select>
                            <option value=""></option>
                            <option value="2011" selected="2011">2011</option>
                            <option value="2010">2010</option>
                            <option value="2009">2009</option>
                            <option value="2008">2008</option>
                            <option value="2007">2007</option>
                            <option value="2006">2006</option>
                            <option value="2005">2005</option>
                        </select>
                </div>


            <!-- % 2eme versement -->
            <div class="input text ">
                <label style="vertical-align: top;">Pourcentage du 2ème versement </label>
                <input type="text" style="width:30px" value="30">
            </div>
            <label style="vertical-align: top;">Date du 2ème versement :</label>
            <div class="input text" style="margin-left:2em;">
                    <label>- A réception des bilans d'activité</label>
                        <label><input type="checkbox" value="1" /></label>
                    <label>- A la date suivante</label>
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
                            <option value="10" selected="10">10</option>
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
                            <option value="06" selected="juin">juin</option>
                            <option value="07">juillet</option>
                            <option value="08">août</option>
                            <option value="09">septembre</option>
                            <option value="10">octobre</option>
                            <option value="11">novembre</option>
                            <option value="12">décembre</option>
                        </select>-
                        <select>
                            <option value=""></option>
                            <option value="2011" selected="2011">2011</option>
                            <option value="2010">2010</option>
                            <option value="2009">2009</option>
                            <option value="2008">2008</option>
                            <option value="2007">2007</option>
                            <option value="2006">2006</option>
                            <option value="2005">2005</option>
                        </select>
                </div>

          </fieldset>

            <div class="submit"><input value="Suivi" type="submit"></div>

        </form></div>

<div class="clearer"><hr></div>            </div>

        </div>

    </body></html>