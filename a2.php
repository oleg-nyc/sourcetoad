<?php
function sortingArr($arr, $sort_arr, &$result=[]){
    if( !is_array($arr) || !is_array($sort_arr) ){
        echo "wrong format";
        return false;
    } 
    foreach( $arr as $k => $v ){
        if( !is_array($v) )
        {
            if( in_array($k, $sort_arr) )
            {
                $result[$k][] = [$k => $v];
                sort( $result[$k] );
            }
            else 
                $result['unsorted'][] = [$k => $v];
        }
        else 
            sortingArr($v, $sort_arr, $result);
    }
    return $result;
}