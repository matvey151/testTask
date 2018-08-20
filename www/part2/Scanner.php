<?php

/**
 * Класс для поиска файлов в директории
 */
class Scanner
{
    /** @var string Регулярное выражение по-умолчанию для фильтрации найденных фалов */
    const DEFAULT_TEMPLATE = '/^[A-Z0-9]+\.ixt$/i';

    /** @var string Регулярное выражение для фильтрации найденных фалов */
    private $template;

    /** @var string Путь к директории */
    private $dir;

    /**
     * В конструктор передается путь к директории
     *
     * @param string $dir
     */
    public function __construct(string $dir)
    {
        $this->dir = $dir;
        $this->setTemplate();
    }

    /**
     * Задает шаблон имен файлов по которому следует фильтровать найденные файлы в директории
     *
     * @param string $template
     * @throws Exception
     */
    public function setTemplate(string $template = self::DEFAULT_TEMPLATE)
    {
        if (@preg_match($template, null) === false) {
           throw new Exception('Регулярное выражение написано с ошибкой');
        }

        $this->template = $template;
    }

    /**
     * Возовращает отсортированный массив имен файлов из указанной в конструкторе директории
     * Если директория не существует или не может быть открыта, то выбрасывает исключение
     *
     * @return array
     * @throws Exception
     */
    public function find(): array
    {
        $files = [];
        if (!$handle = opendir($this->dir)) {
            throw new Exception('Ошибка отрытия ' . $this->dir);
        }

        while (false !== ($file = readdir($handle))) {
            if ($this->checkFileName($file)) {
                $files[] = $file;
            }
        }
        closedir($handle);

        return $this->sort($files);
    }

    /**
     * Возвращает отсортированный массив переданный в параметре
     *
     * @param $files
     * @return array
     */
    private function sort($files): array
    {
        sort($files);
        return $files;
    }

    /**
     * Проверяет имя файла на соответствие щаблону
     *
     * @param $file
     * @return bool
     */
    private function checkFileName($file): bool
    {
        return 1 === preg_match($this->template, $file);
    }

}
