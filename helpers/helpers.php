<?php 

if(!function_exists('te_recluta_print_time'))
{
	function te_recluta_print_time($date){
        $time = '';

        $start_date = new DateTime($date);
        $since_start = $start_date->diff(new DateTime(date("Y-m-d")." ".date("H:i:s")));
        $time.= "Hace ";
        if($since_start->y==0){
            if($since_start->m==0){
                if($since_start->d==0){
                   if($since_start->h==0){
                       if($since_start->i==0){
                          if($since_start->s==0){
                             $time.= $since_start->s.' segundos';
                          }else{
                              if($since_start->s==1){
                                 $time.= $since_start->s.' segundo'; 
                              }else{
                                 $time.= $since_start->s.' segundos'; 
                              }
                          }
                       }else{
                          if($since_start->i==1){
                              $time.= $since_start->i.' minuto'; 
                          }else{
                            $time.= $since_start->i.' minutos';
                          }
                       }
                   }else{
                      if($since_start->h==1){
                        $time.= $since_start->h.' hora';
                      }else{
                        $time.= $since_start->h.' horas';
                      }
                   }
                }else{
                    if($since_start->d==1){
                        $time.= $since_start->d.' día';
                    }else{
                        $time.= $since_start->d.' días';
                    }
                }
            }else{
                if($since_start->m==1){
                   $time.= $since_start->m.' mes';
                }else{
                    $time.= $since_start->m.' meses';
                }
            }
        }else{
            if($since_start->y==1){
                $time.= $since_start->y.' año';
            }else{
                $time.= $since_start->y.' años';
            }
        }

        return $time;
    }
}

if(!function_exists('te_recluta_hide_contact'))
{
	function te_recluta_hide_contact($string){
	    $regex_email = '/[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})/';
	    $regex_phone = "/[0-9]{5,}|\d[ 0-9 ]{1,}\d|\sone|\stwo|\sthree|\sfour|\sfive|\ssix|\sseven|\seight|\snine|\sten/i";

	    $string = preg_replace($regex_email,'***',$string);
	    //$string = preg_replace($regex_phone,'***',$string);

	    return $string;
	}
}