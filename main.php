<?php
//disable display notice
error_reporting(E_ALL & ~E_NOTICE);

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

function train($chords, $label)
{
    updateLegacyVariablesWhenTrain($chords,$label);
    global $songs;
    $songs[] = [$label, $chords];
    global $labelCounts;
    $labelCount = $labelCounts[$label] ?? 0;
    $labelCounts[$label] = $labelCount + 1;
}

/**
 * update variables which are updated when train in legacy code.
 */
function updateLegacyVariablesWhenTrain($chords, $label){
    global $allChords;
    $GLOBALS['label'][] = $label;
    foreach($chords as $chord){
        if(empty($allChords) || !in_array($chord,$allChords))
            $allChords[]  = $chord;
    }
}

function getNumberOfSongs()
{
    return count($GLOBALS['songs']);
}

function setLabelProbabilities()
{
    $numberOfSongs = getNumberOfSongs();
    global $labelProbabilities,$labelCounts;
    foreach (array_keys($labelCounts) as $label) {
        $labelProbabilities[$label] = $labelCounts[$label] / $numberOfSongs;
    }
}

function setChordCountsInLabels()
{
    global $chordCountsInLabels;
    foreach ($GLOBALS['songs'] as $song) {
        [$label,$chords] = $song;
        $chordCounters = $chordCountsInLabels[$label] ?? [];
        foreach ($chords as $chord) {
            $beforeCount = $chordCounters[$chord] ?? 0;
            $chordCounters[$chord] = $beforeCount + 1;
        }
        $chordCountsInLabels[$label] = $chordCounters;
    }
}

function setProbabilityOfChordsInLabels()
{
    global $probabilityOfChordsInLabels,$chordCountsInLabels;
    $probabilityOfChordsInLabels = $chordCountsInLabels;

    foreach($probabilityOfChordsInLabels as $label=>&$chords){
        foreach($chords as &$chord){
            $chord *= 1/getNumberOfSongs();
        }
    }
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

const CLASSIFY_PROBABILITIES = 1.01;
function classify($chords){
    global $labelProbabilities,$probabilityOfChordsInLabels;
    print_r($labelProbabilities);
    $classified = [];
    foreach ($labelProbabilities as $label=>$probabilities) {
        $labelClassifyProbabilities = $probabilities + CLASSIFY_PROBABILITIES;
        foreach ($chords as $chord) {
            $probabilityOfChordInLabel = $probabilityOfChordsInLabels[$label][$chord] ?? false;
            if ($probabilityOfChordInLabel) {
                $labelClassifyProbabilities *= ($probabilityOfChordInLabel + CLASSIFY_PROBABILITIES);
            }
            $classified[$label] = $labelClassifyProbabilities;
        }
    }
    print_r($classified);
}

classify(['d', 'g', 'e', 'dm']);
classify(['f#m7', 'a', 'dadd9', 'dmaj7', 'bm', 'bm7', 'd', 'f#m']);