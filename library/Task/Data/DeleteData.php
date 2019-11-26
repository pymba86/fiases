<?php


namespace Library\Task\Data;

use Library\Filesystem\DirectoryInterface;
use Library\Search\ConnectionInterface;

/**
 * Задача для удаления данных, указанных в файле, из БД
 * @package Library\Task\Data
 */
class DeleteData extends AbstractDataTask
{

    /**
     * @inheritdoc
     */
    protected function getTaskDescription(): string
    {
        return 'Удаление данных из' . $this->mapper->getIndexName();
    }

    /**
     * @inheritdoc
     */
    protected function searchFileInDir(DirectoryInterface $dir)
    {
        $files = $dir->findFilesByPattern($this->mapper->getDeleteFileMask());
        $file = reset($files);
        return $file;
    }

    /**
     * @inheritdoc
     */
    protected function processItem(array $item)
    {
        /** @var ConnectionInterface $search */
        $search = $this->di->get("search");
        $search->delete($this->mapper, $item);
    }

}