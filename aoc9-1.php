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

$free = array_filter($partition, fn (array $sector) => $sector['type'] === 'free');
$files = array_filter($partition, fn (array $sector) => $sector['type'] === 'file');

function gapsExist(array $partition): bool
{
    $gaps = 0;
    $partitionCount = count($partition);

    for ($i = 0; $i < ($partitionCount - 1); $i++) {
        if ($partition[$i]['type'] === 'file' && $partition[$i + 1]['type'] === 'free') {
            $gaps++;
            if ($gaps > 1) return true;
        }
    }

    return false;
}

while (gapsExist($partition)) {
    $freeData = reset($free);
    $freeIdx = key($free);
    unset($free[$freeIdx]);
    $fileData = end($files);
    $fileIdx = key($files);
    unset($files[$fileIdx]);
    $partition[$freeIdx] = $fileData;
    $partition[$fileIdx] = $freeData;
}

$sum = 0;

foreach (array_filter($partition, fn (array $sector) => $sector['type'] === 'file') as $index => $file) {
    $sum += ($index * $file['id']);
}

echo $sum . "\n";