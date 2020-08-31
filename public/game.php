<?php
    require_once '../vendor/autoload.php';

    use Thresh\Constants\Platforms;
    use Thresh\Constants\Regions;
    use Thresh\Entities\ActiveGame\ActiveGame;
    use Thresh\Entities\Summoner\Summoner;
    use Thresh\Helper\Config;
    use Thresh\Helper\EncryptionUtils;
    use Thresh\Helper\Loader;
    use Thresh\Helper\Utils;

    $encrypted_api_key = EncryptionUtils::encrypt('');
    Config::setApiKey($encrypted_api_key);
    Config::setRegion(Regions::EUROPE);
    Config::setPlatform(Platforms::EUW);
    Loader::init();
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title></title>
        <link rel="icon" href="images/favicon.png">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="styles/styles.css">
    </head>
    <body><h1 align="center">League of Legends</h1>
        <?php
        if(isset($_GET["name"])) {
            $summonerName = str_replace(" ", "", $_GET["name"]);
            $summoner = Summoner::getSummonerByName($summonerName);
            $game = new ActiveGame($summoner);
            if ($game->exists()) {
                echo "<p class='game-player-row' style='color: white;'>Map: " . $game->getMap()->getName();
                echo "<br>Game Mode: " . $game->getGameMode();
                echo "<br>Queue: " . $game->getQueueType()->getDescription() . "</p></div>";
                $blueSideTeam = $game->getBlueSideTeam();
                foreach ($blueSideTeam AS $player) {
                    $keyRune = $player->getRunes()[0];
                    $runestat = $player->getRunes()[8];
                    echo "<div><table class='background-blue-team'>";
                    if (strcasecmp(str_replace(" ", "", $player->getSummonername()), str_replace(" ", "", $summonerName)) == 0) {
                        $realName = $player->getSummonername();
                        echo "<tr class='game-player-row background-blue-team-player'>";
                    } else {
                        echo "<tr class='game-player-row'>";
                    }
                    echo "<td style='width=\"250px\"'><a href='summoner.php?name=" . $player->getSummonername() . "'>" . $player->getSummonername() . "</a></td>";
                    echo "<td><img alt='". $player->getChampion()->getName()."' class='small-icon' src='" . Utils::getChampionIconURL($player->getChampion()) . "'> " . $player->getChampion()->getName() . "</td>";
                    echo "<td><img alt='".$keyRune->getName()."' class='small-icon' src='" . Utils::getRuneIconURL($keyRune) . "'>" . $keyRune->getName()."</td>";
                    echo "</tr>";
                }
                echo "</table><p class='game-player-row' style='text-align: center; font-size: 30px; font-weight: bold; color: white; margin-top: 0; margin-bottom: 0;'>VS</p><table class='background-red-team'>";
                $redSideTeam = $game->getRedSideTeam();
                foreach ($redSideTeam AS $player) {
                    $keyRune = $player->getRunes()[0];
                    $runestat = $player->getRunes()[8];
                    echo "<div><table class='background-red-team'>";
                    if (strcasecmp(str_replace(" ", "", $player->getSummonername()), str_replace(" ", "", $summonerName)) == 0) {
                        $realName = $player->getSummonername();
                        echo "<tr class='game-player-row background-red-team-player'>";
                    } else {
                        echo "<tr class='game-player-row'>";
                    }
                    echo "<td style='width=\"250px\"'><a href='summoner.php?name=" . $player->getSummonername() . "'>" . $player->getSummonername() . "</a></td>";
                    echo "<td><img alt='".$player->getChampion()->getName()."' class='small-icon' src='" . Utils::getChampionIconURL($player->getChampion()) . "'> " . $player->getChampion()->getName() . "</td>";
                    echo "<td><img alt='".$keyRune->getName()."' class='small-icon' src='" . Utils::getRuneIconURL($keyRune) . "'>" . $keyRune->getName()."</td>";
                    echo "</tr>";
                }
                echo "</table><button onclick='location.href=\"summoner.php?name=".$_GET["name"]."\";'>Zurück</button>";
            } else {
                echo "<p>No active Game found for this Summoner!</p>";
            }
        }else{
            echo "<p>No Summonername specified!</p>";
        }?>
        <script type="text/javascript">
            document.getElementsByTagName("title")[0].textContent = "<?=$realName?> - LoL Test";
        </script>
    </body>
</html>
