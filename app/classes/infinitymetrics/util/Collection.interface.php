<?php

/**
 * The Collection interface defines the methods for a given collection of
 * objects without restrictions on indexes, etc.
 *
 * @author Marcello de Sales <marcello.sales@gmail.com> Nov 09, 2008 22:34 PST
 * @version $Id$
 */
interface Collection {
    /**
     * Adds an element to the list
     * @param object $newElement is an object to be added to the list
     */
    public function add($newElement);
    /**
     * Adds all elements from a given collection to the current collection
     * @param Collection $collection the collection of items to add
     */
    public function addAll(Collection $collection);
    /**
     * Removes an existing item from the list
     * @param object $elementToRemove is the element ot be removed from the
     * collection.
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
     * @param object element is the element that's trying to be found
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
