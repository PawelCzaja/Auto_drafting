<?php

    // geting json data redy
    $json_file = file_get_contents("Statistic.json");
    $champions = json_decode($json_file, true);
    // ----------------------

    function countering_champions($picked_by_enemies, $champions){
        $countering_picks = [];
        for($j = 0; $j < count($picked_by_enemies); $j++){
            for($i = 0; $i < count($champions); $i++){
                if($champions[$i]["name"] == $picked_by_enemies[$j]){
                    array_push($countering_picks, $champions[$i]['counter_3']);
                }
            }
        }
        return $countering_picks;
    }

    function picking($champions){

        // zmienne
        $roles = [];
        $picked = [];
        $picked_by_enemies = ["Sona","Zilean","Nami"];
        $adap = 0;
        $team = [];
        $countering_picks = countering_champions($picked_by_enemies, $champions);
        $how_many_counters = 0;
        $is_counter_picked = false;


        for($j = 0; $j < 5;$j++){
            $highest_score = 0;

            for($i = 0; $i < count($champions); $i++){

                $is_counter = false;
                $name = $champions[$i]["name"];
                $winrate = intval($champions[$i]["winrate"]);
                $role = $champions[$i]["role"];
                $adORap = $champions[$i]["adap"];
                $kontrowany_przez = $champions[$i]["counter_3"];
                $kontruje = $champions[$i]["counter_2"];
                $synergia = $champions[$i]["counter_1"];

                if(!in_array($role, $roles) && !in_array($name, $picked) && !in_array($name, $picked_by_enemies)){
                    if($adap + $adORap >= 2 || $adap + $adORap <= -2){
                        if($adap + $adORap >= 3 || $adap + $adORap <= -3){
                            $score = $winrate - 7;
                        }
                        else{
                            $score = $winrate - 4;
                        }
                    }
                    else{
                        $score = $winrate;
                    }
                    if(in_array($name, $countering_picks) || in_array($kontruje, $picked_by_enemies)){
                        $score += 3;
                        $is_counter = true;
                    }

                    if(in_array($kontrowany_przez, $picked_by_enemies)){
                        $score = $score - 7;
                    }

                    if(in_array($synergia, $picked)){
                        $score += 3;
                    }

                    if($score > $highest_score){
                        $highest_score = $score;
                        $ktory = $i;
                        if($is_counter){
                            $is_counter_picked = true;
                        }
                        else{
                            $is_counter_picked = false;
                        }
                    }
                }
            }
            if($is_counter_picked){
                $how_many_counters += 1;
            }
            array_push($roles, $champions[$ktory]["role"]);
            array_push($picked, $champions[$ktory]["name"]);
            $adap += $champions[$ktory]["adap"];
            array_push($team, $ktory);
        }
        for($i = 0;$i < 5;$i++){
            $pick = $team[$i];
            echo($champions[$pick]["name"]. " ");
        }
        echo("<br> Ile kontr: ".$how_many_counters);
        echo("<br> Ad/Ap ratio: ".$adap);

    }



    picking($champions)
?>