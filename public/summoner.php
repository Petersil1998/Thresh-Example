<?php
    require_once '../vendor/autoload.php';

    use Thresh\Collections\Champions;
    use Thresh\Entities\Match\ActiveGame;
    use Thresh\Entities\Summoner\Summoner;
    use Thresh\Helper\Utils;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

        <title></title>

        <link rel="icon" href="images/favicon.png">
        <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">-->
        <link rel="stylesheet" type="text/css" href="bootstrap-4.3.1-dist/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="bootstrap-4.3.1-dist/css/bootstrap-grid.css">
        <link rel="stylesheet" type="text/css" href="bootstrap-4.3.1-dist/css/bootstrap-reboot.css">
        <link rel="stylesheet" type="text/css" href="styles/styles.css">
    </head>
    <body>

        <h1 align="center">League of Legends</h1>
        <?php
            if(empty($_GET["name"])){
                echo "<p>No Summonername specified</p>";
            } else {
                $summonerName = str_replace(" ", "", $_GET["name"]);
                $summoner = Summoner::getSummonerByName($summonerName);
                if($summoner){
                    echo "<p>Summoner not found</p>";
                } else {
                    echo "<div><div><img alt='Profile Icon' src='" .Utils::getProfileIconURL($summoner)."' style='width: 200px; height: 200px;' class='center'></div>";
                    echo "<div><table style='border: 0; margin-top: 10px'>";
                    echo "<tr><td class='noborder'>Summonername:</td><td class='noborder'>" . $summoner->getSummonername() . "</td></tr>";
                    echo "<tr><td class='noborder'>Level:</td><td class='noborder'>" . $summoner->getSummonerLevel() . "</td></tr>";
                    echo "<tr><td class='noborder'>Masterypoints:</td><td class='noborder'>" . $summoner->getTotalMasteryPoints() . "</td></tr>";
                    echo "<tr><td class='noborder' style='vertical-align: top'>Revision Date:</td><td class='noborder'>" . date("d. F Y G:i:s", $summoner->getRevisionDate()) . "</td></tr>";
                    echo "</table></div></div>";

                    $game = new ActiveGame($summoner);

                    if ($game->exists()) {
                        echo "<a href='game.php?name=" . $summoner->getSummonername() . "'><button>View Live Game</button></a>";
                    }

                    $solo_duo = $summoner->getRankSoloDuo();
                    $flex_5v5 = $summoner->getRankFlex5v5();
                    $tft = $summoner->getRankTft();

                    echo "<p>Ranked Solo/Duo: <img alt='' class='rank-icon' title='".$solo_duo->getTier() . " " . $solo_duo->getRank()."' src='images/Ranks/".strtolower($solo_duo->getTier()).".png'></p>";
                    echo "<p>Ranked Flex 5v5: <img alt='' class='rank-icon' title='".$flex_5v5->getTier() . " " . $flex_5v5->getRank()."' src='images/Ranks/".strtolower($flex_5v5->getTier()).".png'></p>";
                    echo "<p>Ranked TFT: " . $tft->getTier() . " " . $tft->getRank() . "<img class='rank-icon' alt='".$tft->getTier()."' src='images/Ranks/".strtolower($tft->getTier()).".png'></p>";

                    echo "<table class='mastery-list'><tr><th>Champion</th><th>Mastery Level</th><th>Points</th><th>Progress to next level</th><th>Tokens for next Level</th><th>Chest Granted</th></tr>";

                    foreach ($summoner->getChampionMasteries() AS $championMastery) {
                        echo "<tr><td style='border-color: white;'>";
                        echo "<img class='small-icon' src='" . Utils::getChampionIconURL($championMastery->getChampionId()) . "'>";
                        echo Champions::getChampion($championMastery->getChampionId());
                        echo "</td><td style='border-color: white; text-align: center'>" . $championMastery->getChampionLevel() . "</td>";
                        echo "<td style='border-color: white; text-align: center'>" . $championMastery->getChampionPoints() . "</td>";
                        if ($championMastery->getChampionLevel() < 5) {
                            echo "<td style='border-color: white; text-align: center'><progress value=\"" . $championMastery->getChampionPointsSinceLastLevel() . "\" max=\"" . ($championMastery->getChampionPointsUntilNextLevel() + $championMastery->getChampionPointsSinceLastLevel()) . "\"></progress></td>";
                            echo "<td style='border-color: white; text-align: center'></td>";
                        } else {
                            echo "<td style='border-color: white; text-align: center'></td>";
                            echo "<td style='border-color: white; text-align: center'>" . $championMastery->getTokensEarned() . "</td>";
                        }
                        echo "<td style='border-color: white; text-align: center'>";
                        if ($championMastery->isChestGranted() == 1) {
                            echo "<img class='small-icon' alt='yes' src=\"images/yes.png\"></td></tr>";
                        } else
                            echo "<img class='small-icon' alt='yes' src=\"images/no.png\"></td></tr>";
                    }
                    echo "</table>";
                }
            }
        ?>

    </body>
    <script type="text/javascript">
        document.getElementsByTagName("title")[0].textContent = "<?php echo $summoner->getSummonername();?> - LoL Test";
    </script>
</html>
