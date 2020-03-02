<?php

namespace App\GenerarHorario;

use App\GenerarHorario\Problema;
use App\GenerarHorario\Ant;

class Main
{
    public $alpha; // = 1.0;
    public $beta; // = 2.0;
    public $q; // = 50.0;
    public $rho; // = 0.1; //Evaporacion    
    public $numberIteration; // = 1; //limite de iteraciones
    public $numberOfAnts; // = 1; //hormigas usadas
    public $estancado; // = 30;
    public $t_max; // = 1 / rho;        
    public $t_minDenominador; // = 5;
    public $t_min; // = t_max / t_minDenominador;        


    public $ants = []; //List<Ant> ants = new ArrayList<>(); //hormigas creadas    
    public $cList = []; //List<Integer> cList = new ArrayList<>(); //timeslots para asignar    
    public $breaks = []; //List<Integer> breaks = new ArrayList<>(); //timeslots para especificar receso


    // Random random = new Random(); //numero random    
    public $matrizPheromoneT = array();  //matriz de asignacion de evento a Ts
    public $pheromone;
    public $Ni_et = []; //List<Double> Ni_et = new ArrayList<>();
    public $probabilidad = []; //List<Double> probabilidad = new ArrayList<>();
    public $problema; //Problema problema;
    public $eventos; //int eventos;
    public $salones; //int salones;
    public $timeslot; //int timeslot;    
    public $currentLocalBest; //Ant currentLocalBest;
    public $currentGlobalBest; //Ant currentGlobalBest;    
    public $encontroGlobal = false; //boolean encontroGlobal = false;
    public $contadorGlobal = 0; //int contadorGlobal = 0;
    public $eventosProgramados = []; //List<Eventos> eventosProgramados = new ArrayList<>();
    public $eventosProgramados2 = []; //List<Eventos> eventosProgramados2 = new ArrayList<>()

    public function __construct($eventosConMaestros, $maestros_et, $espaciosDeTiempo, $alpha, $beta, $q, $evaporation, $iterations, $ants, $estancado, $t_minDenominador, $num_aulas, $receso)
    {
        $this->problema = new Problema($eventosConMaestros, $maestros_et, $espaciosDeTiempo);
        $this->alpha = $alpha;
        $this->beta = $beta;
        $this->q = $q;
        $this->rho = $evaporation;
        $this->numberIteration = $iterations;
        $this->numberOfAnts = $ants;
        $this->estancado = $estancado;
        $this->t_max = 1 / $this->rho;
        $this->t_minDenominador = $t_minDenominador;
        $this->t_min = $this->t_max / $t_minDenominador;
        $this->salones = $num_aulas;
        foreach ($receso as $itemReceso) {
            $this->breaks[] = $itemReceso;
        }
        $this->pheromone = 0.0;
        $this->eventos = sizeof($this->problema->eventos);
        $this->timeslot = sizeof($this->problema->timeslots);
        $this->matrizPheromoneT = array(); //new Double[eventos][timeslot];
        $this->matrizSolucion = array(); //new String[timeslot][salones];        

        for ($i = 0; $i < $this->eventos; $i++) {
            for ($j = 0; $j < $this->timeslot; $j++) {
                $this->matrizPheromoneT[$i][$j] = $this->t_max;
            }
        }
        for ($i = 0; $i < $this->numberOfAnts; $i++) {
            $this->ants[] = new Ant($i, $this->problema);
        }
        $this->cList = $this->problema->timeslots;
        foreach ($this->ants as $ant) {
            for ($j = 0; $j < sizeof($this->cList); $j++) {
                $ant->cListAlready[] = false;
                $ant->Vi[] = 0;
                $ant->ViolacionesDuras[] = 0;
                $ant->Ai[] = null;
            }
            // foreach($receso as $break){      
            if ($this->breaks != null) {
                foreach ($this->breaks as $break) {
                    $ant->cListAlready[$break] = true;
                }
            }
        }
    }
    public function start()
    {
        global $test;
        global $currentIndex;
        global $total;
        for ($i = 0; $i < sizeof($this->cList); $i++) {
            $this->Ni_et[] = 0.0;
            $this->probabilidad[] = 0.0;
        }
        for ($i = 0; $i < $this->numberIteration; $i++) {
            $this->reset();
            foreach ($this->ants as $ant) {
                for ($k = 0; $k < sizeof($this->problema->eventos); $k++) {
                    $this->CalcularProbabilidades($k, $ant);
                    $numberDouble = (float) rand() / (float) getrandmax();
                    $total = 0;
                    for ($l = 0; $l < sizeof($this->cList); $l++) {
                        $total += $this->probabilidad[$l];
                        if ($total >= $numberDouble) {
                            $currentIndex = $l;
                            $ant->Ai[] = $l; // ants.get(j).Ai.add(l);                                                                                                                
                            break;
                        }
                    }
                    $this->penalizar($ant);
                    $this->penalizarMaestro($ant, $k, "$currentIndex");
                }
                $this->penalizarEmpalmeMaestro($ant);
            }
            $this->mejorHormigaLocal();
            // dd(json_encode($this->ants));
            $this->busquedaLocal();
            // dd(json_encode($this->currentGlobalBest->Vi));
            $this->penalizarEmpalmeMaestro($this->currentLocalBest);
            $this->contarViolacionesSuaves($this->currentLocalBest);
            $this->mejorHormigaGlobal();
            $this->updathePheromoneTrails();
            $this->reiniciarTmaxAndTmin();
        }
        $this->contarViolacionesSuaves($this->currentGlobalBest);
        $this->penalizarEmpalmeMaestro($this->currentGlobalBest);
        $this->imprimirSolucion($this->currentGlobalBest);

        $this->matrizSolucion = array_combine($this->problema->timeslotsHoras, $this->matrizSolucion);
        // dd(json_encode($this->currentGlobalBest->Vi));
    }
    public function imprimirSolucion($ant)
    {
        for ($k = 0; $k < $this->timeslot; $k++) {
            for ($i = 1; $i <= $this->salones; $i++) {
                $this->matrizSolucion[$k][$i] = null;
            }
        }
        for ($k = 0; $k < sizeof($ant->Vi); $k++) {
            $this->matrizSolucion[$k][0] = $ant->Vi[$k];
        }

        for ($k = 0; $k < sizeof($ant->Ai); $k++) {
            for ($i = 1; $i <= $this->salones; $i++) {
                //Verifico si el "salon" esta vacio para poderlo asignar, si no lo pasó al siguiente, esto solo en la matriz de solución
                if ($this->matrizSolucion[$ant->Ai[$k]][$i] == null) {
                    // dd($this->problema->eventos[$k]);
                    $this->matrizSolucion[$ant->Ai[$k]][$i] = $this->problema->eventos[$k]->id;
                    for ($j = 0; $j < sizeof($this->problema->eventos[$k]->maestroList); $j++) {
                        $this->matrizSolucion[$ant->Ai[$k]][$i] .= "," . $this->problema->eventos[$k]->maestroList[$j]->nombre;
                        // $this->matrizSolucion[$ant->Ai[$k]][$this->problema->eventos[$k]->name] = $this->problema->eventos[$k]->maestroList[$j]->nombre;                        
                    }
                    break;
                }
            }
        }
    }

