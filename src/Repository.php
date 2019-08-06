<?php
/**
 * @project git-reader
 * @author Nazariy Slyusarchuk <hello@nazariy.me>
 */

namespace GitReader;

/**
 * Class Repository
 * @package GitReader
 */
class Repository
{
    /**
     * @var int
     */
    protected $limit;

    /**
     * @var Command
     */
    public $cmd;

    protected static $debug = true;

    /**
     * GitLog constructor.
     * @param string $path
     */
    public function __construct(string $path = null)
    {
        $this->cmd = new Command($path ?? getcwd(), true);
    }

    /**
     * Get Debug
     * @return mixed
     */
    public static function getDebug()
    {
        return self::$debug;
    }

    /**
     * Set Debug
     * @static
     * @access public
     * @param mixed $debug
     */
    public static function setDebug(bool $debug = true): void
    {
        self::$debug = $debug;
    }


    /**
     * setLimit
     * @param int $limit
     * @return self
     */
    public function setLimit(int $limit): self
    {
        $this->limit = abs($limit);
        return $this;
    }

    /**
     * getLimit
     * @return string|null
     */
    public function getLimit(): ?string
    {
        return $this->limit === null ? null : -$this->limit;
    }


    /**
     * getLogs
     * @param mixed ...$params
     * @return array
     */
    public function getLogs(...$params): array
    {
        $format = Parser::getProcessedFormat($params);

        if ($format) {
            $type = sprintf('--pretty=tformat:"{%s}"', implode('}{', $format));
        } else {
            $type = '--shortstat';
        }

        return Parser::applyFormatting(
            $this->cmd->array('log', $this->getLimit(), $type),
            $format
        );
    }

    /**
     * getShortStatGraphRaw
     * @return string
     */
    public function getShortStatGraphRaw(): string
    {
        return $this->cmd->string('log', $this->getLimit(), '--graph --shortstat');
    }

    /**
     * getShortStatGraph
     * @return array
     */
    public function getShortStatGraph(): array
    {
        return Parser::graphFormatter(
            $this->cmd->array('log', $this->getLimit(), '--graph --shortstat')
        );
    }

    /**
     * getContributors
     * @param string $branch
     * @return array
     */
    public function getContributors(string $branch = 'master'): array
    {
        $array = $this->cmd->array('shortlog', $branch, '-s', '-n', '-e');

        array_walk($array, [Parser::class, 'contributors']);

        return $array;
    }

    /**
     * getBranch
     * @return array
     */
    public function getBranches(): array
    {
        $array = $this->cmd->array('show-ref');

        array_walk($array, [Parser::class, 'branch']);

        return $array;
    }

    /**
     * getCommitCount
     * @param string $branch
     * @return int
     */
    public function getCommitCount(string $branch = 'HEAD'): int
    {
        return $this->cmd->int('rev-list', '--count', '--no-merges', $branch);
    }

    /**
     * getCurrentBranch
     * @return string
     */
    public function getCurrentBranch(): string
    {
        return $this->cmd->string('rev-parse', '--abbrev-ref', 'HEAD');
    }
}