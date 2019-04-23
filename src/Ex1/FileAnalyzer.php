<?php

namespace Socloz\Recruitment\Ex1;

//ini_set('max_execution_time', 120);

/**
 * Class FileAnalyzer
 * @package Socloz\Recruitment\Ex1
 */
class FileAnalyzer
{
    /**
     * @param string $fileName
     * @return Iterator
     */
    public function readFile($fileName)
    {
        // Si le fichier est inexistant, on ne continue pas
        if (!$file = fopen($fileName, 'r')) return;
        
        while (($line = fgets($file)) !== false) yield $line;
 
        fclose($file);
    }    

    /**
     * @param int $storeId
     * @param int $skuId 
     * @param int $quantity
     * @param Store $store
     * @return Store
     */
    public function fillStore(int $storeId, int $skuId, int $quantity, Store $store) : Store
    {
        if ((int)$quantity > 0) 
        {
            $store->setSku($skuId, null);
            $store->addNbAvailability($quantity);
        }
        $store->calculMaxQuantity($quantity);
        $store->addNbOccurrence();
        $store->addQuantity($quantity);
      
        return $store;
    }

    /**
     * @param string $dataFileName
     * @return array
     */
    public function generateStats($dataFileName)
    {
        $stores = [];
        $skus = [];
        $max_quantity = 0;
        $sum_quantity = 0;
        $availability_rate = 0;
        $nb_availability = 0;
        $nb_line = 0;
        $storesCollection = new Collection();
        $generator = self::readFile($dataFileName);

        foreach ($generator as $line)
        {
            $nb_line++;
            $dataLine = explode(",", $line);
            $storeId = (int)$dataLine[0];
            $skuId = (int)$dataLine[1];
            $quantity = (int)$dataLine[2];

            $stores[$storeId] = null;
            $skus[$skuId] = null;  

            $max_quantity = max($quantity, $max_quantity);
            $sum_quantity += $quantity;
            if ($quantity > 0) $nb_availability++;

            // si le store n'existe pas dans notre collection, on le crée
            if (!$storesCollection->hasKey($storeId)) $storesCollection->set($storeId, new Store($storeId));
            $store = $storesCollection->get($storeId);
            $store = self::fillStore($storeId, $skuId, $quantity, $store);
            $storesCollection->set($storeId, $store); 
        }

        $avg_quantity = $sum_quantity / $nb_line;
        $availability_rate = $nb_availability / $nb_line;

        $storesResult = [];

        foreach($storesCollection->getItems() as $store) 
        {
            $storesResult[$store->getId()] = $store->serialize();
        }

        $results = [
            "store_count" => count($stores),
            "sku_count" => count($skus),
            "max_quantity" => $max_quantity,
            "avg_quantity" => $avg_quantity,
            "availability_rate" => $availability_rate,
            "stores" => $storesResult
        ];

        return $results;
    }
}

?>