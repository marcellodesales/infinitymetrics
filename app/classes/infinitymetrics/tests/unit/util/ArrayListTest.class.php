<?php
require_once 'PHPUnit/Framework.php';
require_once 'infinitymetrics/util/ArrayList.class.php';
/**
 * Tests for the ArrayList class.
 *
 * @author Marcello de Sales (marcello.sales@gmail.com) Nov 09, 2008 22:34 PST
 */
class ArrayListTest extends PHPUnit_Framework_TestCase {

    private $subject;
    const INITIAL_CAPACITY = 3;

    protected function setUp() {
        parent::setUp();
        $this->subject = ArrayList::makeNewInstance(self::INITIAL_CAPACITY);
    }

    public function testCollectionCreation() {
        $this->assertNotNull($this->subject);
        $newCollection = ArrayList::makeNewInstance(self::INITIAL_CAPACITY);
        $this->assertNotSame($this->subject, $newCollection);
        for($i=0; $i < $this->subject->size(); $i++) {
            $this->assertSame(null, $this->subject->get($i));
        }
    }

    public function testManipulationWithIndexes() {
        //asserting the creation is empty
        $this->assertEquals(false, $this->subject->isEmpty());
        $this->assertEquals(self::INITIAL_CAPACITY, $this->subject->size());
        //adding items
        $this->subject->addAt(1, "de Sales");
        $this->subject->addAt(0, "Marcello");
        //asserting the size of the collection changed and is not empty
        $this->assertEquals(self::INITIAL_CAPACITY, $this->subject->size());
        $this->assertEquals(false, $this->subject->isEmpty());
        //verifying the elements
        $this->assertTrue($this->subject->contains("Marcello"));
        $this->assertTrue($this->subject->contains("de Sales"));
        $this->assertEquals(0, $this->subject->indexOf("Marcello"));
        $this->assertEquals(1, $this->subject->indexOf("de Sales"));
    }

    public function testAddAllWithClearIndexes() {
        //asserting the creation is empty
        $this->subject->clear();
        $this->assertEquals(true, $this->subject->isEmpty());
        $this->assertEquals(0, $this->subject->size());
        //adding items
        $elements = array();
        $elements[] = "third";
        $elements[] = "fourth";

        $element3 = array();
        $element3[] = "first";
        $element3[] = "second";

        $this->subject->addAllAt(2, $elements);
        $this->assertEquals(sizeof($elements), $this->subject->size());
        $this->subject->addAllAt(0, $element3);
        $this->assertEquals(sizeof($elements) * 2, $this->subject->size());
    }

    protected function tearDown() {
        $this->subject = null;
    }
}
?>
