<?php
/**
 * @project git-reader
 * @author Nazariy Slyusarchuk <hello@nazariy.me>
 */

namespace GitReader;


use GitReader\Exception\FileSystemException;
use GitReader\Exception\RuntimeException;
use SplFileInfo;

/**
 * Class Command
 * @package GitReader
 */
class Command
{
    /**
     * @var SplFileInfo
     */
    protected $directory;

    /**
     * Command constructor.
     * @param string $directory
     * @param bool $autoCorrect
     */
    final public function __construct(string $directory, bool $autoCorrect = true)
    {
        if ($autoCorrect && basename($directory) !== '.git') {
            $directory .= DIRECTORY_SEPARATOR . '.git';
        }

        $instance = new SplFileInfo($directory);

        if ($instance->isDir() === false) {
            throw new FileSystemException('Invalid GIT directory', 404);
        }

        if ($instance->isReadable() === false) {
            throw new FileSystemException('GIT directory is not accessible');
        }

        $this->directory = $instance;
    }

    /**
     * command
     * @param mixed ...$params
     * @return string
     */
    protected function command(... $params): string
    {
        $default = [
            'git',
            '--git-dir',
            escapeshellarg($this->directory->getPathname())
        ];

        return escapeshellcmd(
            implode(' ', array_merge($default, array_filter($params)))
        );
    }

    /**
     * exec
     * @param string ...$params
     * @return array
     */
    protected function exec(array $params): array
    {
        $cmd = call_user_func_array([$this, 'command'], $params);

        if (Repository::getDebug()) {
            $cmd .= ' 2>&1';
        }

        $response = exec($cmd, $output, $code);

        if ($code !== 0) {
            if (empty($response)) {
                $response = 'Unknown error occurred';
            }
            throw new RuntimeException($response, $code);
        }

        return $output;
    }

    /**
     * array
     * @param mixed ...$params
     * @return array
     */
    public function array(...$params): array
    {
        return $this->exec($params);
    }

    /**
     * int
     * @param mixed ...$params
     * @return int
     */
    public function int(...$params): int
    {
        return (int)$this->exec($params)[0];
    }

    /**
     * string
     * @param mixed ...$params
     * @return string
     */
    public function string(...$params): string
    {
        return implode(PHP_EOL, $this->exec($params));
    }
}