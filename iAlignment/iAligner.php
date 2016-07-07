<?php
require_once("Token.php");
require_once("Sentence.php");
require_once("Viewer.php");
class iAligner{

	protected $sentence1;
	protected $sentence2;

	
	// Alignment Options
	protected $NonAlphanumeric=0;
	protected $casesensitive=0;
	protected $diacritics=0;
	protected $levenshtein=0;

	// this variables will be ued in Needlman-Wunsch Algorithm
	// Changing these values will produce different result
	protected $gap=-4;
	protected $mismatch=-5;
	protected $match=8;

	// $matrix is a 2 dimensional array to save the scores of Needlman-Wunsch Algorithm
	protected $matrix=array();
	protected $optimal_alignment=array();

	public function Aligner(){
	}
	
	protected function setSentences($s1,$s2)
	{
	 $this->sentence1=new Sentence(trim($s1));
	 $this->sentence2=new Sentence(trim($s2));
	}

	public function setOptions($punc=1,$case=0,$diac=1,$lev=0)
	{
	 $this->NonAlphanumeric=$punc;
	 $this->casesensitive=$case; // 1: case sensitive, 0: not case sensitive 
	 $this->diacritics=$diac;
	 $this->levenshtein=$lev;
	}
	
// first step: intialize the alignment matrix	
	protected function initialization()
	{
		$this->matrix=array(); // reset Matrix variable
		
		$m=count($this->sentence1->tokens); // Length of the first sentence
		$n=count($this->sentence2->tokens); // Length of the second sentence

		// initialize the matrix
		for($i=0;$i<= $m;$i++)
			for($j=0;$j<= $n;$j++)
				$this->matrix[$i][$j]['val']=0;
		for($i=0;$i<= $m;$i++)
			$this->matrix[$i+1][0]['val']=($i+1)*$this->gap;
		for($i=0;$i<= $n;$i++)
			$this->matrix[0][$i+1]['val']=($i+1)*$this->gap;		
		// End of initialization
	}

// second step: fill the matrix with values according to needlemann wunsch schema 
	protected function fillMarix()
	{
		$m=count($this->sentence1->tokens); // Length of the first sentence
		$n=count($this->sentence2->tokens); // Length of the second sentence
	
		for($i=0;$i<= $m;$i++){
			for($j=0;$j<= $n;$j++){
				$sc=$this->mismatch;
				$aligned=$this->isAligned($this->sentence1->tokens[$i],$this->sentence2->tokens[$j]);
				
				if($aligned[0])
					$sc = $this->match;

				$ma=$this->matrix[$i-1][$j-1]['val'] + $sc; // Matching/Mismatching
				$hgap = $this->matrix[$i-1][$j]['val'] + $this->gap; // Horizental gap
				$vgap = $this->matrix[$i][$j-1]['val'] + $this->gap; // Vertical gap

				$MaxValue=max($ma,$hgap,$vgap);

				$pointer="NW";
				$alignedclass=$aligned[1];
				if($MaxValue==$hgap && $MaxValue > $ma){
					$pointer="UP";
					$alignedclass="Gap";
				}
				else if($MaxValue==$vgap && $MaxValue > $ma){
					$pointer="LE";
					$alignedclass="Gap";
				}

				
				$this->matrix[$i][$j]['val']=$MaxValue;
				$this->matrix[$i][$j]['pointer']=$pointer;
				$this->matrix[$i][$j]['class']=$alignedclass;
			}	
	}
}

// third step: extract the optimal alignment from the matrix
	protected function GetOptimalAlignment()
	{
		$m=count($this->sentence1->tokens); // Length of the first sentence
		$n=count($this->sentence2->tokens); // Length of the second sentence

		$this->optimal_alignment['sentence1'] = array();
		$this->optimal_alignment['sentence2'] = array();
		$this->optimal_alignment['relation'] = array();

	
		$i=$m-1;$j=$n-1;
		while($i >= 0 && $j >= 0) { // Start interation
		$base1 = $this->sentence1->tokens[$i];
		$base2 = $this->sentence2->tokens[$j];
		$pointer = $this->matrix[$i][$j]['pointer'];
		$class = $this->matrix[$i][$j]['class'];
		if($pointer == "NW") {
			$i--;
			$j--;
			if($this->isAligned($base1,$base2)){
			$this->optimal_alignment['sentence1'][]=$base1;
			$this->optimal_alignment['sentence2'][]=$base2;
			$this->optimal_alignment['relation'][]=$class;
			}
			else{
			$this->optimal_alignment['sentence1'][]=$base1;
			$this->optimal_alignment['sentence2'][]=$base2;
			$this->optimal_alignment['relation'][]=$class;
/*
			$this->optimal_alignment['sentence1'][]="";
			$this->optimal_alignment['sentence2'][]=$base2;
			$this->optimal_alignment['relation'][]="Not Aligned";
*/			}
		} else if($pointer == "LE") {
			$j--;
			$this->optimal_alignment['sentence1'][]="";
			$this->optimal_alignment['sentence2'][]=$base2;
			$this->optimal_alignment['relation'][]=$class;
		}else if($pointer == "UP") {
			$i--;
			$this->optimal_alignment['sentence1'][]=$base1;
			$this->optimal_alignment['sentence2'][]="";
			$this->optimal_alignment['relation'][]=$class;
		}
	 }// End interation
	    
	    if($i < 0) { 
	 // copy the rest of sentence2 to the optimal Alignment
	  while($j >= 0) {
		$base2 = $this->sentence2->tokens[$j];
		$j--;
		$this->optimal_alignment['sentence1'][]="";
		$this->optimal_alignment['sentence2'][]=$base2;  
		$this->optimal_alignment['relation'][]="Gap";
	  } // End While
	 } // End if
	  
	    if($j < 0) { 
	 // copy the rest of sentence1 to the optimal Alignment
	  while($i >= 0) {
		$base1 = $this->sentence1->tokens[$i];
		$i--;
		$this->optimal_alignment['sentence1'][]=$base1;
		$this->optimal_alignment['sentence2'][]="";  
		$this->optimal_alignment['relation'][]="Gap";
	  } // End While
	 } // End if
  
		$this->optimal_alignment['sentence1']= array_reverse($this->optimal_alignment['sentence1']);
		$this->optimal_alignment['sentence2']= array_reverse($this->optimal_alignment['sentence2']); 
		$this->optimal_alignment['relation']= array_reverse($this->optimal_alignment['relation']); 

	} // End of GetOptimalAlignment
	
// check if the two words are aligned or not taking in account alignment's options
	protected function isAligned($w1,$w2)
	{
		$w1=trim($w1);
		$w2=trim($w2);

		if($w1==$w2)
			return array(True,"Aligned-complete");

		if($this->NonAlphanumeric==1){         // ignore NonAlphanumeric
			$w1=Token::removeNonAlphanumeric($w1);
			$w2=Token::removeNonAlphanumeric($w2);
			if($w1==$w2)
				return array(True,"Aligned-removedNonAlphanumeric");
		}

		if( $this->diacritics==1){		// ignore 	diacritics

		  $w1=Token::removeDiacritics($w1);
		  $w2=Token::removeDiacritics($w2);
			if($w1==$w2)
				return array(True,"Aligned-removeddiacritics");
		}

		if($this->casesensitive==0){		// 	convert words to lower case
			
		  $w1=Token::lowerCase($w1);
		  $w2=Token::lowerCase($w2);
			if($w1==$w2)
				return array(True,"Aligned-case");
		}
	
		$similar=False;
		if($this->levenshtein==1)
		{
		  $similar=Token::isSimilarTo($w1,$w2);
			if($similar)
				return array(True,"Aligned-levenshtein");
		}
	
		if($w1==$w2 || $similar)
			return array(True,"Aligned-combination");
		 else 
			return array(False,"notAligned");
	}
	
// PairwaiseAlignment: align two sentences	
	public function PairwiseAlignment($sen1,$sen2)
	{
	 $this->setSentences($sen1,$sen2);
	 $this->initialization();
	 $this->fillMarix();
	 $this->GetOptimalAlignment();
	 
	 return $this->optimal_alignment;
	}


/*
// this function is used for testing purposes to print the score matrix
	public function printMatrix()
	{
		$m=count($this->sentence1->tokens); // Length of the first sentence
		$n=count($this->sentence2->tokens); // Length of the second sentence
		echo "<table class='table'><tr><td></td>";
		for($i=0;$i<$m;$i++)
			echo "<th>".$this->sentence1->tokens[$i]."</th>";
		echo "</tr>";
		for($j=0;$j<$n;$j++)
		{
			echo "<tr><th>".$this->sentence2->tokens[$j]."</th>";
			for($i=0;$i<$m;$i++)
				echo "<td>".$this->matrix[$i][$j][val]."(".$this->matrix[$i][$j]['pointer'].")</td>";
		}
	}
*/
}


?>