    public function reiniciarTmaxAndTmin()
    {
        if ($this->encontroGlobal == false) {
            $this->contadorGlobal++;
        } else {
            $this->contadorGlobal = 0;
            // System.out.println("Se encontró un nuevo mejor global");
        }
        if ($this->contadorGlobal == $this->estancado) {
            // System.out.println("No se ha encontrado un mejor global, la busqueda se ha estancado, la matriz de feromona se ha reiniciado");
            for ($i = 0; $i < $this->eventos; $i++) {
                for ($j = 0; $j < $this->timeslot; $j++) {
                    $this->matrizPheromoneT[$i][$j] = $this->t_max;
                }
            }
        }
    }
    public function updathePheromoneTrails()
    {
        for ($i = 0; $i < $this->eventos; $i++) {
            for ($j = 0; $j < $this->timeslot; $j++) {
                $this->matrizPheromoneT[$i][$j] *= (1 - $this->rho);
            }
        }
        for ($j = 0; $j < sizeof($this->currentGlobalBest->Ai); $j++) {
            $this->matrizPheromoneT[$j][$this->currentGlobalBest->Ai[$j]] += $this->currentGlobalBest->recorrido;
        }

        if ($this->encontroGlobal) {
            for ($i = 0; $i < $this->eventos; $i++) {
                for ($j = 0; $j < $this->timeslot; $j++) {
                    if ($this->matrizPheromoneT[$i][$j] < $this->t_min) {
                        $this->matrizPheromoneT[$i][$j] = $this->t_min;
                    } else if ($this->matrizPheromoneT[$i][$j] > $this->t_max) {
                        $this->matrizPheromoneT[$i][$j] = $this->t_max;
                    } else {
                        $this->matrizPheromoneT[$i][$j] = $this->matrizPheromoneT[$i][$j];
                    }
                }
            }
        }
    }

    public function mejorHormigaGlobal()
    {
        if ($this->currentGlobalBest == null) {
            $this->currentGlobalBest = $this->currentLocalBest;
            $this->encontroGlobal = true;
            $this->t_max = 1 / $this->rho * $this->currentGlobalBest->recorrido;
            $this->t_min = $this->t_max / $this->t_minDenominador;
        } else if ($this->currentLocalBest->violaciones < $this->currentGlobalBest->violaciones) {
            $this->currentGlobalBest = $this->currentLocalBest;
            $this->encontroGlobal = true;
            $this->t_max = 1 / $this->rho * $this->currentGlobalBest->recorrido;
            $this->t_min = $this->t_max / $this->t_minDenominador;
        } else {
            $this->encontroGlobal = false;
        }
    }

    public function verificarEventosProgramados()
    {
    }

