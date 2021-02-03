<?php
//disable display notice

error_reporting(E_ALL & ~E_NOTICE);
require('./Entities/Main.php');

use Entities\Main;
use Entities\Main\Song;

// songs
$imagine = ['c', 'cmaj7', 'f', 'am', 'dm', 'g', 'e7'];
$somewhere_over_the_rainbow = ['c', 'em', 'f', 'g', 'am'];
$tooManyCooks = ['c', 'g', 'f'];
$iWillFollowYouIntoTheDark = ['f', 'dm', 'bb', 'c', 'a', 'bbm'];
$babyOneMoreTime = ['cm', 'g', 'bb', 'eb', 'fm', 'ab'];
$creep = ['g', 'gsus4', 'b', 'bsus4', 'c', 'cmsus4', 'cm6'];
$army = ['ab', 'ebm7', 'dbadd9', 'fm7', 'bbm', 'abmaj7', 'ebm'];
$paperBag = ['bm7', 'e', 'c', 'g', 'b7', 'f', 'em', 'a', 'cmaj7', 'em7', 'a7', 'f7', 'b'];
$toxic = ['cm', 'eb', 'g', 'cdim', 'eb7', 'd7', 'db7', 'ab', 'gmaj7', 'g7'];
$bulletproof = ['d#m', 'g#', 'b', 'f#', 'g#m', 'c#'];
$song_11 = [];
$songs = [];
$labels = [];
$allChords = [];
$labelCounts = [];
$labelProbabilities = [];
$chordCountsInLabels = [];
$probabilityOfChordsInLabels = [];

/**
 * 為了讓全域函式可以正常運行，用function實做singleton
 * 讓client使用全域函式結果與原先一致。
 */
function getLegacyMainInstance() : Main{
    static $main = null;
    if(!$main){
        $main = new Main();
    }
    return $main;
}

/**
 * 新增歌曲
 */
function train($chords, $label)
{
    $main = getLegacyMainInstance();
    $main->addSong($chords,$label);
    global $songs;
    $songs = array_map(function(Song $song){
        return $song->toArray();
    },$main->getSongs());
    global $labelCounts;
    $labelCounts = $main->getLabelToCountTable();
    global $allChords;
    $allChords = $main->getAllUniqueChords();
    global $label;
    $label = array_map(function(Song $song){
        return $song->getLabel();
    },$main->getSongs());
}

/**
 * 歌曲數目
 */
function getNumberOfSongs()
{
    return getLegacyMainInstance()->getSongsCount();
}

/**
 * 計算每個label的機率
 */
function setLabelProbabilities()
{
    global $labelProbabilities;
    $labelProbabilities = getLegacyMainInstance()->getLabelInSongsProbabilities();
}

/**
 * 計算每個label的歌曲中有多少chord
 */
function setChordCountsInLabels()
{
    global $chordCountsInLabels;
    $chordCountsInLabels = getLegacyMainInstance()->getLabelToChordsCountTable();
}

/**
 * 計算各label當中每個chord在所有歌曲中出現的機率
 */
function setProbabilityOfChordsInLabels()
{
    global $probabilityOfChordsInLabels;
    $probabilityOfChordsInLabels = getLegacyMainInstance()->getChordsInLabelProbability();
}

train($imagine, 'easy');
train($somewhere_over_the_rainbow, 'easy');
train($tooManyCooks, 'easy');
train($iWillFollowYouIntoTheDark, 'medium');
train($babyOneMoreTime, 'medium');
train($creep, 'medium');
train($paperBag, 'hard');
train($toxic, 'hard');
train($bulletproof, 'hard');

setLabelProbabilities();
setChordCountsInLabels();
setProbabilityOfChordsInLabels();

function classify($chords){
    global $labelProbabilities,$probabilityOfChordsInLabels;
    print_r($labelProbabilities);
    $classified = getLegacyMainInstance()->classify($chords,$probabilityOfChordsInLabels);
    print_r($classified);
}

classify(['d', 'g', 'e', 'dm']);
classify(['f#m7', 'a', 'dadd9', 'dmaj7', 'bm', 'bm7', 'd', 'f#m']);