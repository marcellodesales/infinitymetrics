<?php

require_once 'infinitymetrics/util/AbstractCollection.class.php';
require_once 'infinitymetrics/util/IndexedList.interface.php';
/**
 *
 * Implementation of an Array list driven by Indexes. It's a generalization
 * of the Abstract Collection, with the addition of the access through the
 * indexes.
 *
 * Additions of elements can replace existing elements (addAt) or merge another
 * collection of elements (array)
 *
 * @author Marcello de Sales <marcello.sales@gmail.com> Nov 10, 2008 00:13 PST
 * @version $Id$
 */
class ArrayList extends AbstractCollection implements IndexedList {

    /**
     * Constructs a new array with the initial capacity. All positions are going
     * to be null and, therefore, needs to be assigned with an instance.
     * @param int $initialCapacity is the initial capacity of the Array List
     */
    protected function  __construct($initialCapacity) {
        $i = 0;
        while (++$i <= $initialCapacity) {
            $this->elements[] = null;
        }
    }
    /**
     * Inserts an element in the indexed position specified
     * @param int $index is the position of the item to be inserted
     * @param object $newElement the element to be inserted
     */
    public function addAt($index, $newElement) {
        if ($index < 0 || $this->size() < $index) {
            throw new Exception("ArrayIndexOutOfBounds: " . $index . " for current value " . $this->size());
        }
        $this->elements[$index] = $newElement;
    }
    /**
     * Adds all elements from a given collection to the current collection on an
     * specified index.
     * @param int $index is the position of the collection to be inserted
     * @param array $collection is the position of the collection to be
     * inserted.
     */
    public function addAllAt($index, $collection) {
        $prefixArray = $this->elements;
        array_splice($prefixArray, $index);

        $partial = array_merge($prefixArray, $collection);

        $sufixArray = $this->elements;
        array_slice($sufixArray, $index);

        $this->elements = array_merge($partial, $sufixArray);
    }
    /**
     * @param object $element is the element to be found.
     * @return The int position of the given element. Returns -1 if the given
     * element is not found.
     */
    public function indexOf($element) {
        for ($i = 0; $i < $this->size(); $i++) {
            if ($this->elements[$i] == $element)
                return $i;
        }
        return -1;
    }
    /**
     * @param int $index is the position of the element.
     * @return The element that is in the position specified by the given $index.
     */
    public function get($index) {
        return $this->elements[$index];
    }
    /**
     * @return Creates a new instance of an IndexedList
     * @param int $inicialCapacity is the initial amount of elements on the array.
     */
    public static function makeNewInstance($inicialCapacity) {
        return new ArrayList($inicialCapacity);
    }
}
?>
