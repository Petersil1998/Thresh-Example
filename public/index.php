<?php
    require_once '../vendor/autoload.php';

    use Thresh\Collections\Champions;
    use Thresh\Collections\Items;
    use Thresh\Collections\Maps;
    use Thresh\Collections\QueueTypes;
    use Thresh\Constants\Constants;
    use Thresh\Constants\Platforms;
    use Thresh\Constants\Regions;
use Thresh\Entities\ActiveGame\ActiveGame;
use Thresh\Entities\Match\MatchDetails;
    use Thresh\Entities\Summoner\Summoner;
    use Thresh\Helper\Config;
    use Thresh\Helper\EncryptionUtils;
    use Thresh\Helper\Loader;
    use Thresh\Helper\Utils;
    use Twig\Loader\FilesystemLoader;
    use Twig\Environment;
    use Twig\TwigFunction;

    $encrypted_api_key = EncryptionUtils::encrypt('');
    Config::setApiKey($encrypted_api_key);
    Config::setRegion(Regions::EUROPE);
    Config::setPlatform(Platforms::EUW);
    Loader::init();

    $loader = new FilesystemLoader('templates');
    $twig = new Environment($loader);
    $function = new TwigFunction('formatNumber', function ($number) {
        return number_format($number, 0, ',', ' ');
    });
    $twig->addFunction($function);
    $function = new TwigFunction('getSprite', function ($sprite) {
        ob_start();
        imagepng(Utils::getPNGFromSprite($sprite));
        $image = ob_get_contents();
        ob_end_clean();
        return base64_encode($image);
    });
    $twig->addFunction($function);
    $function = new TwigFunction('getChampionIconPath', function ($championId) {
        return Utils::getChampionIconURL($championId);
    });
    $twig->addFunction($function);
    $function = new TwigFunction('getProfileIconURL', function ($summoner) {
        return Utils::getProfileIconURL($summoner);
    });
    $twig->addFunction($function);
    $function = new TwigFunction('getRuneIconURL', function ($rune) {
        return Utils::getRuneIconURL($rune);
    });
    $twig->addFunction($function);

    if(!isset($_GET['id'])){
        echo $twig->render('index.twig');
    } else {
        $id = $_GET['id'];
        switch ($id){
            case 0:
            {
                if(empty($_GET['name'])){
                    echo $twig->render('error.twig', array(
                        'errorMessage' => 'Summonername not specified',
                    ));
                }else{
                    $summonerName = str_replace(" ", "", $_GET["name"]);
                    $summoner = Summoner::getSummonerByName($summonerName);
                    if(!$summoner){
                        echo $twig->render('error.twig', array(
                            'errorMessage' => 'Summoner doesn\'t exist',
                        ));
                    } else {
                        $game = new ActiveGame($summoner);
                        echo $twig->render('summoner.twig', array(
                            'summoner'          => $summoner,
                            'game'              => $game,
                        ));
                    }
                }
                break;
            }
            case 1:
            {
                if (empty($_GET['name'])) {
                    echo $twig->render('error.twig', array(
                        'errorMessage' => 'Summonername not specified',
                    ));
                } else {
                    $summonerName = str_replace(" ", "", $_GET["name"]);
                    $summoner = Summoner::getSummonerByName($summonerName);
                    $game = new ActiveGame($summoner);
                    if (!$game->exists()) {
                        echo $twig->render('error.twig', array(
                            'errorMessage' => 'Player isn\'t currently in a game',
                        ));
                    } else {
                        echo $twig->render('game.twig', array(
                            'summonerName'      => $summonerName,
                            'game'              => $game,
                        ));
                    }
                }
                break;
            }
            default: {
                echo "ID: ".$id;
            }
        }
    }
