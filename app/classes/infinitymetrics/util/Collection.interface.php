<?php

/**
 * The Collection interface defines the methods for a given collection of
 * objects without restrictions on indexes, etc.
 *
 * @author Marcello de Sales <marcello.sales@gmail.com>
 * @version $Id$
 */
interface Collection {

    /**
     * Adds an element to the list
     * @param string $title
     * @return the instance of this builder.
     */
    public function add($newElement);
    /**
     * Adds all elements from a given collection to the current collection
     * @param string $collection
     */
    public function addAll(Collection $collection);
    /**
     * Removes an existing item from the list
     * @param string $elementToRemove is the element ot be removed from the
     * collection. It returns the current list if the given element is not found.
     */
    public function remove($elementToRemove);
    /**
     * @return The size of the collection.
     */
    public function size();
    /**
     * @return The complemete list of elements.
     */
    public function getElements();
    /**
     * @return The complemete list of elements.
     * @param string element is the element that's trying to be found
     */
    public function contains($element);
    /**
     * @return if the size of the collection is zero.
     */
    public function isEmpty();
    /**
     * @return Removes all elements from teh list.
     */
    public function clear();
}
?>
