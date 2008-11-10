<?php

require_once 'infinitymetrics/util/Collection.interface.php';
/**
 * The AbstractCollection the implementation of a given list with items that
 * has doesn't have an index, nor are sorted.
 *
 * @author Marcello de Sales <marcello.sales@gmail.com>
 * @version $Id$
 */
class AbstractCollection implements Collection {

    /**
     * The elements of the collection.
     * @var string
     */
    protected $elements;
    /**
     * Constructs a new abstract collection with an empty list 
     */
    protected function  __construct() {
        $this->elements = array();
    }
    /**
     * Adds an element to the list
     * @param string $title
     * @return the instance of this builder.
     */
    public function add($newElement) {
        $this->elements[] = $newElement;
    }
    /**
     * Adds all elements from a given collection to the current collection
     * @param string $collection
     */
    public function addAll(Collection $collection) {
        $this->elements = array_merge($this->elements, $collection->getElements());
    }
    /**
     * Removes an existing item from the list
     * @param string $elementToRemove is the element ot be removed from the
     * collection. It returns the current list if the given element is not found.
     */
    public function remove($elementToRemove) {
        $tempList = array();
        foreach($this->elements as $value) {
            if ($value != $elementToRemove)
                $tempList[] = $value;
        }
        $this->elements = $tempList;
    }
    /**
     * @return The size of the collection.
     */
    public function size() {
        return sizeof($this->elements);
    }
    /**
     * @return The complemete list of elements.
     */
    public function getElements() {
        return $this->elements;
    }
    /**
     * @return The complemete list of elements.
     * @param string element is the element that's trying to be found
     */
    public function contains($element) {
        foreach($this->elements as $value) {
            if ($value == $element)
                return true;
        }
        return false;
    }
    /**
     * @return if the size of the collection is zero.
     */
    public function isEmpty() {
        return $this->size() == 0;
    }
    /**
     * @return Removes all elements from teh list.
     */
    public function clear() {
        $this->elements = array();
    }
    /**
     * @return A new instance of the collection.
     */
    public static function makeNewInstance() {
        return new AbstractCollection();
    }
}
?>
