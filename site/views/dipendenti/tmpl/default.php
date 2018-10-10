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
                    <td class="cognome"><?php echo $dipendente['cognome']; ?></td>
                    <td class="nome"><?php echo $dipendente['nome']; ?></td>
                    <td class="valore_orario"><?php echo $dipendente['valore_orario']; ?></td>
                    <td class="bottoni"><a href="" onmouseover="" onclick="modifyclick()"><span class="oi oi-pencil" title="icon name" aria-hidden="true"></span></a><span class="oi oi-delete red" title="icon name" aria-hidden="true" onclick="delclick()"></span></td>
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

        <div class="col-xs-4 col-md-4 text-info"><h3>nome:</h3> <input class="form-control form-control-sm" type="text" id="nome"></div>
        <div class="col-xs-4 col-md-4 text-info"><h3>cognome:</h3> <input class="form-control form-control-sm" type="text" id="cognome"></div>
        <div class="col-xs-4 col-md-2 text-info"><h3>valore orario:</h3> <input class="form-control form-control-sm" type="text" id="valore_orario"></div>
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

    function modifyclick() {
        alert ('ciao!');
    }

</script>
</html>
