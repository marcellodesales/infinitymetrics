<?php

/**
 * Implementation of the indexed list based on the index of the elements
 *
 * @author Marcello de Sales <marcello.sales@gmail.com> Nov 10, 2008 00:13 PST
 * @version $Id$
 */
interface IndexedList extends Collection {
    /**
     * Inserts an element in the indexed position specified
     * @param int $index is the position of the item to be inserted
     * @param object $newElement
     */
    public function addAt($index, $newElement);
    /**
     * Adds all elements from a given collection to the current collection on an
     * specified index.
     * @param int $index is the position of the collection to be inserted
     * @param array $collection is the position of the collection to be
     * inserted.
     */
    public function addAllAt($index, $collection);
    /**
     * @param object $element is the element to be found.
     * @return The int position of the given element. Returns -1 if the given
     * element is not found.
     */
    public function indexOf($element);
    /**
     * @param int $index is the position of the element.
     * @return The element that is in the position specified by the given $index.
     */
    public function get($index);
}
?>
