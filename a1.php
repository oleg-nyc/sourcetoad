<?php
function printArr($arr){
  if( !is_array($arr) ) return false;
    foreach( $arr as $k => $v ){
        if( !is_array($v) ) 
                            echo "<tr><td>" . $k ."</td><td>" . $v . "</td></tr>";
        else 
            printArr($v);
    }
} 