<h1>Thresh Example</h1>

This is an example project for the [Thresh](https://github.com/Petersil1998/Thresh) library.

Main file: public/index.php (uses **Twig** as templating engine)

public/summoner.php and public/game.php are pure **PHP** and **HTML** examples

In order to use the **Thresh** library you have to have this code in your main PHP file:

Use `EncryptionUtils::encrypt('<your_api_key>')` to encrypt your RIOT API KEY which you can get [here](https://developer.riotgames.com)<br>
Use `Config::setApiKey($encrypted_api_key)` to set your api key for the application (this key will be used for the Riot API calls)<br>
Use `Config::setRegion(<region>)` to set and update the region that the library uses for API calls (full list of Regions can be found [here](https://github.com/Petersil1998/Thresh/blob/master/src/Constants/Regions.php) )<br>
Use `Config::setPlatform(<platform>)` to set and update the platform that the library uses for API calls (full list of Platforms can be found [here](https://github.com/Petersil1998/Thresh/blob/master/src/Constants/Platforms.php) )<br>
Use `Loader::init()` to initialize the Application (It automatically updates Champions, Runes, Maps, QueueTypes, etc... when there is a new version available)
