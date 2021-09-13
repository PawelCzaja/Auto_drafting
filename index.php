<html>
    <head>
        <title>Drafting</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="style.css">
        <?php 
            include 'draft.php';

            $picked_by_enemies = [];
            @array_push($picked_by_enemies, $_COOKIE["red_1"]);
            @array_push($picked_by_enemies, $_COOKIE["red_2"]);
            @array_push($picked_by_enemies, $_COOKIE["red_3"]);
            @array_push($picked_by_enemies, $_COOKIE["red_4"]);
            @array_push($picked_by_enemies, $_COOKIE["red_5"]);

            $picked = [];
            @array_push($picked, $_COOKIE["blue_1"]);
            @array_push($picked, $_COOKIE["blue_2"]);
            @array_push($picked, $_COOKIE["blue_3"]);
            @array_push($picked, $_COOKIE["blue_4"]);
            @array_push($picked, $_COOKIE["blue_5"]);

            $ile_picked = how_many_picked($picked);

            // Making and delating cookies
            if(isset($_GET["red_1"])){
                setcookie("red_1" , $_GET["red_1"]);
            }
            else
            {
                setcookie("red_1", "", time() - 3600);
            }
            // -----------------------------------
            if(isset($_GET["red_2"])){
                setcookie("red_2", $_GET["red_2"]);
            }
            else
            {
                setcookie("red_2", "", time() - 3600);
            }
            // -----------------------------------
            if(isset($_GET["red_3"])){
                setcookie("red_3" , $_GET["red_3"]);
            }
            else
            {
                setcookie("red_3", "", time() - 3600);
            }
            // -----------------------------------
            if(isset($_GET["red_4"])){
                setcookie("red_4" , $_GET["red_4"]);
            }
            else
            {
                setcookie("red_4", "", time() - 3600);
            }
            // -----------------------------------
            if(isset($_GET["red_5"])){
                setcookie("red_5" , $_GET["red_5"]);
            }
            else
            {
                setcookie("red_5", "", time() - 3600);
            }
            // -----------------------------------
            if(isset($_GET["blue_1"])){
                setcookie("blue_1" , $_GET["blue_1"]);
            }
            else
            {
                setcookie("blue_1", "", time() - 3600);
            }
            // -----------------------------------
            if(isset($_GET["blue_2"])){
                setcookie("blue_2" , $_GET["blue_2"]);
            }
            else
            {
                setcookie("blue_2", "", time() - 3600);
            }
            // -----------------------------------
            if(isset($_GET["blue_3"])){
                setcookie("blue_3" , $_GET["blue_3"]);
            }
            else
            {
                setcookie("blue_3", "", time() - 3600);
            }
            // -----------------------------------
            if(isset($_GET["blue_4"])){
                setcookie("blue_4" , $_GET["blue_4"]);
            }
            else
            {
                setcookie("blue_4", "", time() - 3600);
            }
            // -----------------------------------
            if(isset($_GET["blue_5"])){
                setcookie("blue_5" , $_GET["blue_5"]);
            }
            else
            {
                setcookie("blue_5", "", time() - 3600);
            }
            // -----------------------------------

            if(isset($_GET["subbmit"]))
            {
                header("location:index.php");
            }
        ?>
    </head>
    <body>
        <header>
            <h1>
                <strong>League of Legends</strong>
            </h1>
            <h2>
                Drafting program
            </h2>
        </header>
        <div id="main">
            <div id="left_picks" class="picks">
                <h2>Blue side</h2>
                <form id="form1" name="form1" method="GET" action="index.php">
                    <input type="text" name="blue_1" value="<?php label('blue_1') ?>"><label><?php echo picked_role($picked[0], $champions, 1) ?></label><br>
                    <input type="text" name="blue_2" value="<?php label('blue_2') ?>"><label><?php echo picked_role($picked[1], $champions, 2) ?></label><br>
                    <input type="text" name="blue_3" value="<?php label('blue_3') ?>"><label><?php echo picked_role($picked[2], $champions, 3) ?></label><br>
                    <input type="text" name="blue_4" value="<?php label('blue_4') ?>"><label><?php echo picked_role($picked[3], $champions, 4) ?></label><br>
                    <input type="text" name="blue_5" value="<?php label('blue_5') ?>"><label><?php echo picked_role($picked[4], $champions, 5) ?></label><br>
                </form>
                <br>
                <p>
                    DMG type | 
                    <?php picked_dmg($picked, $champions) ?>
                </p>
            </div>
            <div id="propositions">
                <div>
                    <?php
                        if($ile_picked < 5)
                        {
                            picking($champions, $picked_by_enemies, $picked); 
                        }
                    ?>
                </div>
                <input form ="form1" type="submit" name="subbmit">
            </div>
            <div id="right_picks" class="picks">
                <h2>Red side</h2>
                <div>
                    <form id="form2" name="form1" method="GET" action="index.php">
                        <label><?php echo picked_role($picked_by_enemies[0], $champions, 1) ?></label><input form ="form1" type="text" name="red_1" value="<?php label('red_1') ?>"><br>
                        <label><?php echo picked_role($picked_by_enemies[1], $champions, 2) ?></label><input form ="form1" type="text" name="red_2" value="<?php label('red_2') ?>"><br>
                        <label><?php echo picked_role($picked_by_enemies[2], $champions, 3) ?></label><input form ="form1" type="text" name="red_3" value="<?php label('red_3') ?>"><br>
                        <label><?php echo picked_role($picked_by_enemies[3], $champions, 4) ?></label><input form ="form1" type="text" name="red_4" value="<?php label('red_4') ?>"><br>
                        <label><?php echo picked_role($picked_by_enemies[4], $champions, 5) ?></label><input form ="form1" type="text" name="red_5" value="<?php label('red_5') ?>"><br>
                    </form>
                </div>
                <br>
                <p> 
                    <?php picked_dmg($picked_by_enemies, $champions) ?>
                    | DMG type
                </p>
            </div>
        </div>
    </body>
    <?php

        function label($whith)
        {
            if(isset($_COOKIE[$whith]))
            {
                echo $_COOKIE[$whith];
            }
        }
        

    ?>
</html>