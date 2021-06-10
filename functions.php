<?php
//import needed variables from episode_links.php file and user input
require "episode_links.php";
$season = $_GET['season'];
$keyword = $_GET['keyword'];

//Checks the season selected by the user.  Depending on selected season,
//a different folder is selected within "transcripts" to be the value of the $pathToDirectory variable.
//Then, the findMatches() function is run to check all txt files within the set path for the user defined $keyword
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

    case 'pzn':
        $pathToDirectory = "./transcripts/pzn/*";
        $response = findMatches($pathToDirectory, $keyword, $episodeLinks);
        echo $response;
        break;
    case 'sf':
        $pathToDirectory = "./transcripts/sf/*";
        $response = findMatches($pathToDirectory, $keyword, $episodeLinks);
        echo $response;
        break;
}

function findMatches($pathToDirectory, $keyword, $episodeLinks){
    //needed variable are defined
    $results = array();
    $htmlString = "";
    $countMatches = 0;
    $fileList = glob($pathToDirectory);
    //force $fileList to follow ascending numeric order
    natsort($fileList);

    //open each txt file in the path and split the contents into sentences.
    //then check each sentence for the existence of $keyword.  If it exists,
    //push the sentence to the $results array
    foreach ($fileList as $search) {
        //grab the first line of the txt file and set it as the $episodeTitle.  Trim to remove leading or hanging whitespace
        $episodeTitle = fgets(fopen($search, 'r'));
        $episodeTitle = trim($episodeTitle);
        $contents = file_get_contents($search);
        $sentences = preg_split('/(?<=[.])\s+(?=[a-z])/i', $contents);

        foreach ($sentences as $sentence) {
            if (preg_match('/\b'. $keyword .'\b/i', $sentence)) {
                //before adding the episode title to the $results array, check whether it already exists
                //if it doesn't, add it and format it into a link to the episode transcript itself
                if (!in_array($episodeTitle, $results)) {
                    //find episode link by searching for the episode title in the $episodeLinks array and
                    //then using the returned index to access the paired link value
                    //var_dump($episodeTitle);
                    $arrayIndex = array_search($episodeTitle, array_column($episodeLinks, 'title'));
                    $link = $episodeLinks[$arrayIndex]['link'];
                    $episodeTitle = "<p><a class='episode_title' href=$link target='_blank'>$episodeTitle</a></p>";
                    //subtracts episode titles from count of total number of $keyword occurrences
                    $countMatches--;
                    array_push($results, $episodeTitle);
                }
                array_push($results, $sentence);
            }
        }
    }

    //highlights the keyword in the sentence
    foreach ($results as $result) {
        $finalResult = preg_replace('/\b'. $keyword .'\b/i', '<span class="keyword_highlight">$0</span>', $result);
        //skip formatting the episode title as a search result
        if (!strpos($finalResult, "<a class='episode_title'")){
            $htmlString .= '<p class="search_result">' . $finalResult . '</p>';
        }else{
            $htmlString .= $finalResult;
        }
        //increment to store total occurrences of $keyword
        $countMatches++;
    }
    //add number of results to the beginning of $htmlString and return it
    $totalResults = '<p class="total_result">Total Results: <span class=\'bold\'>' . $countMatches . '</span></p>';
    return $htmlString = $totalResults . $htmlString;
}

