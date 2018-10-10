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

    .nome{

        width: 35%;
    }

    .cognome{

        width: 35%;
    }

    .valore_orario{

        width: 10%;
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
    <h2>FOR - GESTIONE PIANI FORMATIVI - DIPENDENTI</h2>
    <table class="table table-striped table-bordered ">
        <thead>
        <tr>
            <th>COGNOME</th>
            <th>NOME</th>
            <th>VALORE ORARIO</th>
            <th></th>
        </tr>
        </thead>

        <tbody>

        <?php  foreach ($this->dipendenti as $dipendente) {
                ?>
                <tr>
                    <td class="cognome"><span class="start_span" id="span_cognome_<?php echo $dipendente['id']; ?>"><?php echo $dipendente['cognome']; ?></span>
                        <input id="input_cognome_<?php echo $dipendente['id']; ?>" class="start_hidden_input form-control form-control-sm" type="text" value="<?php echo $dipendente['cognome']; ?>"></td>
                    <td class="nome"><span class="start_span" id="span_nome_<?php echo $dipendente['id']; ?>"><?php echo $dipendente['nome']; ?></span>
                        <input id="input_nome_<?php echo $dipendente['id']; ?>" class="start_hidden_input form-control form-control-sm" type="text" value="<?php echo $dipendente['nome']; ?>"></td>
                    <td class="valore_orario"><span class="start_span" id="span_valore_orario_<?php echo $dipendente['id']; ?>"><?php echo $dipendente['valore_orario']; ?></span>
                        <input id="input_valore_orario_<?php echo $dipendente['id']; ?>" class="start_hidden_input form-control form-control-sm" type="text" value="<?php echo $dipendente['valore_orario']; ?>"></td>
                    <td class="bottoni">
                        <button><span class="modify_button oi oi-pencil" title="icon name" aria-hidden="true" id="<?php echo $dipendente['id']; ?>"></span></button>
                        <button class="confirm_button" id="confirm_button_<?php echo $dipendente['id']; ?>"><span class="oi oi-thumb-up" title="icon name" aria-hidden="true" id="confirm_span_<?php echo $dipendente['id']; ?>"></span></button>
                        <button onclick="deleteclick(<?php echo $dipendente['id']; ?>)"><span class="oi oi-delete red" title="icon name" aria-hidden="true"></span></button></td>
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
    </div>

    <div  class="row insertbox">
        <div class="col-xs-0 col-md-4"></div>
        <div class="col-xs-12 col-md-4 text-center"><button  class="form-control btn btn-outline-secondary btn-sm" id="insertnewdipendente" value="conferma" onclick="insertclick()" type="button">CONFERMA</button>
        </div><div class="col-xs-0 col-md-4"></div>
    </div>
</div>

<script type="text/javascript">
    function insertclick(){

        var valore_orario=jQuery("#valore_orario").val().replace(",",".");
        jQuery.ajax({
            method: "POST",
            cache: false,
            url: 'index.php?option=com_ggpm&task=dipendenti.insert&nome='+jQuery("#nome").val()+'&cognome='+jQuery("#cognome").val()+'&valore_orario='+valore_orario

        }).done(function() {

            alert("inserimento riuscito");
            location.reload();


        });
    }

    jQuery(".modify_button").click(function (event) {

        jQuery('.start_hidden_input').hide()
        jQuery('.start_span').show()
        var str=jQuery(event.target).attr('id').toString();
        jQuery("#input_cognome_"+str).toggle();
        jQuery("#input_nome_"+str).toggle();
        jQuery("#input_valore_orario_"+str).toggle();
        jQuery("#confirm_button_"+str).toggle();
        jQuery("#span_cognome_"+str).toggle();
        jQuery("#span_nome_"+str).toggle();
        jQuery("#span_valore_orario_"+str).toggle();
    });

    jQuery(".oi-thumb-up").click(function (event) {


        var str=jQuery(event.target).attr('id').toString();
        console.log(str);
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
</script>
</html>
