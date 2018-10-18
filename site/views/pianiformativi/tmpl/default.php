<?php
defined('_JEXEC') or die;
?>


<head>
<style>
    .insertbox{

        background-color: #d9edf7;

    }

    #calendario td{

    font-size: xx-small;
    padding: 0px;
    }
    .start_hidden_input,.confirm_button, .confirm_budget_button{

        display: none;
    }

    .red{

        color:red;
    }

    .descrizione_piano_attivo{

        padding-left: 12px;
        font-size: larger;

    }

    .background-green{

        background-color: #67b168;
    }

    .background-cyan{

        background-color: #5bc0de;
    }

</style>
</head>


<div class="container">
    <div class="row justify-content-between background-green">

        <div class="col-10">

            <table class="table table-bordered">

                <thead>
                <tr class="d-flex"><th class="col-12">GESTIONE PIANI FORMATIVI</th></tr>
                <tr class="d-flex"><th class="col-3">descrizione</th><th class="col-3">data inizio</th><th class="col-3">data fine</th><th class="col-3"></th></tr>
                </thead>
                <tbody>
                <?php foreach ($this->piani_formativi as $pianoformativo){?>
                <tr class="d-flex">
                    <td class="col-3"><span id="span_piano_descrizione_<?php echo $pianoformativo['id']; ?>"><?php echo $pianoformativo['descrizione']?></span>
                        <input id="input_piano_descrizione_<?php echo $pianoformativo['id']; ?>" class="start_hidden_input form-control form-control-sm" type="text" value="<?php echo $pianoformativo['descrizione']; ?>"></td>
                    <td class="col-3"><span id="span_piano_data_inizio_<?php echo $pianoformativo['id']; ?>"><?php echo Date('d/m/Y',strtotime($pianoformativo['data_inizio']))?></span>
                        <input id="input_piano_data_inizio_<?php echo $pianoformativo['id']; ?>" class="start_hidden_input form-control form-control-sm" type="date" value="<?php echo $pianoformativo['data_inizio']; ?>"></td>
                    <td class="col-3"><span id="span_piano_data_fine_<?php echo $pianoformativo['id']; ?>"><?php echo Date('d/m/Y',strtotime($pianoformativo['data_fine']))?></span>
                        <input id="input_piano_data_fine_<?php echo $pianoformativo['id']; ?>" class="start_hidden_input form-control form-control-sm" type="date" value="<?php echo $pianoformativo['data_fine']; ?>"></td>

                <td class="col-3">
                    <button><span class="modify_button oi oi-pencil" title="modifica piano formativo" aria-hidden="true" id="<?php echo $pianoformativo['id']; ?>"></span></button>
                    <button class="confirm_button" id="confirm_button_<?php echo $pianoformativo['id']; ?>">
                        <span class="oi oi-thumb-up" title="conferma modifiche" aria-hidden="true" id="confirm_span_<?php echo $pianoformativo['id']; ?>"></span></button>
                    <button onclick="deletepianoclick(<?php echo $pianoformativo['id']; ?>)"><span class="oi oi-delete red" title="cancella piano formativo" aria-hidden="true"></span></button>

                </td>
                <?php }?>
                </tr>
                </tbody>
            </table>
            <table class="table table-bordered">

                <thead>
                <tr class="d-flex"><th class="col-12">AGGIUNGI UN NUOVO PIANO FORMATIVO</th></tr>
                <tr class="d-flex"><th class="col-4">descrizione</th><th class="col-4">data inizio</th><th class="col-4">data fine</th></tr>
                </thead>
                <tbody>

                <tr class="d-flex insertbox">
                    <td class="col-4"><input class="form-control form-control-sm" type="text" id="piano_descrizione"></td>
                    <td class="col-4"><input class="form-control form-control-sm" type="date" id="piano_data_inizio"></td>
                    <td class="col-4"><input class="form-control form-control-sm" type="date" id="piano_data_fine"></td>
                </tr>
                <tr class="d-flex insertbox">
                    <td class="col-12"> <button  class="form-control btn btn-outline-secondary btn-sm" id="insertnewpiano" value="conferma" onclick="insertpianoclick()" type="button">CONFERMA</button></td>

                </tr>

                </tbody>
            </table>
        </div>

    </div>
    <div class="row background-cyan" style="margin-top: 20px;">
        <table class="table table-bordered">

            <thead>
            <tr class="d-flex"><th class="col-12">SCEGLI IL PIANO FORMATIVO DA PROGETTARE</th></tr>
            </thead>
            <tbody>
            <tr class="d-flex insertbox">
                <td class="col-4">
                    <select id="piano_formativo">
                        <option value="0">scegli</option>
                        <?php foreach ($this->piani_formativi as $pianoformativo){
                        echo '<option value='.$pianoformativo['id'].'>'.$pianoformativo['descrizione'].'</option>';
                        }?>
                    </select>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="row justify-content-between">

            <div class="col-6">
                <table class="table table-bordered table-striped">
                    <thead><th class="col-12 d-flex">GESTIONE BUDGET <span class="red descrizione_piano_attivo"><?php echo $this->descrizione_piano_formativo_attivo ?></span></th></thead>
                    <tbody>
                    <?php if($this->budget!=null){

                        foreach ($this->budget as $item){ $this->totale=$this->totale+$item['budget'];?>
                        <tr class="d-flex"><td class="col-6"><?php echo $item['voce_costo']?></td>
                            <td class="col-2">
                                <span class="start_span" id="span_budget_budget_<?php echo $item['id']?>"><?php echo $item['budget']?></span>
                                    <input id="input_budget_budget_<?php echo $item['id']?>" class="start_hidden_input form-control form-control-sm" type="text" value="<?php echo $item['budget']?>"></td>
                                        <td class="col-4">
                                            <button><span class="modify_budget_button oi oi-pencil" title="modifica" aria-hidden="true" id="<?php echo $item['id']; ?>"></span></button>
                                            <button class="confirm_budget_button" id="confirm_button_<?php echo $item['id']; ?>">
                                                <span class="oi oi-thumb-up" title="conferma modifiche" aria-hidden="true" id="budget_confirm_span_<?php echo $item['id']; ?>"></span></button>
                                            <button onclick="deletebudgetclick(<?php echo $item['id']; ?>)"><span class="oi oi-delete red" title="cancella piano formativo" aria-hidden="true"></span></button>

                                        </td>

                        </tr>
                        <?php }
                    } ?>
                    <tr class="d-flex"><td class="col-6"> TOTALE BUDGET </td><td class="col-6"><?php echo  $this->totale?></td></tr>
                    <tr class="d-flex insertbox">
                        <td class="col-8">
                            <select class="form-control form-control-sm" type="text" id="budget_voce_costo">
                                <?php foreach ($this->voci_costo as $voce_costo){

                                    echo '<option value='.$voce_costo['id'].'>'.$voce_costo['descrizione'].'</option>';
                                }

                                ?>

                            </select>
                        </td>
                        <td class="col-4"><input class="form-control form-control-sm" type="text" id="budget_budget"></td>
                    </tr>
                    <tr class="d-flex insertbox">
                        <td class="col-12"> <button  class="form-control btn btn-outline-secondary btn-sm" id="insertnewbudget" value="conferma" onclick="insertbudgetclick()" type="button">CONFERMA</button></td>

                    </tr>

                    </tbody>
                </table>
            </div>
            <div class="col-6">
                 <div class="row ">
                     <div class="col-6 border border-primary rounded">CRUSCOTTO DIPENDENTI</div>
                     <div class="row">
                     <div class="col-6">dipendente</div><div class="col-3">ore residue</div><div class="col-3">budget residuo</div>
                     <div class="col-6">topolino</div><div class="col-3">1020</div><div class="col-3">20.0000</div>
                     <div class="col-6">pippo</div><div class="col-3">1040</div><div class="col-3">30.000</div>
                     <div class="col-6">petruzzella</div><div class="col-3">124</div><div class="col-3">2.050</div>
                     </div>
                 </div>
             </div>
         </div>
    </div>
   <div class="row">
        OTTOBRE
        <table class="table table-bordered" id="calendario">
            <thead>
                   <tr><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td></tr>
            </thead>
            <tbody>
            <tr><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td></tr>


            </tbody>
        </table>

    </div>

