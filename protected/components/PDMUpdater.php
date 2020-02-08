<?php


class PDMUpdater
{

    private $downloadUrl;
    private $basePath;
    const TEMP_FOLDER = "temp";
    private $error;
    const UPDATE_URL = "http://www.igestdevelopment.com/updates_log.txt";

    /**
     * PDMUpdater constructor.
     */
    public function __construct( $url="" )
    {
        if($url){
            $this->setDownloadUrl($url);
        }
        $this->basePath = Yii::app()->basePath;
    }

    public function createTempFolder(){
        if (!file_exists($this->getTarBasePath())) {
            mkdir($this->getTarBasePath(), 0777, true);
        }
    }

    public function downloadUpdatePackage(){
        return $this->downloadFile();
    }

    public function getErrorMessage(){
        return $this->error;
    }

    public function update(){
        if(!$this->downloadUrl){

        }else{
            $this->createTempFolder();
            $this->downloadFile();
            $this->extractContents();
        }
    }

    public function cleanUp(){
        $tempPath = $this->getTarBasePath() . "/*";
        exec("rm -rf ".$tempPath, $log);
    }

    public function validateUpdate(){
        $valid = true;
        $url = $this->getDownloadUrl();
        if (filter_var($url, FILTER_VALIDATE_URL) == false){
            $valid = false;
            $this->error = "Url is not valid (" . $url . ")";
            return false;
        }else{
            $filename = $this->getFileName();
            $file_parts = pathinfo($filename);
            $info = parse_url($url);
            if($info["host"] != "wfspace.sfo2.digitaloceanspaces.com"){
                $valid = false;
                $this->error = "Not a valid update path (".$info["host"]. ")";
            }
            if(isset($file_parts['extension']) &&  $file_parts['extension']!= "gz"){
                $valid = false;
                $this->error = "File is not tar. (". $filename.")";
            }
        }

        return $valid;
    }



    public function setDownloadUrl($var){
        $this->downloadUrl = $var;
    }

    public function getDownloadUrl(){
        return trim($this->downloadUrl);
    }

    private function getFileName(){
        $name = basename($this->getDownloadUrl()); // to get file name
        return $name;
    }

    private function getTarBasePath(){
        return $this->basePath
            . '/../'.self::TEMP_FOLDER;
    }

    private function getTarPath(){
        return $this->getTarBasePath().'/'
            . $this->getFileName();
    }

    private function downloadFile(){
        file_put_contents($this->getTarPath(), fopen($this->getDownloadUrl(), 'r') );
        return true;
    }

    public function extractContents(){
        $command = "tar -xzvf ". $this->getTarPath() . " -C ". $this->getExtractDestination();
        return shell_exec($command);
    }

    private function getExtractDestination(){
        return $this->basePath . '/../'
            . self::TEMP_FOLDER.'/';
    }

    private function updatePath(){
        return $this->basePath . '/../';
    }

    private function updateSourcePath(){
        return $this->basePath . '/../'. self::TEMP_FOLDER .'/'
            . pathinfo(pathinfo($this->getFileName(), PATHINFO_FILENAME), PATHINFO_FILENAME) . "/";
    }

    public function copyUpdatedFiles(){
        $params = $this->updateSourcePath() . " " . $this->updatePath();
        $fullCommand = $this->basePath . "/update.sh {$params}";
        return shell_exec($fullCommand);
    }


}
