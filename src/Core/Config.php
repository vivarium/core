<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) 2026 Luca Cantoreggi
 */

declare(strict_types=1);

namespace Vivarium\Core;

use RuntimeException;
use SimpleXMLElement;
use Vivarium\Assertion\Boolean\IsTrue;

use function file_exists;

final class Config
{
    /** @param list<string> $modules */
    public function __construct(
        private bool $metadataEnabled,
        private string $metadataPath,
        private bool $cacheEnabled,
        private string $cachePath,
        private array $modules
    ) {
    }

    public static function loadFromFile(string $path): self
    {
        (new IsTrue())
            ->assert(file_exists($path));

        libxml_use_internal_errors(true);
        $xml = simplexml_load_file($path);

        if ($xml === false) {
            $error = libxml_get_errors()[0] ?? null;
            libxml_clear_errors();

            throw new RuntimeException(sprintf(
                'Failed to parse config file "%s": %s',
                $path,
                $error !== null ? trim($error->message) : 'Unknown error'
            ));
        }

        return new self(
            filter_var($xml->metadata['enabled'], FILTER_VALIDATE_BOOLEAN),
            (string) $xml->metadata['path'],
            filter_var($xml->cache['enabled'], FILTER_VALIDATE_BOOLEAN),
            (string) $xml->cache['path'],
            self::parseModules($xml)
        );
    }

    public function isMetadataEnabled(): bool
    {
        return $this->metadataEnabled;
    }

    public function getMetadataPath(): string
    {
        return $this->metadataPath;
    }

    public function isCacheEnabled(): bool
    {
        return $this->cacheEnabled;
    }

    public function getCachePath(): string
    {
        return $this->cachePath;
    }

    /** @return list<string> */
    public function getModules(): array
    {
        return $this->modules;
    }

    /** @return list<string> */
    private static function parseModules(SimpleXMLElement $xml): array
    {
        $modules = [];
        foreach ($xml->modules->module ?? [] as $module) {
            $class = (string) $module['class'];
            if ($class !== '') {
                $modules[] = $class;
            }
        }

        return $modules;
    }
}
