<?php
/**
 * Created by PhpStorm.
 * User: Jean-Michel
 * Date: 19/11/17
 * Time: 12:04
 */

namespace OpCacheBundle\Controller;


class OpCache
{
    private $phpVersion;
    private $opcVersion;
    private $totalMemory;
    private $freeMemory;
    private $usedMemory;
    private $wastedMemory;
    private $hitRate;
    private $fileCount;
    private $fileMemory;

    private function getStringFromPropertyAndValue($property, $value)
    {
        if ($value === false) {
            return 'false';
        }

        if ($value === true) {
            return 'true';
        }

        switch ($property) {
            case 'used_memory':
            case 'free_memory':
            case 'wasted_memory':
            case 'memory_consumption':
                return $value;
                break;
            case 'opcache.memory_consumption':
                return $value;
                break;
            case 'current_wasted_percentage':
            case 'opcache_hit_rate':
                return number_format($value, 2).'%';
                break;
            case 'blacklist_miss_ratio':
                return number_format($value, 2);
                break;
        }

        return $value;
    }

    public function convertSize($taille){
        $ra = 0;
        if ($taille > 1048576) {
            $ra = sprintf("%.2f MB", $taille/1048576);
        } elseif ($taille > 1024) {
            $ra = sprintf("%.2f kB", $taille/1024);
        } else {
            $ra = sprintf("%d bytes", $taille);
        }
        return $ra;
    }

    private function fillFileMemory($status){
        foreach ($status as $key => $value) {

            if ($key == 'scripts') {
                foreach ($value as $k => $v) {
                  $taille = 0;
                    $v = $this->getStringFromPropertyAndValue($k,$v);
                    foreach ($v as $sk => $sv){
                        if ($sk == 'memory_consumption'){
                            $taille =  $sv;
                        }
                    }
                    $this->fileMemory[] = ['fileName' => $k,
                        'fileSize' => $taille,
                        'fileSizeText' => $this->convertSize($taille)];

                }
            }
        }
    }

    private function defMem($val){
        // fonction non trouvée pour l'exercice
        $trouve = 0;
        for($i=0; $i <= strlen($val);$i++){
            if (substr($val,$i,1 )== " "){
                $trouve = $i;
            }
        }
        return  'def_mem à voir ';

    }
    public function __construct()
    {
        $config = opcache_get_configuration();
        $status = opcache_get_status();

        $stats = $status['opcache_statistics'];
        $mem = $status['memory_usage'];

        $this->phpVersion = phpversion();
        $this->opcVersion = $config['version']['version'] ;
        $this->totalMemory = $config['directives']['opcache.memory_consumption'];
        $this->usedMemory = $mem['used_memory'];
        $this->wastedMemory = $mem['wasted_memory'];
        $this->hitRate = round($stats['opcache_hit_rate'], 2);
        $nbFile = (isset($status["scripts"])) ?   count($status["scripts"]) : 0;
        $this->fileCount = $nbFile;
        $this->fileMemory = [];
        $this->fillFileMemory($status);

    }


    public function setPhpVersion($phpVersion){$this->phpVersion = $phpVersion;}
    public function getPhpVersion(){return $this->phpVersion;}

    public function setOpcVersion($opcVersion){$this->opcVersion = $opcVersion;}
    public function getOpcVersion(){return $this->opcVersion;}

    public function setTotalMemory($totalMemory){$this->totalMemory = $totalMemory;}
    public function getTotalMemory(){return $this->totalMemory;}

    public function setFreeMemory($freeMemory){$this->freeMemory = $freeMemory;}
    public function getFreeMemory(){return $this->freeMemory;}

    public function setUsedMemory($usedMemory){$this->usedMemory = $usedMemory;}
    public function getUsedMemory(){return $this->usedMemory;}

    public function setWastedMemory($wastedMemory){$this->wastedMemory = $wastedMemory;}
    public function getWastedMemory(){return $this->wastedMemory;}

    public function setHitRate($hitRate){$this->hitRate = $hitRate;}
    public function getHitRate(){return $this->hitRate;}

    public function setFileCount($fileCount){$this->fileCount = $fileCount;}
    public function getFileCount(){return $this->fileCount;}

    public function setFileMemory($fileMemory){$this->fileMemory = $fileMemory;}
    public function getFileMemory(){return $this->fileMemory;}


}