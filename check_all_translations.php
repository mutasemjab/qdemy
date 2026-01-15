<?php

// Define the prefixes to check
$prefixes = ['auth', 'messages', 'front', 'panel', 'validation', 'pagination', 'passwords'];

// Base paths
$langPath = __DIR__ . '/resources/lang';
$keysInputDir = __DIR__ . '/translation-keys';
$outputDir = __DIR__ . '/missing-translations';

// Check if input directory exists
if (!is_dir($keysInputDir)) {
    echo "⚠ Input directory not found: $keysInputDir\n";
    echo "   Please run extract_keys.php first\n";
    exit(1);
}

// Create output directory
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

echo "=== Translation Validation ===\n\n";

$totalKeysChecked = 0;
$totalMissing = 0;

foreach ($prefixes as $prefix) {
    $keysFile = $keysInputDir . '/' . $prefix . '.txt';

    // Skip if keys file doesn't exist
    if (!file_exists($keysFile)) {
        echo "⚠ $prefix.txt not found, skipping...\n";
        continue;
    }

    // Read keys from file
    $keys = file($keysFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if (empty($keys)) {
        echo "✓ $prefix: No keys to check\n";
        continue;
    }

    // Load translation files
    $enFile = $langPath . '/en/' . $prefix . '.php';
    $arFile = $langPath . '/ar/' . $prefix . '.php';

    $enTranslations = [];
    $arTranslations = [];

    if (file_exists($enFile)) {
        $enTranslations = include $enFile;
        if (!is_array($enTranslations)) {
            $enTranslations = [];
        }
    } else {
        echo "⚠ Warning: $enFile not found\n";
    }

    if (file_exists($arFile)) {
        $arTranslations = include $arFile;
        if (!is_array($arTranslations)) {
            $arTranslations = [];
        }
    } else {
        echo "⚠ Warning: $arFile not found\n";
    }

    $missingEn = [];
    $missingAr = [];

    foreach ($keys as $key) {
        $key = trim($key);
        if (empty($key)) continue;

        $totalKeysChecked++;

        // Check if key exists in English
        if (!array_key_exists($key, $enTranslations)) {
            $missingEn[] = $key;
            $totalMissing++;
        }

        // Check if key exists in Arabic
        if (!array_key_exists($key, $arTranslations)) {
            $missingAr[] = $key;
            $totalMissing++;
        }
    }

    // Create directory for missing translations
    $prefixOutputDir = $outputDir . '/' . $prefix;

    if (!empty($missingEn) || !empty($missingAr)) {
        if (!is_dir($prefixOutputDir)) {
            mkdir($prefixOutputDir, 0755, true);
        }
    }

    // Write missing English keys
    if (!empty($missingEn)) {
        $enOutputFile = $prefixOutputDir . '/missing_en.txt';
        file_put_contents($enOutputFile, implode("\n", $missingEn) . "\n");
    }

    // Write missing Arabic keys
    if (!empty($missingAr)) {
        $arOutputFile = $prefixOutputDir . '/missing_ar.txt';
        file_put_contents($arOutputFile, implode("\n", $missingAr) . "\n");
    }

    // Show summary for this prefix
    echo "$prefix:\n";
    echo "  Total keys: " . count($keys) . "\n";

    if (count($missingEn) > 0) {
        echo "  ✗ Missing in English: " . count($missingEn) . " keys → missing-translations/$prefix/missing_en.txt\n";
    } else {
        echo "  ✓ Complete in English\n";
    }

    if (count($missingAr) > 0) {
        echo "  ✗ Missing in Arabic: " . count($missingAr) . " keys → missing-translations/$prefix/missing_ar.txt\n";
    } else {
        echo "  ✓ Complete in Arabic\n";
    }

    echo "\n";
}

// Summary
echo "=== Summary ===\n";
echo "Total keys checked: $totalKeysChecked\n";
echo "Total missing: $totalMissing\n";
echo "Output directory: missing-translations/\n";
echo "\n✓ Done!\n";
