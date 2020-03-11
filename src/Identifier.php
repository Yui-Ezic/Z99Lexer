<?php


namespace Z99Lexer;


class Identifier
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $type;

    public function __construct(int $id, string $name, ?string $type)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getType() : ?string
    {
        return $this->type;
    }
}