</div>


<script type="text/javascript">

    var piano_formativo_attivo=<?php echo $this->id_piano_formativo_attivo; ?>

    function insertpianoclick(){

        var piano_descrizione=jQuery("#piano_descrizione").val();
        var data_inizio=jQuery("#piano_data_inizio").val();
        var data_fine=jQuery("#piano_data_fine").val();

        jQuery.ajax({
            method: "POST",
            cache: false,
            url: 'index.php?option=com_ggpm&task=pianiformativi.insert&descrizione='+piano_descrizione+'&data_inizio='+data_inizio+'&data_fine='+data_fine

        }).done(function() {

            alert("inserimento riuscito");
            location.reload();


        });
    }

    function insertbudgetclick(){

        var budget_voce_costo=jQuery("#budget_voce_costo").val();
        var budget_budget=jQuery("#budget_budget").val();
        var piano_formativo=piano_formativo_attivo;

        jQuery.ajax({
            method: "POST",
            cache: false,
            url: 'index.php?option=com_ggpm&task=budget.insert&id_voce_costo='+budget_voce_costo+'&budget='+budget_budget+'&id_piano_formativo='+piano_formativo

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
        jQuery("#input_piano_descrizione_"+str).toggle();
        jQuery("#input_piano_data_inizio_"+str).toggle();
        jQuery("#input_piano_data_fine_"+str).toggle();

        jQuery("#confirm_button_"+str).toggle();
        jQuery("#span_piano_descrizione_"+str).toggle();
        jQuery("#span_piano_data_inizio_"+str).toggle();
        jQuery("#span_piano_data_fine_"+str).toggle();

    });

    jQuery(".modify_budget_button").click(function (event) {

        jQuery('.start_hidden_input').hide()
        jQuery('.start_span').show()
        var str=jQuery(event.target).attr('id').toString();
        jQuery("#input_budget_budget_"+str).toggle();
        jQuery("#confirm_button_"+str).toggle();
        jQuery("#span_budget_budget_"+str).toggle();

    });


    jQuery(".oi-thumb-up").click(function (event) {

        var str = jQuery(event.target).attr('id').toString();

        if(str.substr(0,3)=="con") {
            var id = str.substr(13, str.length - 13);
            var descrizione = jQuery('#input_piano_descrizione_' + id).val().toString();
            var data_inizio = jQuery('#input_piano_data_inizio_' + id).val().toString();
            var data_fine = jQuery('#input_piano_data_fine_' + id).val().toString();

            jQuery.ajax({
                method: "POST",
                cache: false,
                url: 'index.php?option=com_ggpm&task=pianiformativi.modify&id=' + id + '&descrizione=' + descrizione + '&data_inizio=' + data_inizio + '&data_fine=' + data_fine

            }).done(function () {

                alert("modifiche riuscite");
                location.reload();


            });

        }

        if(str.substr(0,3)=="bud") {

            var id = str.substr(20, str.length - 20);
            var budget = jQuery('#input_budget_budget_' + id).val().toString();


            jQuery.ajax({
                method: "POST",
                cache: false,
                url: 'index.php?option=com_ggpm&task=budget.modify&id=' + id + '&budget=' + budget

            }).done(function () {

                alert("modifiche riuscite");
                location.reload();


            });
        }
    });


    jQuery("#piano_formativo").change(function(event){

        var id=jQuery("#piano_formativo").val();
        window.open("?option=com_ggpm&view=pianiformativi&id_piano_formativo_attivo="+id.toString(),"_self")
    });

    function deletepianoclick(id) {

        if(confirm('attenzione, stai cancellando un piano formativo')==true) {
            jQuery.ajax({
                method: "POST",
                cache: false,
                url: 'index.php?option=com_ggpm&task=pianiformativi.delete&id=' + id.toString()

            }).done(function () {

                alert("cancellazione riuscita");
                location.reload();


            });
        }
    }

    function deletebudgetclick(id) {

        if(confirm('attenzione, stai cancellando un elemento di budget')==true) {
            jQuery.ajax({
                method: "POST",
                cache: false,
                url: 'index.php?option=com_ggpm&task=budget.delete&id=' + id.toString()

            }).done(function () {

                alert("cancellazione riuscita");
                location.reload();


            });
        }
    }

</script>
</html>
