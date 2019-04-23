<?php

namespace Socloz\Recruitment\Ex1;

/**
 * Class Store
 * @package Socloz\Recruitment\Ex1
 */
class Store
{
    private $id = 0;
    private $max_quantity = 0;
    private $avg_quantity = 0;
    private $skus = [];
    private $available_sku_count = 0;
    private $available_rate = 0;
    private $nb_occurence = 0;
    private $sum_quantity = 0;
    private $nb_availability = 0;

    /**
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @param int quantity
     * @return void
     */
    public function calculMaxQuantity($quantity) : void
    {
        $this->max_quantity = max([(int)$quantity, $this->max_quantity]);
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    public function setSku($key, $value) : void
    {
        $this->skus[$key] = $value;
    }

    public function countSkus() : int
    {
        return count($this->skus);
    }

    public function getMaxQuantity() : int
    {
        return $this->max_quantity;
    }

    public function getSumQuantity() : int
    {
        return $this->sum_quantity;
    }

    public function addNbOccurrence() : void
    {
        $this->nb_occurence++;
    }

    public function addQuantity($quantity) : void
    {
        $this->sum_quantity += (int)$quantity;
    }

    public function calculeAverageQuantity() : float
    {
        return ($this->sum_quantity / $this->nb_occurence);
    }

    public function addNbAvailability($availabality) : void
    {
        if ($availabality > 0) $this->nb_availability++;
    }

    public function calculeAvailabilityRate() : float
    {
        return ($this->nb_availability / $this->nb_occurence);
    }

    public function serialize() : array
    {
        return 
        [
            "max_quantity" => $this->getMaxQuantity(),
            "avg_quantity" => $this->calculeAverageQuantity(),
            "availability_rate" => $this->calculeAvailabilityRate(),
            "available_sku_count" => $this->countSkus()
        ];
    }
}
?>