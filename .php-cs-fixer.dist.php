<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

return (new Config())
    ->setParallelConfig(ParallelConfigFactory::detect()) // @TODO 4.0 no need to call this manually
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'no_superfluous_phpdoc_tags' => false,
        'array_push' => false,
        'yoda_style' => false,
        'single_line_comment_style' => false, // per les #region #endregion
        'single_line_comment_spacing' => false, // per les #region #endregion
    ])
    // 💡 by default, Fixer looks for `*.php` files excluding `./vendor/` - here, you can groom this config
    ->setFinder(
        (new Finder())
            // 💡 root folder to check
            ->in(__DIR__)
        // 💡 additional files, eg bin entry file
        // ->append([__DIR__.'/bin-entry-file'])
        // 💡 folders to exclude, if any
        // ->exclude([/* ... */])
        // 💡 path patterns to exclude, if any
        // ->notPath([/* ... */])
        // 💡 extra configs
        // ->ignoreDotFiles(false) // true by default in v3, false in v4 or future mode
        // ->ignoreVCS(true) // true by default
    )
;
