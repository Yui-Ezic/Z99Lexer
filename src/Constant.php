<?php


namespace Z99Lexer;


class Constant
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var number
     */
    private $value;

    public function __construct(int $id, $value)
    {
        $this->id = $id;
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return number
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return sprintf('@%02d  %-10s',
            $this->getId(),
            (string)$this->getValue()
        );
    }

}