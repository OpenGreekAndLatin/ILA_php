<?php

class Token{
		
	public static $levensteinThreshold = 0.3;
	

    static function removeDiacritics($token){
    	$original=	 array("ῆ","ῒ","ῒ","ῖ","ῶ","ά","ά","ὰ","έ","ὲ","ή","ὴ","ί","ὶ","ό","ό","ὸ","ύ","ὺ","ώ","ὼ","ᾴ","ᾲ","ῄ","ῂ","ῴ","ῲ","Ά","Ὰ","Έ","Ὲ","Ὴ","Ή","Ί","Ὶ","Ὸ","Ό","Ύ","Ὺ","Ώ","Ὼ");
    	$replacement=array("η","ι","ι","ι","ω","α","α","α","ε","ε","η","η","ι","ι","ο","ο","ο","υ","υ","ω","ω","ᾳ","ᾳ","ῃ","ῃ","ῳ","ῳ","Α","Α","Ε","Ε","Η","Η","Ι","Ι","Ο","Ο","Υ","Υ","Ω","Ω");
    	return str_replace($original,$replacement,$token);
	}
	
	// remove non alphanumeric character 
    static function removeNonAlphanumeric($token){
		$temp=preg_replace("/\P{L}+/u", " ", $token); // replace non letter charecters with whitespace
		return preg_replace("/[ \t\n\r]+/si"," ",$temp); // remove multiple whitespaces
	}	
	
	// convert $token to lowercase
    static function lowerCase($token){
		return mb_strtolower($token, 'UTF-8');
	}
	

	// Check the similarity of two tokens according to Levenshtein Distance Metric, using $levensteinThreshold 
	static function isSimilarTo( $token1, $token2){

		 $l1 = strlen( $token1 ) ; // Length of  $token1 
		 $l2 = strlen( $token2 ) ; // Length of  $token2 
		 
		 $dis = range( 0 , $l2 ) ; // Erste Zeile mit (0,1,2,...,n) erzeugen
								  // $dis stellt die vorrangeganene Zeile da.
		 for ( $x = 1 ; $x <= $l1 ; $x ++ ) {        
			 $dis_new[ 0 ] = $x ; // Das erste element der darauffolgenden Zeile ist $x, $dis_new ist damit die aktuelle Zeile mit der gearbeitet wird
			 for ( $y = 1 ; $y <= $l2 ; $y ++ ) {
				 if( $token1[ $x - 1 ] == $token2[ $y - 1 ] ) 	 $c = 0 ;
				 else   								 $c = 1 ;
				 $dis_new[ $y ] = min ( $dis[ $y ] + 1 , $dis_new[ $y - 1 ] + 1 , $dis[ $y - 1 ] + $c ) ;	 
			 }
			 $dis = $dis_new ;              
		 }	
		 $distance=$dis[ $l2 ] ; // return the distance: # differenct letters
		 
		 $maxLength=max(strlen($token1),strlen($token2));
		 
		 if($maxLength!=0)
			$NormalisedDistance=$distance/$maxLength;
		 else 
			$NormalisedDistance=1;
		 return ($NormalisedDistance < self::$levensteinThreshold);
	}

    /**
     * @param float $levensteinThreshold
     */
    public static function setLevensteinThreshold($levensteinThreshold)
    {
        self::$levensteinThreshold = $levensteinThreshold;
    }
}

?>