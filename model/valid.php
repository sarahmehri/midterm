<?php
/**
 * model/validate.php
 *
 */
function validNames($names)
{

    $names = trim($names);
    return !empty($names) && ctype_alpha($names);//&& in_array(strtolower($food));

}



function validAnswers($selectedAns){
    $validAns = getAnswers();
    foreach($selectedAns as $selected){
        if(!in_array($selected, $validAns)){
            return false;
        }
    }
    return true;
}
