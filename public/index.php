<?php
    require_once '../vendor/autoload.php';

    use Thresh\Entities\ActiveGame\ActiveGame;
    use Thresh\Entities\Summoner\Summoner;
    use Thresh\Helper\Config;
    use Thresh\Helper\EncryptionUtils;
    use Thresh_Core\Constants\Platforms;
    use Thresh_Core\Constants\Regions;
    use Thresh_Core\Utils\Loader;
    use Thresh_Core\Utils\Util;
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
        return Util::getBase64EncodedImageFromSprite($sprite);
    });
    $twig->addFunction($function);
    $function = new TwigFunction('getChampionIconPath', function ($championId) {
        return Util::getChampionIconURL($championId);
    });
    $twig->addFunction($function);
    $function = new TwigFunction('getProfileIconURL', function ($profileIconId) {
        return Util::getProfileIconURL($profileIconId);
    });
    $twig->addFunction($function);
    $function = new TwigFunction('getRuneIconURL', function ($rune) {
        return Util::getRuneIconURL($rune);
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
                        $game = new ActiveGame($summoner->getId());
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
                    $game = new ActiveGame($summoner->getId());
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
