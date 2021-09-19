<?php

    // geting json data redy
    $json_file = file_get_contents("champions_statistics.json");
    $champions_data = json_decode($json_file, true);
    // ----------------------

    

    class Champion
    {
        public $name;
        public $winrate;
        public $synergy;
        public $synergy_val;
        public $counters;
        public $counters_val;
        public $counter;
        public $counter_val;
        public $role;
        public $adap;
        public $score;
        public $counters2 = [];
        public $synergy2 = [];

        public function __construct($name, $winrate, $synergy, $synergy_val, $counters, $counters_val, $countered_by, $countered_by_val, $role, $adap)
        {
            $this->name = $name;
            $this->winrate = $winrate*100;
            $this->synergy = $synergy;
            $this->synergy_val = $synergy_val;
            $this->counters = $counters;
            $this->counters_val = $counters_val;
            $this->counter = $countered_by;
            $this->counter_val = $countered_by_val;
            $this->role = $role[0];
            $this->adap = $adap;
            $this->score = floatval($this->winrate);
        }
    }
    
    // Tworzenie championów
    for ($i = 0; $i < count($champions_data); $i++)
    {
        $champions[$i] = new Champion(
            $champions_data[$i]["name"],
            $champions_data[$i]["winrate"],
            $champions_data[$i]["synergy"],
            $champions_data[$i]["synergy_val"],
            $champions_data[$i]["counters"],
            $champions_data[$i]["counters_val"],
            $champions_data[$i]["counter"], 
            $champions_data[$i]["counter_val"], 
            $champions_data[$i]["role"],
            $champions_data[$i]["adap"],
        );
    }

    function enemie_countered_by($picked_by_enemies, $champions)
    {
        $enemie_countered_by = [];

        foreach ($picked_by_enemies as $picked)
        {
            foreach ($champions as $champ)
            {
                if ($champ->name == $picked)
                {
                    $counter = [$champ->counter, $champ->counter_val, $picked];
                    array_push($enemie_countered_by, $counter);
                }
            }
        }
        return $enemie_countered_by;
    }


    function enemie_counter($picked_by_enemies, $champions)
    {
        $enemie_counter = [];

        foreach ($picked_by_enemies as $picked)
        {
            foreach ($champions as $champ)
            {
                if ($champ->name == $picked)
                {
                    $counters = [$champ->counters, $champ->counters_val, $picked];
                    array_push($enemie_counter, $counters);
                }
            }
        }
        return $enemie_counter;
    }


    function ally_synergy($picked, $champions)
    {
        $ally_synergy = [];

        foreach ($picked as $picked)
        {
            foreach ($champions as $champ)
            {
                if ($champ->name == $picked)
                {
                    $synergy = [$champ->synergy, $champ->synergy_val, $picked];
                    array_push($ally_synergy, $synergy);
                }
            }
        }
        return $ally_synergy;
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

    function picked_adap($picked, $champions)
    {
        $adap = 0;
        foreach($picked as $i)
        {
            foreach($champions as $champ)
            {
                if($champ->name == $i)
                {
                    $adap += $champ->adap;
                }
            }
        }
        return $adap;
    }

    function picked_role($picked, $champions, $num)
    {

        foreach($champions as $champ)
        {
            if($champ->name == $picked)
            {
                switch($champ->role)
                {
                    case 1:
                        $role = "TOP";
                        break;
                    case 2:
                        $role = "JUNGLER";
                        break;
                    case 3:
                        $role = "MID";
                        break;
                    case 4:
                        $role = "ADC";
                        break;
                    case 5:
                        $role = "SUPP";
                        break;
                }
                return $role;
            }
        }
        return "Summoner ".$num;
    }

    function picked_dmg($picked, $champions)
    {
        foreach($picked as $i)
        {
            if ($i != "")
            {
                foreach($champions as $champ)
                {
                    if($champ->name == $i)
                    {
                        if($champ->adap == -1)
                        {
                            echo "<span class='blue'> &#9632 </span>";
                        }
                        else if($champ->adap == 1)
                        {
                            echo "<span class='orange'> &#9632 </span>";
                        }
                        else
                        {
                            echo "&#9632";
                        }
                    }
                }
            }
            else
            {
                echo "&#9632";
            }
        }
    }
    

    function how_many_picked($picked)
    {
        $i = 0;
        foreach($picked as $pick)
        {
            if($pick != "")
            {
                $i++;
            }
        }
        return $i;
    }

    function picking($champions ,$picked_by_enemies, $picked){

        // zmienne do edycji
        $ile_postaci_pokazuje = 8;
        $ile_warta_kontra = 5;
        $ile_warta_synergia = 3;

        // zmienne
        $roles = picked_roles($picked, $champions);
        $adap = picked_adap($picked, $champions);
        $names = [];
        $chosed = [];
        $score = 100;
        $how_many_counters = 0;
        $is_counter_picked = false;

        $enemie_countered_by = enemie_countered_by($picked_by_enemies, $champions);
        $enemie_counter = enemie_counter($picked_by_enemies, $champions);
        $ally_synergy = ally_synergy($picked, $champions);


        for($i = 0; $i < $ile_postaci_pokazuje; $i++)
        //while($score >= 52.5)
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
                    // jeżeli kontuje przeciwnika score + dodatkowo jeśli synergia + jeśli przeciwnik kontruje score -
                    if(in_array($champ->counters, $picked_by_enemies))
                    {
                        $champ->score += $champ->counters_val;
                    }
                    if(in_array($champ->counter, $picked_by_enemies))
                    {
                        $champ->score = $champ->score - $champ->counter_val;
                    }
                    if(in_array($champ->synergy, $picked))
                    {
                        $champ->score += $champ->synergy_val;
                    }


                    // sprawdzanie dodatkowych kontr oraz dodatkowych przeciwnikow
                    foreach($enemie_countered_by as $counter)
                    {
                        if($champ->name == $counter[0] && $champ->counters !== $counter[2])
                        {
                            $champ->score += $counter[1];
                            // tymaczasowa naprawa błędu z powtarzaniem się nazwy postaci
                            if(!in_array($counter[2], $champ->counters2))
                            {
                                array_push($champ->counters2, $counter[2]);
                            }
                        }
                    }

                    foreach($enemie_counter as $counter)
                    {
                        if($champ->name == $counter[0] && $champ->counters !== $counter[2])
                        {
                            $champ->score = $champ->score -  $counter[1];
                        }
                    }

                    foreach($ally_synergy as $synergy)
                    {
                        if($champ->name == $synergy[0] && $champ->synergy !== $synergy[2])
                        {
                            $champ->score += $synergy[1];
                            // tymaczasowa naprawa błędu z powtarzaniem się nazwy postaci
                            if(!in_array($synergy[2], $champ->synergy2))
                            {
                                array_push($champ->synergy2, $synergy[2]);
                            }
                        }
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
            echo "<p><b>";
            echo $i->name."</b> - ";
            echo "<span class='winrate'> Winrate: <b>".$i->winrate." </b></span> | ";
            echo "<span class='score'>Score: <b>".$i->score." </b></span>";



            if (in_array($i->counters, $picked_by_enemies))
            {
                echo "<span class='red'> | Kontruje: <b>".$i->counters."</b></span>";
            }

            foreach($i->counters2 as $counters)
            {
                if (in_array($counters, $picked_by_enemies))
                {
                    echo "<span class='red'> | Kontruje: <b>".$counters."</b></span>";
                }
            } 

            if (in_array($i->synergy, $picked))
            {
                echo "<span class='green'> | Synergia: <b>".$i->synergy."</b></span>";
            }

            foreach($i->synergy2 as $synergy)
            {
                if (in_array($synergy, $picked))
                {
                    echo "<span class='green'> | Synergia: <b>".$synergy."</b></span>";
                }
            } 


            switch($i->role)
            {
                case 1:
                    $role = "TOP";
                    break;
                case 2:
                    $role = "JUNGLER";
                    break;
                case 3:
                    $role = "MID";
                    break;
                case 4:
                    $role = "ADC";
                    break;
                case 5:
                    $role = "SUPP";
                    break;
            }
            switch($i->adap)
            {
                case -1:
                    $this_adap = "<span class='ap'> AP </span>";
                    break;
                case 0:
                    $this_adap = "NONE";
                    break;
                case 1:
                    $this_adap = "<span class='ad'> AD </span>";
                    break;
            }
            echo "<span class='role'> | Role: <b>".$role." </b></span>";
            echo "<span class='adap'> | DMG_type: <b>".$this_adap." </b></span>";
            echo "</p>";
        }
    }
?>