<?php
namespace Entities;
require('Main/Song.php');

use Entities\Main\Song;

class Main{

    /**
     * @var Song[]
     */
    private $songs = [];

    /**
     * 新增歌曲
     */
    function addSong(array $chords, string $label) : Main
    {
        $this->songs[] = new Song($chords,$label);
        return $this;
    }

    public function getSongs():array{
        return $this->songs;
    }

    public function getSongsCount(){
        return count($this->songs);
    }
    
    function getLabelToCountTable():array{
        $labelCounts = [];
        foreach($this->songs as $song){
            $label = $song->getLabel();
            $labelCounter = $labelCounts[$label] ?? 0;
            $labelCounts[$label] = $labelCounter + 1;
        }
        return $labelCounts;
    }

    function getLabelInSongsProbabilities(){
        return array_map(function($labelCount){
            return $labelCount / $this->getSongsCount();
        },$this->getLabelToCountTable());
    }

    /**
     * 回傳所有種類的label
     * @return string[]
     */
    function getAllUniqueLabels(){
        return 
        array_unique(
            array_map(function(Song $song){
                return $song->getLabel;
            },$this->songs)
        );
    }

    /**
     * 回傳所有種類的chord
     * @return string[]
     */
    function getAllUniqueChords():array{
        $allChords = [];
        foreach($this->songs as $song){
            foreach($song->getChords() as $chord){
                $allChords[] = $chord;
            }
        }
        return array_unique($allChords);
    }

    /**
     * 每個label的歌曲中有多少chord
     */
    function getLabelToChordsCountTable(){
        $labelToChordsCountTable = [];
        array_walk(
            $this->songs,
            function(Song $song)use(&$labelToChordsCountTable){
                $label = $song->getLabel();
                $chords = $song->getChords();
                $chordCounters = $labelToChordsCountTable[$label] ?? [];
                foreach ($chords as $chord) {
                    $beforeCount = $chordCounters[$chord] ?? 0;
                    $chordCounters[$chord] = $beforeCount + 1;
                }
                $labelToChordsCountTable[$label] = $chordCounters;
            }
        );
        return $labelToChordsCountTable;
    }

    /**
     * 計算各label當中每個chord在所有歌曲中出現的機率
     */
    public function getChordsInLabelProbability():array{
        return 
        array_map(function(array $chords){
            return array_map(function(int $count){
                return $count/=getLegacyMainInstance()->getSongsCount();
            },$chords);
        },$this->getLabelToChordsCountTable());
    }

    /**
     * 未知演算法常數
     */
    const CLASSIFY_PROBABILITIES = 1.01;

    /**
     * 未知的演算法
     */
    function classify(array $chords,?array $probabilityOfChordsInLabels = null){
        if($probabilityOfChordsInLabels == null){
            $probabilityOfChordsInLabels = $this->getChordsInLabelProbability();
        }
        $classified = [];
        foreach ($this->getLabelInSongsProbabilities() as $label=>$probabilities) {
            $labelClassifyProbabilities = $probabilities + CLASSIFY_PROBABILITIES;
            foreach ($chords as $chord) {
                $probabilityOfChordInLabel = $probabilityOfChordsInLabels[$label][$chord] ?? false;
                if ($probabilityOfChordInLabel) {
                    $labelClassifyProbabilities *= ($probabilityOfChordInLabel + CLASSIFY_PROBABILITIES);
                }
                $classified[$label] = $labelClassifyProbabilities;
            }
        }
        return $classified;
    }
}