<?php

/**
 * Description of Institution
 *
 * @author Andres Ardila
 */
class Institution {

    private $abbreviation;
    private $name;
    private $city;
    private $state_province;
    private $country;

    public function builder($abbreviation,
                            $name,
                            $city,
                            $state_province,
                            $country)
    {
        $this->abbreviation = $abbreviation;
        $this->name = $name;
        $this->city = $city;
        $this->state_province = $state_province;
        $this->country = $country;
    }

    public function getAbbreviation() {
        return $this->abbreviation;
    }

    public function getName() {
        return $this->name;
    }

    public function getCity() {
        return $this->city;
    }

    public function getStateProvince() {
        return $this->state_province;
    }

    public function getCountry() {
        return $this->country;
    }

    public function setAbbreviation($abbrv) {
        $this->abbreviation = $abbrv;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setCity($city) {
        $this->city = $city;
    }

    public function setStateProvince($state_prov) {
        $this->state_province = $state_prov;
    }

    public function setCountry($country) {
        $this->country = $country;
    }
}
?>
