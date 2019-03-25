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
    <h2>GESTIONE PIANI FORMATIVI - VOCI DI COSTO</h2>
    <table class="table table-striped table-bordered ">
        <thead>
        <tr>
            <th>FASE</th>
            <th>DESCRIZIONE</th>
            <th></th>
        </tr>
        </thead>

        <tbody>

        <?php
        foreach ($this->voci_costo as $voci_costo) {

            ?>
                <tr>
                    <td class="fase"><span class="start_span" id="fase_voce_costo_<?php echo $voci_costo['id']; ?>"><?php echo $voci_costo['fase']; ?></span>

                        <select class="start_hidden_input select_nuovo_ruolo" id="select_voci_costo_<?php echo $voci_costo['id']; ?>" onchange=''>

                            <?php foreach ($this->fasi as $fase){

                                echo '<option value='.$fase['id'].'>'.$fase['descrizione'].'</option>';
                            }

                            ?>


                        </select>
                    </td>
                    <td class="fase"><span class="start_span" id="span_voci_costo_<?php echo $voci_costo['id']; ?>"><?php echo $voci_costo['descrizione']; ?></span>
                        <input id="input_voci_costo_<?php echo $voci_costo['id']; ?>" class="start_hidden_input form-control form-control-sm" type="text" value="<?php echo $voci_costo['descrizione']; ?>"></td>
                    <td class="bottoni">
                        <button><span class="modify_button oi oi-pencil" title="modifica voce di costo" aria-hidden="true" id="<?php echo $voci_costo['id']; ?>"></span></button>
                        <button class="confirm_button" id="confirm_button_<?php echo $voci_costo['id']; ?>"><span class="oi oi-thumb-up" title="conferma modifiche" aria-hidden="true" id="confirm_span_<?php echo $voci_costo['id']; ?>"></span></button>
                        <button onclick="deleteclick(<?php echo $voci_costo['id']; ?>)"><span class="oi oi-delete red" title="cancella voce di costo" aria-hidden="true"></span></button>
                    </td>
                </tr>
                <?php
            }
        ?>
        </tbody>
    </table>
</div>
<div class="form-group form-group-sm">
        <div  class="row insertbox"><div class="col-xs-12 col-md-12"><b>INSERISCI UNA NUOVA VOCE DI COSTO </b></div></div>

    <div  class="row insertbox">
        <div class="col-xs-4 col-md-4 text-info"><h5>fase di appartenenza</h5>
            <select id="nuova_fase" onchange=''>
                <option value="0">INSERISCI LA FASE</option>
                <?php foreach ($this->fasi as $fase){

                    echo '<option value='.$fase['id'].'>'.$fase['descrizione'].'</option>';
                }

                ?>


            </select>
        </div>
        <div class="col-xs-4 col-md-4 text-info"><h5>descrizione:</h5> <input class="form-control form-control-sm" type="text" id="descrizione"></div>
        <div class="col-xs-4 col-md-4 text-info"></div>
    </div>

    <div  class="row insertbox">
        <div class="col-xs-0 col-md-4"></div>
        <div class="col-xs-12 col-md-4 text-center"><button  class="form-control btn btn-outline-secondary btn-sm" id="insertnewdipendente" value="conferma" onclick="insertclick()" type="button">CONFERMA</button>
        </div><div class="col-xs-0 col-md-4"></div>
    </div>
</div>

<script type="text/javascript">

      function insertclick(){

        jQuery.ajax({
            method: "POST",
            cache: false,
            url: 'index.php?option=com_ggpm&task=vocicosto.insert&descrizione='+jQuery("#descrizione").val()+'&id_fase='+jQuery("#nuova_fase").val()

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
        jQuery("#select_voci_costo_"+str).toggle();
        jQuery("#input_voci_costo_"+str).toggle();
        jQuery("#confirm_button_"+str).toggle();
        jQuery("#span_voci_costo_"+str).toggle();
        jQuery("#fase_voce_costo_"+str).toggle();

    });



    //QUESTA E' LA PROCEDURA DI INVIO DEI DATI MODIFICATI
    jQuery(".oi-thumb-up").click(function (event) {

        var str = jQuery(event.target).attr('id').toString();
        console.log(str.substr(13, str.length - 13));
        var id = str.substr(13, str.length - 13);
        var descrizione = jQuery('#input_voci_costo_' + id).val().toString();
        var id_fase=jQuery('#select_voci_costo_' + id).val().toString();
        jQuery.ajax({
                method: "POST",
                cache: false,
                url: 'index.php?option=com_ggpm&task=vocicosto.modify&id=' + id + '&descrizione=' + descrizione+ '&id_fase=' + id_fase

            }).done(function () {

                alert("modifiche riuscite");
                location.reload();


        });


    });


    function deleteclick(id) {

        if(confirm('attenzione, stai cancellando una fase')==true) {
            jQuery.ajax({
                method: "POST",
                cache: false,
                url: 'index.php?option=com_ggpm&task=vocicosto.delete&id=' + id.toString()

            }).done(function () {

                alert("cancellazione riuscita");
                location.reload();


            });
        }
    }

</script>
</html>
