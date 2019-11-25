<?php


namespace Library\Task\Data;

use Library\Filesystem\DirectoryInterface;

/**
 * Задача для обновления данных, указанных в файле, из БД
 * @package Library\Task\Data
 */
class UpdateData extends AbstractDataTask
{

    /**
     * @inheritdoc
     */
    protected function getTaskDescription(): string
    {
        return 'Обновление данных в' . $this->mapper->getSqlName();
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
        // FIXME Тут что-то должно быть
    }

}