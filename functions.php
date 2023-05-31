<?php

// ALl the body parts
$bodyParts = ["no_human","head","hands","human","almost_dead","full"];

function getCurrentPicture($part){
    return "./images/hangman_". $part. ".png";
}


// Get all the hangman Parts
function getParts(){
    global $bodyParts;
    return isset($_SESSION["parts"]) ? $_SESSION["parts"] : $bodyParts;
}

// add part to the Hangman
function addPart(){
    $parts = getParts();
    array_shift($parts);
    $_SESSION["parts"] = $parts;
}

// get Current Hangman Body part
function getCurrentPart(){
    $parts = getParts();
    return $parts[0];
}


/*
    Function Name: fetchWordArray()
    Return values: Returns an array of characters.
*/


    function fetchWordArray($wordFile)
    {
        $fseek = fopen($wordFile,'r');
        $fstat = filesize($wordFile);
        echo 'Length of File: '.($fstat).'<br>';
           if ($fseek)
        {
            $random_line = null;
            $line = null;
            $count = 0;
            while (($line = fgets($fseek)) !== false) 
            {
                $count++;
                if(rand() % $count == 0) 
                {
                      $random_line = trim($line);
                }
        }
        if (!feof($fseek)) 
        {
            fclose($fseek);
            return null;
        }else 
        {
            fclose($fseek);
        }
    }
        $answer = str_split($random_line);
        return $answer;
    }
/*
    Function Name: hideCharacters()
    Parameters: Word whose characters are to be hidden.
    Return values: Returns a string of characters.
*/
    function hideCharacters($answer)
    {
        $noOfHiddenChars = floor((sizeof($answer)/2)+1);
        $count = 0;
        $hidden = $answer;
        while ($count < $noOfHiddenChars )
        {
            $rand_element = rand(0,sizeof($answer)-2);
            if( $hidden[$rand_element] != '_' )
            {
                $hidden = str_replace($hidden[$rand_element],'_',$hidden,$replace_count);
                $count = $count + $replace_count;
            }
        }
        return $hidden;
    }
/*
    Function Name: checkAndReplace()
    Parameters: UserInput, Hidden string and the answer.
    Return values: Returns a character array.
*/
    function checkAndReplace($userInput, $hidden, $answer)
    {
        $i = 0;
        $wrongGuess = true;
        while($i < count($answer))
        {
            if ($answer[$i] == $userInput)
            {
                $hidden[$i] = $userInput;
                $wrongGuess = false;
            }
            $i = $i + 1;
        }
        if (!$wrongGuess) $_SESSION['attempts'] = $_SESSION['attempts'] - 1;
            else{
                if(!isBodyComplete()){
                    addPart(); 
                }
            }
        return $hidden;
    }
    
    function isBodyComplete(){
    $parts = getParts();
    // is the current parts less than or equal to one
    if(count($parts) <= 1){
        return true;
    }
    return false;
}
/*
    Function Name: checkGameOver()
    Parameters: Maximum attempts, no. of attempts made by user, Hidden string and the answer.
    Return values: Returns a character array.
*/
    function checkGameOver($MAX_ATTEMPTS,$userAttempts, $answer, $hidden)
    {
        if ($userAttempts >= ($MAX_ATTEMPTS-1))
            {
                echo "Game Over. The correct word was ";
                foreach ($answer as $letter) echo $letter;
                echo '<br><form action = "" method = "post"><input type = "submit" name' .
                  ' = "newWord" value = "Try another Word"/></form><br>';
                die();
            }
            if ($hidden == $answer)
            {
                echo "Word Cracked Successfully. The correct word is indeed ";
                foreach ($answer as $letter) echo $letter;
                echo '<br><form action = "" method = "post"><input ' .
                  'type = "submit" name = "newWord" value = "Try another Word"/></form><br>';
                die();
            }
    }
?>