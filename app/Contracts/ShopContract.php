<?php

namespace App\Contracts;

interface ShopContract
{
    public function getAllShopsWithDues($date);
    public function getDateWiseSalesData($startDate = null, $endDate = null);
}
