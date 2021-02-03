<?php
namespace Entities\Main;

class Song{

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string[]
     */
    protected $chords;

    public function __construct(array $chords,string $label)
    {
        $this->label = $label;
        $this->chords = $chords;
    }

    public function getLabel(){
        return $this->label;
    }

    public function getChords(){
        return $this->chords;
    }

    public function toArray():array{
        return [$this->getLabel(),$this->getChords()];
    }
}