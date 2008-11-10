<?php
/**
 * $Id: AbstractCollection.class.php 202 2008-11-10 00:12:40Z marcellosales $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITYs, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the Berkeley Software Distribution (BSD).
 * For more information please see <http://ppm-8.dev.java.net>.
 */
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
