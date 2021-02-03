<?php
namespace Entities\Main\Algorithm;
require('IClassify.php');

use Entities\Main\Algorithm\IClassify;

/**
 * 舊Code的未知演算法實作
 */
class LegacyClassify implements IClassify{
    
    /**
     * 未知演算法常數
     */
    const CLASSIFY_PROBABILITIES = 1.01;

    public function execute(array $chords,array $labelInSongProbabilities,array $probabilityOfChordsInLabels){
        $classified = [];
        foreach ($labelInSongProbabilities as $label=>$probabilities) {
            $labelClassifyProbabilities = $probabilities + self::CLASSIFY_PROBABILITIES;
            foreach ($chords as $chord) {
                $probabilityOfChordInLabel = $probabilityOfChordsInLabels[$label][$chord] ?? false;
                if ($probabilityOfChordInLabel) {
                    $labelClassifyProbabilities *= ($probabilityOfChordInLabel + self::CLASSIFY_PROBABILITIES);
                }
                $classified[$label] = $labelClassifyProbabilities;
            }
        }
        return $classified;
    }
}