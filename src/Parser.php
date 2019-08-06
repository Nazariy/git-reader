<?php
/**
 * @project git-reader
 * @author Nazariy Slyusarchuk <hello@nazariy.me>
 */

namespace GitReader;


use DateTime;

/**
 * Class Parser
 * @package GitReader
 */
class Parser
{
    public static $map = [
        'hash' => '%H',
        'hashAbbr' => '%h',
        'tree' => '%T',
        'treeAbbr' => '%t',
        'parent' => '%P',
        'parentAbbr' => '%p',
        'authorName' => '%an',
        'authorEmail' => '%ae',
        'authorDate' => '%ad',
        'authorDateRel' => '%ar',
        'authorDateRFC' => '%aD',
        'authorDateISO' => '%ai',
        'authorDateStrictISO' => '%aI',
        'authorDateTimestamp' => '%at',
        'committerName' => '%cn',
        'committerEmail' => '%ce',
        'committerDate' => '%cd',
        'committerDateRel' => '%cr',
        'committerDateRFC' => '%cD',
        'committerDateISO' => '%ci',
        'committerDateStrictISO' => '%cI',
        'committerDateTimestamp' => '%ct',
        'subject' => '%s',
        'encoding' => '%e',
    ];

    /**
     * applyFormatting
     * @param array $array
     * @param array $format
     * @return array
     */
    public static function applyFormatting(array $array, array $format = []): array
    {
        if (empty($format)) {
            $group = [];
            $i = -1;
            foreach ($array as $line) {
                if (strpos($line, 'commit') === 0) {
                    $i++;
                }
                $line = trim($line);
                if ($line !== '') {
                    $group[$i][] = $line;
                }
            }
            return $group;
        }

        $keys = array_flip(array_replace(
            $format, array_intersect(self::$map, $format)
        ));

        array_walk($array, static function (&$line) use ($keys) {
            $line = array_combine($keys, sscanf($line, str_repeat('{%[^}]}', count($keys))));
        });

        return $array;
    }

    /**
     * getProcessedFormat
     * @param array $params
     * @return array
     */
    public static function getProcessedFormat(array $params): array
    {
        if (count($params) === 1 && is_array($params[0])) {
            $params = array_shift($params);
        }

        array_walk($params, static function (&$key) {
            $key = $key[0] !== '%' && isset(self::$map[$key]) ? self::$map[$key] : null;
        });

        return array_filter($params);
    }

    public static function graphFormatter(array $lines): array
    {
        $i = -1;
        $array = [];

        foreach ($lines as $line) {
            if ($line[0] === '*') {
                $i++;
            }
            $line = trim($line, "*|  \t\n\r\0\x0B");
            if ($line === '') {
                continue;
            }
            switch (true) {
                case sscanf($line, 'commit %s', $line):
                    $array[$i]['hash'] = $line;
                    break;
                case preg_match('/(\w+):\s+(.*)/', $line, $arr) && isset($arr[1], $arr[2]):
                    $array[$i][strtolower($arr[1])] = trim($arr[2]);
                    break;
                default:
                    $array[$i]['changes'][] = $line;
                    break;
            }
        }

        array_walk($array, static function (array &$arr) {
            if (isset($arr['author'])) {
                preg_match('/(\w+)\s+<(.*)>/', $arr['author'], $matches);
                $arr['author_name'] = $matches[1] ?? null;
                $arr['author_email'] = $matches[2] ?? null;
            }
            if (isset($arr['date'])) {
                $arr['date'] = new DateTime($arr['date']);
            }
            if (isset($arr['changes'])) {
                $arr['stats'] = array_pop($arr['changes']);
                $arr['comment'] = implode(PHP_EOL, $arr['changes']);
            }
            ksort($arr);
        });

        return $array;
    }

    public static function contributors(& $v): void
    {
        sscanf($v, '%d %[^]]', $commits, $name);
        $tmp = explode(' ', $name);
        $email = trim(array_pop($tmp), '<>');
        $name = implode(' ', $tmp);
        $v = compact('name', 'email', 'commits');
    }

    public static function branch(& $v): void
    {
        [$hash, $path] = explode(' ', $v);
        sscanf($path, 'refs/%[^/]/%s', $type, $name);
        $v = compact('hash', 'type', 'path', 'name');
    }
}