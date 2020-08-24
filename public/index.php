<?php
    require_once '../vendor/autoload.php';

    use Thresh\Collections\Champions;
    use Thresh\Collections\Maps;
    use Thresh\Collections\QueueTypes;
    use Thresh\Constants\Platforms;
    use Thresh\Constants\Regions;
    use Thresh\Entities\Match\ActiveGame;
    use Thresh\Entities\Summoner\Summoner;
    use Thresh\Helper\Config;
    use Thresh\Helper\EncryptionUtils;
    use Thresh\Helper\Loader;
    use Thresh\Helper\Utils;
    use Twig\Loader\FilesystemLoader;
    use Twig\Environment;
    use Twig\TwigFunction;

    $encrypted_api_key = EncryptionUtils::encrypt('RGAPI-f3744a49-50ae-4b70-b478-cc2d18ecc909');
    Config::setApiKey($encrypted_api_key);
    Config::setRegion(Regions::EUROPE);
    Config::setPlatform(Platforms::EUW);
    Loader::init();

    $loader = new FilesystemLoader('templates');
    $twig = new Environment($loader);
    $function = new TwigFunction('getMap', function ($mapId) {
        return Maps::getMap($mapId);
    });
    $twig->addFunction($function);
    $function = new TwigFunction('getChampion', function ($championId) {
        return Champions::getChampion($championId);
    });
    $twig->addFunction($function);
    $function = new TwigFunction('getQueueType', function ($queueTypeId) {
        return QueueTypes::getQueueType($queueTypeId);
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
                    $summoner = new Summoner($summonerName);
                    if(!$summoner->exists()){
                        echo $twig->render('error.twig', array(
                            'errorMessage' => 'Summoner doesn\'t exist',
                        ));
                    } else {
                        $game = new ActiveGame($summonerName);
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
                    $game = new ActiveGame($summonerName);
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
