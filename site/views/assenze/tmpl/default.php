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

    #contenitore_ruoli{

        width: 20%;

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
    <h2>FOR - GESTIONE PIANI FORMATIVI - ASSENZE DIPENDENTI</h2>


        <?php
        foreach ($this->assenze as $dipendente) {

            ?>
            <table class="table table-striped table-bordered ">
                <thead>
                <tr>
                    <th>COGNOME</th>
                    <th>NOME</th>
                    <th>VALORE ORARIO</th>

                </tr>
                </thead>

                <tbody>
                <tr>
                    <td class="cognome"><span class="start_span" id="span_cognome_<?php echo $dipendente['id']; ?>"><a href="index.php/assenze?id_dipendente=<?php echo $dipendente['id']; ?>"><?php echo $dipendente['cognome']; ?></a></span></td>
                    <td class="nome"><span class="start_span" id="span_nome_<?php echo $dipendente['id']; ?>"><?php echo $dipendente['nome']; ?></span></td>
                    <td class="valore_orario"><span class="start_span" id="span_valore_orario_<?php echo $dipendente['id']; ?>"><?php echo $dipendente['valore_orario']; ?></span></td>
                </tr>
                <tr>
                    <table class="table table-striped table-bordered ">
                        <thead>
                        <tr>
                            <th>DATE </th>
                            <th>CAUSALE</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($dipendente['assenze'] as $assenza){?>
                            <tr>
                                <td class="data"><span class="start_span" id="span_cognome"><?php echo Date('d/m/Y',strtotime($assenza['data_inizio'])).' - '.Date('d/m/Y',strtotime($assenza['data_fine']));?></span></td>
                                <td class="causale"><span class="start_span" id="span_nome"><?php echo $assenza['causale']; ?></span>
                                    <button onclick="deleteclick(<?php echo $assenza['id']; ?>)"><span class="oi oi-delete red" title="cancella assenza" aria-hidden="true"></span></button></td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </tr>
                <?php

            }
        ?>
        </tbody>
    </table>

</div>


<div class="form-group form-group-sm">
    <?php if($this->_filterparam->id_dipendente!=null){?>
    <div  class="row insertbox"><div class="col-xs-12 col-md-12"><b>INSERISCI UNA NUOVA ASSENZA</b></div></div>

    <div  class="row insertbox">

        <div class="col-xs-4 col-md-4 text-info"><h5>data inizio:</h5>

          <input type='date' class="form-control" id="data_inizio"/>

        </div>
        <div class="col-xs-4 col-md-4 text-info"><h5>data fine:</h5>

            <input type='date' class="form-control" id="data_fine"/>

        </div>
        <div class="col-xs-4 col-md-4 text-info"><h5>causale:</h5> <input class="form-control form-control-sm" type="text" id="causale"></div>

    </div>

    <div  class="row insertbox">
        <div class="col-xs-0 col-md-4"></div>
        <div class="col-xs-12 col-md-4 text-center"><button  class="form-control btn btn-outline-secondary btn-sm" id="insertnewassenza" value="conferma" onclick="insertclick()" type="button">CONFERMA</button>
        </div><div class="col-xs-0 col-md-4"></div>
    </div>
    <?php } ?>
</div>

<script type="text/javascript">


    function insertclick(){

        var id_dipendente=<?php echo $this->_filterparam->id_dipendente;?>

        jQuery.ajax({
            method: "POST",
            cache: false,
            url: 'index.php?option=com_ggpm&task=assenze.insert&id_dipendente='+id_dipendente+'&data_inizio='+jQuery("#data_inizio").val()+'&data_fine='+jQuery("#data_fine").val()+'&causale='+jQuery("#causale").val()

        }).done(function() {

            alert("inserimento riuscito");
            location.reload();


        });
    }


    jQuery("#data_inizio").change(function () {

        console.log(jQuery("#data-inizio").val());
        jQuery("#data_fine").val(jQuery("#data_inizio").val());
    });


    function deleteclick(id) {

        if(confirm('attenzione, stai cancellando una assenza')==true) {
            jQuery.ajax({
                method: "POST",
                cache: false,
                url: 'index.php?option=com_ggpm&task=assenze.delete&id=' + id.toString()

            }).done(function () {

                alert("cancellazione riuscita");
                location.reload();


            });
        }
    }


</script>
</html>
