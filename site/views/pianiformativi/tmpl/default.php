<?php
defined('_JEXEC') or die;
?>


<head>
    <style>
        .insertbox{

            background-color: #d9edf7;

        }

        .festivo{

            background-color:red;
        }

        #calendario td{

            font-size: xx-small;
            padding: 0px;
        }

        #tasks td{

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

<body>
<div class="container">
    <div class="row  background-green">

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
    <div class="row " style="margin-top: 20px; margin-bottom: 20px;">
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
    </div>
    <div class="row justify-content-between">
        <?php if($this->id_piano_formativo_attivo){?>
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
                    <tr  insertbox">
                        <td class="col-12"> <button  class="form-control btn btn-outline-secondary btn-sm" id="insertnewbudget" value="conferma" onclick="insertbudgetclick()" type="button">CONFERMA</button></td>

                    </tr>

                    </tbody>
                </table>
            </div>
        <?php }?>
        <div class="col-6">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr class="d-flex"><th class="col-12">CRUSCOTTO DIPENDENTI</th></tr>
                    <tr class="d-flex"><th class="col-3">dipendente</th><th class="col-2">ore impegnate</th><th class="col-2">ore ferie</th><th class="col-2">ore residue</th><th class="col-3">budget impegnato</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($this->cruscottodipendenti as $dipendente){
                    echo '<tr class="d-flex"><td class="col-3">'.$dipendente['cognome'].'</td><td class="col-2">'.$dipendente['ore_impegnate'].'</td><td class="col-2">'.$dipendente['ore_ferie'].'</td><td class="col-2">'.$dipendente['ore_residue'].'</td><td class="col-3">'.$dipendente['budget_impegnato'].' €</td></tr>';
                    }?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($this->id_piano_formativo_attivo){?>
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered">
                    <thead>
                    <tr class="d-flex"><th class="col-12"> INSERISCI NUOVA ATTIVITA'</th></tr>
                    </thead>
                    <tbody>
                    <tr class="d-flex">
                        <td class="col-4">descrizione
                            <input class="form-control form-control-sm" type="text" id="task_descrizione">
                        </td>
                        <td class="col-4">data inizio
                            <input class="form-control form-control-sm" type="date" id="task_data_inizio">
                        </td>
                        <td class="col-4">durata in giorni
                            <input class="form-control form-control-sm" type="text" id="task_durata">
                        </td>
                    </tr>
                    <tr class="d-flex">
                        <td class="col-4">
                            <span id="task_data_fine_calcolata">data fine calcolata</span>
                        </td>

                        <td class="col-4">ore
                            <input class="form-control form-control-sm" type="text" id="task_ore">
                        </td>
                        <td class="col-4"><br>
                            <select class="form-control form-control-sm" id="task_voce_costo">
                                <option>scegli una voce di costo</option>
                                <?php foreach ($this->budget as $item){

                                    echo '<option value='.$item['id'].'>'.$item['voce_costo'].'</option>';
                                }

                                ?>

                            </select>
                        </td>
                    </tr>
                    <tr class="d-flex">
                        <td class="col-4">
                            <select class="form-control form-control-sm" type="text" id="task_ruolo">
                                <option>scegli un ruolo</option>
                                <?php foreach ($this->ruoli as $item){

                                    echo '<option value='.$item['id'].'>'.$item['ruolo'].'</option>';
                                }

                                ?>

                            </select>
                        </td>
                        <td class="col-4">
                            <select class="form-control form-control-sm" type="text" id="task_dipendente">
                                <option>scegli un dipendente</option>
                            </select>
                        </td>
                        <td class="col-4">
                            <select class="form-control form-control-sm" type="text" id="task_task_propedeutico">
                                <option>scegli un task propedeutico</option>
                                <?php if($this->task[0]!=null) {
                                    foreach ($this->task[0] as $item) {

                                        echo '<option value=' . $item['id'] . '>' . $item['descrizione'] . '</option>';
                                    }
                                }
                                ?>

                            </select>
                        </td>
                    </tr>
                    <tr class="d-flex">
                        <td class="col-4">valore orario
                            <input class="form-control form-control-sm" type="text" id="task_valore_orario">
                        </td>
                        <td class="col-8"> <button  class="form-control btn btn-outline-secondary btn-sm" id="insertnewtask" value="conferma" onclick="inserttaskclick()" type="button">CONFERMA</button></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php }?>

    <div class="row">
        <div class="col-2" style="padding-right: 0px;">
            <table class="table table-bordered" id="tasks">
                <thead>
                <tr class="d-flex"><th class="col-12"> task</th></tr>

                <tr class="d-flex"><th class="col-12">&nbsp</th></tr>
                <tr class="d-flex"><td style="height: 15px;">&nbsp</td></tr>
                </thead>
                <tbody>
                <?php //COLONNA DEI TASK
                if(isset($this->task[0])) {
                    foreach ($this->task[0] as $item) {
                        echo '<tr class=\"d-flex\"><td style="height: 15px;">' . $item['descrizione'] . '-'.$item['cognome'].'-'.$item['task_budget'].' €</td></tr>';
                    }
                }?>
                </tbody>
            </table>

        </div>
        <div class="col-10 table-responsive" style="padding-left: 0px; padding-left: 0px;">

            <table class="table table-bordered" id="calendario">
                <thead>
                <tr class="d-flex"><th class="col-12"> CALENDARIO</th></tr>

                <tr class="d-flex">

                    <?php //RIGA DEI MESI
                    $dimensioni_pixel_giorno=20;
                    $totale_giorni_progetto=0;
                    if($this->calendario_piano_formativo) {
                        foreach ($this->calendario_piano_formativo[0] as $mese) {
                            $giorni_mese = $this->mesi[intval(date_format($mese, 'm'))][1];
                            $nome_mese = $this->mesi[intval(date_format($mese, 'm'))][0];
                            $totale_giorni_progetto = $totale_giorni_progetto + $giorni_mese; ?>
                            <th style="width: <?php echo $giorni_mese * $dimensioni_pixel_giorno; ?>px;"><?php echo $nome_mese; ?></th>
                        <?php }
                    }?>
                <tr  class="d-flex">
                    <?php //RIGA DEI GIORNI
                    if($this->calendario_piano_formativo) {
                        $index=0;
                        foreach ($this->calendario_piano_formativo[0] as $mese_) {

                            $giorni_mese = $this->mesi[intval(date_format($mese_, 'm'))][1];
                            for ($giorno = 1; $giorno < $giorni_mese+1 ; $giorno++) {
                                if(isset($this->calendario_piano_formativo[1][$index]['f'])) {
                                       $festivo_feriale = ($this->calendario_piano_formativo[1][$index]['f'] == 1 ? "festivo" : "feriale");
                                }else{
                                    $festivo_feriale =null;
                                }
                                echo '<td style="width: ' . $dimensioni_pixel_giorno . 'px;" class="'.$festivo_feriale.'">' . $giorno . '</td>';
                            $index++;
                            }
                        }
                    }?>
                </tr>

                </thead>
                <tbody>
                <?php //RIGHE DEI TASK
                if(isset($this->task[3]))
                    $dayoftasks=$this->task[3];
                $tasknumber=0;
                if(isset($this->task[0])) {
                    foreach ($this->task[0] as $item) {

                        echo '<tr class="d-flex">';

                        for ($i = 1; $i < $totale_giorni_progetto+1; $i++) {
                            if (isset($dayoftasks[$tasknumber][$i])) {
                                $colore_del_giorno = $dayoftasks[$tasknumber][$i];
                            } else {
                                $colore_del_giorno = 'none';
                            }
                            echo '<td style="width: ' . $dimensioni_pixel_giorno . 'px; height: 15px; background-color:' . $colore_del_giorno . '">' . $i . '</td>';
                        }
                        echo '</tr>';
                        $tasknumber++;
                    }
                }?>
                </tbody>
            </table>

        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Date in conflitto per il dipendente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body container-fluid">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">




    var durata;
    var ruoli_dipendenti=[];

    ruoli_dipendenti=[

        <?php if($this->array_ruolo_dipendente!=null){

        foreach ($this->array_ruolo_dipendente as $item){
            echo "{ruolo:'".$item['ruolo_id']."',cognome:'".$item['cognome']."',id:'".$item['id']."',valore_orario:'".$item['valore_orario']."'},";

        }
    }
        ?>
    ]
    // console.log(ruoli_dipendenti.filter(x => x.ruolo === 'progettista'));
    var piano_formativo_attivo=<?php if($this->id_piano_formativo_attivo){ echo $this->id_piano_formativo_attivo; } else { echo "null";}?>;


    jQuery("#piano_formativo").change(function(event){

        var id=jQuery("#piano_formativo").val();
        window.open("?option=com_ggpm&view=pianiformativi&id_piano_formativo_attivo="+id.toString(),"_self")
    });



    jQuery("#task_ruolo").change(function(){

        var ruolo_scelto=jQuery("#task_ruolo").val();
        //console.log(ruolo_scelto);
        jQuery("#task_dipendente option").remove();
        var dipendenti_da_caricare=ruoli_dipendenti.filter(x => x.ruolo == ruolo_scelto);
        //console.log(dipendenti_da_caricare);
        jQuery("#task_dipendente").append("<option value='0'>scegli "+jQuery("#task_ruolo option:selected").text()+"</option>");
        for (i=0; i<dipendenti_da_caricare.length; i++){

            jQuery("#task_dipendente").append("<option value="+dipendenti_da_caricare[i]['id']+">"+dipendenti_da_caricare[i]['cognome']+"</option>");
        }
    });

    jQuery("#task_dipendente").change(function(){
        var valore_orario=null;
        var id_dipendente= jQuery("#task_dipendente").val();
        jQuery.ajax({
            method: "POST",
            cache: false,
            url: 'index.php?option=com_ggpm&task=dipendenti.getdipendentevaloreorario&id='+id_dipendente

        }).done(function(data) {

            valore_orario=JSON.parse(data);
            jQuery("#task_valore_orario").val(valore_orario);
        });

    });


    jQuery("#task_durata").keyup(function(){


        update_data_fine();
    });

    jQuery("#task_data_inizio").change(function(){

        update_data_fine();
    });


    jQuery("#task_task_propedeutico").change(function(){

        var id_task_propedeutico=jQuery("#task_task_propedeutico").val();

        jQuery.ajax({
            method: "POST",
            cache: false,
            url: 'index.php?option=com_ggpm&task=task.gettask&id='+id_task_propedeutico

        }).done(function(data) {

            //console.log(data)
            console.log(JSON.parse(data))
            //console.log(JSON.parse(data)[0].data_inizio);
            var data_to_set=new Date(JSON.parse(data)[0][0].data_fine);
            var mese_to_set=("0"+(data_to_set.getMonth()+1)).toString().slice(-2)
            if(data_to_set.getDate().toString().length===1) {
                var giorno_to_set = ("0" + (data_to_set.getDate().toString()).slice(-2))
            }else{
                var giorno_to_set = data_to_set.getDate().toString()
            }
            jQuery("#task_data_inizio").val(data_to_set.getFullYear()+"-"+mese_to_set+"-"+giorno_to_set);

        });
    })


    function update_data_fine() {

        if(jQuery("#task_data_inizio").val().length>0 && !isNaN(parseInt(jQuery("#task_durata").val()))){
            //var data_inizio=new Date();
            //console.log(data_inizio);
            var data_inizio=new Date(jQuery("#task_data_inizio").val());
            data_inizio=data_inizio.getFullYear()+"-"+(data_inizio.getMonth()+1)+"-"+data_inizio.getDate();
            var giorni_da_aggiungere_=jQuery("#task_durata").val();
            var giorni_da_aggiungere=0;
            var id_dipendente= jQuery("#task_dipendente").val();
            console.log(giorni_da_aggiungere);
            jQuery.ajax({
                method: "POST",
                cache: false,
                url: 'index.php?option=com_ggpm&task=task.gestionegiornidaaggiungere&data_inizio='+data_inizio+'&durata='+giorni_da_aggiungere_+'&id_dipendente='+id_dipendente

            }).done(function(data) {


                giorni_da_aggiungere=JSON.parse(data);

                durata=giorni_da_aggiungere-1;
                var data_fine=new Date(jQuery("#task_data_inizio").val());
                data_fine.setDate(data_fine.getDate()+parseInt(durata))

                jQuery("#task_data_fine_calcolata").html(data_fine.getDate().toString()+"/"+(data_fine.getMonth()+1).toString()+"/"+data_fine.getFullYear());
                verificacaricodipendente(id_dipendente,data_inizio,data_fine.getFullYear().toString()+"-"+(data_fine.getMonth()+1).toString()+"-"+data_fine.getDate().toString());


            });


        }

    }

    function verificacaricodipendente(id_dipendente,data_inizio,data_fine){

        jQuery.ajax({
            method: "POST",
            cache: false,
            url: 'index.php?option=com_ggpm&task=task.verificacaricodipendente&id_dipendente='+id_dipendente+'&data_inizio='+data_inizio+'&data_fine='+data_fine

        }).done(function(data) {

            console.log(JSON.parse(data));
            var sovrapposizioni=JSON.parse(data);
            var scritta="";
            jQuery(".modal-body").empty();
            if(sovrapposizioni.length>0) {
                jQuery(".modal-body").append("<table class=\"table table-bordered table-striped\"><thead><tr class=\"d-flex\"><th class=\"col-6 \"> PIANO FORMATIVO</th><th class=\"col-6\">TASK</th></tr></thead><tbody>");
                for (i = 0; i < sovrapposizioni.length; i++) {
                    jQuery(".modal-body table").append("<tr class=\"d-flex\"><td class='col-6'><b>" + sovrapposizioni[i].descrizione_piano + "</b></td><td class='col-6'><b>" + sovrapposizioni[i].descrizione + "</b></td></tr>")
                }
                jQuery(".modal-body").append("</tbody></table>");
                jQuery("#exampleModal").modal('show');
            }

        });

    }

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

    function inserttaskclick(){

        var id_piano_formativo=piano_formativo_attivo;
        var descrizione=jQuery("#task_descrizione").val();
        var data_inizio=jQuery("#task_data_inizio").val();
        //var durata=jQuery("#task_durata").val();
        var ore=jQuery("#task_ore").val();
        var id_voce_costo=jQuery("#task_voce_costo").val();
        var id_ruolo=jQuery("#task_ruolo").val();
        var id_dipendente=jQuery("#task_dipendente").val();
        var id_task_propedeutico=jQuery("#task_task_propedeutico").val();

        var valore_orario=jQuery("#task_valore_orario").val().replace(",",".");

        jQuery.ajax({
            method: "POST",
            cache: false,
            url: 'index.php?option=com_ggpm&task=task.insert&id_piano_formativo='+id_piano_formativo
            +'&descrizione='+descrizione
            +'&data_inizio='+data_inizio
            +'&durata='+durata
            +'&ore='+ore
            +'&id_voce_costo='+id_voce_costo
            +'&id_ruolo='+id_ruolo
            +'&id_dipendente='+id_dipendente
            +'&id_task_propedeutico='+id_task_propedeutico
            +'&valore_orario='+valore_orario



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
