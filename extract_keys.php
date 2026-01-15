<?php

// Define the prefixes to search for (based on actual translation files)
$prefixes = ['auth', 'messages', 'front', 'panel', 'validation', 'pagination', 'passwords'];

// Initialize arrays to store keys for each prefix
$keys = [];
foreach ($prefixes as $prefix) {
    $keys[$prefix] = [];
}

// Function to recursively get files by extension
function getFilesByExtension($dir, $extensions = ['php']) {
    $files = [];
    try {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $ext = $file->getExtension();
                if (in_array($ext, $extensions)) {
                    $files[] = $file->getPathname();
                }
            }
        }
    } catch (Exception $e) {
        echo "⚠ Error scanning directory: " . $e->getMessage() . "\n";
    }

    return $files;
}

// Function to extract translation keys from file content
function extractKeys($content, $prefixes) {
    $foundKeys = [];

    foreach ($prefixes as $prefix) {
        // Pattern for __('prefix.key') or __("prefix.key")
        $pattern = '/__\s*\(\s*[\'"]' . preg_quote($prefix, '/') . '\.([^\'"]+)[\'"]\s*\)/';
        if (preg_match_all($pattern, $content, $matches)) {
            foreach ($matches[1] as $key) {
                $key = trim($key);
                if (!empty($key) && !isset($foundKeys[$prefix][$key])) {
                    $foundKeys[$prefix][$key] = true;
                }
            }
        }

        // Pattern for trans('prefix.key') or trans("prefix.key")
        $pattern2 = '/trans\s*\(\s*[\'"]' . preg_quote($prefix, '/') . '\.([^\'"]+)[\'"]\s*\)/';
        if (preg_match_all($pattern2, $content, $matches)) {
            foreach ($matches[1] as $key) {
                $key = trim($key);
                if (!empty($key) && !isset($foundKeys[$prefix][$key])) {
                    $foundKeys[$prefix][$key] = true;
                }
            }
        }

        // Pattern for @lang('prefix.key') - Blade directive
        $pattern3 = '/@lang\s*\(\s*[\'"]' . preg_quote($prefix, '/') . '\.([^\'"]+)[\'"]\s*\)/';
        if (preg_match_all($pattern3, $content, $matches)) {
            foreach ($matches[1] as $key) {
                $key = trim($key);
                if (!empty($key) && !isset($foundKeys[$prefix][$key])) {
                    $foundKeys[$prefix][$key] = true;
                }
            }
        }
    }

    // Convert back to simple array format
    foreach ($foundKeys as $prefix => $prefixKeys) {
        $foundKeys[$prefix] = array_keys($prefixKeys);
    }

    return $foundKeys;
}

// Get all relevant files
$viewsDir = __DIR__ . '/resources/views';
$appDir = __DIR__ . '/app';
$resourcesDir = __DIR__ . '/resources';

$files = [];
$files = array_merge($files, getFilesByExtension($viewsDir, ['php']));
$files = array_merge($files, getFilesByExtension($appDir, ['php']));
$files = array_merge($files, getFilesByExtension($resourcesDir, ['js', 'vue']));

echo "Scanning " . count($files) . " files in resources/views, app, and resources directories...\n\n";

$filesWithTranslations = 0;

// Process each file
foreach ($files as $file) {
    $content = file_get_contents($file);
    $foundKeys = extractKeys($content, $prefixes);

    // Check if any keys found in this file
    $hasKeys = false;
    foreach ($foundKeys as $prefixKeys) {
        if (!empty($prefixKeys)) {
            $hasKeys = true;
            break;
        }
    }
    if ($hasKeys) $filesWithTranslations++;

    // Merge keys
    foreach ($foundKeys as $prefix => $prefixKeys) {
        foreach ($prefixKeys as $key) {
            $keys[$prefix][] = $key;
        }
    }
}

// Remove duplicates and sort
foreach ($prefixes as $prefix) {
    $keys[$prefix] = array_unique($keys[$prefix]);
    sort($keys[$prefix]);
}

// Create output directory
$outputDir = __DIR__ . '/translation-keys';
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

// Write results to files with summary
echo "\n=== Extraction Results ===\n\n";
$totalKeys = 0;

foreach ($prefixes as $prefix) {
    $filename = $outputDir . '/' . $prefix . '.txt';
    $keyCount = count($keys[$prefix]);
    $totalKeys += $keyCount;

    if (!empty($keys[$prefix])) {
        $content = implode("\n", $keys[$prefix]);
        $content .= "\n";
        file_put_contents($filename, $content);
        echo "✓ $prefix: " . $keyCount . " unique keys → translation-keys/$prefix.txt\n";
    } else {
        echo "  $prefix: 0 keys\n";
    }
}

echo "\n=== Summary ===\n";
echo "Total files scanned: " . count($files) . "\n";
echo "Files with translations: " . $filesWithTranslations . "\n";
echo "Total unique keys found: " . $totalKeys . "\n";
echo "Output directory: $outputDir/\n";
echo "\n✓ Done!\n";
