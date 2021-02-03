<?php
error_reporting(E_ALL & ~E_WARNING);
use PHPUnit\Framework\TestCase;


final class MainTest extends TestCase
{    
    protected $imagine = ['c', 'cmaj7', 'f', 'am', 'dm', 'g', 'e7'];
    protected $somewhere_over_the_rainbow = ['c', 'em', 'f', 'g', 'am'];
    protected $tooManyCooks = ['c', 'g', 'f'];
    protected $iWillFollowYouIntoTheDark = ['f', 'dm', 'bb', 'c', 'a', 'bbm'];
    protected $babyOneMoreTime = ['cm', 'g', 'bb', 'eb', 'fm', 'ab'];
    protected $creep = ['g', 'gsus4', 'b', 'bsus4', 'c', 'cmsus4', 'cm6'];
    protected $paperBag = ['bm7', 'e', 'c', 'g', 'b7', 'f', 'em', 'a', 'cmaj7', 'em7', 'a7', 'f7', 'b'];
    protected $toxic = ['cm', 'eb', 'g', 'cdim', 'eb7', 'd7', 'db7', 'ab', 'gmaj7', 'g7'];
    protected $bulletproof = ['d#m', 'g#', 'b', 'f#', 'g#m', 'c#'];

    protected function execute(){
        require('main_legacy.php');
    }