    public function busquedaLocal()
    {
        global $eventosMover;
        $eventosMover = array();
        $this->eventosProgramados = array();
        global $indiceEventoMover;
        $indiceEventoMover = 0;
        global $indiceNoMover;
        $indiceNoMover = 0;
        //timeslot al cual el evento se va mover, es decir, timeslot actual +1
        global $timeslotMove;
        $timeslotMove = 0;
        //actual espacio de tiempo el cual pertenece
        global $currentTimeslot;
        $currentTimeslot = 0;
        //contador para saber cuantos eventos hay en el espacio de tiempo al cual se va mover
        global $contadorNextTS;
        $contadorNextTS = 0;
        //indice para poder reemplazar el valor en la lista Ai
        global $indiceAsignacionEvento;
        $indiceAsignacionEvento = 0;
        //variable de prueba
        global $contadorTest;
        $contadorTest = 0;
        //Variable para contar en el while
        global $z;
        $z = 0;

        global $indiceN2Evento;
        $indiceN2Evento = 0;

        //Variable aun aprueba para guardar la posicion 
        global $indiceAi;
        $indiceAi = 0;
        global $indiceAi2;
        $indiceAi2 = 0;

        global $contadorN2;
        $contadorN2 = 0;
        //booleano para detener el bucle para recorrer el siguiente espacio de tiempo
        global $nextTS;
        $nextTS = false;

        global $posicionAi;
        $posicionAi = array(); // List<Integer> posicionAi = new ArrayList<>();
        global $posicionAiMover;
        $posicionAiMover = array(); //List<Integer> posicionAiMover = new ArrayList<>();
        global $posicionEventosProgramados;
        $posicionEventosProgramados = array(); //List<Integer> posicionEventosProgramados = new ArrayList<>();

        // for (int i = 0; i < currentLocalBest.ViolacionesDuras.size(); i++) {
        for ($i = 0; $i < sizeof($this->currentLocalBest->ViolacionesDuras); $i++) {
            // if (currentLocalBest.ViolacionesDuras.get(i) > 0) {
            if ($this->currentLocalBest->ViolacionesDuras[$i] > 0) {
                $this->eventosProgramados = array();
                $posicionAi = array();
                for ($j = 0; $j < sizeof($this->currentLocalBest->Ai); $j++) {
                    if ($this->currentLocalBest->Ai[$j] == $i) {
                        //los agrega a la lista de eventos programados para su uso posterior de analizar si hay dos maestros iguales en ese mismo espacio de tiempo
                        $this->eventosProgramados[] = $this->problema->eventos[$j]; 
                        $posicionAi[] = $j;
                    }
                }
                for ($l = 0; $l < sizeof($this->eventosProgramados); $l++) {
                    for ($m = $l + 1; $m < sizeof($this->eventosProgramados); $m++) {
                        for ($n = 0; $n < sizeof($this->eventosProgramados[$l]->maestroList); $n++) {
                            for ($o = 0; $o < sizeof($this->eventosProgramados[$m]->maestroList); $o++) {
                                // if (eventosProgramados.get(l).maestroList.get(n).name.equals(eventosProgramados.get(m).maestroList.get(o).name)) {
                                if ($this->eventosProgramados[$l]->maestroList[$n]->nombre == $this->eventosProgramados[$m]->maestroList[$o]->nombre && !in_array($this->eventosProgramados[$l],$eventosMover)) {
                                    if (in_array($i, $this->eventosProgramados[$l]->espaciosComun) && !in_array($i, $this->eventosProgramados[$m]->espaciosComun)) {
                                        //System.out.println("El evento " + eventosProgramados.get(m).name + " sera movido");
                                        $indiceEventoMover = $m;
                                        $indiceNoMover = $l;
                                    } else if (in_array($i, $this->eventosProgramados[$l]->espaciosComun) && in_array($i, $this->eventosProgramados[$m]->espaciosComun)) {
                                        //System.out.println("El evento " + eventosProgramados.get(m).name + " sera movido porque ambos contienen");
                                        $indiceEventoMover = $m;
                                        $indiceNoMover = $l;
                                    } else if (!in_array($i, $this->eventosProgramados[$l]->espaciosComun) && in_array($i, $this->eventosProgramados[$m]->espaciosComun)) {
                                        //System.out.println("El evento " + eventosProgramados.get(l).name + " sera movido");
                                        $indiceEventoMover = $l;
                                        $indiceNoMover = $m;
                                    } else if (!in_array($i, $this->eventosProgramados[$l]->espaciosComun) && !in_array($i, $this->eventosProgramados[$m]->espaciosComun)) {
                                        //System.out.println("El evento " + eventosProgramados.get(m).name + " sera movido porque ninguno contiene");
                                        $indiceEventoMover = $m;
                                        $indiceNoMover = $l;
                                    }
                                    if (!in_array($this->eventosProgramados[$indiceEventoMover], $eventosMover)) {
                                        $eventosMover[] = $this->eventosProgramados[$indiceEventoMover]; // $eventosMover.add(eventosProgramados.get(indiceEventoMover));
                                        $posicionAiMover[] = $posicionAi[$indiceEventoMover]; // $posicionAiMover.add(posicionAi.get(indiceEventoMover));
                                    }
                                    $n = $o = sizeof($this->eventosProgramados[$m]->maestroList) + 1;
                                }
                            }
                        }
                    }
                }
            }
        }
        // dd("mejor hormiga",$this->currentLocalBest,"eventos a mover",$eventosMover,"posicion de los eventos a mover",$posicionAiMover);
        for ($i = 0; $i < sizeof($eventosMover); $i++) {
            for ($j = 0; $j < sizeof($this->problema->eventos); $j++) {
                //Obtengo el espacio de tiempo en donde esta asignado actualmente                
                if ($eventosMover[$i]->name == $this->problema->eventos[$j]->name) {
                    //currentTimeslot representa el espacio de tiempo actual, el cual esta asignado y esta generando hcv                    
                    $currentTimeslot = $this->currentLocalBest->Ai[$j]; //.Ai.get(j);
                    //System.out.println("El ultimo espacio de tiempo es "+(timeslot-1));
                    if ($currentTimeslot == $this->timeslot) {
                        $currentTimeslot = -1;
                        //System.out.println("El ultimo espacio de tiempo es " + (timeslot - 1));
                        //System.out.println("Error");
                    }
                    //indiceAsignacionEvento representa en que indice de la lista Ai esta asignado evento ei
                    $indiceAsignacionEvento = $j;
                    break;
                }
            }
            $timeslotMove = $currentTimeslot;
            $nextTS = false;
            $z = 0;
            while ($nextTS == false) {                
                $z++;
                // dd(($this->receso));
                // dd("espacios de tiempo recso",($this->timeslot - sizeof($this->receso)));
                if ($z > ($this->timeslot - sizeof($this->breaks))) {                                             
                    // echo ($z);
                    // echo ("\n\n\n Entró a L2");
                    // if ($z == ($this->timeslot)) {                                            
                    for ($k = 0; $k < $this->timeslot; $k++) {
                        // if($k==2){
                        // dd($k);}
                        $this->eventosProgramados = array();
                        $posicionEventosProgramados = array();
                        // dd($this->eventosProgramados);
                        //evitar receso
                        // 
                        if ($k != $currentTimeslot && !in_array($k, $this->breaks)) {
                            // dd(($this->receso));
                            // dd("violaciones duras l2");
                            for ($j = 0; $j < sizeof($this->currentLocalBest->Ai); $j++) {
                                //guardo en la lista, aquellos eventos que estan en el espacio de tiempo k para comparar con el evento a mover
                                if ($this->currentLocalBest->Ai[$j] == $k) {
                                    $this->eventosProgramados[] = $this->problema->eventos[$j];
                                    $posicionEventosProgramados[] = $j;
                                }
                            }
                            // dd("Espacio de tiempo",$k,"Eventos programados",$this->eventosProgramados,"mejor hormiga locla",$this->currentLocalBest);
                            $contadorTest = 0;
                            $contadorN2 = 0;
                            for ($j = 0; $j < sizeof($this->eventosProgramados); $j++) {
                                for ($l = 0; $l < sizeof($this->eventosProgramados[$j]->maestroList); $l++) {
                                    // if (eventosMover.get(i).maestroList.contains(eventosProgramados.get(j).maestroList.get(l))) {
                                    if (in_array($this->eventosProgramados[$j]->maestroList[$l], $eventosMover[$i]->maestroList)) {
                                        //System.out.println("Si contiene maestro repetido entre el evento mover y los eventos al espacio de tiempo que quieor mover");
                                        $contadorTest++;
                                        //cual es el evento que repite
                                        $indiceN2Evento = $j;
                                        break;
                                    }
                                }
                            }

                            $this->eventosProgramados2 = array();
                            //guardo los eventos del espacio de tiempo en donde esta ubicado EventosMover.´para luego comparar que ningun cambio empalme
                            for ($j = 0; $j < sizeof($this->currentLocalBest->Ai); $j++) {
                                if ($this->currentLocalBest->Ai[$j] == $currentTimeslot) {
                                    if ($this->problema->eventos[$j] != $eventosMover[$i]) {
                                        $this->eventosProgramados2[] = $this->problema->eventos[$j];
                                    }
                                }
                            }

                            if ($contadorTest == 1) {
                                for ($m = 0; $m < sizeof($this->eventosProgramados2); $m++) {
                                    for ($l = 0; $l < sizeof($this->eventosProgramados[$indiceN2Evento]->maestroList); $l++) {
                                        // if (eventosProgramados2.get(m).maestroList.contains(eventosProgramados.get(indiceN2Evento).maestroList.get(l))) {
                                        if (in_array($this->eventosProgramados[$indiceN2Evento]->maestroList[$l], $this->eventosProgramados2[$m]->maestroList)) {
                                            $contadorN2++;
                                            break;
                                        }
                                    }
                                    //System.out.println("el evento repetido es " + eventosProgramados.get(indiceN2Evento).name);
                                }
                                if ($contadorN2 == 0) {
                                    // currentLocalBest.Ai.set(posicionEventosProgramados.get(indiceN2Evento), currentTimeslot);  //del nuevo a donde genera el error
                                    // currentLocalBest.Ai.set(posicionAiMover.get(i), k); //de donde genera el error al nuevo   al parecer ya esta
                                    $this->currentLocalBest->Ai[$posicionEventosProgramados[$indiceN2Evento]] = $currentTimeslot;  //del nuevo a donde genera el error
                                    $this->currentLocalBest->Ai[$posicionAiMover[$i]] = $k; //de donde genera el error al nuevo   al parecer ya esta
                                    $nextTS = true;               
                                    // echo ("cambió L2 ContadorN2==0");
                                    $k = $this->timeslot + 1;
                                }
                            } else if ($contadorTest == 0) {
                                $proyectosContadorN2 = 0;
                                for ($m = 0; $m < sizeof($this->eventosProgramados2); $m++) {
                                    //for (int l = 0; l < eventosProgramados.get(indiceN2Evento).maestroList.size(); l++) {
                                    for ($l = 0; $l < sizeof($this->eventosProgramados); $l++) {
                                        $contadorN2 = 0;
                                        for ($n = 0; $n < sizeof($this->eventosProgramados[$l]->maestroList); $n++) {
                                            // if (!eventosProgramados2.get(m).maestroList.contains(eventosProgramados.get(l).maestroList.get(n))) {
                                            if (!in_array($this->eventosProgramados[$l]->maestroList[$n], $this->eventosProgramados2[$m]->maestroList)) {
                                                //if (!eventosProgramados2.get(m).maestroList.contains(eventosProgramados.get(l).maestroList.get(n))) {                                                
                                                $contadorN2++;
                                                //l = eventosProgramados.size() + 1;                                               
                                                //break;                                                
                                            } else {
                                                break;
                                            }
                                        }
                                        if ($contadorN2 == sizeof($this->eventosProgramados[$l]->maestroList)) {
                                            $indiceN2Evento = $l;
                                            $proyectosContadorN2++;
                                        }
                                    }
                                }
                                if ($proyectosContadorN2 == 2) {
                                    // currentLocalBest.Ai.set(posicionEventosProgramados.get(indiceN2Evento), currentTimeslot);  //del nuevo a donde genera el error
                                    // currentLocalBest.Ai.set(posicionAiMover.get(i), k); //de donde genera el error al nuevo   al parecer ya esta
                                    // indiceN2Evento = (int) (Math.random() * eventosProgramados.size());
                                    $indiceN2Evento = rand(0, sizeof($this->eventosProgramados));
                                    if($indiceN2Evento > 0){
                                        $indiceN2Evento -= 1;
                                    }                                    
                                    
                                    // if($indiceN2Evento == 2){
                                    //     dd("error proyectsContadorN2",$indiceN2Evento,"posivion",$posicionEventosProgramados);
                                    // }                                    
                                    $this->currentLocalBest->Ai[$posicionEventosProgramados[$indiceN2Evento]] = $currentTimeslot;
                                    $this->currentLocalBest->Ai[$posicionAiMover[$i]] = $k;
                                    $nextTS = true;          
                                    // echo ("cambió L2 proyectosContadorN2==2");                          
                                    $k = $this->timeslot + 1;
                                }
                                if ($proyectosContadorN2 == 1) {
                                    // currentLocalBest.Ai.set(posicionEventosProgramados.get(indiceN2Evento), currentTimeslot);  //del nuevo a donde genera el error
                                    // currentLocalBest.Ai.set(posicionAiMover.get(i), k); //de donde genera el error al nuevo   al parecer ya esta

                                    $this->currentLocalBest->Ai[$posicionEventosProgramados[$indiceN2Evento]] = $currentTimeslot;
                                    $this->currentLocalBest->Ai[$posicionAiMover[$i]] = $k;
                                    $nextTS = true;        
                                    // echo ("cambió L2 proyectosContadorN2==1");                            
                                    $k = $this->timeslot + 1;
                                }
                            }
                            $nextTS = true;        
                        }
                    }
                } else {
                    
                    $contadorNextTS = 0;
                    $timeslotMove++;                    
                    // dd($this->receso,$timeslotMove);                    
                    // var_dump($timeslotMove);
                    // && !in_array($timeslotMove,$this->receso)
                    //evitar receso      
                    // nuevocode                    
                    // $posicionEventosProgramados = array();              
                    // echo ("\nentró a L1 ".$timeslotMove." valor de Z:".$z);
                    if (!in_array($timeslotMove, $this->breaks)) {                                                
                        if ($timeslotMove < $this->timeslot) {
                        // dd($this->ants);                        
                            // dd($timeslotMove,$this->receso,!in_array($timeslotMove,$this->receso),$this->currentLocalBest->Ai);                        
                            $this->eventosProgramados = array();
                            // dd(sizeof($this->currentLocalBest->Ai));
                            for ($j = 0; $j < sizeof($this->currentLocalBest->Ai); $j++) {                                                                
                                // dd("timeslotMove",$timeslotMove,"currentlocalbest",$this->currentLocalBest->Ai[$j]);
                                if ($this->currentLocalBest->Ai[$j] == $timeslotMove) {   
                                    // echo ("\nError");                                 
                                    $this->eventosProgramados[] = $this->problema->eventos[$j]; 
                                    $contadorNextTS++;
                                }
                            }
                            // echo (sizeof($this->eventosProgramados));
                            // echo("contador".$contadorNextTS."\n");
                            // echo ("\nsalio del ciclo Error");    
                            // echo ($timeslotMove);
                            // var_dump ($this->eventosProgramados,"\n");
                            if ($contadorNextTS < $this->salones) {
                                $contadorTest = 0;
                                //cabe recalcar que eventosProgramados los uso para agregar a aquellos eventos que estan asignados en un mismo espacio de tiempo para poder verificar si existe o no empalme                
                                for ($j = 0; $j < sizeof($this->eventosProgramados); $j++) {
                                    for ($k = 0; $k < sizeof($eventosMover[$i]->maestroList); $k++) {
                                        // if (eventosMover.get(i).maestroList.contains(eventosProgramados.get(j).maestroList.get(k))) {
                                        if (in_array($this->eventosProgramados[$j]->maestroList[$k], $eventosMover[$i]->maestroList)) {
                                            //System.out.println("Comparando el evento problematico con el evento " + eventosProgramados.get(j).name);
                                            //System.out.println(eventosProgramados.get(j).maestroList.get(k) + ", " + eventosMover.get(i).maestroList);
                                            $contadorTest++;
                                        }
                                    }
                                }
                                if ($contadorTest == 0) {
                                    // currentLocalBest.Ai.set(indiceAsignacionEvento, timeslotMove);
                                    $this->currentLocalBest->Ai[$indiceAsignacionEvento] = $timeslotMove;
                                    // echo ("cambio");
                                    $nextTS = true;                                    
                                    //System.out.println("El evento pudo ser reasignado");
                                }
                                //System.out.println("El evento empalmado " + eventosMover.get(i).name + " Sera movido al nuevo espacio de tiempo " + timeslotMove + " en Ai se va cambiar " + indiceAsignacionEvento);
                            }
                        }
                        else {
                            $timeslotMove = -1;
                        }
                    } 
                }
            }
        }
        // $this->contarViolacionesDuras($this->currentLocalBest);
        $this->penalizarEmpalmeMaestro($this->currentLocalBest);
        $this->contarViolacionesSuaves($this->currentLocalBest);
        if ($this->currentLocalBest->intViolacionesDuras > 0) {
            // dd("put");
            return 0;
        } else {
            $eventosMover = array();
            for ($i = 0; $i < sizeof($this->currentLocalBest->Vi); $i++) {
                if ($this->currentLocalBest->Vi[$i] > 0) {
                    //Esto no podría funcionar como esta en el codigo pero me beneficia
                    //al parecer limpia lo ultimo almacenado al momento de contar las violaciones duras de hormiga por hormiga
                    $this->eventosProgramados = array();
                    //Recorro las asignaciones para poder agregar a una lista a aquellos eventos que estan en un espacio de tiempo en donde existen hcv
                    for ($j = 0; $j < sizeof($this->currentLocalBest->Ai); $j++) {
                        //eventosMover.clear();
                        if ($this->currentLocalBest->Ai[$j] == $i) {
                            //los agrega a la lista de eventos programados para su uso posterior de analizar si hay dos maestros iguales en ese mismo espacio de tiempo                            
                            $this->eventosProgramados[] = $this->problema->eventos[$j];
                        }
                    }
                    //                    Comparo todos los eventos para poder determinar cual es el evento que va tener que ser movido, asi que se agragn a una nueva lista                 
                    for ($l = 0; $l < sizeof($this->eventosProgramados); $l++) {
                        if (!in_array($i, $this->eventosProgramados[$l]->espaciosComun)) {
                            //System.out.println("El evento " + eventosProgramados.get(m).name + " sera movido");
                            //indiceEventoMover = m;
                            // eventosMover.add(eventosProgramados.get(l));
                            $eventosMover[] = $this->eventosProgramados[$l];
                        }
                    }
                }
            }

            for ($i = 0; $i < sizeof($eventosMover); $i++) {
                for ($j = 0; $j < sizeof($this->problema->eventos); $j++) {
                    //Obtengo el espacio de tiempo en donde esta asignado actualmente
                    if ($eventosMover[$i]->name == $this->problema->eventos[$j]->name) {
                        //currentTimeslot representa el espacio de tiempo actual, el cual esta asignado y esta generando scv                    
                        $currentTimeslot = $this->currentLocalBest->Ai[$j];
                        //Si llega al ultimo espacio de tiempo y no ha recorrdio 0 entonces se pone en -1 para que sumando 1 vaya el ET 0
                        //System.out.println("El ultimo espacio de tiempo es "+(timeslot-1));
                        if ($currentTimeslot == $this->timeslot) {
                            $currentTimeslot = -1;
                            //System.out.println("El ultimo espacio de tiempo es " + (timeslot - 1));
                            //System.out.println("Error");
                        }
                        //indiceAsignacionEvento representa en que indice de la lista Ai esta asignado evento ei
                        $indiceAsignacionEvento = $j;
                        break;
                    }
                    //if()
                }

                $timeslotMove = $currentTimeslot;
                $nextTS = false;
                $z = 0;
                while ($nextTS == false) {
                    $z++;
                    // if ($z == ($this->timeslot - sizeof($this->receso))) {
                    if ($z == ($this->timeslot - sizeof($this->breaks))) {
                        $nextTS = true;                        
                        //System.exit(0);
                    } else {
                        $contadorNextTS = 0;
                        //debería ir un while o un ciclo que permita seguir con la busqueda del espacio de tiempo +1?
                        //Error a veces porque asigna un espacio de tiempo fuera del intervalo                
                        //if(timeslotMove > timeslot)
                        $timeslotMove++;
                        //evitar receso
                        // && !in_array($timeslotMove,$this->receso)
                        if ($timeslotMove < $this->timeslot) {
                            if (!in_array($timeslotMove, $this->breaks)) {
                                $this->eventosProgramados = array();
                                //System.out.println("TimeslotMove antes de camiar " + timeslotMove);
                                for ($j = 0; $j < sizeof($this->currentLocalBest->Ai); $j++) {
                                    if ($this->currentLocalBest->Ai[$j] == $timeslotMove) {
                                        $this->eventosProgramados[] = $this->problema->eventos[$j];
                                        $contadorNextTS++;
                                    }
                                }
                                if ($contadorNextTS < $this->salones) {
                                    $contadorTest = 0;
                                    //cabe recalcar que eventosProgramados los uso para agregar a aquellos eventos que estan asignados en un mismo espacio de tiempo para poder verificar si existe o no empalme                
                                    for ($j = 0; $j < sizeof($this->eventosProgramados); $j++) {
                                        for ($k = 0; $k < sizeof($eventosMover[$i]->maestroList); $k++) {
                                            // if (eventosMover.get(i).maestroList.contains(eventosProgramados.get(j).maestroList.get(k))) {
                                            if (in_array($this->eventosProgramados[$j]->maestroList[$k], $eventosMover[$i]->maestroList)) {
                                                //System.out.println("Comparando el evento problematico con el evento " + eventosProgramados.get(j).name);
                                                //System.out.println(eventosProgramados.get(j).maestroList.get(k) + ", " + eventosMover.get(i).maestroList);
                                                $contadorTest++;
                                            }
                                        }
                                    }
                                    if ($contadorTest == 0) {
                                        // if (eventosMover.get(i).espaciosComun.contains(timeslotMove)) {
                                        if (in_array($timeslotMove, $eventosMover[$i]->espaciosComun)) {
                                            // currentLocalBest.Ai.set(indiceAsignacionEvento, timeslotMove);
                                            $this->currentLocalBest->Ai[$indiceAsignacionEvento] = $timeslotMove;
                                            $nextTS = true;                                            
                                        }
                                        //System.out.println("El evento pudo ser reasignado");
                                    }
                                    //System.out.println("El evento empalmado " + eventosMover.get(i).name + " Sera movido al nuevo espacio de tiempo " + timeslotMove + " en Ai se va cambiar " + indiceAsignacionEvento);
                                }
                            }
                        } else {
                            $timeslotMove = -1;
                        }
                    }
                }
            }
        }
    }
    public function contarViolacionesDuras()
    {
    }
    public function contarViolacionesSuaves($ant)
    {
        global $count;
        $count = 0;
        for ($i = 0; $i < sizeof($ant->Vi); $i++) {
            $ant->Vi[$i] = 0;
        }

        $ant->Vi[0] = 0;
        for ($j = 0; $j < $this->eventos; $j++) {
            global $encontrado;
            $encontrado = false;
            global $newValue;
            $newValue = 0;
            global $timeloslot;
            $timeloslot = 0;
            //dd($ant,$ant->Ai[$j],$this->problema->eventos[$j]->espaciosComun,$this->eventos);
            //for (int k = 0; k < indice; k++) {
            // if ($this->problema->eventos[$j]->espaciosComun . contains(ant . Ai . get(j))) {
            if (in_array($ant->Ai[$j], $this->problema->eventos[$j]->espaciosComun)) {
                $encontrado = true;
                //break;
            } else {
                $encontrado = false;
                $timeloslot = $ant->Ai[$j];
                //System.out.println("no esta asignado en ese espacio de tiempo "+timeloslot );
            }
            //}
            if ($encontrado == false) {
                // $newValue = $ant->Vi[$timeloslot] + 1;
                $newValue = $ant->Vi[$timeloslot] + 1;
                $ant->SetViolaciones($timeloslot, $newValue);
            }
        }

        for ($i = 0; $i < sizeof($ant->Vi); $i++) {
            $count += $ant->Vi[$i];
        }
        $ant->seTcantidadDeViolaciones($count);
    }

