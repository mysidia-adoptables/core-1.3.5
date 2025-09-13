<?php
/**
 * Safely rename PHP files to match their class/interface/trait name.
 * Works with abstract/final classes and case-insensitive filesystems.
 *
 * Usage:
 *   php rename_php_files.php /path/to/folder
 */

$baseDir = $argv[1] ?? '.';

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($baseDir, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

/**
 * Safely get the first top-level class/interface/trait name from a file.
 */
function getFirstClassName(string $filePath): ?string {
    $code = file_get_contents($filePath);
    $tokens = token_get_all($code);

    $namespace = '';
    $className = null;

    for ($i = 0; $i < count($tokens); $i++) {
        $token = $tokens[$i];

        if (is_array($token)) {
            // Capture namespace
            if ($token[0] === T_NAMESPACE) {
                $namespace = '';
                $j = $i + 1;
                while (isset($tokens[$j]) && is_array($tokens[$j]) && in_array($tokens[$j][0], [T_STRING, T_NS_SEPARATOR])) {
                    $namespace .= $tokens[$j][1];
                    $j++;
                }
            }

            // Capture class, interface, or trait
            if (in_array($token[0], [T_CLASS, T_INTERFACE, T_TRAIT])) {
                // Skip anonymous classes
                $prevToken = $tokens[$i - 1] ?? null;
                if (is_array($prevToken) && $prevToken[0] === T_NEW) {
                    continue;
                }

                // Get the name
                $nameToken = $tokens[$i + 2] ?? null;
                if (is_array($nameToken) && $nameToken[0] === T_STRING) {
                    $className = $namespace ? $namespace . '\\' . $nameToken[1] : $nameToken[1];
                    return $className;
                }
            }
        }
    }

    return null;
}

foreach ($iterator as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') {
        continue;
    }

    $filePath = $file->getPathname();
    $fileName = $file->getFilename();
    $dirPath  = $file->getPath();
    $nameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME);

    // Only process lowercase filenames
    if ($nameWithoutExt !== strtolower($nameWithoutExt)) {
        continue;
    }

    $className = getFirstClassName($filePath);
    if (!$className) {
        echo "⚠️ No class/interface/trait found in {$filePath}\n";
        continue;
    }

    // Only use the short class name (strip namespace) for filename
    $parts = explode('\\', $className);
    $shortName = end($parts);
    $finalFile = $shortName . '.php';
    $finalPath = $dirPath . DIRECTORY_SEPARATOR . $finalFile;

    // Skip if another file with the same name exists
    if (file_exists($finalPath) && strcasecmp($finalPath, $filePath) !== 0) {
        echo "⚠️ Skipping {$filePath} → {$finalFile} (target exists)\n";
        continue;
    }

    // Step 1: safe temp rename
    $tempFile = '__tmp_' . $nameWithoutExt . '.php';
    $tempPath = $dirPath . DIRECTORY_SEPARATOR . $tempFile;

    if (!rename($filePath, $tempPath)) {
        echo "❌ Failed temp rename {$fileName} → {$tempFile}\n";
        continue;
    }

    // Step 2: rename temp → final
    if (!rename($tempPath, $finalPath)) {
        echo "❌ Failed final rename {$tempFile} → {$finalFile}\n";
        continue;
    }

    echo "✅ Renamed {$fileName} → {$finalFile}\n";
}