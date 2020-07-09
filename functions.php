<?php
$season = $_GET['season'];
$keyword = $_GET['keyword'];

switch ($season){
    case 'aih':
        $pathToDirectory = "./transcripts/aih/*";
        $response = findMatches($pathToDirectory, $keyword);
        echo $response;
        break;

    case 'mar':
        $pathToDirectory = "./transcripts/mar/*";
        $response = findMatches($pathToDirectory, $keyword);
        echo $response;
        break;

    case 'wih':
        $pathToDirectory = "./transcripts/wih/*";
        $response = findMatches($pathToDirectory, $keyword);
        echo $response;
        break;

    case 'sih':
        $pathToDirectory = "./transcripts/sih/*";
        $response = findMatches($pathToDirectory, $keyword);
        echo $response;
        break;

    case 'cw':
        $pathToDirectory = "./transcripts/cw/*";
        $response = findMatches($pathToDirectory, $keyword);
        echo $response;
        break;

    case 'tm':
        $pathToDirectory = "./transcripts/tm/*";
        $response = findMatches($pathToDirectory, $keyword);
        echo $response;
        break;

    case 'ropa':
        $pathToDirectory = "./transcripts/ropa/*";
        $response = findMatches($pathToDirectory, $keyword);
        echo $response;
        break;
}

function findMatches($pathToDirectory, $keyword){
    $results = array();
    $htmlString = "";
    $fileList = glob($pathToDirectory);
    natsort($fileList);

    foreach ($fileList as $search) {
        $contents = file_get_contents($search);
        $episodeTitle = fgets(fopen($search, 'r'));
        $episodeTitle = "<p class='episode_title'>$episodeTitle</p>";
        $sentences = preg_split('/(?<=[.])\s+(?=[a-z])/i', $contents);
        foreach ($sentences as $sentence) {
            if (strpos($sentence, $keyword)) {
                if (!in_array($episodeTitle, $results)) {
                    array_push($results, $episodeTitle);
                }
                array_push($results, $sentence);
            }

        }
    }
    foreach ($results as $result){
        $highlightedKeyword = '<span class="keyword_highlight">' . $keyword . '</span>';
        $newResult = str_replace($keyword, $highlightedKeyword, $result);

        $htmlString .= '<p class="search_result">' . $newResult . '</p>';
    }
    $totalResults = 'Total Results: <span class=\'number_result\'>' . count($results) . '</span>';
    return $htmlString = $totalResults . $htmlString;
}