    public function penalizarEmpalmeMaestro($ant)
    {
        global $count;
        $count = 0;
        global $contadorMaestroEmpalme;
        $ContadorMaestroEmpalme = 0;
        global $contadorEspacioDeTiempo;
        $contadorEspacioDeTiempo = 0;
        // List<String> maestros = new ArrayList<String>();
        global $maestros;
        $maestros = [];

        global $testerSizeOf;
        // for (int i = 0; i < timeslot; i++) {
        for ($i = 0; $i < $this->timeslot; $i++) {
            // eventosProgramados.clear();
            // unset($this->eventosProgramados);
            $this->eventosProgramados = array();
            unset($maestros);
            //Agrego los proyectos asignaods a un espacio de tiempo en especifico
            // for (int j = 0; j < ant.Ai.size(); j++) {
            for ($j = 0; $j < sizeof($ant->Ai); $j++) {
                // if (ant.Ai.get(j).equals(i)) {
                if ($ant->Ai[$j] == $i) {
                    // dd("indice i",$i,"evento programado ",$ant->Ai[$j]);
                    //los agrega a la lista de eventos programados para su uso posterior de analizar si hay dos maestros iguales en ese mismo espacio de tiempo

                    $this->eventosProgramados[] = $ant->problema->eventos[$j];
                }
            }
            // dd($this->eventosProgramados);
            // dd($this->eventosProgramados);
            // dd($this->eventosProgramados,"sizeoF",sizeof($this->eventosProgramados));
            //Si hay mas de dos eventos en un espacio de tiempo analiza el empalme, porque si es solo uno pues jamás habra empalme
            // if (eventosProgramados.size() > 1) {
            // $testerSizeOf=sizeof($this->eventosProgramados);
            // dd($testerSizeOf);
            if (sizeof($this->eventosProgramados) > 1) {
                // dd("Paso",$this->eventosProgramados,$ant,$i);
                $contadorEspacioDeTiempo = 0;
                //Verifica si los eventos programados en n¿un mismio espacio de tiempo tienen espacios en comun, entre los 3
                // for (int l = 0; l < eventosProgramados.size(); l++) {
                for ($l = 0; $l < sizeof($this->eventosProgramados); $l++) {
                    //System.out.println("evento en comun en el espacio de tiempo " + i + ": " + eventosProgramados.get(l).name + ", " + eventosProgramados.get(l).espaciosComun);
                    // if (eventosProgramados.get(l).espaciosComun.contains(i)) {
                    if (in_array($i, $this->eventosProgramados[$l]->espaciosComun)) {
                        // if (in_array($maestro->nombre, $evento->maestros))
                        $contadorEspacioDeTiempo++;
                        //break;                        
                    }
                    //System.out.println("ESpacios de tiempo contador " + contadorEspacioDeTiempo);
                }
                $ContadorMaestroEmpalme = 0;
                for ($l = 0; $l < sizeof($this->eventosProgramados); $l++) {
                    for ($m = $l + 1; $m < sizeof($this->eventosProgramados); $m++) {
                        for ($n = 0; $n < sizeof($this->eventosProgramados[$l]->maestroList); $n++) {
                            for ($o = 0; $o < sizeof($this->eventosProgramados[$m]->maestroList); $o++) {
                                if ($this->eventosProgramados[$l]->maestroList[$n]->nombre == $this->eventosProgramados[$m]->maestroList[$o]->nombre) {
                                    $ContadorMaestroEmpalme++;
                                    $n = $o = sizeof($this->eventosProgramados[$m]->maestroList) + 1;
                                }
                            }
                        }
                    }
                }
                $ant->seTcantidadDeViolacionesDuras($i, $ContadorMaestroEmpalme);
            } else {
                $ant->seTcantidadDeViolacionesDuras($i, 0);
            }
        }
        foreach ($ant->ViolacionesDuras as $j) {
            $count += $j;
        }
        $ant->setIntViolacionesDuras($count);
    }