    public function testSongs(){
        $this->execute();
        global $songs;
        $this->assertEqualsCanonicalizing([
            ['easy',$this->imagine],
            ['easy',$this->somewhere_over_the_rainbow],
            ['easy',$this->tooManyCooks],
            ['medium',$this->iWillFollowYouIntoTheDark],
            ['medium',$this->babyOneMoreTime],
            ['medium',$this->creep],
            ['hard',$this->paperBag],
            ['hard',$this->toxic],
            ['hard',$this->bulletproof],
        ],$songs);
    }
    public function testAllChords(){
        global $allChords;
        $this->assertEqualsCanonicalizing([
            'c',
            'cmaj7',
            'f',
            'am',
            'dm',
            'g',
            'e7',
            'em',
            'bb',
            'a',
            'bbm',
            'cm',
            'eb',
            'fm',
            'ab',
            'gsus4',
            'b',
            'bsus4',
            'cmsus4',
            'cm6',
            'bm7',
            'e',
            'b7',
            'em7',
            'a7',
            'f7',
            'cdim',
            'eb7',
            'd7',
            'db7',
            'gmaj7',
            'g7',
            'd#m',
            'g#',
            'f#',
            'g#m',
            'c#',
        ],$allChords);
    }
    public function testLabelCounts(){
        global $labelCounts;
        $this->assertEqualsCanonicalizing([
            'easy'=>3,
            'medium'=>3,
            'hard'=>3
        ],$labelCounts);
    }
    public function testLabelProbabilities(){
        global $labelProbabilities;
        $this->assertEqualsCanonicalizing([
            'easy' => 0.33333333333333,
            'medium' => 0.33333333333333,
            'hard' => 0.33333333333333,
        ],$labelProbabilities);
    }
    public function testChordCountsInLabels(){
        global $chordCountsInLabels;
        $this->assertEqualsCanonicalizing([
                'easy'=>[
                    'c' => 3,
                    'cmaj7' => 1,
                    'f' => 3,
                    'am' => 2,
                    'dm' => 1,
                    'g' => 3,
                    'e7' => 1,
                    'em' => 1,
                ],
                'medium'=>[
                    'f' => 1,
                    'dm' => 1,
                    'bb' => 2,
                    'c' => 2,
                    'a' => 1,
                    'bbm' => 1,
                    'cm' => 1,
                    'g' => 2,
                    'eb' => 1,
                    'fm' => 1,
                    'ab' => 1,
                    'gsus4' => 1,
                    'b' => 1,
                    'bsus4' => 1,
                    'cmsus4' => 1,
                    'cm6' => 1,
                ],
                'hard'=>[
                    'bm7' => 1,
                    'e' => 1,
                    'c' => 1,
                    'g' => 2,
                    'b7' => 1,
                    'f' => 1,
                    'em' => 1,
                    'a' => 1,
                    'cmaj7' => 1,
                    'em7' => 1,
                    'a7' => 1,
                    'f7' => 1,
                    'b' => 2,
                    'cm' => 1,
                    'eb' => 1,
                    'cdim' => 1,
                    'eb7' => 1,
                    'd7' => 1,
                    'db7' => 1,
                    'ab' => 1,
                    'gmaj7' => 1,
                    'g7' => 1,
                    'd#m' => 1,
                    'g#' => 1,
                    'f#' => 1,
                    'g#m' => 1,
                    'c#' => 1,
                ]
            ],$chordCountsInLabels);
    }
    public function testProbabilityOfChordsInLabels(){
        global $probabilityOfChordsInLabels;
        $this->assertEqualsCanonicalizing(
            [
                "easy"=>[
                    "c"=>0.3333333333333333,
                    "cmaj7"=>0.1111111111111111,
                    "f"=>0.3333333333333333,
                    "am"=>0.2222222222222222,
                    "dm"=>0.1111111111111111,
                    "g"=>0.3333333333333333,
                    "e7"=>0.1111111111111111,
                    "em"=>0.1111111111111111
                ],
                "medium"=>[
                    "f"=>0.1111111111111111,
                    "dm"=>0.1111111111111111,
                    "bb"=>0.2222222222222222,
                    "c"=>0.2222222222222222,
                    "a"=>0.1111111111111111,
                    "bbm"=>0.1111111111111111,
                    "cm"=>0.1111111111111111,
                    "g"=>0.2222222222222222,
                    "eb"=>0.1111111111111111,
                    "fm"=>0.1111111111111111,
                    "ab"=>0.1111111111111111,
                    "gsus4"=>0.1111111111111111,
                    "b"=>0.1111111111111111,
                    "bsus4"=>0.1111111111111111,
                    "cmsus4"=>0.1111111111111111,
                    "cm6"=>0.1111111111111111
                ],
                "hard"=>[
                    "bm7"=>0.1111111111111111,
                    "e"=>0.1111111111111111,
                    "c"=>0.1111111111111111,
                    "g"=>0.2222222222222222,
                    "b7"=>0.1111111111111111,
                    "f"=>0.1111111111111111,
                    "em"=>0.1111111111111111,
                    "a"=>0.1111111111111111,
                    "cmaj7"=>0.1111111111111111,
                    "em7"=>0.1111111111111111,
                    "a7"=>0.1111111111111111,
                    "f7"=>0.1111111111111111,
                    "b"=>0.2222222222222222,
                    "cm"=>0.1111111111111111,
                    "eb"=>0.1111111111111111,
                    "cdim"=>0.1111111111111111,
                    "eb7"=>0.1111111111111111,
                    "d7"=>0.1111111111111111,
                    "db7"=>0.1111111111111111,
                    "ab"=>0.1111111111111111,
                    "gmaj7"=>0.1111111111111111,
                    "g7"=>0.1111111111111111,
                    "d#m"=>0.1111111111111111,
                    "g#"=>0.1111111111111111,
                    "f#"=>0.1111111111111111,
                    "g#m"=>0.1111111111111111,
                    "c#"=>0.1111111111111111
                ]
            ],
            $probabilityOfChordsInLabels);
    }
    
    public function testSong11(){
        global $song_11;
        $this->assertTrue(empty($song_11));
    }

    public function testLabels(){
        global $labels;
        $this->assertTrue(empty($labels));
    }
    
    public function testLabel(){
        global $label;
        $this->assertEqualsCanonicalizing([
            "easy",
            "easy",
            "easy",
            "medium",
            "medium",
            "medium",
            "hard",
            "hard",
            "hard",
        ],$label);
    }
}
