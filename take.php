<?php
require_once __DIR__ . '/vendor/autoload.php';

require_once "state/PlayerStats.php";
require_once "state/Clanwars.php";
require_once "state/ClanStats.php";

$playerstats = new PlayerStatsAccumulator();
$clanwars = new ClanwarsAccumulator();
$clanstats = new ClanStatsAccumulator();

$reference = new ActionFPS\GamesCachedActionReference();
$proc = new ActionFPS\Processor();

$playerstats_state = new ActionFPS\BasicStateResult([], []);
$clanwars_state = new ActionFPS\BasicStateResult([], []);
$clanstats_state = new ActionFPS\BasicStateResult([], []);

$playerstats_state->loadFromFile("data/playerstats.json");
$clanwars_state->loadFromFile("data/clanwars.json");
$clanstats_state->loadFromFile("data/clanstats.json");

$proc->processNewGames($reference, $playerstats, $playerstats_state)->saveToFile("data/playerstats.json");
$clanwars_state = $proc->processNewGames($reference, $clanwars, $clanwars_state);
$clanwars_state->saveToFile("data/clanwars.json");

$state = $clanwars_state->getState();
$wars = $state->completed;
$proc->processFromScratch($reference, $clanstats, $wars)->saveToFile("data/clanstats.json");
?>
