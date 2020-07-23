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
    $fileList = glob($pathToDirectory);
    natsort($fileList);

    foreach ($fileList as $search) {
        $episodeTitle = fgets(fopen($search, 'r'));
        $episodeTitle = trim($episodeTitle);
        $contents = file_get_contents($search);
        $sentences = preg_split('/(?<=[.])\s+(?=[a-z])/i', $contents);

        foreach ($sentences as $sentence) {
            if (strpos($sentence, $keyword)) {
                if (!in_array($episodeTitle, $results)) {
                    $array = array_search($episodeTitle, array_column($episodeLinks, 'title'));
                    $link = $episodeLinks[$array]['link'];
                    $episodeTitle = "<p><a class='episode_title' target='_blank'>$episodeTitle</a></p>";
                    $countMatches--;
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
        $countMatches++;
    }

    $totalResults = 'Total Results: <span class=\'number_result\'>' . $countMatches . '</span>';
    return $htmlString = $totalResults . $htmlString;
}
//"﻿Autumn in Hieron 10: Chekhov’s Torture Elf"
//"Autumn in Hieron 10: Chekhov’s Torture Elf"

