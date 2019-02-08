<?php

namespace Library\Unpacker;

use Library\Filesystem\DirectoryInterface;
use Library\Filesystem\FileInterface;
use RarArchive;
use RuntimeException;
use Throwable;

class Rar implements UnpackerInterface
{

    /**
     * @inheritdoc
     */
    public function unpack(FileInterface $source, DirectoryInterface $destination)
    {
        if (!$source->isExists()) {
            throw new RuntimeException(
                "Can't find archive " . $source->getPath() . ' to extract'
            );
        }
        if (!$destination->isExists()) {
            throw new RuntimeException(
                "Can't find destination folder " . $destination->getPath() . ' to extract'
            );
        }
        $archive = $this->getRarInstance($source);
        try {
            $this->extractArchiveTo($archive, $destination);
        } catch (Throwable $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        } finally {
            $archive->close();
        }
    }

    /**
     * Распаковывает архи в указанный каталог.
     *
     * @param RarArchive         $archive
     * @param DirectoryInterface $destination
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    protected function extractArchiveTo(RarArchive $archive, DirectoryInterface $destination)
    {
        $entries = $archive->getEntries();
        if (!is_array($entries)) {
            throw new RuntimeException("Can't read entries from archive");
        }
        $path = $destination->getPath();
        foreach ($entries as $entry) {
            if ($entry->extract($path) === false) {
                $name = $entry->getName();
                throw new RuntimeException("Can't extract entry {$name} to {$path}");
            }
        }
    }

    /**
     * Возвращает объект с открытым архивом.
     *
     * @param FileInterface $source
     *
     * @return RarArchive
     *
     * @throws RuntimeException
     */
    protected function getRarInstance(FileInterface $source): RarArchive
    {
        $rar = RarArchive::open($source->getPath());
        if (!($rar instanceof RarArchive)) {
            throw new RuntimeException(
                "Can't open file " . $source->getPath() . ' as rar archive'
            );
        }
        return $rar;
    }
}