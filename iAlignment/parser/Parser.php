<?
class Parser{
	
	protected $path="";
	protected $parallelSentences=array();
	protected $sources=array();
	
// Constructor, takes one parameter $p, path of the input file
	public function Parser($p){
		$this->path=$p;
	}

	public function parse_csv(){
		// read the contents of the file into a string
		$content=file_get_contents($this->path);
		// split the contents into a list of lines
		$lines=explode("\n",$content);
		foreach($lines as $id=>$line){
			// split each line to a list of sentences
			$cells=explode("\t",$line);
			$this->parallelSentences[$id]=$cells;
		}
		return 	$this->parallelSentences;
	}

	public function parse_txt(){
		// read the contents of the file into a string
		$content=file_get_contents($this->path);
		$sentences=array();
		// split the contents into a list of lines
		$lines=explode("\n",$content);
		foreach($lines as $id=>$line){
			if(trim($line)==""){
				if(sizeof($sentences) > 0){
					$this->parallelSentences[]=$sentences;
					$sentences=array();
				}
			}else{
				$sentences[]=$line;
			}
		}
		return 	$this->parallelSentences;
	}

}



?>
