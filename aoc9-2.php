<?php

$disk = str_split(file_get_contents(__DIR__ . '/day9.txt'));
$diskLength = count($disk);
$isFileLength = false;
$partition = [];

$fileId = -1;

for ($i = 0; $i < $diskLength; $i++) {
    $isFileLength = !$isFileLength;
    $fileId += (int) $isFileLength;
    $value = $disk[$i];

    for ($j = 0; $j < $value; $j++) {
        $partition[] = [
            'type' => $isFileLength ? 'file' : 'free',
            'id' => $isFileLength ? $fileId : null
        ];
    }
}

$partitionSize = count($partition);
$free = array_filter($partition, fn (array $sector) => $sector['type'] === 'free');
$files = array_filter($partition, fn (array $sector) => $sector['type'] === 'file');

$fullFileMap = [];

foreach ($files as $index => $file) {
    $fileId = $file['id'];
    if (!isset($fullFileMap[$fileId])) {
        $fullFileMap[$fileId] = [
            'indexes' => [], 
            'size' => 0
        ];
    }
    $fullFileMap[$fileId]['indexes'][] = $index;
    $fullFileMap[$fileId]['size']++;
    rsort($fullFileMap[$fileId]['indexes']);
}

krsort($fullFileMap);

function isFreeSpace(int $startIndex, int $endIndex, array $partition): bool
{
    for ($x = $startIndex; $x < $endIndex; $x++) {
        if ($partition[$x]['type'] !== 'free') {
            return false;
        }
    }

    return true;
}

foreach ($fullFileMap as $fileID => $file) {
    $requiredSpace = $file['size'];
    $indexes = $file['indexes'];
    $firstFileIndex = min($file['indexes']);

    for ($x = 0; $x < $firstFileIndex; $x++) {
        if (isFreeSpace($x, $x + $requiredSpace, $partition)) {
            for ($i = $x; $i < ($x + $requiredSpace); $i++) {
                foreach ($indexes as $fileIndex) {
                    $fs = $partition[$i];
                    $partition[$i] = $partition[$fileIndex];
                    $partition[$fileIndex] = $fs;
                }
            }
            break;
        }
    }
}

$sum = 0;

foreach (array_filter($partition, fn (array $sector) => $sector['type'] === 'file') as $index => $file) {
    $sum += ($index * $file['id']);
}

echo $sum . "\n";