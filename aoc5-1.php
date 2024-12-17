<?php

$grid = [];

$processingUpdates = false;
$pageOrder = [];
$updates = [];

foreach (file(__DIR__ . '/day5.txt', FILE_IGNORE_NEW_LINES & FILE_SKIP_EMPTY_LINES) as $y => $line) {
    if (!strpos($line, '|')) $processingUpdates = true;

    if (!$processingUpdates) {
        $pageOrder[] = array_map('intval', explode('|', $line));
    } else {
        $updates[] = array_map('intval', explode(',', $line));
    }
}

function sortUpdate(array $update, array $pageOrder): array
{    
    foreach ($pageOrder as $order) {
        [$check, $mustComeBefore] = $order;
        $pairExists = in_array($check, $update) && in_array($mustComeBefore, $update);

        // a page order rule requires both the number to check and the dependency to exist in the update
        if ($pairExists) {
            $idx1 = array_search($check, $update);
            $idx2 = array_search($mustComeBefore, $update);

            // if the position of the number to check exists before the position of the number which should come before it, swap them
            if ($idx1 > $idx2) {
                $tmp = $update[$idx2];
                $update[$idx2] = $update[$idx1];
                $update[$idx1] = $tmp;
                $update = array_values($update);
            }
        }
    }

    return $update;
}

$sum = 0;

foreach ($updates as $update) {
    $test = sortUpdate($update, $pageOrder);
    
    if ($test == $update) {
        $sum += $update[floor(count($update) / 2)];
    }
}

echo "$sum\n";