    public function penalizarMaestro($ant, $evento, $timeslot)
    {
        global $count;
        $count = 0;
        global $encontrado;
        $encontrado = false;
        global $newValue;
        $newValue = 0;
        $indice = $ant->problema->eventos[$evento]->sizeComun;
        for ($i = 0; $i < $indice; $i++) {
            // dd($ant,"evento", $ant->problema->eventos[$evento]->espaciosComun[5]);
            // dd("timeslot",$timeslot,"evento",$ant->problema->eventos[$evento]->espaciosComun[$i]);
            if ($ant->problema->eventos[$evento]->espaciosComun[$i] == $timeslot) {
                $encontrado = true;
            }
        }
        if (!$encontrado) {
            // newValue = ant.Vi.get(timeslot) + 1;            
            $newValue = $ant->Vi[$timeslot] + 1;
            $ant->SetViolaciones($timeslot, $newValue);
        }
        foreach ($ant->Vi as $j) {
            $count += $j;
        }
        $ant->seTcantidadDeViolaciones($count);
        //Tal vez aqui podria contar las violaciones suaves y no en hormiga local
        //System.out.println("Violaciones suaves (int) " + ants.get(ant).violaciones);
    }

    public function penalizar($ant)
    {
        // dd($ant);
        global $contadorTime;
        for ($i = 0; $i < sizeof($ant->Ai); $i++) {
            for ($j = 0; $j < sizeof($ant->Ai); $j++) {
                // if (ants.get(ant).Ai.get(i).equals(ants.get(ant).Ai.get(j))) {
                if ($ant->Ai[$i] == $ant->Ai[$j]) {
                    $contadorTime += 1;
                }
                if ($contadorTime >= $this->salones) {
                    // ants.get(ant).cListAlready.set(ants.get(ant).Ai.get(i), true);
                    $ant->cListAlready[$ant->Ai[$i]] = true; //.set(ants.get(ant).Ai.get(i), true);
                    // dd("aqui se ve el true ",$ant->cListAlready,"hormigas",$this->ants);
                }
                if ($contadorTime > $this->salones) {
                    // int newValue = contadorTime - salones;
                    $newValue = $contadorTime - $this->salones;
                    // ants.get(ant).Vi.set(ants.get(ant).Ai.get(i), newValue);
                    $ant->Vi[$ant->Ai[$i]] = $newValue; //.set(ants.get(ant).Ai.get(i), newValue);
                }
            }
            $contadorTime = 0;
        }
    }
    public function CalcularProbabilidades($evento, $ant)
    {
        // dd($this->matrizPheromoneT);
        $this->pheromone = 0.0;
        //cListUpdate(cList, cListAlready);
        for ($l = 0; $l < sizeof($this->cList); $l++) {
            // Ni_et.set(l, (1 / (1.0 + ants.get(ant).Vi.get(l))));
            $this->Ni_et[$l] = (1 / (1.0 + $ant->Vi[$l]));
        }
        // evitar receso CalcularProbabilidades
        if ($this->breaks != null) {
            foreach ($this->breaks as $break) {
                // dd($this->receso);            
                $this->Ni_et[$break] = 0.0;
                // $this->probabilidad[$break->posicion] = 0.0; //.set(l, 0.0);                             
            }
        }
        // dd($this->Ni_et);
        //        System.out.println("Valor heuristico: " + Ni_et);
        //        System.out.println("Violacion : " + Vi_et[ant]);
        for ($l = 0; $l < sizeof($this->cList); $l++) {
            // if (!ants.get(ant).cListAlready.get(l)) {
            if (!$ant->cListAlready[$l]) {
                // dd($ant->cListAlready[$l]);
                // dd($this->Ni_et[$l]);
                $this->pheromone += pow($this->matrizPheromoneT[$evento][$l], $this->alpha) * pow($this->Ni_et[$l], $this->beta);
            }
        }
        // dd($this->matrizPheromoneT);
        // for (int l = 0; l < cList.size(); l++) {
        for ($l = 0; $l < sizeof($this->cList); $l++) {
            if ($ant->cListAlready[$l]) {
                $this->probabilidad[$l] = 0.0; //.set(l, 0.0);                                
            } else {
                $numerador = pow($this->matrizPheromoneT[$evento][$l], $this->alpha) * pow($this->Ni_et[$l], $this->beta);
                $this->probabilidad[$l] = $numerador / $this->pheromone; //.set(l, (numerador / pheromone));
            }
        }
        // if($evento==21){
        //     dd($this->Ni_et,$this->matrizPheromoneT,$ant,$this->probabilidad,$this->pheromone);
        // }
        // dd($this->probabilidad);        
        // dd($this->probabilidad);
        //System.out.println("Probabilidad: " + probabilidad);
    }


