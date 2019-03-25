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
    <h2>GESTIONE PIANI FORMATIVI - FASI</h2>
    <table class="table table-striped table-bordered ">
        <thead>
        <tr>
            <th>DESCRIZIONE</th>
            <th></th>
        </tr>
        </thead>

        <tbody>

        <?php
        foreach ($this->fasi as $fase) {

            ?>
                <tr>
                    <td class="fase"><span class="start_span" id="span_fase_<?php echo $fase['id']; ?>"><?php echo $fase['descrizione']; ?></span>
                        <input id="input_fase_<?php echo $fase['id']; ?>" class="start_hidden_input form-control form-control-sm" type="text" value="<?php echo $fase['descrizione']; ?>"></td>
                    <td class="bottoni">
                        <button><span class="modify_button oi oi-pencil" title="modifica fase" aria-hidden="true" id="<?php echo $fase['id']; ?>"></span></button>
                        <button class="confirm_button" id="confirm_button_<?php echo $fase['id']; ?>"><span class="oi oi-thumb-up" title="conferma modifiche" aria-hidden="true" id="confirm_span_<?php echo $fase['id']; ?>"></span></button>
                        <button onclick="deleteclick(<?php echo $fase['id']; ?>)"><span class="oi oi-delete red" title="cancella fase" aria-hidden="true"></span></button>
                    </td>
                </tr>
                <?php
            }
        ?>
        </tbody>
    </table>
</div>
<div class="form-group form-group-sm">
    <div  class="row insertbox"><div class="col-xs-12 col-md-12"><b>INSERISCI UNA NUOVA FASE </b></div></div>

    <div  class="row insertbox">
        <div class="col-xs-4 col-md-4 text-info"></div>
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
            url: 'index.php?option=com_ggpm&task=fasi.insert&descrizione='+jQuery("#descrizione").val()

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
        jQuery("#input_fase_"+str).toggle();

        jQuery("#confirm_button_"+str).toggle();
        jQuery("#span_fase_"+str).toggle();


    });



    //QUESTA E' LA PROCEDURA DI INVIO DEI DATI MODIFICATI
    jQuery(".oi-thumb-up").click(function (event) {

        var str = jQuery(event.target).attr('id').toString();
        console.log(str.substr(13, str.length - 13));
        var id = str.substr(13, str.length - 13);
        var descrizione = jQuery('#input_fase_' + id).val().toString();
        jQuery.ajax({
                method: "POST",
                cache: false,
                url: 'index.php?option=com_ggpm&task=fasi.modify&id=' + id + '&descrizione=' + descrizione

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
                url: 'index.php?option=com_ggpm&task=fasi.delete&id=' + id.toString()

            }).done(function () {

                alert("cancellazione riuscita");
                location.reload();


            });
        }
    }

</script>
</html>
