<?php
defined('_JEXEC') or die;
?>


<head>
<style>
    .insertbox{

        background-color: #d9edf7;
        margin-left: 3px;
    }

    .nome{

        width: 25%;
    }

    .cognome{

        width: 25%;
    }

    .valore_orario{

        width: 10%;
    }
    .monte_ore{

        width: 10%;
    }
    #contenitore_ruoli{

        width: 10%;

    }

    .bottoni{

        width: 20%;
    }

    .red{

        color:red;
    }

    .green{

        color:lawngreen;
    }

    .start_hidden_input,.confirm_button{

        display: none;
    }


    .delete_ruolo{
        font-size: smaller;
    }

</style>
</head>


<div class="table-responsive">
    <h2>FOR - GESTIONE PIANI FORMATIVI - DIPENDENTI</h2>
    <table class="table table-striped table-bordered ">
        <thead>
        <tr>
            <th>COGNOME</th>
            <th>NOME</th>
            <th>VALORE ORARIO</th>
            <th>MONTE ORE</th>
            <th>RUOLI</th>
            <th></th>
        </tr>
        </thead>

        <tbody>

        <?php
        foreach ($this->dipendenti as $dipendente) {
            $ruoli=null;
            ?>
                <tr>
                    <td class="cognome"><span class="start_span" id="span_cognome_<?php echo $dipendente['id']; ?>"><?php echo $dipendente['cognome']; ?></span>
                        <input id="input_cognome_<?php echo $dipendente['id']; ?>" class="start_hidden_input form-control form-control-sm" type="text" value="<?php echo $dipendente['cognome']; ?>"></td>
                    <td class="nome"><span class="start_span" id="span_nome_<?php echo $dipendente['id']; ?>"><?php echo $dipendente['nome']; ?></span>
                        <input id="input_nome_<?php echo $dipendente['id']; ?>" class="start_hidden_input form-control form-control-sm" type="text" value="<?php echo $dipendente['nome']; ?>"></td>
                    <td class="valore_orario"><span class="start_span" id="span_valore_orario_<?php echo $dipendente['id']; ?>"><?php echo $dipendente['valore_orario']; ?></span>
                        <input id="input_valore_orario_<?php echo $dipendente['id']; ?>" class="start_hidden_input form-control form-control-sm" type="text" value="<?php echo $dipendente['valore_orario']; ?>"></td>
                    <td class="monte_ore"><span class="start_span" id="span_monte_ore_<?php echo $dipendente['id']; ?>"><?php echo $dipendente['monte_ore']; ?></span>
                        <input id="input_monte_ore_<?php echo $dipendente['id']; ?>" class="start_hidden_input form-control form-control-sm" type="text" value="<?php echo $dipendente['monte_ore']; ?>"></td>
                    <td id="contenitore_ruoli">

                            <?php foreach ($dipendente['ruoli'] as $ruolo) {

                                if($ruolo['ruolo']!=null) {
                                    echo ' <div class="row">
                                            <div class="col-md-8">' . $ruolo['ruolo'] . '</div>
                                            <div class="col-md-4" onclick=deleteruoloclick(' . $ruolo['id'] . ')><span class="oi oi-puzzle-piece red delete_ruolo" title="cancella ruolo" aria-hidden="true"></span></div>
                                      </div>';
                                }
                            }?>
                            <div ><select class="start_hidden_input select_nuovo_ruolo" id="nuovo_ruolo_<?php echo $dipendente['id']; ?>" onchange=''>
                                    <option value='0'>aggiungi un ruolo</option>
                                    <?php foreach ($this->ruoli as $ruolo){

                                        echo '<option value='.$ruolo['id'].'>'.$ruolo['ruolo'].'</option>';
                                    }

                                    ?>


                                </select></div>
                    </td>
                    <td class="bottoni">
                        <button><span class="modify_button oi oi-pencil" title="modifica utente" aria-hidden="true" id="<?php echo $dipendente['id']; ?>"></span></button>
                        <button class="confirm_button" id="confirm_button_<?php echo $dipendente['id']; ?>"><span class="oi oi-thumb-up" title="conferma modifiche" aria-hidden="true" id="confirm_span_<?php echo $dipendente['id']; ?>"></span></button>
                        <button onclick="deleteclick(<?php echo $dipendente['id']; ?>)"><span class="oi oi-delete red" title="cancella utente" aria-hidden="true"></span></button>
                        <button><span class="add_ruolo oi oi-puzzle-piece green" title="aggiungi ruolo" aria-hidden="true" id="addruolo_<?php echo $dipendente['id']; ?>"></span></button></td>
                </tr>
                <?php
            }
        ?>
        </tbody>
    </table>