    public function reset()
    {
        // for (int i = 0; i < ants.size(); i++) {
        foreach ($this->ants as $ant) {
            // $ant->Ai ();            
            unset($ant->Ai);
            for ($j = 0; $j < sizeof($this->cList); $j++) {
                // $ant->Ai [$j] =null;
                $ant->cListAlready[$j] = false; //.set(j, false);
                $this->probabilidad[$j] = 0.0;
            }
            foreach ($this->breaks as $break) {
                // dd($this->receso);
                $ant->cListAlready[$break] = true;
                $this->Ni_et[$break] = 0.0;
                // $this->probabilidad[$break->posicion] = 0.0; //.set(l, 0.0);                             
            }
        }
        // dd($this->ants);
    }

    public function mejorHormigaLocal()
    {
        global $recorrido;
        $recorrido = 0.0;
        global $count;
        $count = 0;
        global $menor;
        global $current;
        //int countHard = 0;        
        $menor = $this->ants[0]->violaciones;

        for ($i = 0; $i < sizeof($this->ants); $i++) {
            $count = $this->ants[$i]->violaciones;
            if ($count <= $menor) {
                $menor = $count;
                // $current = i;
                $this->currentLocalBest = $this->ants[$i];
            }
        }
        $recorrido = $this->q / (1 + $menor);
        // dd($this->q,"menor",$menor,"recorrido",$recorrido);        
        $this->currentLocalBest->setRecorrido($recorrido);
        // dd($menor, "hormigaas", $this->ants,"mejor local",$this->currentLocalBest,"recorrido",$recorrido,"q",$this->q);        
        // dd($this->currentLocalBest);
        // dd($this->ants,$this->probabilidad,$this->currentLocalBest);
    }
}
