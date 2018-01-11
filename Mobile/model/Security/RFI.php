<?php

class rfistop
{
	public $silarray = array("php", "txt", "asp", "aspx", "html"),
		   $dizinarray = array("public_html", "htdocs", "httpdocs", "httpsdocs");
    private $rfi;
    
	public function rfidurdur($durdur = "exit", $yapilacak = "index.php")
    {
		foreach($_REQUEST as $gelen=>$veri)
        {
			if(preg_match("#(./|http|.\\\)#si",$veri))
            {
				if(self::rfikontrol($veri) === false)
                {
					switch($durdur)
                    {
						case "exit":
							exit();
						break;
						case "header":
							header("Location:".$yapilacak);
						break;
						case "custom":
							echo $yapilacak;
						break;
					}
				}
                elseif(self::rfikontrol($veri) === true)
                {
					//echo "tamam";
				}
			}
		}
	}
    
	private function rfikontrol($suzulecek)
    {
		if(preg_match("#http#si",$suzulecek))
        {
			$urlal=array_reverse(explode("/",$suzulecek));
			return $this->detect($urlal[0]);
		}
        else
        {
			if(preg_match("#(..\\\|../|.\\\|./)#si",$suzulecek))
            {
				$dizinust="";
				$ustsayi="";
				if(preg_match("#/#si",__FILE__))
                {
					$dizinbol=array_reverse(explode("/",__FILE__));
				}
                else
                {
					$dizinbol=array_reverse(explode("\\",__FILE__));
				}
				
				foreach($this->dizinarray as $dizin)
                {
					$sayi=array_search($dizin,$dizinbol);
					if($sayi>0)
                    {
						$dizinust=$sayi;
					}
				}
				$ustsayi+=substr_count($suzulecek,".\\");
				$ustsayi+=substr_count($suzulecek,"./");
				if($dizinust<$ustsayi)
                {
					return false;
				}
                else
                {
					return true;
				}
			}
		}
	}
    
	private function detect($detect){
		$uzanti=array_reverse(explode(".",$detect));
		if(in_array($uzanti[0],$this->silarray)){
			return false;
		}else{
			return true;
		}
	}

}
?>