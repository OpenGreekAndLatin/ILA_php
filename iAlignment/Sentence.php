<?php
// Sentence CLASS
require_once("Token.php");

class Sentence{
	
	private $text="";
	public $tokens=array();
	
	public function Sentence($txt){
		$this->setText($txt);
	}
	
	function setText($txt){
		$this->text=$txt;
		if (strpos($txt, '||') !== false)
			$this->WStokenizer();
		else
			$this->AdvancedTokenizer();
	}
	
		
	// Tokenize the sentence and save it as an array of tokens
	// The whitespace tokenizer breaks on whitespace
	function WStokenizer(){
		$tokens= explode(" ",$this->text);
		foreach($tokens as $k=>$tok)
			$this->tokens[]=$tok;				
	}
	
	// Advanced Tokenizer, breaks on punctuations and special charachters
	function AdvancedTokenizer()
	{ 
	 $original=   array("·","ॱ","·",'”'  , '“' , '؛' , '،' , "." , "," , ":" , ";" , "[" , "]" , "(" , ")" , "{" , "}" , "\"" , "'" , "\“" , "?" , "!" );
	 $replacement=array(" · "," ॱ "," · ",' " ',' " ',' ; ',' , '," . "," , "," : "," ; "," [ "," ] "," ( "," ) "," { "," } "," \" "," ' "," \“ "," ? "," ! ");

	 $temp=str_replace($original,$replacement,trim($this->text));
	 $this->text=preg_replace("/[ \t\n\r]+/si"," ",$temp); // replace multiple whitespaces with single whitespace
	 $this->tokens=explode(" ",$this->text); 
	}
	
}


?>