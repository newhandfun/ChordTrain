<?php
namespace Entities\Main\Algorithm;

/**
 * 未知演算法的實作
 */
interface IClassify{
    function execute(array $chords,array $labelInSongProbabilities,array $probabilityOfChordsInLabels);
}