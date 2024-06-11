<?php declare(strict_types=1);

namespace App\Service;

use App\DTO\DataDTO;
use App\Exception\DataException;

final class DataLoader
{
    private const REQUIRED_KEYS = ['bin', 'amount', 'currency'];

    /**
     * @throws DataException
     */
    public function __construct(
        private array $parameters = [],
        private ?array $data = null,
        private ?string $filePath = null
    ) {
        global $argv;

        $this->parameters = $argv;
        $this->filePath = getcwd() . '/' . ($this->parameters[1] ?? '.');
        $this->check();
    }

    /**
     * @throws DataException
     *
     * @return DataDTO[]
     */
    public function getData(): array
    {
        if (null === $this->data) {
            $this->fetch();
        }

        return $this->data;
    }

    /**
     * @throws DataException
     */
    private function check(): void
    {
        if (true === empty($this->parameters[1])) {
            throw new DataException(
                'Specify the file with data to process. Usage: `$ php calculate input.txt`.'
            );
        }

        if (false === file_exists($this->filePath) || false === is_file($this->filePath)) {
            throw new DataException(sprintf(
                'File `%s` not exists or is not accessible.',
                $this->filePath
            ));
        }

        $this->filePath = realpath($this->filePath);
    }

    /**
     * @throws DataException
     */
    private function fetch(): void
    {
        $lines = preg_split('/[\r\n]+/', file_get_contents($this->filePath));
        $this->data = [];

        foreach ($lines as $line) {
            if (true === empty($line)) {
                continue;
            }
            $this->processLine($line);
        }

        if (true === empty($this->data)) {
            throw new DataException(sprintf(
                'File `%s` is empty or has unknown format.',
                $this->filePath
            ));
        }
    }

    private function processLine(string $line): void
    {
        $lineData = json_decode($line, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            return;
        }

        $intersectRequiredKeys = count(array_intersect_key(array_flip(self::REQUIRED_KEYS), $lineData));
        if (count(self::REQUIRED_KEYS) !== $intersectRequiredKeys) {
            return;
        }

        $this->data[] = (new DataDTO())
            ->setBin(strval($lineData['bin']))
            ->setAmount(floatval($lineData['amount']))
            ->setCurrency(strval($lineData['currency']));
    }
}
