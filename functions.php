<?php
require "episode_links.php";
$season = $_GET['season'];
$keyword = $_GET['keyword'];

switch ($season){
    case 'aih':
        $pathToDirectory = "./transcripts/aih/*";
        $response = findMatches($pathToDirectory, $keyword, $episodeLinks);
        echo $response;
        break;

    case 'mar':
        $pathToDirectory = "./transcripts/mar/*";
        $response = findMatches($pathToDirectory, $keyword, $episodeLinks);
        echo $response;
        break;

    case 'wih':
        $pathToDirectory = "./transcripts/wih/*";
        $response = findMatches($pathToDirectory, $keyword, $episodeLinks);
        echo $response;
        break;

    case 'sih':
        $pathToDirectory = "./transcripts/sih/*";
        $response = findMatches($pathToDirectory, $keyword, $episodeLinks);
        echo $response;
        break;

    case 'cw':
        $pathToDirectory = "./transcripts/cw/*";
        $response = findMatches($pathToDirectory, $keyword, $episodeLinks);
        echo $response;
        break;

    case 'tm':
        $pathToDirectory = "./transcripts/tm/*";
        $response = findMatches($pathToDirectory, $keyword, $episodeLinks);
        echo $response;
        break;

    case 'ropa':
        $pathToDirectory = "./transcripts/ropa/*";
        $response = findMatches($pathToDirectory, $keyword, $episodeLinks);
        echo $response;
        break;
}

function findMatches($pathToDirectory, $keyword, $episodeLinks){
    $results = array();
    $htmlString = "";
    $countMatches = 0;
    $keywordList = array();
    $keywordList[0] = strtolower($keyword);
    $keywordList[1] = ucfirst($keyword);
    $fileList = glob($pathToDirectory);
    natsort($fileList);

    foreach ($fileList as $search) {
        $episodeTitle = fgets(fopen($search, 'r'));
        $episodeTitle = trim($episodeTitle);
        $contents = file_get_contents($search);
        $sentences = preg_split('/(?<=[.])\s+(?=[a-z])/i', $contents);

        foreach ($sentences as $sentence) {
            if (stripos($sentence, $keyword)) {
                if (!in_array($episodeTitle, $results)) {
                    $arrayIndex = array_search($episodeTitle, array_column($episodeLinks, 'title'));
                    $link = $episodeLinks[$arrayIndex]['link'];
                    $episodeTitle = "<p><a class='episode_title' href=$link target='_blank'>$episodeTitle</a></p>";
                    $countMatches--;
                    array_push($results, $episodeTitle);
                }
                array_push($results, $sentence);
            }
        }
    }

    foreach ($results as $result) {
        $highlightedKeyword = '<span class="keyword_highlight">' . $keywordList[0] . '</span>';
        $newResult = str_replace($keywordList[0], $highlightedKeyword, $result);
        $highlightedKeywordUpeer = '<span class="keyword_highlight">' . $keywordList[1] . '</span>';
        $finalResult = str_replace($keywordList[1], $highlightedKeywordUpeer, $newResult);
        if (!strpos($newResult, "<a class='episode_title'")){
            $htmlString .= '<p class="search_result">' . $finalResult . '</p>';
        }else{
            $htmlString .= $newResult;
        }
        $countMatches++;
    }

    $totalResults = '<p class="total_result">Total Results: <span class=\'number_result\'>' . $countMatches . '</span></p>';
    return $htmlString = $totalResults . $htmlString;
}

