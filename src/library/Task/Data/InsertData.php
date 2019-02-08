<?php


namespace Library\Task\Data;

use Library\Filesystem\DirectoryInterface;
use Library\Search\ConnectionInterface;

/**
 * Задача для наполнения данных с нуля для указанного маппера
 * @package Library\Task\Data
 */
class InsertData extends AbstractDataTask
{

    /**
     * @inheritdoc
     */
    protected function getTaskDescription(): string
    {
        return 'Вставка новых данных ' . $this->mapper->getIndexName();
    }

    /**
     * @inheritdoc
     */
    protected function searchFileInDir(DirectoryInterface $dir)
    {
        $files = $dir->findFilesByPattern($this->mapper->getInsertFileMask());
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
        $search->save($this->mapper, $item);
    }

}