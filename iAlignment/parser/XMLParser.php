<?
require_once("Parser.php");

class XMLParser extend Parser{
	

	public parse(){
		// parse the contents of the file into a XML object
		$XML=simplexml_load_file($this->path);
		// the XML input file has a specific Structure, 
		// first we get the headers, the source description

		// read the sources
		$sources=$xml->sources;
		foreach($sources as $source){
			$id=$source->attributes()['id'];
			$description="".$source;
			$this->sources[$id]=$description;
		}		
		
		// read the parallel sentences
		$sentences=$xml->sentences;
		foreach($sentences as $sentence){
			$sentenceId=$sentence->attributes()['SentenceId'];
			foreach($sentence-> as $p){
			 $sourceId=$p->attributes()['sourceId'];
			 $sen="".$p;
			 $this->parallelSentences[$sentenceId][$sourceId]=$sen;
			}
		}
		
		return $this->parallelSentences;
	}

}



?>
