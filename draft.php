<?php

    // geting json data redy
    $json_file = file_get_contents("Statistic.json");
    $champions_data = json_decode($json_file, true);
    // ----------------------

    class Champion
    {
        public $name;
        public $winrate;
        public $synergy;
        public $counters;
        public $countered_by;
        public $role;
        public $adap;
        public $score;

        public function __construct($name, $winrate, $synergy, $counters, $countered_by, $role, $adap)
        {
            $this->name = $name;
            $this->winrate = $winrate;
            $this->synergy = $synergy;
            $this->counters = $counters;
            $this->countered_by = $countered_by;
            $this->role = $role;
            $this->adORap = $adap;
            $this->score = floatval($this->winrate);
        }
    }
    
    // Tworzenie championów
    for ($i = 0; $i < count($champions_data); $i++)
    {
        $champions[$i] = new Champion(
            $champions_data[$i]["name"],
            $champions_data[$i]["winrate"],
            $champions_data[$i]["counter_1"],
            $champions_data[$i]["counter_2"],
            $champions_data[$i]["counter_3"],
            $champions_data[$i]["role"],
            $champions_data[$i]["adap"],
        );
    }

    function picked_roles($picked, $champions)
    {
        $roles = [];
        foreach($picked as $i)
        {
            foreach($champions as $champ)
            {
                if($champ->name == $i)
                {
                    array_push($roles, $champ->role);
                }
            }
        }
        return $roles;
    }


    function picking($champions ,$picked_by_enemies, $picked){

        // zmienne do edycji
        $ile_postaci_pokazuje = 4;

        // zmienne
        $roles = picked_roles($picked, $champions);
        $chosed = [];
        $names = [];
        $adap = 0;
        $score = 100;
        $how_many_counters = 0;
        $is_counter_picked = false;

        //for($i = 0; $i < $ile_postaci_pokazuje; $i++)
        while($score >= 52.5)
        {
            $highest_score = 0;
            foreach($champions as $champ)
            {
                // sprawdzam czy champion jest wolny
                if(!in_array($champ->role, $roles) && !in_array($champ->name, $picked) && !in_array($champ->name, $picked_by_enemies) && !in_array($champ->name, $names))
                {
                    $champ->score = floatval($champ->winrate);
                    // jeżeli team adap >|2| zmniejszam score jeżeli >|3| dodatkowo
                    if($champ->adap + $adap >= 2 || $champ->adap + $adap <= -2)
                    {
                        $champ->score = $champ->score - 3;
                    }
                    if($champ->adap + $adap >= 3 || $champ->adap + $adap <= -3)
                    {
                        $champ->score = $champ->score - 5;
                    }
                    // jeżeli kontuje przeciwnika score +3 dodatkowo jeśli synergia +3 jeśli przeciwnik kontruje score -7 
                    if(in_array($champ->counters, $picked_by_enemies))
                    {
                        $champ->score += 3;
                    }
                    if(in_array($champ->countered_by, $picked_by_enemies))
                    {
                        $champ->score = $champ->score - 7;
                    }
                    if(in_array($champ->synergy, $picked))
                    {
                        $champ->score += 3;
                    }
                    if($champ->score > $highest_score){
                        $highest_score = $champ->score;
                        $ktory = $champ;
                    }
                }
            }
            array_push($names, $ktory->name);
            array_push($chosed, $ktory);
            $score = $ktory->score;
        }

        foreach($chosed as $i)
        {
            echo $i->name." ".$i->score." winrate ".$i->winrate."<br>";
        }
    }
?>