</div>
<div class="form-group form-group-sm">
    <div  class="row insertbox"><div class="col-xs-12 col-md-12"><b>INSERISCI UN NUOVO DIPENDENTE</b></div></div>

    <div  class="row insertbox">

        <div class="col-xs-4 col-md-4 text-info"><h5>nome:</h5> <input class="form-control form-control-sm" type="text" id="nome"></div>
        <div class="col-xs-4 col-md-4 text-info"><h5>cognome:</h5> <input class="form-control form-control-sm" type="text" id="cognome"></div>
        <div class="col-xs-4 col-md-2 text-info"><h5>valore orario:</h5> <input class="form-control form-control-sm" type="text" id="valore_orario"></div>
        <div class="col-xs-4 col-md-2 text-info"><h5>monte ore:</h5> <input class="form-control form-control-sm" type="text" id="monte_ore"></div>
    </div>

    <div  class="row insertbox">
        <div class="col-xs-0 col-md-4"></div>
        <div class="col-xs-12 col-md-4 text-center"><button  class="form-control btn btn-outline-secondary btn-sm" id="insertnewdipendente" value="conferma" onclick="insertclick()" type="button">CONFERMA</button>
        </div><div class="col-xs-0 col-md-4"></div>
    </div>
</div>

<script type="text/javascript">

    var change_operation=null;
    function insertclick(){

        var valore_orario=jQuery("#valore_orario").val().replace(",",".");
        var monte_ore=jQuery("#monte_ore").val().replace(",",".");
        jQuery.ajax({
            method: "POST",
            cache: false,
            url: 'index.php?option=com_ggpm&task=dipendenti.insert&nome='+jQuery("#nome").val()+'&cognome='+jQuery("#cognome").val()+'&valore_orario='+valore_orario+'&monte_ore='+monte_ore

        }).done(function() {

            alert("inserimento riuscito");
            location.reload();


        });
    }

    //questa funzione intercetta l'evento click sui pulsanti di modifica, e trasforma i campi testo della riga in campi input. Prima però riporta tutti a testo
    jQuery(".modify_button").click(function (event) {

        jQuery('.start_hidden_input').hide()
        jQuery('.start_span').show()
        var str=jQuery(event.target).attr('id').toString();
        jQuery("#input_cognome_"+str).toggle();
        jQuery("#input_nome_"+str).toggle();
        jQuery("#input_valore_orario_"+str).toggle();
        jQuery("#input_monte_ore_"+str).toggle();
        jQuery("#confirm_button_"+str).toggle();
        jQuery("#span_cognome_"+str).toggle();
        jQuery("#span_nome_"+str).toggle();
        jQuery("#span_valore_orario_"+str).toggle();
        jQuery("#span_monte_ore_"+str).toggle();
        change_operation='modify_anagrafica';
    });

    jQuery(".add_ruolo").click(function (event) {

        jQuery(".select_nuovo_ruolo").hide();
        var id=jQuery(event.target).attr('id').toString();
        id=id.substr(9,id.length-9);
        jQuery("#nuovo_ruolo_"+id).toggle();
        jQuery("#confirm_button_"+id).toggle();
        change_operation='add_ruolo';

    });

    jQuery(".select_nuovo_ruolo").change(function(event){




    });

    //QUESTA E' LA PROCEDURA DI INVIO DEI DATI MODIFICATI
    jQuery(".oi-thumb-up").click(function (event) {

        var str = jQuery(event.target).attr('id').toString();
        console.log(str.substr(13, str.length - 13));
        var id = str.substr(13, str.length - 13);

        if(change_operation=='modify_anagrafica') {

            var cognome = jQuery('#input_cognome_' + id).val().toString();
            var nome = jQuery('#input_nome_' + id).val().toString();
            var valore_orario = jQuery('#input_valore_orario_' + id).val().toString();
            var monte_ore = jQuery('#input_monte_ore_' + id).val().toString();
            jQuery.ajax({
                method: "POST",
                cache: false,
                url: 'index.php?option=com_ggpm&task=dipendenti.modify&id=' + id + '&cognome=' + cognome + '&nome=' + nome + '&valore_orario=' + valore_orario+ '&monte_ore=' + monte_ore

            }).done(function () {

                alert("modifiche riuscite");
                location.reload();


            });

        }

        if(change_operation=='add_ruolo'){

            var ruolo_id=jQuery("#nuovo_ruolo_"+id).val().toString();// PRENDE IL VALUE DELLA OPTION, QUINDI ID DEL RUOLO
            console.log(ruolo_id);

            jQuery.ajax({
                method: "POST",
                cache: false,
                url: 'index.php?option=com_ggpm&task=ruoli.insert_map&id_dipendente=' + id.toString()+'&id='+ruolo_id.toString()

            }).done(function() {
                alert("aggiunto ruolo");
                location.reload();
            }).fail(function($xhr) {
                var data = $xhr.responseJSON;
                console.log(data);
            });

        }

    });


    function deleteclick(id) {

        if(confirm('attenzione, stai cancellando un utente')==true) {
            jQuery.ajax({
                method: "POST",
                cache: false,
                url: 'index.php?option=com_ggpm&task=dipendenti.delete&id=' + id.toString()

            }).done(function () {

                alert("cancellazione riuscita");
                location.reload();


            });
        }
    }

    function deleteruoloclick(id) {

        if(confirm('attenzione, stai cancellando il ruolo per un utente')==true) {
            jQuery.ajax({
                method: "POST",
                cache: false,
                url: 'index.php?option=com_ggpm&task=ruoli.delete_map&id=' + id.toString()

            }).done(function () {

                alert("cancellazione riuscita");
                location.reload();


            });
        }

    }
</script>
</html>
