<?php
namespace Entities;
require('Main/Song.php');
require('Main/Algorithm/LegacyClassify.php');

use Entities\Main\Algorithm\IClassify;
use Entities\Main\Algorithm\LegacyClassify;
use Entities\Main\Song;

class Main{

    /**
     * @var Song[]
     */
    private $songs = [];

    /**
     * @var IClassify
     */
    private $classifyAlgorithm = null;

    public function __construct(?IClassify $classifyAlgorithm = null)
    {
        if(empty($classifyAlgorithm)){
            $classifyAlgorithm = new LegacyClassify();
        }
        $this->classifyAlgorithm = $classifyAlgorithm;
    }

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

    /**
     * 設定使用的演算法
     */
    public function setClassify(IClassify $newAlgorithm):Main{
        $this->classifyAlgorithm = $newAlgorithm;
        return $this;
    }

    /**
     * 取得歌曲數目
     */
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
     * 未知的演算法
     */
    function classify(array $chords,?array $probabilityOfChordsInLabels = null){
        if($probabilityOfChordsInLabels == null){
            $probabilityOfChordsInLabels = $this->getChordsInLabelProbability();
        }
        //執行目前指定的演算法
        return $this->classifyAlgorithm->execute($chords,$this->getLabelInSongsProbabilities(),$probabilityOfChordsInLabels);
    }
}