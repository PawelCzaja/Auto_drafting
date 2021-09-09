<?php

    // geting json data redy
    $json_file = file_get_contents("Statistic.json");
    $champions = json_decode($json_file, true);
    // ----------------------


    function picking($champions){

        // zmienne
        $roles = [];
        $picked = ["Sona","Blitzcrank", "Corki", "Ashe", "Kog'Maw"];
        $adap = 0;
        $team = [];


        for($j = 0; $j < 5;$j++){
            $highest_score = 0;

            for($i = 0; $i < count($champions); $i++){

                $name = $champions[$i]["name"];
                $winrate = intval($champions[$i]["winrate"]);
                $role = $champions[$i]["role"];
                $adORap = $champions[$i]["adap"];

                if(!in_array($role, $roles) && !in_array($name, $picked)){
                    if($adap + $adORap >= 2 || $adap + $adORap <= -2){
                        if($adap + $adORap >= 3 || $adap + $adORap <= -3){
                            $score = $winrate - 5;
                        }
                        else{
                            $score = $winrate - 2;
                        }
                    }
                    else{
                        $score = $winrate;
                    }
                    
                    if($score > $highest_score){
                        $highest_score = $score;
                        $ktory = $i;
                    }
                }
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
    }



    picking($champions)
?>