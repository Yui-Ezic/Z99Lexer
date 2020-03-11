<?php


namespace Z99Lexer;


class Token
{
    /**
     * @var int
     */
    private $line;

    /**
     * @var string
     */
    private $string;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int|null
     */
    private $index;

    public function __construct(int $line, string $string, string $type, ?int $index = null)
    {

        $this->line = $line;
        $this->string = $string;
        $this->type = $type;
        $this->index = $index;
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * @return string
     */
    public function getString(): string
    {
        return $this->string;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int|null
     */
    public function getIndex(): ?int
    {
        return $this->index;
    }
}