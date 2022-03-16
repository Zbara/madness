<?php

namespace App\Service;

class GitRevision
{
    public const MAJOR = 1;

    public function getBuild(): string
    {
        $branch = trim(exec('git symbolic-ref HEAD | sed -e "s/^refs\/heads\///"'));
        $rev = mb_str_split(exec('git rev-list HEAD --count'));


        return sprintf('build v%s.%s.%s-%s', self::MAJOR, implode('.', $rev), $branch, $this->getHash());
    }

    public function getDate(): string
    {
        return trim(exec("git log -1 --pretty=format:'%ci'"));
    }

    public function getHash(): string
    {
        $commitHash = trim(exec('git log --pretty="%h" -n1 HEAD'));

        return sprintf('%s', $commitHash);
    }

    public function getCommand($exec): string
    {
        return trim(exec('git ' . $exec));
    }
}
