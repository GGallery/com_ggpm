<?php
defined('_JEXEC') or die;
?>

<html>
<head>
<style>
    .insertbox{

        background-color: #d9edf7;
        margin-left: 3px;
    }



    .bottoni{

        width: 20%;
    }

    .red{

        color:red;
    }

    .start_hidden_input,.confirm_button{

        display: none;
    }


</style>
</head>


<div class="table-responsive">
    <h2>GESTIONE PIANI FORMATIVI - RUOLI</h2>
    <table class="table table-striped table-bordered ">
        <thead>
        <tr>
            <th>RUOLO</th>

            <th></th>
        </tr>
        </thead>

        <tbody>

        <?php  foreach ($this->ruoli as $ruolo) {
                ?>
                <tr>
                    <td class="ruolo "><span class="start_span" id="span_ruolo_<?php echo $ruolo['id']; ?>"><?php echo $ruolo['ruolo']; ?></span>
                        <input id="input_ruolo_<?php echo $ruolo['id']; ?>" class="start_hidden_input form-control form-control-sm " type="text" value="<?php echo $ruolo['ruolo']; ?>"></td>
                    <td class="bottoni">
                        <button><span class="modify_button oi oi-pencil" title="icon name" aria-hidden="true" id="<?php echo $ruolo['id']; ?>"></span></button>
                        <button class="confirm_button" id="confirm_button_<?php echo $ruolo['id']; ?>"><span class="oi oi-thumb-up" title="icon name" aria-hidden="true" id="confirm_span_<?php echo $ruolo['id']; ?>"></span></button>
                        <button onclick="deleteclick(<?php echo $ruolo['id']; ?>)"><span class="oi oi-delete red" title="icon name" aria-hidden="true"></span></button></td>
                </tr>
                <?php
            }
        ?>
        </tbody>
    </table>
</div>
<div class="form-group form-group-sm">
    <div  class="row insertbox"><div class="col-xs-12 col-md-12"><b>INSERISCI UN NUOVO RUOLO</b></div></div>

    <div  class="row insertbox">
        <div class="col-xs-4 col-md-4"></div>
        <div class="col-xs-4 col-md-4 text-info"><h5>ruolo:</h5> <input class="form-control form-control-sm" type="text" id="ruolo"></div>
        <div class="col-xs-4 col-md-4"></div>
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
            url: 'index.php?option=com_ggpm&task=ruoli.insert&ruolo='+jQuery("#ruolo").val()

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
        jQuery("#input_ruolo_"+str).toggle();
       ;
        jQuery("#confirm_button_"+str).toggle();
        jQuery("#span_ruolo_"+str).toggle();

    });

    //QUESTA E' LA PROCEDURA DI INVIO DEI DATI MODIFICATI
    jQuery(".oi-thumb-up").click(function (event) {


        var str=jQuery(event.target).attr('id').toString();
        console.log(str.substr(13,str.length-13));
        var id=str.substr(13,str.length-13);
        var ruolo=jQuery('#input_ruolo_'+id).val().toString();


        jQuery.ajax({
            method: "POST",
            cache: false,
            url: 'index.php?option=com_ggpm&task=ruoli.modify&id='+id+'&ruolo='+ruolo

        }).done(function() {

            alert("modifiche riuscite");
            location.reload();


        });


    });


    function deleteclick(id) {

        if(confirm('attenzione, stai cancellando un ruolo')==true) {
            jQuery.ajax({
                method: "POST",
                cache: false,
                url: 'index.php?option=com_ggpm&task=ruoli.delete&id=' + id.toString()

            }).done(function () {

                alert("cancellazione riuscita");
                location.reload();


            });
        }
    }
</script>
</html>
